<?php
class PresupuestoController {
    private $presupuestoModel;
    
    public function __construct() {
        $this->presupuestoModel = new Presupuesto();
    }
    
    public function index() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        include 'views/layout/header.php';
        include 'views/presupuestos.php';
        include 'views/layout/footer.php';
    }
    
    public function ajaxData() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        header('Content-Type: application/json');
        try {
            $resumen = $this->presupuestoModel->getResumenByObra($obra_id);
            echo json_encode(['success' => true, 'data' => $resumen]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
?>