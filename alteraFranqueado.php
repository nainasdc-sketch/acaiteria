<?php
// Incluir o arquivo de conexão com o banco de dados e a classe da franqueado
include_once "../server/Conexao.php";
include_once "../server/FranqueadoClasse.php";
include_once "../server/FranqueadoCRUD.php";

// Verificar se o ID da franqueado foi passado por parâmetro na URL
if (isset($_GET['id'])) {
    // Instanciar a classe de CRUD da franqueado
    $franqueadoCRUD = new FranqueadoCRUD();

    // Buscar os dados da franqueado pelo ID
    $idFranqueado = $_GET['id'];
    $franqueado = $franqueadoCRUD->read($idFranqueado);

    // Verificar se a franqueado foi encontrada no banco de dados
    if ($franqueado) {
        // Preencher o formulário com os dados da franqueado
        $nome = $franqueado->getNome();
        $sobrenome = $franqueado->getSobrenome();
        $email = $franqueado->getEmail();
        $fone = $franqueado->getFone();
        $estado = $franqueado->getEstado();
        $cidade = $franqueado->getCidade();

    } else {
        // Se a franqueado não for encontrada, redirecionar para a página de listagem
        header("Location: listaFranqueado.php");
        exit();
    }
} else {
    // Se o ID da franqueado não for fornecido, redirecionar para a página de listagem
    header("Location: listaFranqueado.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alteração de Franqueado</title>
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
        <h1 class="text-center mb-4">Alteração da Franqueado</h1>
        <form action="alteraFranqueado.php" method="POST" id="form-edicao-franqueado">

            <input type="hidden" name="idFranqueado" value="<?php echo $franqueado->getIdFranqueado(); ?>">
            <input type="hidden" name="action" value="editar">
            <div class="form-group">
                <label for="nome">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome; ?>" required>
            </div>
            <div class="form-group">
                <label for="sobrenome">Sobrenome</label>
                <input type="text" class="form-control" id="sobrenome" name="sobrenome" value="<?php echo $sobrenome; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label for="fone">Telefone</label>
                <input type="text" class="form-control" id="fone" name="fone" value="<?php echo $fone; ?>" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="">Selecione o Estado...</option>
                    <option value="AC" <?php echo ($estado == "AC" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Acre</option>
                    <option value="AL" <?php echo ($estado == "AL" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Alagoas</option>
                    <option value="AP" <?php echo ($estado == "AP" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Amapá</option>
                    <option value="AM" <?php echo ($estado == "AM" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Amazonas</option>
                    <option value="BA" <?php echo ($estado == "BA" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Bahia</option>
                    <option value="CE" <?php echo ($estado == "CE" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Ceará</option>
                    <option value="DF" <?php echo ($estado == "DF" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Distrito Federal</option>
                    <option value="ES" <?php echo ($estado == "ES" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Espírito Santo</option>
                    <option value="GO" <?php echo ($estado == "GO" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Goiás</option>
                    <option value="MA" <?php echo ($estado == "MA" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Maranhão</option>
                    <option value="MT" <?php echo ($estado == "MT" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Mato Grosso</option>
                    <option value="MS" <?php echo ($estado == "MS" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Mato Grosso do Sul</option>
                    <option value="MG" <?php echo ($estado == "MG" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Minas Gerais</option>
                    <option value="PA" <?php echo ($estado == "PA" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Pará</option>
                    <option value="PB" <?php echo ($estado == "PB" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Paraíba</option>
                    <option value="PR" <?php echo ($estado == "PR" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Paraná</option>
                    <option value="PE" <?php echo ($estado == "PE" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Pernambuco</option>
                    <option value="PI" <?php echo ($estado == "PI" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Piauí</option>
                    <option value="RJ" <?php echo ($estado == "RJ" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Rio de Janeiro</option>
                    <option value="RN" <?php echo ($estado == "RN" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Rio Grande do Norte</option>
                    <option value="RS" <?php echo ($estado == "RS" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Rio Grande do Sul</option>
                    <option value="RO" <?php echo ($estado == "RO" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Rondônia</option>
                    <option value="RR" <?php echo ($estado == "RR" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Roraima</option>
                    <option value="SC" <?php echo ($estado == "SC" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Santa Catarina</option>
                    <option value="SP" <?php echo ($estado == "SP" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>São Paulo</option>
                    <option value="SE" <?php echo ($estado == "SE" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Sergipe</option>
                    <option value="TO" <?php echo ($estado == "TO" ? 'selected="true" style="background-color:#b5ffb5"' : '' ) ?>>Tocantins</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cidade">Cidade</label>
                <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo $cidade; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Alterar</button>
            <a href="listaFranqueado.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../assets/scripts/scripts.js"></script>

</body>
</html>
