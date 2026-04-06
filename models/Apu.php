<?php
class Apu {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAllByObra($obra_id) {
        $sql = "SELECT p.id, p.codigo, p.descripcion, p.unidad,
                       COALESCE(SUM(r.cantidad * r.precio_unitario), 0) as precio_unitario
                FROM partidas p
                JOIN grupos g ON p.grupo_id = g.id
                LEFT JOIN apu_recursos r ON p.id = r.partida_id
                WHERE g.obra_id = ?
                GROUP BY p.id
                ORDER BY g.orden, p.codigo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$obra_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRecursosByPartida($partida_id) {
        $stmt = $this->db->prepare("SELECT * FROM apu_recursos WHERE partida_id = ?");
        $stmt->execute([$partida_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addRecurso($data) {
        $sql = "INSERT INTO apu_recursos (partida_id, descripcion, unidad, cantidad, precio_unitario) 
                VALUES (?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['partida_id'], $data['descripcion'], $data['unidad'], 
                               $data['cantidad'], $data['precio_unitario']]);
    }
    
    public function updateRecurso($id, $data) {
        $sql = "UPDATE apu_recursos SET descripcion=?, unidad=?, cantidad=?, precio_unitario=? 
                WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['descripcion'], $data['unidad'], $data['cantidad'], 
                               $data['precio_unitario'], $id]);
    }
    
    public function deleteRecurso($id) {
        $stmt = $this->db->prepare("DELETE FROM apu_recursos WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getPrecioUnitario($partida_id) {
        $stmt = $this->db->prepare("SELECT COALESCE(SUM(cantidad * precio_unitario), 0) as total 
                                    FROM apu_recursos WHERE partida_id = ?");
        $stmt->execute([$partida_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>