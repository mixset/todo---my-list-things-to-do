$(document).ready(function()
{
    /**
     *
     * @type {string}
    */
    var jsonPath = 'storage/message.json';

    /**
     * Handler for AJAX requests
    */
    var request;

    /**
     * @param type
     * @param message
     */
    function message(type, message) {
        if (type) {
            alertify.log(message);
        } else {
            alertify.error(message);
        }
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
        var data = '&text=' + $("#notetext").val() + '&timeout=' + $("#timeout").val() + '&action=add';

        request = $.ajax({
            type: "POST",
            url: "index.php",
            data: data
        });

        request.done(function() {
            $.getJSON(jsonPath, function(result) {
                message(result['type'], result['message']);
                refreshWindow();
            });
        });

        e.preventDefault();
    });

    /**
     * @Description: make note done.
    */
    $('.done_note').click(function(e)
    {
        request = $.ajax({
            type: "POST",
            url: "index.php",
            data: '&id=' + this.value + '&action=done'
        });

        request.done(function() {
            $.getJSON(jsonPath, function(result) {
                message(result['type'], result['message']);
                refreshWindow();
            });
        });

        e.preventDefault();
    });

   /**
    * @Description: delete note from database
   */
    $('.delete_note').click(function(e)
    {
        var request = $.ajax({
            type: "POST",
            url: "index.php",
            data: '&id=' + this.value + '&action=delete'
        });

        request.done(function() {
            $.getJSON(jsonPath, function(result) {
                message(result['type'], result['message']);
                refreshWindow();
            });
        });

        e.preventDefault();
    });

    /**
     * @Description: make note active
    */
   $('.current_note').click(function(e)
   {
       request = $.ajax({
          type: "POST",
          url: "index.php",
          data: '&id=' + this.value + '&action=current'
      });

      request.done(function() {
           $.getJSON(jsonPath, function(result) {
               message(result['type'], result['message']);
               refreshWindow();
           });
      });


      e.preventDefault();
    });

    /**
     * @Description: update given record
    */
    $('#submit_edit').click(function(e)
    {
        var data = '&id=' + $("#submit_edit").val() + '&text=' + $("#notetext_edit").val() + '&timeout=' + $("#timeout_edit").val() + '&action=edit';

        request = $.ajax({
            type: "POST",
            url: "index.php",
            data: data
        });

        request.done(function() {
            $.getJSON(jsonPath, function(result) {
                message(result['type'], result['message']);
                refreshWindow();
            });
        });

      e.preventDefault();
    });
});




