<?php
include_once "CategoriaCRUD.php";
include_once "CategoriaClasse.php";

class CategoriaController {
    private $categoriaCrud;

    public function __construct() {
        $this->categoriaCrud = new CategoriaCRUD();
    }

    public function processRequest() {
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
                    $this->deletar($d['categoria_id']);
                    break;
                case 'visualizar':
                    $this->visualizar($d['categoria_id']);
                    break;
                case 'listar':
                    $this->listar();
                    break;
                default:
                    header("Location: ../../");
                    break;
            }
        } else {
            header("Location: ../../");
        }
    }

    public function cadastrar($data) {
        $categoria = new Categoria();
        $categoria->setNome($data['nome']);
        $categoria->setStatus($data['status']);

        if ($this->categoriaCrud->create($categoria)) {
            echo json_encode(['status' => 'success', 'message' => 'Categoria cadastrada com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar categoria.']);
        }
    }

    public function editar($data) {
        $categoria = new Categoria();
        $categoria->setIdCategoria($data['categoria_id']);
        $categoria->setNome($data['nome']);
        $categoria->setStatus($data['status']);

        
        if ($this->categoriaCrud->update($categoria)) {
            echo json_encode(['status' => 'success', 'message' => 'Categoria editada com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao editar categoria.']);
        }
    }

    public function deletar($idCategoria) {
        if ($this->categoriaCrud->delete($idCategoria)) {
            echo json_encode(['status' => 'success', 'message' => 'Categoria deletada com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao deletar categoria.']);
        }
    }

    public function visualizar($idCategoria) {
        $categoria = $this->categoriaCrud->read($idCategoria);
        if ($categoria) {
            echo json_encode(['status' => 'success', 'data' => $categoria]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Categoria não encontrada.']);
        }
    }

    public function listar() {
        $categorias = $this->categoriaCrud->listAll();
        if ($categorias) {
            echo json_encode(['status' => 'success', 'data' => $categorias]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhuma categoria encontrada.']);
        }
    }
}

$controller = new CategoriaController();
$controller->processRequest();
?>
