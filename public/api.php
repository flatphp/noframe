<?php
require dirname(__DIR__) . '/boot.php';
const APP_API_PATH = ROOT_PATH . '/app/api';

$path = \Lib\Request::path();
$path = explode('/', $path);

$controller = 'Index';
$action = 'Index';
if (!empty($path[0])) {
    $controller = str_replace('-', '', ucwords($path[0], '-'));
}
if (!empty($path[1])) {
    $action = str_replace('-', '', ucwords($path[1], '-'));
}

$controller_class = $controller .'Controller';
$class_file = APP_API_PATH . '/controller/' . $controller_class . '.php';
if (!is_file($class_file)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    echo '404 not found';
    exit;
}
include APP_API_PATH . '/controller/ControllerAbstract.php';
include $class_file;
$class = new $controller_class();
if (!method_exists($class, $action)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    echo '404 action not found';
    exit;
}

try {
    $data = $class->$action();
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $data = ['code' => 1000, 'msg' => $e->getMessage()];
    if (defined('DEBUG') && DEBUG) {
        $data['file'] = $e->getFile();
        $data['line'] = $e->getLine();
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

