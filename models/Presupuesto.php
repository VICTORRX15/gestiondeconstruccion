<?php
class Presupuesto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getConnection();
    }
    
    public function getConsolidadoPorObra($obra_id) {
        $sql = "SELECT g.id as grupo_id, g.codigo as grupo_codigo, g.nombre as grupo_nombre,
                       p.id as partida_id, p.codigo, p.descripcion, p.unidad,
                       COALESCE(SUM(c.cantidad), 0) as cantidad_computada,
                       COALESCE((SELECT SUM(r.cantidad * r.precio_unitario) 
                                 FROM apu_recursos r WHERE r.partida_id = p.id), 0) as precio_unitario
                FROM grupos g
                LEFT JOIN partidas p ON p.grupo_id = g.id
                LEFT JOIN computos c ON p.id = c.partida_id
                WHERE g.obra_id = ?
                GROUP BY g.id, p.id
                ORDER BY g.orden, p.codigo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$obra_id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $grupos = [];
        foreach ($rows as $row) {
            $grupo_id = $row['grupo_id'];
            if (!isset($grupos[$grupo_id])) {
                $grupos[$grupo_id] = [
                    'grupo_codigo' => $row['grupo_codigo'],
                    'grupo_nombre' => $row['grupo_nombre'],
                    'partidas' => [],
                    'subtotal' => 0
                ];
            }
            if ($row['partida_id']) {
                $costo_total = $row['cantidad_computada'] * $row['precio_unitario'];
                $grupos[$grupo_id]['partidas'][] = [
                    'codigo' => $row['codigo'],
                    'descripcion' => $row['descripcion'],
                    'unidad' => $row['unidad'],
                    'cantidad' => floatval($row['cantidad_computada']),
                    'precio_unitario' => floatval($row['precio_unitario']),
                    'costo_total' => floatval($costo_total)
                ];
                $grupos[$grupo_id]['subtotal'] += $costo_total;
            }
        }
        return $grupos;
    }
    
    public function getResumenByObra($obra_id) {
        $grupos = $this->getConsolidadoPorObra($obra_id);
        $total = 0;
        foreach ($grupos as $grupo) {
            $total += $grupo['subtotal'];
        }
        return ['total_presupuesto' => $total, 'grupos' => $grupos];
    }
}
?>