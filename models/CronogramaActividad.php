<?php
class CronogramaActividad {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAllByObra($obra_id) {
        $stmt = $this->db->prepare("SELECT c.*, p.descripcion as partida_nombre, p.codigo as partida_codigo
                                    FROM cronograma_actividades c
                                    LEFT JOIN partidas p ON c.partida_id = p.id
                                    WHERE c.obra_id = ?
                                    ORDER BY c.fecha_inicio");
        $stmt->execute([$obra_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $sql = "INSERT INTO cronograma_actividades (obra_id, nombre, fecha_inicio, fecha_fin, progreso, partida_id) 
                VALUES (?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['obra_id'], $data['nombre'], $data['fecha_inicio'], 
                               $data['fecha_fin'], $data['progreso'], $data['partida_id']]);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE cronograma_actividades SET nombre=?, fecha_inicio=?, fecha_fin=?, progreso=?, partida_id=? 
                WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['nombre'], $data['fecha_inicio'], $data['fecha_fin'], 
                               $data['progreso'], $data['partida_id'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM cronograma_actividades WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>