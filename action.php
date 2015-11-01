<?php
/**
 * @Author: Dominik Ryńko
 * @Website: http://www.rynko.pl/
 * @Version: 1.1
*/

if(isset($_POST))
{
    if (file_exists('todo.class.php')) {
        require 'todo.class.php';
        $todo = new ToDo;
    } else {
        echo 'Nie odnaleziono klasy todo.class.php';
        exit;
    }

    switch($_POST['action'])
    {
        case 'add_new':
        {
            $addNewNote = $todo -> addNewNote($_POST['text'], $_POST['timeout']);
            if($addNewNote[0] == false) {
                echo $addNewNote[1];
            }
            elseif($addNewNote[0] == true) {
                echo $addNewNote[1];
            }
        }
        break;

        case 'done':
        {
            $done = $todo -> doneNote($_POST['id']);
            if($done[0] == 1) {
                echo $done[1];
            }
        }
        break;

        case 'delete':
        {
          $delete = $todo -> deleteNote($_POST['id']);
          if($delete[0] == 1) {
                echo $delete[1];
          }
        }
        break;

        case 'current':
        {
            $current = $todo -> currentNote($_POST['id']);
            if($current[0] == 1)
            {
                echo $current[1];
            }
        }
        break;

        case 'edit':
        {
            $edit = $todo -> editNote(['text' => $_POST['text'], 'timeout' => $_POST['timeout'], 'id' => $_POST['id']]);
            if ($edit[0] == false) {
                echo $edit[1];
            } elseif ($edit[0] == true) {
                echo $edit[1];
            }
        }
        break;
    }
} 

?>