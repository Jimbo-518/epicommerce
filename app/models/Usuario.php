<?php
require_once dirname(__DIR__, 2) . '/config/Database.php';

class Usuario
{
    public static function verificarCredenciales($username, $password)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE Usuario = :usuario");
        $stmt->execute(['usuario' => $username]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['Password'])) {
            return $usuario;
        }
        return false;
    }

    public static function obtenerUsuario($username)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE Usuario = :usuario");
        $stmt->execute(['usuario' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function registrar($nombre, $usuario, $password, $rol, $creador)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO usuario (Nombre, Usuario, Password, Rol, Creador) 
            VALUES (:nombre, :usuario, :password, :rol, :creador)
        ");
        return $stmt->execute([
            'nombre' => $nombre,
            'usuario' => $usuario,
            'password' => $password,
            'rol' => $rol,
            'creador' => $creador
        ]);
    }

    public static function obtenerUsuarioPorNombre($username)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuario WHERE Usuario = :Usuario LIMIT 1");
        $stmt->bindParam(':Usuario', $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
?>