<?php
class Cliente {
    private $idCliente;
    private $nome;
    private $dataNasc;
    private $email;
    private $fone;
    private $status;
    private $loginId;

    public function __construct($idCliente = null, $nome = null, $dataNasc = null, $email = null, $fone = null, $status = null, $loginId = null) {
        $this->idCliente = $idCliente;
        $this->nome = $nome;
        $this->dataNasc = $dataNasc;
        $this->email = $email;
        $this->fone = $fone;
        $this->status = $status;
        $this->loginId = $loginId;
    }

    public function getIdCliente() {
        return $this->idCliente;
    }

    public function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getDataNasc() {
        return $this->dataNasc;
    }

    public function setDataNasc($dataNasc) {
        $this->dataNasc = $dataNasc;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getFone() {
        return $this->fone;
    }

    public function setFone($fone) {
        $this->fone = $fone;
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