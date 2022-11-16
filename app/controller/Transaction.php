<?php
class Transaction extends TransactionModel{
    public function index(){
        View::render('Transaction/index.php');
    }
    public function new(){
        View::render('Transaction/new.php');
    }
    public function edit(){
        View::render('Transaction/update.php');
    }
    public function showTransac(){
        $result = $this->getTransac();
        return $result;
    }
    public function submitIndex($download, $upload, $idDevice){
        TransactionModel::setTransac($download, $upload, $idDevice);
    }
    public function deleteIndex($id){
        TransactionModel::delTransac($id);
    }
    public function getTransaction($query, $params, $action){
        TransactionModel::queryTransaction($query, $params, $action);
    }
    public function transpose($array)
    {
      $retData = array();
    
        foreach ($array as $row => $columns) {
          foreach ($columns as $row2 => $column2) {
              $retData[$row2][$row] = $column2;
          }
        }
      return $retData;
    }
}
?>