<?php
include_once "Conexao.php";
include_once "PublicClasse.php";

class ProdutoCRUD {
    public function read($idProduto) {
        $sql = 'SELECT * FROM produto WHERE produto_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idProduto);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listAll() {
        $sql = 'SELECT * FROM produto WHERE status IN (0, 1)';
        $stmt = Conexao::getInstance()->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $produtos = [];
        foreach ($result as $row) {
            $produto = new Produto();
            $produto->setIdProduto($row['produto_id']);
            $produto->setNome($row['nome']);
            $produto->setIngredientes($row['ingredientes']);
            $produto->setDescricao($row['descricao']);
            $produto->setPreco($row['preco']);
            $produto->setStatus($row['status']);
            $produto->setFotoNome($row['foto_nome']);
            $produto->setFotoPasta($row['foto_pasta']);
            $produto->setCategoriaId($row['categoria_categoria_id']);
            $produtos[] = $produto;
        }
    
        return $produtos;
    }

    public function listAllActive() {
        $conexao = Conexao::getInstance();
        $sql = "SELECT p.produto_id, p.nome, p.preco, p.descricao, p.ingredientes, CONCAT(p.foto_pasta, p.foto_nome) AS imagem, c.nome as categoria
                FROM produto p
                INNER JOIN categoria c ON p.categoria_categoria_id = c.categoria_id
                WHERE p.status = 0 AND c.status = 0 ORDER BY p.nome ASC";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listAllActiveByCategory($categoriaId) {
        $conexao = Conexao::getInstance();
        $sql = "SELECT p.produto_id, p.nome, p.preco, p.descricao, p.ingredientes, CONCAT(p.foto_pasta, p.foto_nome) AS imagem, c.nome as categoria
                FROM produto p
                INNER JOIN categoria c ON p.categoria_categoria_id = c.categoria_id
                WHERE p.status = 0 AND c.status = 0 AND categoria_categoria_id = ? ORDER BY p.nome ASC";
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $categoriaId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProdutoNomeById($idProduto) {
        $sql = 'SELECT nome FROM produto WHERE produto_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idProduto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['nome'];
    }
}


class CategoriaCRUD {
    public function create(Categoria $categoria) {
        $sql = 'INSERT INTO categoria (nome, status) VALUES (?, ?)';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $categoria->getNome());
        $stmt->bindValue(2, $categoria->getStatus());
        return $stmt->execute();
    }

    public function read($idCategoria) {
        $sql = 'SELECT * FROM categoria WHERE categoria_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idCategoria);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function listAllActive() {
        $conexao = Conexao::getInstance();
        $sql = 'SELECT categoria_id, nome, status FROM categoria WHERE status IN (0)';
        $stmt = $conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getNomeById($idCategoria) {
        $sql = 'SELECT nome FROM categoria WHERE categoria_id = ?';
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $idCategoria);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['nome'];
    }
}

Class ComandaCRUD {
    public function finalizarPedido($dados) {
        // Define o fuso horário para São Paulo
        date_default_timezone_set('America/Sao_Paulo');
        $data_cad = date('Y-m-d H:i:s');
    
        $sql = "UPDATE comanda SET 
                    tipo_retirada = ?, 
                    taxa_entrega = ?, 
                    taxa_info = ?, 
                    forma_pagamento = ?, 
                    observacao = ?, 
                    status = ?, 
                    data_cad = ?
                WHERE cod_comanda = ?";
        
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $dados['tipo_retirada'], PDO::PARAM_INT);
        $stmt->bindValue(2, $dados['taxa_entrega'], PDO::PARAM_STR);
        $stmt->bindValue(3, $dados['taxa_info'], PDO::PARAM_STR);
        $stmt->bindValue(4, $dados['forma_pagamento'], PDO::PARAM_STR);
        $stmt->bindValue(5, $dados['observacao'], PDO::PARAM_STR);
        $stmt->bindValue(6, $dados['status'], PDO::PARAM_INT);
        $stmt->bindValue(7, $data_cad, PDO::PARAM_STR);
        $stmt->bindValue(8, $dados['cod_comanda'], PDO::PARAM_STR);
    
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false];
        }
    }   
    
    
    public function cadComandaInicial($clienteId, $codigoComanda) {

        $sql_consult = 'SELECT * FROM comanda WHERE cliente_cliente_id = ?';
        $stmt_consult = Conexao::getInstance()->prepare($sql_consult);
        $stmt_consult->bindValue(1, $clienteId);
        $stmt_consult->execute();
        $result = $stmt_consult->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($result as $row) {
            if($row['status'] == 0){
                $sql_update = 'UPDATE comanda SET status = ? WHERE comanda_id = ? AND status = ?';
                $stmt_update = Conexao::getInstance()->prepare($sql_update);
                $stmt_update->bindValue(1, 9); //Concluído
                $stmt_update->bindValue(2, $row['comanda_id']);
                $stmt_update->bindValue(3, 0);
                $stmt_update->execute();
            }
        }

        $sql_cad = 'INSERT INTO comanda (cliente_cliente_id, cod_comanda, status) VALUES (?, ?, ?)';
        $stmt_cad = Conexao::getInstance()->prepare($sql_cad);
        $stmt_cad->bindValue(1, $clienteId);
        $stmt_cad->bindValue(2, $codigoComanda);
        $stmt_cad->bindValue(3, '0');

        return $stmt_cad->execute();
    }
}

Class PedidoCRUD {
    public function inserirPedido($dados) {

        // Verificar se a comanda existe
        $sqlVerificarComanda = "SELECT comanda_id FROM comanda WHERE cod_comanda = ? AND cliente_cliente_id = ?";
        $stmtVerificarComanda = Conexao::getInstance()->prepare($sqlVerificarComanda);
        $stmtVerificarComanda->bindValue(1, $dados['cod_comanda'], PDO::PARAM_STR);
        $stmtVerificarComanda->bindValue(2, $dados['fk_cliente_id'], PDO::PARAM_INT);
        $stmtVerificarComanda->execute();
        $comanda = $stmtVerificarComanda->fetch(PDO::FETCH_ASSOC);
    
        if (!$comanda) {
            return ['success' => false, 'message' => 'Comanda ou cliente não encontrado.'];
        }
    
        // Verificar se o produto existe
        $sqlVerificarProduto = "SELECT produto_id FROM produto WHERE produto_id = ?";
        $stmtVerificarProduto = Conexao::getInstance()->prepare($sqlVerificarProduto);
        $stmtVerificarProduto->bindValue(1, $dados['fk_produto_id'], PDO::PARAM_INT);
        $stmtVerificarProduto->execute();
        $produto = $stmtVerificarProduto->fetch(PDO::FETCH_ASSOC);
    
        if (!$produto) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }
    
        // Inserir o pedido
        $sql = "INSERT INTO pedido (fk_comanda_id, fk_cliente_id, fk_produto_id, qtde, preco_un, preco_tt, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = Conexao::getInstance()->prepare($sql);
        $stmt->bindValue(1, $comanda['comanda_id'], PDO::PARAM_INT);
        $stmt->bindValue(2, $dados['fk_cliente_id'], PDO::PARAM_INT);
        $stmt->bindValue(3, $dados['fk_produto_id'], PDO::PARAM_INT);
        $stmt->bindValue(4, $dados['qtde'], PDO::PARAM_INT);
        $stmt->bindValue(5, $dados['preco_un'], PDO::PARAM_STR);
        $stmt->bindValue(6, $dados['preco_tt'], PDO::PARAM_STR);
        $stmt->bindValue(7, $dados['status'], PDO::PARAM_INT);
    
        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Erro ao inserir o pedido.'];
        }
    }  
}