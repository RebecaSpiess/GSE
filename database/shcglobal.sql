-- --------------------------------------------------------
-- Servidor:                     shcglobal.mysql.dbaas.com.br
-- Versão do servidor:           5.6.40-84.0-log - Percona Server (GPL), Release 84.0, Revision 47234b3
-- OS do Servidor:               debian-linux-gnu
-- HeidiSQL Versão:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Copiando estrutura do banco de dados para shcglobal
CREATE DATABASE IF NOT EXISTS `shcglobal` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci */;
USE `shcglobal`;

-- Copiando estrutura para tabela shcglobal.FREQUENCIA
CREATE TABLE IF NOT EXISTS `FREQUENCIA` (
  `ID` int(20) NOT NULL AUTO_INCREMENT,
  `ID_PESSOA` int(15) NOT NULL,
  `DATA` date NOT NULL,
  `PRESENCA` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_PESSOA_FREQUENCIA` (`ID_PESSOA`),
  CONSTRAINT `FK_PESSOA_FREQUENCIA` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela shcglobal.MATERIA
CREATE TABLE IF NOT EXISTS `MATERIA` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela shcglobal.NOTAS
CREATE TABLE IF NOT EXISTS `NOTAS` (
  `ID` int(5) NOT NULL AUTO_INCREMENT,
  `ID_TURMA` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `NOTA` int(10) unsigned NOT NULL,
  `DATA` date NOT NULL,
  `DESCRICAO` varchar(255) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_TURMA_NOTAS` (`ID_TURMA`),
  KEY `FK_PESSOA_NOTAS` (`ID_PESSOA`),
  CONSTRAINT `FK_PESSOA_NOTAS` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`),
  CONSTRAINT `FK_TURMA_NOTAS` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela shcglobal.OCORRENCIA
CREATE TABLE IF NOT EXISTS `OCORRENCIA` (
  `ID` int(20) NOT NULL,
  `ID_PESSOA_ALUNO` int(15) NOT NULL,
  `ID_PESSOA_AUTOR` int(15) NOT NULL,
  `DATA` date NOT NULL,
  `DESCRICAO` varchar(250) COLLATE latin1_general_ci NOT NULL,
  KEY `FK_PESSOA_ALUNO_OCORRENCIA` (`ID_PESSOA_ALUNO`),
  KEY `FK_PESSOA_AUTOR_OCORRENCIA` (`ID_PESSOA_AUTOR`),
  CONSTRAINT `FK_PESSOA_ALUNO_OCORRENCIA` FOREIGN KEY (`ID_PESSOA_ALUNO`) REFERENCES `PESSOA` (`ID`),
  CONSTRAINT `FK_PESSOA_AUTOR_OCORRENCIA` FOREIGN KEY (`ID_PESSOA_AUTOR`) REFERENCES `PESSOA` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela shcglobal.PESSOA
CREATE TABLE IF NOT EXISTS `PESSOA` (
  `ID` int(15) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `SOBRENOME` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `E-MAIL` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `TIPO_PESSOA` int(1) NOT NULL,
  `SENHA` varchar(250) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_TIPO_PESSOA` (`TIPO_PESSOA`),
  CONSTRAINT `FK_TIPO_PESSOA` FOREIGN KEY (`TIPO_PESSOA`) REFERENCES `TIPO_PESSOA` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela shcglobal.PLANO_AULA
CREATE TABLE IF NOT EXISTS `PLANO_AULA` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_TURMA` int(15) NOT NULL,
  `ID_PESSOA` int(10) NOT NULL,
  `DESCRICAO` varchar(250) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_TURMA_PLANO_AULA` (`ID_TURMA`),
  KEY `FK_PESSOA_PLANO_AULA` (`ID_PESSOA`),
  CONSTRAINT `FK_PESSOA_PLANO_AULA` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`),
  CONSTRAINT `FK_TURMA_PLANO_AULA` FOREIGN KEY (`ID_TURMA`) REFERENCES `TURMA` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela shcglobal.TIPO_PESSOA
CREATE TABLE IF NOT EXISTS `TIPO_PESSOA` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `NOME` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `U_NOME_TIPO` (`NOME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela shcglobal.TURMA
CREATE TABLE IF NOT EXISTS `TURMA` (
  `ID` int(10) NOT NULL,
  `ID_PESSOA` int(15) NOT NULL,
  `ID_MATERIA` int(5) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_PESSOA` (`ID_PESSOA`),
  KEY `FK_MATERIA` (`ID_MATERIA`),
  CONSTRAINT `FK_MATERIA` FOREIGN KEY (`ID_MATERIA`) REFERENCES `MATERIA` (`ID`),
  CONSTRAINT `FK_PESSOA` FOREIGN KEY (`ID_PESSOA`) REFERENCES `PESSOA` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- Exportação de dados foi desmarcado.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
