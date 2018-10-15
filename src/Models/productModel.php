<?php
class ProductModel {
    function __construct(){

    }

    public function getProduct($id){
        try {
            $configs = include( __DIR__ . '/../config.php');
            $pdo = new PDO('mysql:host=' . $configs['db']['host'] . ';dbname=' . $configs['db']['name'], $configs['db']['username'], $configs['db']['password']);
            $statement = $pdo->query("SELECT * from products where id=" . $id);
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return json_encode($row);
        }
        catch(Exception $e) {
            if ($_SERVER['ENVIRONMENT'] == 'development') throw $e;
            throw new Exception("Error");
        }
    }
}