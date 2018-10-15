<?php

require_once __DIR__ . '/../Models/productModel.php';

define('INVALID_STATUS_CODE', 403);
define('NOT_FOUND_STATUS_CODE', 404);

class TransactionController
{

    private $model;

    public function __construct($tile)
    {
        $this->model = new $tile;
    }

    public function get($query)
    {
        parse_str($query, $query);

        $query = $this->defaulting(['limit' => true, 'offset' => true], $query);

        $validated = $this->validate(['limit' => true, 'offset' => true], $query);

        if (!$validated['is_valid']) {
            throw new Exception(implode(",", $validated['errors']), INVALID_STATUS_CODE);
        }

        $query = array_intersect_key($query, array_flip(['limit', 'offset']));

        return ($this->model->getTransactions($query));
    }

    public function delete($id)
    {
        $validated = $this->validate(['id' => true], ['id' => $id]);

        if (!$validated['is_valid']) {
            throw new Exception(implode(",", $validated['errors']), INVALID_STATUS_CODE);
        }

        $transaction = $this->model->getTransaction($id);

        if ($transaction == 'false') throw new Exception('Transaction not found', NOT_FOUND_STATUS_CODE);

        return ($this->model->deleteTransaction($id));
    }

    public function put($body)
    {
        $body = json_decode($body, true);

        $validated = $this->validate([
            'user_id' => true,
            'product_id' => true,
            'amount' => true,
            'currency' => true
        ], $body);

        if (!$validated['is_valid']) {
            throw new Exception(implode(",", $validated['errors']), INVALID_STATUS_CODE);
        }

        $product_model = new ProductModel();

        $product = $product_model->getProduct($body['product']['id']);

        if ($product == 'false') throw new Exception('Product not found', NOT_FOUND_STATUS_CODE);

        $body = [
            'user_id' => $body['user_id'],
            'amount' => $body['amount'],
            'currency' => $body['currency'],
            'product' => [
                'id' => $body['product']['id']
            ]
        ];

        return ($this->model->putTransaction($body));
    }

    public function defaulting($fieldsToDefaulting, $body)
    {
        if ($fieldsToDefaulting['limit']) {
            if (is_null($body['limit']) or ($body['limit'] == '')) $body['limit'] = '100';
        }

        if ($fieldsToDefaulting['offset']) {
            if (is_null($body['offset']) or ($body['offset'] == '')) $body['offset'] = '0';
        }

        return $body;
    }

    public function validate($fieldsToValidate, $body)
    {
        $validated = [
            'is_valid' => true,
            'errors' => []
        ];

        if ($fieldsToValidate['id']) {
            if (is_null($body['id']) or ($body['id'] == '')) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The id can not be empty");
            }
            else if (!ctype_digit($body['id'])){
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The id must be an int string");
            }
        }

        if ($fieldsToValidate['user_id']) {
            if (is_null($body['user_id'])) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The user_id can not be empty");
            }
            else if (!is_string($body['user_id'])) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The user_id must be a string");
            }
            else if ($body['user_id'] == '') {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The user_id can not be empty");
            }
            else if (!ctype_digit($body['user_id'])){
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The user_id must be an int string");
            }
        }

        if ($fieldsToValidate['product_id']) {
            if (is_null($body['product']['id'])) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The product.id can not be empty");
            }
            else if (!is_string($body['product']['id'])) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The product.id must be a string");
            }
            else if ($body['product']['id'] == '') {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The product.id can not be empty");
            }
            else if (!ctype_digit($body['product']['id'])){
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The product.id must be an int string");
            }
        }

        if ($fieldsToValidate['amount']) {
            if (is_null($body['amount'])) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The amount can not be empty");
            }
            else if (!is_string($body['amount'])) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The amount must be a string");
            }
            else if ($body['amount'] == '') {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The amount can not be empty");
            }
            else if (!is_numeric($body['user_id'])){
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The amount must be a numeric string");
            }
        }

        if ($fieldsToValidate['currency']) {
            if (is_null($body['currency'])) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The currency can not be empty");
            }
            else if (!is_string($body['currency'])) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The currency must be a string");
            }
            else if ($body['currency'] == '') {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The currency can not be empty");
            }
            else if ($body['currency'] != 'coins' and $body['currency'] != 'gems') {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The currency must be coins or gem");
            }
        }

        if ($fieldsToValidate['limit']) {
            if (is_null($body['limit']) or ($body['limit'] == '')) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The limit can not be empty");
            }
            else if (!ctype_digit($body['limit'])){
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The limit must be an int string");
            }
        }

        if ($fieldsToValidate['offset']) {
            if (is_null($body['offset']) or ($body['offset'] == '')) {
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The offset can not be empty");
            }
            else if (!ctype_digit($body['offset'])){
                $validated['is_valid'] = false;
                array_push($validated['errors'], "The offset must be an int string");
            }
        }

        return $validated;
    }
}