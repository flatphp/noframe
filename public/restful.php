<?php
require dirname(__DIR__) . '/boot.php';

// nikic/fast-route required

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/hello/{name:[\w\-]+}', ['Hello', 'sayHello']);

    // $r->addRoute('POST', '/some/some', ['Some', 'save']);

});


// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        echo '404 Page Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header($_SERVER["SERVER_PROTOCOL"] . " 405 Method Not Allowed");
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        if (is_array($handler)) {
            handler($handler[0], $handler[1], $vars);
        } elseif (function_exists($handler)) {
            $data = call_user_func_array($handler, $vars);
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
            echo 'Invalid Handler ' . $handler;
        }
        break;
}


function handler($class, $method, $vars = [])
{
    try {
        $class = $class . 'Controller';
        $class_file = ROOT_PATH . '/app/rest/controller/' . $class . '.php';
        include ROOT_PATH . '/app/rest/controller/ControllerAbstract.php';
        include $class_file;
        $api = new $class();
        $ct = count($vars);
        if ($ct == 0) {
            $data = $api->$method();
        } else {
            $data = call_user_func_array([$api, $method], $vars);
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
        $data = ['code' => 1000, 'msg' => $e->getMessage()];
        if (defined('DEBUG') && DEBUG) {
            $data['file'] = $e->getFile();
            $data['line'] = $e->getLine();
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}