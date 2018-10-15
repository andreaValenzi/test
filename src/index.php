<?php

if (isset($_SERVER['PATH_INFO'])) {
    $path = $_SERVER['PATH_INFO'];

    $path_split = explode('/', ltrim($path));

} else {
    return header('Location: /dashboard.html');
}

$req_controller = $path_split[1];

$req_method = strtolower($_SERVER['REQUEST_METHOD']);

$req_body = file_get_contents('php://input');

$req_resource_id = $path_split[2];

$req_query = $_SERVER['QUERY_STRING'];

if ($req_controller == 'transactions') {
    $controller_path = __DIR__ . '/Controllers/transactionController.php';
    $model_path = __DIR__ . '/Models/transactionModel.php';

    $model = 'TransactionModel';
    $controller = 'TransactionController';
}
else {
    http_response_code(404);
    echo('Page Not Found');
}

require_once $controller_path;
require_once $model_path;

$ControllerObj = new $controller($model);
header('Content-Type: application/json');

try {
    if ($req_method == 'put') $input = $req_body;
    else if ($req_method == 'delete') {
        $input = $req_resource_id;
    }
    else if ($req_method == 'get') {
        $input = $req_query;
    }
    echo $ControllerObj->$req_method($input);
}
catch(Exception $e) {
    $code = 500;
    if ($e -> getCode()) $code = $e -> getCode();
    http_response_code($code);
    echo(json_encode(['message' => $e->getMessage(), 'source' => $req_controller]));
}