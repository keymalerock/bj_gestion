ALTER 
ALGORITHM=UNDEFINED 
DEFINER=`root`@`localhost` 
SQL SECURITY DEFINER 
VIEW `v_usuarios` AS 
SELECT
empleados.id_empleado AS id_usuario,
empleados.pass_empleado AS pass_empleado,
empleados.login_empleado AS login_empleado,
empleados.id_perfil AS id_perfil
from `empleados`
where (`empleados`.`st_empleado_p` = '1') ;
/**************************************************/
CREATE 
VIEW `v_novedades`AS 
SELECT
novedad.id_afiliado,
afiliado.apell_afiliado,
afiliado.dociden_afiliado,
afiliado.nomb_afiliado,
novedad.obs_nov,
novedad.fe_nov,
novedad.estado_nov
FROM
novedad
Inner Join afiliado ON afiliado.id_afiliado = novedad.id_afiliado ;

