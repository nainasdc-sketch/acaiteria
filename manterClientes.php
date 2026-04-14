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
    <title>Manter Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
        <h1>Manter Clientes</h1>

        <!-- Botão para abrir modal de cadastro de cliente -->
        <button class="btn btn-primary mb-3" id="btnCadastrarCliente">Cadastrar Cliente</button>

        <!-- Tabela para listar clientes -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody id="tabelaClientes">
                <!-- Clientes serão inseridos aqui via JavaScript -->
            </tbody>
        </table>

        <!-- Modal para Cadastro/Edição de Cliente -->
        <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalClienteLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalClienteLabel">Cadastrar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formCliente">
                            <input type="hidden" id="cliente_id" name="cliente_id">
                            <div class="mb-3">
                                <label for="nomeCliente" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nomeCliente" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_nasc" class="form-label">Data de Nascimento</label>
                                <input type="text" class="form-control inputDataNasc" id="dataNascCliente" name="data_nasc" required placeholder="dd/mm/yyyy">
                            </div>
                            <div class="mb-3">
                                <label for="emailCliente" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="emailCliente" name="email" required>
                            </div>
                            <div class="mb-3" id="boxStatus">
                                <label for="statusCliente" class="form-label">Status</label>
                                <select name="status" class="form-control" id="statusCliente" required>
                                    <option value="0">Ativo</option>    
                                    <option value="1">Inativo</option>
                                </select>
                            </div>
                            <div class="mb-3" id="boxNivel">
                                <label for="nivelCliente" class="form-label">Nível</label>
                                <select name="nivel" class="form-control" id="nivelCliente" required>
                                    <option value="0">Cliente</option>    
                                    <option value="1">Funcionário Nível 1</option>
                                    <option value="2">Funcionário Nível 2</option>
                                    <option value="3">Funcionário Nível 3</option>
                                    <option value="4">Funcionário Nível 4</option>
                                </select>
                            </div>
                            <div class="login_area" style="padding: 20px 10px 10px 10px;border-radius: 10px;margin-top: 40px;box-shadow: 0 0 18px 4px #5555550f;">
                                <h5 style="text-align: center;">Área de Login</h5>
                                <div class="mb-3">
                                    <label for="foneCliente" class="form-label">Telefone para Login</label>
                                    <input type="text" class="form-control" id="foneCliente" name="fone" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" required>
                                </div>
                                <div class="mb-3">
                                    <label for="senhaCliente" class="form-label" id="labelSenha">Senha</label>
                                    <input type="password" class="form-control" id="senhaCliente" name="senha" required>
                                </div>
                                <div class="mb-3" id="boxNovaSenha">
                                    <label for="novaSenhaCliente" class="form-label">Nova Senha</label>
                                    <input type="password" class="form-control" id="novaSenhaCliente" name="novaSenha">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="salvarCliente()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Cadastro/Edição de Endereço -->
        <div class="modal fade" id="modalEndereco" tabindex="-1" aria-labelledby="modalEnderecoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEnderecoLabel">Cadastrar Endereço</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEndereco">
                            <input type="hidden" id="cliente_id_endereco" name="cliente_id">
                            <div class="mb-3">
                                <label for="cepCliente" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cepCliente" name="cep" required onblur="buscarEnderecoPorCep()">
                            </div>
                            <div class="mb-3">
                                <label for="numeroCliente" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numeroCliente" name="numero" required>
                            </div>
                            <div class="mb-3">
                                <label for="logradouro" class="form-label">Endereço</label>
                                <input type="text" class="form-control" id="logradouro" name="logradouro" required disabled>
                            </div>
                            <div class="mb-3">
                                <label for="estadoCliente" class="form-label">Estado</label>
                                <input type="text" class="form-control" id="estadoCliente" name="estado" required disabled>
                            </div>
                            <div class="mb-3">
                                <label for="cidadeCliente" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidadeCliente" name="cidade" required disabled>
                            </div>
                            <div class="mb-3">
                                <label for="bairroCliente" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairroCliente" name="bairro" required disabled>
                            </div>
                            <div class="mb-3">
                                <label for="complementoCliente" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complementoCliente" name="complemento">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="salvarEndereco()">Salvar Endereço</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#dataNascCliente", {
            dateFormat: "d/m/Y",  // Formato dia/mês/ano
        });

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
            setTimeout(function () {
                var v = mphone(o.value);
                if (v != o.value) {
                    o.value = v;
                }
            }, 1);
        }

        function mphone(v) {
            var r = v.replace(/\D/g,"");
            r = r.replace(/^0/,"");
            if (r.length > 10) {
                // 11+ digits. Format as 5+4.
                r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/,"($1) $2-$3");
            }
            else if (r.length > 5) {
                // 6..10 digits. Format as 4+4
                r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/,"($1) $2-$3");
            }
            else if (r.length > 2) {
                // 3..5 digits. Add (0XX..)
                r = r.replace(/^(\d\d)(\d{0,5})/,"($1) $2");
            }
            else {
                // 0..2 digits. Just add (0XX
                r = r.replace(/^(\d*)/, "($1");
            }
            return r;
        }

        // Adiciona os eventos aos campos do formulário
        document.getElementById('nomeCliente').addEventListener('blur', function() {
            this.value = formatarNome(this.value);
        });

        document.getElementById('foneCliente').addEventListener('input', function() {
            this.value = formatarTelefone(this.value);
        });

        // Função para buscar o endereço pelo CEP
        function buscarEnderecoPorCep() {
            const cep = document.getElementById('cepCliente').value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('logradouro').value = data.logradouro || '';
                            document.getElementById('bairroCliente').value = data.bairro || '';
                            document.getElementById('cidadeCliente').value = data.localidade || '';
                            document.getElementById('estadoCliente').value = data.uf || '';

                            document.getElementById('logradouro').disabled = false;
                            document.getElementById('bairroCliente').disabled = false;
                            document.getElementById('cidadeCliente').disabled = false;
                            document.getElementById('estadoCliente').disabled = false;
                        } else {
                            alert('CEP não encontrado.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar CEP:', error);
                        alert('Erro ao buscar o CEP.');
                    });
            }
        }

        document.getElementById('btnCadastrarCliente').addEventListener('click', function () {
            document.getElementById('formCliente').reset();
            document.getElementById('cliente_id').value = '';
            document.getElementById('boxStatus').style.display = 'none';
            document.getElementById('modalClienteLabel').innerText = 'Cadastrar Cliente';
            document.getElementById('boxNovaSenha').style.display = "none";
            document.getElementById('foneCliente').style.background = "#ffff";
            document.getElementById('foneCliente').readOnly = false;
            
            document.getElementById('labelSenha').innerText = "Senha";
            var modal = new bootstrap.Modal(document.getElementById('modalCliente'));
            modal.show();
        });

        function salvarCliente() {
            const formData = new FormData(document.getElementById('formCliente'));

            // Verifica se é para editar ou cadastrar um cliente
            if (document.getElementById('cliente_id').value) {
                formData.append('action', 'editar');
            } else {
                formData.append('action', 'cadastrar');
            }

            fetch('../server/ClienteController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Erro ao salvar o cliente: ' + data.message);
                }
            })
            .catch(error => console.error('Erro ao salvar o cliente:', error));
        }

        function editarCliente(id) {
            const formData = new FormData();
            document.getElementById('boxStatus').style.display = 'block';
            document.getElementById('foneCliente').readOnly = true;
            document.getElementById('foneCliente').style.background = "#cdcdcd";
            document.getElementById('novaSenhaCliente').style.display = "block";
            document.getElementById('labelSenha').innerText = "Senha Atual";

            formData.append('action', 'visualizar');
            formData.append('cliente_id', id);

            fetch('../server/ClienteController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    alert(data.message);
                    return;
                }

                document.getElementById('cliente_id').value = data.cliente_id;
                document.getElementById('nomeCliente').value = data.nome || '';
                document.getElementById('dataNascCliente').value = data.data_nasc || '';
                document.getElementsByClassName('inputDataNasc').value = data.data_nasc || '';
                document.getElementById('emailCliente').value = data.email || '';
                document.getElementById('statusCliente').value = data.status;
                document.getElementById('foneCliente').value = data.fone || '';
                document.getElementById('senhaCliente').value = ''; // Deixar vazio por segurança
                document.getElementById('novaSenhaCliente').value = ''; // Deixar vazio por segurança
                document.getElementById('modalClienteLabel').innerText = 'Editar Cliente';
                document.getElementById('nivelCliente').value = data.nivel;
                
                // Abrir o modal
                var modal = new bootstrap.Modal(document.getElementById('modalCliente'));
                modal.show();
            })
            .catch(error => console.error('Erro ao buscar cliente:', error));
        }

        function editarEndereco(id) {
            const formData = new FormData();
            formData.append('action', 'visualizarEndereco');
            formData.append('cliente_id', id);

            document.getElementById('cliente_id_endereco').value = id;

            fetch('../server/EnderecoController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'error') {
                    if(data.message === 'vazio'){
                        // Abrir o modal de endereço
                        var modal = new bootstrap.Modal(document.getElementById('modalEndereco'));
                        modal.show();
                        return;
                    }else{
                        alert(data.message);
                        return;
                    }
                }

                document.getElementById('logradouro').disabled = false;
                document.getElementById('bairroCliente').disabled = false;
                document.getElementById('cidadeCliente').disabled = false;
                document.getElementById('estadoCliente').disabled = false;

                document.getElementById('logradouro').value = data.logradouro || '';
                document.getElementById('numeroCliente').value = data.numero || '';
                document.getElementById('bairroCliente').value = data.bairro || '';
                document.getElementById('cidadeCliente').value = data.cidade || '';
                document.getElementById('estadoCliente').value = data.estado || '';
                document.getElementById('cepCliente').value = data.cep || '';
                document.getElementById('complementoCliente').value = data.complemento || '';
                document.getElementById('modalEnderecoLabel').innerText = 'Editar Endereço';
                
                // Abrir o modal de endereço
                var modal = new bootstrap.Modal(document.getElementById('modalEndereco'));
                modal.show();
            })
            .catch(error => console.error('Erro ao buscar endereço:', error));
        }

        function salvarEndereco() {
            const formData = new FormData(document.getElementById('formEndereco'));
            const complemento = document.getElementById('complementoCliente').value;
            formData.append('action', 'salvarEndereco');

            if(complemento == ""){
                formData.append('complemento', 'Nenhum');
            }

            fetch('../server/EnderecoController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert('Erro ao salvar o endereço: ' + data.message);
                }
            })
            .catch(error => console.error('Erro ao salvar o endereço:', error));
        }

        function deletarCliente(id) {
            if (confirm('Tem certeza que deseja excluir este cliente?')) {
                const formData = new FormData();
                formData.append('action', 'deletar');
                formData.append('cliente_id', id);

                fetch('../server/ClienteController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Erro ao excluir o cliente: ' + data.message);
                    }
                })
                .catch(error => console.error('Erro ao excluir o cliente:', error));
            }
        }

        // Função para listar os clientes
        function listarClientes() {
            fetch('../server/ClienteController.php', {
                method: 'POST',
                body: new URLSearchParams({ 'action': 'listar' })
            })
            .then(response => response.json())
            .then(data => {
                const tabelaClientes = document.getElementById('tabelaClientes');
                tabelaClientes.innerHTML = '';

                data.forEach(cliente => {
                    const statusTexto = cliente.status == '0' ? 'Ativo' : 'Inativo';
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${cliente.cliente_id}</td>
                        <td>${cliente.nome}</td>
                        <td>${cliente.data_nasc}</td>
                        <td>${cliente.email}</td>
                        <td>${cliente.fone}</td>
                        <td>${statusTexto}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarCliente(${cliente.cliente_id})">Editar</button>
                            <button class="btn btn-info btn-sm" onclick="editarEndereco(${cliente.cliente_id})">Editar Endereço</button>
                            <button class="btn btn-danger btn-sm" onclick="deletarCliente(${cliente.cliente_id})">Excluir</button>
                        </td>
                    `;
                    tabelaClientes.appendChild(tr);
                });
            })
            .catch(error => console.error('Erro ao listar clientes:', error));
        }
        
        function logout() {
            sessionStorage.clear();
            window.location.href = "../serverPublic/logout.php";
        }

        // Carrega os clientes ao abrir a página
        listarClientes();
    </script>
</body>

</html>
