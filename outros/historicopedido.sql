# Host: localhost  (Version: 5.5.24-log)
# Date: 2015-10-14 08:15:21
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
# Source for table "historicopedido"
#

CREATE TABLE `historicopedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produto` int(11) NOT NULL DEFAULT '0',
  `local` int(11) NOT NULL DEFAULT '0',
  `data_pedido` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `pedido` int(11) DEFAULT NULL,
  `historico` blob,
  `quantidade` decimal(10,0) NOT NULL DEFAULT '0',
  `valorcusto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `percentualcusto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `valorunitario` decimal(10,2) NOT NULL DEFAULT '0.00',
  `usuario_login` varchar(20) NOT NULL DEFAULT '',
  `data` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`produto`,`local`,`data_pedido`),
  KEY `usuario` (`usuario_login`),
  KEY `local` (`local`),
  KEY `produto` (`produto`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='historicomovimento';

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
