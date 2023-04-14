<?php
require dirname(__DIR__) . '/boot.php';
const APP_RPC_PATH = ROOT_PATH . '/app/rpc';

// JsonRPCServer required

// RPC入口
// 由于rpc路由的简洁性，以及为了提升性能，所以不使用fast-route

// $class = isset($_GET['class']) ? $_GET['class'] : '';
$class = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if (!$class) {
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
    echo '405 Method Not Allowed';
    exit;
}

$name = strtolower($class);
$name = str_replace('-', '', ucwords($name, '-')) . 'Controller';
$rpc_file = APP_RPC_PATH . '/controller/' . $name . '.php';
if (!is_file($rpc_file)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
    echo 'Invalid RPC call ' . $class;
    exit;
}
include APP_RPC_PATH . '/controller/ControllerAbstract.php';
include $rpc_file;

$rpc = new $name();
JsonRPCServer::handle($rpc);
