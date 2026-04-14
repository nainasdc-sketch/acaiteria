<?php
class LoginController {
    private $login;

    public function __construct() {
        $this->login = new Login();
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
                    $this->deletar($d['idLogin']);
                    break;
                case 'visualizar':
                    $this->visualizar($d['idLogin']);
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
        $this->login->setLogin($data['login']);
        $this->login->setSenha($data['senha']);
        $this->login->setNivel($data['nivel']);
        // Implementar lógica para salvar login
    }

    public function editar($data) {
        $this->login->setIdLogin($data['idLogin']);
        $this->login->setLogin($data['login']);
        $this->login->setSenha($data['senha']);
        $this->login->setNivel($data['nivel']);
        // Implementar lógica para editar login
    }

    public function deletar($idLogin) {
        // Implementar lógica para deletar login
    }

    public function visualizar($idLogin) {
        // Implementar lógica para visualizar login
    }

    public function listar() {
        // Implementar lógica para listar logins
    }
}