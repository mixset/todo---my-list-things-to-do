<!DOCTYPE html>
 <html lang="pl">
  <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/main.css">
   <link rel="stylesheet" href="css/bootstrap-datepicker.min.css">
   <title>Moja lista rzeczy do zrobienia - http://www.rynko.pl </title>
   <meta name="author" content="http://www.rynko.pl - Dominik Ryńko ">
   <meta name="description" content="Moja lista ToDo. Lista rzeczy, które musisz zrobić. Autor: Dominik Ryńko. www.rynko.pl">
   <script src="js/jquery.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script src="js/bootstrap-datepicker.min.js"></script>
   <script src="js/locales/bootstrap-datepicker.pl.min.js"></script>
   <script src="js/script.js"></script>
  </head>
 <body class="container">
  <header class="well">
   <h1 class="pull-center"><a href="index.php" title="Powrót do strony główej">Moja lista &bdquo;ToDo&rdquo;</a></h1>
  </header>
  <?php
   try {
      if (file_exists('todo.class.php')) {
          require 'todo.class.php';
          $note = new ToDo;
      } else {
          echo 'Nie odnaleziono klasy todo.class.php';
          exit;
      }
   } catch(Exception $e) {
      echo $e -> getMessage();
   }
   $data = $note -> getOneRecord($_GET['id']);
  ?>
  <section>
   <div id="log"></div> <!--- empty div for messages -->
    <div id="edit_note">
      <div class="modal-header">
         <h2>Edytuj notatkę</h2>
      </div>
      <div class="modal-body">
       <label for="notetext_edit">Treść</label>
        <div class="input-prepend"><textarea id="notetext_edit" rows="6"><?php echo $data[0]; ?></textarea></div>
	   <label for="timeout_edit">Data wygaśnięcia</label>
        <div class="input-prepend"><span class="add-on"><i class="icon-time"></i></span><input type="text" id="timeout_edit" value="<?php echo date('d/m/Y', $data[1]); ?>"></div>
        <span class="badge badge-info">W formacie: DD/MM/YYYY</span>
	  </div>
      <div class="modal-footer">
       <button id="submit_edit" class="btn btn-primary" value="<?php echo $_GET['id']; ?>">Uaktualnij</button>
      </div>
     </div>
   </section>
  <footer class="well">
    <p class="pull-right">Copyright by Dominik Ryńko &copy; - <i>www.rynko.pl</i></p>
	<p class="pull-left">Wszystkie prawa zastrzeżone</p>
  </footer>
 </body>
</html>