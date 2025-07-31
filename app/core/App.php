<?php
require_once dirname(__DIR__, 2) . '/config/Database.php';


class App {
    protected $controller = 'LoginController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        if (file_exists("../app/controllers/" . ucfirst($url[0]) . "Controller.php")) {
            $this->controller = ucfirst($url[0]) . "Controller";
            unset($url[0]);
        } else {
            // 🔴 Si el controlador no existe, mostramos un error 404 en lugar de redirigir al login
            http_response_code(404);
            echo "Error 404: Página no encontrada.";
            exit();
        }
    
        require_once "../app/controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller();
    
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        } else if (isset($url[1])) {
            // 🔴 Si el método no existe en el controlador, mostramos un error 404
            http_response_code(404);
            echo "Error 404: Método no encontrado.";
            exit();
        }
    
        $this->params = $url ? array_values($url) : [];
    
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return ['login'];
    }
}
?>