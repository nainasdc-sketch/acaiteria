<?php
class Endereco {
    private $idEndereco;
    private $descricao;
    private $cep;
    private $casa;
    private $observacao;
    private $clienteId;

    public function __construct($idEndereco = null, $descricao = null, $cep = null, $casa = null, $observacao = null, $clienteId = null) {
        $this->idEndereco = $idEndereco;
        $this->descricao = $descricao;
        $this->cep = $cep;
        $this->casa = $casa;
        $this->observacao = $observacao;
        $this->clienteId = $clienteId;
    }

    public function getIdEndereco() {
        return $this->idEndereco;
    }

    public function setIdEndereco($idEndereco) {
        $this->idEndereco = $idEndereco;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getCep() {
        return $this->cep;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function getCasa() {
        return $this->casa;
    }

    public function setCasa($casa) {
        $this->casa = $casa;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    public function getClienteId() {
        return $this->clienteId;
    }

    public function setClienteId($clienteId) {
        $this->clienteId = $clienteId;
    }
}