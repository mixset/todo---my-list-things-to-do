<!DOCTYPE html>
 <html lang="pl">
  <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/main.css">
   <title>Moja lista rzeczy do zrobienia - ToDo - wersja demonstracyjna skryptu ToDo - rynko.pl </title>
   <meta name="author" content="www.rynko.pl - Dominik Ryńko ">
   <meta name="description" content="Moja lista ToDo. Lista rzeczy, które musisz zrobić. Autor: Dominik Ryńko. www.rynko.pl">
   <script src="js/jquery.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script src="js/script.js"></script>
  </head>
 <body class="container">
  <header class="well">
   <h1 class="pull-center"><a href="index.php" title="Powrót do strony główej">Moja lista &bdquo;ToDo&rdquo;</a></h1>
  </header>
  <?php
  define('SCRIPT', true);
   require 'todo.class.php';
   $note = new todo;   
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
        <div class="input-prepend"><textarea id="notetext_edit" rows="6"><?php echo $data[0][0]; ?></textarea></div>
	   <label for="timeout_edit">Data wygaśnięcia</label>
        <div class="input-prepend"><span class="add-on"><i class="icon-time"></i></span><input type="text" id="timeout_edit" value="<?php echo date('d/m/Y', $data[0][1]); ?>"></div>
        <span class="badge badge-info">W formacie: DD/MM/YYYY</span>
	  </div>
      <div class="modal-footer">
	   <a href="index.php" id="link_back" class="btn btn-primary">Wróć</a>
       <button id="submit_edit" class="btn btn-primary" value="<?php echo $_GET['id']; ?>">Uaktualnij</button>
      </div>
     </div>
   </section>
  <footer class="well">
    <p class="pull-right">Copyright by Dominik Ryńko &copy; - <i>www.rynko.pl</i></p>
	<p style="pull-left">Wszystkie prawa zastrzeżone</p>
  </footer>

 </body>
</html>