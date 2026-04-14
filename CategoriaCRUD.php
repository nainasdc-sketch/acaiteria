<?php
include_once "Conexao.php";
include_once "CategoriaClasse.php";

class CategoriaCRUD {
    public function create(Categoria $categoria) {
        $sql = 'INSERT INTO categoria (nome, status) VALUES (?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $categoria->getNome());
        $stmt->bindValue(2, $categoria->getStatus());
        return $stmt->execute();
    }

    public function read($idCategoria) {
        $sql = 'SELECT * FROM categoria WHERE categoria_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idCategoria);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update(Categoria $categoria) {
        $sql = 'UPDATE categoria SET nome = ?, status = ? WHERE categoria_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $categoria->getNome());
        $stmt->bindValue(2, $categoria->getStatus());
        $stmt->bindValue(3, $categoria->getIdCategoria());
        return $stmt->execute();
    }
    
    public function delete($idCategoria) {
        $sql = 'DELETE FROM categoria WHERE categoria_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idCategoria);
        return $stmt->execute();
    }
    
    public function listAll() {
        $sql = 'SELECT * FROM categoria WHERE status IN (0, 1) ORDER BY nome ASC';
        $stmt = Conexao::getInstance()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $categorias = [];
        foreach ($result as $row) {
            $categoria = new Categoria();
            $categoria->setIdCategoria($row['categoria_id']);
            $categoria->setNome($row['nome']);
            $categoria->setStatus($row['status']);
            $categorias[] = $categoria;
        }
    
        return $categorias;
    }
    

    public function getNomeById($idCategoria) {
        $sql = 'SELECT nome FROM categoria WHERE categoria_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idCategoria);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['nome'];
    }
}