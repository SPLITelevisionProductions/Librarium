<!DOCTYPE html>
<html>
<body>
  <?php
    //include config
    require_once('admin/config.php');

    if(isset($_GET['id'])) {
      $id = $_GET['id'];

      $stmt = $db->prepare('SELECT ID, Title, BookNo, OwnerColour, LoanTo, imgwidth, imgheight FROM books WHERE Collector = :userID ORDER BY Series, BookNo, Title');
		  $stmt->execute(array(
			     ':userID' => $id
		  ));

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
          ?>
          <? if ($row['imgwidth'] != "") { ?><div class="bookCover <?=$row['OwnerColour']?>" onclick="bkdShow(<?=$row['ID']?>)" style="background-image: url(/images/books/<?=$row['ID']?>.jpg); width: <?=$imgwidthper?>%; height: <?=$imgheightper?>%;" title="<?=$row['Title']?>">
        <? } else { ?>
          <div class="bookCover <?=$row['OwnerColour']?>" onclick="bkdShow(<?=$row['ID']?>)" title="<?=$row['Title']?>"><?=$row['Title']?><? } ?>
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
