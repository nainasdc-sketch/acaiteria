<?php
include_once "Conexao.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os dados necessários foram enviados
    if (isset($_POST['idPedido']) && isset($_POST['novoStatus'])) {
        $idPedido = $_POST['idPedido'];
        $novoStatus = $_POST['novoStatus'];

        try {
            // Atualiza o status da comanda correspondente ao pedido
            $sql = 'UPDATE comanda SET status = ? WHERE comanda_id = (SELECT fk_comanda_id FROM pedido WHERE fk_comanda_id = ? LIMIT 1)';
            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $novoStatus, PDO::PARAM_INT);
            $stmt->bindValue(2, $idPedido, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo 'success';
            } else {
                echo 'Erro ao atualizar o status do pedido.';
            }
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else {
        echo 'Dados incompletos.';
    }
} else {
    echo 'Requisição inválida.';
}
?>