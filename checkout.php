<?php
session_start();
include_once "server/Conexao.php";

// Verifica se o cliente está logado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o carrinho existe e não está vazio
if (!isset($_SESSION['carrinho']['carrinho']) || empty($_SESSION['carrinho']['carrinho'])) {
    header("Location: loja.php");
    exit();
}

// Função para calcular o total do carrinho
function calcularTotalCarrinho()
{
    $total = 0;
    foreach ($_SESSION['carrinho']['carrinho'] as $item) {
        // Verifica se o item possui as chaves 'preco' e 'quantidade'
        if (is_array($item) && isset($item['preco'], $item['quantidade'])) {
            $total += (float)$item['preco'] * (int)$item['quantidade'];
        }
    }
    return $total;
}


// Define as taxas
$taxaEntrega = 5.00;
$taxaEmbalagem = 5.00;
$totalCarrinho = calcularTotalCarrinho();
$totalFinal = $totalCarrinho;

// Define o tipo de retirada como 'balcao' por padrão
$tipoRetirada = isset($_POST['tipo_retirada']) ? $_POST['tipo_retirada'] : 'balcao';

if ($tipoRetirada === 1) {
    $totalFinal += $taxaEntrega + $taxaEmbalagem;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
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

        .body_menu {
            width: 700px;
        }

        h3 {
            margin-top: 50px;
        }

        .payment {
            margin-bottom: 50px;
        }

        .payment .options_pix,
        .payment .options_money,
        .payment .options_all {
            display: none;
        }

        .payment .options_money .label {
            margin-top: 20px;
            display: block;
            font-weight: 600;
        }

        .payment .options_money .box_options {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            column-gap: 20px;
        }

        .payment .options_money .box_options label {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            box-shadow: 0px 0 6px -2px #afafaf;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s;
        }

        .payment .options_money .box_options label:hover {
            cursor: pointer;
            transform: scale(1.05);
        }

        .payment .options_money .box_options label input {
            width: 30px;
        }

        .payment .options_pix {
            margin-top: 30px;
        }

        .payment .options_pix .box_options {
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        .payment .options_pix .box_options .title {
            text-align: center;
        }

        .payment .options_pix .box_options .desc {
            display: block;
            width: 100%;
            overflow-wrap: anywhere;
            padding-top: 20px;
        }

        .payment .options_pix .box_options img {
            width: 100%;
            height: auto;
        }

        .payment .options_all {
            margin-top: 15px;
        }

        .payment .options_all .title {
            color: #ef5a00;
            background-color: #fff9f6;
            border-radius: 10px;
            padding: 10px;
            border: 2px solid #ef5a00;
            font-size: 17px;
            text-align: center;
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
                        <form action="serverPublic/logout.php" method="POST" style="display: inline;">
                            <button type="submit" class="logout-btn">Sair</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container mt-5 body_menu">
        <h2>Checkout</h2>
        <hr>

        <!-- Resumo do Pedido -->
        <h3>Resumo do Pedido</h3>
        <ul class="list-group mb-3">
            <?php
            // Verifica se existe o array 'carrinho' na sessão
            if (isset($_SESSION['carrinho']['carrinho'])) {
                foreach ($_SESSION['carrinho']['carrinho'] as $item):
                    // Extrai os dados de cada item
                    $nome = isset($item['nome']) ? htmlspecialchars($item['nome']) : 'Produto Indefinido';
                    $quantidade = isset($item['quantidade']) ? (int)$item['quantidade'] : 1;
                    $preco = isset($item['preco']) ? (float)$item['preco'] : 0.0;
            ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo $nome; ?> - Quantidade: <?php echo $quantidade; ?>
                        <span>R$<?php echo number_format($preco * $quantidade, 2, ',', '.'); ?></span>
                    </li>
            <?php
                endforeach;
            } else {
                echo "<li class='list-group-item'>Carrinho vazio.</li>";
            }
            ?>
        </ul>
        <p>Total dos Itens: R$<?php echo number_format($totalCarrinho, 2, ',', '.'); ?></p>

        <!-- Tipo de Retirada -->
        <h3>Tipo de Retirada</h3>
        <form action="checkout.php" method="POST" id="checkoutForm">
            <input type="hidden" name="cod_comanda" id="cod_comanda" value="<?= $_SESSION['comanda_codigo'] ?>">

            <div class="mb-3">
                <select name="tipo_retirada" id="tipoRetirada" class="form-select" required onchange="atualizarTotal()">
                    <option value="0" <?php echo ($tipoRetirada === '0') ? 'selected' : ''; ?>>Balcão/Loja</option>
                    <option value="1" <?php echo ($tipoRetirada === '1') ? 'selected' : ''; ?>>Entrega</option>
                </select>
            </div>

            <!-- Endereço para entrega -->
            <div id="enderecoEntrega" style="display: <?php echo ($tipoRetirada === '1') ? 'block' : 'none'; ?>;">
                <h4>Endereço para Entrega</h4>
                <input type="hidden" name="cliente_id_endereco" id="cliente_id_endereco" value="<?php echo $_SESSION['cliente_id']; ?>">
                <input type="hidden" name="taxa_entrega" id="taxa_entrega" value="5">
                <input type="hidden" name="form_data" id="form_data" value="vazio">

                <div class="mb-3">
                    <label for="cep" class="form-label">CEP</label>
                    <input type="text" id="cep" name="cep" class="form-control" onblur="buscarEnderecoPorCep()" required>
                </div>
                <div class="mb-3">
                    <label for="numero" class="form-label">Número</label>
                    <input type="text" id="numero" name="numero" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="logradouro" class="form-label">Endereço</label>
                    <input type="text" id="logradouro" name="logradouro" class="form-control" disabled required>
                </div>
                <div class="mb-3">
                    <label for="bairro" class="form-label">Bairro</label>
                    <input type="text" id="bairro" name="bairro" class="form-control" disabled required>
                </div>
                <div class="mb-3">
                    <label for="cidade" class="form-label">Cidade</label>
                    <input type="text" id="cidade" name="cidade" class="form-control" disabled required>
                </div>
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <input type="text" id="estado" name="estado" class="form-control" disabled required>
                </div>
                <div class="mb-3">
                    <label for="complemento" class="form-label">Complemento</label>
                    <textarea name="complemento" id="complemento" class="form-control" rows="4" maxlength="1000"></textarea>
                </div>

                <!-- Resumo do Total -->
                <div class="mb-3">
                    <p>Taxa de Entrega: R$<?php echo number_format($taxaEntrega, 2, ',', '.'); ?></p>
                    <p>Taxa de Embalagem: R$<?php echo number_format($taxaEmbalagem, 2, ',', '.'); ?></p>
                </div>
            </div>

            <div class="payment">
                <h3>Forma de Pagamento</h2>
                    <div class="box_itens">
                        <div class="item">
                            <select name="payment_options" class="form-control" id="payment_options" onchange="detalhePagamento(this)" required>
                                <option value="null">Escolha uma opção...</option>
                                <option value="cartão" ref='all_options'>Cartão de Crédito/Débito</option>
                                <option value="refeição" ref='all_options'>Cartão Refeição/Alimentação</option>
                                <option value="pix" ref='cod_pix'>Transferência PIX</option>
                                <option value="dinheiro" ref="money">Em Dinheiro</option>
                            </select>
                        </div>
                        <div class="options_all" id="all_options">
                            <div class="box_options">
                                <h4 class="title">O pagamento deve ser realizado no balcão ou quando receber o pedido.</h4>
                            </div>
                        </div>
                        <div class="options_pix" id="cod_pix">
                            <div class="box_options">
                                <h4 class="title">Pagar com PIX</h4>
                                <img src="assets/imagens/qrcode-pix.webp" alt="QR code para transferência via PIX.">
                                <span class="desc" id="code_qr">Após realizar o pagamento envie o comprovante para: <a href="https://wa.me/5561999999999" target="blank">(61) 99999-9999</a></span>
                            </div>
                        </div>
                        <div class="options_money" id='money'>
                            <span class="label">Levar troco para:</span>
                            <div class="box_options">
                                <label for="10">
                                    <span class="price">R$ 10</span>
                                    <input type="radio" name="observacao" id="10" value="10">
                                </label>
                                <label for="20">
                                    <span class="price">R$ 20</span>
                                    <input type="radio" name="observacao" id="20" value="20">
                                </label>
                                <label for="50">
                                    <span class="price">R$ 50</span>
                                    <input type="radio" name="observacao" id="50" value="50">
                                </label>
                                <label for="100">
                                    <span class="price">R$ 100</span>
                                    <input type="radio" name="observacao" id="100" value="100">
                                </label>
                                <label for="200">
                                    <span class="price">R$ 200</span>
                                    <input type="radio" name="observacao" id="200" value="200">
                                </label>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="mb-3">
                <h4>Total Final: R$ <span id="totalFinal"><?php echo number_format($totalFinal, 2, ',', '.'); ?></span></h4>
            </div>

            <!-- Botão para Finalizar e Processar o Pagamento -->
            <button type="button" class="btn btn-primary" onclick="finalizarPedido()">Finalizar Pedido</button>
        </form>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para buscar o endereço pelo CEP
        function buscarEnderecoPorCep() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            const form_data = document.getElementById('form_data').value;

            if (cep.length === 8 && form_data == "vazio") {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {

                            document.getElementById('logradouro').disabled = false;
                            document.getElementById('bairro').disabled = false;
                            document.getElementById('cidade').disabled = false;
                            document.getElementById('estado').disabled = false;

                            document.getElementById('logradouro').value = data.logradouro || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('cidade').value = data.localidade || '';
                            document.getElementById('estado').value = data.uf || '';

                        } else {
                            alert('CEP não encontrado.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar CEP:', error);
                        alert('Erro ao buscar o CEP.');
                    });
            }
        }

        function editarEndereco(idCliente) {
            const formData = new FormData();
            formData.append('action', 'visualizarEndereco');
            formData.append('cliente_id', idCliente);

            fetch('server/EnderecoController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        //Verifica se havia endereço cadastrado
                        if (data.message === 'vazio') {
                            return;
                        
                        //Mostra outra mensagem de erro que possa aparecer
                        } else {
                            alert(data.message);
                            return;
                        }
                    }

                    document.getElementById('form_data').value = "ja_cadastrado";

                    document.getElementById('logradouro').disabled = false;
                    document.getElementById('bairro').disabled = false;
                    document.getElementById('cidade').disabled = false;
                    document.getElementById('estado').disabled = false;

                    document.getElementById('logradouro').value = data.logradouro || '';
                    document.getElementById('numero').value = data.numero || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    
                    document.getElementById('cidade').value = data.cidade || '';
                    document.getElementById('estado').value = data.estado || '';
                    document.getElementById('cep').value = data.cep || '';
                    document.getElementById('complemento').value = data.complemento || '';
                })
                //.catch(error => console.error('Erro ao buscar endereço:', error));
        }

        function atualizarTotal() {
            const tipoRetirada = document.getElementById('tipoRetirada').value;
            const enderecoEntrega = document.getElementById('enderecoEntrega');
            const cliente_id = document.getElementById('cliente_id_endereco').value;

            let totalFinal = <?php echo $totalCarrinho; ?>;

            if (tipoRetirada === '1') {
                enderecoEntrega.style.display = 'block';
                totalFinal += <?php echo $taxaEntrega + $taxaEmbalagem; ?>;
            } else {
                enderecoEntrega.style.display = 'none';
            }

            document.getElementById('totalFinal').innerText = totalFinal.toFixed(2).replace('.', ',');

            editarEndereco(cliente_id);
        }

        function salvarEndereco() {
            const formData = new FormData(document.getElementById('checkoutForm'));
            const complemento = document.getElementById('complementoCliente').value;
            formData.append('action', 'salvarEndereco');

            if (complemento == "") {
                formData.append('complemento', 'Nenhum');
            }

            fetch('server/EnderecoController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Erro ao salvar o endereço: ' + data.message);
                    }
                })
                .catch(error => console.error('Erro ao salvar o endereço:', error));
        }

        function detalhePagamento(selectElement) {
            // Esconder todos os elementos primeiro
            document.querySelectorAll('[ref]').forEach(element => {
                const id = element.getAttribute('ref');
                const div = document.getElementById(id);
                if (div) div.style.display = 'none';
            });

            // Pegar o valor selecionado
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const ref = selectedOption.getAttribute('ref');

            // Exibir o elemento correspondente se houver atributo ref
            if (ref) {
                const div = document.getElementById(ref);
                if (div) div.style.display = 'block';
            }
        }

        function finalizarPedido() {
            const formData = new FormData(document.getElementById('checkoutForm'));
            const tipoRetirada = document.getElementById('tipoRetirada').value;
            const paymentOption = document.getElementById('payment_options').value;
            let troco = "0";

            // Verificar tipo de retirada
            if (tipoRetirada === '1') { // Entrega
                const logradouro = document.getElementById('logradouro').value;
                const numero = document.getElementById('numero').value;
                const bairro = document.getElementById('bairro').value;
                const cidade = document.getElementById('cidade').value;
                const estado = document.getElementById('estado').value;
                const cep = document.getElementById('cep').value;

                if (logradouro === "" || numero === "" || bairro === "" || cidade === "" || estado === "" || cep === "") {
                    alert("Nenhum campo do endereço pode ficar vazio para entrega!");
                    return;
                }

                formData.append('logradouro', logradouro);
                formData.append('numero', numero);
                formData.append('bairro', bairro);
                formData.append('cidade', cidade);
                formData.append('estado', estado);
                formData.append('cep', cep);
                formData.append('taxa_entrega', 5.00);
                formData.append('taxa_info', 'Taxa de entrega');
            }

            // Verificar pagamento em dinheiro e troco
            if (paymentOption === 'dinheiro') {
                const trocoSelecionado = document.querySelector('input[name="observacao"]:checked');
                troco = trocoSelecionado ? trocoSelecionado.value : "0";
            }

            // Adicionar os campos adicionais ao formData
            formData.append('tipo_retirada', tipoRetirada);
            formData.append('forma_pagamento', paymentOption);
            formData.append('observacao', troco);
            formData.append('status', 1);
            formData.append('action', 'finalizar_pedido');

            // Adicionar os itens do carrinho ao formData
            const carrinho = <?php echo json_encode($_SESSION['carrinho']['carrinho']); ?>;
            formData.append('carrinho', JSON.stringify(carrinho));

            // Enviar os dados via fetch
            fetch('serverPublic/PublicController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = "loja.php";
                    } else {
                        alert('Erro ao finalizar o pedido: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro ao finalizar o pedido:', error);
                    alert('Erro ao processar o pedido.');
                });
        }
    </script>
</body>

</html>