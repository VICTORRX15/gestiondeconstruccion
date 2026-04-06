<?php
class ApuController {
    private $apuModel;
    private $partidaModel;
    private $grupoModel;
    
    public function __construct() {
        $this->apuModel = new Apu();
        $this->partidaModel = new Partida();
        $this->grupoModel = new Grupo();
    }
    
    public function index() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        $grupos = $this->grupoModel->getAllByObra($obra_id);
        include 'views/layout/header.php';
        include 'views/apus.php';
        include 'views/layout/footer.php';
    }
    
    public function ajaxList() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        header('Content-Type: application/json');
        $apus = $this->apuModel->getAllByObra($obra_id);
        echo json_encode(['success' => true, 'data' => $apus]);
    }
    
    public function ajaxGetRecursos() {
        $partida_id = $_GET['partida_id'];
        header('Content-Type: application/json');
        $recursos = $this->apuModel->getRecursosByPartida($partida_id);
        $precio_total = $this->apuModel->getPrecioUnitario($partida_id);
        echo json_encode(['success' => true, 'data' => $recursos, 'precio_total' => $precio_total]);
    }
    
    public function ajaxSaveRecurso() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $id = isset($data['recurso_id']) && $data['recurso_id'] !== '' ? $data['recurso_id'] : null;
            if ($id) {
                $result = $this->apuModel->updateRecurso($id, $data);
            } else {
                $result = $this->apuModel->addRecurso($data);
            }
            echo json_encode(['success' => $result]);
        }
    }
    
    public function ajaxDeleteRecurso() {
        $id = $_POST['id'];
        echo json_encode(['success' => $this->apuModel->deleteRecurso($id)]);
    }
}
?>