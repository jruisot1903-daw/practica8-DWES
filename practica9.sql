-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         12.1.2-MariaDB - MariaDB Server
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para practica9
CREATE DATABASE IF NOT EXISTS `practica9` /*!40100 DEFAULT CHARACTER SET utf8mb3 COLLATE utf8mb3_spanish2_ci */;
USE `practica9`;

-- Volcando estructura para tabla practica9.acl_roles
CREATE TABLE IF NOT EXISTS `acl_roles` (
  `cod_acl_role` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `perm1` tinyint(1) NOT NULL DEFAULT 0,
  `perm2` tinyint(1) NOT NULL DEFAULT 0,
  `perm3` tinyint(1) NOT NULL DEFAULT 0,
  `perm4` tinyint(1) NOT NULL DEFAULT 0,
  `perm5` tinyint(1) NOT NULL DEFAULT 0,
  `perm6` tinyint(1) NOT NULL DEFAULT 0,
  `perm7` tinyint(1) NOT NULL DEFAULT 0,
  `perm8` tinyint(1) NOT NULL DEFAULT 0,
  `perm9` tinyint(1) NOT NULL DEFAULT 0,
  `perm10` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`cod_acl_role`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla practica9.acl_roles: ~3 rows (aproximadamente)
INSERT INTO `acl_roles` (`cod_acl_role`, `nombre`, `perm1`, `perm2`, `perm3`, `perm4`, `perm5`, `perm6`, `perm7`, `perm8`, `perm9`, `perm10`) VALUES
	(1, 'normales', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
	(2, 'administradores', 1, 1, 0, 0, 0, 0, 0, 0, 0, 0),
	(3, 'superadmin', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0);

-- Volcando estructura para tabla practica9.acl_usuarios
CREATE TABLE IF NOT EXISTS `acl_usuarios` (
  `cod_acl_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `contrasenia` varchar(64) NOT NULL,
  `cod_acl_role` int(11) NOT NULL,
  `borrado` tinyint(1) NOT NULL,
  PRIMARY KEY (`cod_acl_usuario`),
  UNIQUE KEY `uq_acl_roles_1` (`nick`),
  KEY `cod_acl_role` (`cod_acl_role`),
  CONSTRAINT `fk_acl_roles_1` FOREIGN KEY (`cod_acl_role`) REFERENCES `acl_roles` (`cod_acl_role`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci;

-- Volcando datos para la tabla practica9.acl_usuarios: ~1 rows (aproximadamente)
INSERT INTO `acl_usuarios` (`cod_acl_usuario`, `nick`, `nombre`, `contrasenia`, `cod_acl_role`, `borrado`) VALUES
	(1, 'Javirs', 'javi', 'Usuario1234', 3, 0);

-- Volcando estructura para tabla practica9.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `cod_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) NOT NULL DEFAULT '',
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `nif` varchar(10) NOT NULL DEFAULT '',
  `direccion` varchar(50) NOT NULL,
  `poblacion` varchar(30) NOT NULL DEFAULT '',
  `provincia` varchar(30) NOT NULL DEFAULT 'defecto',
  `CP` varchar(5) NOT NULL DEFAULT '00000',
  `fecha_nacimiento` date NOT NULL,
  `borrado` tinyint(4) NOT NULL DEFAULT 0,
  `foto` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cod_usuario`),
  UNIQUE KEY `nick_unico` (`nick`),
  CONSTRAINT `FK_NICK_ACL` FOREIGN KEY (`nick`) REFERENCES `acl_usuarios` (`nick`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_spanish2_ci COMMENT='tabla donde tendremos a los usuarios del sistema ';

-- Volcando datos para la tabla practica9.usuarios: ~1 rows (aproximadamente)
INSERT INTO `usuarios` (`cod_usuario`, `nick`, `nombre`, `nif`, `direccion`, `poblacion`, `provincia`, `CP`, `fecha_nacimiento`, `borrado`, `foto`) VALUES
	(6, 'Javirs', 'javi', '12345678P', 'Calle Nueva', 'V.Algaidas', 'malaga', '23415', '2004-03-19', 0, '');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
