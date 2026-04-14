<?php
session_start();
include_once "server/ClienteCRUD.php";
include_once "server/LoginCRUD.php";
include_once "server/ComandaCRUD.php";
include_once "server/FuncionarioCRUD.php";

// Verifica se o usuário já está logado
if (isset($_SESSION['cliente_id'])) {
    if (isset($_SESSION['nivel_acesso'])) {
        if ($_SESSION['nivel_acesso']  === 0) {
            header("Location: ../loja.php");
            exit;
        } elseif ($_SESSION['nivel_acesso'] > 0) {
            header("Location: admin/listaPedidos.php");
            exit;
        }
    }
}

$erro = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : "";

// Limpa a mensagem de erro após exibi-la para evitar que apareça em recargas subsequentes
unset($_SESSION['login_error']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['fone'];
    $senha = $_POST['senha'];

    $loginCrud = new LoginCRUD();
    $acesso = $loginCrud->findByLogin($login);
    
    // Verificar senha e status do usuário
    if ($acesso && password_verify($senha, $acesso['senha'])) {
        $clienteCrud = new ClienteCRUD();
        $cliente = $clienteCrud->findClientByLogin($acesso['id_login']);



        // Veririca se existe um cliente associado ao login e cria a comanda caso exista
        if($cliente){

            // Gerar e cadastrar uma nova comanda
            $comandaCrud = new ComandaCRUD();
            $comandaCodigo = gerarCodigoComanda();
            $cadastraComanda = $comandaCrud->cadComandaInicial($cliente['cliente_id'], $comandaCodigo);
            
            if($cadastraComanda){
                // Salvar código da comanda na sessão
                $_SESSION['comanda_codigo'] = $comandaCodigo;

                $_SESSION['cliente_id'] = $cliente['cliente_id'];
                $_SESSION['nivel_acesso'] = $acesso['nivel'];
                $_SESSION['primeiro_nome'] = getPrimeiroESegundoNome($cliente['nome']);

            }else{
                $_SESSION['login_error'] = "Houve um problema ao iniciar uma comanda!";
            }

        }else{

            $funcionarioCrud = new FuncionarioCRUD();
            $funcionario = $funcionarioCrud->findFuncByLogin($acesso['id_login']);

            if($funcionario){
                $_SESSION['funcionario_id'] = $funcionario['funcionario_id'];
                $_SESSION['funcionario_nome'] = getPrimeiroESegundoNome($funcionario['nome']);
                $_SESSION['nivel_acesso'] = $acesso['nivel'];
            }else{
                $_SESSION['login_error'] = "Houve um problema ao buscar o usuário.";
            }
        }

        // Redirecionar de acordo com o nível de acesso
        if ($acesso['nivel'] === 0) {
            header('Location: loja.php');
        } else {
            header('Location: admin/listaPedidos.php');
        }

        exit;
    } else {
        // Define uma mensagem de erro que será capturada pelo formulário de login
        $_SESSION['login_error'] = "Login ou senha incorretos!";
    }
    
    header('Location: login.php');
    exit;
}

function getPrimeiroNome($nomeCompleto) {
    $partes = explode(' ', trim($nomeCompleto)); // Divide o nome por espaços e remove espaços extras
    return $partes[0]; // Retorna apenas o primeiro nome
}

function getPrimeiroESegundoNome($nomeCompleto) {
    $partes = explode(' ', trim($nomeCompleto)); // Divide o nome por espaços e remove espaços extras
    return isset($partes[1]) ? $partes[0] . ' ' . $partes[1] : $partes[0]; // Retorna o primeiro e o segundo nome, ou só o primeiro se não houver segundo
}

function gerarCodigoComanda() {
    // Gerar um código numérico único para a comanda
    return 'CMD' . time(); // Exemplo de código único
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
        }
        section.login {
            background-color: #4f0065;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100vw;
            height: 100vh;

        }
        section.login .box_login {
            width: 400px;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <section class="login">
        <div class="container mt-5 box_login">
            <h2>Login</h2>
            <?php if (!empty($erro)) {
                echo "<p class='text-danger'>$erro</p>";
            } ?>
            <form method="POST">
                <input type="hidden" name="action" value="fazer_login">
                <div class="mb-3">
                    <label for="foneCliente" class="form-label">Telefone para Login</label>
                    <input type="text" class="form-control" id="foneCliente" name="fone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
            <p><a href="cadastro.php">Não possui uma conta? Cadastre-se</a></p>
            <p style="display: none;"><a href="recuperar_senha.php">Esqueceu sua senha?</a></p>
        </div>
    </section>

    <script>
        function mask(o, f) {
            setTimeout(function() {
                var v = mphone(o.value);
                if (v != o.value) {
                    o.value = v;
                }
            }, 1);
        }

        function mphone(v) {
            var r = v.replace(/\D/g, "");
            r = r.replace(/^0/, "");
            if (r.length > 10) {
                r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
            } else if (r.length > 5) {
                r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
            } else if (r.length > 2) {
                r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
            } else {
                r = r.replace(/^(\d*)/, "($1");
            }
            return r;
        }
    </script>
</body>

</html>