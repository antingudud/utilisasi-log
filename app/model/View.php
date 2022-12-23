<?php
class View{
    protected $template;
    public static function render($view){
        $file = dirname(__DIR__, 1) . "/view/$view";

        if(is_readable($file)){
            require $file;
        } else {
            include_once dirname(__DIR__, 1) . "/view/404.php";
            echo $file . $view;
        }
    }
}
?>