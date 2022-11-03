<?php
class View{
    public static function render($view){
        $file = "../app/view/$view";

        if(is_readable($file)){
            require $file;
        } else {
            echo "$file not found";
        }
    }
}
?>