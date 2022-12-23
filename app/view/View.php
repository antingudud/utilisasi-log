<?php
namespace App;
class View {
    protected string $view;
    protected array $params = [];
    function __construct(string $view, array $params = [])
    {
        $this->view = $view;
        $this->params = $params;       
    }
    public function render ()
    {
        $viewPath = VIEW_PATH . "/" . $this->view . ".php";
        if(!is_readable($viewPath)){
            header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
            include VIEW_PATH . "/404.php";
            return;
        }
        include $viewPath;
    }
}