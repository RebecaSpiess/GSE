-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.73
-- Generation Time: 19-Set-2019 às 22:24
-- Versão do servidor: 5.6.40-84.0-log
-- PHP Version: 5.6.30-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shcglobal`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `FREQUENCIA`
--

CREATE TABLE `FREQUENCIA` (
  `ID` int(20) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `DATA` date NOT NULL,
  `PRESENCA` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `MATERIA`
--

CREATE TABLE `MATERIA` (
  `ID` int(5) NOT NULL,
  `NOME` varchar(255) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `NOTAS`
--

CREATE TABLE `NOTAS` (
  `ID` int(5) NOT NULL,
  `ID_TURMA` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `NOTA` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `OCORRENCIA`
--

CREATE TABLE `OCORRENCIA` (
  `ID` int(20) NOT NULL,
  `ID_PESSOA_ALUNO` int(15) NOT NULL,
  `ID_PESSOA_AUTOR` int(15) NOT NULL,
  `DATA` date NOT NULL,
  `DESCRICAO` varchar(250) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `PESSOA`
--

CREATE TABLE `PESSOA` (
  `ID` int(15) UNSIGNED NOT NULL,
  `NOME` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `SOBRENOME` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `E-MAIL` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `TIPO_PESSOA` int(1) NOT NULL,
  `SENHA` varchar(250) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `PLANO_AULA`
--

CREATE TABLE `PLANO_AULA` (
  `ID` int(11) NOT NULL,
  `ID_TURMA` int(15) NOT NULL,
  `ID_PESSOA` int(10) NOT NULL,
  `DESCRICAO` varchar(250) COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `TIPO_PESSOA`
--

CREATE TABLE `TIPO_PESSOA` (
  `ID` int(1) UNSIGNED ZEROFILL NOT NULL,
  `NOME` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `TURMA`
--

CREATE TABLE `TURMA` (
  `ID` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `ID_MATERIA` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `FREQUENCIA`
--
ALTER TABLE `FREQUENCIA`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `MATERIA`
--
ALTER TABLE `MATERIA`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `NOTAS`
--
ALTER TABLE `NOTAS`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `PESSOA`
--
ALTER TABLE `PESSOA`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `PLANO_AULA`
--
ALTER TABLE `PLANO_AULA`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `TIPO_PESSOA`
--
ALTER TABLE `TIPO_PESSOA`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `U_NOME_TIPO` (`NOME`);

--
-- Indexes for table `TURMA`
--
ALTER TABLE `TURMA`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `FREQUENCIA`
--
ALTER TABLE `FREQUENCIA`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `MATERIA`
--
ALTER TABLE `MATERIA`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `NOTAS`
--
ALTER TABLE `NOTAS`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PESSOA`
--
ALTER TABLE `PESSOA`
  MODIFY `ID` int(15) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `PLANO_AULA`
--
ALTER TABLE `PLANO_AULA`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `TIPO_PESSOA`
--
ALTER TABLE `TIPO_PESSOA`
  MODIFY `ID` int(1) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
