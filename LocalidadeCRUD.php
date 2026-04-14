<?php

include_once "Conexao.php";

class LocalidadeCRUD {

    public function create(Localidade $localidade) {
        $sql = 'INSERT INTO localidade (estado, permissao) VALUES (?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $localidade->getEstado());
        $stmt->bindValue(2, $localidade->getPermissao());
        return $stmt->execute();
    }

    public function read($idLocalidade) {
        $sql = 'SELECT * FROM localidade WHERE idLocalidade = :idLocalidade';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':idLocalidade', $idLocalidade, PDO::PARAM_INT);
        $stmt->execute();

        $localidade = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($localidade) {
            $objLocalidade = new Localidade();
            $objLocalidade->setIdLocalidade($localidade['idLocalidade']);
            $objLocalidade->setEstado($localidade['estado']);
            $objLocalidade->setPermissao($localidade['permissao']);
            return $objLocalidade;
        } else {
            return false;
        }
    }

    public function update(Localidade $localidade) {
        $sql = 'UPDATE localidade SET estado = :estado, permissao = :permissao WHERE idLocalidade = :idLocalidade';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':estado', $localidade->getEstado());
        $stmt->bindValue(':permissao', $localidade->getPermissao());
        $stmt->bindValue(':idLocalidade', $localidade->getIdLocalidade());
        return $stmt->execute();
    }

    public function delete($idLocalidade) {
        $sql = 'DELETE FROM localidade WHERE idLocalidade = :idLocalidade';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':idLocalidade', $idLocalidade, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listAll() {
        $sql = 'SELECT * FROM localidade';
        $stmt = Conexao::getInstance()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $localidades = [];
        foreach ($result as $row) {
            $localidade = new Localidade();
            $localidade->setIdLocalidade($row['idLocalidade']);
            $localidade->setEstado($row['estado']);
            $localidade->setPermissao($row['permissao']);
            $localidades[] = $localidade;
        }
        
        return $localidades;
    }
}
?>
