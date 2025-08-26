<?php
class Controller {
    public function view($view, $data = []) {
        extract($data);
        require "../app/views/" . $view . ".php";
    }

    protected function verificarPermiso($url_pagina) {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['permisos']) || empty($_SESSION['permisos'])) {
            header("Location: " . BASE_URL . "dashboard");
            exit();
        }

        $paginas_permitidas = $_SESSION['permisos'];

        // Extraer solo la columna 'url' para una búsqueda eficiente
        $urls_permitidas = array_column($paginas_permitidas, 'url');
        
        // Verificar si la URL actual está en la lista de permisos
        if (!in_array($url_pagina, $urls_permitidas)) {
            header("Location: " . BASE_URL . "dashboard");
            exit();
        }
    }
}
?>