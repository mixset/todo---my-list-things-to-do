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
?>
<!DOCTYPE html>
 <html lang="pl">
  <head>
   <meta charset="UTF-8">
   <link rel="stylesheet" href="css/bootstrap.min.css">
   <link rel="stylesheet" href="css/main.css">
   <link rel="stylesheet" href="css/bootstrap-datepicker.min.css">
   <title>Moja lista rzeczy do zrobienia - ToDo - rynko.pl </title>
   <meta name="author" content="www.rynko.pl - Dominik Ryńko">
   <meta name="description" content="Moja lista ToDo. Lista rzeczy, czyli ToDo - lista rzeczy, które musisz zrobić. Autor: Dominik Ryńko. www.rynko.pl">
   <script src="js/jquery.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script src="js/bootstrap-datepicker.min.js"></script>
   <script src="js/locales/bootstrap-datepicker.pl.min.js"></script>
   <script src="js/script.js"></script>
  </head>
 <body class="container">
  <header class="well pull-center">
   <h1><a href="index.php" title="Moja lista rzeczy do zrobienia">Moja lista &bdquo;ToDo&rdquo;</a></h1>
  </header>
  <section>
   <div class="well pull-center">
    <a href="#addCategory" class="btn btn-primary" data-toggle="modal">Dodaj Notatkę</a>
     <form action="#" method="POST" id="addNoteForm">
	  <div id="addCategory" class="modal hide fade" tabindex="-2" role="dialog" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
         <h3>Dodaj Notatkę</h3>
      </div>
	  <div class="modal-body">
       <label for="notetext">Treść</label>
        <div class="input-prepend"><textarea id="notetext" rows="6" placeholder="Treść notatki"></textarea></div>
	   <label for="timeout">Data wygaśnięcia</label>
        <div class="input-prepend" id="datepicker">
         <span class="add-on">
          <i class="icon-time"></i>
         </span>
         <input type="text" value="<?php echo $note -> getTomorrowDate(); ?>" id="timeout">
        </div>
		<span class="badge badge-info">W formacie: DD/MM/YYYY</span>
      </div>
      <div class="modal-footer">
       <button class="btn" data-dismiss="modal" aria-hidden="true">Zamknij</button>
       <button id="submit_add" class="btn btn-primary" title="Dodawanie zostało zablokowane" data-dismiss="modal" aria-hidden="true">Dodaj</button>
      </div>
     </div>
	</form>
   </div>

   <div id="log"></div> <!--- empty div for messages -->
  <?php
   $data = $note -> getNotesList();
   $count = count($data);
   if ($count > 0) {
   ?>
   <table class="table table-bordered table-hover">
     <thead>
      <tr>
       <th>Treść</th>
	   <th>Status</th>
	   <td>Rozpoczęto</td>
	   <td>Data wygaśnięcia</td>
       <th>Akcja</th>
      </tr>
     </thead>
     <tbody>
     <?php
         for ($i = 0; $i < $count; ++$i) {
             ?>
             <tr>
                 <td class="note_text">
                     <p><?php echo stripslashes($data[$i][1]); ?></p>
                     <?php if ($data[$i][2] == 1) {
                         if ($note->isNoteCurrent($data[$i][4]) == true) {
                             echo ' <p><span class="label label-important font-change">Czas minął - ' . date('d/m/Y', $data[$i][4]) . '</span></p>';
                         }
                     } ?>
                 </td>
                 <td class="status_row">
                     <?php
                     if ($data[$i][2] == 1)
                         echo '<span class="label label-info font-change">Aktualne</span>';
                     elseif ($data[$i][2] == 2)
                         echo '<span class="label label-success font-change">Zrobione</span>';
                     ?>
                 </td>
                 <td class="date_row"><?php echo date('d/m/Y', $data[$i][3]); ?></td>
                 <td class="date_row"><?php echo date('d/m/Y', $data[$i][4]); ?></td>
                 <td class="action_row">
                     <?php echo $data[$i][2] == 1 ? '<button class="done_note btn btn-success" value="' . $data[$i][0] . '" title="Zrobione" aria-label="Zrobione"><i class="icon-ok icon-white"></i></button>' : '<button class="current_note btn btn-warning" aria-label="Aktualne" value="' . $data[$i][0] . '" title="Aktualne"><i class="icon-minus icon-white"></i></button>';
                     echo "\n"; ?>
                     <a href="edit_note.php?id=<?php echo $data[$i][0]; ?>" name="<?php echo $data[$i][0]; ?>" data-toggle="modal" class="btn btn-primary" title="Edytuj" aria-label="Edytuj"><i class="icon-edit icon-white"></i></a>
                     <button class="delete_note btn btn-inverse" value="<?php echo $data[$i][0]; ?>" aria-label="Usuń" title="Usuń"><i class="icon-remove icon-white"></i></button>
                 </td>
             </tr>
             <?php
         }
     } else {
        echo '<h3 class="pull-center">Brak notatek.</h3>';
     }
	 ?>
</tbody>
   </table>
   </section>
  <footer class="well">
    <p class="pull-right">Copyright by Dominik Ryńko &copy; - <i>www.rynko.pl</i></p>
	<p style="pull-left">Wszystkie prawa zastrzeżone</p> 
  </footer>
 </body>
</html>