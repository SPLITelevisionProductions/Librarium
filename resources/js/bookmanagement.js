var currUsr;
var currBk;

$(document).ready(function() {
  if (window.navigator.standalone == true) {
    $('html').addClass('webAppMode');
  }

  $('#BKDEditBook').submit(function(event) {

    var formData = {
      'id':         $('#BKDID').val(),
      'series':     $('#BKDSeries').val(),
      'title':      $('#BKDTitle').val(),
      'bookno':     $('#BKDBookNo').val(),
      'author':     $('#BKDAuthor').val(),
      'publisher':  $('#BKDPublisher').val(),
      'year':       $('#BKDYear').val(),
      'genre':      $('#BKDGenre').val(),
      'medium':     $('#BKDMedium').val(),
      'notes':      $('#BKDNotes').val()
    };

    console.log($('#BKDNotes').val());

    $.ajax({
      method    : 'POST',
      url       : '/admin/libmanagement/bookDetails.php',
      data      : formData,
      dataType  : 'html',
      success   : function(result){
        $("#BKDetailsBody").html(result);
        getBooks(currUsr);
        $('#BookDetails').removeClass('editable');
      }
    });

    event.preventDefault();
  });
});

function getBooks(id) {

  currUsr = id;
  console.log(currUsr);

  $.ajax({
    method    : 'GET',
    url       : '/admin/libmanagement/getBooks.php',
    data      : 'id=' + id,
    dataType  : 'html',
    success   : function(result){
      $("#ShelvesCont").html(result);
    }
  });
}

function addBook() {

  $.ajax({
    method    : 'GET',
    url       : '/admin/libmanagement/bookDetails.php',
    data      : 'new=yes',
    dataType  : 'html',
    success   : function(result){
      $("#BKDetailsBody").html(result);
      getBooks(currUsr);
    }
  });

  $('#BookDetails').addClass('visible');
  $('#BookDetails').addClass('editable');
  $('#BlurOverlay').fadeIn();

}

function saveBook() {

  var formData = {
    'id':         $('#BKDID').val(),
    'series':     $('#BKDSeries').val(),
    'title':      $('#BKDTitle').val(),
    'bookno':     $('#BKDBookNo').val(),
    'author':     $('#BKDAuthor').val(),
    'publisher':  $('#BKDPublisher').val(),
    'year':       $('#BKDYear').val(),
    'genre':      $('#BKDGenre').val(),
    'medium':     $('#BKDMedium').val(),
    'notes':      $('#BKDNotes').val()
  };

  $.ajax({
    method    : 'POST',
    url       : '/admin/libmanagement/bookDetails.php',
    data      : formData,
    dataType  : 'html',
    success   : function(result){
      $("#BKDetailsBody").html(result);
      getBooks(currUsr);
      $('#BookDetails').removeClass('editable');
    }
  });
}

function deleteBook() {

  var id = $('#BKDID').val();

  $.ajax({
    method    : 'GET',
    url       : '/admin/libmanagement/bookDetails.php',
    data      : 'delete=' + id,
    dataType  : 'html',
    success   : function(result){
      bkdHide();
      getBooks(currUsr);
    }
  });
}

function copyBook() {

  var id = $('#BKDID').val();

  $.ajax({
    method    : 'GET',
    url       : '/admin/libmanagement/bookDetails.php',
    data      : 'duplicate=' + id,
    dataType  : 'html',
    success   : function(result){
      $("#BKDetailsBody").html(result);
      $('#BookDetails').addClass('editable');
      getBooks(currUsr);
    }
  });
}

function uploadCover() {

  var id = $('#BKDID').val();
  console.log(id);
  var fileData = $('#BKDCoverUp').prop('files')[0];
  var formData = new FormData();
  formData.append('file',fileData);
  //formData.append('id', id);

  $.ajax({
    url: '/admin/libmanagement/bookDetails.php?upload=' + id,
    dataType: 'html', // what to expect back from the PHP script
    cache: false,
    contentType: false,
    processData: false,
    data: formData,
    type: 'post',
    success: function(data){
      bkdCrop(id);
    }
  });

}

function bkdShow(id) {

  console.log(id);

  $('#BookDetails').removeClass('hidden');
  $('#BookDetails').addClass('visible');
  $('#BlurOverlay').fadeIn();

  $.ajax({
    method    : 'GET',
    url       : '/admin/libmanagement/bookDetails.php',
    data      : 'id=' + id,
    dataType  : 'html',
    async     : false,
    success   : function(result){ $("#BKDetailsBody").html(result); }
  });
}

function bkdHide() {
  $('#BookDetails').removeClass('visible');
  $('#BookDetails').removeClass('editable');
  $('#BlurOverlay').fadeOut();
}

function bkdCrop(id) {
  var image = '/images/books/' + id + '_original.jpg';
  var img = '<img id="BKDCropImage' + id + '" onload="runJcrop(' + id + ')" src="' + image + '"></img>'
  $('#BKDCropImageWrap').html(img);
}

function runJcrop(id) {
  var image = document.getElementById('BKDCropImage' + id)
  /*$('#BKDCropImage' + id).Jcrop({
    boxWidth: 320,
    setSelect: [ 20,20,100,100 ],
    onSelect: showCoords,
    onChange: showCoords
  });*/
  var cropper = new Cropper(image, {
    strict: true,
    viewMode: 1,
    zoomable: false,
    rotatable: false,
    movable: false,
    autoCropArea: 0.3,
    crop: function(e) {
      $('#BKDCropX').val(e.detail.x);
      $('#BKDCropY').val(e.detail.y);
      $('#BKDCropW').val(e.detail.width);
      $('#BKDCropH').val(e.detail.height);
  }
});
  $('#BookCrop').addClass('visible');
}

function showCoords(c) {
      // variables can be accessed here as
      // c.x, c.y, c.x2, c.y2, c.w, c.h
      $('#BKDCropX').val(c.x);
      $('#BKDCropY').val(c.y);
      $('#BKDCropW').val(c.w);
      $('#BKDCropH').val(c.h);
};

function saveCrop() {
  var id = $('#BKDID').val();
  var formData = {
    'cropid':         $('#BKDID').val(),
    'cropx':     $('#BKDCropX').val(),
    'cropy':      $('#BKDCropY').val(),
    'cropw':     $('#BKDCropW').val(),
    'croph':     $('#BKDCropH').val()
  };

  $.ajax({
    method    : 'POST',
    url       : '/admin/libmanagement/bookDetails.php',
    data      : formData,
    dataType  : 'html',
    success   : function(result){
      setTimeout(function(){
        saveBook();
        getBooks(currUsr);
        setTimeout(function(){
          $('#BookDetails').addClass('editable');
          $('#BookCrop').removeClass('visible');
          //$('#BKDCropImage' + id).data('Jcrop').destroy();
          $('#BKDCropImageWrap').html('');
          //location.reload();
        }, 1000);
      }, 3000);
    }
  });
}

function cancelCrop() {
  $('#BookCrop').removeClass('visible');
  $('#BKDCropImageWrap').html('');
}

function logOut() {
  window.location = '/logout';
}
