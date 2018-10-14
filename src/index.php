<?php

if (isset($_SERVER['PATH_INFO'])) {
    $path = $_SERVER['PATH_INFO'];

    $path_split = explode('/', ltrim($path));

} else {
    return header('Location: /dashboard.html');
}

$req_controller = $path_split[1];

$req_model = $path_split[1];

$req_method = strtolower($_SERVER['REQUEST_METHOD']);

$req_resource_id = $path_split[2];

if ($req_controller == 'transactions') {
    $controller_path = __DIR__ . '/Controllers/transactionController.php';
    $model_path = __DIR__ . '/Models/transactionModel.php';

    $model = 'TransactionModel';
    $controller = 'TransactionController';
}
else {
    header('HTTP/1.1 404 Not Found');
}

require_once $controller_path;
require_once $model_path;

$ModelObj = new $model;
$ControllerObj = new $controller($model);
header('Content-Type: application/json');

try {
    print $ControllerObj->$req_method($req_resource_id);
}
catch(Exception $e) {
    header('HTTP/1.1 500 Internal Server Error');
    die(json_encode(['message' => $e->getMessage()]));
}