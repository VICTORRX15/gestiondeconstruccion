<?php
class GrupoController {
    private $grupoModel;
    
    public function __construct() {
        $this->grupoModel = new Grupo();
    }
    
    public function ajaxList() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        header('Content-Type: application/json');
        $grupos = $this->grupoModel->getAllByObra($obra_id);
        echo json_encode($grupos);
    }
    
    public function ajaxSave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $id = isset($data['id']) && $data['id'] !== '' ? $data['id'] : null;
            if ($id) {
                $result = $this->grupoModel->update($id, $data);
            } else {
                $result = $this->grupoModel->create($data);
            }
            echo json_encode(['success' => $result]);
        }
    }
    
    public function ajaxDelete() {
        $id = $_POST['id'];
        echo json_encode(['success' => $this->grupoModel->delete($id)]);
    }
}
?>