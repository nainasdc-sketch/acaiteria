<?php
session_start();

// Lê o corpo da requisição, que contém o carrinho em JSON
$data = file_get_contents("php://input");
$carrinho = json_decode($data, true);

// Verifica se os dados do carrinho foram recebidos corretamente
if (is_array($carrinho)) {
    // Armazena o carrinho na sessão
    $_SESSION['carrinho'] = $carrinho;

    // Retorna uma resposta de sucesso
    echo json_encode(['status' => 'success', 'message' => 'Carrinho atualizado com sucesso.']);
} else {
    // Retorna uma resposta de erro se os dados não foram recebidos corretamente
    echo json_encode(['status' => 'error', 'message' => 'Dados do carrinho inválidos.']);
}
?>
