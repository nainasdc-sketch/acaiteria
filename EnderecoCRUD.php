<?php
include_once "Conexao.php";

class EnderecoCRUD {
    public function create($data) {
        $sql = 'INSERT INTO endereco (cep, logradouro, numero, complemento, estado, cidade, bairro, cliente_cliente_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $data['cep']);
        $stmt->bindValue(2, $data['logradouro']);
        $stmt->bindValue(3, $data['numero']);
        $stmt->bindValue(4, $data['complemento']);
        $stmt->bindValue(5, $data['estado']);
        $stmt->bindValue(6, $data['cidade']);
        $stmt->bindValue(7, $data['bairro']);
        $stmt->bindValue(8, $data['cliente_id']);
        return $stmt->execute();
    }

    public function findByClienteId($clienteId) {
        $sql = 'SELECT * FROM endereco WHERE cliente_cliente_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $clienteId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($data) {
        $sql = 'UPDATE endereco SET cep = ?, logradouro = ?, numero = ?, complemento = ?, estado = ?, cidade = ?, bairro = ? WHERE cliente_cliente_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $data['cep']);
        $stmt->bindValue(2, $data['logradouro']);
        $stmt->bindValue(3, $data['numero']);
        $stmt->bindValue(4, $data['complemento']);
        $stmt->bindValue(5, $data['estado']);
        $stmt->bindValue(6, $data['cidade']);
        $stmt->bindValue(7, $data['bairro']);
        $stmt->bindValue(8, $data['cliente_id']);
        return $stmt->execute();
    }
}
?>
