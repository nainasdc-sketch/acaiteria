<?php
class LoginCRUD {
    public function create(Login $login) {
        $sql = 'INSERT INTO login (login, senha, nivel) VALUES (?, ?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $login->getLogin());
        $stmt->bindValue(2, $login->getSenha());
        $stmt->bindValue(3, $login->getNivel());
        return $stmt->execute();
    }

    public function read($idLogin) {
        $sql = 'SELECT * FROM login WHERE id_login = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idLogin);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(Login $login) {
        $sql = 'UPDATE login SET senha = ?, nivel = ? WHERE login = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $login->getSenha());
        $stmt->bindValue(2, $login->getNivel());
        $stmt->bindValue(3, $login->getLogin());
        return $stmt->execute();
    }

    public function delete($idLogin) {
        $sql = 'DELETE FROM login WHERE id_login = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idLogin);
        return $stmt->execute();
    }

    public function listAll() {
        $sql = 'SELECT * FROM login';
        $stmt = Conexao::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Adicionando função para verificar se o login já existe
    public function findByLogin($login) {
        $sql = 'SELECT * FROM login WHERE login = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $login);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
