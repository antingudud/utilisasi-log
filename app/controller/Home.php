<?php
class Home extends ConnectDB{
    public function index(){
        View::render('Home/index.php');
    }
}
?>