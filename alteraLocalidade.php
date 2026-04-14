<?php
// Incluir o arquivo de conexão com o banco de dados e a classe da localidade
include_once "../server/Conexao.php";
include_once "../server/LocalidadeClasse.php";
include_once "../server/LocalidadeCRUD.php";

// Verificar se o ID da localidade foi passado por parâmetro na URL
if (isset($_GET['id'])) {
    // Instanciar a classe de CRUD da localidade
    $localidadeCRUD = new LocalidadeCRUD();

    // Buscar os dados da localidade pelo ID
    $idLocalidade = $_GET['id'];
    $localidade = $localidadeCRUD->read($idLocalidade);

    // Verificar se a localidade foi encontrada no banco de dados
    if ($localidade) {
        // Preencher o formulário com os dados da localidade
        $estado = $localidade->getEstado();
        $permissao = $localidade->getPermissao();
    } else {
        // Se a localidade não for encontrada, redirecionar para a página de listagem
        header("Location: listaLocalidade.php");
        exit();
    }
} else {
    // Se o ID da localidade não for fornecido, redirecionar para a página de listagem
    header("Location: listaLocalidade.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração de Localidade</title>
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
        <h1 class="text-center mb-4">Alteração da Localidade</h1>
        <form action="alteraLocalidade.php" method="POST" id="form-edicao-localidade">

            <input type="hidden" name="idLocalidade" value="<?php echo $localidade->getIdLocalidade(); ?>">
            <input type="hidden" name="action" value="editar">
            <div class="form-group">
                <label for="estado">Estado</label>
                <input type="text" class="form-control" id="estado" name="estado" value="<?php echo $estado; ?>" required>
            </div>
            <div class="form-group">
                <select class="form-control" id="permissao" name="permissao" required>
                    <option value="">Selecione o Status da Permissão...</option>
                    <option value="1" <?php if($permissao == 1){echo 'selected="true" style="background-color: #b5ffb5"';} ?>>Permitido</option>
                    <option value="2" <?php if($permissao == 2){echo 'selected="true" style="background-color: #b5ffb5"s';} ?>>Não Permitido</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Alterar</button>
            <a href="listaLocalidade.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/scripts/scripts.js"></script>
</body>
</html>
