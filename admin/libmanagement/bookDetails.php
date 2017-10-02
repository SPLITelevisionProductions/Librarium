<?php
    //include config
    require_once('../config.php');

    ini_set('memory_limit', '512M');

    function image_fix_orientation($filename) {
    $exif = exif_read_data($filename);
    if (!empty($exif['Orientation'])) {
        $image = imagecreatefromjpeg($filename);
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
                echo "Case 3";
                break;

            case 6:
                $image = imagerotate($image, -90, 0);
                echo "Case 6";
                break;

            case 8:
                $image = imagerotate($image, 90, 0);
                echo "Case 8";
                break;
        }

        imagejpeg($image, $filename, 80);
    } else {
      echo "Cant get Orientation";
    }
}

    if(isset($_GET['new'])) {
      try {

  			//insert into database with a prepared statement
  			$stmt = $db->prepare('INSERT INTO books (Collector) VALUES (:userID)');
        $stmt->execute(array(
  			     ':userID' => $_COOKIE['id']
  		  ));
        $bookID = $db->lastInsertId('Collector');
      } catch(PDOException $e) {
        $error[] = $e->getMessage();
      }
    } elseif (isset($_GET['duplicate'])) {
      $dupID = $_GET['duplicate'];

      try {
        $sstmt = $db->prepare('SELECT * FROM books WHERE ID = :bookID');
  		  $sstmt->execute(array(
  			     ':bookID' => $dupID
  		  ));

  		  $book = $sstmt->fetch();

        $bookTitle = $book['Title'] . " Copy";

  			//insert into database with a prepared statement
  			$stmt = $db->prepare('INSERT INTO books (Series, Title, BookNo, Author, Publisher, Year, Genre, Medium, OwnerID, Notes, Collector) VALUES (:series, :title, :bookno, :author, :publ, :year, :genre, :medium, :owner, :notes, :userID)');
        $stmt->execute(array(
          ':series' => $book['Series'],
          ':title'  => $bookTitle,
          ':bookno' => $book['BookNo'],
          ':author' => $book['Author'],
          ':publ'   => $book['Publisher'],
          ':year'   => $book['Year'],
          ':genre'  => $book['Genre'],
          ':medium' => $book['Medium'],
          ':owner'  => $book['OwnerID'],
          ':notes'  => $book['Notes'],
          ':userID' => $_COOKIE['id']
  		  ));
        $bookID = $db->lastInsertId('Collector');
      } catch(PDOException $e) {
        $error[] = $e->getMessage();
      }
    } elseif(isset($_POST['title'])) {
      try {
        echo $_POST['owner'];
        $stmt = $db->prepare('UPDATE books SET Series = :series, Title = :title, BookNo = :bookno, Author = :author, Publisher = :publ, Year = :year, Genre = :genre, Medium = :medium, OwnerID = :owner, Notes = :notes WHERE ID = :bookID');
        $stmt->execute(array(
          ':series' => $_POST['series'],
          ':title'  => $_POST['title'],
          ':bookno' => $_POST['bookno'],
          ':author' => $_POST['author'],
          ':publ'   => $_POST['publisher'],
          ':year'   => $_POST['year'],
          ':genre'  => $_POST['genre'],
          ':medium' => $_POST['medium'],
          ':owner'  => $_POST['owner'],
          ':bookID' => $_POST['id'],
          ':notes'  => $_POST['notes']
        ));
        $bookID = $_POST['id'];
      } catch(PDOException $e) {
        echo $e->getMessage();
      }
    } elseif(isset($_GET['id'])) {
      $bookID = $_GET['id'];
    } elseif(isset($_GET['delete'])) {
      $deleteID = $_GET['delete'];

      try {

  			//insert into database with a prepared statement
  			$stmt = $db->prepare('DELETE FROM books WHERE ID = :bookID');
        $stmt->execute(array(
  			     ':bookID' => $deleteID
  		  ));
      } catch(PDOException $e) {
        $error[] = $e->getMessage();
      }
    } elseif(isset($_FILES["file"]["type"]) && isset($_GET['upload'])) {

      $bookID = $_GET['upload'];

      $sourcePath = $_FILES['file']['tmp_name'];
      $targetPath = "../../images/books/".$bookID.'_original.jpg';

      $imgtest_info = getimagesize($sourcePath);
      echo $imgtest_info[2];

      if (($img_info = getimagesize($sourcePath)) === FALSE) {
        die("Image not found or not an image");
      }

      $width = $img_info[0];
      $height = $img_info[1];

      if ($img_info[2] != IMAGETYPE_JPEG) {

        switch ($img_info[2]) {
          case IMAGETYPE_GIF  : $src = imagecreatefromgif($sourcePath);  break;
          case IMAGETYPE_PNG  : $src = imagecreatefrompng($sourcePath);  break;
          default : die("Unknown filetype");
        }

        $tmp = imagecreatetruecolor($width, $height);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
        imagejpeg($tmp, $targetPath);

      } elseif ($img_info[2] == IMAGETYPE_JPEG) {
        move_uploaded_file($sourcePath, $targetPath);
      }

      image_fix_orientation($targetPath);

    } elseif(isset($_POST['cropid'])) {
      $targ_s = 600;
      $jpeg_quality = 80;

      $croph = $_POST['croph'];
      $cropw = $_POST['cropw'];

      if ($cropw < $croph) {
        $targ_h = ($croph / $croph) * $targ_s;
        $targ_w = ($cropw / $croph) * $targ_s;
      } else {
        $targ_w= ($cropw / $cropw) * $targ_s;
        $targ_h = ($croph / $cropw) * $targ_s;
      }

      $src = '../../images/books/'.$_POST['cropid'].'_original.jpg';
      $target = '../../images/books/'.$_POST['cropid'].'.jpg';
      $img_r = imagecreatefromjpeg($src);
      $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

      imagecopyresampled($dst_r,$img_r,0,0,$_POST['cropx'],$_POST['cropy'],$targ_w,$targ_h,$cropw,$croph);

      imagejpeg($dst_r, $target, $jpeg_quality);

      try {
        $stmt = $db->prepare('UPDATE books SET imgwidth = :cropw, imgheight = :croph WHERE ID = :bookID');
        $stmt->execute(array(
          ':cropw' => $_POST['cropw'],
          ':croph' => $_POST['croph'],
          ':bookID' => $_POST['cropid']
        ));
        $bookID = $_POST['cropid'];
      } catch(PDOException $e) {
        echo $e->getMessage();
      }

    } else {
      include('../error/403.php');
      die();
    }

    if (isset($bookID)) {
      try {
      $stmt = $db->prepare('SELECT * FROM books WHERE ID = :bookID');
		  $stmt->execute(array(
			     ':bookID' => $bookID
		  ));

		  $book = $stmt->fetch();
		  $bookcnt = count($book);

      if ($book['OwnerID'] != "") {
        $ostmt = $db->prepare('SELECT Colour FROM owners WHERE ID = :ownid');
        $ostmt->execute(array(
             ':ownid' => $book['OwnerID']
        ));

        $colour = $ostmt->fetch();
      }

      ?>
      <!DOCTYPE html>
      <html>
      <body>
        <div class="bookImage">
        		<div id="Book<?=$book['ID']?>Det" class="bookItem">
              <?
              $imgwidth = $book['imgwidth'];
              $imgheight = $book['imgheight'];

              if ($book['imgwidth'] != "") {
                if ($imgwidth < $imgheight) {
                  $imgheightper = ($imgheight / $imgheight) * 100;
                  $imgwidthper = ($imgwidth / $imgheight) * 100;
                } else {
                  $imgwidthper = ($imgwidth / $imgwidth) * 100;
                  $imgheightper = ($imgheight / $imgwidth) * 100;
                }
              }
              ?>
              <? if ($book['imgwidth'] != "") { ?><div class="bookCover" style="background-image: url(/images/books/<?=$book['ID']?>.jpg); width: <?=$imgwidthper?>%; height: <?=$imgheightper?>%; <? if ($book['OwnerID'] != '') {?>border-color: #<?=$colour['Colour']?>;<?}?>" title="<?=$row['Title']?>">
            <? } else { ?>
              <div class="bookCover" <? if ($book['OwnerID'] != '') {?>style="border-color: #<?=$colour['Colour']?>;"<?}?> title="<?=$row['Title']?>">><? } ?>
                 <? if ($book['LoanTo'] != "") { ?><div class="loanBanner">LOAN</div><? } ?>
                 <input type="file" id="BKDCoverUp" name="coverimage" accept="image/*" onchange="uploadCover()" />
              </div>
            </div>
        </div>
        <ul>
          <input type="hidden" id="BKDID" name="id" value="<?=$book['ID']?>" />
          <li id="BKDTitleWr"><span class="bkdVOnly"><?=$book['Title']?></span><span class="bkdEOnly"><input name="title" id="BKDTitle" placeholder="Title" value="<?=$book['Title']?>" /></span></li>
          <li id="BKDSerNo"><span class="bkdVOnly"><b><?=$book['Series']?></b> <?if($book['BookNo'] != "") {?>#<?} echo $book['BookNo']?></span><span class="bkdEOnly"><input name="series" id="BKDSeries" placeholder="Series" value="<?=$book['Series']?>" /><div id="BookNoCont"><input id="BKDBookNo" name="bookno" placeholder="No." value="<?=$book['BookNo']?>" /></div></span></li>
          <li id="BKDAuthorWr"><span class="bkdVOnly"><?=$book['Author']?></span><span class="bkdEOnly"><input name="author" id="BKDAuthor" placeholder="Author" value="<?=$book['Author']?>" /></span></li>
          <li><label for="BKDPublisher">Publisher</label><input name="publisher" id="BKDPublisher" placeholder="Publisher" value="<?=$book['Publisher']?>" /></li>
          <li><label for="BKDYear">Year</label><input name="year" id="BKDYear" type="number" max="2560" placeholder="Year" value="<?=$book['Year']?>" /></li>
          <li><label for="BKDMedium">Medium</label><input name="medium" id="BKDMedium" placeholder="Medium" value="<?=$book['Medium']?>" /></li>
          <li><label for="BKDGenre">Genre</label><input name="genre" id="BKDGenre" placeholder="Genre" value="<?=$book['Genre']?>" /></li>
          <li></li>
          <li><label for="BKDOwner">Owner</label><span><input name="owner" id="BKDOwner" placeholder="Owner" value="<?=$book['OwnerID']?>" /></li>
          <li><label for="BKDRating">Rating</label><span>Coming Soon</span></li>
          <li><label for="BKDNotes">Notes</label><textarea id="BKDNotes"><?=$book['Notes']?></textarea></li>
        </ul>
    <?} catch(PDOException $e) {
      $error[] = $e->getMessage();
    }
  } ?>
