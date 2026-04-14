<?php
class Produto {
    private $idProduto;
    private $nome;
    private $ingredientes;
    private $descricao;
    private $preco;
    private $status;
    private $fotoNome;
    private $fotoPasta;
    private $categoriaId;

    public function __construct($idProduto = null, $nome = null, $ingredientes = null, $descricao = null, $preco = null, $status = null, $fotoNome = null, $fotoPasta = null, $categoriaId = null) {
        $this->idProduto = $idProduto;
        $this->nome = $nome;
        $this->ingredientes = $ingredientes;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->status = $status;
        $this->fotoNome = $fotoNome;
        $this->fotoPasta = $fotoPasta;
        $this->categoriaId = $categoriaId;
    }

    public function getIdProduto() {
        return $this->idProduto;
    }

    public function setIdProduto($idProduto) {
        $this->idProduto = $idProduto;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getIngredientes() {
        return $this->ingredientes;
    }

    public function setIngredientes($ingredientes) {
        $this->ingredientes = $ingredientes;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function setPreco($preco) {
        $this->preco = $preco;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getFotoNome() {
        return $this->fotoNome;
    }

    public function setFotoNome($fotoNome) {
        $this->fotoNome = $fotoNome;
    }

    public function getFotoPasta() {
        return $this->fotoPasta;
    }

    public function setFotoPasta($fotoPasta) {
        $this->fotoPasta = $fotoPasta;
    }

    public function getCategoriaId() {
        return $this->categoriaId;
    }

    public function setCategoriaId($categoriaId) {
        $this->categoriaId = $categoriaId;
    }
}