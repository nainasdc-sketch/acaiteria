<?php
// Incluir o arquivo de conexão com o banco de dados e as classes necessárias
include_once "../server/Conexao.php";
include_once "../server/FranquiaClasse.php";
include_once "../server/FranquiaCRUD.php";

// Criar uma instância do CRUD de Franquias
$franquiaCRUD = new FranquiaCRUD();

// Obter a lista de todas as franquias
$franquias = $franquiaCRUD->listAll();

?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Franquias</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <style>
            .container {
                margin-top: 50px;
            }
        </style>
    </head>
    <body>
        <?php include '../_resources/menu.php'; ?>

        <div class="container">
            <h1 class="text-center mt-4">Lista de Franquias</h1>
            <table class="table table-striped table-bordered text-center mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Quantidade Atual</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($franquias as $franquia) : ?>
                        <tr>
                            <th scope="row"><?php echo $franquia->getIdFranquia(); ?></th>
                            <td><?php echo $franquia->getTipo(); ?></td>
                            <td><?php echo $franquia->getQtdeAtual(); ?></td>
                            <td>R$ <?php echo $franquia->getPreco(); ?></td>
                            <td>
                                <a href="alteraFranquia.php?id=<?php echo $franquia->getIdFranquia(); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm excluir-franquia" data-id="<?php echo $franquia->getIdFranquia(); ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="../assets/scripts/scripts.js"></script>
    </body>
</html>
