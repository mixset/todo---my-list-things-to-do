<?php
/////////////////////////////////////////////////////////
// Author: Dominik Ryńko                               //
// Website: http://www.rynko.pl/                       //
// Version: 1.1                                        //
// Info: If you want to modify this file, you have to  // 
//       leave information about author                //
// Description: File, where all actions are execuings  //
//                              All Rights Reserved    //
/////////////////////////////////////////////////////////

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST)) // Sprawdza czy forumularz został wysłany metodą POST i czy w ogóle został wysłany
{
 define('SCRIPT', true);

 require_once 'todo.class.php';
 $todo = new todo;
 
 if($_POST['action'] == 'add_new') 
 {
  $addNewNote = $todo -> addNewNote($_POST['text'], $_POST['timeout']);
   if($addNewNote[0] == false)
   { 
    echo $addNewNote[1];
   }
   elseif($addNewNote[0] == true)
   {
    echo $addNewNote[1];
   }
  }
  elseif($_POST['action'] == 'done')
  {
   $done = $todo -> doneNote($_POST['id']);
   if($done[0] == 1)
   {
    echo $done[1];
   } 
  }  
  elseif($_POST['action'] == 'delete')
  {
   $delete = $todo -> deleteNote($_POST['id']);
   if($delete[0] == 1)
   {
    echo $delete[1];
   }
  }
  elseif($_POST['action'] == 'current')
  {
   $current = $todo -> currentNote($_POST['id']);
   if($current[0] == 1)
   {
    echo $current[1];
   }
  }
  elseif($_POST['action'] == 'edit')
  {
   $edit = $todo -> editNote($_POST['text'], $_POST['timeout'], $_POST['id']);
   if($edit[0] == false)
   { 
    echo $edit[1];
   }
   elseif($edit[0] == true)
   {
    echo $edit[1];
   }
  }
}

?>