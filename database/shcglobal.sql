CREATE DATABASE  IF NOT EXISTS `shcglobal` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci */;
USE `shcglobal`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: 186.202.152.73    Database: shcglobal
-- ------------------------------------------------------
-- Server version	5.6.40-84.0-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `FREQUENCIA`
--

DROP TABLE IF EXISTS `FREQUENCIA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FREQUENCIA` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `ID_PESSOA` int(15) NOT NULL,
  `DATA` date NOT NULL,
  `PRESENCA` tinyint(1) NOT NULL,
  `ID_TURMA` int(10) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_PESSOA_FREQUENCIA` (`ID_PESSOA`),
  KEY `FK_TURMA_FREQUENCIA` (`ID_TURMA`),
  CONSTRAINT `FK_PESSOA_FREQUENCIA` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`),
  CONSTRAINT `FK_TURMA_FREQUENCIA` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MATERIA`
--

DROP TABLE IF EXISTS `MATERIA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MATERIA` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `NOME_UNICO` (`NOME`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MENSAGEM`
--

DROP TABLE IF EXISTS `MENSAGEM`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MENSAGEM` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `REMETENTE` int(15) DEFAULT NULL,
  `DESTINATARIO` int(15) DEFAULT NULL,
  `AVISO` varchar(250) COLLATE latin1_general_ci DEFAULT NULL,
  `DATA_HORA_AVISO` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `FK_REMETENTE` (`REMETENTE`),
  KEY `FK_DESTINATARIO` (`DESTINATARIO`),
  CONSTRAINT `FK_DESTINATARIO` FOREIGN KEY (`DESTINATARIO`) REFERENCES `PESSOA` (`ID`),
  CONSTRAINT `FK_REMETENTE` FOREIGN KEY (`REMETENTE`) REFERENCES `PESSOA` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `NOTAS`
--

DROP TABLE IF EXISTS `NOTAS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NOTAS` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `ID_TURMA` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `NOTA` float unsigned NOT NULL,
  `DATA` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DESCRICAO` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_PESSOA_NOTAS` (`ID_PESSOA`),
  KEY `FK_NOTAS_TURMA` (`ID_TURMA`),
  CONSTRAINT `FK_NOTAS_TURMA` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`),
  CONSTRAINT `FK_PESSOA_NOTAS` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OCORRENCIA`
--

DROP TABLE IF EXISTS `OCORRENCIA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OCORRENCIA` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `ID_PESSOA_ALUNO` int(15) NOT NULL,
  `ID_PESSOA_AUTOR` int(15) NOT NULL,
  `DATA` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DESCRICAO` varchar(250) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_PESSOA_ALUNO_OCORRENCIA` (`ID_PESSOA_ALUNO`),
  KEY `FK_PESSOA_AUTOR_OCORRENCIA` (`ID_PESSOA_AUTOR`),
  CONSTRAINT `FK_PESSOA_ALUNO_OCORRENCIA` FOREIGN KEY (`ID_PESSOA_ALUNO`) REFERENCES `PESSOA` (`ID`),
  CONSTRAINT `FK_PESSOA_AUTOR_OCORRENCIA` FOREIGN KEY (`ID_PESSOA_AUTOR`) REFERENCES `PESSOA` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PESSOA`
--

DROP TABLE IF EXISTS `PESSOA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PESSOA` (
  `ID` int(15) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(250) COLLATE utf8_bin NOT NULL,
  `SOBRENOME` varchar(250) COLLATE utf8_bin NOT NULL,
  `EMAIL` varchar(250) COLLATE utf8_bin NOT NULL,
  `TIPO_PESSOA` int(1) NOT NULL,
  `SENHA` varchar(300) COLLATE utf8_bin NOT NULL,
  `DATA_NASCIMENTO` date NOT NULL,
  `TIPO_SEXO` smallint(6) NOT NULL,
  `CPF` varchar(11) COLLATE utf8_bin DEFAULT NULL,
  `TELEFONE` varchar(13) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `EMAIL_UNIQUE` (`EMAIL`),
  KEY `FK_TIPO_PESSOA` (`TIPO_PESSOA`),
  KEY `FK_TIPO_SEXO` (`TIPO_SEXO`),
  CONSTRAINT `FK_TIPO_PESSOA` FOREIGN KEY (`TIPO_PESSOA`) REFERENCES `TIPO_PESSOA` (`ID`),
  CONSTRAINT `FK_TIPO_SEXO` FOREIGN KEY (`TIPO_SEXO`) REFERENCES `SEXO` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PLANO_AULA`
--

DROP TABLE IF EXISTS `PLANO_AULA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PLANO_AULA` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_TURMA` int(15) NOT NULL,
  `DESCRICAO` varchar(250) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_TURMAS` (`ID_TURMA`),
  CONSTRAINT `FK_TURMAS` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary view structure for view `RELATORIO_TURMA`
--

DROP TABLE IF EXISTS `RELATORIO_TURMA`;
/*!50001 DROP VIEW IF EXISTS `RELATORIO_TURMA`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `RELATORIO_TURMA` AS SELECT 
 1 AS `TURMA`,
 1 AS `MATERIA`,
 1 AS `PROFESSOR`,
 1 AS `NOME`,
 1 AS `SOBRENOME`,
 1 AS `EMAIL`,
 1 AS `DATA_NASCIMENTO`,
 1 AS `SEXO`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `SEXO`
--

DROP TABLE IF EXISTS `SEXO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SEXO` (
  `ID` smallint(6) NOT NULL,
  `SEXO` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TIPO_PESSOA`
--

DROP TABLE IF EXISTS `TIPO_PESSOA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TIPO_PESSOA` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(50) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `U_NOME_TIPO` (`NOME`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TURMA`
--

DROP TABLE IF EXISTS `TURMA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TURMA` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `ID_PESSOA` int(15) NOT NULL,
  `ID_MATERIA` int(5) NOT NULL,
  `NOME_TURMA` varchar(225) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `NOME_TURMA_UNIQUE` (`NOME_TURMA`),
  KEY `FK_PESSOA` (`ID_PESSOA`),
  KEY `FK_MATERIA` (`ID_MATERIA`),
  CONSTRAINT `FK_MATERIA` FOREIGN KEY (`ID_MATERIA`) REFERENCES `MATERIA` (`ID`),
  CONSTRAINT `FK_PESSOA` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TURMA_PESSOA`
--

DROP TABLE IF EXISTS `TURMA_PESSOA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TURMA_PESSOA` (
  `ID_TURMA` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  PRIMARY KEY (`ID_TURMA`,`ID_PESSOA`),
  KEY `FK_PESSOAS` (`ID_PESSOA`),
  CONSTRAINT `FK_PESSOAS` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`),
  CONSTRAINT `FK_TURMA` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'shcglobal'
--

--
-- Final view structure for view `RELATORIO_TURMA`
--

/*!50001 DROP VIEW IF EXISTS `RELATORIO_TURMA`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`shcglobal`@`%%` SQL SECURITY DEFINER */
/*!50001 VIEW `RELATORIO_TURMA` AS select `TU`.`NOME_TURMA` AS `TURMA`,`MA`.`NOME` AS `MATERIA`,concat(concat(`PE_PROFESSOR`.`NOME`,' '),`PE_PROFESSOR`.`SOBRENOME`) AS `PROFESSOR`,`PE`.`NOME` AS `NOME`,`PE`.`SOBRENOME` AS `SOBRENOME`,`PE`.`EMAIL` AS `EMAIL`,`PE`.`DATA_NASCIMENTO` AS `DATA_NASCIMENTO`,`SEX`.`SEXO` AS `SEXO` from (((((`TURMA` `TU` join `TURMA_PESSOA` `TUP` on((`TU`.`ID` = `TUP`.`ID_TURMA`))) join `PESSOA` `PE` on((`PE`.`ID` = `TU`.`ID_PESSOA`))) join `PESSOA` `PE_PROFESSOR` on((`TU`.`ID_PESSOA` = `PE_PROFESSOR`.`ID`))) join `SEXO` `SEX` on((`SEX`.`ID` = `PE`.`TIPO_SEXO`))) join `MATERIA` `MA` on((`MA`.`ID` = `TU`.`ID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-11-28 16:57:34
