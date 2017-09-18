<?php
//include config
require_once('admin/config.php');

$title = 'Public Collection';

if (isset($_GET['username'])) {
	$stmt = $db->prepare('SELECT memberID, fname, publicColl FROM users WHERE username = :username');
	$stmt->execute(array(':username' => $_GET['username']));
	$urow = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!empty($urow['memberID']) && $urow['publicColl'] == 1){

		$title = $urow['fname'] . "&rsquo;s Collection";
		$pagetype = 'pubcoll';
		$pageclass = 'collection';
		$getID = $urow['memberID'];
		//include header template
		require('resources/php/header.php');

?>

<header id="MainHeader">
	<div class="headerTitle"><?=$title?></div>
</header>
<div id="ShelvesCont"></div>
<div id="BookDetails" class="popUp">
	<header>
		<div class="headerTitle">Details</div>
		<button id="BkDetailsClose" class="modalClose" onclick="bkdHide()"></button>
	</header>
	<div id="BKDetailsBody">
			<div class="bookImage"><div class="bookItem"><div class="bookCover"></div></div></div>
      <ul>
        <li><input name="title" id="BKDTitle" placeholder="Title" /></li>
        <li id="BKDSerNo"><input placeholder="Series" /><div id="BookNoCont"><input type="number" placeholder="No." max="999" /></div></li>
        <li><input name="author" id="BKDAuthor" placeholder="Author" /></li>
        <li><label for="BKDPublisher">Publisher</label><input name="publisher" id="BKDPublisher" /></li>
        <li><label for="BKDYear">Year</label><input name="year" id="BKDYear" type="number" max="2560" /></li>
        <li><label for="BKDMedium">Medium</label><input name="medium" id="BKDMedium" /></li>
        <li><label for="BKDGenre">Genre</label><input name="genre" id="BKDGenre" /></li>
        <li></li>
        <li><label for="BKDOwner">Owner</label><input /></li>
        <li><label for="BKDRating">Rating</label><input type="range" min="0" max="5" step="0.5" value="0" /></li>
      </ul>
		</div>
</div>

<?
	} else {
	$title = 'Not Found';
	$pageclass = "sysPage";

	//include header template
	require('resources/php/header.php');
?>

<div class="blurUnderlayFix"></div>
<div class="blurOverlay" style="display: block;"></div>
<header id="MainHeader"></header>
<div class="vertAlignWrap">
	<div class="vertAlignCont">
		<div class="libLogo"></div>
		<h2>Collection Not Found</h2>
		<p>The Collection you are looking for cannot be found, or isn&rsquo;t available for public viewing</p>
	</div>
</div>
<?php
	}
} else {
	header('Location: collection');
}
//include header template
require('resources/php/footer.php');
?>
