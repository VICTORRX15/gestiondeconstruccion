<?php
class Computo {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getAllByObra($obra_id) {
        $sql = "SELECT c.*, p.codigo, p.descripcion, p.unidad, g.id as grupo_id, g.nombre as grupo_nombre
                FROM computos c 
                JOIN partidas p ON c.partida_id = p.id
                JOIN grupos g ON p.grupo_id = g.id
                WHERE g.obra_id = ?
                ORDER BY c.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$obra_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $cantidad = $this->calcularCantidad($data);
        $sql = "INSERT INTO computos (partida_id, n_veces, factor, largo, ancho, alto, cantidad) 
                VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['partida_id'], $data['n_veces'], $data['factor'],
            $data['largo'], $data['ancho'], $data['alto'], $cantidad
        ]);
    }
    
    public function update($id, $data) {
        $cantidad = $this->calcularCantidad($data);
        $sql = "UPDATE computos SET partida_id=?, n_veces=?, factor=?, largo=?, ancho=?, alto=?, cantidad=? 
                WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['partida_id'], $data['n_veces'], $data['factor'],
            $data['largo'], $data['ancho'], $data['alto'], $cantidad, $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM computos WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    private function calcularCantidad($data) {
        $largo = isset($data['largo']) && $data['largo'] !== '' ? $data['largo'] : 1;
        $ancho = isset($data['ancho']) && $data['ancho'] !== '' ? $data['ancho'] : 1;
        $alto = isset($data['alto']) && $data['alto'] !== '' ? $data['alto'] : 1;
        return $data['n_veces'] * $data['factor'] * $largo * $ancho * $alto;
    }
}
?>