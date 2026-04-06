<?php
class DashboardController {
    private $obraModel;
    
    public function __construct() {
        $this->obraModel = new Obra();
    }
    
    public function index() {
        $obras = $this->obraModel->getAll();
        include 'views/layout/header.php';
        include 'views/dashboard.php';
        include 'views/layout/footer.php';
    }
    
    public function ajaxData() {
        header('Content-Type: application/json');
        $obras = $this->obraModel->getAll();
        $totalObras = count($obras);
        
        $totalPresupuesto = 0;
        $presupuestoModel = new Presupuesto();
        foreach ($obras as $obra) {
            $resumen = $presupuestoModel->getResumenByObra($obra['id']);
            $totalPresupuesto += $resumen['total_presupuesto'];
        }
        
        echo json_encode([
            'success' => true,
            'total_obras' => $totalObras,
            'presupuesto_total' => $totalPresupuesto,
            'obras' => $obras
        ]);
    }
}
?>