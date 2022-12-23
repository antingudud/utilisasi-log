<?php
use App\Model\Transaction\TransactionModel;
use App\Model\Transaction\TransactionService;
class Transaction extends TransactionModel{
    // public function view($view){
    //     View::render($view);
    // }
    public function showTransac(){
        $result = $this->getTransac();
        return $result;
    }
    public function submitIndex($download, $upload, $date, $idDevice){
        (new TransactionService)->create_service($download, $upload, $date, $idDevice);

    }
    public function updateIndex(Array $params){
        (new TransactionService)->update_service($params);
    }
    public function deleteIndex($id){
        (new TransactionService)->delete_service($id);
    }
    public function getTransaction(Array $params){
        (new TransactionService)->read_service($params);
    }
    public function showUpdateView($id){
        return (new TransactionService)->selectUpdate_service($id);
    }
    public function selectAlterForm(){
        return (new TransactionService)->selectAlternateForm_service();
    }
    public function getMainTable(){
        return (new TransactionService)->selectMainForm_service();
    }
}
?>