<?php
class CronogramaController {
    private $cronogramaModel;
    
    public function __construct() {
        $this->cronogramaModel = new CronogramaActividad();
    }
    
    public function index() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        include 'views/layout/header.php';
        include 'views/cronograma.php';
        include 'views/layout/footer.php';
    }
    
    public function ajaxList() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        header('Content-Type: application/json');
        echo json_encode($this->cronogramaModel->getAllByObra($obra_id));
    }
    
    public function ajaxSave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $id = isset($data['id']) && $data['id'] !== '' ? $data['id'] : null;
            if ($id) {
                $result = $this->cronogramaModel->update($id, $data);
            } else {
                $result = $this->cronogramaModel->create($data);
            }
            echo json_encode(['success' => $result]);
        }
    }
    
    public function ajaxDelete() {
        $id = $_POST['id'];
        echo json_encode(['success' => $this->cronogramaModel->delete($id)]);
    }
}
?>