<?php
namespace App\View;

/**
 * View is a templating engine class.
 */
class View{
    protected string $view;
    protected string $baseUrl;
    protected Array $params = [];

    public function __construct(String $view, array $params =[])
    {
        $configFile = file_get_contents(dirname(__DIR__, 1) . "/config/config.json");
        $configContent = json_decode($configFile);
        $this->params = $params;
        $this->view = $view;
        $this->baseUrl = $configContent->{'web_url'};
    }
    public function render(){
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        if(!is_readable($viewPath)){
            return include_once VIEW_PATH . '/404.php';
        }
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView();
        $viewContent = str_replace('{{base-url}}', $this->baseUrl, $viewContent);
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