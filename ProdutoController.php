<?php
include_once "ProdutoCRUD.php";
include_once "ProdutoClasse.php";

class ProdutoController
{
    private $produtoCrud;

    public function __construct()
    {
        $this->produtoCrud = new ProdutoCRUD();
    }

    public function processRequest()
    {
        $d = filter_input_array(INPUT_POST);

        if (isset($d['action'])) {
            $action = $d['action'];
            switch ($action) {
                case 'cadastrar':
                    $this->cadastrar($d);
                    break;
                case 'editar':
                    $this->editar($d);
                    break;
                case 'deletar':
                    $this->deletar($d['produto_id']);
                    break;
                case 'visualizar':
                    $this->visualizar($d['produto_id']);
                    break;
                case 'listar':
                    $this->listar();
                    break;
                default:
                    echo "Ação inválida!";
                    break;
            }
        } else {
            echo "Nenhuma ação informada!";
        }
    }

    public function cadastrar($data)
    {
        $produto = new Produto();
        $produto->setNome($data['nomeProduto']);
        $produto->setPreco($data['precoProduto']);
        $produto->setDescricao($data['descricaoProduto']);
        $produto->setIngredientes($data['ingredientes'] ?? null);
        $produto->setStatus(0);  // Definindo como ativo por padrão
        $produto->setCategoriaId($data['categoria_id'] ?? null);

        // Verifica se um arquivo de imagem foi enviado
        if (isset($_FILES['fotoProduto']) && $_FILES['fotoProduto']['error'] == UPLOAD_ERR_OK) {
            $fotoProduto = $_FILES['fotoProduto'];

            // Validações de segurança do arquivo
            if ($fotoProduto['size'] > 10 * 1024 * 1024) {
                echo json_encode(['status' => 'error', 'message' => 'O arquivo deve ter no máximo 10MB.']);
                return;
            }
            if (mime_content_type($fotoProduto['tmp_name']) !== 'image/jpeg') {
                if (mime_content_type($fotoProduto['tmp_name']) !== 'image/webp') {
                    echo json_encode(['status' => 'error', 'message' => 'Apenas arquivos JPG e WEBP são permitidos.']);
                    return;
                } else {
                    $extension = '.webp';
                }
            } else {
                $extension = '.jgp';
            }

            // Gera um nome aleatório para a foto
            $randomName = bin2hex(random_bytes(5));
            $fileName = $randomName . $extension;

            // Caminho para salvar o arquivo
            $uploadDir = __DIR__ . '/../uploads/';
            $uploadPath = $uploadDir . $fileName;

            // Move o arquivo para a pasta de uploads
            if (!move_uploaded_file($fotoProduto['tmp_name'], $uploadPath)) {
                echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar a imagem do produto.']);
                return;
            }

            // Define o caminho e nome do arquivo no objeto Produto
            $produto->setFotoNome($fileName);
            $produto->setFotoPasta('uploads/');
        }

        if ($this->produtoCrud->create($produto)) {
            echo json_encode(['status' => 'success', 'message' => 'Produto cadastrado com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar produto.']);
        }
    }

    public function editar($data)
    {
        $produto = new Produto();
        $produto->setIdProduto($data['produto_id']);
        $produto->setNome($data['nomeProduto']);
        $produto->setPreco($data['precoProduto']);
        $produto->setDescricao($data['descricaoProduto']);
        $produto->setIngredientes($data['ingredientes'] ?? null);
        $produto->setStatus($data['status'] ?? 0);
        $produto->setCategoriaId($data['categoria_id'] ?? null);

        // Verifica se um arquivo de imagem foi enviado
        if (isset($_FILES['fotoProduto']) && $_FILES['fotoProduto']['error'] == UPLOAD_ERR_OK) {
            $fotoProduto = $_FILES['fotoProduto'];

            // Validações de segurança do arquivo
            if ($fotoProduto['size'] > 10 * 1024 * 1024) {
                echo json_encode(['status' => 'error', 'message' => 'O arquivo deve ter no máximo 10MB.']);
                return;
            }
            if (mime_content_type($fotoProduto['tmp_name']) !== 'image/jpeg') {
                if (mime_content_type($fotoProduto['tmp_name']) !== 'image/webp') {
                    echo json_encode(['status' => 'error', 'message' => 'Apenas arquivos JPG e WEBP são permitidos.']);
                    return;
                } else {
                    $extension = '.webp';
                }
            } else {
                $extension = '.jgp';
            }

            // Gera um nome aleatório para a foto
            $randomName = bin2hex(random_bytes(5));
            $fileName = $randomName . $extension;

            // Caminho para salvar o arquivo
            $uploadDir = __DIR__ . '/../uploads/';
            $uploadPath = $uploadDir . $fileName;

            // Move o arquivo para a pasta de uploads
            if (!move_uploaded_file($fotoProduto['tmp_name'], $uploadPath)) {
                echo json_encode(['status' => 'error', 'message' => 'Erro ao salvar a imagem do produto.']);
                return;
            }

            // Define o caminho e nome do arquivo no objeto Produto
            $produto->setFotoNome($fileName);
            $produto->setFotoPasta('uploads/');
        } else if (isset($data['fotoPasta']) && isset($data['fotoNome'])) {
            // Define o caminho e nome do arquivo no objeto Produto
            $produto->setFotoNome($data['fotoNome']);
            $produto->setFotoPasta($data['fotoPasta']);
        }

        if ($this->produtoCrud->update($produto)) {
            echo json_encode(['status' => 'success', 'message' => 'Produto editado com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao editar o produto.']);
        }
    }

    public function deletar($idProduto)
    {
        if ($this->produtoCrud->delete($idProduto)) {
            echo json_encode(['status' => 'success', 'message' => 'Produto excluído com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao deletar o produto.']);
        }
    }

    public function visualizar($idProduto)
    {
        $produtoData = $this->produtoCrud->read($idProduto);

        if ($produtoData) {
            echo json_encode($produtoData);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao visualizar o produto.']);
        }
    }

    public function listar()
    {
        $produtos = $this->produtoCrud->listAll();

        if ($produtos) {
            echo json_encode($produtos);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao listar os produtos.']);
        }
    }
}

$controller = new ProdutoController();
$controller->processRequest();
