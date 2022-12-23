<?php
namespace App\View;
class View{
    protected string $view;
    protected Array $params = [];

    public function __construct(String $view, array $params =[])
    {
        $this->params = $params;
        $this->view = $view;
    }
    public function render(){
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        if(!is_readable($viewPath)){
            return include_once VIEW_PATH . '/404.php';
        }
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        ob_start();
        $viewPath = VIEW_PATH . '/' . 'resources/main' . '.php';
        include_once $viewPath;
        return ob_get_clean();
    }
    protected function renderOnlyView()
    {
        ob_start();
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';
        include_once $viewPath;
        return ob_get_clean();
    }

}
?>