<?php
include_once "Conexao.php";
include_once "FranquiaClasse.php";
include_once "FranquiaCRUD.php";

class FranquiaController {

    private $franquia;
    private $franquiaCrud;

    public function __construct() {
        $this->franquia = new Franquia();
        $this->franquiaCrud = new FranquiaCRUD();
    }

    public function processRequest() {
        $d = filter_input_array(INPUT_POST);

        if (isset($d['action'])) {
            $action = $d['action'];
            switch ($action) {
                case 'cadastrar':
                    $this->cadastrar($d);
                    break;
                case 'editar':
                    $this->editar($d);
                    break;
                case 'deletar':
                    $this->deletar($d['idFranquia']);
                    break;
                case 'visualizar':
                    $this->visualizar($d['idFranquia']);
                    break;
                case 'listar':
                    $this->listar();
                    break;
                default:
                    header("Location: ../../");
                    break;
            }
        } else {
            header("Location: ../../");
        }
    }

    public function cadastrar($data) {
        $retorno = json_decode($this->validateData($data));

        if ($retorno->status == "success") {
            $this->franquia->setTipo($data['tipo']);
            $this->franquia->setQtdeAtual($data['qtde_atual']);
            $this->franquia->setPreco(number_format($data['preco'], 2, ',', '.'));
            $this->franquiaCrud->create($this->franquia);
        }

        $cod_retorno = array(
            'status' => $retorno->status,
            'message' => $retorno->message,
        );

        echo json_encode($cod_retorno);
    }

    public function editar($data) {
        $franquia = new Franquia();
        $franquia->setIdFranquia($data['idFranquia']);
        $franquia->setTipo($data['tipo']);
        $franquia->setQtdeAtual($data['qtde_atual']);
        $franquia->setPreco($data['preco']);
    
        if ($this->franquiaCrud->update($franquia)) {
            $data = [];
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
        }
    
        echo json_encode($data);
    }

    public function deletar($idFranquia) {
        if ($this->franquiaCrud->delete($idFranquia)) {
            $cod_retorno = array(
                'status' => "success",
                'message' => "Franquia excluída com sucesso!",
            );
        } else {
            $cod_retorno = array(
                'status' => "error",
                'message' => "Erro ao excluir franquia!",
            );
        }
        echo json_encode($cod_retorno);
    }

    public function visualizar($idFranquia) {
        $franquia = $this->franquiaCrud->read($idFranquia);
        echo json_encode($franquia);
    }

    public function listar() {
        $franquias = $this->franquiaCrud->listAll();
        echo json_encode($franquias);
    }

    private function validateData($data) {
        $errors = [];
        $result = [];

        if (empty($data['tipo'])) {
            $errors['tipo'] = 'Tipo é obrigatório.';
        }

        if (empty($data['qtde_atual'])) {
            $errors['qtde_atual'] = 'Quantidade atual é obrigatória';
        }

        if (empty($data['preco'])) {
            $errors['preco'] = 'Preço é obrigatório.';
        }

        if (!empty($errors)) {
            $result['status'] = "error";
            $result['message'] = $errors;
        } else {
            $result['status'] = 'success';
            $result['message'] = 'Dados da franquia validado com sucesso!';
        }

        return json_encode($result);
    }
}

$controller = new FranquiaController();
$controller->processRequest();
?>
