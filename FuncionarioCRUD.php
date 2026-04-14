<?php 
include_once "Conexao.php";
include_once "FuncionarioClasse.php";

class FuncionarioCRUD {
    public function create(Funcionario $funcionario) {
        $sql = 'INSERT INTO funcionario (nome, status, login_id_login) VALUES (?, ?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $funcionario->getNome());
        $stmt->bindValue(2, $funcionario->getStatus());
        $stmt->bindValue(3, $funcionario->getLoginId());
        return $stmt->execute();
    }

    public function read($idFuncionario) {
        $sql = 'SELECT * FROM funcionario WHERE funcionario_id = ? AND status != 2';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idFuncionario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(Funcionario $funcionario) {
        $sql = 'UPDATE funcionario SET nome = ?, status = ? WHERE funcionario_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $funcionario->getNome());
        $stmt->bindValue(2, $funcionario->getStatus());
        $stmt->bindValue(3, $funcionario->getIdFuncionario());
        return $stmt->execute();
    }

    public function delete($idFuncionario) {
        $sql = 'UPDATE funcionario SET status = 2 WHERE funcionario_id = ?'; // Status 2 para indicar que está excluído
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idFuncionario);
        return $stmt->execute();
    }

    public function listAll() {
        $sql = 'SELECT * FROM funcionario WHERE status != 2';
        $stmt = Conexao::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findFuncByLogin($loginId) {
        $sql = 'SELECT * FROM funcionario WHERE login_id_login = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $loginId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
