<?php
class Categoria {
    private $idCategoria;
    private $nome;
    private $status;

    public function __construct($idCategoria = null, $nome = null, $status = null) {
        $this->idCategoria = $idCategoria;
        $this->nome = $nome;
        $this->status = $status;
    }

    public function getIdCategoria() {
        return $this->idCategoria;
    }

    public function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
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
}