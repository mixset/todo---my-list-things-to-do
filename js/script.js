// Version: v1.0                  

function message(msg) {
 return $('#log').addClass("well pull-center").html('<span class="label label-success" style="padding:5px;font-size:medium;">' + msg + '</span>');
}

function refreshWindow() {
 setTimeout( function() { window.location.href = window.location.href } , 1000);
}

$(document).ready(function() {

 // Tooltip
 $(".table a, .table button").tooltip(
 {
  placement: 'bottom'
 });

 // Add new record
 $('#submit_add').click(function() {

  var notetext = $("#notetext").val();
  var timeout  = $("#timeout").val();
  var datatext = '&text=' + notetext + '&timeout=' + timeout + '&action=add_new';

  var note = $.ajax({
   type: "POST",
   url: "action.php",
   data: datatext,
  })

  note.done(function(msg) {
   refreshWindow();
   message(msg);
  });

 });


 $('.done_note').click(function() {

  var done_note_id = this.value;

  var note = $.ajax({
   type: "POST",
   url: "action.php",
   data: '&id=' + done_note_id + '&action=done',
  })

  note.done(function(msg) {
   refreshWindow();
   message(msg);
  });
  
 });

  // Delete record
  $('.delete_note').click(function() {

   var delete_note_id = this.value;

   var note = $.ajax({
    type: "POST",
    url: "action.php",
    data: '&id=' + delete_note_id + '&action=delete',
   })

  note.done(function(msg) {
   refreshWindow();
   message(msg);
  });
 
 });

  // Active record to status 1
  $('.current_note').click(function() {

   var current_note_id = this.value;

   var note = $.ajax({
    type: "POST",
    url: "action.php",
    data: '&id=' + current_note_id + '&action=current',
   })
 
   note.done(function(msg) {
    refreshWindow();
    message(msg);
  });

 });

  // Update records 
  $('#submit_edit').click(function() {
   var text     =  $("#notetext_edit").val();
   var timeout  =  $("#timeout_edit").val();
   var id       = $("#submit_edit").val();
   var datatext = '&id=' + id + '&text=' + text + '&timeout=' + timeout + '&action=edit';

   var note = $.ajax({
    type: "POST",
    url: "action.php",
    data: datatext,
   })

   note.done(function(msg) {
    refreshWindow();
    message(msg);
   });
  
 });

});




