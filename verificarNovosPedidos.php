<?php
include_once "Conexao.php";

try {
    // Consulta para verificar pedidos com status "Não Enviado" (status = 0)
    $sql = 'SELECT comanda.comanda_id AS idPedido FROM comanda WHERE status = 8 LIMIT 1';
    $stmt = Conexao::getInstance()->prepare($sql);
    $stmt->execute();
    $novoPedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($novoPedido) {
        // Se encontrar um pedido com status 0, retorna "novoPedido" com o ID do pedido
        echo json_encode([
            "novoPedido" => true,
            "idPedido" => $novoPedido['idPedido']
        ]);
    } else {
        // Nenhum pedido novo
        echo json_encode(["novoPedido" => false]);
    }
} catch (Exception $e) {
    echo json_encode([
        "novoPedido" => false,
        "erro" => $e->getMessage()
    ]);
}