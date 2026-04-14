<?php
include_once "../server/Conexao.php";
include_once "../server/FranqueadoClasse.php";
include_once "../server/FranqueadoCRUD.php";

$franqueadoCrud = new FranqueadoCRUD();
$franqueados = $franqueadoCrud->listAll();

if (is_array($franqueados) && count($franqueados) > 0 && $franqueados[0] instanceof Franqueado) {
    session_start();
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lista de Franqueados</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .container {
                margin-top: 50px;
            }
        </style>
    </head>
    <body>

    <?php include_once '../_resources/menu.php'; ?>

    <div class="container">
        <h1 class="text-center mt-4">Lista de Franqueados</h1>
        <table class="table table-striped table-bordered text-center">
            <thead class="thead-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Sobrenome</th>
                <th scope="col">Email</th>
                <th scope="col">Telefone</th>
                <th scope="col">Estado</th>
                <th scope="col">Cidade</th>
                <th scope="col">Opções</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($franqueados as $franqueado) : ?>
                <tr>
                    <th scope="row"><?php echo $franqueado->getIdFranqueado(); ?></th>
                    <td><?php echo $franqueado->getNome(); ?></td>
                    <td><?php echo $franqueado->getSobrenome(); ?></td>
                    <td><?php echo $franqueado->getEmail(); ?></td>
                    <td><?php echo $franqueado->getFone(); ?></td>
                    <td><?php echo $franqueado->getEstado(); ?></td>
                    <td><?php echo $franqueado->getCidade(); ?></td>
                    <td>
                        <a href="alteraFranqueado.php?id=<?php echo $franqueado->getIdFranqueado(); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm excluir-franqueado" data-id="<?php echo $franqueado->getIdFranqueado(); ?>">
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
    <?php
} else {
    session_start();
    $_SESSION['mensagem'] = "Erro ao obter a lista de franqueados.";
    header("Location: index.php");
    exit;
}
?>
