<?php
require_once '../vendor/autoload.php';
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Usuario
{
    public static function verificarCredenciales($username, $password)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['password'])) {

            if ($usuario['id_depto_edificio']) {

                $id_depto_edificio = $usuario['id_depto_edificio'];

                $sql_info = "
        SELECT
            d.departamento,
            ed.edificio
        FROM
            departamento_edificio de
        JOIN
            departamentos d ON de.id_depto = d.id_depto
        JOIN
            edificios ed ON de.id_edificio = ed.id_edificio
        WHERE
            de.id_depto_edificio = :id;
    ";
                $stmt_info = $db->prepare($sql_info);
                $stmt_info->bindParam(':id', $id_depto_edificio);
                $stmt_info->execute();
                $info_adicional = $stmt_info->fetch(PDO::FETCH_ASSOC);

                if ($info_adicional) {
                    echo "Departamento: " . $info_adicional['departamento'] . "<br>";
                    echo "Edificio: " . $info_adicional['edificio'] . "<br>";
                    return [
                        'usuario' => $usuario,
                        'info_adicional' => $info_adicional
                    ];
                } else {
                    return false;
                }

            } else {
                return false;
            }
        }
        return false;
    }

    public static function listaempleados()
    {
        $db = Database::getConnection();
        $sql = "
        SELECT
            e.*,
            d.departamento,
            ed.edificio
        FROM
            empleados e
        JOIN
            departamento_edificio de ON e.id_depto_edificio = de.id_depto_edificio
        JOIN
            departamentos d ON de.id_depto = d.id_depto
        JOIN
            edificios ed ON de.id_edificio = ed.id_edificio
    ";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($empleados) {
            return $empleados;
        }

        return false;
    }

    public static function registrar($id, $nombre, $id_depto_edificio, $usuario, $password, $vacaciones, $fecha_ingreso)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO empleados (`id_empleado`, `nombre`, `id_depto_edificio`, `vacaciones`, `usuario`, `password`, `fecha_ingreso`) 
            VALUES (:id_empleado, :nombre, :id_depto_edificio, :vacaciones, :usuario, :password, :fecha_ingreso)
        ");
        return $stmt->execute([
            'id_empleado' => $id,
            'nombre' => $nombre,
            'id_depto_edificio' => $id_depto_edificio,
            'vacaciones' => $vacaciones,
            'usuario' => $usuario,
            'password' => $password,
            'fecha_ingreso' => $fecha_ingreso
        ]);
    }

    public static function obtenerUsuarioPorNombre($username)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE usuario = :Usuario LIMIT 1");
        $stmt->bindParam(':Usuario', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function pushUserID($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE id_empleado = :id_empleado LIMIT 1");
        $stmt->bindParam(':id_empleado', $id);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario['id_depto_edificio']) {

            $id_depto_edificio = $usuario['id_depto_edificio'];

            $sql_info = "
        SELECT
            d.departamento,
            ed.edificio
        FROM
            departamento_edificio de
        JOIN
            departamentos d ON de.id_depto = d.id_depto
        JOIN
            edificios ed ON de.id_edificio = ed.id_edificio
        WHERE
            de.id_depto_edificio = :id;
    ";
            $stmt_info = $db->prepare($sql_info);
            $stmt_info->bindParam(':id', $id_depto_edificio);
            $stmt_info->execute();
            $info_adicional = $stmt_info->fetch(PDO::FETCH_ASSOC);

            if ($info_adicional) {
                return [
                    'usuario' => $usuario,
                    'info_adicional' => $info_adicional
                ];
            }
        }
    }
    public static function actualizarEmpleado($id, $nombre, $id_depto_edificio, $vacaciones)
    {
        $db = Database::getConnection();

        // La consulta ahora actualiza la columna 'id_depto_edificio'
        $stmt = $db->prepare("
        UPDATE empleados
        SET
            nombre = :nombre,
            id_depto_edificio = :id_depto_edificio,
            vacaciones = :vacaciones
        WHERE id_empleado = :id
    ");

        return $stmt->execute([
            'nombre' => $nombre,
            'id_depto_edificio' => $id_depto_edificio, // Se pasa el ID obtenido
            'vacaciones' => $vacaciones,
            'id' => $id
        ]);
    }
    public static function eliminarEmpleado($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM empleados WHERE id_empleado = :id");
        return $stmt->execute(['id' => $id]);
    }

    public static function listaEdificios()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT DISTINCT id_depto_edificio FROM empleados ORDER BY id_depto_edificio ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function listaAreas()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT DISTINCT departamento FROM departamentos ORDER BY departamento ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function obtenerAsistenciasPorEmpleado($id_empleado, $fecha_inicio, $fecha_fin)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
        SELECT fecha, entrada, entrada_comida, salida_comida, salida
        FROM asistencias
        WHERE id_empleado = :id_empleado
          AND fecha BETWEEN :fecha_inicio AND :fecha_fin
        ORDER BY fecha ASC
    ");
        $stmt->execute([
            'id_empleado' => $id_empleado,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>