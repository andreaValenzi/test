<?php

class TransactionModel {
    function __construct(){

    }

    public function getTransaction($id){
        try {
            $configs = include( __DIR__ . '/../config.php');
            $pdo = new PDO('mysql:host=' . $configs['db']['host'] . ';dbname=' . $configs['db']['name'], $configs['db']['username'], $configs['db']['password']);
            $statement = $pdo->query("SELECT 
              transactions.id as tid,
              transactions.user_id as uid,
              transactions.amount as amount,
              transactions.currency as currency,
              products.id as pid,
              products.description as description
              from transactions, products where transactions.product_id = products.id and transactions.id=" . $id);
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            return json_encode(toDto($row));
        }
        catch(Exception $e) {
            if ($_SERVER['ENVIRONMENT'] == 'development') throw $e;
            throw new Exception("Error");
        }
    }

    public function getTransactions($query){
        try {
            $configs = include( __DIR__ . '/../config.php');
            $pdo = new PDO('mysql:host=' . $configs['db']['host'] . ';dbname=' . $configs['db']['name'], $configs['db']['username'], $configs['db']['password']);
            $statement = $pdo->query("SELECT 
              transactions.id as tid,
              transactions.user_id as uid,
              transactions.amount as amount,
              transactions.currency as currency,
              products.id as pid,
              products.description as description
              from transactions, products where transactions.product_id = products.id LIMIT " . $query['limit'] . " OFFSET " . $query['offset']);
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $objects = array_map('toDto', $rows);
            return json_encode($objects);
        }
        catch(Exception $e) {
            if ($_SERVER['ENVIRONMENT'] == 'development') throw $e;
            throw new Exception("Error");
        }
    }

    public function deleteTransaction($id) {
        try {
            $configs = include( __DIR__ . '/../config.php');
            $pdo = new PDO('mysql:host=' . $configs['db']['host'] . ';dbname=' . $configs['db']['name'], $configs['db']['username'], $configs['db']['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM transactions WHERE id=" . $id;
            $pdo->exec($sql);
            return json_encode([]);
        }
        catch(Exception $e) {
            if ($_SERVER['ENVIRONMENT'] == 'development') throw $e;
            throw new Exception("Error");
        }
    }

    public function putTransaction($body) {
        try {
            $configs = include( __DIR__ . '/../config.php');
            $pdo = new PDO('mysql:host=' . $configs['db']['host'] . ';dbname=' . $configs['db']['name'], $configs['db']['username'], $configs['db']['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO transactions (user_id, amount, currency, product_id)
              VALUES ('" . $body['user_id'] . "','" . $body['amount'] . "','" . $body['currency'] . "','" . $body['product']['id'] . "');";
            $pdo->exec($sql);
            return json_encode([]);
        }
        catch(Exception $e) {
            if ($_SERVER['ENVIRONMENT'] == 'development') throw $e;
            throw new Exception("Error");
        }
    }
}

function toDto($elem)
{
    $object = [
        'id' => $elem['tid'],
        'user_id' => $elem['uid'],
        'product' => [
            'id' => $elem['pid'],
            'description' => $elem['description']
        ],
        'amount' => round($elem['amount'], 2),
        'currency' => $elem['currency']
    ];

    return $object;
}