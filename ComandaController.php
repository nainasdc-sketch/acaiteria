<?php
include_once "ComandaCRUD.php";
include_once "Conexao.php";

class ComandaController {
    private $comandaCrud;

    public function __construct() {
        $this->comandaCrud = new ComandaCRUD();
    }

    public function processRequest() {
        $d = filter_input_array(INPUT_POST);

        if (isset($d['action'])) {
            $action = $d['action'];
            switch ($action) {
                case 'deletar_comanda':
                    $this->deletarComanda($d['comanda_id']);
                    break;
                default:
                    echo json_encode(['status' => 'error', 'message' => 'Ação inválida!']);
                    break;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhuma ação informada!']);
        }
    }

    public function deletarComanda($comandaId) {

        if ($this->comandaCrud->delete($comandaId)) {
            echo json_encode(['status' => 'success', 'message' => 'Comanda excluída com sucesso.']);
        }else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir comanda.']);
            exit;
        }
        
    }
}

$controller = new ComandaController();
$controller->processRequest();
