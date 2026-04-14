<?php

include_once "Conexao.php";

class FranquiaCRUD {

    public function create(Franquia $franquia) {
        $sql = 'INSERT INTO franquia (tipo, qtde_atual, preco) VALUES (?, ?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $franquia->getTipo());
        $stmt->bindValue(2, $franquia->getQtdeAtual());
        $stmt->bindValue(3, $franquia->getPreco());
        return $stmt->execute();
    }

    public function read($idFranquia) {
        $sql = 'SELECT * FROM franquia WHERE idFranquia = :idFranquia';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':idFranquia', $idFranquia, PDO::PARAM_INT);
        $stmt->execute();

        $franquia = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($franquia) {
            $objFranquia = new Franquia();
            $objFranquia->setIdFranquia($franquia['idFranquia']);
            $objFranquia->setTipo($franquia['tipo']);
            $objFranquia->setQtdeAtual($franquia['qtde_atual']);
            $objFranquia->setPreco($franquia['preco']);
            return $objFranquia;
        } else {
            return false;
        }
    }

    public function update(Franquia $franquia) {
        $sql = 'UPDATE franquia SET tipo = :tipo, qtde_atual = :qtde_atual, preco = :preco WHERE idFranquia = :idFranquia';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':tipo', $franquia->getTipo());
        $stmt->bindValue(':qtde_atual', $franquia->getQtdeAtual());
        $stmt->bindValue(':preco', $franquia->getPreco());
        $stmt->bindValue(':idFranquia', $franquia->getIdFranquia());
        return $stmt->execute();
    }

    public function delete($idFranquia) {
        $sql = 'DELETE FROM franquia WHERE idFranquia = :idFranquia';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(':idFranquia', $idFranquia, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function listAll() {
        $sql = 'SELECT * FROM franquia';
        $stmt = Conexao::getInstance()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $franquias = [];
        foreach ($result as $row) {
            $franquia = new Franquia();
            $franquia->setIdFranquia($row['idFranquia']);
            $franquia->setTipo($row['tipo']);
            $franquia->setQtdeAtual($row['qtde_atual']);
            $franquia->setPreco($row['preco']);
            $franquias[] = $franquia;
        }
        
        return $franquias;
    }
}
?>
