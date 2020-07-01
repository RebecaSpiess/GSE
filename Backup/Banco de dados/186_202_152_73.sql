-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.73
-- Generation Time: 30-Jun-2020 às 21:30
-- Versão do servidor: 5.6.40-84.0-log
-- PHP Version: 5.6.40-0+deb8u11

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
CREATE DATABASE IF NOT EXISTS `shcglobal` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci;
USE `shcglobal`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `FREQUENCIA`
--

CREATE TABLE `FREQUENCIA` (
  `ID` int(20) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `DATA` date NOT NULL,
  `PRESENCA` tinyint(1) NOT NULL,
  `ID_TURMA` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `FREQUENCIA`
--

INSERT INTO `FREQUENCIA` (`ID`, `ID_PESSOA`, `DATA`, `PRESENCA`, `ID_TURMA`) VALUES
(5, 26, '1994-09-16', 1, 21),
(6, 30, '1994-09-16', 1, 21),
(7, 26, '2019-09-16', 1, 21),
(8, 30, '2019-09-16', 1, 21),
(11, 30, '2019-11-15', 1, 21),
(12, 30, '2019-11-17', 1, 22),
(13, 28, '2019-11-17', 0, 22),
(14, 6, '2019-11-17', 1, 22),
(15, 22, '2019-11-17', 0, 22),
(16, 30, '2019-12-05', 1, 21),
(17, 30, '1998-12-12', 1, 21),
(18, 30, '2019-12-12', 1, 22),
(19, 28, '2019-12-12', 1, 22),
(20, 6, '2019-12-12', 0, 22),
(21, 22, '2019-12-12', 0, 22),
(22, 30, '2019-12-05', 1, 22),
(23, 28, '2019-12-05', 0, 22),
(24, 6, '2019-12-05', 1, 22),
(25, 22, '2019-12-05', 0, 22);

-- --------------------------------------------------------

--
-- Estrutura da tabela `MATERIA`
--

CREATE TABLE `MATERIA` (
  `ID` int(5) NOT NULL,
  `NOME` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `MATERIA`
--

INSERT INTO `MATERIA` (`ID`, `NOME`) VALUES
(9, 'Alemão'),
(3, 'Ciência'),
(6, 'Geografia'),
(8, 'Inglês'),
(2, 'Matemática'),
(1, 'Português'),
(7, 'Trigonometria');

-- --------------------------------------------------------

--
-- Estrutura da tabela `MENSAGEM`
--

CREATE TABLE `MENSAGEM` (
  `ID` int(11) NOT NULL,
  `REMETENTE` int(15) DEFAULT NULL,
  `DESTINATARIO` int(15) DEFAULT NULL,
  `AVISO` varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
  `DATA_HORA_AVISO` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `MENSAGEM`
--

INSERT INTO `MENSAGEM` (`ID`, `REMETENTE`, `DESTINATARIO`, `AVISO`, `DATA_HORA_AVISO`) VALUES
(1, 4, 35, ' sdsdsds', '2019-11-27 00:02:36'),
(2, 4, 35, ' ewewewew', '2019-11-27 00:06:26'),
(3, 4, 35, ' 1212121', '2019-11-27 00:06:49'),
(4, 4, 35, ' Teste', '2019-11-27 00:34:03'),
(5, 4, 35, ' Teste', '2019-11-27 00:36:08'),
(6, 4, 35, ' teste', '2019-11-27 00:53:30'),
(7, 4, 35, ' sdsdsd', '2019-11-27 00:57:01'),
(8, 4, 35, ' sdsdsd', '2019-11-27 00:58:20'),
(9, 4, 35, ' dfdfdfd', '2019-11-27 01:11:08'),
(10, 4, 35, ' sdsdsddss', '2019-11-27 01:11:44'),
(11, 4, 35, ' dfdfdfd', '2019-11-27 01:13:28'),
(12, 4, 35, ' sdsdsdsdsds', '2019-11-27 01:15:57'),
(13, 4, 35, ' dfdfdfdfd', '2019-11-27 01:36:54'),
(14, 4, 4, ' sdsdsdsdsdssdsdssdsds', '2019-11-27 01:46:10'),
(15, 4, 4, ' sdsdsdsdsdssdsdssdsds', '2019-11-27 01:46:35'),
(16, 4, 6, ' dfdfdfdfd', '2019-11-27 01:46:41'),
(17, 4, 6, ' weweewwewewew', '2019-11-27 01:47:11'),
(18, 4, 6, ' weweewwewewew', '2019-11-27 01:48:25'),
(19, 4, 6, ' dfdfdsad', '2019-11-27 01:48:29'),
(20, 4, 6, ' dfdfdsad', '2019-11-27 01:48:57'),
(21, 4, 6, ' sdsdsds', '2019-11-27 01:49:01'),
(22, 4, 6, ' sdsdsds', '2019-11-27 01:49:37'),
(23, 4, 6, ' sdsdsds', '2019-11-27 01:50:52'),
(24, 4, 4, ' sdsdsdsds', '2019-11-27 01:51:20'),
(25, 4, 4, ' Teste', '2019-11-27 01:54:03'),
(26, 4, 6, 'KKKKKKKKKKKKKKKKKKKK', '2019-11-27 01:54:12'),
(27, 4, 6, ' testssds', '2019-11-27 01:55:15'),
(28, 4, 6, ' 11111111111111111', '2019-11-27 01:55:33'),
(29, 4, 6, ' Teste2', '2019-11-27 01:57:12'),
(30, 4, 6, ' Teste 3', '2019-11-27 01:58:00'),
(31, 4, 6, 'Test 4', '2019-11-27 02:02:37'),
(32, 4, 6, ' Test 6\r\n', '2019-11-27 02:04:14'),
(33, 4, 6, 'Test 7', '2019-11-27 02:05:38'),
(34, 4, 6, ' 6666666666', '2019-11-27 02:08:30'),
(35, 4, 6, ' 000000000000000', '2019-11-27 02:09:30'),
(36, 4, 6, ' erererr', '2019-11-27 02:10:25'),
(37, 4, 6, ' 89898989989898  *-****', '2019-11-27 02:11:09'),
(38, 4, 6, ' 89898989989898  *-****', '2019-11-27 02:12:36'),
(39, 4, 6, ' dfdfdfdfdfdfdfdfddddddddddddddddddddddddddd', '2019-11-27 02:12:45'),
(40, 4, 6, ' Azul- T1', '2019-11-27 02:14:15'),
(41, 4, 6, ' Test', '2019-11-27 02:18:27'),
(42, 4, 6, ' T078', '2019-11-27 02:19:03'),
(43, 4, 6, ' t02', '2019-11-27 02:20:28'),
(44, 4, 6, ' tetetetetete', '2019-11-27 02:23:10'),
(45, 4, 6, ' 888888888888888888888888888888888888888888888888888888888', '2019-11-27 02:24:43'),
(46, 4, 6, ' 888888888888888888888888888888888888888888888888888888888', '2019-11-27 02:25:18'),
(47, 4, 6, ' 9999999999999999999999999999999999999', '2019-11-27 02:25:31'),
(48, 4, 6, ' 10212454422', '2019-11-27 02:29:29'),
(49, 4, 6, ' Teste100', '2019-11-27 02:31:21'),
(50, 4, 6, ' Teste101', '2019-11-27 02:32:47'),
(51, 4, 6, ' T102', '2019-11-27 02:33:14'),
(52, 4, 6, ' T303', '2019-11-27 02:34:41'),
(53, 4, 6, ' Reply test', '2019-11-27 02:36:34'),
(54, 4, 6, ' Db close test', '2019-11-27 02:41:52'),
(55, 4, 35, ' T987', '2019-11-27 02:43:14'),
(56, 4, 6, ' T987', '2019-11-27 02:43:39'),
(57, 4, 6, ' T852456', '2019-11-27 02:45:21'),
(58, 4, 4, ' Rebeca Spiess Test', '2019-11-27 03:34:04'),
(59, 4, 4, ' Teste 1000000000000000', '2019-11-27 03:34:26'),
(60, 4, 4, ' AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', '2019-11-27 03:50:00'),
(61, 4, 35, ' Boa noite pai,\r\n\r\nteste da aplicação.\r\n\r\nAvise-me se recebeu.', '2019-11-27 23:10:15'),
(62, 35, 6, ' Teste', '2019-11-27 23:17:35'),
(63, 4, 35, ' Teste', '2019-11-27 23:19:26'),
(64, 4, 6, ' Teste com o Jonatan.', '2019-11-28 12:49:42'),
(65, 4, 35, ' Teste Avisos', '2019-11-28 20:35:36'),
(66, 4, 4, ' Teste de aplicação', '2019-11-28 21:08:48'),
(67, 4, 4, ' Teste', '2019-11-28 21:11:38'),
(68, 4, 4, ' Teste', '2019-11-28 21:17:35'),
(69, 4, 4, ' teste', '2019-12-05 22:16:18'),
(70, 4, 4, ' teste', '2019-12-05 22:29:22'),
(71, 4, 4, ' teste', '2019-12-05 22:35:15'),
(72, 4, 4, ' teste', '2019-12-05 22:46:23'),
(73, 4, 4, ' teste1', '2019-12-05 23:08:14');

-- --------------------------------------------------------

--
-- Estrutura da tabela `NOTAS`
--

CREATE TABLE `NOTAS` (
  `ID` int(5) NOT NULL,
  `ID_TURMA` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `NOTA` float UNSIGNED NOT NULL,
  `DATA` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DESCRICAO` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `NOTAS`
--

INSERT INTO `NOTAS` (`ID`, `ID_TURMA`, `ID_PESSOA`, `NOTA`, `DATA`, `DESCRICAO`) VALUES
(18, 21, 26, 9, '2019-11-28 17:23:33', 'Teste 2'),
(19, 21, 30, 9, '2019-11-28 17:23:33', 'Teste 2'),
(20, 21, 26, 9, '2019-11-28 17:24:07', '1212121'),
(21, 21, 30, 9, '2019-11-28 17:24:07', '1212121'),
(22, 21, 26, 10, '2019-11-28 17:24:37', '1212121'),
(23, 21, 30, 10, '2019-11-28 17:24:37', '1212121'),
(24, 21, 26, 10, '2019-11-28 17:31:02', 'Teste 3'),
(25, 21, 30, 10, '2019-11-28 17:31:02', 'Teste 3'),
(26, 21, 26, 10, '2019-11-28 17:32:50', '1212121'),
(27, 21, 30, 10, '2019-11-28 17:32:50', '1212121'),
(28, 21, 26, 10, '2019-11-28 17:33:31', '78787'),
(29, 21, 30, 10, '2019-11-28 17:33:31', '78787'),
(30, 21, 26, 10, '2019-11-28 17:35:49', '1212121'),
(31, 21, 30, 10, '2019-11-28 17:35:49', '1212121'),
(32, 21, 26, 10, '2019-11-28 18:12:54', '1212121'),
(33, 21, 30, 10, '2019-11-28 18:12:54', '1212121'),
(34, 22, 30, 5, '2019-11-28 21:19:23', 'Assunto unidade 1'),
(35, 22, 28, 10, '2019-11-28 21:19:23', 'Assunto unidade 1'),
(36, 22, 6, 9, '2019-11-28 21:19:23', 'Assunto unidade 1'),
(37, 22, 22, 8.5, '2019-11-28 21:19:23', 'Assunto unidade 1'),
(38, 23, 49, 2, '2019-12-05 22:18:08', 'Teste 2'),
(39, 23, 28, 5, '2019-12-05 22:18:08', 'Teste 2'),
(40, 23, 44, 8, '2019-12-05 22:18:08', 'Teste 2'),
(41, 23, 29, 0, '2019-12-05 22:18:08', 'Teste 2'),
(42, 23, 20, 9, '2019-12-05 22:18:08', 'Teste 2'),
(43, 21, 30, 1, '2019-12-05 22:20:40', 'Teste 2'),
(44, 21, 30, 2, '2019-12-05 22:40:21', 'Teste 2'),
(45, 22, 30, 1, '2019-12-05 22:45:50', 'Teste 2'),
(46, 22, 28, 2, '2019-12-05 22:45:50', 'Teste 2'),
(47, 22, 6, 4, '2019-12-05 22:45:50', 'Teste 2'),
(48, 22, 22, 8, '2019-12-05 22:45:50', 'Teste 2'),
(49, 21, 30, 4, '2019-12-05 23:10:17', 'Teste 3');

-- --------------------------------------------------------

--
-- Estrutura da tabela `OCORRENCIA`
--

CREATE TABLE `OCORRENCIA` (
  `ID` int(20) NOT NULL,
  `ID_PESSOA_ALUNO` int(15) NOT NULL,
  `ID_PESSOA_AUTOR` int(15) NOT NULL,
  `DATA` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DESCRICAO` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `OCORRENCIA`
--

INSERT INTO `OCORRENCIA` (`ID`, `ID_PESSOA_ALUNO`, `ID_PESSOA_AUTOR`, `DATA`, `DESCRICAO`) VALUES
(1, 6, 4, '2019-11-28 07:12:29', 'Teste de ocorrência!'),
(2, 6, 4, '2019-11-28 07:13:41', 'teste'),
(3, 6, 4, '2019-11-28 07:14:21', 'sdsdsdsdsds'),
(4, 6, 4, '2019-11-28 07:14:31', 'sdsdsdsdsds'),
(5, 6, 4, '2019-11-28 07:14:59', 'teste'),
(6, 6, 4, '2019-11-28 07:15:09', 'teste'),
(7, 6, 4, '2019-11-28 07:17:40', 'teste'),
(8, 6, 4, '2019-11-28 07:17:48', 'Teste!'),
(9, 6, 4, '2019-11-28 07:18:53', 'sdsdsdsds'),
(10, 6, 4, '2019-11-28 07:20:25', 'Ocorrência!!!'),
(11, 6, 4, '2019-11-28 07:20:47', 'Ocorrência!'),
(12, 32, 4, '2019-11-28 20:31:19', 'Teste de ocorrência '),
(13, 6, 4, '2019-11-28 21:19:59', 'Teste');

-- --------------------------------------------------------

--
-- Estrutura da tabela `PESSOA`
--

CREATE TABLE `PESSOA` (
  `ID` int(15) NOT NULL,
  `NOME` varchar(250) COLLATE utf8_bin NOT NULL,
  `SOBRENOME` varchar(250) COLLATE utf8_bin NOT NULL,
  `EMAIL` varchar(250) COLLATE utf8_bin NOT NULL,
  `TIPO_PESSOA` int(1) NOT NULL,
  `SENHA` varchar(300) COLLATE utf8_bin NOT NULL,
  `DATA_NASCIMENTO` date NOT NULL,
  `TIPO_SEXO` smallint(6) NOT NULL,
  `CPF` varchar(11) COLLATE utf8_bin DEFAULT NULL,
  `TELEFONE` varchar(13) COLLATE utf8_bin DEFAULT NULL,
  `NOME_RESP1` varchar(250) COLLATE utf8_bin NOT NULL,
  `NOME_RESP2` varchar(250) COLLATE utf8_bin NOT NULL,
  `SOBRENOME_RESP1` varchar(250) COLLATE utf8_bin NOT NULL,
  `SOBRENOME_RESP2` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `PESSOA`
--

INSERT INTO `PESSOA` (`ID`, `NOME`, `SOBRENOME`, `EMAIL`, `TIPO_PESSOA`, `SENHA`, `DATA_NASCIMENTO`, `TIPO_SEXO`, `CPF`, `TELEFONE`, `NOME_RESP1`, `NOME_RESP2`, `SOBRENOME_RESP1`, `SOBRENOME_RESP2`) VALUES
(4, 'Rebeca', 'Spiess', 'spiess.rebeca@gmail.com', 2, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1996-10-04', 0, NULL, NULL, '', '', '', ''),
(6, 'Luiz', 'Glasenapp', 'luizglasenapp@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(20, 'Sara', 'Spiess', 'luizglasenapp2@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(22, 'Maicon', 'Mayer', 'luizglasenapp3@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(25, 'Marcides', 'Glasenapp', 'luizglasenapp0@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(26, 'Doraci Bahr', 'Glasenapp', 'luizglasenapp6@gmail.com', 4, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(28, 'Lindo', 'Glasenapp', 'luizglasenapp8@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(29, 'Renilda', 'Bahr', 'luizglasenapp23@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(30, 'Invald', 'Bahr', 'luizglasenapp19@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(32, 'Renata', 'Beckert', 'luizglasenapp81@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(34, 'Viviane', 'Spiess', 'viviane.spiess@gmail.com', 1, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1966-03-26', 0, NULL, NULL, '', '', '', ''),
(35, 'Charles', 'Spiess', 'spiesscharles@gmail.com', 2, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1963-02-07', 0, NULL, NULL, '', '', '', ''),
(38, 'Luiz Glasenapp', 'Spiess Glasenapp', 'luizglasenapp987@gmail.com', 2, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '2019-10-16', 1, NULL, NULL, '', '', '', ''),
(39, 'Luiz Glasenapp', 'Spiess Glasenapp6', 'luizglasenapp985698@gmail.com', 2, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, '08857242919', '1111111111', '', '', '', ''),
(40, 'Luiz', 'Glasenapp4', 'luizglasenapp200@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(42, 'Luiz', 'Glasenapp', 'luizglasenapp6589@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 1, NULL, NULL, '', '', '', ''),
(43, 'Luiz Glasenapp', 'Luiz Glasenapp', 'sdsds@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 0, NULL, NULL, '', '', '', ''),
(44, 'Martha', 'Gaulke', 'marthagaulke@exemplo.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1996-09-12', 0, NULL, NULL, '', '', '', ''),
(46, 'Breno', 'Soares', 'brenosoares@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1999-01-01', 1, NULL, NULL, '', '', '', ''),
(48, 'Leticia', 'Krueger', 'viviane.spiess10@gmail.com', 2, '0803d7f36cfcbfcd22beb2e5dc7b0cba15849c65ca300a63115a142fb7b2723e99fc014c194ca80696601fc322716352c8faf8d5d8482b1449bfa3fb888929cf', '1996-09-16', 0, '090.822.059', '(47) 9648-955', '', '', '', ''),
(49, 'Bruno', 'Souza', 'brunosouza@exemplo.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1999-12-15', 1, NULL, NULL, '', '', '', ''),
(50, 'Maria', 'Bonita', 'mariabonita@exemplo.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1999-12-12', 0, NULL, NULL, '', '', '', ''),
(51, 'Fernanda', 'Amanda', 'fernandamanda@exemplo.com', 2, '0803d7f36cfcbfcd22beb2e5dc7b0cba15849c65ca300a63115a142fb7b2723e99fc014c194ca80696601fc322716352c8faf8d5d8482b1449bfa3fb888929cf', '1986-12-15', 0, '08857242919', '1111111111', '', '', '', ''),
(52, 'Luiz Glasenapp23', 'Spiess Glasenapp23', 'luizglasenapp98@gmail.com', 2, '0803d7f36cfcbfcd22beb2e5dc7b0cba15849c65ca300a63115a142fb7b2723e99fc014c194ca80696601fc322716352c8faf8d5d8482b1449bfa3fb888929cf', '1994-09-16', 1, '111111.1111', '1111111111', '', '', '', ''),
(54, 'Luiz Glasenapp', 'Spiess Glasenapp', 'luizglasenapp23232@gmail.com', 1, '0803d7f36cfcbfcd22beb2e5dc7b0cba15849c65ca300a63115a142fb7b2723e99fc014c194ca80696601fc322716352c8faf8d5d8482b1449bfa3fb888929cf', '1194-09-16', 1, '088.572.429', '1111111111', '', '', '', ''),
(56, 'Luiz Glasenapp', 'Spiess Glasenapp', 'lg@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1994-09-16', 0, NULL, NULL, '', '', '', ''),
(57, 'Charles', 'Spiess', 'charles.spiess@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1963-02-07', 1, NULL, NULL, '', '', '', ''),
(58, 'Mario', 'Spiess', 'mario.spiess@exemplo.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1996-12-15', 1, NULL, NULL, '', '', '', ''),
(59, 'Maria', 'Schmitid', 'marias@exemplo.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1989-12-12', 0, NULL, NULL, '', '', '', ''),
(61, 'Luiz Glasenapp', 'Spiess', 'luizglasenapp99@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1998-12-12', 1, NULL, NULL, '', '', '', ''),
(62, 'Luiz Glasenapp', 'Spiess', 'spiess.rebeca@gmail12.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1989-12-12', 1, NULL, NULL, '', '', '', ''),
(63, 'Rebeca Spiess', 'Spiess Glasenapp', 'spiess.rebeca99@gmail.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1989-12-12', 0, NULL, NULL, '', '', '', ''),
(65, 'teste', 'teste', 'emailteste@teste.com', 3, '88cfd378fb1b9b0f88b382c54c9f1fd2d666a1586be1f85423b270af2b9f436b112833d0fda4511de902439a512f979f0a42b49903fb7e0a684bbb2badaeafd6', '1996-05-15', 1, NULL, NULL, '', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `PLANO_AULA`
--

CREATE TABLE `PLANO_AULA` (
  `ID` int(11) NOT NULL,
  `ID_TURMA` int(15) NOT NULL,
  `DESCRICAO` varchar(250) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `PLANO_AULA`
--

INSERT INTO `PLANO_AULA` (`ID`, `ID_TURMA`, `DESCRICAO`) VALUES
(1, 21, 'Teste 123654789'),
(2, 22, 'Teste plano de aula');

-- --------------------------------------------------------

--
-- Stand-in structure for view `RELATORIO_TURMA`
-- (See below for the actual view)
--
CREATE TABLE `RELATORIO_TURMA` (
`TURMA` varchar(225)
,`MATERIA` varchar(255)
,`PROFESSOR` varchar(501)
,`NOME` varchar(250)
,`SOBRENOME` varchar(250)
,`EMAIL` varchar(250)
,`DATA_NASCIMENTO` date
,`SEXO` varchar(50)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `SEXO`
--

CREATE TABLE `SEXO` (
  `ID` smallint(6) NOT NULL,
  `SEXO` varchar(50) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `SEXO`
--

INSERT INTO `SEXO` (`ID`, `SEXO`) VALUES
(0, 'Feminino'),
(1, 'Masculino');

-- --------------------------------------------------------

--
-- Estrutura da tabela `TIPO_PESSOA`
--

CREATE TABLE `TIPO_PESSOA` (
  `ID` int(1) NOT NULL,
  `NOME` varchar(50) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `TIPO_PESSOA`
--

INSERT INTO `TIPO_PESSOA` (`ID`, `NOME`) VALUES
(3, 'Aluno(a)'),
(2, 'Diretor(a)'),
(1, 'Professor(a)'),
(4, 'Secretário(a)');

-- --------------------------------------------------------

--
-- Estrutura da tabela `TURMA`
--

CREATE TABLE `TURMA` (
  `ID` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `ID_MATERIA` int(5) NOT NULL,
  `NOME_TURMA` varchar(225) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Extraindo dados da tabela `TURMA`
--

INSERT INTO `TURMA` (`ID`, `ID_PESSOA`, `ID_MATERIA`, `NOME_TURMA`) VALUES
(21, 6, 2, 'Classe 1'),
(22, 34, 1, 'Turma 1A'),
(23, 34, 6, 'Turma 2');

-- --------------------------------------------------------

--
-- Estrutura da tabela `TURMA_PESSOA`
--

CREATE TABLE `TURMA_PESSOA` (
  `ID_TURMA` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `TURMA_PESSOA`
--

INSERT INTO `TURMA_PESSOA` (`ID_TURMA`, `ID_PESSOA`) VALUES
(22, 6),
(23, 20),
(22, 22),
(21, 26),
(22, 26),
(22, 28),
(23, 28),
(23, 29),
(21, 30),
(22, 30),
(23, 44),
(23, 49);

-- --------------------------------------------------------

--
-- Structure for view `RELATORIO_TURMA`
--
DROP TABLE IF EXISTS `RELATORIO_TURMA`;

CREATE ALGORITHM=UNDEFINED DEFINER=`shcglobal`@`%%` SQL SECURITY DEFINER VIEW `RELATORIO_TURMA`  AS  select `TU`.`NOME_TURMA` AS `TURMA`,`MA`.`NOME` AS `MATERIA`,concat(concat(`PE_PROFESSOR`.`NOME`,' '),`PE_PROFESSOR`.`SOBRENOME`) AS `PROFESSOR`,`PE`.`NOME` AS `NOME`,`PE`.`SOBRENOME` AS `SOBRENOME`,`PE`.`EMAIL` AS `EMAIL`,`PE`.`DATA_NASCIMENTO` AS `DATA_NASCIMENTO`,`SEX`.`SEXO` AS `SEXO` from (((((`TURMA` `TU` join `TURMA_PESSOA` `TUP` on((`TU`.`ID` = `TUP`.`ID_TURMA`))) join `PESSOA` `PE` on((`PE`.`ID` = `TU`.`ID_PESSOA`))) join `PESSOA` `PE_PROFESSOR` on((`TU`.`ID_PESSOA` = `PE_PROFESSOR`.`ID`))) join `SEXO` `SEX` on((`SEX`.`ID` = `PE`.`TIPO_SEXO`))) join `MATERIA` `MA` on((`MA`.`ID` = `TU`.`ID`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `FREQUENCIA`
--
ALTER TABLE `FREQUENCIA`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_PESSOA_FREQUENCIA` (`ID_PESSOA`),
  ADD KEY `FK_TURMA_FREQUENCIA` (`ID_TURMA`);

--
-- Indexes for table `MATERIA`
--
ALTER TABLE `MATERIA`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `NOME_UNICO` (`NOME`);

--
-- Indexes for table `MENSAGEM`
--
ALTER TABLE `MENSAGEM`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_REMETENTE` (`REMETENTE`),
  ADD KEY `FK_DESTINATARIO` (`DESTINATARIO`);

--
-- Indexes for table `NOTAS`
--
ALTER TABLE `NOTAS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_PESSOA_NOTAS` (`ID_PESSOA`),
  ADD KEY `FK_NOTAS_TURMA` (`ID_TURMA`);

--
-- Indexes for table `OCORRENCIA`
--
ALTER TABLE `OCORRENCIA`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_PESSOA_ALUNO_OCORRENCIA` (`ID_PESSOA_ALUNO`),
  ADD KEY `FK_PESSOA_AUTOR_OCORRENCIA` (`ID_PESSOA_AUTOR`);

--
-- Indexes for table `PESSOA`
--
ALTER TABLE `PESSOA`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `EMAIL_UNIQUE` (`EMAIL`),
  ADD KEY `FK_TIPO_PESSOA` (`TIPO_PESSOA`),
  ADD KEY `FK_TIPO_SEXO` (`TIPO_SEXO`);

--
-- Indexes for table `PLANO_AULA`
--
ALTER TABLE `PLANO_AULA`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_TURMAS` (`ID_TURMA`);

--
-- Indexes for table `SEXO`
--
ALTER TABLE `SEXO`
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
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `NOME_TURMA_UNIQUE` (`NOME_TURMA`),
  ADD KEY `FK_PESSOA` (`ID_PESSOA`),
  ADD KEY `FK_MATERIA` (`ID_MATERIA`);

--
-- Indexes for table `TURMA_PESSOA`
--
ALTER TABLE `TURMA_PESSOA`
  ADD PRIMARY KEY (`ID_TURMA`,`ID_PESSOA`),
  ADD KEY `FK_PESSOAS` (`ID_PESSOA`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `FREQUENCIA`
--
ALTER TABLE `FREQUENCIA`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `MATERIA`
--
ALTER TABLE `MATERIA`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `MENSAGEM`
--
ALTER TABLE `MENSAGEM`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `NOTAS`
--
ALTER TABLE `NOTAS`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `OCORRENCIA`
--
ALTER TABLE `OCORRENCIA`
  MODIFY `ID` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `PESSOA`
--
ALTER TABLE `PESSOA`
  MODIFY `ID` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `PLANO_AULA`
--
ALTER TABLE `PLANO_AULA`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `TIPO_PESSOA`
--
ALTER TABLE `TIPO_PESSOA`
  MODIFY `ID` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `TURMA`
--
ALTER TABLE `TURMA`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `FREQUENCIA`
--
ALTER TABLE `FREQUENCIA`
  ADD CONSTRAINT `FK_PESSOA_FREQUENCIA` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`),
  ADD CONSTRAINT `FK_TURMA_FREQUENCIA` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`);

--
-- Limitadores para a tabela `MENSAGEM`
--
ALTER TABLE `MENSAGEM`
  ADD CONSTRAINT `FK_DESTINATARIO` FOREIGN KEY (`DESTINATARIO`) REFERENCES `PESSOA` (`ID`),
  ADD CONSTRAINT `FK_REMETENTE` FOREIGN KEY (`REMETENTE`) REFERENCES `PESSOA` (`ID`);

--
-- Limitadores para a tabela `NOTAS`
--
ALTER TABLE `NOTAS`
  ADD CONSTRAINT `FK_NOTAS_TURMA` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`),
  ADD CONSTRAINT `FK_PESSOA_NOTAS` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`);

--
-- Limitadores para a tabela `OCORRENCIA`
--
ALTER TABLE `OCORRENCIA`
  ADD CONSTRAINT `FK_PESSOA_ALUNO_OCORRENCIA` FOREIGN KEY (`ID_PESSOA_ALUNO`) REFERENCES `PESSOA` (`ID`),
  ADD CONSTRAINT `FK_PESSOA_AUTOR_OCORRENCIA` FOREIGN KEY (`ID_PESSOA_AUTOR`) REFERENCES `PESSOA` (`ID`);

--
-- Limitadores para a tabela `PESSOA`
--
ALTER TABLE `PESSOA`
  ADD CONSTRAINT `FK_TIPO_PESSOA` FOREIGN KEY (`TIPO_PESSOA`) REFERENCES `TIPO_PESSOA` (`ID`),
  ADD CONSTRAINT `FK_TIPO_SEXO` FOREIGN KEY (`TIPO_SEXO`) REFERENCES `SEXO` (`ID`);

--
-- Limitadores para a tabela `PLANO_AULA`
--
ALTER TABLE `PLANO_AULA`
  ADD CONSTRAINT `FK_TURMAS` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`);

--
-- Limitadores para a tabela `TURMA`
--
ALTER TABLE `TURMA`
  ADD CONSTRAINT `FK_MATERIA` FOREIGN KEY (`ID_MATERIA`) REFERENCES `MATERIA` (`ID`),
  ADD CONSTRAINT `FK_PESSOA` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`);

--
-- Limitadores para a tabela `TURMA_PESSOA`
--
ALTER TABLE `TURMA_PESSOA`
  ADD CONSTRAINT `FK_PESSOAS` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`),
  ADD CONSTRAINT `FK_TURMA` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
