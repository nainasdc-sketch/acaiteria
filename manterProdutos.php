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
    <title>Manter Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <h1>Manter Produtos</h1>

        <!-- Botão para abrir modal de cadastro de funcionário -->
        <button class="btn btn-primary mb-3" id="btnCadastrarProduto">Cadastrar Produto</button>

        <?php
        include_once "../server/ProdutoCRUD.php";
        include_once "../server/CategoriaCRUD.php";
        $produtoCrud = new ProdutoCRUD();
        $categoriaCrud = new CategoriaCRUD();

        // Listar produtos ativos e inativos (status 0 ou 1)
        $produtos = $produtoCrud->listAll();
        ?>

        <!-- Lista de Produtos -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-box"></i> Produtos
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Foto</th>
                            <th>Nome</th>
                            <th>Preço</th>
                            <th>Descrição</th>
                            <th>Ingredientes</th>
                            <th>Categoria</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($produtos as $produto) {
                            $statusText = $produto->getStatus() == 0 ? 'Ativo' : 'Inativo';
                            $fotoPath = ($produto->getFotoPasta() != NULL ? '../' . $produto->getFotoPasta() . $produto->getFotoNome() : '../assets/imagens/no_photo.webp');
                            $categoria = $categoriaCrud->getNomeById($produto->getCategoriaId());
                            echo "<tr>";
                            echo "<td>{$produto->getIdProduto()}</td>";
                            echo "<td><img src='{$fotoPath}' alt='Foto do Produto' width='50' height='50'></td>";
                            echo "<td>{$produto->getNome()}</td>";
                            echo "<td>R$ {$produto->getPreco()}</td>";
                            echo "<td>{$produto->getDescricao()}</td>";
                            echo "<td>{$produto->getIngredientes()}</td>";
                            echo "<td>{$categoria}</td>";
                            echo "<td>{$statusText}</td>";
                            echo "<td>
                                    <button class='btn btn-warning btn-sm' onclick='editarProduto({$produto->getIdProduto()})'>Editar</button>
                                    <button class='btn btn-danger btn-sm' onclick='deletarProduto({$produto->getIdProduto()})'>Excluir</button>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para Cadastro/Edição de Produto -->
        <div class="modal fade" id="modalProduto" tabindex="-1" aria-labelledby="modalProdutoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalProdutoLabel">Cadastrar Produto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formProduto" enctype="multipart/form-data">
                            <input type="hidden" id="produto_id" name="produto_id">
                            <input type="hidden" id="imagemAtualSrc" name="imagemAtualSrc">

                            <input type="hidden" name="action" id="action">
                            <div class="mb-3">
                                <label for="nomeProduto" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nomeProduto" name="nomeProduto" required>
                            </div>
                            <div class="mb-3">
                                <label for="precoProduto" class="form-label">Preço</label>
                                <input type="number" class="form-control" id="precoProduto" name="precoProduto" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricaoProduto" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricaoProduto" name="descricaoProduto" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="ingredientes" class="form-label">Ingredientes</label>
                                <textarea class="form-control" id="ingredientes" name="ingredientes" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="0">Ativo</option>
                                    <option value="1">Inativo</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoria</label>
                                <div class="input-group">
                                    <select name="categoria_id" id="categoria" class="form-control" required>
                                        <?php
                                        $categorias = $categoriaCrud->listAll();
                                        foreach ($categorias as $categoria) {
                                            echo "<option value='{$categoria->getIdCategoria()}'>{$categoria->getNome()}</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-secondary" onclick="abrirModalCategoria()">+</button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="fotoProduto" class="form-label">Foto do Produto (JPG ou WEBP, 1000x1000px, até 10MB)</label>
                                <input type="file" class="form-control" id="fotoProduto" name="fotoProduto" accept=".jpg, .webp">
                            </div>
                            <div class="mb-3">
                                <img id="imagemAtual" src="" alt="Imagem do Produto" style="max-width: 100%; height: auto; display: none;">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="salvarProduto()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para Cadastro/Edição de Categoria -->
        <div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCategoriaLabel">Cadastrar/Editar Categoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formCategoria">
                            <input type="hidden" id="categoria_id" name="categoria_id">
                            <div class="mb-3">
                                <label for="nomeCategoria" class="form-label">Nome da Categoria</label>
                                <input type="text" class="form-control" id="nomeCategoria" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="statusCategoria" class="form-label">Status Categoria</label>
                                <select name="status" id="statusCategoria" class="form-control">
                                    <option value="1" selected>Inativo</option>
                                    <option value="0">Ativo</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="salvarCategoria()">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('btnCadastrarProduto').addEventListener('click', function() {
            document.getElementById('formProduto').reset();
            document.getElementById('produto_id').value = '';
            document.getElementById('action').value = 'cadastrar';
            document.getElementById('modalProdutoLabel').innerText = 'Cadastrar Produto';
            var modal = new bootstrap.Modal(document.getElementById('modalProduto'));
            modal.show();
        });

        function editarProduto(id) {
            const formData = new FormData();
            formData.append('action', 'visualizar');
            formData.append('produto_id', id);

            fetch('../server/ProdutoController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'error') {
                        document.getElementById('produto_id').value = data.produto_id;
                        document.getElementById('nomeProduto').value = data.nome;
                        document.getElementById('precoProduto').value = data.preco;
                        document.getElementById('descricaoProduto').value = data.descricao;
                        document.getElementById('ingredientes').value = data.ingredientes;
                        document.getElementById('status').value = data.status;
                        document.getElementById('categoria').value = data.categoria_categoria_id;
                        document.getElementById('action').value = 'editar';

                        // Exibe a imagem atual do produto
                        const imagemAtual = document.getElementById('imagemAtual');
                        const imgAtualSrc = document.getElementById('imagemAtualSrc');
                        if (data.foto_pasta && data.foto_nome) {
                            imagemAtual.src = `../${data.foto_pasta}${data.foto_nome}`;
                            imagemAtual.style.display = 'block';

                            imgAtualSrc.value = `${data.foto_pasta}${data.foto_nome}`;
                        } else {
                            imagemAtual.style.display = 'none';
                        }

                        document.getElementById('modalProdutoLabel').innerText = 'Editar Produto';
                        var modal = new bootstrap.Modal(document.getElementById('modalProduto'));
                        modal.show();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Erro ao buscar produto:', error));
        }


        function salvarProduto() {
            const formData = new FormData(document.getElementById('formProduto'));

            // Verifica o tamanho e o tipo do arquivo
            const fotoProduto = document.getElementById('fotoProduto').files[0];
            fileExtension = '';
            if (fotoProduto) {
                if (fotoProduto.size > 10 * 1024 * 1024) {
                    alert('O arquivo deve ter no máximo 10MB.');
                    return;
                }
                if (!fotoProduto.type.includes('jpeg')) {
                    if (!fotoProduto.type.includes('webp')) {
                        alert('Apenas arquivos JPG e WEBP são permitidos.');
                        return;
                    } else {
                        fileExtension = '.webp';
                    }
                } else {
                    fileExtension = '.jpg';
                }

                // Gera um nome aleatório para a foto se uma nova imagem for carregada
                const randomName = Math.random().toString(36).substring(2, 12);
                const fileName = randomName + fileExtension;

                // Adiciona o nome e a pasta ao FormData
                formData.append('fotoNome', fileName);
                formData.append('fotoPasta', 'uploads/');

                // Adiciona o arquivo ao FormData para salvar na pasta 'uploads'
                formData.append('fotoProduto', fotoProduto, fileName);
            } else {
                const imagemAtualSrc = document.getElementById("imagemAtualSrc").value;

                if (imagemAtualSrc) {
                    formData.append('fotoNome', imagemAtualSrc.replace('uploads/', ''));
                    formData.append('fotoPasta', 'uploads/');
                } else {
                    formData.append('fotoNome', '');
                    formData.append('fotoPasta', '');
                }
            }

            fetch('../server/ProdutoController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Exibe a resposta completa no console
                    if (data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Erro ao salvar o produto: ' + data.message);
                    }
                })
                .catch(error => console.error('Erro ao salvar o produto:', error));
        }


        function deletarProduto(id) {
            if (confirm('Tem certeza que deseja excluir este produto?')) {
                const formData = new FormData();
                formData.append('action', 'deletar');
                formData.append('produto_id', id);

                fetch('../server/ProdutoController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert('Erro ao excluir o produto.');
                        }
                    })
                    .catch(error => console.error('Erro ao excluir o produto:', error));
            }
        }

        function abrirModalCategoria() {
            document.getElementById('formCategoria').reset();
            document.getElementById('categoria_id').value = '';
            var modal = new bootstrap.Modal(document.getElementById('modalCategoria'));
            modal.show();
        }

        function salvarCategoria() {
            const formData = new FormData(document.getElementById('formCategoria'));
            formData.append('action', 'cadastrar');

            // Exibir os dados no console
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            fetch('../server/CategoriaController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Categoria salva com sucesso. A página será recarregada.');
                        location.reload();
                    } else {
                        alert('Erro ao salvar a categoria.');
                    }
                })
                .catch(error => console.error('Erro ao salvar a categoria:', error));
        }

        function editarCategoria(id) {
            const formData = new FormData();
            formData.append('action', 'visualizar');
            formData.append('idCategoria', id);

            fetch('../server/CategoriaController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('categoria_id').value = data.categoria_id;
                    document.getElementById('nomeCategoria').value = data.nome;
                    document.getElementById('status').value = data.status;
                    document.getElementById('action').value = 'editar';
                    document.getElementById('modalCategoriaLabel').innerText = 'Editar Categoria';
                    var modal = new bootstrap.Modal(document.getElementById('modalCategoria'));
                    modal.show();
                })
                .catch(error => console.error('Erro ao buscar categoria:', error));
        }

        function deletarCategoria(id) {
            if (confirm('Tem certeza que deseja excluir esta categoria?')) {
                const formData = new FormData();
                formData.append('action', 'deletar');
                formData.append('idCategoria', id);

                fetch('../server/CategoriaController.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert('Categoria excluída com sucesso!');
                            location.reload();
                        } else {
                            alert('Erro ao excluir a categoria.');
                        }
                    })
                    .catch(error => console.error('Erro ao excluir a categoria:', error));
            }
        }

        
        function logout() {
            sessionStorage.clear();
            window.location.href = "../serverPublic/logout.php";
        }
    </script>
</body>

</html>