<?php
use System\Engine;
use System\Logger;

$page = (empty($_GET['page']) || is_null($_GET['page'])) ? null : $_GET['page'];
$page = $todo -> secure($page);

if (is_null($page) || empty($page)) {
    $pathToFile = Engine::$config['path']['subpages'].'front.php';
} else {
    $pathToFile =  Engine::$config['path']['subpages'].$page.'.php';
}

if (checkFile($pathToFile)) {
    include $pathToFile;
} else {
    Logger::warning(__FILE__, __LINE__, 'File: ' . $pathToFile . ' does not exist or is empty.');
}

function createJSON($type = 'success', $message) {
    unlink(Engine::$config['path']['jsonPath'].'message.json');
    file_put_contents(Engine::$config['path']['jsonPath'].'message.json', json_encode(['type' => $type, 'message' => $message]));
}

if (isset($_POST['action'])) {
    $action = $todo -> secure($_POST['action']);

    switch($action)
    {
        case 'add':
            $add = $todo -> add($_POST['text'], $_POST['timeout']);
            createJSON($add[0], $add[1]);
        break;

        case 'done':
            $done = $todo -> done($_POST['id']);
            createJSON($done[0], $done[1]);
        break;

        case 'delete':
            $delete = $todo -> delete($_POST['id']);
            createJSON($delete[0], $delete[1]);
        break;

        case 'current':
            $current = $todo -> current($_POST['id']);
            createJSON($current[0], $current[1]);
        break;

        case 'edit':
            $edit = $todo -> edit(['text' => $_POST['text'], 'timeout' => $_POST['timeout'], 'id' => $_POST['id']]);
            createJSON($edit[0], $edit[1]);
        break;
    }
}
