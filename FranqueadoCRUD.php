<?php

include_once "Conexao.php";

class FranqueadoCRUD {

    public function create(Franqueado $franqueado) {
        $sql = 'INSERT INTO franqueado (nome, sobrenome, email, fone, estado, cidade) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $franqueado->getNome());
        $stmt->bindValue(2, $franqueado->getSobrenome());
        $stmt->bindValue(3, $franqueado->getEmail());
        $stmt->bindValue(4, $franqueado->getFone());
        $stmt->bindValue(5, $franqueado->getEstado());
        $stmt->bindValue(6, $franqueado->getCidade());
        return $stmt->execute();
    }

    public function read($idFranqueado) {
        $sql = 'SELECT * FROM franqueado WHERE idFranqueado = :idFranqueado';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':idFranqueado', $idFranqueado, PDO::PARAM_INT);
        $stmt->execute();

        $franqueado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($franqueado) {
            $objFranqueado = new Franqueado();
            $objFranqueado->setIdFranqueado($franqueado['idFranqueado']);
            $objFranqueado->setNome($franqueado['nome']);
            $objFranqueado->setSobrenome($franqueado['sobrenome']);
            $objFranqueado->setEmail($franqueado['email']);
            $objFranqueado->setFone($franqueado['fone']);
            $objFranqueado->setEstado($franqueado['estado']);
            $objFranqueado->setCidade($franqueado['cidade']);
            return $objFranqueado;
        } else {
            return false;
        }
    }

    public function update(Franqueado $franqueado) {
        $sql = 'UPDATE franqueado SET nome = :nome, sobrenome = :sobrenome, email = :email, fone = :fone, estado = :estado, cidade = :cidade WHERE idFranqueado = :idFranqueado';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':nome', $franqueado->getNome());
        $stmt->bindValue(':sobrenome', $franqueado->getSobrenome());
        $stmt->bindValue(':email', $franqueado->getEmail());
        $stmt->bindValue(':fone', $franqueado->getFone());
        $stmt->bindValue(':estado', $franqueado->getEstado());
        $stmt->bindValue(':cidade', $franqueado->getCidade());
        $stmt->bindValue(':idFranqueado', $franqueado->getIdFranqueado());
        return $stmt->execute();
    }

    public function delete($idFranqueado) {
        $sql = 'DELETE FROM franqueado WHERE idFranqueado = :idFranqueado';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':idFranqueado', $idFranqueado, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listAll() {
        $sql = 'SELECT * FROM franqueado';
        $stmt = Conexao::getInstance()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $franqueados = [];
        foreach ($result as $row) {
            $franqueado = new Franqueado();
            $franqueado->setIdFranqueado($row['idFranqueado']);
            $franqueado->setNome($row['nome']);
            $franqueado->setSobrenome($row['sobrenome']);
            $franqueado->setEmail($row['email']);
            $franqueado->setFone($row['fone']);
            $franqueado->setEstado($row['estado']);
            $franqueado->setCidade($row['cidade']);
            $franqueados[] = $franqueado;
        }
        
        return $franqueados;
    }
}
?>
