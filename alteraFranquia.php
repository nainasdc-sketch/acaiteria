<?php
// Incluir o arquivo de conexão com o banco de dados e a classe da franquia
include_once "../server/Conexao.php";
include_once "../server/FranquiaClasse.php";
include_once "../server/FranquiaCRUD.php";

// Verificar se o ID da franquia foi passado por parâmetro na URL
if (isset($_GET['id'])) {
    // Instanciar a classe de CRUD da franquia
    $franquiaCRUD = new FranquiaCRUD();

    // Buscar os dados da franquia pelo ID
    $idFranquia = $_GET['id'];
    $franquia = $franquiaCRUD->read($idFranquia);

    // Verificar se a franquia foi encontrada no banco de dados
    if ($franquia) {
        // Preencher o formulário com os dados da franquia
        $tipo = $franquia->getTipo();
        $qtde_atual = $franquia->getQtdeAtual();
        $preco = $franquia->getPreco();
    } else {
        // Se a franquia não for encontrada, redirecionar para a página de listagem
        header("Location: listaFranquia.php");
        exit();
    }
} else {
    // Se o ID da franquia não for fornecido, redirecionar para a página de listagem
    header("Location: listaFranquia.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração de Franquia</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <?php include_once '../_resources/menu.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Alteração da Franquia</h1>
        <form action="alteraFranquia.php" method="POST" id="form-edicao-franquia">

            <input type="hidden" name="idFranquia" value="<?php echo $franquia->getIdFranquia(); ?>">
            <input type="hidden" name="action" value="editar">
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <input type="text" class="form-control" id="tipo" name="tipo" value="<?php echo $tipo; ?>" required>
            </div>
            <div class="form-group">
                <label for="qtde_atual">Quantidade Atual</label>
                <input type="number" class="form-control" id="qtde_atual" name="qtde_atual" value="<?php echo $qtde_atual; ?>" required>
            </div>
            <div class="form-group">
                <label for="preco">Preço</label>
                <input type="text" class="form-control" id="preco" name="preco" value="<?php echo $preco; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Alterar</button>
            <a href="listaFranquia.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/scripts/scripts.js"></script>
</body>
</html>
