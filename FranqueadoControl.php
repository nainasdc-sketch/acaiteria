<?php
include_once "Conexao.php";
include_once "FranqueadoClasse.php";
include_once "FranqueadoCRUD.php";

class FranqueadoController {

    private $franqueado;
    private $franqueadoCrud;

    public function __construct() {
        $this->franqueado = new Franqueado();
        $this->franqueadoCrud = new FranqueadoCRUD();
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
                    $this->deletar($d['idFranqueado']);
                    break;
                case 'visualizar':
                    $this->visualizar($d['idFranqueado']);
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

        if ($retorno->status == 'success') {
            $this->franqueado->setNome($data['nome']);
            $this->franqueado->setSobrenome($data['sobrenome']);
            $this->franqueado->setEmail($data['email']);
            $this->franqueado->setFone($data['fone']);
            $this->franqueado->setEstado($data['estado']);
            $this->franqueado->setCidade($data['cidade']);
            $success = $this->franqueadoCrud->create($this->franqueado);
            
            if ($success) {
                $cod_retorno = array(
                    'status' => 'success',
                    'message' => 'Franqueado cadastrado com sucesso.',
                );
            } else {
                $cod_retorno = array(
                    'status' => 'error',
                    'message' => array('Erro ao cadastrar franqueado.'),
                );
            }
        } else {
            $cod_retorno = array(
                'status' => 'error',
                'message' => $retorno->message,
            );
        }

        echo json_encode($cod_retorno);
    }

    public function editar($data) {
        $franqueado = new Franqueado();
        $franqueado->setIdFranqueado($data['idFranqueado']);
        $franqueado->setNome($data['nome']);
        $franqueado->setSobrenome($data['sobrenome']);
        $franqueado->setEmail($data['email']);
        $franqueado->setFone($data['fone']);
        $franqueado->setEstado($data['estado']);
        $franqueado->setCidade($data['cidade']);
    
        if ($this->franqueadoCrud->update($franqueado)) {
            $data = [];
            $data['status'] = "success";
        } else {
            $data['status'] = "error";
        }
    
        echo json_encode($data);
    }

    public function deletar($idFranqueado) {
        if ($this->franqueadoCrud->delete($idFranqueado)) {
            $cod_retorno = array(
                'status' => "success",
                'message' => "Franqueado excluído com sucesso!",
            );
        } else {
            $cod_retorno = array(
                'status' => "error",
                'message' => "Erro ao excluir franqueado!",
            );
        }
        echo json_encode($cod_retorno);
    }

    public function visualizar($idFranqueado) {
        $franqueado = $this->franqueadoCrud->read($idFranqueado);
        echo json_encode($franqueado);
    }

    public function listar() {
        $franqueados = $this->franqueadoCrud->listAll();
        echo json_encode($franqueados);
    }

    private function validateData($data) {
        $errors = [];
        $result = [];

        if (empty($data['nome'])) {
            $errors['nome'] = 'Nome é obrigatório.';
        }

        if (empty($data['sobrenome'])) {
            $errors['sobrenome'] = 'Sobrenome é obrigatório.';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email é obrigatório.';
        }

        if (empty($data['fone'])) {
            $errors['fone'] = 'Telefone é obrigatório.';
        }

        if (empty($data['estado'])) {
            $errors['estado'] = 'Estado é obrigatório.';
        }

        if (empty($data['cidade'])) {
            $errors['cidade'] = 'Cidade é obrigatória.';
        }

        if (!empty($errors)) {
            $result['status'] = false;
            $result['message'] = $errors;
        } else {
            $result['status'] = 'success';
            $result['message'] = 'Dados do franqueado validado com sucesso!';
        }

        return json_encode($result);
    }
}

$controller = new FranqueadoController();
$controller->processRequest();
?>
