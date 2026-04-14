<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        section.register {
            background-color: #4f0065;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100vw;
            height: 100vh;

        }

        section.register .box_register {
            width: 600px;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <section class="register">
        <div class="container mt-5 box_register">
            <!-- Título da página -->
            <h1>Realizar Cadastro</h1>

            <!-- Formulário de cadastro -->
            <form id="formCliente">
                <div class="mb-3">
                    <label for="nomeCliente" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nomeCliente" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="dataNascCliente" class="form-label">Data de Nascimento</label>
                    <input type="text" class="form-control inputDataNasc" id="dataNascCliente" name="data_nasc" required placeholder="dd/mm/yyyy">
                </div>
                <div class="mb-3">
                    <label for="emailCliente" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="emailCliente" name="email" required>
                </div>

                <!-- Área de login e senha -->
                <div class="login_area" style="padding: 20px 10px 10px 10px; border-radius: 10px; margin-top: 40px; box-shadow: 0 0 18px 4px #5555550f;">
                    <h5 style="text-align: center;">Área de Login</h5>
                    <div class="mb-3">
                        <label for="foneCliente" class="form-label">Telefone para Login</label>
                        <input type="text" class="form-control" id="foneCliente" name="fone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
                    </div>
                    <div class="mb-3">
                        <label for="senhaCliente" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senhaCliente" name="senha" required>
                    </div>
                </div>

                <!-- Botão de cadastro -->
                <button type="button" class="btn btn-primary mt-3" onclick="cadastrarCliente()">Cadastrar</button>
                <a href="login.php" class="btn btn-secondary mt-3">Fazer Login</a>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#dataNascCliente", {
            dateFormat: "d/m/Y",
        });

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

        async function cadastrarCliente() {
            const formData = new FormData(document.getElementById('formCliente'));
            formData.append('action', 'cadastrar');

            try {
                const response = await fetch('server/ClienteController.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.status === 'success') {
                    alert("Cliente cadastrado com sucesso!");
                    window.location.href = "login.php";
                } else {
                    alert("Erro ao cadastrar cliente: " + data.message);
                }
            } catch (error) {
                console.error("Erro ao cadastrar cliente:", error);
            }
        }
    </script>
</body>

</html>