<?php
include_once "Conexao.php";
include_once "LocalidadeClasse.php";
include_once "LocalidadeCRUD.php";

class LocalidadeController {

    private $localidade;
    private $localidadeCrud;

    public function __construct() {
        $this->localidade = new Localidade();
        $this->localidadeCrud = new LocalidadeCRUD();
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
                    $this->deletar($d['idLocalidade']);
                    break;
                case 'visualizar':
                    $this->visualizar($d['idLocalidade']);
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
            $this->localidade->setEstado($data['estado']);
            $this->localidade->setPermissao($data['permissao']);
            $this->localidadeCrud->create($this->localidade);
        }

        $cod_retorno = array(
            'status' => $retorno->status,
            'message' => $retorno->message,
        );

        echo json_encode($cod_retorno);
    }

    public function editar($data) {
        $localidade = new Localidade();
        $localidade->setIdLocalidade($data['idLocalidade']);
        $localidade->setEstado($data['estado']);
        $localidade->setPermissao($data['permissao']);
    
        if ($this->localidadeCrud->update($localidade)) {
            $data = [];
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
        }
    
        echo json_encode($data);
    }

    public function deletar($idLocalidade) {
        if ($this->localidadeCrud->delete($idLocalidade)) {
            $cod_retorno = array(
                'status' => "success",
                'message' => "Localidade excluída com sucesso!",
            );
        } else {
            $cod_retorno = array(
                'status' => "error",
                'message' => "Erro ao excluir localidade!",
            );
        }
        echo json_encode($cod_retorno);
    }

    public function visualizar($idLocalidade) {
        $localidade = $this->localidadeCrud->read($idLocalidade);
        echo json_encode($localidade);
    }

    public function listar() {
        $localidades = $this->localidadeCrud->listAll();
        echo json_encode($localidades);
    }

    private function validateData($data) {
        $errors = [];
        $result = [];

        if (empty($data['estado'])) {
            $errors['estado'] = 'Estado é obrigatório.';
        }

        if (empty($data['permissao'])) {
            $errors['permissao'] = 'Permissão é obrigatória.';
        }

        if (!empty($errors)) {
            $result['status'] = 'error';
            $result['message'] = $errors;
        } else {
            $result['status'] = 'success';
            $result['message'] = 'Dados da localidade validada.';
        }

        return json_encode($result);
    }
}

$controller = new LocalidadeController();
$controller->processRequest();
?>
