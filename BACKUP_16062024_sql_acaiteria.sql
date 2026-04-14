-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16-Jun-2024 às 20:54
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `acaiteria`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `franqueado`
--

CREATE TABLE `franqueado` (
  `idFranqueado` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `sobrenome` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `fone` varchar(45) DEFAULT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `cidade` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `franqueado`
--

INSERT INTO `franqueado` (`idFranqueado`, `nome`, `sobrenome`, `email`, `fone`, `estado`, `cidade`) VALUES
(2, 'Maria', 'Silva', 'maria@gmail.com', '(61) 98523-6589', 'MA', 'Codó'),
(3, 'Joãosinho', 'Alcântra', 'joao@gmai.com', '(61) 9865-2158', 'PA', 'Passo Alto'),
(5, 'Teste de Cliente', 'Sobrenome', 'meunome@gmai.com', '(61) 98523-6589', 'DF', 'Águas Claras'),
(6, 'Joãosinho', 'Borges', 'alex@teste.com', '(61) 9865-2158', 'DF', 'Bumba');

-- --------------------------------------------------------

--
-- Estrutura da tabela `franquia`
--

CREATE TABLE `franquia` (
  `idFranquia` int(11) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `qtde_atual` varchar(45) DEFAULT NULL,
  `preco` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `franquia`
--

INSERT INTO `franquia` (`idFranquia`, `tipo`, `qtde_atual`, `preco`) VALUES
(1, 'Açaí', '10', '1.560.000,00'),
(5, 'Açaí Gourmet', '5', '150.000,00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `localidade`
--

CREATE TABLE `localidade` (
  `idLocalidade` int(11) NOT NULL,
  `estado` varchar(45) DEFAULT NULL,
  `permissao` varchar(45) DEFAULT NULL COMMENT '1 - Permitido\r\n2 - Não permitido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `localidade`
--

INSERT INTO `localidade` (`idLocalidade`, `estado`, `permissao`) VALUES
(1, 'DF', '2'),
(2, 'AP', '1'),
(3, 'GO', '1'),
(6, 'DF', '2');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `franqueado`
--
ALTER TABLE `franqueado`
  ADD PRIMARY KEY (`idFranqueado`);

--
-- Índices para tabela `franquia`
--
ALTER TABLE `franquia`
  ADD PRIMARY KEY (`idFranquia`);

--
-- Índices para tabela `localidade`
--
ALTER TABLE `localidade`
  ADD PRIMARY KEY (`idLocalidade`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `franqueado`
--
ALTER TABLE `franqueado`
  MODIFY `idFranqueado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `franquia`
--
ALTER TABLE `franquia`
  MODIFY `idFranquia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `localidade`
--
ALTER TABLE `localidade`
  MODIFY `idLocalidade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
