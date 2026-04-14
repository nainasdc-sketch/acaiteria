<?php
session_start();

function verificarLogin()
{
    if (!isset($_SESSION['funcionario_id']) || $_SESSION['nivel_acesso'] === 0) {
        header("Location: ../loja.php");
        exit();
    }
}

verificarLogin();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../assets/estilos/style-store.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="box_branding">
                        <img src="../assets/imagens/logomarca.webp" alt="Amor de Açaí" style="border-radius: 10px;">
                        <h1>Área Administrativa</h1>
                    </div>
                </div>
                <div class="col" style="align-items: center;display: flex;">
                    <div class="perfil-container">
                        <img src="../assets/icons/perfil.webp" alt="Ícone de perfil"> <!-- Insira o caminho do ícone de perfil aqui -->
                        <span><?php echo htmlspecialchars($_SESSION['funcionario_nome']); ?></span>
                        <button type="submit" class="logout-btn" onclick="logout()">Sair</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Admin Açaiteria</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="listaPedidos.php">Pedidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manterClientes.php">Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manterProdutos.php">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manterFuncionarios.php">Funcionários</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container mt-5">
        <?php
        include_once "../server/ComandaCRUD.php";
        include_once "../server/ProdutoCRUD.php";
        include_once "../server/ClienteCRUD.php";

        function getStatusText($status)
        {
            switch ($status) {
                case 0:
                    return 'Não Enviado';
                case 1:
                    return 'Recebido pela Cozinha';
                case 2:
                    return 'Pedido Aceito';
                case 3:
                    return 'Pedido Rejeitado';
                case 4:
                    return 'Em Preparação';
                case 5:
                    return 'Pronto Para Retirada';
                case 6:
                    return 'Aguardando Entregador';
                case 7:
                    return 'Saiu para Entrega';
                case 9:
                    return 'Concluído';
                default:
                    return 'Desconhecido';
            }
        }

        $comandaCrud = new ComandaCRUD();
        $produtoCrud = new ProdutoCRUD();
        $clienteCrud = new ClienteCRUD();

        function listPedidosByStatus($status)
        {
            $sql = 'SELECT 
                comanda.comanda_id,
                comanda.cod_comanda,
                comanda.status,
                comanda.tipo_retirada,
                GROUP_CONCAT(CONCAT(produto.nome, " - ", pedido.qtde, "x (R$", pedido.preco_tt, ")") SEPARATOR "<br>") AS produtos,
                cliente.nome AS clienteNome,
                cliente.fone AS clienteFone,
                SUM(pedido.preco_tt) AS precoTotal
            FROM 
                pedido
            JOIN 
                comanda ON pedido.fk_comanda_id = comanda.comanda_id
            JOIN 
                produto ON pedido.fk_produto_id = produto.produto_id
            JOIN 
                cliente ON comanda.cliente_cliente_id = cliente.cliente_id
            WHERE 
                comanda.status = ?
            GROUP BY 
                comanda.cod_comanda, cliente.nome, cliente.fone, comanda.tipo_retirada';

            $stmt = Conexao::getInstance()->prepare($sql);
            $stmt->bindValue(1, $status, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        ?>

        <!-- Pedidos Novos (Aguardando Aceitação) -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <i class="fas fa-clock"></i> Pedidos Novos (Aguardando Aceitação)
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Código da Comanda</th>
                            <th>Cliente</th>
                            <th>Produto</th>
                            <th>Preço Total</th>
                            <th>Tipo de Retirada</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pedidosNovos = listPedidosByStatus(1);
                        foreach ($pedidosNovos as $pedido) {
                            $telefoneCliente = preg_replace('/\D/', '', $pedido['clienteFone']);
                            $tipoRetirada = $pedido['tipo_retirada'] == 1 ? 'Entrega' : 'Retirada';
                            echo "<tr>";
                            echo "<td>{$pedido['cod_comanda']}</td>";
                            echo "<td>{$pedido['clienteNome']}</td>";
                            echo "<td>{$pedido['produtos']}</td>";
                            echo "<td>R$ {$pedido['precoTotal']}</td>";
                            echo "<td>{$tipoRetirada}</td>";
                            echo "<td>
                <button class='btn btn-success btn-sm' onclick='atualizarStatus({$pedido['comanda_id']}, 2)'>Aceitar</button>
                <button class='btn btn-danger btn-sm' onclick='atualizarStatus({$pedido['comanda_id']}, 3)'>Rejeitar</button>
                <a class='btn btn-success btn-sm' href='https://wa.me/55{$telefoneCliente}?text=Olá, seu pedido foi recebido e está em processamento.' target='whatsapp'><i class='fab fa-whatsapp'></i></a>
              </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Demais tabelas de pedidos -->
        <?php
        $statusList = [
            2 => ['title' => 'Aguardando Início de Preparo', 'color' => 'warning', 'icon' => 'fa-clock', 'nextStatus' => 4, 'nextTitle' => 'Enviar para Preparo'],
            4 => ['title' => 'Pedidos em Preparo', 'color' => 'primary', 'icon' => 'fa-blender', 'nextStatus' => 5, 'nextTitle' => 'Liberar Retirada'],
            5 => ['title' => 'Pedidos Prontos para Retirada', 'color' => 'success', 'icon' => 'fa-box', 'nextStatus' => 6, 'nextTitle' => 'Liberar p/ Coleta'],
            6 => ['title' => 'Pedidos Aguardando Entregador', 'color' => 'dark', 'icon' => 'fa-motorcycle', 'nextStatus' => 7, 'nextTitle' => 'Saiu para Entrega'],
            7 => ['title' => 'Saiu para Entrega', 'color' => 'secondary', 'icon' => 'fa-shipping-fast', 'nextStatus' => 9, 'nextTitle' => 'Entregue'],
            9 => ['title' => 'Pedidos Concluídos', 'color' => 'success', 'icon' => 'fa-thumbs-up'],
            3 => ['title' => 'Pedidos Rejeitados', 'color' => 'danger', 'icon' => 'fa-times-circle']
        ];

        foreach ($statusList as $status => $details) {
            echo "<div class='card mb-4'>";
            echo "<div class='card-header bg-{$details['color']} text-white'>";
            echo "<i class='fas {$details['icon']}'></i> {$details['title']}";
            echo "</div>";
            echo "<div class='card-body'>";
            echo "<table class='table table-bordered'>";
            echo "<thead><tr><th>Código da Comanda</th><th>Cliente</th><th>Produto</th><th>Preço Total</th><th>Tipo de Retirada</th><th>Ações</th></tr></thead>";
            echo "<tbody>";
            $pedidos = listPedidosByStatus($status);
            foreach ($pedidos as $pedido) {
                $tipoRetirada = $pedido['tipo_retirada'] == 1 ? 'Entrega' : 'Retirada';
                $telefoneCLiente = preg_replace('/\D/', '', $pedido['clienteFone']);
                echo "<tr>";
                echo "<td>{$pedido['cod_comanda']}</td>";
                echo "<td>{$pedido['clienteNome']}</td>";
                echo "<td>{$pedido['produtos']}</td>";
                echo "<td>R$ {$pedido['precoTotal']}</td>";
                echo "<td>{$tipoRetirada}</td>";
                switch ($status) {
                    case 2:
                        $mensagem = "O seu pedido acaba de ser recebido por nossa equipe. Nós avisaremos assim que começar a ser preparado.";
                        break;
                    case 3:
                        $mensagem = "Acabamos de cancelar seu pedido. Tivemos um problema e seu pedido teve que ser cancelado.";
                        break;
                    case 4:
                        $mensagem = "Falta pouco, seu açaí começou a ser preparado.";
                        break;
                    case 5:
                        $mensagem = "Ótima notícia 🙌. O seu pedido acabou de ficar pronto e já pode ser retirado.";
                        break;
                    case 6:
                        $mensagem = "Ótima notícia 🙌. O seu pedido acabou de ficar pronto e estamos aguardando apenas o entregador recolher seu pedido. 🛵";
                        break;
                    case 7:
                        $mensagem = "A caminho. Seu açaí já está com nosso entregador e chegará em instantes.";
                        break;
                    case 9:
                        $mensagem = "Seu pedido foi finalizado. Agradecemos a preferência.";
                        break;
                }

                if (isset($details['nextStatus'])) {


                    if ($details['nextStatus'] == 5 && $pedido['tipo_retirada'] == 1) {
                        echo "<td>
                                <button class='btn btn-primary btn-sm' onclick='atualizarStatus({$pedido['comanda_id']}, 6)'>Liberar p/ Coleta</button>
                                ";
                    } else {
                        echo "<td>
                                <button class='btn btn-primary btn-sm' onclick='atualizarStatus({$pedido['comanda_id']}, {$details['nextStatus']})'>{$details['nextTitle']}</button>
                                ";
                    }
                    echo "<a class='btn btn-success btn-sm' href='https://wa.me/55{$telefoneCLiente}?text={$mensagem}' target='whatsapp'><i class='fab fa-whatsapp'></i></a>
                            </td>";
                } else {
                    echo "<td>
                        <a class='btn btn-success btn-sm' href='https://wa.me/55{$telefoneCLiente}?text={$mensagem}' target='whatsapp'><i class='fab fa-whatsapp'></i></a>
                        <button class='btn btn-danger btn-sm' onclick='deletaComanda({$pedido['comanda_id']})'><i class='fa fa-trash'></i></button>
                    </td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function atualizarStatus(idPedido, novoStatus, action = "update", message = "") {
            const formData = new FormData();
            formData.append('idPedido', idPedido);
            formData.append('novoStatus', novoStatus);

            fetch('../server/atualizarStatus.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        if (action == "update") {
                            location.reload();
                        } else if (action == "notification") {
                            // Mostra notificação sem recarregar a página
                            mostrarNotificacaoPedidoNovo();
                        }

                    } else {
                        alert('Erro ao atualizar o status do pedido.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao atualizar o status do pedido.');
                });
        }

        // Verificação a cada 10 segundos para novos pedidos
        setInterval(() => {
            fetch('../server/verificarNovosPedidos.php')
                .then(response => response.json())
                .then(data => {
                    if (data.novoPedido) {
                        atualizarStatus(data.idPedido, 1, "notification"); // Atualiza para "Recebido pela Cozinha" e mostra notificação
                    }
                })
                .catch(error => {
                    console.error('Erro ao verificar novos pedidos:', error);
                });
        }, 10000);

        function mostrarNotificacaoPedidoNovo() {
            const notificacao = document.getElementById('notificacao-pedido-novo');
            notificacao.style.display = 'block';
        }

        function logout() {
            sessionStorage.clear();
            window.location.href = "../serverPublic/logout.php";
        }

        function deletaComanda(idComanda){

            const deletar = confirm("Tem certeza que deseja remover essa comanda? Essa ação não poderá ser revertida.", "sim");
            if(deletar){
                const formData = new FormData();
                formData.append('action', 'deletar_comanda');
                formData.append('comanda_id', idComanda);

                fetch('../server/ComandaController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status == "success") {
                            location.reload();
                        }else {
                            alert("Erro ao excluir comanda. " + data.message)
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao verificar novos pedidos:', error);
                    });
            }

        }
    </script>

    <div id="notificacao-pedido-novo" class="alert alert-warning alert-dismissible fade show alerta_novo_pedido" role="alert">
        <strong>Pedido Novo!</strong> Um novo pedido foi recebido. Por favor, recarregue a página.
        <button type="button" class="btn btn-primary btn-sm" onclick="location.reload()">Recarregar</button>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <style>
        .alerta_novo_pedido {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</body>

</html>