<?php
//include config
require_once('../config.php');

//check if already logged in move to home page
if(!$user->is_logged_in()) {
	header('Location: /');
}

$pagetype = 'coll';
$pageclass = 'sysPage';

//define page title
$title = 'Edit Book';

//include header template
require('../../resources/php/header.php');
?>

<header id="MainHeader">
	<div class="headerTitle"><?=$title?></div>
</header>
<div class="vertAlignWrap">
	<div class="vertAlignCont">
		<div class="libLogo"></div>
		<form role="form" method="post" action="" autocomplete="off">
      <div class='succMsg'>This form allows you to edit/view any book</div>
			<input type="number" name="bookid" id="bookid" placeholder="Book ID" value="" tabindex="1">
			<div id="FormButtons">
				<input type="button" name="editbook" onclick="bkdShow($('#bookid').val())" value="Edit" tabindex="2">
			</div>
		</form>
		<div class="splitFooter"></div>
	</div>
</div>
<div id="BlurOverlay"></div>
<div id="Sidebar">
	<button id="SbClose" class="modalClose" onclick="sbHide()"></button>
	<div id="SbContents"></div>
	<div id="SbBottom">
		<input type="button" onclick="logOut()" value="Log Out" />
		<p>Librarium. PROTO &copy; Copyright 2017 SPLITelevision Productions NZ</p>
	</div>
</div>
<div id="BookDetails" class="popUp">
	<header>
		<button id="BkDetailsEdit" onclick="$('#BookDetails').addClass('editable');">Edit</button>
		<button id="BkDetailsCancel" onclick="$('#BookDetails').removeClass('editable');">Cancel</button>
		<div class="headerTitle">Details</div>
		<button id="BkDetailsClose" class="modalClose" onclick="bkdHide()"></button>
	</header>
	<form id="BKDEditBook" role="form" method="post" action="/admin/libmanagement/bookDetails.php" autocomplete="off">
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
		<input type="submit" value="Save" />
		<input type="button" onclick="copyBook()" value="Make a Copy" />
		<input type="button" onclick="deleteBook()" value="Delete" />
	</form>
</div>
<div id="BookCrop" class="popUp">
	<header>
		<button id="BkCoverSave" onclick="saveCrop()">Save</button>
		<div class="headerTitle">Crop</div>
		<button id="BkCoverCancel" onclick="cancelCrop()">Cancel</button>
	</header>
	<div id="BKDCropImageWrap"></div>
	<input id="BKDCropX" hidden />
	<input id="BKDCropY" hidden />
	<input id="BKDCropW" hidden />
	<input id="BKDCropH" hidden />
</div>
<div id="OwnerMgmt" class="popUp">
	<header>
		<button id="BkOwnersEdit" onclick="$('#BookDetails').addClass('editable');">Edit</button>
		<button id="BkDetailsCancel" onclick="$('#BookDetails').removeClass('editable');">Cancel</button>
		<div class="headerTitle">Owners</div>
		<button id="BkDetailsClose" onclick="">Done</button>
	</header>
</div>
<?php
//include header template
require('../../resources/php/footer.php');
?>
