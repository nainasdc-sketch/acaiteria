<?php
include_once "EnderecoCRUD.php";
include_once "Conexao.php";

class EnderecoController {
    private $enderecoCrud;

    public function __construct() {
        $this->enderecoCrud = new EnderecoCRUD();
    }

    public function processRequest() {
        $d = filter_input_array(INPUT_POST);

        if (isset($d['action'])) {
            $action = $d['action'];
            switch ($action) {
                case 'salvarEndereco':
                    $this->salvarEndereco($d);
                    break;
                case 'visualizarEndereco':
                    $this->visualizarEndereco($d['cliente_id']);
                    break;
                default:
                    echo json_encode(['status' => 'error', 'message' => 'Ação inválida!']);
                    break;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhuma ação informada!']);
        }
    }

    public function salvarEndereco($data) {
        try {
            // Iniciar transação
            Conexao::getInstance()->beginTransaction();

            // Verificar se o endereço já existe para este cliente
            $enderecoExistente = $this->enderecoCrud->findByClienteId($data['cliente_id']);

            if ($enderecoExistente) {
                // Atualizar Endereço
                if (!$this->enderecoCrud->update($data)) {
                    throw new Exception("Erro ao atualizar endereço.");
                }
            } else {
                // Criar Endereço
                if (!$this->enderecoCrud->create($data)) {
                    throw new Exception("Erro ao cadastrar endereço.");
                }
            }

            // Commit
            Conexao::getInstance()->commit();

            echo json_encode(['status' => 'success', 'message' => 'Endereço salvo com sucesso.']);
        } catch (Exception $e) {
            // Rollback
            Conexao::getInstance()->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function visualizarEndereco($clienteId) {
        $endereco = $this->enderecoCrud->findByClienteId($clienteId);
        if ($endereco) {
            echo json_encode($endereco);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'vazio', 'cliente_id' => $clienteId]);
        }
    }
}

$controller = new EnderecoController();
$controller->processRequest();
