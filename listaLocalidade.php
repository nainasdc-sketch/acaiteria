<?php
// Incluir o arquivo de conexão com o banco de dados e as classes necessárias
include_once "../server/Conexao.php";
include_once "../server/LocalidadeClasse.php";
include_once "../server/LocalidadeCRUD.php";

// Criar uma instância do CRUD de Localidades
$localidadeCRUD = new LocalidadeCRUD();

// Obter a lista de todas as localidades
$localidades = $localidadeCRUD->listAll();

?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Localidades</title>
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
            <h1 class="text-center mt-4">Lista de Localidades</h1>
            <table class="table table-striped table-bordered text-center mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Permissão</th>
                        <th scope="col">Opções</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($localidades as $localidade) : ?>
                        <tr>
                            <th scope="row"><?php echo $localidade->getIdLocalidade(); ?></th>
                            <td><?php echo $localidade->getEstado(); ?></td>
                            <td><?php echo ($localidade->getPermissao() == 1 ? 'Permitido' : 'Não Permitido' ); ?></td>
                            <td>
                                <a href="alteraLocalidade.php?id=<?php echo $localidade->getIdLocalidade(); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm excluir-localidade" data-id="<?php echo $localidade->getIdLocalidade(); ?>">
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
