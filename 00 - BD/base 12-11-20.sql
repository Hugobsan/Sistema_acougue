-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 12-Nov-2020 às 14:09
-- Versão do servidor: 10.4.6-MariaDB
-- versão do PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `acougue`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `administrador`
--

CREATE TABLE `administrador` (
  `IdAdm` int(10) UNSIGNED NOT NULL,
  `Nome` varchar(49) COLLATE latin1_general_ci NOT NULL,
  `Senha` varchar(33) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `administrador`
--

INSERT INTO `administrador` (`IdAdm`, `Nome`, `Senha`) VALUES
(6, 'Admin', 'admin');

-- --------------------------------------------------------

--
-- Estrutura da tabela `caderneta`
--

CREATE TABLE `caderneta` (
  `id_caderneta` int(20) NOT NULL,
  `status_caderneta` varchar(10) COLLATE latin1_general_ci NOT NULL,
  `data_abertura` date NOT NULL,
  `id_cliente` int(10) NOT NULL,
  `data_fechamento` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `caderneta`
--

INSERT INTO `caderneta` (`id_caderneta`, `status_caderneta`, `data_abertura`, `id_cliente`, `data_fechamento`) VALUES
(29, 'fechada', '2020-10-20', 3, '2020-10-20'),
(25, 'fechada', '2020-10-03', 1, '2020-10-03'),
(26, 'fechada', '2020-10-19', 1, '2020-10-20'),
(27, 'fechada', '2020-10-20', 1, '2020-10-21'),
(21, 'fechada', '2020-09-27', 2, '2020-10-20'),
(23, 'fechada', '2020-09-27', 1, '2020-10-03'),
(30, 'fechada', '2020-10-21', 1, '2020-10-21'),
(31, 'fechada', '2020-10-21', 1, '2020-10-28'),
(33, 'fechada', '2020-10-21', 11, '2020-10-21'),
(34, 'aberta', '2020-10-21', 11, NULL),
(35, 'fechada', '2020-10-21', 2, '2020-10-21'),
(36, 'aberta', '2020-10-21', 2, NULL),
(37, 'fechada', '2020-10-28', 13, '2020-10-28'),
(38, 'aberta', '2020-10-28', 13, NULL),
(39, 'fechada', '2020-10-28', 1, '2020-10-28'),
(40, 'fechada', '2020-10-28', 1, '2020-10-28'),
(41, 'aberta', '2020-10-28', 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `caixa`
--

CREATE TABLE `caixa` (
  `id_dia` int(11) NOT NULL,
  `valor` float NOT NULL,
  `data_fechamento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `caixa`
--

INSERT INTO `caixa` (`id_dia`, `valor`, `data_fechamento`) VALUES
(2, 650, '2020-11-12');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(10) NOT NULL,
  `nome_cliente` varchar(110) COLLATE latin1_general_ci NOT NULL,
  `cpf_cliente` varchar(30) COLLATE latin1_general_ci NOT NULL,
  `telefone_cliente` varchar(25) COLLATE latin1_general_ci NOT NULL,
  `endereco_cliente` varchar(110) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(40) NOT NULL,
  `data_compra` date DEFAULT NULL,
  `id_prod_comprado` int(10) DEFAULT NULL,
  `cod_barras` varchar(13) COLLATE latin1_general_ci DEFAULT NULL,
  `peso_produto` float DEFAULT NULL,
  `nome_produto` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `valor_produto` float DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `preco_unidade` float DEFAULT NULL,
  `id_caderneta` int(11) DEFAULT NULL,
  `nome_cliente` varchar(110) COLLATE latin1_general_ci DEFAULT NULL,
  `exibir_linha` int(1) DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `compra`
--

INSERT INTO `compra` (`id_compra`, `data_compra`, `id_prod_comprado`, `cod_barras`, `peso_produto`, `nome_produto`, `valor_produto`, `id_cliente`, `preco_unidade`, `id_caderneta`, `nome_cliente`, `exibir_linha`) VALUES
(73, '2020-10-21', 2, NULL, 0.2, 'Carne de Segunda', 4.6, 1, 23, 27, 'Heloisa Evelyn Santos', 0),
(72, '2020-10-21', 3, NULL, 2, 'Lombo de Porco', 28, 1, 14, 27, 'Heloisa Evelyn Santos', 0),
(71, '2020-10-21', 6, NULL, 0.436, 'Toucinho', 3.488, NULL, 8, NULL, NULL, 0),
(70, '2020-10-21', 2, NULL, 0.35, 'Carne de Segunda', 8.05, NULL, 23, NULL, NULL, 0),
(67, '2020-10-20', 2, NULL, 0.5, 'Carne de Segunda', 11.5, NULL, 23, NULL, NULL, 0),
(68, '2020-10-20', 2, NULL, 0.2, 'Carne de Segunda', 4.6, NULL, 23, NULL, NULL, 0),
(69, '2020-10-20', 1, NULL, 2, 'Carne de Primeira', 54, NULL, 27, NULL, NULL, 0),
(17, '2020-10-20', 11, NULL, 2, 'Coxa Pacote', 27, NULL, 13.5, NULL, NULL, 0),
(18, '2020-10-20', 11, NULL, 2, 'Coxa Pacote', 27, NULL, 13.5, NULL, NULL, 0),
(19, '2020-10-20', 11, NULL, 0.5, 'Coxa Pacote', 6.75, NULL, 13.5, NULL, NULL, 0),
(20, '2020-10-20', 12, NULL, 50, 'Asa de Frango', 800, NULL, 16, NULL, NULL, 0),
(21, '2020-10-20', 3, NULL, 2, 'Lombo de Porco', 28, NULL, 14, NULL, NULL, 0),
(22, '2020-10-20', 4, NULL, 2, 'Carne de Porco', 24, NULL, 12, NULL, NULL, 0),
(23, '2020-10-20', 5, NULL, 4, 'Costelinha de Porco', 56, NULL, 14, NULL, NULL, 0),
(51, '2020-10-20', 2, '1000200230000', 111.48, 'Carne de Segunda', 2564.04, NULL, 23, NULL, NULL, 0),
(30, '2020-10-20', 3, NULL, 5, 'Lombo de Porco', 70, NULL, 14, NULL, NULL, 0),
(47, '2020-10-20', 11, '1001100135000', 0.6, 'Coxa Pacote', 8.1, NULL, 13.5, NULL, NULL, 0),
(48, '2020-10-20', 2, '1000200230000', 1.48, 'Carne de Segunda', 34.04, NULL, 23, NULL, NULL, 0),
(50, '2020-10-20', 2, '1000200230000', 11.48, 'Carne de Segunda', 264.04, NULL, 23, NULL, NULL, 0),
(52, '2020-10-20', 2, '1000200230001', 111.48, 'Carne de Segunda', 2564.04, NULL, 23, NULL, NULL, 0),
(53, '2020-10-20', 2, '1000200235000', 111.48, 'Carne de Segunda', 2619.78, NULL, 23.5, NULL, NULL, 0),
(54, '2020-10-20', 2, '1000200235500', 111.48, 'Carne de Segunda', 2625.35, NULL, 23.55, NULL, NULL, 0),
(55, '2020-10-20', 2, '1000200235550', 111.48, 'Carne de Segunda', 2625.35, NULL, 23.55, NULL, NULL, 0),
(56, '2020-10-20', 11, '1001100230000', 111.48, 'Coxa Pacote', 2564.04, NULL, 23, NULL, NULL, 0),
(74, '2020-10-21', 2, NULL, 0.2, 'Carne de Segunda', 4.6, 1, 23, 27, 'Heloisa Evelyn Santos', 0),
(79, '2020-10-21', 1, NULL, 0.12, 'Carne de Primeira', 3.24, 1, 27, 30, 'Heloisa Evelyn Santos', 0),
(78, '2020-10-21', 2, NULL, 11.48, 'Carne de Segunda', 264.04, 1, 23, 30, 'Heloisa Evelyn Santos', 0),
(89, '2020-10-21', 2, '1000200230000', 1.48, 'Carne de Segunda', 34.04, NULL, 23, NULL, NULL, 0),
(82, '2020-10-21', 3, NULL, 2, 'Lombo de Porco', 28, NULL, 14, NULL, NULL, 0),
(86, '2020-10-21', 2, '0100020023000', 11.48, 'Carne de Segunda', 264.04, 1, 23, 30, 'Heloisa Evelyn Santos', 0),
(88, '2020-10-21', 2, NULL, 1, 'Carne de Segunda', 23, NULL, 23, NULL, NULL, 0),
(100, '2020-10-21', NULL, NULL, NULL, 'Valor Fixo', 200, 1, NULL, 31, 'Heloisa Evelyn Santos', 0),
(103, '2020-10-21', 1, NULL, 1, 'Carne de Primeira', 27, NULL, 27, NULL, NULL, 0),
(106, '2020-10-21', 2, '1000200230000', 11.48, 'Carne de Segunda', 264.04, NULL, 23, NULL, NULL, 0),
(107, '2020-10-21', NULL, NULL, NULL, 'Valor Fixo', 100, 11, NULL, 34, 'Hugo Barbosa Santos', 0),
(108, '2020-10-21', 11, '0100110013500', 0.6, 'Coxa Pacote', 8.1, 11, 13.5, 34, 'Hugo Barbosa Santos', 0),
(110, '2020-10-21', 2, '0100020023000', 1.48, 'Carne de Segunda', 34.04, 11, 23, 34, 'Hugo Barbosa Santos', 0),
(111, '2020-10-21', 2, '0100020023000', 11.48, 'Carne de Segunda', 264.04, 2, 23, 36, 'Bruna Vanessa Sophia Almeida', 0),
(113, '2020-10-21', 2, NULL, 0.21, 'Carne de Segunda', 4.83, NULL, 23, NULL, NULL, 0),
(114, '2020-10-23', 11, '0100110013500', 0.6, 'Coxa Pacote', 8.1, 2, 13.5, 36, 'Bruna Vanessa Sophia Almeida', 0),
(115, '2020-10-24', 2, '1000200014802', 1.48, 'Carne de Segunda', 34.04, NULL, 23, NULL, NULL, 0),
(116, '2020-10-24', 2, '1000200114822', 11.482, 'Carne de Segunda', 264.086, NULL, 23, NULL, NULL, 0),
(117, '2020-10-24', 2, '0100020001480', 1.48, 'Carne de Segunda', 34.04, 2, 23, 36, 'Bruna Vanessa Sophia Almeida', 0),
(118, '2020-10-28', 2, '1000200148024', 14.802, 'Carne de Segunda', 340.446, NULL, 23, NULL, NULL, 0),
(119, '2020-10-28', 2, '1000200148024', 14.802, 'Carne de Segunda', 340.446, NULL, 23, NULL, NULL, 0),
(120, '2020-10-28', 2, NULL, 0.3, 'Carne de Segunda', 6.9, NULL, 23, NULL, NULL, 0),
(121, '2020-10-28', NULL, NULL, NULL, 'Valor Fixo', 200, 1, NULL, 31, 'Heloisa Evelyn Santos', 0),
(122, '2020-10-28', NULL, NULL, NULL, 'Valor Fixo', 100, 1, NULL, 39, 'Heloisa Evelyn Santos', 0),
(123, '2020-10-28', NULL, NULL, NULL, 'Valor Fixo', 200, 1, NULL, 41, 'Heloisa Evelyn Santos', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id_produto` int(10) NOT NULL,
  `nome_produto` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `preco_unidade` float NOT NULL,
  `estoque_atual` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id_produto`, `nome_produto`, `preco_unidade`, `estoque_atual`) VALUES
(11, 'Toucinho', 10, 0),
(10, 'Suan de Porco', 30, 0),
(9, 'Pernil de Porco', 15, 0),
(8, 'Carne de Porco', 17, 0),
(7, 'Costelinha de Porco', 18, 0),
(6, 'Lombo de Porco', 18, 0),
(5, 'Costela de Boi', 15, 0),
(4, 'Dobradinha', 20, 0),
(3, 'Carne de Sol', 26, 0),
(2, 'Carne de Boi 2', 26, 0),
(1, 'Carne de Boi 1', 32, 0),
(12, 'Tripa de Porco', 10, 0),
(13, 'Barriga de Porco', 15, 0),
(14, 'Pezinho de Porco', 12, 0),
(15, 'Linguiça de Porco', 12, 0),
(16, 'Linguiça Caseira', 23, 0),
(17, 'Linguiça Calabresa', 21, 0),
(18, 'Linguiça Flip', 22, 0),
(19, 'Linguiça Suína', 20.75, 0),
(20, 'Linguiça de Pernil', 20.75, 0),
(21, 'Linguiça de Frango', 20.75, 0),
(22, 'Frango', 8.9, 0),
(24, 'Asa de Frango Pacote', 18.6, 0),
(26, 'Coxa de frango Pacote', 16.7, 0),
(27, 'Peito de frango bandeja', 15, 0),
(28, 'Moela de frango', 10.6, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`IdAdm`);

--
-- Índices para tabela `caderneta`
--
ALTER TABLE `caderneta`
  ADD PRIMARY KEY (`id_caderneta`);

--
-- Índices para tabela `caixa`
--
ALTER TABLE `caixa`
  ADD PRIMARY KEY (`id_dia`);

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Índices para tabela `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id_produto`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `IdAdm` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `caderneta`
--
ALTER TABLE `caderneta`
  MODIFY `id_caderneta` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de tabela `caixa`
--
ALTER TABLE `caixa`
  MODIFY `id_dia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(40) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id_produto` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
