<?php
class ComputoController {
    private $computoModel;
    
    public function __construct() {
        $this->computoModel = new Computo();
    }
    
    public function index() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        include 'views/layout/header.php';
        include 'views/computos.php';
        include 'views/layout/footer.php';
    }
    
    public function ajaxList() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        header('Content-Type: application/json');
        echo json_encode($this->computoModel->getAllByObra($obra_id));
    }
    
    public function ajaxSave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $id = isset($data['id']) && $data['id'] !== '' ? $data['id'] : null;
            $data['largo'] = ($data['largo'] === '') ? null : $data['largo'];
            $data['ancho'] = ($data['ancho'] === '') ? null : $data['ancho'];
            $data['alto'] = ($data['alto'] === '') ? null : $data['alto'];
            
            if ($id) {
                $result = $this->computoModel->update($id, $data);
            } else {
                $result = $this->computoModel->create($data);
            }
            echo json_encode(['success' => $result]);
        }
    }
    
    public function ajaxDelete() {
        $id = $_POST['id'];
        echo json_encode(['success' => $this->computoModel->delete($id)]);
    }
}
?>