<!DOCTYPE html>
<html>
<body>
  <?php
    //include config
    require_once('admin/config.php');

    if(isset($_GET['id'])) {
      $id = $_GET['id'];

      $stmt = $db->prepare('SELECT * FROM books WHERE ID = :bookID');
		  $stmt->execute(array(
			     ':bookID' => $id
		  ));

		  $book = $stmt->fetch();
		  $bookcnt = count($book);

      ?>

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
            <? if ($book['imgwidth'] != "") { ?><div class="bookCover <?=$book['OwnerColour']?>" style="background-image: url(/images/books/<?=$book['ID']?>.jpg); width: <?=$imgwidthper?>%; height: <?=$imgheightper?>%;">
          <? } else { ?>
            <div class="bookCover <?=$book['OwnerColour']?>"><? } ?>
               <? if ($book['LoanTo'] != "") { ?><div class="loanBanner">LOAN</div><? } ?>
            </div>
          </div>
      </div>
      <ul>
        <input type="hidden" value="<?=$book['ID']?>" />
        <li id="BKDTitleWr"><span class="bkdVOnly"><?=$book['Title']?></span><span class="bkdEOnly"><input name="title" id="BKDTitle" placeholder="Title" value="<?=$book['Title']?>" /></span></li>
        <li id="BKDSerNo"><span class="bkdVOnly"><b><?=$book['Series']?></b> #<?=$book['BookNo']?></span><span class="bkdEOnly"><input name="series" id="BKDSeries" placeholder="Series" value="<?=$book['Series']?>" /><div id="BookNoCont"><input type="number" placeholder="No." max="999" value="<?=$book['BookNo']?>" /></div></span></li>
        <li id="BKDAuthorWr"><span class="bkdVOnly"><?=$book['Author']?></span><span class="bkdEOnly"><input name="author" id="BKDAuthor" placeholder="Author" value="<?=$book['Author']?>" /></span></li>
        <li><label for="BKDPublisher">Publisher</label><input name="publisher" id="BKDPublisher" placeholder="Publisher" value="<?=$book['Publisher']?>" /></li>
        <li><label for="BKDYear">Year</label><input name="year" id="BKDYear" type="number" max="2560" placeholder="Year" value="<?=$book['Year']?>" /></li>
        <li><label for="BKDMedium">Medium</label><input name="medium" id="BKDMedium" placeholder="Medium" value="<?=$book['Medium']?>" /></li>
        <li><label for="BKDGenre">Genre</label><input name="genre" id="BKDGenre" placeholder="Genre" value="<?=$book['Genre']?>" /></li>
        <li></li>
        <li><label for="BKDOwner">Owner</label><input placeholder="Owner" value="<?=$book['Owner']?>" /></li>
        <li><label for="BKDRating">Rating</label><input type="range" min="0" max="5" step="0.5" value="0" /></li>
      </ul>
    <?} else {?>
      <h1>Forbidden</h1>
      <p>You are not permitted to access this resource</p>
    <?}?>
