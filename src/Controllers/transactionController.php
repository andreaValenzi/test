<?php

class TransactionController
{

    private $model;
    public function __construct($tile)
    {
        $this->model = new $tile;
    }

    public function get()
    {
        return ($this->model->getTransactions());
    }

    public function delete($id)
    {
        if (is_null($id) or ($id == '')) {
            throw new Exception("The id can not be empty");
        }
        else if (!ctype_digit($id)){
            throw new Exception("The id must be an int string");
        }
        else {
            return ($this->model->deleteTransaction($id));
        }
    }

    public function put()
    {
        // TODO
    }
}