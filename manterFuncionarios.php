<?php
session_start();

function verificarLogin()
{
    if (!isset($_SESSION['funcionario_id']) || $_SESSION['nivel_acesso'] === 0) {
        header("Location: ../loja.php");
        exit();
    }
}

verificarLogin();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manter Funcionários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/estilos/style-store.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="box_branding">
                        <img src="../assets/imagens/logomarca.webp" alt="Amor de Açaí" style="border-radius: 10px;">
                        <h1>Área Administrativa</h1>
                    </div>
                </div>
                <div class="col" style="align-items: center;display: flex;">
                    <div class="perfil-container">
                        <img src="../assets/icons/perfil.webp" alt="Ícone de perfil"> <!-- Insira o caminho do ícone de perfil aqui -->
                        <span><?php echo htmlspecialchars($_SESSION['funcionario_nome']); ?></span>
                        <button type="submit" class="logout-btn" onclick="logout()">Sair</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Admin Açaiteria</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="listaPedidos.php">Pedidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manterClientes.php">Clientes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manterProdutos.php">Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manterFuncionarios.php">Funcionários</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="container mt-5">
        <!-- Título da página -->
        <h1>Manter Funcionários</h1>

        <!-- Botão para abrir modal de cadastro de funcionário -->
        <button class="btn btn-primary mb-3" id="btnCadastrarFuncionario">Cadastrar Funcionário</button>

        <!-- Tabela para listar funcionários -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaFuncionarios">
                <!-- Funcionários serão inseridos aqui via JavaScript -->
            </tbody>
        </table>

        <!-- Modal para Cadastro/Edição de Funcionário -->
        <div class="modal fade" id="modalFuncionario" tabindex="-1" aria-labelledby="modalFuncionarioLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFuncionarioLabel">Cadastrar Funcionário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formFuncionario">
                            <input type="hidden" id="funcionario_id" name="funcionario_id">
                            <div class="mb-3">
                                <label for="nomeFuncionario" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nomeFuncionario" name="nome" required>
                            </div>
                            <div class="mb-3" id="boxStatus" style="display: none;">
                                <label for="statusFuncionario" class="form-label">Status</label>
                                <select name="status" class="form-control" id="statusFuncionario" required>
                                    <option value="0">Ativo</option>
                                    <option value="1">Inativo</option>
                                </select>
                            </div>

                            <div class="login_area" style="padding: 20px 10px 10px 10px;border-radius: 10px;margin-top: 40px;box-shadow: 0 0 18px 4px #5555550f;">
                                <h5 style="text-align: center;">Área de Login</h5>
                                <div class="mb-3">
                                    <label for="foneFuncionario" class="form-label">Telefone para Login</label>
                                    <input type="text" class="form-control" id="foneFuncionario" name="fone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
                                </div>
                                <div class="mb-3">
                                    <label for="senhaFuncionario" class="form-label" id="labelSenha">Senha</label>
                                    <input type="password" class="form-control" id="senhaFuncionario" name="senha" required>
                                </div>
                                <div class="mb-3" id="boxNovaSenha" style="display: none;">
                                    <label for="novaSenhaFuncionario" class="form-label">Nova Senha</label>
                                    <input type="password" class="form-control" id="novaSenhaFuncionario" name="novaSenha">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="salvarFuncionario()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Função para formatar o nome
        function formatarNome(nome) {
            return nome
                .toLowerCase()
                .split(' ')
                .map(palavra => palavra.charAt(0).toUpperCase() + palavra.slice(1))
                .join(' ');
        }

        // Função para formatar o telefone
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
                // 11+ digits. Format as 5+4.
                r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
            } else if (r.length > 5) {
                // 6..10 digits. Format as 4+4
                r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
            } else if (r.length > 2) {
                // 3..5 digits. Add (0XX..)
                r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
            } else {
                // 0..2 digits. Just add (0XX
                r = r.replace(/^(\d*)/, "($1");
            }
            return r;
        }

        // Adiciona os eventos aos campos do formulário
        document.getElementById('nomeFuncionario').addEventListener('blur', function() {
            this.value = formatarNome(this.value);
        });

        document.getElementById('foneFuncionario').addEventListener('input', function() {
            this.value = formatarTelefone(this.value);
        });

        // Adiciona os eventos aos campos do formulário
        document.getElementById('btnCadastrarFuncionario').addEventListener('click', function() {
            document.getElementById('formFuncionario').reset();
            document.getElementById('funcionario_id').value = '';
            document.getElementById('boxStatus').style.display = 'none';
            document.getElementById('boxNovaSenha').style.display = "none";
            document.getElementById('foneFuncionario').readOnly = false;
            document.getElementById('foneFuncionario').style.background = "#ffff";

            document.getElementById('modalFuncionarioLabel').innerText = 'Cadastrar Funcionário';
            document.getElementById('labelSenha').innerText = "Senha";
            var modal = new bootstrap.Modal(document.getElementById('modalFuncionario'));
            modal.show();
        });

        function salvarFuncionario() {
            const formData = new FormData(document.getElementById('formFuncionario'));

            // Verifica se é para editar ou cadastrar um funcionário
            if (document.getElementById('funcionario_id').value) {
                formData.append('action', 'editar');
            } else {
                formData.append('action', 'cadastrar');
            }

            fetch('../server/FuncionarioController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Erro ao salvar o funcionário: ' + data.message);
                    }
                })
                .catch(error => console.error('Erro ao salvar o funcionário:', error));
        }

        function editarFuncionario(id) {
            const formData = new FormData();
            formData.append('action', 'visualizar');
            formData.append('funcionario_id', id);

            fetch('../server/FuncionarioController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        alert(data.message);
                        return;
                    }

                    document.getElementById('funcionario_id').value = data.funcionario_id;
                    document.getElementById('nomeFuncionario').value = data.nome || '';
                    document.getElementById('statusFuncionario').value = data.status;
                    document.getElementById('foneFuncionario').value = data.fone || '';
                    document.getElementById('senhaFuncionario').value = ''; // Deixar vazio por segurança
                    document.getElementById('novaSenhaFuncionario').value = ''; // Deixar vazio por segurança

                    document.getElementById('boxStatus').style.display = 'block';
                    document.getElementById('boxNovaSenha').style.display = "block";
                    document.getElementById('foneFuncionario').readOnly = true;
                    document.getElementById('foneFuncionario').style.background = "#cdcdcd";

                    document.getElementById('labelSenha').innerText = "Senha Atual";
                    document.getElementById('modalFuncionarioLabel').innerText = 'Editar Funcionário';

                    // Abrir o modal
                    var modal = new bootstrap.Modal(document.getElementById('modalFuncionario'));
                    modal.show();
                })
                .catch(error => console.error('Erro ao buscar funcionário:', error));
        }

        function deletarFuncionario(id) {
            if (confirm('Tem certeza que deseja excluir este funcionário?')) {
                const formData = new FormData();
                formData.append('action', 'deletar');
                formData.append('funcionario_id', id);

                fetch('../server/FuncionarioController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert('Erro ao excluir o funcionário: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Erro ao excluir o funcionário:', error));
            }
        }

        // Função para listar os funcionários
        function listarFuncionarios() {
            fetch('../server/FuncionarioController.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'action': 'listar'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    const tabelaFuncionarios = document.getElementById('tabelaFuncionarios');
                    tabelaFuncionarios.innerHTML = '';

                    data.forEach(funcionario => {
                        const statusTexto = funcionario.status == '0' ? 'Ativo' : 'Inativo';
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                        <td>${funcionario.funcionario_id}</td>
                        <td>${funcionario.nome}</td>
                        <td>${statusTexto}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarFuncionario(${funcionario.funcionario_id})">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="deletarFuncionario(${funcionario.funcionario_id})">Excluir</button>
                        </td>
                    `;
                        tabelaFuncionarios.appendChild(tr);
                    });
                })
                .catch(error => console.error('Erro ao listar funcionários:', error));
        }

        function logout() {
            sessionStorage.clear();
            window.location.href = "../serverPublic/logout.php";
        }

        // Carrega os funcionários ao abrir a página
        listarFuncionarios();
    </script>
</body>

</html>