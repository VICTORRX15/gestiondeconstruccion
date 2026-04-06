<?php
class Partida {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAllByGrupo($grupo_id) {
        $stmt = $this->db->prepare("SELECT p.*, g.nombre as grupo_nombre, g.codigo as grupo_codigo, g.obra_id
                                    FROM partidas p 
                                    JOIN grupos g ON p.grupo_id = g.id 
                                    WHERE p.grupo_id = ? 
                                    ORDER BY p.codigo");
        $stmt->execute([$grupo_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllByObra($obra_id) {
        $stmt = $this->db->prepare("SELECT p.*, g.nombre as grupo_nombre, g.codigo as grupo_codigo
                                    FROM partidas p 
                                    JOIN grupos g ON p.grupo_id = g.id 
                                    WHERE g.obra_id = ? 
                                    ORDER BY g.orden, p.codigo");
        $stmt->execute([$obra_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM partidas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO partidas (grupo_id, codigo, descripcion, unidad) VALUES (?,?,?,?)");
        return $stmt->execute([$data['grupo_id'], $data['codigo'], $data['descripcion'], $data['unidad']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE partidas SET codigo=?, descripcion=?, unidad=?, grupo_id=? WHERE id=?");
        return $stmt->execute([$data['codigo'], $data['descripcion'], $data['unidad'], $data['grupo_id'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM partidas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>