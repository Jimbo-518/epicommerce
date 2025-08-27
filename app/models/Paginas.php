<?php
require_once '../vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Paginas
{
    public static function obtenerPaginas()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM paginas");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPaginasPorDepto($id_depto)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id_pagina FROM depto_pagina WHERE id_depto = ?");
        $stmt->execute([$id_depto]);

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_column($resultados, 'id_pagina');
    }

    public static function obtenerPaginasPermitidas($id_depto)
    {
        $db = Database::getConnection();

        $sql = "SELECT p.nombre, p.url 
            FROM paginas p
            JOIN depto_pagina dp ON p.id_pagina = dp.id_pagina
            WHERE dp.id_depto = ? AND p.tipo = 'pagina' ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_depto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerWidgetsPermitidos($id_depto)
    {
        $db = Database::getConnection();

        $sql = "SELECT p.nombre, p.url 
            FROM paginas p
            JOIN depto_pagina dp ON p.id_pagina = dp.id_pagina
            WHERE dp.id_depto = ? AND p.tipo = 'widget' ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_depto]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function actualizarPermisos($id_depto, $paginas_a_guardar)
    {
        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("DELETE FROM depto_pagina WHERE id_depto = ?");
            $stmt->execute([$id_depto]);

            if (!empty($paginas_a_guardar)) {
                $sql = "INSERT INTO depto_pagina (id_depto, id_pagina) VALUES (?, ?)";
                $stmt = $db->prepare($sql);

                foreach ($paginas_a_guardar as $pagina_id) {
                    $stmt->execute([$id_depto, $pagina_id]);
                }
            }
            $db->commit();

            return true;
        } catch (Exception $e) {
            $db->rollBack();
            return false;
        }
    }
}
?>