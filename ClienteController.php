<?php
include_once "ClienteCRUD.php";
include_once "ClienteClasse.php";
include_once "LoginCRUD.php";
include_once "LoginClasse.php";
include_once "FuncionarioCRUD.php";
include_once "FuncionarioClasse.php";
include_once "Conexao.php";

class ClienteController {
    private $clienteCrud;
    private $loginCrud;
    private $funcionarioCrud;

    public function __construct() {
        $this->clienteCrud = new ClienteCRUD();
        $this->loginCrud = new LoginCRUD();
        $this->funcionarioCrud = new FuncionarioCRUD();
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
                    $this->deletar($d['cliente_id']);
                    break;
                case 'visualizar':
                    $this->visualizar($d['cliente_id']);
                    break;
                case 'listar':
                    $this->listar();
                    break;
                case 'recuperar_senha':
                    $this->listar();
                    break;
                default:
                    echo json_encode(['status' => 'error', 'message' => 'Ação inválida!']);
                    break;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhuma ação informada!']);
        }
    }

    public function cadastrar($data) {
        try {
            // Verificar se o telefone (login) já está cadastrado
            if ($this->loginCrud->findByLogin($data['fone'])) {
                echo json_encode(['status' => 'error', 'message' => 'Este telefone já está cadastrado.']);
                return;
            }

            // Iniciar transação
            Conexao::getInstance()->beginTransaction();

            // Criar Login
            $login = new Login();
            $login->setLogin($data['fone']); // Utilizando o telefone como login
            $login->setSenha(password_hash($data['senha'], PASSWORD_DEFAULT));
            $login->setNivel(0); // Nível 0 para cliente

            if (!$this->loginCrud->create($login)) {
                throw new Exception("Erro ao cadastrar login.");
            }

            $loginId = Conexao::getInstance()->lastInsertId();

            // Criar Cliente
            $cliente = new Cliente();
            $cliente->setNome($data['nome']);
            $cliente->setDataNasc($data['data_nasc']);
            $cliente->setEmail($data['email']);
            $cliente->setFone($data['fone']);
            $cliente->setStatus(0); // Ativo por padrão
            $cliente->setLoginId($loginId);

            if (!$this->clienteCrud->create($cliente)) {
                throw new Exception("Erro ao cadastrar cliente.");
            }

            // Commit
            Conexao::getInstance()->commit();

            echo json_encode(['status' => 'success', 'message' => 'Cliente cadastrado com sucesso.']);
        } catch (Exception $e) {
            // Rollback
            Conexao::getInstance()->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function editar($data) {
        try {
            // Iniciar transação
            Conexao::getInstance()->beginTransaction();

            //Verifica se o login e senha estão corretos
            $loginAtual = $this->loginCrud->findByLogin($data['fone']);
                
            if(password_verify($data['senha'], $loginAtual['senha'])){
                if($data['novaSenha'] != null)
                    $senhaFinal = password_hash($data['novaSenha'], PASSWORD_DEFAULT);
                else
                    $senhaFinal = password_hash($data['senha'], PASSWORD_DEFAULT);
            }else{
                echo json_encode(['status' => 'error', 'message' => 'Senha incorreta.']);
                return;
            }

            // Criar Login
            $login = new Login();
            $login->setLogin($data['fone']); // Utilizando o telefone como login
            $login->setSenha($senhaFinal);
            $login->setNivel($data['nivel']);
            
            // Atualizar Cliente
            $cliente = new Cliente();
            $cliente->setIdCliente($data['cliente_id']);
            $cliente->setNome($data['nome']);
            $cliente->setDataNasc($data['data_nasc']);
            $cliente->setEmail($data['email']);
            $cliente->setFone($data['fone']);
            $cliente->setStatus($data['status']);

            if (!$this->loginCrud->update($login)) {
                throw new Exception("Erro ao editar Login.");
            }
            if (!$this->clienteCrud->update($cliente)) {
                throw new Exception("Erro ao editar cliente.");
            }

            // Commit
            Conexao::getInstance()->commit();

            echo json_encode(['status' => 'success', 'message' => 'Cliente editado com sucesso.']);
        } catch (Exception $e) {
            // Rollback
            Conexao::getInstance()->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deletar($idCliente) {
        try {
            // Iniciar transação
            Conexao::getInstance()->beginTransaction();

            // Buscar o login_id relacionado ao cliente
            $cliente = $this->clienteCrud->read($idCliente);
            if (!$cliente) {
                throw new Exception("Cliente não encontrado.");
            }
            $loginId = $cliente['login_id_login'];

            // Deletar Cliente
            if (!$this->clienteCrud->delete($idCliente)) {
                throw new Exception("Erro ao excluir cliente.");
            }

            // Deletar Login
            if (!$this->loginCrud->delete($loginId)) {
                throw new Exception("Erro ao excluir login.");
            }

            // Commit
            Conexao::getInstance()->commit();

            echo json_encode(['status' => 'success', 'message' => 'Cliente excluído com sucesso.']);
        } catch (Exception $e) {
            // Rollback
            Conexao::getInstance()->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function visualizar($idCliente) {
        $cliente = $this->clienteCrud->read($idCliente);
        if ($cliente) {
            $login = $this->loginCrud->read($cliente['login_id_login']);
            if ($login) {
                $cliente['login'] = $login['login'];
                $cliente['nivel'] = $login['nivel'];
                // Enviar todos os dados do cliente, incluindo email e nível de login
                echo json_encode($cliente);
                return;
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'Cliente não encontrado.']);
    }

    public function listar() {
        $clientes = $this->clienteCrud->listAll();

        if ($clientes) {
            echo json_encode($clientes);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhum cliente encontrado.']);
        }
    }
}

$controller = new ClienteController();
$controller->processRequest();
