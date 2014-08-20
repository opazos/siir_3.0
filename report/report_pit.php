<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();
/*E decidido armar las consultas a este nivel asi que aqui vamos*/

//1.- Nº de PIT por Oficina Local
$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
org_ficha_taz.cod_dependencia=002";
$result=mysql_query($sql) or die (mysql_error());
$pit_1=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
org_ficha_taz.cod_dependencia=003";
$result=mysql_query($sql) or die (mysql_error());
$pit_2=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
org_ficha_taz.cod_dependencia=004";
$result=mysql_query($sql) or die (mysql_error());
$pit_3=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
org_ficha_taz.cod_dependencia=005";
$result=mysql_query($sql) or die (mysql_error());
$pit_4=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
org_ficha_taz.cod_dependencia=006";
$result=mysql_query($sql) or die (mysql_error());
$pit_5=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
org_ficha_taz.cod_dependencia=007";
$result=mysql_query($sql) or die (mysql_error());
$pit_6=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
org_ficha_taz.cod_dependencia=008";
$result=mysql_query($sql) or die (mysql_error());
$pit_7=mysql_num_rows($result);

$total_pit=$pit_1+$pit_2+$pit_3+$pit_4+$pit_5+$pit_6+$pit_7;

//2.- Nº de familias por Oficina Local (Solo PIT's)
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
WHERE org_ficha_usuario.titular=1 AND
pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
pit_bd_user_iniciativa.estado=1 AND
org_ficha_organizacion.cod_dependencia=002";
$result=mysql_query($sql) or die (mysql_error());
$fam_1=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
WHERE org_ficha_usuario.titular=1 AND
pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
pit_bd_user_iniciativa.estado=1 AND
org_ficha_organizacion.cod_dependencia=003";
$result=mysql_query($sql) or die (mysql_error());
$fam_2=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
WHERE org_ficha_usuario.titular=1 AND
pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
pit_bd_user_iniciativa.estado=1 AND
org_ficha_organizacion.cod_dependencia=004";
$result=mysql_query($sql) or die (mysql_error());
$fam_3=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
WHERE org_ficha_usuario.titular=1 AND
pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
pit_bd_user_iniciativa.estado=1 AND
org_ficha_organizacion.cod_dependencia=005";
$result=mysql_query($sql) or die (mysql_error());
$fam_4=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
WHERE org_ficha_usuario.titular=1 AND
pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
pit_bd_user_iniciativa.estado=1 AND
org_ficha_organizacion.cod_dependencia=006";
$result=mysql_query($sql) or die (mysql_error());
$fam_5=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
WHERE org_ficha_usuario.titular=1 AND
pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
pit_bd_user_iniciativa.estado=1 AND
org_ficha_organizacion.cod_dependencia=007";
$result=mysql_query($sql) or die (mysql_error());
$fam_6=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
WHERE org_ficha_usuario.titular=1 AND
pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
pit_bd_user_iniciativa.estado=1 AND
org_ficha_organizacion.cod_dependencia=008";
$result=mysql_query($sql) or die (mysql_error());
$fam_7=mysql_num_rows($result);

$total_fam=$fam_1+$fam_2+$fam_3+$fam_4+$fam_5+$fam_6+$fam_7;

//3.- Nº de familias por Oficina Local (PDN sueltos)
$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.n_contrato<>0 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=002";
$result=mysql_query($sql) or die (mysql_error());
$fpdn_1=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.n_contrato<>0 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=003";
$result=mysql_query($sql) or die (mysql_error());
$fpdn_2=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.n_contrato<>0 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=004";
$result=mysql_query($sql) or die (mysql_error());
$fpdn_3=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.n_contrato<>0 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=005";
$result=mysql_query($sql) or die (mysql_error());
$fpdn_4=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.n_contrato<>0 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=006";
$result=mysql_query($sql) or die (mysql_error());
$fpdn_5=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.n_contrato<>0 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=007";
$result=mysql_query($sql) or die (mysql_error());
$fpdn_6=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_usuario.n_documento
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = org_ficha_usuario.n_documento
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE org_ficha_usuario.titular=1 AND
pit_bd_user_iniciativa.estado=1 AND
pit_bd_ficha_pdn.n_contrato<>0 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=008";
$result=mysql_query($sql) or die (mysql_error());
$fpdn_7=mysql_num_rows($result);

$total_fpdn=$fpdn_1+$fpdn_2+$fpdn_3+$fpdn_4+$fpdn_5+$fpdn_6+$fpdn_7;

//3.5.- Porcentaje familias por Oficina local
$tfam1=$fam_1+$fpdn_1;
$tfam2=$fam_2+$fpdn_2;
$tfam3=$fam_3+$fpdn_3;
$tfam4=$fam_4+$fpdn_4;
$tfam5=$fam_5+$fpdn_5;
$tfam6=$fam_6+$fpdn_6;
$tfam7=$fam_7+$fpdn_7;

$total_tfam=$tfam1+$tfam2+$tfam3+$tfam4+$tfam5+$tfam6+$tfam7;

$pfam1=($tfam1/2724)*100;
$pfam2=($tfam2/1893)*100;
$pfam3=($tfam3/3367)*100;
$pfam4=($tfam4/3719)*100;
$pfam5=($tfam5/3567)*100;
$pfam6=($tfam6/4096)*100;
$pfam7=($tfam7/3394)*100;

$total_pfam=($total_tfam/22730)*100;

//4.- Nº de distritos atendidos
$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=002";
$result=mysql_query($sql) or die (mysql_error());
$dist_1=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=003";
$result=mysql_query($sql) or die (mysql_error());
$dist_2=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=004";
$result=mysql_query($sql) or die (mysql_error());
$dist_3=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=005";
$result=mysql_query($sql) or die (mysql_error());
$dist_4=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=006";
$result=mysql_query($sql) or die (mysql_error());
$dist_5=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=007";
$result=mysql_query($sql) or die (mysql_error());
$dist_6=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=008";
$result=mysql_query($sql) or die (mysql_error());
$dist_7=mysql_num_rows($result);

$total_dist=$dist_1+$dist_2+$dist_3+$dist_4+$dist_5+$dist_6+$dist_7;

//4.5.- Porcentaje de distritos por oficina local
$pdist1=($dist_1/26)*100;
$pdist2=($dist_2/13)*100;
$pdist3=($dist_3/13)*100;
$pdist4=($dist_4/11)*100;
$pdist5=($dist_5/17)*100;
$pdist6=($dist_6/10)*100;
$pdist7=($dist_7/29)*100;

$total_pdist=($total_dist/119)*100;

//5.- Nº de Planes de Gestion por Oficina Local
$sql="SELECT pit_bd_ficha_mrn.cod_mrn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=002";
$result=mysql_query($sql) or die (mysql_error());
$mrn_1=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_mrn.cod_mrn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=003";
$result=mysql_query($sql) or die (mysql_error());
$mrn_2=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_mrn.cod_mrn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=004";
$result=mysql_query($sql) or die (mysql_error());
$mrn_3=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_mrn.cod_mrn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=005";
$result=mysql_query($sql) or die (mysql_error());
$mrn_4=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_mrn.cod_mrn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=006";
$result=mysql_query($sql) or die (mysql_error());
$mrn_5=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_mrn.cod_mrn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=007";
$result=mysql_query($sql) or die (mysql_error());
$mrn_6=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_mrn.cod_mrn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=008";
$result=mysql_query($sql) or die (mysql_error());
$mrn_7=mysql_num_rows($result);

$total_mrn=$mrn_1+$mrn_2+$mrn_3+$mrn_4+$mrn_5+$mrn_6+$mrn_7;

//6.- Nº de Planes de Negocio por Oficina Local
$sql="SELECT pit_bd_ficha_pdn.cod_pdn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE 
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
/*pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND*/
org_ficha_organizacion.cod_dependencia='002'";
$result=mysql_query($sql) or die (mysql_error());
$pdn_1=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pdn.cod_pdn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE 
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
/*pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND*/
org_ficha_organizacion.cod_dependencia='003'";
$result=mysql_query($sql) or die (mysql_error());
$pdn_2=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pdn.cod_pdn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE 
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
/*pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND*/
org_ficha_organizacion.cod_dependencia='004'";
$result=mysql_query($sql) or die (mysql_error());
$pdn_3=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pdn.cod_pdn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE 
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
/*pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND*/
org_ficha_organizacion.cod_dependencia='005'";
$result=mysql_query($sql) or die (mysql_error());
$pdn_4=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pdn.cod_pdn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE 
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
/*pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND*/
org_ficha_organizacion.cod_dependencia='006'";
$result=mysql_query($sql) or die (mysql_error());
$pdn_5=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pdn.cod_pdn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE 
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
/*pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND*/
org_ficha_organizacion.cod_dependencia='007'";
$result=mysql_query($sql) or die (mysql_error());
$pdn_6=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_pdn.cod_pdn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE 
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
/*pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND*/
org_ficha_organizacion.cod_dependencia='008'";
$result=mysql_query($sql) or die (mysql_error());
$pdn_7=mysql_num_rows($result);

$total_pdn=$pdn_1+$pdn_2+$pdn_3+$pdn_4+$pdn_5+$pdn_6+$pdn_7;

//7.- Nº de Distritos Nivel de Pobreza 1
$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=002 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$npa_1=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=003 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$npa_2=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=004 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$npa_3=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=005 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$npa_4=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=006 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$npa_5=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=007 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$npa_6=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=008 AND
sys_bd_distrito.nivel_pobreza=1";
$result=mysql_query($sql) or die (mysql_error());
$npa_7=mysql_num_rows($result);

//8.- Nº de Distritos con Nivel de Pobreza 2
$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=002 AND
sys_bd_distrito.nivel_pobreza=2";
$result=mysql_query($sql) or die (mysql_error());
$npb_1=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=003 AND
sys_bd_distrito.nivel_pobreza=2";
$result=mysql_query($sql) or die (mysql_error());
$npb_2=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=004 AND
sys_bd_distrito.nivel_pobreza=2";
$result=mysql_query($sql) or die (mysql_error());
$npb_3=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=005 AND
sys_bd_distrito.nivel_pobreza=2";
$result=mysql_query($sql) or die (mysql_error());
$npb_4=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=006 AND
sys_bd_distrito.nivel_pobreza=2";
$result=mysql_query($sql) or die (mysql_error());
$npb_5=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=007 AND
sys_bd_distrito.nivel_pobreza=2";
$result=mysql_query($sql) or die (mysql_error());
$npb_6=mysql_num_rows($result);

$sql="SELECT DISTINCT org_ficha_organizacion.cod_dist
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia=008 AND
sys_bd_distrito.nivel_pobreza=2";
$result=mysql_query($sql) or die (mysql_error());
$npb_7=mysql_num_rows($result);

//9.- Distritos con nivel de pobreza 1 y 2 por oficina local
$npc_1=$npa_1+$npb_1;
$npc_2=$npa_2+$npb_2;
$npc_3=$npa_3+$npb_3;
$npc_4=$npa_4+$npb_4;
$npc_5=$npa_5+$npb_5;
$npc_6=$npa_6+$npb_6;
$npc_7=$npa_7+$npb_7;

//10.- Nº de IDL a atender por Oficina Local
$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
pit_bd_ficha_idl.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia=002";
$result=mysql_query($sql) or die (mysql_error());
$idl1=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
pit_bd_ficha_idl.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia=003";
$result=mysql_query($sql) or die (mysql_error());
$idl2=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
pit_bd_ficha_idl.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia=004";
$result=mysql_query($sql) or die (mysql_error());
$idl3=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
pit_bd_ficha_idl.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia=005";
$result=mysql_query($sql) or die (mysql_error());
$idl4=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
pit_bd_ficha_idl.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia=006";
$result=mysql_query($sql) or die (mysql_error());
$idl5=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
pit_bd_ficha_idl.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia=007";
$result=mysql_query($sql) or die (mysql_error());
$idl6=mysql_num_rows($result);

$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
pit_bd_ficha_idl.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia=008";
$result=mysql_query($sql) or die (mysql_error());
$idl7=mysql_num_rows($result);

$total_idl=$idl1+$idl2+$idl3+$idl4+$idl5+$idl6+$idl7+$idl8;

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>::Vista Preeliminar::</title>
<!-- cargamos el estilo de la pagina -->
<link href="../stylesheets/print.css" rel="stylesheet" type="text/css">
<link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
<style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
  @page { size: A4 landscape; }
</style>
<!-- Fin -->

<!-- CARGAMOS EL PLUGINS -->
<!-- CARGAMOS EL JQUERY -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>


<!-- GENERAMOS LA GRAFICA -->

<!-- Grafico 1 -->
<script type="text/javascript">
var chart;
$(document).ready(function() 
{
//GRAFICO 1	
chart = new Highcharts.Chart({
chart: {
renderTo: 'container_1',
defaultSeriesType: 'bar'
},
title: {
text: 'Nº de Familias Atendidas por Oficina Local'
},
subtitle: {
text: 'Fuente : NEC PDSS II'
},
xAxis: {
categories: ['Quequeña', 'Ichupampa', 'Paucarcolla', 'Saman', 'Oropesa','Paucartambo','Tamburco'],
title: {
text: null
}
},
yAxis: {
min: 0,
title: {
text: 'Nº de Familias',
align: 'high'
}
},
tooltip: {
formatter: function() {
return ''+
  this.series.name +': '+ this.y +' Familias';
}
},
plotOptions: {
bar: {
dataLabels: {
enabled: true
            }
      }
            },
            
legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
         },           

credits: {
enabled: false
},
series: 
[
{
name: 'Meta',
data: [2724,1893,3367,3719,3567,4096,3394]
},	
{	
name: 'Familias atendidas',
data: [<? echo $tfam1;?>, <? echo $tfam2;?>, <? echo $tfam3;?>, <? echo $tfam4;?>, <? echo $tfam5;?>,<? echo $tfam6;?>,<? echo $tfam7;?>]
}	
]
});
});
</script>

<!-- Grafico 2 -->
<script type="text/javascript">
var chart;
$(document).ready(function() 
{
//GRAFICO 1	
chart = new Highcharts.Chart({
chart: {
renderTo: 'container_2',
defaultSeriesType: 'bar'
},
title: {
text: 'Nº de Iniciativas aprobadas por Oficina Local'
},
subtitle: {
text: 'Fuente : NEC PDSS II'
},
xAxis: {
categories: ['Quequeña', 'Ichupampa', 'Paucarcolla', 'Saman', 'Oropesa','Paucartambo','Tamburco'],
title: {
text: null
}
},
yAxis: {
min: 0,
title: {
text: 'Nº de Iniciativas',
align: 'high'
}
},
tooltip: {
formatter: function() {
return ''+
  this.series.name +': '+ this.y +' Iniciativas';
}
},
plotOptions: {
bar: {
dataLabels: {
enabled: true
            }
      }
            },
            
legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
         },           

credits: {
enabled: false
},
series: 
[
{	
name: 'Nº PITs',
data: [<? echo $pit_1;?>, <? echo $pit_2;?>, <? echo $pit_3;?>, <? echo $pit_4;?>, <? echo $pit_5;?>,<? echo $pit_6;?>,<? echo $pit_7;?>]
},
{
name: 'Nº PGRNs',
data: [<? echo $mrn_1;?>,<? echo $mrn_2;?>,<? echo $mrn_3;?>,<? echo $mrn_4;?>,<? echo $mrn_5;?>,<? echo $mrn_6;?>,<? echo $mrn_7;?>]
},
{
name: 'Nº PDNs',
data: [<? echo $pdn_1;?>,<? echo $pdn_2;?>,<? echo $pdn_3;?>,<? echo $pdn_4;?>,<? echo $pdn_5;?>,<? echo $pdn_6;?>,<? echo $pdn_7;?>]
}			
]
});
});
</script>

<!-- Grafico 3 -->
<script type="text/javascript">
var chart;
$(document).ready(function() 
{
//GRAFICO 1	
chart = new Highcharts.Chart({
chart: {
renderTo: 'container_3',
defaultSeriesType: 'column'
},
title: {
text: 'Nº de Distritos atendidos por Oficina Local'
},
subtitle: {
text: 'Fuente : NEC PDSS II'
},
xAxis: {
categories: ['Quequeña', 'Ichupampa', 'Paucarcolla', 'Saman', 'Oropesa','Paucartambo','Tamburco'],
title: {
text: null
}
},
yAxis: {
min: 0,
title: {
text: 'Nº de distritos',
align: 'high'
}
},
tooltip: {
formatter: function() {
return ''+
  this.series.name +': '+ this.y +' distritos';
}
},
plotOptions: {
column: {
dataLabels: {
enabled: true
            }
      }
            },
            
legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
         },           

credits: {
enabled: false
},
series: 
[
{
name: 'Meta',
data: [26,13,13,11,17,10,29]
},
{	
name: 'Distritos atendidos',
data: [<? echo $dist_1;?>, <? echo $dist_2;?>, <? echo $dist_3;?>, <? echo $dist_4;?>, <? echo $dist_5;?>,<? echo $dist_6;?>,<? echo $dist_7;?>]
},
{
name: 'Distritos Quintil 1 y 2',
data: [<? echo $npc_1;?>,<? echo $npc_2;?>,<? echo $npc_3;?>,<? echo $npc_4;?>,<? echo $npc_5;?>,<? echo $npc_6;?>,<? echo $npc_7;?>]
}			
]
});
});
</script>
<!-- FIN DE CONFIGURACION -->
</head>
<body>
<!-- Cargo los plugins para la grafica -->
<script type="text/javascript" src="../plugins/grafica/js/highcharts.js"></script>
<script type="text/javascript" src="../plugins/grafica/js/modules/exporting.js"></script>
<!-- Fin de los Plugins para la grafica -->


<div class="capa txt_titulo">INFORMACION SOBRE PLANES DE INVERSION TERRITORIAL</div>
<br/>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="txt_titulo centrado">
    <td rowspan="2">OFICINA LOCAL</td>
    <td rowspan="2">Nº PIT's</td>
    <td colspan="3">Nº DE FAMILIAS</td>
    <td colspan="3">Nº DISTRITOS</td>
    <td rowspan="2">Nº PGRN</td>
    <td rowspan="2">Nº PDN</td>
    <td rowspan="2">Nº IDL</td>
  </tr>
  <tr class="txt_titulo centrado">
    <td>ATENDIDO</td>
    <td>META</td>
    <td>%</td>
    <td>ATENDIDO</td>
    <td>META</td>
    <td>%</td>
  </tr>
  <tr>
    <td>QUEQUEÑA</td>
    <td class="derecha"><? echo number_format($pit_1);?></td>
    <td class="derecha"><? echo number_format($tfam1);?></td>
    <td class="derecha"><? echo number_format(2724);?></td>
    <td class="derecha"><? echo number_format($pfam1);?></td>
    <td class="derecha"><? echo number_format($dist_1);?></td>
    <td class="derecha">26</td>
    <td class="derecha"><? echo number_format($pdist1);?></td>
    <td class="derecha"><? echo number_format($mrn_1);?></td>
    <td class="derecha"><? echo number_format($pdn_1);?></td>
    <td class="derecha"><? echo number_format($idl1);?></td>
  </tr>
  <tr>
    <td>ICHUPAMPA</td>
    <td class="derecha"><? echo number_format($pit_2);?></td>
    <td class="derecha"><? echo number_format($tfam2);?></td>
    <td class="derecha"><? echo number_format(1893);?></td>
    <td class="derecha"><? echo number_format($pfam2);?></td>
    <td class="derecha"><? echo number_format($dist_2);?></td>
    <td class="derecha">13</td>
    <td class="derecha"><? echo number_format($pdist2);?></td>
    <td class="derecha"><? echo number_format($mrn_2);?></td>
    <td class="derecha"><? echo number_format($pdn_2);?></td>
    <td class="derecha"><? echo number_format($idl2);?></td>    
  </tr>
  <tr>
    <td>PAUCARCOLLA</td>
    <td class="derecha"><? echo number_format($pit_3);?></td>
    <td class="derecha"><? echo number_format($tfam3);?></td>
    <td class="derecha"><? echo number_format(3367);?></td>
    <td class="derecha"><? echo number_format($pfam3);?></td>
    <td class="derecha"><? echo number_format($dist_3);?></td>
    <td class="derecha">13</td>
    <td class="derecha"><? echo number_format($pdist3);?></td>
    <td class="derecha"><? echo number_format($mrn_3);?></td>
    <td class="derecha"><? echo number_format($pdn_3);?></td>
    <td class="derecha"><? echo number_format($idl3);?></td>    
  </tr>
  <tr>
    <td>SAMAN</td>
    <td class="derecha"><? echo number_format($pit_4);?></td>
    <td class="derecha"><? echo number_format($tfam4);?></td>
    <td class="derecha"><? echo number_format(3719);?></td>
    <td class="derecha"><? echo number_format($pfam4);?></td>
    <td class="derecha"><? echo number_format($dist_4);?></td>
    <td class="derecha">11</td>
    <td class="derecha"><? echo number_format($pdist4);?></td>
    <td class="derecha"><? echo number_format($mrn_4);?></td>
    <td class="derecha"><? echo number_format($pdn_4);?></td>
    <td class="derecha"><? echo number_format($idl4);?></td>    
  </tr>
  <tr>
    <td>OROPESA</td>
    <td class="derecha"><? echo number_format($pit_5);?></td>
    <td class="derecha"><? echo number_format($tfam5);?></td>
    <td class="derecha"><? echo number_format(3567);?></td>
    <td class="derecha"><? echo number_format($pfam5);?></td>
    <td class="derecha"><? echo number_format($dist_5);?></td>
    <td class="derecha">17</td>
    <td class="derecha"><? echo number_format($pdist5);?></td>
    <td class="derecha"><? echo number_format($mrn_5);?></td>
    <td class="derecha"><? echo number_format($pdn_5);?></td>
    <td class="derecha"><? echo number_format($idl5);?></td>    
  </tr>
  <tr>
    <td>PAUCARTAMBO</td>
    <td class="derecha"><? echo number_format($pit_6);?></td>
    <td class="derecha"><? echo number_format($tfam6);?></td>
    <td class="derecha"><? echo number_format(4096);?></td>
    <td class="derecha"><? echo number_format($pfam6);?></td>
    <td class="derecha"><? echo number_format($dist_6);?></td>
    <td class="derecha">10</td>
    <td class="derecha"><? echo number_format($pdist6);?></td>
    <td class="derecha"><? echo number_format($mrn_6);?></td>
    <td class="derecha"><? echo number_format($pdn_6);?></td>
    <td class="derecha"><? echo number_format($idl6);?></td>    
  </tr>
  <tr>
    <td>TAMBURCO</td>
    <td class="derecha"><? echo number_format($pit_7);?></td>
    <td class="derecha"><? echo number_format($tfam7);?></td>
    <td class="derecha"><? echo number_format(3394);?></td>
    <td class="derecha"><? echo number_format($pfam7);?></td>
    <td class="derecha"><? echo number_format($dist_7);?></td>
    <td class="derecha">29</td>
    <td class="derecha"><? echo number_format($pdist7);?></td>
    <td class="derecha"><? echo number_format($mrn_7);?></td>
    <td class="derecha"><? echo number_format($pdn_7);?></td>
    <td class="derecha"><? echo number_format($idl7);?></td>    
  </tr>
  <tr class="txt_titulo">
    <td>TOTAL</td>
    <td class="derecha"><? echo number_format($total_pit);?></td>
    <td class="derecha"><? echo number_format($total_tfam);?></td>
    <td class="derecha"><? echo number_format(22730);?></td>
    <td class="derecha"><? echo number_format($total_pfam);?></td>
    <td class="derecha"><? echo number_format($total_dist);?></td>
    <td class="derecha">119</td>
    <td class="derecha"><? echo number_format($total_pdist);?></td>
    <td class="derecha"><? echo number_format($total_mrn);?></td>
    <td class="derecha"><? echo number_format($total_pdn);?></td>
    <td class="derecha"><? echo number_format($total_idl);?></td>    
  </tr>
</table>

<p>
<br/>
</p>

<!-- GENERAMOS EL GRAFICO -->
<div id="container_1" style="width: 1000px; height: 400px; margin: 0 auto"></div>
<div id="container_2" style="width: 1000px; height: 600px; margin: 0 auto"></div>
<div id="container_3" style="width: 1000px; height: 400px; margin: 0 auto"></div>
<!-- FIN DEL GRAFICO -->
<p><br/></p>
<div class="capa">
	<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>

