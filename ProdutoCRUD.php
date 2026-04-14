<?php
include_once "Conexao.php";
include_once "ProdutoClasse.php";

class ProdutoCRUD {
    public function create(Produto $produto) {
        // Verificar se a categoria é válida
        if (!$this->isCategoriaValida($produto->getCategoriaId())) {
            throw new Exception("Categoria inválida!");
        }

        $sql = 'INSERT INTO produto (nome, ingredientes, descricao, preco, status, foto_nome, foto_pasta, categoria_categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $produto->getNome());
        $stmt->bindValue(2, $produto->getIngredientes());
        $stmt->bindValue(3, $produto->getDescricao());
        $stmt->bindValue(4, $produto->getPreco());
        $stmt->bindValue(5, $produto->getStatus());
        $stmt->bindValue(6, $produto->getFotoNome());
        $stmt->bindValue(7, $produto->getFotoPasta());
        $stmt->bindValue(8, $produto->getCategoriaId());
        return $stmt->execute();
    }

    public function read($idProduto) {
        $sql = 'SELECT * FROM produto WHERE produto_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idProduto);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(Produto $produto) {
        // Verificar se a categoria é válida
        if (!$this->isCategoriaValida($produto->getCategoriaId())) {
            throw new Exception("Categoria inválida!");
        }

        $sql = 'UPDATE produto SET nome = ?, ingredientes = ?, descricao = ?, preco = ?, status = ?, foto_nome = ?, foto_pasta = ?, categoria_categoria_id = ? WHERE produto_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $produto->getNome());
        $stmt->bindValue(2, $produto->getIngredientes());
        $stmt->bindValue(3, $produto->getDescricao());
        $stmt->bindValue(4, $produto->getPreco());
        $stmt->bindValue(5, $produto->getStatus());
        $stmt->bindValue(6, $produto->getFotoNome());
        $stmt->bindValue(7, $produto->getFotoPasta());
        $stmt->bindValue(8, $produto->getCategoriaId());
        $stmt->bindValue(9, $produto->getIdProduto());
        return $stmt->execute();
    }

    public function delete($idProduto) {
        $sql = 'UPDATE produto SET status = 2 WHERE produto_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idProduto);
        return $stmt->execute();
    }

    public function listAll() {
        $sql = 'SELECT * FROM produto WHERE status IN (0, 1) ORDER BY nome ASC';
        $stmt = Conexao::getInstance()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $produtos = [];
        foreach ($result as $row) {
            $produto = new Produto();
            $produto->setIdProduto($row['produto_id']);
            $produto->setNome($row['nome']);
            $produto->setIngredientes($row['ingredientes']);
            $produto->setDescricao($row['descricao']);
            $produto->setPreco($row['preco']);
            $produto->setStatus($row['status']);
            $produto->setFotoNome($row['foto_nome']);
            $produto->setFotoPasta($row['foto_pasta']);
            $produto->setCategoriaId($row['categoria_categoria_id']);
            $produtos[] = $produto;
        }
    
        return $produtos;
    }

    public function getProdutoNomeById($idProduto) {
        $sql = 'SELECT nome FROM produto WHERE produto_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idProduto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['nome'];
    }

    private function isCategoriaValida($categoriaId) {
        $sql = 'SELECT COUNT(*) as count FROM categoria WHERE categoria_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $categoriaId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
}
