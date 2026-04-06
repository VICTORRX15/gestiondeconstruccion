<?php
class ObraController {
    private $obraModel;
    
    public function __construct() {
        $this->obraModel = new Obra();
    }
    
    public function index() {
        $obras = $this->obraModel->getAll();
        include 'views/layout/header.php';
        include 'views/obras.php';
        include 'views/layout/footer.php';
    }
    
    public function ver($id) {
        $obra = $this->obraModel->getById($id);
        $resumen = $this->obraModel->getResumen($id);
        
        $presupuestoModel = new Presupuesto();
        $presupuesto = $presupuestoModel->getResumenByObra($id);
        
        $apuModel = new Apu();
        $apus = $apuModel->getAllByObra($id);
        
        include 'views/layout/header.php';
        include 'views/obra_detalle.php';
        include 'views/layout/footer.php';
    }
    
    public function ajaxList() {
        header('Content-Type: application/json');
        echo json_encode($this->obraModel->getAll());
    }
    
    public function ajaxSave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $id = isset($data['id']) && $data['id'] !== '' ? $data['id'] : null;
            if ($id) {
                $result = $this->obraModel->update($id, $data);
            } else {
                $result = $this->obraModel->create($data);
            }
            echo json_encode(['success' => $result]);
        }
    }
    
    public function ajaxDelete() {
        $id = $_POST['id'];
        echo json_encode(['success' => $this->obraModel->delete($id)]);
    }
}
?>