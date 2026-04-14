<?php
class Franquia {
    private $idFranquia;
    private $tipo;
    private $qtdeAtual;
    private $preco;

    public function __construct($idFranquia = null, $tipo = null, $qtdeAtual = null, $preco = null) {
        $this->idFranquia = $idFranquia;
        $this->tipo = $tipo;
        $this->qtdeAtual = $qtdeAtual;
        $this->preco = $preco;
    }

    public function getIdFranquia() {
        return $this->idFranquia;
    }

    public function setIdFranquia($idFranquia) {
        $this->idFranquia = $idFranquia;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function getQtdeAtual() {
        return $this->qtdeAtual;
    }

    public function setQtdeAtual($qtdeAtual) {
        $this->qtdeAtual = $qtdeAtual;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function setPreco($preco) {
        $this->preco = $preco;
    }
}
?>
