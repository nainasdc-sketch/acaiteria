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

class Comanda {
    private $comanda_id;
    private $cod_comanda;
    private $taxa_entrega;
    private $tipo_retirada;
    private $taxa_extra;
    private $taxa_info;
    private $data_cad;
    private $status;
    private $forma_pagamento;
    private $observacao;
    private $clienteId;

    public function __construct($comanda_id = null, $cod_comanda = null, $taxa_entrega = null, $tipo_retirada = null, $taxa_extra = null, $taxa_info = null, $data_cad = null, $status = null, $forma_pagamento = null, $observacao = null, $cliente_cliente_id = null) {
        $this->comanda_id = $comanda_id;
        $this->cod_comanda = $cod_comanda;
        $this->taxa_entrega = $taxa_entrega;
        $this->tipo_retirada = $tipo_retirada;
        $this->taxa_extra = $taxa_extra;
        $this->taxa_info = $taxa_info;
        $this->data_cad = $data_cad;
        $this->status = $status;
        $this->forma_pagamento = $forma_pagamento;
        $this->observacao = $observacao;
        $this->clienteId = $cliente_cliente_id;
    }

    public function getComanda_id() {
        return $this->comanda_id;
    }

    public function setComanda_id($comanda_id) {
        $this->comanda_id = $comanda_id;
    }

    public function getCod_comanda() {
        return $this->cod_comanda;
    }

    public function setCod_comanda($cod_comanda) {
        $this->cod_comanda = $cod_comanda;
    }

    public function getTaxa_entrega() {
        return $this->taxa_entrega;
    }

    public function setTaxa_entrega($taxa_entrega) {
        $this->taxa_entrega = $taxa_entrega;
    }

    public function getTipo_retirada() {
        return $this->tipo_retirada;
    }

    public function setTipo_retirada($tipo_retirada) {
        $this->tipo_retirada = $tipo_retirada;
    }

    public function getTaxa_extra() {
        return $this->taxa_extra;
    }

    public function setTaxa_extra($taxa_extra) {
        $this->taxa_extra = $taxa_extra;
    }

    public function getTaxa_info() {
        return $this->taxa_info;
    }

    public function setTaxa_info($taxa_info) {
        $this->taxa_info = $taxa_info;
    }

    public function getData_cad() {
        return $this->data_cad;
    }

    public function setData_cad($data_cad) {
        $this->data_cad = $data_cad;
    }

    public function getStatus() {
        return $this->status;
    }
    
    public function setStatus($status) {
        $this->status = $status;
    }

    public function setForma_pagamento($forma_pagamento) {
        $this->forma_pagamento = $forma_pagamento;
    }

    public function getForma_pagamento() {
        return $this->forma_pagamento;
    }

    public function setObservacao($observacao) {
        $this->observacao = $observacao;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function getClienteId() {
        return $this->clienteId;
    }

    public function setClienteId($clienteId) {
        $this->clienteId = $clienteId;
    }
}

class Pedido {
    private $pedido_id;
    private $fk_comanda_id;
    private $fk_cliente_id;
    private $fk_produto_id;
    private $qtde;
    private $preco_un;
    private $preco_tt;
    private $status;

    public function __construct($pedido_id = null, $fk_comanda_id = null, $fk_cliente_id = null, $fk_produto_id = null, $qtde = null, $preco_un = null, $preco_tt = null, $status = null) {
        $this->pedido_id = $pedido_id;
        $this->fk_comanda_id = $fk_comanda_id;
        $this->fk_cliente_id = $fk_cliente_id;
        $this->fk_produto_id = $fk_produto_id;
        $this->qtde = $qtde;
        $this->preco_un = $preco_un;
        $this->preco_tt = $preco_tt;
        $this->status = $status;
    }

    // Getter e Setter para pedido_id
    public function getPedidoId() {
        return $this->pedido_id;
    }

    public function setPedidoId($pedido_id) {
        $this->pedido_id = $pedido_id;
    }

    // Getter e Setter para fk_comanda_id
    public function getFkComandaId() {
        return $this->fk_comanda_id;
    }

    public function setFkComandaId($fk_comanda_id) {
        $this->fk_comanda_id = $fk_comanda_id;
    }

    // Getter e Setter para fk_cliente_id
    public function getFkClienteId() {
        return $this->fk_cliente_id;
    }

    public function setFkClienteId($fk_cliente_id) {
        $this->fk_cliente_id = $fk_cliente_id;
    }

    // Getter e Setter para fk_produto_id
    public function getFkProdutoId() {
        return $this->fk_produto_id;
    }

    public function setFkProdutoId($fk_produto_id) {
        $this->fk_produto_id = $fk_produto_id;
    }

    // Getter e Setter para qtde
    public function getQtde() {
        return $this->qtde;
    }

    public function setQtde($qtde) {
        $this->qtde = (int)$qtde;
    }

    // Getter e Setter para preco_un
    public function getPrecoUn() {
        return $this->preco_un;
    }

    public function setPrecoUn($preco_un) {
        $this->preco_un = (float)$preco_un;
    }

    // Getter e Setter para preco_tt
    public function getPrecoTt() {
        return $this->preco_tt;
    }

    public function setPrecoTt($preco_tt) {
        $this->preco_tt = (float)$preco_tt;
    }

    // Getter e Setter para status
    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = (int)$status;
    }
}
