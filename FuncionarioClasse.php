<?php
class Funcionario {
    private $idFuncionario;
    private $nome;
    private $status;
    private $loginId;

    public function __construct($idFuncionario = null, $nome = null, $status = null, $loginId = null) {
        $this->idFuncionario = $idFuncionario;
        $this->nome = $nome;
        $this->status = $status;
        $this->loginId = $loginId;
    }

    public function getIdFuncionario() {
        return $this->idFuncionario;
    }

    public function setIdFuncionario($idFuncionario) {
        $this->idFuncionario = $idFuncionario;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getLoginId() {
        return $this->loginId;
    }

    public function setLoginId($loginId) {
        $this->loginId = $loginId;
    }
}