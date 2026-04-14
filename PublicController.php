<?php
session_start();

include_once "PublicCRUD.php";
include_once "PublicClasse.php";

class PublicController
{
    private $produtoCrud;
    private $categoriaCrud;
    private $comandaCrud;
    private $pedidoCrud;

    public function __construct()
    {
        $this->produtoCrud = new ProdutoCRUD();
        $this->categoriaCrud = new CategoriaCRUD();
        $this->comandaCrud = new ComandaCRUD();
        $this->pedidoCrud = new PedidoCRUD();
    }

    public function processRequest()
    {
        $d = filter_input_array(INPUT_POST);

        if (isset($d['action'])) {
            $action = $d['action'];
            switch ($action) {
                case 'visualizar_produto':
                    $this->visualizarProduto($d['produto_id']);
                    break;
                case 'listar_produtos':
                    $this->listarProdutosAtivos((isset($d['categoria_id']) ? $d['categoria_id'] : null));
                    break;
                case 'listar_categorias':
                    $this->listarCategoriasAtivas();
                    break;
                case 'finalizar_pedido':
                    $this->finalizarPedido($d);
                    break;
                case 'gerar_comanda':
                    $this->gerarComanda($d['cliente_id']);
                    break;
                default:
                    echo "Ação inválida!";
                    break;
            }
        } else {
            echo "Nenhuma ação informada!";
        }
    }

    public function visualizarProduto($idProduto)
    {
        $produtoData = $this->produtoCrud->read($idProduto);

        if ($produtoData) {
            echo json_encode($produtoData);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao visualizar o produto.']);
        }
    }

    // Função para listar produtos ativos para o usuário
    public function listarProdutosAtivos($categoriaId)
    {

        if (isset($categoriaId)) $produtos = $this->produtoCrud->listAllActiveByCategory($categoriaId);
        else $produtos = $this->produtoCrud->listAllActive();

        if ($produtos) {
            echo json_encode($produtos);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao listar os produtos.']);
        }
    }


    // Função para listar produtos ativos para o usuário
    public function listarCategoriasAtivas()
    {
        $categorias = $this->categoriaCrud->listAllActive();

        if ($categorias) {
            echo json_encode($categorias);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao listar os produtos.']);
        }
    }

    // Função para finalizar pedido e enviar para cozinha
    public function finalizarPedido($pedido)
    {
        // Extrair os dados do pedido
        $cod_comanda = $pedido['cod_comanda'] ?? null;

        if ($cod_comanda == null) {
            echo json_encode(['status' => 'error', 'message' => 'Comanda não encontrada.']);
            return;
        }
        $tipo_retirada = $pedido['tipo_retirada'] ?? '0';
        $forma_pagamento = $pedido['forma_pagamento'] ?? 'Indefinido';
        $observacao = $pedido['observacao'] ?? 'Sem observações';
        $status = 1;
        $carrinho = json_decode($pedido['carrinho'], true);
        $clienteId = $_SESSION['cliente_id'];

        // Configurar taxa de entrega e informações adicionais
        $taxa_entrega = 0.0;
        $taxa_info = '';

        if ($tipo_retirada === '1') { // Entrega
            $taxa_entrega = $pedido['taxa_entrega'] ?? 0.0;
            $taxa_info = $pedido['taxa_info'] ?? 'Entrega';
        }

        // Preparar os dados para atualização da comanda
        $dadosComanda = [
            'cod_comanda' => $cod_comanda,
            'tipo_retirada' => $tipo_retirada,
            'taxa_entrega' => $taxa_entrega,
            'taxa_info' => $taxa_info,
            'forma_pagamento' => $forma_pagamento,
            'observacao' => $observacao,
            'status' => $status
        ];

        // Finalizar a comanda
        $resultComanda = $this->comandaCrud->finalizarPedido($dadosComanda);

        if ($resultComanda['success']) {
            // Inserir os itens do pedido
            foreach ($carrinho as $item) {
                $produtoId = $item['produtoId'];
                $quantidade = $item['quantidade'];
                $precoUn = $item['preco'];
                $precoTt = $precoUn * $quantidade;

                $dadosPedido = [
                    'cod_comanda' => $cod_comanda,
                    'fk_cliente_id' => $clienteId,
                    'fk_produto_id' => $produtoId,
                    'qtde' => $quantidade,
                    'preco_un' => $precoUn,
                    'preco_tt' => $precoTt,
                    'status' => 0
                ];

                $result = $this->pedidoCrud->inserirPedido($dadosPedido);

                if (!$result['success']) {
                    echo json_encode(['status' => 'error', 'message' => $result['message']]);
                    exit;
                }
            }

            // Gerar e cadastrar uma nova comanda
            $comandaCodigo = 'CMD' . time();
            $cadastraComanda = $this->comandaCrud->cadComandaInicial($clienteId, $comandaCodigo);

            if($cadastraComanda){
                // Salvar código da comanda na sessão
                $_SESSION['comanda_codigo'] = $comandaCodigo;
                echo json_encode(['status' => 'success', 'message' => 'Pedido realizado com sucesso!']);
            }else{
                echo json_encode(['status' => 'error', 'message' => 'Houve um problema ao iniciar uma nova comanda!']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao finalizar a comanda.']);
        }
    }
    public function gerarComanda($idCliente) {

    }
}

$controller = new PublicController();
$controller->processRequest();
