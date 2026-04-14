<?php
session_start();

function verificarLogin()
{
    if (!isset($_SESSION['cliente_id'])) {
        header("Location: login.php");
        exit();
    }
}
verificarLogin();

function inicializarCarrinho()
{
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }
}

function adicionarProdutoAoCarrinho($produtoId, $nome, $preco, $quantidade)
{
    inicializarCarrinho();
    $produtoExistente = false;

    // Verifica se o produto já está no carrinho
    foreach ($_SESSION['carrinho'] as &$item) {
        if ($item['produtoId'] === $produtoId) {
            $item['quantidade'] += $quantidade;
            $produtoExistente = true;
            break;
        }
    }

    // Se não estiver, adiciona como novo item
    if (!$produtoExistente) {
        $_SESSION['carrinho'][] = [
            'produtoId' => $produtoId,
            'nome' => $nome,
            'preco' => $preco,
            'quantidade' => $quantidade
        ];
    }
}

function removerProdutoDoCarrinho($produtoId)
{
    inicializarCarrinho();
    foreach ($_SESSION['carrinho'] as $key => $item) {
        if ($item['produtoId'] === $produtoId) {
            unset($_SESSION['carrinho'][$key]);
            break;
        }
    }
    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); // Reindexa o array
}

function calcularTotalCarrinho()
{
    $total = 0;
    foreach ($_SESSION['carrinho'] as $item) {
        $total += $item['preco'] * $item['quantidade'];
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            overflow-y: scroll;
        }

        /* Cabeçalho */
        header {
            background-color: #007bff;
            color: white;
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header img {
            height: 50px;
            margin-right: 10px;
            vertical-align: middle;
        }

        header h1 {
            margin: 0;
        }

        header .box_branding {
            display: flex;
            flex-direction: row;
            column-gap: 10px;
        }

        /* Estilização do perfil */
        .perfil-container {
            display: flex;
            align-items: center;
            width: fit-content;
            margin: 0 0 0 auto;
        }

        .perfil-container img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .perfil-container span {
            font-weight: bold;
            margin-right: 15px;
        }

        .logout-btn {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: underline;
        }

        /* Menu de Categorias */
        .menu-categorias {
            display: flex;
            justify-content: center;
            background-color: #f1f1f1;
            padding: 1rem;
            gap: 1rem;
        }

        .menu-categorias a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .menu-categorias a:hover {
            background-color: #e0e0e0;
        }

        /* Container dos Produtos */
        #produtosContainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: left;
            gap: 2rem;
            padding: 2rem;
        }

        /* Estilo do Card do Produto */
        .card {
            width: 290px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-img-top {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .card-body {
            padding: 1rem;
            text-align: center;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin: 0.5rem 0;
        }

        .card-text {
            font-size: 0.9rem;
            color: #666;
            margin: 0.5rem 0;
        }

        /* Preço flutuante sobre a imagem */
        .card-price {
            position: absolute;
            font-size: 1rem;
            margin-bottom: 1rem;
            top: 10px;
            right: 10px;
            background-color: rgba(0, 123, 255, 0.8);
            color: white;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            font-weight: bold;
        }

        /* Estilo para a categoria */
        .card-category {
            font-size: 0.9rem;
            color: #555;
            font-weight: bold;
        }

        /* Botões */
        .card-body .btn {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            margin: 0.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .card-body .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .card-body .btn-primary:hover {
            background-color: #0056b3;
        }

        .card-body .btn-success {
            background-color: #28a745;
            color: white;
        }

        .card-body .btn-success:hover {
            background-color: #218838;
        }

        /* Destaque para o preço */
        .produto-preco {
            font-size: 1.5rem;
            color: #ff5722;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        /* Total calculado */
        .produto-total {
            font-size: 1.2rem;
            color: #007bff;
            font-weight: bold;
        }

        /* Alinhamento da quantidade e botões */
        .produto-quantidade {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Título dos detalhes */
        .produto-detalhe-titulo {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-top: 1rem;
        }

        footer {
            background-color: purple;
            padding: 10px 0;
        }

        footer .btn {
            margin: auto 0 auto auto;
            display: block;
            color: #fff;
        }

        footer .btn:hover {
            color: #fff;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="box_branding">
                        <img src="assets/imagens/logomarca.webp" alt="Amor de Açaí" style="border-radius: 10px;">
                        <h1>Amor de Açaí</h1>
                    </div>
                </div>
                <div class="col" style="align-items: center;display: flex;">
                    <div class="perfil-container">
                        <img src="assets/icons/perfil.webp" alt="Ícone de perfil"> <!-- Insira o caminho do ícone de perfil aqui -->
                        <span><?php echo htmlspecialchars($_SESSION['primeiro_nome']); ?></span>
                        <button type="submit" class="logout-btn" onclick="logout()">Sair</button>
                        <!-- Botão para abrir o carrinho -->
                        <button type="button" class="btn btn-primary ms-3" onclick="abrirCarrinho()">
                            🛒 Carrinho <span id="quantidadeCarrinho" class="badge bg-secondary">0</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <nav class="menu-categorias" id="menuCategorias">
        <!-- Categorias serão adicionadas aqui pelo JavaScript -->
    </nav>

    <section class="products">
        <div class="container">
            <div class="row">
                <div class="col" id="produtosContainer">
                    <!-- Os cards dos produtos serão adicionados aqui pelo JavaScript -->
                </div>
            </div>
        </div>
    </section>

    <!-- Modal para exibir o carrinho -->
    <div class="modal fade" id="carrinhoModal" tabindex="-1" aria-labelledby="carrinhoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carrinhoModalLabel">Carrinho de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="listaCarrinho" class="list-group mb-3">
                        <!-- Produtos do carrinho serão listados aqui via JavaScript -->
                    </ul>
                    <p>Total: R$ <span id="totalCarrinho"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="finalizarPedido()">Finalizar Pedido</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Adicionar o card flutuante para os detalhes do produto -->
    <div class="modal fade" id="produtoDetalhesModal" tabindex="-1" aria-labelledby="produtoDetalhesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="produtoNome"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Imagem do produto -->
                    <img id="produtoImagem" src="" alt="Imagem do Produto" style="width: 100%; border-radius: 5px;">

                    <!-- Preço unitário destacado -->
                    <p class="produto-preco">R$ <span id="produtoPreco"></span></p>

                    <!-- Total calculado -->
                    <p class="produto-total">Total: R$ <span id="produtoTotal"></span></p>

                    <!-- Ingredientes -->
                    <p class="produto-detalhe-titulo">Ingredientes:</p>
                    <p id="produtoIngredientes"></p>

                    <!-- Descrição -->
                    <p class="produto-detalhe-titulo">Descrição:</p>
                    <p id="produtoDescricao"></p>

                    <!-- Quantidade e botões -->
                    <div class="produto-quantidade">
                        <label for="quantidade" class="form-label">Quantidade:</label>
                        <input type="number" id="quantidade" class="form-control" style="width: 80px;" min="1" value="1">
                        <button type="button" class="btn btn-success" onclick='adicionarProdutoAoCarrinho()'>+ Carrinho</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="carrinhoModal" tabindex="-1" aria-labelledby="carrinhoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carrinhoModalLabel">Carrinho de Compras</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="listaCarrinho">
                        <!-- Produtos do carrinho serão listados aqui via JavaScript -->
                    </ul>
                    <p>Total: R$ <span id="totalCarrinho"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="finalizarPedido()">Finalizar Pedido</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <button class="btn" onclick="logout()">Área Administrativa</button>
        </div>
    </footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let precoUnitario = 0;

        document.getElementById("quantidade").addEventListener("input", function() {
            const quantidade = parseInt(this.value);
            const total = precoUnitario * quantidade;
            document.getElementById("produtoTotal").innerText = total.toFixed(2);
        });

        async function carregarCategorias() {
            try {
                const response = await fetch('serverPublic/PublicController.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'action': 'listar_categorias'
                    })
                });
                const categorias = await response.json();
                const menu = document.getElementById('menuCategorias');

                // Link para "Todos"
                const todosLink = document.createElement('a');
                todosLink.href = "#";
                todosLink.innerText = "Todos";
                todosLink.addEventListener('click', (event) => {
                    event.preventDefault();
                    carregarProdutos();
                });
                menu.appendChild(todosLink);

                // Links das categorias
                categorias.forEach(categoria => {
                    const link = document.createElement('a');
                    link.href = "#";
                    link.innerText = categoria.nome;
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        carregarProdutos(categoria.categoria_id);
                    });
                    menu.appendChild(link);
                });
            } catch (error) {
                console.error('Erro ao carregar categorias:', error);
            }
        }

        // Função para carregar produtos do backend e exibir na página
        async function carregarProdutos(categoriaId = null) {
            try {
                const params = new URLSearchParams({
                    'action': 'listar_produtos'
                });
                if (categoriaId) params.append('categoria_id', categoriaId);

                const response = await fetch('serverPublic/PublicController.php', {
                    method: 'POST',
                    body: params
                });

                const produtos = await response.json();
                const container = document.getElementById('produtosContainer');
                container.innerHTML = '';

                produtos.forEach(produto => {
                    const photo = produto.imagem || 'assets/imagens/no_photo.webp';
                    const card = document.createElement('div');
                    card.className = 'card mb-3';
                    card.innerHTML = `
                <div style="position: relative;">
                    <img src="${photo}" class="card-img-top" alt="${produto.nome}">
                    <span class="card-price">R$${produto.preco}</span>
                </div>
                <div class="card-body">
                    <h5 class="card-title">${produto.nome}</h5>
                    <p class="card-category">${produto.categoria}</p>
                    <p class="card-text">${produto.ingredientes}</p>
                    <button class="btn btn-primary" onclick='abrirDetalhesProduto(${JSON.stringify(produto)})'>Detalhes</button>
                    <button class="btn btn-success" onclick='adicionarProdutoAoCarrinhoDireto(${JSON.stringify(produto)})'>+ Carrinho</button>
                </div>
            `;
                    container.appendChild(card);
                });
            } catch (error) {
                console.error('Erro ao carregar produtos:', error);
            }
        }

        // Função para abrir o modal de detalhes do produto
        function abrirDetalhesProduto(produto) {
            document.getElementById("produtoNome").innerText = produto.nome;
            document.getElementById("produtoImagem").src = produto.imagem || 'assets/imagens/no_photo.webp';
            document.getElementById("produtoPreco").innerText = produto.preco;
            document.getElementById("produtoIngredientes").innerText = produto.ingredientes || 'Não especificado';
            document.getElementById("produtoDescricao").innerText = produto.descricao || 'Sem descrição';

            precoUnitario = parseFloat(produto.preco);
            atualizarTotal();

            // Armazenar o ID real do produto no modal
            document.getElementById("produtoDetalhesModal").setAttribute("data-produto-id", produto.produto_id);

            const modal = new bootstrap.Modal(document.getElementById('produtoDetalhesModal'));
            modal.show();
        }

        // Função para atualizar o total baseado na quantidade selecionada
        function atualizarTotal() {
            const quantidade = parseInt(document.getElementById("quantidade").value) || 1;
            const total = precoUnitario * quantidade;
            document.getElementById("produtoTotal").innerText = total.toFixed(2);
        }

        // Função para adicionar o produto ao carrinho a partir do modal de detalhes
        function adicionarProdutoAoCarrinho() {
            const quantidade = parseInt(document.getElementById("quantidade").value) || 1;
            const produtoNome = document.getElementById("produtoNome").innerText;
            const produtoId = document.getElementById("produtoDetalhesModal").getAttribute("data-produto-id");
            const preco = precoUnitario;

            if (!produtoId) {
                alert("Erro ao adicionar o produto: ID não encontrado.");
                return;
            }

            adicionarAoCarrinho(produtoId, produtoNome, preco, quantidade);
            exibirNotificacao(`${produtoNome} adicionado ao carrinho!`);
            atualizarQuantidadeCarrinho();
            fecharDetalhesProduto();
        }

        // Função para adicionar o produto ao carrinho diretamente
        function adicionarProdutoAoCarrinhoDireto(produto) {
            adicionarAoCarrinho(produto.produto_id, produto.nome, produto.preco, 1);
            exibirNotificacao(`${produto.nome} adicionado ao carrinho!`);
            atualizarQuantidadeCarrinho();
        }

        // Função para adicionar um item ao carrinho (manipulação do sessionStorage)
        function adicionarAoCarrinho(produtoId, nome, preco, quantidade) {
            const carrinho = JSON.parse(sessionStorage.getItem("carrinho") || "[]");
            const index = carrinho.findIndex(item => item.produtoId === produtoId);

            if (index > -1) {
                carrinho[index].quantidade += quantidade;
            } else {
                carrinho.push({
                    produtoId,
                    nome,
                    preco,
                    quantidade
                });
            }

            sessionStorage.setItem("carrinho", JSON.stringify(carrinho));
            atualizarCarrinhoModal();
        }

        // Função para abrir o modal do carrinho
        function abrirCarrinho() {
            atualizarCarrinhoModal(); // Atualiza o conteúdo do modal antes de abrir
            const modal = new bootstrap.Modal(document.getElementById('carrinhoModal'));
            modal.show();
        }

        // Função para atualizar o conteúdo do modal do carrinho
        function atualizarCarrinhoModal() {
            const listaCarrinho = document.getElementById("listaCarrinho");
            const totalCarrinho = document.getElementById("totalCarrinho");
            const carrinho = JSON.parse(sessionStorage.getItem("carrinho") || "[]");
            listaCarrinho.innerHTML = '';
            let total = 0;

            carrinho.forEach(item => {
                total += item.preco * item.quantidade;
                listaCarrinho.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                ${item.nome} <br>Quantidade: ${item.quantidade}
                <span>R$${(item.preco * item.quantidade).toFixed(2)}</span>
                <button class="btn btn-danger btn-sm" onclick="removerDoCarrinho(${item.produtoId})">Remover</button>
            </li>
        `;
            });
            totalCarrinho.innerText = total.toFixed(2);
        }

        // Função para remover um produto do carrinho
        function removerDoCarrinho(produtoId) {
            const carrinho = JSON.parse(sessionStorage.getItem("carrinho") || "[]");
            const novoCarrinho = carrinho.filter(item => item.produtoId !== produtoId);
            sessionStorage.setItem("carrinho", JSON.stringify(novoCarrinho));
            atualizarCarrinhoModal();
            atualizarQuantidadeCarrinho();
        }

        // Função para exibir uma notificação de produto adicionado
        function exibirNotificacao(mensagem) {
            const notificacao = document.createElement("div");
            notificacao.className = "alert alert-success position-fixed top-0 end-0 m-3";
            notificacao.style.zIndex = 1051; // Para garantir que esteja acima de outros elementos
            notificacao.innerText = mensagem;

            document.body.appendChild(notificacao);

            setTimeout(() => {
                notificacao.remove();
            }, 2000); // Remove após 2 segundos
        }

        // Função para atualizar a quantidade de itens no ícone do carrinho
        function atualizarQuantidadeCarrinho() {
            const carrinho = JSON.parse(sessionStorage.getItem("carrinho") || "[]");
            const quantidadeTotal = carrinho.reduce((total, item) => total + item.quantidade, 0);
            document.getElementById("quantidadeCarrinho").innerText = quantidadeTotal;
        }

        // Função para finalizar o pedido e redirecionar para a página de checkout
        function finalizarPedido() {

            const carrinho = JSON.parse(sessionStorage.getItem("carrinho") || "[]");

            console.log(carrinho);

            if (carrinho.length == 0) {
                alert("O carrinho não pode ser enviado vazio.");
                return;
            }

            fetch('serverPublic/atualizar_sessao_carrinho.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        carrinho
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        sessionStorage.clear();
                        window.location.href = 'checkout.php';
                    } else {
                        alert('Erro ao enviar pedido: ' + data.message);
                    }
                });
        }

        function logout() {
            sessionStorage.clear();
            window.location.href = "serverPublic/logout.php";
        }

        // Carrega os produtos ao iniciar
        carregarProdutos();
        carregarCategorias();
        atualizarQuantidadeCarrinho();
    </script>

</body>

</html>