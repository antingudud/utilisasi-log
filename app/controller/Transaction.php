<?php
class Transaction extends TransactionModel{
    public function index(){
        View::render('Transaction/index.php');
    }
    public function new(){
        View::render('Transaction/new.php');
    }
    public function showTransac(){
        $result = $this->getTransac();
        return $result;
    }
    public function submitIndex(){
        
    }
}
?>