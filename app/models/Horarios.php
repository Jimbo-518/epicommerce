<?php
class Horario
{
    public static function getDias()
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT id_dia, dia FROM dias ORDER BY id_dia ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getHorariosByDeptoEdificio($idDeptoEdificio)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT h.*, d.dia 
                          FROM horarios h 
                          JOIN dias d ON h.id_dia = d.id_dia 
                          WHERE h.id_depto_edificio = :id");
        $stmt->execute(['id' => $idDeptoEdificio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insertar($data)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO horarios 
            (id_depto_edificio, id_dia, entrada, tolerancia, salida, comida, descanso, rango_descanso) 
            VALUES (:id_depto_edificio, :id_dia, :entrada, :tolerancia, :salida, :comida, :descanso, :rango_descanso)");

        return $stmt->execute([
            ':id_depto_edificio' => $data['id_depto_edificio'],
            ':id_dia' => $data['id_dia'],
            ':entrada' => $data['entrada'],
            ':tolerancia' => $data['tolerancia'],
            ':salida' => $data['salida'],
            ':comida' => $data['comida'],
            ':descanso' => $data['descanso'],
            ':rango_descanso' => $data['rango_descanso']
        ]);
    }

    public static function eliminar($id_horario)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM horarios WHERE id_horario = :id");
        return $stmt->execute([':id' => $id_horario]);
    }
}