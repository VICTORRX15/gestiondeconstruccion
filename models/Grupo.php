<?php
class Grupo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAllByObra($obra_id) {
        $stmt = $this->db->prepare("SELECT * FROM grupos WHERE obra_id = ? ORDER BY orden, codigo");
        $stmt->execute([$obra_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM grupos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO grupos (obra_id, codigo, nombre, orden) VALUES (?,?,?,?)");
        return $stmt->execute([$data['obra_id'], $data['codigo'], $data['nombre'], $data['orden']]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE grupos SET codigo=?, nombre=?, orden=? WHERE id=?");
        return $stmt->execute([$data['codigo'], $data['nombre'], $data['orden'], $id]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM grupos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>