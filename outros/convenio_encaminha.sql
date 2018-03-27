# Host: localhost  (Version: 5.5.24-log)
# Date: 2015-07-06 10:51:19
# Generator: MySQL-Front 5.3  (Build 2.53)

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE */;
/*!40101 SET SQL_MODE='' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES */;
/*!40103 SET SQL_NOTES='ON' */;

#
# Source for table "convenio_encaminha"
#

CREATE TABLE `convenio_encaminha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_guia` varchar(14) DEFAULT NULL,
  `data_guia` date DEFAULT NULL,
  `titular` int(11) DEFAULT NULL,
  `dependente` int(11) DEFAULT NULL,
  `tipo_especialidade` varchar(20) DEFAULT NULL,
  `especialidade` int(11) DEFAULT NULL,
  `descricao` varchar(60) DEFAULT NULL,
  `profissional` int(11) DEFAULT NULL,
  `data_agenda` date DEFAULT NULL,
  `hora_agenda` time DEFAULT NULL,
  `origem` int(11) DEFAULT NULL,
  `destino` int(11) DEFAULT NULL,
  `motivo` int(11) DEFAULT NULL,
  `observacao` blob,
  `data_atende` date DEFAULT NULL,
  `hora_atende` time DEFAULT NULL,
  `status_atende` int(11) DEFAULT NULL,
  `usuario_atende` varchar(20) DEFAULT NULL,
  `data` datetime DEFAULT NULL,
  `usuario_login` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='convenio_encaminha';

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
