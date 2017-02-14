$(document).ready(function()
{
    /**
     * @param msg
     * @returns {*|jQuery}
     */
    function message(msg) {
        return $('#log').addClass("well pull-center").html('<span class="label label-success" style="padding:5px;font-size:medium;">' + msg + '</span>');
    }

    /**
     * @Description: refresh current window
    */
    function refreshWindow() {
        setTimeout( function() { window.location.href = window.location.href } , 1000);
    }

    /**
     * @Description: active date picker
    */
    $('#timeout, #timeout_edit').datepicker(
    {
        format: 'dd/mm/yyyy',
        startDate: '01/01/2010',
        endDate: '12/30/2020',
        language: 'pl'
    });

    /**
     * @Description: show tooltip
    */
    $(".table a, .table button").tooltip(
    {
        placement: 'bottom'
    });

    /**
     * @Description: add new record to the database by AJAX
    */
    $('#submit_add').click(function(e)
    {
        var text     = document.getElementById('notetext').value
            ,timeout = document.getElementById('timeout').value
            ,data    =  '&text=' + text + '&timeout=' + timeout + '&action=add_new';

        $.ajax({
            type: "POST",
            url: "action.php",
            data: data
        }).done(function(msg) {
            refreshWindow();
             message(msg);
        });

    });

    /**
     * @Description: make note done.
    */
    $('.done_note').click(function()
    {
        var done_note_id = this.value;

        $.ajax({
            type: "POST",
            url: "action.php",
            data: '&id=' + done_note_id + '&action=done'
        }).done(function(msg) {
            refreshWindow();
            message(msg);
        });
    });

   /**
    * @Description: delete note from database
   */
    $('.delete_note').click(function()
    {
        var delete_note_id = this.value;

        $.ajax({
            type: "POST",
            url: "action.php",
            data: '&id=' + delete_note_id + '&action=delete'
        }).done(function(msg) {
           refreshWindow();
           message(msg);
        });
    });

    /**
     * @Description: make note active
    */
   $('.current_note').click(function()
   {
       var current_note_id = this.value;

      $.ajax({
        type: "POST",
        url: "action.php",
        data: '&id=' + current_note_id + '&action=current'
      }).done(function(msg) {
        refreshWindow();
        message(msg);
      });
    });

    /**
     * @Description: update given record
    */
    $('#submit_edit').click(function(e)
    {
        var content  = document.getElementById('notetext_edit').value
            ,timeout = document.getElementById('timeout_edit').value
            ,id      = document.getElementById('submit_edit').value
            ,data    = '&id=' + id + '&text=' + content + '&timeout=' + timeout + '&action=edit';

        $.ajax({
            type: "POST",
            url: "action.php",
            data: data
        }).done(function(msg) {
            refreshWindow();
            message(msg);
        });
      e.preventDefault();
    });
});




