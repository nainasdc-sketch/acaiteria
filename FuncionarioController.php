<?php
include_once "FuncionarioCRUD.php";
include_once "LoginCRUD.php";
include_once "FuncionarioClasse.php";
include_once "LoginClasse.php";
include_once "Conexao.php";

class FuncionarioController {
    private $funcionarioCrud;
    private $loginCrud;

    public function __construct() {
        $this->funcionarioCrud = new FuncionarioCRUD();
        $this->loginCrud = new LoginCRUD();
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
                    $this->deletar($d['funcionario_id']);
                    break;
                case 'visualizar':
                    $this->visualizar($d['funcionario_id']);
                    break;
                case 'listar':
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
            $login->setNivel(1); // Nível 1 para funcionário

            if (!$this->loginCrud->create($login)) {
                throw new Exception("Erro ao cadastrar login.");
            }

            $loginId = Conexao::getInstance()->lastInsertId();

            // Criar Funcionário
            $funcionario = new Funcionario();
            $funcionario->setNome($data['nome']);
            $funcionario->setStatus($data['status']);
            $funcionario->setLoginId($loginId);

            if (!$this->funcionarioCrud->create($funcionario)) {
                throw new Exception("Erro ao cadastrar funcionário.");
            }

            // Commit
            Conexao::getInstance()->commit();

            echo json_encode(['status' => 'success', 'message' => 'Funcionário cadastrado com sucesso.']);
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

            // Verifica se o login e senha estão corretos
            $loginAtual = $this->loginCrud->findByLogin($data['fone']);

            if (password_verify($data['senha'], $loginAtual['senha'])) {
                $senhaFinal = $data['novaSenha'] != null ? password_hash($data['novaSenha'], PASSWORD_DEFAULT) : $loginAtual['senha'];
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Senha incorreta.']);
                return;
            }

            // Atualizar Login
            $login = new Login();
            $login->setLogin($data['fone']); // Utilizando o telefone como login
            $login->setSenha($senhaFinal);
            $login->setNivel(1); // Nível 1 para funcionário

            if (!$this->loginCrud->update($login)) {
                throw new Exception("Erro ao editar login.");
            }

            // Atualizar Funcionário
            $funcionario = new Funcionario();
            $funcionario->setIdFuncionario($data['funcionario_id']);
            $funcionario->setNome($data['nome']);
            $funcionario->setStatus($data['status']);
            $funcionario->setLoginId($loginAtual['id_login']);

            if (!$this->funcionarioCrud->update($funcionario)) {
                throw new Exception("Erro ao editar funcionário.");
            }

            // Commit
            Conexao::getInstance()->commit();

            echo json_encode(['status' => 'success', 'message' => 'Funcionário editado com sucesso.']);
        } catch (Exception $e) {
            // Rollback
            Conexao::getInstance()->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function deletar($idFuncionario) {
        try {
            // Iniciar transação
            Conexao::getInstance()->beginTransaction();

            // Buscar o login_id relacionado ao funcionário
            $funcionario = $this->funcionarioCrud->read($idFuncionario);
            if (!$funcionario) {
                throw new Exception("Funcionário não encontrado.");
            }
            $loginId = $funcionario['login_id_login'];

            // Deletar Funcionário
            if (!$this->funcionarioCrud->delete($idFuncionario)) {
                throw new Exception("Erro ao excluir funcionário.");
            }

            // Deletar Login
            if (!$this->loginCrud->delete($loginId)) {
                throw new Exception("Erro ao excluir login.");
            }

            // Commit
            Conexao::getInstance()->commit();

            echo json_encode(['status' => 'success', 'message' => 'Funcionário excluído com sucesso.']);
        } catch (Exception $e) {
            // Rollback
            Conexao::getInstance()->rollBack();
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function visualizar($idFuncionario) {
        $funcionario = $this->funcionarioCrud->read($idFuncionario);
        if ($funcionario) {
            $login = $this->loginCrud->read($funcionario['login_id_login']);
            if ($login) {
                $funcionario['login'] = $login['login'];
                $funcionario['fone'] = $login['login'];
                echo json_encode($funcionario);
                return;
            }
        }
        echo json_encode(['status' => 'error', 'message' => 'Funcionário não encontrado.']);
    }

    public function listar() {
        $funcionarios = $this->funcionarioCrud->listAll();

        if ($funcionarios) {
            echo json_encode($funcionarios);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhum funcionário encontrado.']);
        }
    }
}

$controller = new FuncionarioController();
$controller->processRequest();
?>
