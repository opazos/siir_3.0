<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

/************************  Area de Reportes *************************/

//1.- Nº de distritos atendidos
$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die(mysql_error());
$distritos=mysql_num_rows($result);

//2.- Nº de distritos quintil 1
$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$pobreza_1=mysql_num_rows($result);

//3.- Nº de distritos quintil 2
$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$pobreza_2=mysql_num_rows($result);

$distritos_pobres=$pobreza_1+$pobreza_2;

//4.- Nº de PIT
$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000";
$result=mysql_query($sql) or die (mysql_error());
$n_pit=mysql_num_rows($result);

//5.- Nº de familias PIT
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
WHERE org_ficha_usuario.titular=1 AND
pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_user_iniciativa.estado=1";
$result=mysql_query($sql) or die (mysql_error());
$familias_pit=mysql_num_rows($result);

//6.- Nº de familias PDN
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.n_contrato<>0 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000";
$result=mysql_query($sql) or die (mysql_error());
$familias_pdn=mysql_num_rows($result);

$n_familias=$familias_pit+$familias_pdn;

//7.- Nº de familias PGRN
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa AND pit_bd_ficha_mrn.cod_mrn = pit_bd_user_iniciativa.cod_iniciativa
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$familias_pgrn=mysql_num_rows($result);

//8.- Nº de familias PDEN
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa AND pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$familias_pdn_total=mysql_num_rows($result);

//9.- Nº de organizaciones de PGRN
$sql="SELECT DISTINCT org_ficha_organizacion.n_documento
FROM pit_bd_ficha_mrn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$org_pgrn=mysql_num_rows($result);

//10.- Nº de personas de PGRN
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa AND pit_bd_ficha_mrn.cod_mrn = pit_bd_user_iniciativa.cod_iniciativa
WHERE
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$n_persona_pgrn=mysql_num_rows($result);

//11.- Nº de personas PGRN mujeres
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa AND pit_bd_ficha_mrn.cod_mrn = pit_bd_user_iniciativa.cod_iniciativa
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=0 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$n_mujer_pgrn=mysql_num_rows($result);

//12.- Nº de personas PGRN varones
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa AND pit_bd_ficha_mrn.cod_mrn = pit_bd_user_iniciativa.cod_iniciativa
WHERE pit_bd_user_iniciativa.estado=1 AND
org_ficha_usuario.sexo=1 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$n_varon_pgrn=mysql_num_rows($result);

//13.- Nº de animadores rurales
$sql="SELECT DISTINCT ficha_ag_oferente.n_documento
FROM ficha_animador INNER JOIN ficha_ag_oferente ON ficha_animador.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_animador.n_documento = ficha_ag_oferente.n_documento";
$result=mysql_query($sql) or die (mysql_error());
$n_animador=mysql_num_rows($result);

//14.- Nº de animadores mujers
$sql="SELECT DISTINCT ficha_ag_oferente.n_documento
FROM ficha_animador INNER JOIN ficha_ag_oferente ON ficha_animador.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_animador.n_documento = ficha_ag_oferente.n_documento
WHERE ficha_ag_oferente.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$animador_mujer=mysql_num_rows($result);

//15.- Nº de animadores hombres
$sql="SELECT DISTINCT ficha_ag_oferente.n_documento
FROM ficha_animador INNER JOIN ficha_ag_oferente ON ficha_animador.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_animador.n_documento = ficha_ag_oferente.n_documento
WHERE ficha_ag_oferente.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$animador_hombre=mysql_num_rows($result);

//16.- Organizaciones que se presentaron al CLAR
$sql="SELECT DISTINCT pit_bd_ficha_mrn.n_documento_org
FROM pit_bd_ficha_mrn";
$result=mysql_query($sql) or die (mysql_error());
$org_clar=mysql_num_rows($result);

//17.- Organizaciones aprobadas
$sql="SELECT DISTINCT pit_bd_ficha_mrn.n_documento_org
FROM pit_bd_ficha_mrn
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$org_aprobada=mysql_num_rows($result);

//18.- Organizaciones aprobadas que son comunidades campesinas
$sql="SELECT DISTINCT pit_bd_ficha_mrn.n_documento_org
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_tipo_org=1";
$result=mysql_query($sql) or die (mysql_error());
$org_aprobada_comunidad=mysql_num_rows($result);

//19.- Organizaciones que cuentan con mapa
$sql="SELECT DISTINCT pit_bd_ficha_pit.n_documento_taz
FROM pit_bd_ficha_pit
WHERE pit_bd_ficha_pit.concurso=1 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$org_mapa=mysql_num_rows($result);

//20.- Organizaciones que realizan cif
$sql="SELECT DISTINCT pit_bd_ficha_mrn.n_documento_org
FROM pit_bd_ficha_mrn INNER JOIN cif_bd_concurso ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn";
$result=mysql_query($sql) or die (mysql_error());
$org_cif=mysql_num_rows($result);

//21.- Nº de concursos Interfamiliares
$sql="SELECT cif_bd_concurso.cod_concurso_cif
FROM cif_bd_concurso";
$result=mysql_query($sql) or die (mysql_error());
$n_cif=mysql_num_rows($result);

//22.- Nº de Mujeres participantes
$sql="SELECT SUM(cif_bd_ficha.n_mujeres) as mujeres
FROM cif_bd_ficha";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
$mujeres_cif=$r1['mujeres'];

//23.- Nº de Familias Participantes CIF
$sql="SELECT DISTINCT cif_bd_participante_cif.n_documento
FROM org_ficha_usuario INNER JOIN cif_bd_participante_cif ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
WHERE org_ficha_usuario.titular=1";
$result=mysql_query($sql) or die (mysql_error());
$famlias_cif=mysql_num_rows($result);

//24.- PGRN con asistencia tecnica
$sql="SELECT DISTINCT pit_bd_ficha_mrn.n_documento_org
FROM pit_bd_ficha_mrn INNER JOIN ficha_sat ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());
$pgrn_sat=mysql_num_rows($result);

//25.- Contratos SAT
$sql="SELECT ficha_sat.cod_sat
FROM pit_bd_ficha_mrn INNER JOIN ficha_sat ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());
$pgrn_contrato_sat=mysql_num_rows($result);

//26.- Nº de oferentes
$sql="SELECT DISTINCT ficha_sat.n_documento
FROM pit_bd_ficha_mrn INNER JOIN ficha_sat ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());
$oferente_pgrn=mysql_num_rows($result);

//27.- Oferentes campesino
$sql="SELECT DISTINCT ficha_ag_oferente.n_documento
FROM pit_bd_ficha_mrn INNER JOIN ficha_sat ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_sat.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_sat.n_documento
WHERE ficha_ag_oferente.cod_tipo_oferente=1";
$result=mysql_query($sql) or die (mysql_error());
$oferente_campesino_pgrn=mysql_num_rows($result);

//28.- Oferentes campesinas mujeres
$sql="SELECT DISTINCT ficha_ag_oferente.n_documento
FROM pit_bd_ficha_mrn INNER JOIN ficha_sat ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_sat.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_sat.n_documento
WHERE ficha_ag_oferente.cod_tipo_oferente=1 AND
ficha_ag_oferente.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$oferente_mujer=mysql_num_rows($result);

//29.- Nº de Visitas Guiadas
$sql="SELECT ficha_vg.cod_visita
FROM pit_bd_ficha_mrn INNER JOIN ficha_vg ON pit_bd_ficha_mrn.cod_mrn = ficha_vg.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());
$vg_mrn=mysql_num_rows($result);

//30.- Nº de participantes VG
$sql="SELECT ficha_participante_vg.n_documento
FROM ficha_vg INNER JOIN ficha_participante_vg ON ficha_vg.cod_visita = ficha_participante_vg.cod_visita
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = ficha_vg.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());
$participante_vg_mrn=mysql_num_rows($result);

//40.- Nº de participantes Mujeres VG
$sql="SELECT ficha_participante_vg.n_documento
FROM ficha_vg INNER JOIN ficha_participante_vg ON ficha_vg.cod_visita = ficha_participante_vg.cod_visita
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = ficha_participante_vg.cod_tipo_doc AND org_ficha_usuario.n_documento = ficha_participante_vg.n_documento
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = ficha_vg.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$mujeres_vg_mrn=mysql_num_rows($result);

//41.- Nº PDN presentadas al clar
$sql="SELECT DISTINCT pit_bd_ficha_pdn.n_documento_org
FROM pit_bd_ficha_pdn";
$result=mysql_query($sql) or die (mysql_error());
$pdn_clar=mysql_num_rows($result); 

//42.- Nº de PDN aprobados en el CLAR
$sql="SELECT pit_bd_ficha_pdn.n_documento_org
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$pdn_aprobado=mysql_num_rows($result);

//43.- Nº de PDN microempresas
$sql="SELECT DISTINCT pit_bd_ficha_pdn.n_documento_org
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_tipo_org=5";
$result=mysql_query($sql) or die (mysql_error());
$pdn_microempresa=mysql_num_rows($result);

//44.- Nº de PDN diferente a microempresa
$sql="SELECT DISTINCT pit_bd_ficha_pdn.n_documento_org
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_tipo_org<>5";
$result=mysql_query($sql) or die (mysql_error());
$pdn_org=mysql_num_rows($result);

//45.- Nº de participantes PDN
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_pdn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$participante_pdn=mysql_num_rows($result);

//46.- Mujeres participantes PDN
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_pdn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_tipo_org<>5 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$mujeres_pdn=mysql_num_rows($result);

//47.- Varones participantes PDN
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_pdn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_tipo_org<>5 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$hombres_pdn=mysql_num_rows($result);

//48.- Mujeres microempresarias
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_pdn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_tipo_org=5 AND
org_ficha_usuario.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$mujeres_micro=mysql_num_rows($result);

//49.- Hombres microempresarios
$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM pit_bd_user_iniciativa INNER JOIN pit_bd_ficha_pdn ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_tipo_org=5 AND
org_ficha_usuario.sexo=1";
$result=mysql_query($sql) or die (mysql_error());
$hombres_micro=mysql_num_rows($result);

//50.- Nº de IDL
$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM pit_bd_ficha_idl
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>003";
$result=mysql_query($sql) or die (mysql_error());
$idl=mysql_num_rows($result);

//51.- Nº de contratos sat pdn
$sql="SELECT ficha_sat.cod_sat
FROM pit_bd_ficha_pdn INNER JOIN ficha_sat ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());
$sat_pdn=mysql_num_rows($result);

//52.- Nº de oferentes
$sql="SELECT DISTINCT ficha_sat.n_documento
FROM pit_bd_ficha_pdn INNER JOIN ficha_sat ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());
$oferente_pdn=mysql_num_rows($result);

//53.- Oferentes mujeres
$sql="SELECT DISTINCT ficha_sat.n_documento
FROM pit_bd_ficha_pdn INNER JOIN ficha_sat ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_sat.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_sat.n_documento
WHERE ficha_ag_oferente.sexo=0";
$result=mysql_query($sql) or die (mysql_error());
$oferente_pdn_mujer=mysql_num_rows($result);

//54.- Nº de visitas Guiadas
$sql="SELECT ficha_vg.cod_visita
FROM pit_bd_ficha_pdn INNER JOIN ficha_vg ON pit_bd_ficha_pdn.cod_pdn = ficha_vg.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());
$vg_pdn=mysql_num_rows($result);

//55.- Participantes Visita guiadas
$sql="SELECT ficha_participante_vg.n_documento
FROM ficha_participante_vg INNER JOIN ficha_vg ON ficha_participante_vg.cod_visita = ficha_vg.cod_visita
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = ficha_vg.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa";
	$result=mysql_query($sql) or die (mysql_error()); 
	$participante_vg_pdn=mysql_num_rows($result);
	
//56.- Nº de eventos de promocion comercial
$sql="SELECT ficha_pf.cod_feria
FROM pit_bd_ficha_pdn INNER JOIN ficha_pf ON pit_bd_ficha_pdn.cod_pdn = ficha_pf.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_pf.cod_tipo_iniciativa";
$result=mysql_query($sql) or die (mysql_error());	
$pf_pdn=mysql_num_rows($result);

//57.- Nº de participantes de promocion comercial
$sql="SELECT ficha_participante_pf.n_documento
FROM pit_bd_ficha_pdn INNER JOIN ficha_pf ON pit_bd_ficha_pdn.cod_pdn = ficha_pf.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_pf.cod_tipo_iniciativa
INNER JOIN ficha_participante_pf ON ficha_participante_pf.cod_feria = ficha_pf.cod_feria";
$result=mysql_query($sql) or die (mysql_error());
$participante_pf_pdn=mysql_num_rows($result);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>::Vista Preeliminar::</title>
<!-- cargamos el estilo de la pagina -->
<link href="../stylesheets/print.css" rel="stylesheet" type="text/css">
<style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
</style>
<!-- Fin -->
</head>

<body>
<? include("../print/encabezado.php");?>

<div class="capa gran_titulo centrado">MARCO LOGICO DEL PROYECTO SIERRA SUR II</div>
<p>&nbsp;</p>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="23%">RESUMEN NARATIVO</td>
    <td width="26%">INDICADORES VERIFICABLES</td>
    <td width="8%">UNIDAD DE MEDIDA</td>
    <td width="8%">META </td>
    <td width="8%">EJECUTADO</td>
    <td width="9%">%</td>
  </tr>
  <tr>
    <td rowspan="22"><strong><u>OBJETIVO DEL PROYECTO</u></strong>
    <br/>
    Lograr que los hombre sy mujeres campesinos y micro empresarios usuarios de Sierra Sur II aumenten sus ingresos, sus activos tangibles y valoricen sus conocimientos, organizacion social y autoestima.</td>
    <td colspan="5"><strong><em>COBERTURA ESPACIAL</em></strong></td>
  </tr>
  <tr>
    <td>- Total distritos atendidos del Ámbito</td>
    <td class="centrado">Distrito</td>
    <td class="derecha">119</td>
    <td class="derecha"><? echo number_format($distritos);?></td>
    <td class="derecha"><? echo number_format(($distritos/119)*100);?></td>
  </tr>
  <tr>
    <td>- Total distritos atendidos de los quintiles 1 y 2</td>
    <td class="centrado">Distrito</td>
    <td class="derecha">108</td>
    <td class="derecha"><? echo number_format($distritos_pobres);?></td>
    <td class="derecha"><? echo number_format(($distritos_pobres/108)*100);?></td>
  </tr>
  <tr>
    <td>- Unidades Territoriales atendidas con Plan de Inversión Territorial - PIT</td>
    <td class="centrado">UT</td>
    <td class="derecha">210</td>
    <td class="derecha"><? echo number_format($n_pit);?></td>
    <td class="derecha"><? echo number_format(($n_pit/210)*100);?></td>
  </tr>
  <tr>
    <td>- Instalación y funcionamiento de Comite Local de Asignación de Recusos</td>
    <td class="centrado">CLAR</td>
    <td class="derecha">7</td>
    <td class="derecha">7</td>
    <td class="derecha">100</td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>USUARIOS DIRECTOS</strong></em></td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>FAMILIAS ATENDIDAS POR EL PROYECTO</strong></em></td>
  </tr>
  <tr>
    <td>- Total familias de organizaciones atendidas por el Proyecto</td>
    <td class="centrado">Familia</td>
    <td class="derecha">22,730</td>
    <td class="derecha"><? echo number_format($n_familias);?></td>
    <td class="derecha"><? echo number_format(($n_familias/22730)*100);?></td>
  </tr>
  <tr>
    <td>- Familias con organizaciones con Planes de Gestión de Recursos Naturales</td>
    <td class="centrado">Familia</td>
    <td class="derecha">9,579</td>
    <td class="derecha"><? echo number_format($familias_pgrn);?></td>
    <td class="derecha"><? echo number_format(($familias_pgrn/9579)*100);?></td>
  </tr>
  <tr>
    <td>- Familias de organizaciones con Planes de Negocio</td>
    <td class="centrado">Familia</td>
    <td class="derecha">13,151</td>
    <td class="derecha"><? echo number_format($familias_pdn_total);?></td>
    <td class="derecha"><? echo number_format(($familias_pdn_total/13151)*100);?></td>
  </tr>
  <tr>
    <td>- Familias de organizaciones con Planes de Gestión de Recursos Naturales y Planes de Negocio</td>
    <td class="centrado">Familia</td>
    <td class="derecha">6,706</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>FAMILIAS QUE SUPERAN LA CONDICIÓN DE POBREZA</strong></em></td>
  </tr>
  <tr>
    <td>- Nº de familias que dejan de ser pobres</td>
    <td class="centrado">Familia</td>
    <td class="derecha">11,592</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>- Nº de familias que superan la pobreza atribuible al proyecto</td>
    <td class="centrado">Familia</td>
    <td class="derecha">8,410</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>- Nº de familias que mejoran ingresos pero aun no salen de la pobreza</td>
    <td class="centrado">Familia</td>
    <td class="derecha">4,295</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>EMPRENDIMIENTOS DE LAS FAMILIAS</strong></em></td>
  </tr>
  <tr>
    <td>- Aumento promedio de US$ 150 anuales en ingresos de los hogares</td>
    <td class="centrado">Familia</td>
    <td class="derecha">9,680</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>- Valor aumentado de bienes naturales y fisicos en US$ 1000 por familias</td>
    <td class="centrado">Familia</td>
    <td class="derecha">13,050</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>- Conocimientos locales registrados y reconocidos</td>
    <td class="centrado">Registro</td>
    <td class="derecha">10</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>- Organizaciones campesinas gestionando adecuadamente sus Recursos Naturales</td>
    <td class="centrado">Organizacion</td>
    <td class="derecha">140</td>
    <td class="derecha"><? echo number_format($org_pgrn);?></td>
    <td class="derecha"><? echo number_format(($org_pgrn/140)*100);?></td>
  </tr>
  <tr>
    <td>- Organizaciones campesinas gestionando adecuadamente sus negocios</td>
    <td class="centrado">Organizacion</td>
    <td class="derecha">935</td>
    <td class="derecha"><? echo number_format($pdn_aprobado);?></td>
    <td class="derecha"><? echo number_format(($pdn_aprobado/935)*100);?></td>
  </tr>
  <tr>
    <td>- Municipios aplican las metodologías del Proyecto a favor de sus poblaciones</td>
    <td class="centrado">Municipio</td>
    <td class="derecha">48</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="23%">RESUMEN NARATIVO</td>
    <td width="26%">INDICADORES VERIFICABLES</td>
    <td width="8%">UNIDAD DE MEDIDA</td>
    <td width="8%">META </td>
    <td width="8%">EJECUTADO</td>
    <td width="9%">%</td>
  </tr>
  <tr>
    <td colspan="6"><em><strong>RESULTADOS</strong></em></td>
  </tr>
  <tr>
    <td rowspan="14">Resultado 1.- Recursos naturales rehabilitados y capitalizados son manejados adecuadamente</td>
    <td colspan="5"><strong><em>SELECCION DE COMUNIDADES U ORGANIZACIONES CAMPESINAS DE PGRN EN CLAR's</em></strong></td>
  </tr>
  <tr>
    <td>Nº de familias capacitadas realizando practicas de manejo de recursos naturales</td>
    <td class="centrado">Fam.</td>
    <td class="derecha">9,579</td>
    <td class="derecha"><? echo number_format($familias_pgrn);?></td>
    <td class="derecha"><? echo number_format(($familias_pgrn/9579)*100);?></td>
  </tr>
  <tr>
    <td>Nº de Organizaciones gestionando recursos naturales (PGRN)</td>
    <td class="centrado">Org.</td>
    <td class="derecha">140</td>
    <td class="derecha"><? echo number_format($org_pgrn);?></td>
    <td class="derecha"><? echo number_format(($org_pgrn/140)*100);?></td>
  </tr>
  <tr>
    <td>Nº de personas capacitadas realizando prácticas de manejo de recursos naturales</td>
    <td class="centrado">Persona</td>
    <td class="derecha">12,453</td>
    <td class="derecha"><? echo number_format($n_persona_pgrn);?></td>
    <td class="derecha"><? echo number_format(($n_persona_pgrn/12453)*100);?></td>
  </tr>
  <tr>
    <td> - Mujeres participantes</td>
    <td class="centrado">Persona</td>
    <td class="derecha">5,604</td>
    <td class="derecha"><? echo number_format($n_mujer_pgrn);?></td>
    <td class="derecha"><? echo number_format(($n_mujer_pgrn/5604)*100);?></td>
  </tr>
  <tr>
    <td> - Varones participantes</td>
    <td class="centrado">Persona</td>
    <td class="derecha">6,849</td>
    <td class="derecha"><? echo number_format($n_varon_pgrn);?></td>
    <td class="derecha"><? echo number_format(($n_varon_pgrn/6849)*100);?></td>
  </tr>
  <tr>
    <td>Practicas sustentables identificadas y aplicadas</td>
    <td class="centrado">&nbsp;</td>
    <td class="derecha">10</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>SUPERFICIE DE TIERRAS CON APLICACION DE PRACTICAS SUSTENTABLES</strong></em></td>
  </tr>
  <tr>
    <td> - Bajo Riego (goteo, aspersión y compostura)</td>
    <td class="centrado">Ha</td>
    <td class="derecha">2,000</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td> - En secano (surcos en contorno, abonamiento organico, rotación)</td>
    <td class="centrado">Ha</td>
    <td class="derecha">1,800</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td> - Pastos Naturales (ordenamiento, resiembra, cercos, otros)</td>
    <td class="centrado">Ha</td>
    <td class="derecha">16,000</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Animadores Rurales apoyando la gestión de recursos naturales</td>
    <td class="centrado">Persona</td>
    <td class="derecha">140</td>
    <td class="derecha"><? echo number_format($n_animador);?></td>
    <td class="derecha"><? echo number_format(($n_animador/140)*100);?></td>
  </tr>
  <tr>
    <td> - Animadores Rurales Mujeres</td>
    <td class="centrado">Persona</td>
    <td class="derecha">35</td>
    <td class="derecha"><? echo number_format($animador_mujer);?></td>
    <td class="derecha"><? echo number_format(($animador_mujer/35)*100);?></td>
  </tr>
  <tr>
    <td>- Animadores Rurales Varones</td>
    <td class="centrado">Persona</td>
    <td class="derecha">105</td>
    <td class="derecha"><? echo number_format($animador_hombre);?></td>
    <td class="derecha"><? echo number_format(($animador_hombre/105)*100);?></td>
  </tr>
  <tr>
    <td colspan="6"><strong><em>PRODUCTOS DEL R1</em></strong></td>
  </tr>
  <tr>
    <td rowspan="20">Actividad 1: Incentivos para la formación y manejo de activos productivos</td>
    <td colspan="5"><em><strong>SELECCION DE COMUNIDADES U ORGANIZACIONES CAMPESINAS DE PGRN EN CLARS</strong></em></td>
  </tr>
  <tr>
    <td>Organizaciones que se presentaron al CLAR</td>
    <td class="centrado">Org.</td>
    <td class="derecha">150</td>
    <td class="derecha"><? echo number_format($org_clar);?></td>
    <td class="derecha"><? echo number_format(($org_clar/150)*100);?></td>
  </tr>
  <tr>
    <td>Organizaciones seleccionadas en el CLAR</td>
    <td class="centrado">Org.</td>
    <td class="derecha">140</td>
    <td class="derecha"><? echo number_format($org_aprobada);?></td>
    <td class="derecha"><? echo number_format(($org_aprobada/140)*100);?></td>
  </tr>
  <tr>
    <td>Organizaciones seleccionadas que son comunidades campesinas</td>
    <td class="centrado">Comunidad Campesina</td>
    <td class="derecha">60</td>
    <td class="derecha"><? echo number_format($org_aprobada_comunidad);?></td>
    <td class="derecha"><? echo number_format(($org_aprobada_comunidad/60)*100);?></td>
  </tr>
  <tr>
    <td>Organizaciones que cuentan con Mapas Culturales (pasado, presente, futuro)</td>
    <td class="centrado">Org.</td>
    <td class="derecha">150</td>
    <td class="derecha"><? echo number_format($org_mapa);?></td>
    <td class="derecha"><? echo number_format(($org_mapa/150)*100);?></td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>CONCURSOS INTERFAMILIARES</strong></em></td>
  </tr>
  <tr>
    <td>Nº de organizaciones que realizan Concursos Interfamiliares</td>
    <td class="centrado">Org.</td>
    <td class="derecha">140</td>
    <td class="derecha"><? echo number_format($org_cif);?></td>
    <td class="derecha"><? echo number_format(($org_cif/140)*100);?></td>
  </tr>
  <tr>
    <td>Nº de Concursos Interfamiliares</td>
    <td class="centrado">Concurso</td>
    <td class="derecha">560</td>
    <td class="derecha"><? echo number_format($n_cif);?></td>
    <td class="derecha"><? echo number_format(($n_cif/560)*100);?></td>
  </tr>
  <tr>
    <td>Nº de familias que participan en los Concursos</td>
    <td class="centrado">Familia</td>
    <td class="derecha">9,579</td>
    <td class="derecha"><? echo number_format($famlias_cif);?></td>
    <td class="derecha"><? echo number_format(($famlias_cif/9579)*100);?></td>
  </tr>
  <tr>
    <td>Mujeres participantes en Concursos Interfamiliares</td>
    <td class="centrado">Persona</td>
    <td class="derecha">5,604</td>
    <td class="derecha"><? echo number_format($mujeres_cif);?></td>
    <td class="derecha"><? echo number_format(($mujeres_cif/5604)*100);?></td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>CONCURSOS INTERCOMUNALES ANUALES</strong></em></td>
  </tr>
  <tr>
    <td>Nº de organizaciones participantes</td>
    <td class="centrado">Org.</td>
    <td class="derecha">100</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de concursos intercomunales anuales</td>
    <td class="centrado">Concurso</td>
    <td class="derecha">145</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>CONCURSO DE RESULTADOS FINALES INTER ORGANIZACIONES</strong></em></td>
  </tr>
  <tr>
    <td>Nº de organizaciones participantes</td>
    <td class="centrado">Org.</td>
    <td class="derecha">140</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de concursos</td>
    <td class="centrado">Concurso</td>
    <td class="derecha">20</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>CONCURSOS ENTRE ANIMADORES RURALES</strong></em></td>
  </tr>
  <tr>
    <td>Nº de concursos entre animadores rurales</td>
    <td class="centrado">Concurso</td>
    <td class="derecha">20</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de animadores rurales participantes</td>
    <td class="centrado">Persona</td>
    <td class="derecha">140</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Mujeres participantes en concursos de animadores rurales</td>
    <td class="centrado">Persona</td>
    <td class="derecha">42</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="11">Actividad 2: Capacitación de Campesino a Campesino</td>
    <td colspan="5"><strong><em>ASISTENCIA TECNICA DE CAMPESINO A CAMPESINO</em></strong></td>
  </tr>
  <tr>
    <td>Organizaciones que contratan Asistencia Técnica de Campesino a Campesino</td>
    <td class="centrado">Org.</td>
    <td class="derecha">140</td>
    <td class="derecha"><? echo number_format($pgrn_sat);?></td>
    <td class="derecha"><? echo number_format(($pgrn_sat/140)*100);?></td>
  </tr>
  <tr>
    <td>Nº de Contratos de Asistencia Tecnica de Campesino a Campesino</td>
    <td class="centrado">Contrato</td>
    <td class="derecha">280</td>
    <td class="derecha"><? echo number_format($pgrn_contrato_sat);?></td>
    <td class="derecha"><? echo number_format(($pgrn_contrato_sat/280)*100);?></td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>OFERENTES DE SERVICIOS DE ASISTENCIA TECNICA</strong></em></td>
  </tr>
  <tr>
    <td>Nº de oferentes que brindan SAT</td>
    <td class="centrado">Persona</td>
    <td class="derecha">242</td>
    <td class="derecha"><? echo number_format($oferente_pgrn);?></td>
    <td class="derecha"><? echo number_format(($oferente_pgrn/242)*100);?></td>
  </tr>
  <tr>
    <td>Nº de oferentes de SAT que son campesinos o campesinas</td>
    <td class="centrado">Persona</td>
    <td class="derecha">242</td>
    <td class="derecha"><? echo number_format($oferente_campesino_pgrn);?></td>
    <td class="derecha"><? echo number_format(($oferente_campesino_pgrn/242)*100);?></td>
  </tr>
  <tr>
    <td>Nº de oferentes de SAT campesinas mujeres</td>
    <td class="centrado">Persona</td>
    <td class="derecha">70</td>
    <td class="derecha"><? echo number_format($oferente_mujer);?></td>
    <td class="derecha"><? echo number_format(($oferente_mujer/70)*100);?></td>
  </tr>
  <tr>
    <td colspan="5"><strong><em>CAPACITACION MEDIANTE VISITAS GUIADAS O GIRAS DE INTERCAMBIO</em></strong></td>
  </tr>
  <tr>
    <td>Nº de Visitas Guiadas o Giras de Intercambio</td>
    <td class="centrado">Evento</td>
    <td class="derecha">280</td>
    <td class="derecha"><? echo number_format($vg_mrn);?></td>
    <td class="derecha"><? echo number_format(($vg_mrn/280)*100);?></td>
  </tr>
  <tr>
    <td>Nº de participantes</td>
    <td class="centrado">Persona</td>
    <td class="derecha">2,800</td>
    <td class="derecha"><? echo number_format($participante_vg_mrn);?></td>
    <td class="derecha"><? echo number_format(($participante_vg_mrn/2800)*100);?></td>
  </tr>
  <tr>
    <td>Nº de participantes Mujeres</td>
    <td class="centrado">Persona</td>
    <td class="derecha">840</td>
    <td class="derecha"><? echo number_format($mujeres_vg_mrn);?></td>
    <td class="derecha"><? echo number_format(($mujeres_vg_mrn/840)*100);?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="23%">RESUMEN NARATIVO</td>
    <td width="26%">INDICADORES VERIFICABLES</td>
    <td width="8%">UNIDAD DE MEDIDA</td>
    <td width="8%">META </td>
    <td width="8%">EJECUTADO</td>
    <td width="9%">%</td>
  </tr>
  <tr>
    <td rowspan="16">Resultado 2: Organizaciones campesinas y microempresas rurales contratan servicios de asistencia técnica y acceden a los mercados en condiciones competitivas</td>
    <td colspan="5"><strong><em>ORGANIZACIONES Y FAMILIAS DESARROLLANDO NEGOCIOS RURALES</em></strong></td>
  </tr>
  <tr>
    <td>Nº de familias que acceden a servicios de asistencia técnica cofinanciada</td>
    <td class="centrado">Familia</td>
    <td class="derecha">13,151</td>
    <td class="derecha"><? echo number_format($familias_pdn_total);?></td>
    <td class="derecha"><? echo number_format(($familias_pdn_total/13151)*100);?></td>
  </tr>
  <tr>
    <td>Organizaciones campesinas que se presentaron al CLAR</td>
    <td class="centrado">Org.</td>
    <td class="derecha">990</td>
    <td class="derecha"><? echo number_format($pdn_clar);?></td>
    <td class="derecha"><? echo number_format(($pdn_clar/990)*100);?></td>
  </tr>
  <tr>
    <td>Organizaciones campesinas y microempresas seleccionadas por el CLAR gestionando negocios rurales</td>
    <td class="centrado">PDN</td>
    <td class="derecha">935</td>
    <td class="derecha"><? echo number_format($pdn_aprobado);?></td>
    <td class="derecha"><? echo number_format(($pdn_aprobado/935)*100);?></td>
  </tr>
  <tr>
    <td> - Nº de Organizaciones campesinas gestionando negocios</td>
    <td class="centrado">Org.</td>
    <td class="derecha">600</td>
    <td class="derecha"><? echo number_format($pdn_org);?></td>
    <td class="derecha"><? echo number_format(($pdn_org/600)*100);?></td>
  </tr>
  <tr>
    <td>- Nº de Microempresas gestionando negocios</td>
    <td class="centrado">Micr.</td>
    <td class="derecha">45</td>
    <td class="derecha"><? echo number_format($pdn_microempresa);?></td>
    <td class="derecha"><? echo number_format(($pdn_microempresa/45)*100);?></td>
  </tr>
  <tr>
    <td>Nº de personas que acceden a servicios de asistencia técnica cofinanciada</td>
    <td class="centrado">Persona</td>
    <td class="derecha">13,500</td>
    <td class="derecha"><? echo number_format($participante_pdn);?></td>
    <td class="derecha"><? echo number_format(($participante_pdn/13500)*100);?></td>
  </tr>
  <tr>
    <td> - Mujeres campesinas</td>
    <td class="centrado">Persona</td>
    <td class="derecha">5,800</td>
    <td class="derecha"><? echo number_format($mujeres_pdn);?></td>
    <td class="derecha"><? echo number_format(($mujeres_pdn/5800)*100);?></td>
  </tr>
  <tr>
    <td>- Varones campesinos</td>
    <td class="centrado">Persona</td>
    <td class="derecha">5,475</td>
    <td class="derecha"><? echo number_format($hombres_pdn);?></td>
    <td class="derecha"><? echo number_format(($hombres_pdn/5475)*100);?></td>
  </tr>
  <tr>
    <td>- Mujeres microempresarias</td>
    <td class="centrado">Persona</td>
    <td class="derecha">135</td>
    <td class="derecha"><? echo number_format($mujeres_micro);?></td>
    <td class="derecha"><? echo number_format(($mujeres_micro/135)*100);?></td>
  </tr>
  <tr>
    <td>- Varones microempresarios</td>
    <td class="centrado">Persona</td>
    <td class="derecha">90</td>
    <td class="derecha"><? echo number_format($hombres_micro);?></td>
    <td class="derecha"><? echo number_format(($hombres_micro/90)*100);?></td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>MERCADO DE SERVICIOS DE ASISTENCIA TECNICA PRIVADA FORTALECIDO</strong></em></td>
  </tr>
  <tr>
    <td>Nº de Familias que contratan servicios de asistencia tecnica con sus propios recursos</td>
    <td class="centrado">%</td>
    <td class="derecha">30</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>PROMOCION COMERCIAL PARA PROMOVER LA COMPETITIVIDAD</strong></em></td>
  </tr>
  <tr>
    <td>Redes de proveedores / clientes</td>
    <td class="centrado">Red</td>
    <td class="derecha">10</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Organizaciones que conforman redes de proveedores / clientes</td>
    <td class="centrado">Org.</td>
    <td class="derecha">30</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><em><strong>PRODUCTOS DEL R2</strong></em></td>
  </tr>
  <tr>
    <td>Actividad 1.- Cofinanciacion de Inversiones para favorecer el desarrollo local </td>
    <td>Inversiones para favorecer el desarrollo local</td>
    <td class="centrado">IDL</td>
    <td class="derecha">37</td>
    <td class="derecha"><? echo number_format($idl);?></td>
    <td class="derecha"><? echo number_format(($idl/37)*100);?></td>
  </tr>
  <tr>
    <td rowspan="4">Actividad 2.- Cofinanciamiento de asistencia técnica en temas técnico - Productivos y en gestión de negocios</td>
    <td colspan="5"><em><strong>ASISTENCIA TECNICA PARA PLANES DE NEGOCIO (PDN) APROBADOS EN EL CLAR</strong></em></td>
  </tr>
  <tr>
    <td>Nº de contratos de Asistencia Tecnica cofinanciada</td>
    <td class="centrado">Contrato</td>
    <td class="derecha">1,346</td>
    <td class="derecha"><? echo number_format($sat_pdn);?></td>
    <td class="derecha"><? echo number_format(($sat_pdn/1346)*100);?></td>
  </tr>
  <tr>
    <td>Nº de oferentes que brindan SAT en PDN</td>
    <td class="centrado">Persona</td>
    <td class="derecha">1,122</td>
    <td class="derecha"><? echo number_format($oferente_pdn);?></td>
    <td class="derecha"><? echo number_format(($oferente_pdn/1122)*100);?></td>
  </tr>
  <tr>
    <td>Nº de oferentes de SAT campesinas mujeres</td>
    <td class="centrado">Persona</td>
    <td class="derecha">280</td>
    <td class="derecha"><? echo number_format($oferente_pdn_mujer);?></td>
    <td class="derecha"><? echo number_format(($oferente_pdn_mujer/280)*100);?></td>
  </tr>
  <tr>
    <td rowspan="8">Actividad 3.- Desarrollo de capacidades locales para el fortalecimiento de negocios</td>
    <td colspan="5"><strong><em>CAPACITACION MEDIANTE VISITAS GUIADAS O GIRAS DE INTERCAMBIO</em></strong></td>
  </tr>
  <tr>
    <td>Nº de visitas guiadas o giras de intercambio</td>
    <td class="centrado">Evento</td>
    <td class="derecha">935</td>
    <td class="derecha"><? echo number_format($vg_pdn);?></td>
    <td class="derecha"><? echo number_format(($vg_pdn/935)*100);?></td>
  </tr>
  <tr>
    <td>Nº de participantes</td>
    <td class="centrado">Persona</td>
    <td class="derecha">9,350</td>
    <td class="derecha"><? echo number_format($participante_vg_pdn);?></td>
    <td class="derecha"><? echo number_format(($participante_vg_pdn/9350)*100);?></td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>AUSPICIO A LA REALIZACION DE EVENTOS DE PROMOCION COMERCIAL</strong></em></td>
  </tr>
  <tr>
    <td>Nº de auspicio</td>
    <td class="centrado">Auspicio</td>
    <td class="derecha">238</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><em><strong>APOYO A LA PARTICIPACIÓN DE USUARIOS EN EVENTOS COMERCIALES</strong></em></td>
  </tr>
  <tr>
    <td>Nº de eventos donde participaron usuarios del PDN</td>
    <td class="centrado">Evento</td>
    <td class="derecha">1,870</td>
    <td class="derecha"><? echo number_format($pf_pdn);?></td>
    <td class="derecha"><? echo number_format(($pf_pdn/1870)*100);?></td>
  </tr>
  <tr>
    <td>Nº de socios de organizaciones participantes</td>
    <td class="centrado">Familia</td>
    <td class="derecha">5,610</td>
    <td class="derecha"><? echo number_format($participante_pf_pdn);?></td>
    <td class="derecha"><? echo number_format(($participante_pf_pdn/5610)*100);?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="23%">RESUMEN NARATIVO</td>
    <td width="26%">INDICADORES VERIFICABLES</td>
    <td width="8%">UNIDAD DE MEDIDA</td>
    <td width="8%">META </td>
    <td width="8%">EJECUTADO</td>
    <td width="9%">%</td>
  </tr>
  <tr>
    <td rowspan="9">Resultado 3: Familias rurales acceden a servicios financieros formales y se benefician de nuevos productos financieros</td>
    <td colspan="5"><em><strong>MUJERES RURALES POBRES VINCULADAS AL SISTEMA FINANCIERO FORMAL</strong></em></td>
  </tr>
  <tr>
    <td>Nº de Mujeres que aperturan cuentas de ahorro</td>
    <td class="centrado">Cuentas</td>
    <td class="derecha">9,240</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>% de Mujeres que realizan al menos 4 operaciones por año</td>
    <td class="centrado">%</td>
    <td class="derecha">70</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>% de Mujeres que obtienen crédito de IFIS y otros</td>
    <td class="centrado">%</td>
    <td class="derecha">30</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><strong><em>FAMILIAS CAMPESINAS BENEFICIARIAS CON INNOVACIONES FINANCIERAS</em></strong></td>
  </tr>
  <tr>
    <td>Nº de Innovaciones Financieras que favorecen a pobres rurales</td>
    <td class="centrado">Productos</td>
    <td class="derecha">2</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de pobladores que contratan seguros de vida anualmente</td>
    <td class="centrado">Persona</td>
    <td class="derecha">11,400</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de mujeres que contratan seguros de vida anualmente</td>
    <td class="centrado">Persona</td>
    <td class="derecha">9,500</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de municipios y otras entidades que cofinancian Seguros de Vida</td>
    <td class="centrado">Institución</td>
    <td class="derecha">50</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="6"><em><strong>PRODUCTOS DEL R3</strong></em></td>
  </tr>
  <tr>
    <td rowspan="5">Actividad 1.- Inclusión de familias rurales pobres en el mercado formal de servicios financieros</td>
    <td>Grupos de Mujeres Ahorristas</td>
    <td class="centrado">Grupo</td>
    <td class="derecha">462</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de Talleres de educación Financiera</td>
    <td class="centrado">Taller</td>
    <td class="derecha">924</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de grupos de autoayuda para el ahorro</td>
    <td class="centrado">Grupo</td>
    <td class="derecha">924</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Nº de Giras de intercambio</td>
    <td class="centrado">Gira</td>
    <td class="derecha">462</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Concursos de resultados entre grupos de ahorristas</td>
    <td class="centrado">Concurso</td>
    <td class="derecha">21</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td width="23%">RESUMEN NARATIVO</td>
    <td width="26%">INDICADORES VERIFICABLES</td>
    <td width="8%">UNIDAD DE MEDIDA</td>
    <td width="8%">META </td>
    <td width="8%">EJECUTADO</td>
    <td width="9%">%</td>
  </tr>
  <tr>
    <td colspan="6"><em><strong>Resultado 4.- Impactos en la valorizacion de los activos intangibles de la población local</strong></em></td>
  </tr>
  <tr>
    <td colspan="6"><em><strong>PRODUCTOS DEL R4</strong></em></td>
  </tr>
  <tr>
    <td rowspan="7">Actividad 1: Puesta en valor de activos intangibles</td>
    <td colspan="5"><em><strong>IDENTIFICACION, RECUPERACION, PRESERVACION Y DESARROLLO DEL PATRIMONIO CULTURAL</strong></em></td>
  </tr>
  <tr>
    <td>Conocimientos locales registrados y compartidos</td>
    <td class="centrado">Registros</td>
    <td class="derecha">10</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Sistematizción de experiencias y/o estudios de casos</td>
    <td class="centrado">Documentos</td>
    <td class="derecha">8</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Iniciativas de revalorización del Patrimonio cultural</td>
    <td class="centrado">Eventos</td>
    <td class="derecha">12</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Historias de vida y/o testimonios</td>
    <td class="centrado">Documentos</td>
    <td class="derecha">7</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Encuentros del conocimiento</td>
    <td class="centrado">Eventos</td>
    <td class="derecha">7</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Divulgación de conocimientos locales y activos culturales</td>
    <td class="centrado">Publicación</td>
    <td class="derecha">7</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="2">Actividad 2: Fortalecimiento de capacidades</td>
    <td>Rutas de Aprendizaje</td>
    <td class="centrado">Eventos</td>
    <td class="derecha">7</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Capacitacion talentos locales</td>
    <td class="centrado">Eventos</td>
    <td class="derecha">14</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="3">Actividad 3: Comunicación y Difusión</td>
    <td>Publicaciones</td>
    <td class="centrado">Documentos</td>
    <td class="derecha">5</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Producción audiovisual</td>
    <td class="centrado">Video/CD</td>
    <td class="derecha">4</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
  <tr>
    <td>Diseño y operación de plataforma virtual de talentos locales</td>
    <td class="centrado">Modulo</td>
    <td class="derecha">1</td>
    <td class="derecha">&nbsp;</td>
    <td class="derecha">&nbsp;</td>
  </tr>
</table>
<br/>
<div class="capa">
	<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
</body>
</html>
