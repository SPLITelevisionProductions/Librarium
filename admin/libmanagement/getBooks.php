<!DOCTYPE html>
<html>
<body>
  <?php
    //include config
    require_once('../config.php');

    if(isset($_GET['id'])) {
      if ($_GET['id'] == 'curr') {
        $id = $_COOKIE['id'];
      } else {
        $id = $_GET['id'];
      }

      if(!isset($_POST['search'])) {

      $stmt = $db->prepare('SELECT ID, Title, BookNo, OwnerID, LoanTo, imgwidth, imgheight FROM books WHERE Collector = :userID ORDER BY Series, CAST(BookNo AS UNSIGNED), BookNo, Title, Year');
		  $stmt->execute(array(
			     ':userID' => $id
		  ));

    } elseif(isset($_POST['search'])) {

      $search = $_POST['search'];

      $stmt = $db->prepare("SELECT ID, Title, BookNo, OwnerID, LoanTo, imgwidth, imgheight FROM books WHERE Title LIKE :search AND Collector = :userID OR Series LIKE :search AND Collector = :userID ORDER BY Series, CAST(BookNo AS UNSIGNED), BookNo, Title, Year");
      $stmt->execute(array(
			     ':userID' => $id,
           ':search' => "%$search%"
		  ));

    }

		  $books = $stmt->fetchAll();
		  $bookcnt = count($books);

      ?>

      <ul id="Shelves">
    		<? foreach ($books as $i => $row): ?>
    		<li id="Book<?=$row['ID']?>" class="bookItem">
          <?
          $imgwidth = $row['imgwidth'];
          $imgheight = $row['imgheight'];

          if ($row['imgwidth'] != "") {
            if ($imgwidth < $imgheight) {
              $imgheightper = ($imgheight / $imgheight) * 100;
              $imgwidthper = ($imgwidth / $imgheight) * 100;
            } else {
              $imgwidthper = ($imgwidth / $imgwidth) * 100;
              $imgheightper = ($imgheight / $imgwidth) * 100;
            }
          }

          if ($row['OwnerID'] != "") {
            $ostmt = $db->prepare('SELECT Colour FROM owners WHERE ID = :ownid');
    		    $ostmt->execute(array(
    			       ':ownid' => $row['OwnerID']
    		    ));

            $colour = $ostmt->fetch();
          }


          ?>
          <? if ($row['imgwidth'] != "") { ?><div class="bookCover" onclick="bkdShow(<?=$row['ID']?>)" style="background-image: url(/images/books/<?=$row['ID']?>.jpg); width: <?=$imgwidthper?>%; height: <?=$imgheightper?>%; <? if ($row['OwnerID'] != '') {?>border-color: #<?=$colour['Colour']?>;<?}?>" title="<?=$row['Title']?>">
        <? } else { ?>
          <div class="bookCover" <? if ($row['OwnerID'] != "") {?>style="border-color: <?=$colour['Colour']?>;"<?}?> onclick="bkdShow(<?=$row['ID']?>)" title="<?=$row['Title']?>"><?=$row['Title']?><? } ?>
            <? if ($row['BookNo'] != "") { ?>
              <div class="bookNoCnr"><?=$row['BookNo']?></div>
             <? }
             if ($row['LoanTo'] != "") { ?><div class="loanBanner">LOAN</div><? } ?>
          </div>
        </li>
    		<? endforeach ?>
    	</ul>

    <?} else {?>
      <h1>Forbidden</h1>
      <p>You are not permitted to access this resource</p>
    <?}?>
