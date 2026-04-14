<?php
include_once "Conexao.php";

class ComandaCRUD {

    public function read($comandaId) {
        $sql = 'SELECT * FROM comanda WHERE comanda_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $comandaId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($comandaId) {
        $sql = 'DELETE FROM comanda WHERE comanda_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $comandaId);
        return $stmt->execute();
    }

    public function listAll() {
        $sql = 'SELECT * FROM comanda';
        $stmt = Conexao::getInstance()->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadComandaInicial($clienteId, $codigoComanda) {

        $sql_consult = 'SELECT * FROM comanda WHERE cliente_cliente_id = ?';
        $stmt_consult = Conexao::getInstance()->prepare($sql_consult);
        $stmt_consult->bindValue(1, $clienteId);
        $stmt_consult->execute();
        $result = $stmt_consult->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($result as $row) {
            if($row['status'] == 0){
                $sql_update = 'UPDATE comanda SET status = ? WHERE comanda_id = ? AND status = ?';
                $stmt_update = Conexao::getInstance()->prepare($sql_update);
                $stmt_update->bindValue(1, 9); //Concluído
                $stmt_update->bindValue(2, $row['comanda_id']);
                $stmt_update->bindValue(3, 0);
                $stmt_update->execute();
            }
        }

        $sql_cad = 'INSERT INTO comanda (cliente_cliente_id, cod_comanda, status) VALUES (?, ?, ?)';
        $stmt_cad = Conexao::getInstance()->prepare($sql_cad);
        $stmt_cad->bindValue(1, $clienteId);
        $stmt_cad->bindValue(2, $codigoComanda);
        $stmt_cad->bindValue(3, '0');

        return $stmt_cad->execute();
    }
}