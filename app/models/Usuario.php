<?php
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
            return $usuario;
        }
        return false;
    }

    public static function obtenerUsuario($username)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM empleados WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function registrar($id, $nombre, $edificio, $area, $vacaciones, $usuario, $password, $creador)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO empleados (id_empleado, nombre, edificio, area, vacaciones, usuario, password, creador) 
            VALUES (:id_empleado, :nombre, :edificio, :area, :vacaciones, :usuario, :password, :creador)
        ");
        return $stmt->execute([
            'id_empleado' => $id,
            'nombre' => $nombre,
            'edificio' => $edificio,
            'area' => $area,
            'vacaciones' => $vacaciones,
            'usuario' => $usuario,
            'password' => $password,
            'creador' => $creador
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

}
?>