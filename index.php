<?php
/**
 * @author Dominik Ryńko <http://rynko.pl/>
 * @Version: 1.0
 * @Contact: http://www.rynko.pl/kontakt
*/

use System\Engine;
use System\ToDoList;

ob_start();

// Before any action, check whether PHP is higher that 5.3.0
if (version_compare(PHP_VERSION, '5.3.0') <= 0) {
    error_log('PHP 5.3.0 version is required. System cannot work properly.');
    exit;
}

// Load necessary files
$files = ['core/Engine.class.php', 'core/Logger.class.php', 'core/ToDoList.class.php'];

function checkFile($file) {
    return file_exists($file) && filesize($file) !== 0;
}

if (checkFile($files[0]) && checkFile($files[1]) && checkFile($files[2])) {
    foreach ($files as $file) {
        require_once $file;
    }
} else {
    error_log('Missing files in core/ directory.');
    exit;
}

// Create new instance of Engine class
//$engine = new Engine();

// Create new instance of ToDoList class
$todo = new ToDoList();

// Header of website
include Engine::$config['path']['template'].'header.php';

// Basic routing
include Engine::$config['path']['template'].'index.php';

// Footer of website
include Engine::$config['path']['template'].'footer.php';

ob_end_flush();
