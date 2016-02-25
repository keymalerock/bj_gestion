/*
Navicat MySQL Data Transfer

Source Server         : sin_password
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : nvw_bj

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-02-24 22:59:10
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='tabla para guardar datos del jugador afiliado al club';

-- ----------------------------
-- Records of afiliado
-- ----------------------------
INSERT INTO `afiliado` VALUES ('1', '14903536', 'BONILLA', 'ERIKA', 'PUEBLO NUEVO, SAN CRÃSTOBAL, TÃCHIRA', 'LACUBCHIS@GMAIL.COM', null, 'F', '2009-07-21', null, 's', '25', '1.59', 'SAN JUAN BAUTISTA', null, 'COLEGIO MAGDALENA ORTIZ', 'EPS', 'A+', 'AL PAPA', 'CUBCHIS.JPG', '1', '3');
INSERT INTO `afiliado` VALUES ('2', '98315451', 'delgado', 'joel', 'bogota', 'marvin@gmail.com', null, 'M', '2013-01-08', null, 'S', null, null, null, null, null, null, null, null, null, '1', '3');
INSERT INTO `afiliado` VALUES ('3', '11260000', 'Siza', 'Rebeca', 'Pueblo nuevo', 'labebebka@hotmail.com', null, 'F', '2013-02-04', '98765', 'S', '19', '1.20', 'muzu', null, 'COLEGIO MAGDALENA ORTIZ', '123414', 'a+', 'la mama', null, '1', '3');
INSERT INTO `afiliado` VALUES ('4', '20123123', 'Papatrueno', 'Jose Felix', 'puente aranda', 'elpapatrueno@hotmail.com', '3502144819', 'M', '1982-02-04', null, null, null, null, null, null, null, null, null, null, null, '1', '2');
INSERT INTO `afiliado` VALUES ('5', '45012331', 'Melcacho', 'Rosa', 'Av ciudad de cali', null, null, 'F', '1996-02-07', null, null, null, null, null, null, null, null, null, null, null, '1', '3');
INSERT INTO `afiliado` VALUES ('6', '6574239', 'Maury', 'Carly', 'Sn Cristobal', 'lacarlydayana@gmail.com', null, 'F', '1991-08-06', null, null, null, null, null, null, null, null, null, null, null, '1', '3');
INSERT INTO `afiliado` VALUES ('7', '789123', 'Bonilla', 'Gabby', 'Muzu', 'mariagabriela3000@gmail.com', null, 'F', '1981-02-18', null, null, null, null, null, null, null, null, null, null, null, '1', '1');
INSERT INTO `afiliado` VALUES ('8', '30123123', 'cUALQUIERA', 'CUALQUIERA', 'BOGOTA', 'MICORREO@GMAIL.COM', null, 'M', '2006-02-15', null, null, null, null, null, null, null, null, null, null, null, '1', '1');

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
INSERT INTO `empleados` VALUES ('1', '1126427917', 'Erick', 'Bonilla', '+573202144721', '1', 'erick', 'keymalerock@hotmail.com', '1', '2');
INSERT INTO `empleados` VALUES ('2', '80321453', 'Marvin', 'Pedraza', '+573118912341', '2', 'marvin', 'elmarvin@gmail.com', '1', '2');
INSERT INTO `empleados` VALUES ('3', '3131214', 'Empleado 3', 'apellido 3', null, '1', 'empleado', null, '1', '2');
INSERT INTO `empleados` VALUES ('4', '15027338', 'eL jeFE', 'rOJO', '04147401218', '1', 'jefe', 'ELJEFE@SOY.YO', '1', '3');

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
  UNIQUE KEY `id_afiliado_ind` (`id_afiliado`),
  CONSTRAINT `id_afiliado_fk1` FOREIGN KEY (`id_afiliado`) REFERENCES `afiliado` (`id_afiliado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of historial
-- ----------------------------
INSERT INTO `historial` VALUES ('1', '1', '2010', 'Pre-pichoneros', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of matricula
-- ----------------------------
INSERT INTO `matricula` VALUES ('1', '3', '1', '1', '23000.00', '5000.00', 'no', '3', null, null, 'Si', null, null, '1', '2', null, '05/2016', null, '1', '1', '1');
INSERT INTO `matricula` VALUES ('2', '2', '1', '3', '4555.00', '4545.00', 'si', '1', 'eric@gmail.com', null, '1', null, '1', '1', '3', null, '07/2000', null, '1', '1', '1');
INSERT INTO `matricula` VALUES ('3', '1', '2', '1', '7800.00', '7800.00', '2', '3', null, null, '1', null, '1', '0', null, null, null, '1', null, '1', null);
INSERT INTO `matricula` VALUES ('4', '8', '1', '4', '5000.00', '6000.00', 'SEMANAL', '4', null, null, '1', '1', null, '0', null, null, '06/2016', null, '1', '1', '1');
INSERT INTO `matricula` VALUES ('5', '6', '1', '3', '545665.00', '4565.00', '3', '3', null, null, null, null, null, '0', null, null, '3', null, null, null, null);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of notificacion
-- ----------------------------
INSERT INTO `notificacion` VALUES ('1', 'tuteka looca', '3', '1');
INSERT INTO `notificacion` VALUES ('2', 'cocoÃ±ia', '4', '4');
INSERT INTO `notificacion` VALUES ('3', 'okey', '3', '2');
INSERT INTO `notificacion` VALUES ('4', 'SASF', '1', '4');
INSERT INTO `notificacion` VALUES ('5', 'fsadf', '2', '8');
INSERT INTO `notificacion` VALUES ('6', 'hola mundo', '1', '8');
INSERT INTO `notificacion` VALUES ('7', 'brincando en la pared', '2', '4');
INSERT INTO `notificacion` VALUES ('8', 'tretregsr', '1', '6');

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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of novedad
-- ----------------------------
INSERT INTO `novedad` VALUES ('1', '1', 'todo fino', '2016-02-16 22:36:22', '2');
INSERT INTO `novedad` VALUES ('2', '2', 'marvin', '2016-02-02 00:13:47', '0');
INSERT INTO `novedad` VALUES ('3', '1', 'Hola Bogota', '2016-02-02 23:43:58', '0');
INSERT INTO `novedad` VALUES ('4', '1', 'buenas practicas', '2016-02-03 23:12:42', '0');
INSERT INTO `novedad` VALUES ('5', '1', 'sdsa sda AD', '2016-02-04 21:50:14', '0');
INSERT INTO `novedad` VALUES ('6', '3', 'NO me gust el beisbol', '2016-02-20 09:53:08', '2');
INSERT INTO `novedad` VALUES ('7', '2', 'tester', '2016-02-16 21:21:20', '0');
INSERT INTO `novedad` VALUES ('8', '5', 'tengo una queja', '2016-02-16 22:27:47', '0');
INSERT INTO `novedad` VALUES ('9', '5', 'segunda prueba', '2016-02-16 23:09:51', '2');
INSERT INTO `novedad` VALUES ('10', '6', 'mas quejas', '2016-02-16 23:29:35', '2');
INSERT INTO `novedad` VALUES ('11', '6', 'mas nobedades', '2016-02-17 21:37:33', '2');
INSERT INTO `novedad` VALUES ('12', '7', 'otra novedad mas', '2016-02-17 23:09:48', '3');
INSERT INTO `novedad` VALUES ('13', '3', 'hola mundo queer', '2016-02-18 00:42:20', '2');
INSERT INTO `novedad` VALUES ('14', '4', 'Vuelve y juega', '2016-02-18 01:36:26', '4');
INSERT INTO `novedad` VALUES ('15', '6', 'NOVEDAD UNO', '2016-02-19 23:20:22', '4');
INSERT INTO `novedad` VALUES ('16', null, null, '2016-02-22 21:07:28', '1');
INSERT INTO `novedad` VALUES ('17', '8', 'no me gusta esa cancion', '2016-02-22 21:40:21', '2');
INSERT INTO `novedad` VALUES ('18', '2', 'nueva novedad', '2016-02-22 22:09:34', '1');
INSERT INTO `novedad` VALUES ('19', '2', 'Me equivoque esta si vale', '2016-02-22 22:11:01', '1');
INSERT INTO `novedad` VALUES ('20', '2', 'nuevo intento', '2016-02-22 22:13:23', '2');
INSERT INTO `novedad` VALUES ('21', '2', 'asf ad sgfadsf', '2016-02-22 23:14:22', '4');
INSERT INTO `novedad` VALUES ('22', '4', 'novedad ultima', '2016-02-23 01:29:44', '2');
INSERT INTO `novedad` VALUES ('23', '1', 'nuevo juego nueva suerte', '2016-02-23 01:59:35', '4');
INSERT INTO `novedad` VALUES ('24', '3', 'los entrenadores dan patadas a los niños troncos', '2016-02-23 03:12:01', '4');
INSERT INTO `novedad` VALUES ('25', '5', 'hols', '2016-02-23 14:11:09', '4');
INSERT INTO `novedad` VALUES ('26', '3', 'nueva queda', '2016-02-24 22:14:57', '2');
INSERT INTO `novedad` VALUES ('27', '8', 'queja', '2016-02-24 22:43:29', '4');

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
INSERT INTO `plan` VALUES ('1', '4-6 AÃ‘OS', '2 DIAS', '1');
INSERT INTO `plan` VALUES ('2', '7 AÃ‘OS', '2 DIAS', '1');
INSERT INTO `plan` VALUES ('3', '7 AÃ‘OS', '3 DIAS', '1');
INSERT INTO `plan` VALUES ('4', '7 AÃ‘OS', '5 DIAS', '1');
INSERT INTO `plan` VALUES ('5', 'ARQUEROS', '2 DIAS', '1');
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
  CONSTRAINT `id_afiliado_fk` FOREIGN KEY (`id_afiliado`) REFERENCES `afiliado` (`id_afiliado`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of representantes
-- ----------------------------
INSERT INTO `representantes` VALUES ('1', '1', '12356', 'DELGADO', 'MARVIN', '9876', 'MMMM@GMAIL.COM', 'Padre', null, null, null, 'Activo');
INSERT INTO `representantes` VALUES ('2', '8', '34343498', 'papa', 'papa', null, null, 'Padre', null, '0', '0', 'Activo');

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of respuesta
-- ----------------------------
INSERT INTO `respuesta` VALUES ('1', '5', '3', 'arreglar caso', '2016-02-04 23:50:14', '1', null);
INSERT INTO `respuesta` VALUES ('2', '1', '1', 'ojo con esa queja', '2016-02-05 16:32:57', '1', null);
INSERT INTO `respuesta` VALUES ('3', '2', '2', 'revise con cuidado el caso', '2016-02-09 20:45:38', '2', 'listo revisado');
INSERT INTO `respuesta` VALUES ('4', '1', '1868', 'mal registrado ,cedula noexiste', '2016-02-07 00:37:23', '0', null);
INSERT INTO `respuesta` VALUES ('5', '1', '2', 'Hola aqui deberia ser estatus 1', '2016-02-09 20:29:37', '1', null);
INSERT INTO `respuesta` VALUES ('6', '10', '2', 'hhgkhjgj', '2016-02-16 23:29:35', '1', null);
INSERT INTO `respuesta` VALUES ('7', '11', '2', 'yuyo', '2016-02-17 21:47:53', '2', 'listo bacan');
INSERT INTO `respuesta` VALUES ('8', '12', '1', 'asignada a erick de nivel 2', '2016-02-18 00:28:28', '3', 'vista por el empleado');
INSERT INTO `respuesta` VALUES ('9', '13', '1', 'otra vez novedad a erick', '2016-02-18 00:49:50', '2', 'replica de cansado');
INSERT INTO `respuesta` VALUES ('10', '14', '3', 'favor revisar this shit.', '2016-02-18 01:36:26', '3', 'ya me voy a dormir.. no me jodas');
INSERT INTO `respuesta` VALUES ('11', '15', '2', 'POR FAVOR REVISAR ESTE CASO', '2016-02-19 23:04:26', '2', 'NO es conmigo');
INSERT INTO `respuesta` VALUES ('12', '15', '3', 'Segunda Asignacion, revisar', '2016-02-19 23:20:22', '3', 'si este caso es conmigo');
INSERT INTO `respuesta` VALUES ('13', '6', '3', 'empleado 3', '2016-02-20 09:53:08', '1', null);
INSERT INTO `respuesta` VALUES ('14', '17', '3', 'trabaje', '2016-02-22 21:40:21', '1', null);
INSERT INTO `respuesta` VALUES ('15', '17', '2', 'faSFSADGF', '2016-02-22 21:49:37', '1', null);
INSERT INTO `respuesta` VALUES ('16', '20', '3', 'revisar esta noveda vigesima', '2016-02-22 22:13:23', '1', null);
INSERT INTO `respuesta` VALUES ('17', '21', '3', 'Se esfumo', '2016-02-23 01:16:14', '1', 'no es conmigo');
INSERT INTO `respuesta` VALUES ('18', '22', '2', 'adSDA', '2016-02-23 01:22:05', '3', 'no sirve sIHFau SAF NASDF SADMNBF');
INSERT INTO `respuesta` VALUES ('19', '22', '1', 'hola', '2016-02-23 01:29:44', '1', null);
INSERT INTO `respuesta` VALUES ('20', '23', '1', 'mas trabajo, fue remitida a otro empleado', '2016-02-23 01:54:36', '4', 'rechazada asignar a otro');
INSERT INTO `respuesta` VALUES ('21', '23', '3', 'empleado 3 resuelva ud, gracias muy mamable por resolver', '2016-02-23 01:59:35', '3', 'si listo el jugador sera atendido en camilla');
INSERT INTO `respuesta` VALUES ('22', '24', '2', 'Revise quien le pega a los guinos, caso cerrado', '2016-02-23 03:12:01', '3', 'rabaleros');
INSERT INTO `respuesta` VALUES ('23', '25', '2', 'hhhhhhh', '2016-02-23 14:11:09', '3', 'wrtrtyer');
INSERT INTO `respuesta` VALUES ('24', '25', '1', 'kkkkkk', '2016-02-23 14:04:34', '1', null);
INSERT INTO `respuesta` VALUES ('25', '26', '3', 'empelado 3', '2016-02-24 22:22:01', '4', 'rechazada por equivocacion');
INSERT INTO `respuesta` VALUES ('26', '27', '2', 'para marvin', '2016-02-24 22:43:29', '4', 'equivocacion 555');

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
INSERT INTO `userlevelpermissions` VALUES ('2', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_novedades', '104');
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
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}plan', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}representantes', '109');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}respuesta', '15');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevelpermissions', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}userlevels', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_novedades', '104');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}v_usuarios', '0');
INSERT INTO `userlevelpermissions` VALUES ('3', '{CD332243-68AB-4368-BF10-F24F7E84F4D6}x_estado_respuesta', '0');
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
