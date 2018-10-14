<?php
class TransactionModel {
    function __construct(){
      
    }

    public function getTransactions(){
        $pdo = new PDO('mysql:host=database;dbname=test', 'andrea', 'ciaone');
        $statement = $pdo->query("SELECT * from transactions, products where transactions.product_id = products.id");
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $objects = array_map('toDto', $rows);
        return json_encode($objects);
    }

    public function deleteTransaction($id) {
        return json_encode([]);
    }
}

function toDto($elem)
{
    $object = [
        'id' => $elem['id'],
        'user_id' => $elem['user_id'],
        'product' => [
            'id' => $elem['product_id'],
            'description' => $elem['description']
        ],
        'amount' => $elem['amount'],
        'currency' => $elem['currency']
    ];

    return $object;
}