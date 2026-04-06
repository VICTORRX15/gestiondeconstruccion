<?php
class PartidaController {
    private $partidaModel;
    
    public function __construct() {
        $this->partidaModel = new Partida();
    }
    
    public function ajaxListByGrupo() {
        $grupo_id = isset($_GET['grupo_id']) ? $_GET['grupo_id'] : 0;
        header('Content-Type: application/json');
        $partidas = $this->partidaModel->getAllByGrupo($grupo_id);
        echo json_encode($partidas);
    }
    
    public function ajaxListByObra() {
        $obra_id = isset($_GET['obra_id']) ? $_GET['obra_id'] : 0;
        header('Content-Type: application/json');
        $partidas = $this->partidaModel->getAllByObra($obra_id);
        echo json_encode($partidas);
    }
    
    public function ajaxSave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $id = isset($data['id']) && $data['id'] !== '' ? $data['id'] : null;
            if ($id) {
                $result = $this->partidaModel->update($id, $data);
            } else {
                $result = $this->partidaModel->create($data);
            }
            echo json_encode(['success' => $result]);
        }
    }
    
    public function ajaxDelete() {
        $id = $_POST['id'];
        echo json_encode(['success' => $this->partidaModel->delete($id)]);
    }
}
?>