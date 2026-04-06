<?php
class Obra {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM obras ORDER BY estado, nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM obras WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO obras (codigo, nombre, descripcion, ubicacion, fecha_inicio, fecha_fin_estimada, estado) VALUES (?,?,?,?,?,?,?)");
        return $stmt->execute([$data['codigo'], $data['nombre'], $data['descripcion'], $data['ubicacion'], $data['fecha_inicio'], $data['fecha_fin_estimada'], $data['estado']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE obras SET codigo=?, nombre=?, descripcion=?, ubicacion=?, fecha_inicio=?, fecha_fin_estimada=?, estado=? WHERE id=?");
        return $stmt->execute([$data['codigo'], $data['nombre'], $data['descripcion'], $data['ubicacion'], $data['fecha_inicio'], $data['fecha_fin_estimada'], $data['estado'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM obras WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getResumen($id) {
        $stmt = $this->db->prepare("
            SELECT 
                (SELECT COUNT(*) FROM grupos WHERE obra_id = ?) as total_grupos,
                (SELECT COUNT(*) FROM partidas p JOIN grupos g ON p.grupo_id = g.id WHERE g.obra_id = ?) as total_partidas,
                (SELECT COUNT(*) FROM computos c JOIN partidas p ON c.partida_id = p.id JOIN grupos g ON p.grupo_id = g.id WHERE g.obra_id = ?) as total_computos
        ");
        $stmt->execute([$id, $id, $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>