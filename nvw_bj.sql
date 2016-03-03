/*
Navicat MySQL Data Transfer

Source Server         : sin_password
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : nvw_bj

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-03-03 09:10:25
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `afiliado`
-- ----------------------------
DROP TABLE IF EXISTS `afiliado`;
CREATE TABLE `afiliado` (
  `id_afiliado` int(10) NOT NULL AUTO_INCREMENT,
  `dociden_afiliado` varchar(12) NOT NULL,
  `apell_afiliado` varchar(100) NOT NULL,
  `nomb_afiliado` varchar(100) NOT NULL,
  `direcc_afiliado` text,
  `email_afiliado` varchar(50) DEFAULT NULL,
  `cel_afiliado` varchar(20) DEFAULT NULL,
  `genero_afiliado` char(2) NOT NULL,
  `fe_afiliado` date NOT NULL,
  `telemerg_afiliado` varchar(20) DEFAULT NULL,
  `talla_afiliado` char(10) DEFAULT NULL,
  `peso_afiliado` varchar(10) DEFAULT NULL,
  `altu_afiliado` float(6,2) DEFAULT NULL,
  `localresdi_afiliado` text COMMENT 'localidad de residencia',
  `telf_fijo_afiliado` varchar(20) DEFAULT NULL COMMENT 'telefono residencial',
  `coleg_afiliado` char(60) DEFAULT NULL COMMENT 'Colegio o Universidad donde  estudia',
  `seguro_afiliado` varchar(60) DEFAULT NULL COMMENT 'seguro afiliado',
  `tiposangre_afiliado` varchar(10) DEFAULT NULL COMMENT 'tipo sangre',
  `contacto_afiliado` varchar(60) DEFAULT NULL COMMENT 'En caso de emergencia llamar a',
  `foto_afiliado` varchar(100) DEFAULT NULL COMMENT 'Imagen IDENTIFICATICA DEL AFILIADO',
  `st_afiliado` tinyint(2) DEFAULT NULL COMMENT 'status 0 inactivo, 1 activo',
  `st_notificado` tinyint(2) DEFAULT NULL COMMENT 'estado de entrada (1 libre, 2 notificacion, 3 por pago, 4 por plan)',
  PRIMARY KEY (`id_afiliado`),
  UNIQUE KEY `ind_dociden_afiliado` (`dociden_afiliado`),
  UNIQUE KEY `ind_cel_afiliado` (`cel_afiliado`),
  UNIQUE KEY `ind_email_afiliado` (`email_afiliado`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='tabla para guardar datos del jugador afiliado al club';

-- ----------------------------
-- Records of afiliado
-- ----------------------------
INSERT INTO `afiliado` VALUES ('10', '1122122122', 'de Arco', 'Juana', 'bogota', 'juanalaiguana@hotmail.com', '32012121', 'F', '1981-03-08', null, null, null, null, null, '(1)3213232', null, null, null, null, null, '1', '1');

-- ----------------------------
-- Table structure for `empleados`
-- ----------------------------
DROP TABLE IF EXISTS `empleados`;
CREATE TABLE `empleados` (
  `id_empleado` int(10) NOT NULL AUTO_INCREMENT,
  `dociden_empleado` varchar(12) DEFAULT NULL COMMENT 'documento de identidad ,cedula, pasaporte, rut',
  `nomb_empleado` varchar(100) NOT NULL COMMENT 'nombres completos',
  `apell_empleado` varchar(100) NOT NULL COMMENT 'apellidos',
  `telf_empleado` varchar(20) DEFAULT NULL COMMENT 'telefono movil o fijo sin prefijo del pais',
  `pass_empleado` varchar(250) DEFAULT NULL,
  `login_empleado` varchar(50) DEFAULT NULL,
  `email_empleado` varchar(100) DEFAULT NULL COMMENT 'correo electronico opcional',
  `st_empleado_p` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Status del empleado 0 Inactivo, 1 Activo, campo modificado por programacion',
  `id_perfil` int(10) NOT NULL,
  PRIMARY KEY (`id_empleado`),
  KEY `ind_id_empleado` (`id_empleado`),
  KEY `ind_id_perfil` (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of empleados
-- ----------------------------
INSERT INTO `empleados` VALUES ('3', '3131214', 'Empleado 3', 'apellido 3', null, '1', 'empleado', null, '1', '2');
INSERT INTO `empleados` VALUES ('4', '15027338', 'eL jeFE', 'rOJO', '04147401218', '1', 'ejecutivo', 'ELJEFE@SOY.YO', '1', '3');

-- ----------------------------
-- Table structure for `historial`
-- ----------------------------
DROP TABLE IF EXISTS `historial`;
CREATE TABLE `historial` (
  `id_historial` int(10) NOT NULL AUTO_INCREMENT,
  `id_afiliado` int(10) NOT NULL,
  `periodo_historial` varchar(10) DEFAULT NULL,
  `team_historial` varchar(100) DEFAULT NULL,
  `torneo_historial` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_historial`),
  KEY `id_afiliado_ind` (`id_afiliado`) USING BTREE,
  CONSTRAINT `id_afiliado_fk1` FOREIGN KEY (`id_afiliado`) REFERENCES `afiliado` (`id_afiliado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of historial
-- ----------------------------
INSERT INTO `historial` VALUES ('1', '10', '2009', 'fasc', '2');

-- ----------------------------
-- Table structure for `matricula`
-- ----------------------------
DROP TABLE IF EXISTS `matricula`;
CREATE TABLE `matricula` (
  `id_matricula` int(10) NOT NULL AUTO_INCREMENT,
  `id_afiliado` int(10) NOT NULL,
  `tipo_matri` tinyint(2) DEFAULT NULL COMMENT 'tipo de matricula nuevo, renovacion, actualizacion',
  `id_plan` int(10) DEFAULT NULL,
  `valor_matri` double(12,2) DEFAULT NULL,
  `valor_men_matri` double(12,2) DEFAULT NULL,
  `conv_matri` varchar(255) DEFAULT NULL COMMENT 'conevio matricula',
  `id_empleado` int(10) DEFAULT NULL COMMENT 'asesor comercial',
  `bol_matri` varchar(100) DEFAULT NULL COMMENT 'enviar boletin a:',
  `cuenta_matri` varchar(100) DEFAULT NULL COMMENT 'enviar estado de cuenta a:',
  `termino1_matri` varchar(10) DEFAULT NULL COMMENT 'Los pagos se deben realizar siempre en los primeros 5 días de cada mes',
  `termino2_matri` varchar(10) DEFAULT NULL COMMENT 'A partir del día 11,  si no está a paz y salvo, no podrá ingresar a entrenamientos, ni jugar partidos de torneo, ni amistosos',
  `termino3_matri` varchar(10) DEFAULT NULL COMMENT 'Si no ha cancelado después de los primeros cinco  (5) días del mes, el pago  tendrá un recargo del 10%',
  `pag_card_matri` tinyint(2) DEFAULT NULL COMMENT 'autorizo apagar con tarjeta credito',
  `tipo_card_matri` char(50) DEFAULT NULL COMMENT 'tipo tarjeta',
  `num_card_matri` varchar(25) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `venc_card_matri` varchar(10) DEFAULT NULL,
  `doc1_matri` char(2) DEFAULT NULL COMMENT 'Fotocopia del documento de identidad ampliada al 150',
  `doc2_matri` char(2) DEFAULT NULL COMMENT '4 Fotos recientes 3x4 con fondo azul',
  `doc3_matri` char(2) DEFAULT NULL COMMENT 'Fotocopia del carnet de EPS, Medicina prepagada o SISBEN',
  `doc4_matri` char(2) DEFAULT NULL COMMENT 'Certificación médica donde conste que es apto para jugar futbol (15 días de plazo para entegarlo)',
  PRIMARY KEY (`id_matricula`),
  KEY `ind_id_afiliado` (`id_afiliado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of matricula
-- ----------------------------
INSERT INTO `matricula` VALUES ('1', '10', '1', '2', '4550.09', '4000.50', '4', '-1', 'keymalerock@yahoo.com', 'keymalerock@yahoo.com', '1', '1', '1', '0', null, null, null, '1', '1', '1', '1');

-- ----------------------------
-- Table structure for `notificacion`
-- ----------------------------
DROP TABLE IF EXISTS `notificacion`;
CREATE TABLE `notificacion` (
  `id_notificacion` int(10) NOT NULL AUTO_INCREMENT,
  `obs_noti` text,
  `st_noti` tinyint(2) DEFAULT NULL,
  `id_afiliado` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_notificacion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notificacion
-- ----------------------------
INSERT INTO `notificacion` VALUES ('1', 'candy 66', '1', '10');

-- ----------------------------
-- Table structure for `novedad`
-- ----------------------------
DROP TABLE IF EXISTS `novedad`;
CREATE TABLE `novedad` (
  `id_novedad` int(10) NOT NULL AUTO_INCREMENT,
  `id_afiliado` int(10) DEFAULT NULL,
  `obs_nov` text,
  `fe_nov` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha de la novedad',
  `estado_nov` tinyint(2) DEFAULT '0' COMMENT '0 inactivo, 1 activo, 2 Revisada, 3 en proceso, 4 caso cerrado',
  PRIMARY KEY (`id_novedad`),
  KEY `id_afiliado_ind` (`id_afiliado`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of novedad
-- ----------------------------
INSERT INTO `novedad` VALUES ('31', '10', 'me quejo por el mal servicio', '2016-03-02 22:46:01', '4');

-- ----------------------------
-- Table structure for `plan`
-- ----------------------------
DROP TABLE IF EXISTS `plan`;
CREATE TABLE `plan` (
  `id_plan` int(10) NOT NULL AUTO_INCREMENT,
  `tipo_plan` varchar(50) DEFAULT NULL COMMENT 'tipo de plan (arqueros, arbitros, 7 en adelante, hinchas)',
  `time_plan` varchar(20) DEFAULT NULL COMMENT 'tiempo del plan 2 dias, 3 dias ,etc',
  `st_plan` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_plan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of plan
-- ----------------------------
INSERT INTO `plan` VALUES ('1', '4-6 AÑOS', 'MiniBoca 2 Dias', '1');
INSERT INTO `plan` VALUES ('2', '7 AÑOS', '2 DIAS', '1');
INSERT INTO `plan` VALUES ('3', '7 AÑOS', '3 DIAS', '1');
INSERT INTO `plan` VALUES ('4', '7 AÑOS', '5 DIAS', '1');
INSERT INTO `plan` VALUES ('5', 'ARQUEROS', '3 DIAS', '1');
INSERT INTO `plan` VALUES ('6', 'ARQUEROS', '4 DIAS', '1');

-- ----------------------------
-- Table structure for `representantes`
-- ----------------------------
DROP TABLE IF EXISTS `representantes`;
CREATE TABLE `representantes` (
  `id_representante` int(10) NOT NULL AUTO_INCREMENT,
  `id_afiliado` int(10) NOT NULL,
  `dociden_repres` varchar(12) NOT NULL,
  `apell_repres` varchar(50) NOT NULL COMMENT 'apellidos',
  `nomb_repres` varchar(50) NOT NULL COMMENT 'nombres',
  `telf_resi_repres` varchar(20) DEFAULT NULL COMMENT 'telefono residencial',
  `email_repres` varchar(50) DEFAULT NULL COMMENT 'correo electronico',
  `par_repres` enum('Padre','Madre','Hermano','Familiar Consanguineo','Representante legal','Amigo','Otros') DEFAULT NULL COMMENT 'Parentesco',
  `cel_repres` varchar(20) DEFAULT NULL COMMENT 'nro celular',
  `contact_e_repres` char(2) DEFAULT NULL COMMENT 'es contacto economico 0 no 1 si',
  `contact_d_repres` char(2) DEFAULT NULL COMMENT 'es contacto deportivo 0 no 1 si',
  `st_repres` enum('Inactivo','Activo') DEFAULT 'Activo' COMMENT 'status del registro 0 inactivo 1 activo',
  PRIMARY KEY (`id_representante`),
  UNIQUE KEY `ind_id_afiliado` (`id_afiliado`),
  UNIQUE KEY `dociden_repres` (`dociden_repres`),
  CONSTRAINT `id_afiliado_fk` FOREIGN KEY (`id_afiliado`) REFERENCES `afiliado` (`id_afiliado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of representantes
-- ----------------------------
INSERT INTO `representantes` VALUES ('1', '10', '1122343343', 'De Arco', 'Flecha', '(1)0987676', 'keymalerock@yahoo.com', 'Madre', '3502123432', '0', '1', 'Activo');

-- ----------------------------
-- Table structure for `respuesta`
-- ----------------------------
DROP TABLE IF EXISTS `respuesta`;
CREATE TABLE `respuesta` (
  `id_respuesta` int(10) NOT NULL AUTO_INCREMENT,
  `id_novedad` int(10) NOT NULL,
  `id_empleado` int(10) DEFAULT NULL COMMENT 'empleado a quien se le asgino el cargo',
  `obs_resp` text COMMENT 'Mensaje adjunto a la asginacion',
  `fe_resp` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'fecha en que se ',
  `estado_resp` tinyint(2) DEFAULT '0' COMMENT 'Status de la respuesta 0 sin ver 1 asiganda, 2 leida por algun agente, 3 cerrado',
  `replica_resp` text,
  PRIMARY KEY (`id_respuesta`),
  KEY `id_novedad_ind` (`id_novedad`),
  CONSTRAINT `id_novedad_fk` FOREIGN KEY (`id_novedad`) REFERENCES `novedad` (`id_novedad`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of respuesta
-- ----------------------------
INSERT INTO `respuesta` VALUES ('1', '31', '3', 'revisar caso de quejas. perfecto gracias por el tiempo gastado y el dinero de soborno', '2016-03-02 22:46:01', '3', 'caso resuelto se compro el silencio del afiliado');

-- ----------------------------
-- Table structure for `userlevelpermissions`
-- ----------------------------
DROP TABLE IF EXISTS `userlevelpermissions`;
CREATE TABLE `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY (`userlevelid`,`tablename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userlevelpermissions
-- ----------------------------
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}afiliado', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}empleados', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}historial', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}matricula', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}novedad', '1');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}plan', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}representantes', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}respuesta', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevels', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_usuarios', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}x_estado_respuesta', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}afiliado', '32');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}empleados', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}historial', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}novedad', '32');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}plan', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}representantes', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}respuesta', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}userlevels', '0');
INSERT INTO `userlevelpermissions` VALUES ('0', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}v_usuarios', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}afiliado', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}empleados', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}historial', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}matricula', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}notificacion', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}novedad', '1');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}plan', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}representantes', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}respuesta', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevels', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_novedades', '96');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_usuarios', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}x_estado_respuesta', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}afiliado', '32');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}empleados', '32');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}historial', '32');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}novedad', '32');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}plan', '32');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}representantes', '32');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}respuesta', '32');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}userlevels', '0');
INSERT INTO `userlevelpermissions` VALUES ('1', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}v_usuarios', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}afiliado', '109');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}empleados', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}historial', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}matricula', '109');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}notificacion', '73');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}novedad', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}plan', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}representantes', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}respuesta', '108');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevels', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_novedades', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_usuarios', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}x_estado_respuesta', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}afiliado', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}empleados', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}historial', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}novedad', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}plan', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}representantes', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}respuesta', '107');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}userlevels', '0');
INSERT INTO `userlevelpermissions` VALUES ('2', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}v_usuarios', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}afiliado', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}empleados', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}historial', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}matricula', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}notificacion', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}novedad', '76');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}plan', '77');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}representantes', '45');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}respuesta', '15');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevels', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_novedades', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_usuarios', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}x_estado_respuesta', '37');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}afiliado', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}empleados', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}historial', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}novedad', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}plan', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}representantes', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}respuesta', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}userlevels', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{D14358A2-AA4F-4759-A927-870E1A6C91CF}v_usuarios', '0');

-- ----------------------------
-- Table structure for `userlevels`
-- ----------------------------
DROP TABLE IF EXISTS `userlevels`;
CREATE TABLE `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) NOT NULL,
  PRIMARY KEY (`userlevelid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of userlevels
-- ----------------------------
INSERT INTO `userlevels` VALUES ('-1', 'Administrator');
INSERT INTO `userlevels` VALUES ('0', 'Default');
INSERT INTO `userlevels` VALUES ('1', 'Afiliado');
INSERT INTO `userlevels` VALUES ('2', 'Empleado');
INSERT INTO `userlevels` VALUES ('3', 'Jefes');

-- ----------------------------
-- Table structure for `x_estado_respuesta`
-- ----------------------------
DROP TABLE IF EXISTS `x_estado_respuesta`;
CREATE TABLE `x_estado_respuesta` (
  `id_x_estado_respuesta` tinyint(3) NOT NULL,
  `estado_respuesta` varchar(50) NOT NULL,
  `status` tinyint(2) NOT NULL,
  PRIMARY KEY (`id_x_estado_respuesta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of x_estado_respuesta
-- ----------------------------
INSERT INTO `x_estado_respuesta` VALUES ('0', 'NO VISTO', '1');
INSERT INTO `x_estado_respuesta` VALUES ('1', 'REVISADO POR EJECUTIVO', '1');
INSERT INTO `x_estado_respuesta` VALUES ('2', 'REVISADO POR EMPLEADO', '1');
INSERT INTO `x_estado_respuesta` VALUES ('3', 'NOVEDAD PROCESADA', '1');
INSERT INTO `x_estado_respuesta` VALUES ('4', 'RECHAZADA', '1');

-- ----------------------------
-- View structure for `v_novedades`
-- ----------------------------
DROP VIEW IF EXISTS `v_novedades`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_novedades` AS select `novedad`.`id_afiliado` AS `id_afiliado`,`afiliado`.`apell_afiliado` AS `apell_afiliado`,`afiliado`.`dociden_afiliado` AS `dociden_afiliado`,`afiliado`.`nomb_afiliado` AS `nomb_afiliado`,`novedad`.`obs_nov` AS `obs_nov`,`novedad`.`fe_nov` AS `fe_nov`,`novedad`.`estado_nov` AS `estado_nov` from (`novedad` join `afiliado` on((`afiliado`.`id_afiliado` = `novedad`.`id_afiliado`)));

-- ----------------------------
-- View structure for `v_usuarios`
-- ----------------------------
DROP VIEW IF EXISTS `v_usuarios`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_usuarios` AS select `empleados`.`id_empleado` AS `id_usuario`,`empleados`.`pass_empleado` AS `pass_empleado`,`empleados`.`login_empleado` AS `login_empleado`,`empleados`.`id_perfil` AS `id_perfil` from `empleados` where (`empleados`.`st_empleado_p` = '1');
