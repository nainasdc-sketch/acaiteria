<?php 
include_once "Conexao.php";
include_once "ClienteClasse.php";

class ClienteCRUD {
    public function create(Cliente $cliente) {
        $sql = 'INSERT INTO cliente (nome, data_nasc, email, fone, status, login_id_login) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $cliente->getNome());
        $stmt->bindValue(2, $cliente->getDataNasc());
        $stmt->bindValue(3, $cliente->getEmail());
        $stmt->bindValue(4, $cliente->getFone());
        $stmt->bindValue(5, $cliente->getStatus());
        $stmt->bindValue(6, $cliente->getLoginId());
        return $stmt->execute();
    }

    public function read($idCliente) {
        $sql = 'SELECT * FROM cliente WHERE cliente_id = ? AND status != 2';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idCliente);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(Cliente $cliente) {
        $sql = 'UPDATE cliente SET nome = ?, data_nasc = ?, email = ?, fone = ?, status = ? WHERE cliente_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $cliente->getNome());
        $stmt->bindValue(2, $cliente->getDataNasc());
        $stmt->bindValue(3, $cliente->getEmail());
        $stmt->bindValue(4, $cliente->getFone());
        $stmt->bindValue(5, $cliente->getStatus());
        $stmt->bindValue(6, $cliente->getIdCliente());
        return $stmt->execute();
    }

    public function delete($idCliente) {
        $sql = 'UPDATE cliente SET status = 2 WHERE cliente_id = ?'; // Status 2 para indicar que está excluído
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idCliente);
        return $stmt->execute();
    }

    public function listAll() {
        $sql = 'SELECT * FROM cliente WHERE status != 2';
        $stmt = Conexao::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findClientByLogin($loginId) {
        $sql = 'SELECT * FROM cliente WHERE login_id_login = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $loginId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
