<?php
class Login {
    private $idLogin;
    private $login;
    private $senha;
    private $nivel;

    public function __construct($idLogin = null, $login = null, $senha = null, $nivel = null) {
        $this->idLogin = $idLogin;
        $this->login = $login;
        $this->senha = $senha;
        $this->nivel = $nivel;
    }

    public function getIdLogin() {
        return $this->idLogin;
    }

    public function setIdLogin($idLogin) {
        $this->idLogin = $idLogin;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getNivel() {
        return $this->nivel;
    }

    public function setNivel($nivel) {
        $this->nivel = $nivel;
    }
}