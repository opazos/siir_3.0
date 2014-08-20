<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.f_termino, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	pit_bd_ficha_adenda.cod_tipo_iniciativa, 
	pit_bd_ficha_adenda.cod_iniciativa, 
	pit_bd_ficha_adenda.referencia, 
	pit_bd_ficha_adenda.n_meses, 
	pit_bd_ficha_adenda.total_pdss, 
	pit_bd_ficha_adenda.total_org, 
	pit_bd_ficha_adenda.f_termino AS f_termino_adenda, 
	pit_bd_ficha_adenda.n_atf, 
	pit_bd_ficha_adenda.n_solicitud, 
	pit_bd_ficha_adenda.f_desembolso, 
	pit_bd_ficha_adenda.comentario, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_taz.sector, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	(pit_bd_ficha_adenda.an_pdss+ 
	pit_bd_ficha_adenda.cif_pdss+ 
	pit_bd_ficha_adenda.at_pdss+ 
	pit_bd_ficha_adenda.vg_pdss+ 
	pit_bd_ficha_adenda.pf_pdss+ 
	pit_bd_ficha_adenda.idl_pdss) AS aporte_pdss, 
	(pit_bd_ficha_adenda.an_org+ 
	pit_bd_ficha_adenda.at_org+ 
	pit_bd_ficha_adenda.vg_org+ 
	pit_bd_ficha_adenda.pf_org+ 
	pit_bd_ficha_adenda.idl_org) AS aporte_org, 
	pit_bd_ficha_adenda.an_pdss, 
	pit_bd_ficha_adenda.cif_pdss, 
	pit_bd_ficha_adenda.at_pdss, 
	pit_bd_ficha_adenda.vg_pdss, 
	pit_bd_ficha_adenda.pf_pdss, 
	pit_bd_ficha_adenda.idl_pdss, 
	pit_bd_ficha_adenda.an_org, 
	pit_bd_ficha_adenda.at_org, 
	pit_bd_ficha_adenda.vg_org, 
	pit_bd_ficha_adenda.pf_org, 
	pit_bd_ficha_adenda.idl_org, 
	pit_bd_ficha_adenda.contenido, 
	pit_bd_ficha_adenda.tipo_plazo, 
	pit_bd_ficha_adenda.tipo_monto
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//2.- Obtengo los Prefijos
$proyecto="<strong>SIERRA SUR II</strong>";
$org="<strong>LA ORGANIZACION</strong>";
$orga="<strong>LAS ORGANIZACIONES</strong>";

//3.- Directiva del PIT
$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directiva_taz.cod_cargo_directivo=1 AND
org_ficha_directiva_taz.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directiva_taz.cod_cargo_directivo=3 AND
org_ficha_directiva_taz.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//4.- Obtengo los datos de la organizacion Hija *******************************************************************************************************************
if ($row['cod_tipo_iniciativa']==3)
{
	//1.- datos de la iniciativa
	$sql="SELECT org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org, 
	pit_bd_ficha_pit.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	pit_bd_ficha_pit.cod_pit
	FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pit.cod_ifi
	 INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_adenda.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa AND pit_bd_ficha_adenda.cod_iniciativa = pit_bd_ficha_pit.cod_pit
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

	//2.- datos de la directiva
	$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno
	FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_directiva_taz.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
	 WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
	 org_ficha_directiva_taz.cod_cargo_directivo=1 AND
	 org_ficha_directiva_taz.vigente=1";
	 $result=mysql_query($sql) or die (mysql_error());
	 $f2=mysql_fetch_array($result);
	 
	$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno
	FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_directiva_taz.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
	 WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
	 org_ficha_directiva_taz.cod_cargo_directivo=3 AND
	 org_ficha_directiva_taz.vigente=1";
	 $result=mysql_query($sql) or die (mysql_error());
	 $f3=mysql_fetch_array($result);	 
	 
//3.- Atf de primer desembolso
$sql="SELECT clar_atf_pit.n_atf, 
	clar_atf_pit.monto_desembolsado, 
	clar_atf_pit.saldo, 
	clar_atf_pit.n_voucher, 
	clar_atf_pit.monto_organizacion, 
	sys_bd_subcomponente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN clar_atf_pit ON clar_atf_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = clar_atf_pit.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pit.cod_poa
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);

//4.- Atf de segundo desembolso
$sql="SELECT clar_atf_pit_sd.n_atf, 
	clar_bd_ficha_sd_pit.f_desembolso, 
	clar_atf_pit_sd.monto_desembolsado, 
	clar_atf_pit_sd.saldo, 
	sys_bd_subcomponente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria
FROM pit_bd_ficha_pit INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pit.cod_pit = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN clar_atf_pit_sd ON clar_atf_pit_sd.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = clar_atf_pit_sd.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_pit_sd.cod_poa
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_pit_sd.cod_ficha_sd
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f5=mysql_fetch_array($result);	 
$total=mysql_num_rows($result);
}
elseif($row['cod_tipo_iniciativa']==4)
{
//1.- obtengo los datos del PDN
$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.total_apoyo, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.at_org, 
	pit_bd_ficha_pdn.vg_org, 
	pit_bd_ficha_pdn.fer_org
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa AND pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

//2.- Juntas directivas
$sql="SELECT org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$f2=mysql_fetch_array($result);

$sql="SELECT org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$f3=mysql_fetch_array($result);

//3.-ATF de primer desembolso
$sql="SELECT clar_atf_pdn.n_atf, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.saldo_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.saldo_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.saldo_3, 
	clar_atf_pdn.monto_4, 
	clar_atf_pdn.saldo_4
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
clar_atf_pdn.cod_tipo_atf_pdn=1";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);

//4.-ATF de segundo desembolso
$sql="SELECT clar_atf_pdn.n_atf, 
	clar_bd_ficha_sd_pit.f_desembolso, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.saldo_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.saldo_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.saldo_3, 
	clar_atf_pdn.monto_4, 
	clar_atf_pdn.saldo_4
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_pdn.cod_relacionador
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
clar_atf_pdn.cod_tipo_atf_pdn=2";
$result=mysql_query($sql) or die (mysql_error());
$f5=mysql_fetch_array($result);
$total=mysql_num_rows($result);

}
elseif($row['cod_tipo_iniciativa']==5)
{
//1.- Obtengo los datos del PGRN
$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.n_cuenta, 
	sys_bd_ifi.descripcion AS ifi, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	pit_bd_ficha_mrn.cif_pdss, 
	pit_bd_ficha_mrn.at_pdss, 
	pit_bd_ficha_mrn.vg_pdss, 
	pit_bd_ficha_mrn.ag_pdss, 
	pit_bd_ficha_mrn.at_org, 
	pit_bd_ficha_mrn.vg_org
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_mrn.cod_mrn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

//2.- Juntas directivas
$sql="SELECT org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$f2=mysql_fetch_array($result);

$sql="SELECT org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod' AND
org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.vigente=1";
$result=mysql_query($sql) or die (mysql_error());
$f3=mysql_fetch_array($result);

//3.- ATF de primer desembolso
$sql="SELECT clar_atf_mrn.n_atf, 
	clar_atf_mrn.desembolso_1, 
	clar_atf_mrn.saldo_1, 
	clar_atf_mrn.desembolso_2, 
	clar_atf_mrn.saldo_2, 
	clar_atf_mrn.desembolso_3, 
	clar_atf_mrn.saldo_3, 
	clar_atf_mrn.desembolso_4, 
	clar_atf_mrn.saldo_4
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_mrn.cod_mrn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN clar_atf_mrn ON clar_atf_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);

//4.- ATF de segundo desembolso
$sql="SELECT clar_atf_mrn_sd.n_atf, 
	clar_bd_ficha_sd_pit.f_desembolso, 
	clar_atf_mrn_sd.monto_1, 
	clar_atf_mrn_sd.saldo_1, 
	clar_atf_mrn_sd.monto_2, 
	clar_atf_mrn_sd.saldo_2, 
	clar_atf_mrn_sd.monto_3, 
	clar_atf_mrn_sd.saldo_3, 
	clar_atf_mrn_sd.monto_4, 
	clar_atf_mrn_sd.saldo_4
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_ficha_adenda ON pit_bd_ficha_mrn.cod_mrn = pit_bd_ficha_adenda.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_ficha_adenda.cod_tipo_iniciativa
	 INNER JOIN clar_atf_mrn_sd ON clar_atf_mrn_sd.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN clar_bd_ficha_sd_pit ON clar_bd_ficha_sd_pit.cod_ficha_sd = clar_atf_mrn_sd.cod_ficha_sd
WHERE pit_bd_ficha_adenda.cod_ficha_adenda='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f5=mysql_fetch_array($result);
$total=mysql_num_rows($result);

}



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
<? include("encabezado.php");?>

<!-- -->
<div class="capa txt_titulo centrado"><u>ADENDA Nº <? echo numeracion($row['n_adenda']);?> –<? echo periodo($row['f_adenda']);?> –<? echo $row['oficina'];?></u><br/>  AL  CONTRATO Nº <? echo numeracion($row['n_contrato']);?> –<? echo periodo($row['f_contrato']);?> – PIT – OL <? echo $row['oficina'];?></div>

<!-- Inicio del contenido -->
<? echo $row['contenido'];?>
<!-- Termino del contenido -->

<p><br/></p>
<table width="90%" cellpadding="4" cellspacing="4" border="0" align="center">
	<tr class="centrado">
		<td width="30%">________________________</td>
		<td width="40%"><br/></td>
		<td width="30%">________________________</td>
	</tr>
	
	<tr class="txt_titulo centrado">
		<td><? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?><br/>PRESIDENTE DE <? echo $org;?></td>
		<td></td>
		<td><? echo $r2['nombre']." ".$r2['paterno']." ".$r2['materno'];?><br/>TESORERO DE <? echo $org;?></td>
	</tr>
	
	<tr>
		<td colspan="3"><p><br/></p></td>
	</tr>

	<tr class="centrado">
		<td width="30%">________________________</td>
		<td width="40%"><br/></td>
		<td width="30%">________________________</td>
	</tr>	
		<tr class="txt_titulo centrado">
		<td><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?><br/>PRESIDENTE DE <? echo $orga;?></td>
		<td></td>
		<td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?><br/>TESORERO DE <? echo $orga;?></td>
	</tr>
	
		<tr>
		<td colspan="3"><br/></td>
	</tr>

	<tr class="centrado">
		<td><br/></td>
		<td>________________________</td>
		<td><br/></td>
	</tr>
	
		<tr class="centrado txt_titulo">
		<td><br/></td>
		<td><? echo $row['nombres']." ".$row['apellidos'];?><br/> JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></td>
		<td><br/></td>
	</tr>	
</table>
<?
if ($row['tipo_monto']<>0)
{
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_desembolso']);?> / OL <? echo $row['oficina'];?></u></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="23%" class="txt_titulo">A</td>
    <td width="1%">:</td>
    <td width="76%">C.P.C JUAN SALAS ACOSTA </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="76%">ADMINISTRADOR DEL NEC PDSS II </td>
  </tr>
  <tr>
    <td class="txt_titulo">CC</td>
    <td width="1%">:</td>
    <td width="76%">Responsable de Componentes </td>
  </tr>
  <tr>
    <td class="txt_titulo">ASUNTO</td>
    <td width="1%">:</td>
    <td width="76%">Desembolso  Adenda</td>
  </tr>
  <tr>
    <td><? echo $orga;?></td>
    <td width="1%">:</td>
    <td width="76%"><? echo $f1['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">REFERENCIA</td>
    <td width="1%">:</td>
    <td width="76%">ADENDA Nº <? echo numeracion($row['n_adenda'])."-".periodo($row['f_adenda']);?> AL CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - PIT – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_desembolso']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="19%">Nombre de <? echo $orga;?> </td>
    <td width="7%">Tipo de Iniciativa </td>
    <td width="9%">ATF N° </td>
    <td width="20%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>
 
  <tr>
    <td><? echo $f1['nombre'];?></td>
    <td class="centrado"><? if ($row['cod_tipo_iniciativa']==3) echo "PIT"; elseif($row['cod_tipo_iniciativa']==4) echo "PDN"; elseif($row['cod_tipo_iniciativa']==5) echo "MRN";?></td>
    <td class="centrado"><? echo numeracion($row['n_atf'])."-".periodo($row['f_desembolso']);?></td>
    <td><? echo $f1['ifi'];?></td>
    <td class="centrado"><? echo $f1['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
  
  <tr>
    <td colspan="5">TOTAL</td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
</table>

<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>Se adjunta a la presente la autorización de transferencia de fondos. </td>
  </tr>
  <tr>
    <td><br>
    Atentamente,</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>




<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> –  <? echo periodo($row['f_desembolso']);?> - <? echo $row['oficina'];?><BR>
A "<? echo $f1['nombre'];?>" PARA ADDENDA Nº <? echo numeracion($row['n_adenda'])."-".periodo($row['f_adenda']);?></div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_desembolso']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">Nombre de la organizacion</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f1['nombre'];?></td>
  </tr>
  
    <tr>
    <td class="txt_titulo">Nº documento</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f1['n_documento'];?></td>
  </tr>
  
  <tr>
    <td class="txt_titulo">Entidad Financiera </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $f1['ifi'];?></td>
  </tr>
  		<tr>
			<td class="txt_titulo">N° de cuenta </td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><? echo $f1['n_cuenta'];?></td>
		</tr>
		
	<tr>
		<td colspan="4" class="txt_titulo centrado">Antecedentes que sustentan esta ATF</td>
	</tr>			
  <tr>
    <td class="txt_titulo">Nº de contrato</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2">Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - PIT – OL <? echo $row['oficina'];?> de fecha <? echo traducefecha($row['f_contrato']);?></td>
  </tr>
    <tr>
    <td class="txt_titulo">Nº de ATF primer desembolso</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo numeracion($f4['n_atf'])."-".periodo($row['f_contrato']);?></td>
  </tr>
    <tr>
    <td class="txt_titulo">Nº de ATF segundo desembolso</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? if ($f5['n_atf']==NULL) echo "No solicita aún"; else echo numeracion($f5['n_atf'])."-".periodo($f5['f_desembolso']);?></td>
  </tr>  
</table>
<br/>
<!-- esto varia segun el tipo de Iniciativa -->
<?
if ($row['cod_tipo_iniciativa']==3)
{
?>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr class="txt_titulo centrado">
	<td colspan="5">ATF DE PRIMER DESEMBOLSO</td>
</tr>

  <tr class="txt_titulo">
    <td width="43%" align="center">ACTIVIDADES</td>
    <td width="6%" align="center">% A DESEMBOLSAR</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>

  <tr>
    <td>Primer desembolso</td>
    <td class="centrado">70.00 </td>
    <td align="right" class="txt_titulo"><? echo number_format($f4['monto_desembolsado'],2);?></td>
    <td align="center"><? echo $f4['poa'];?></td>
    <td align="center"><? echo $f4['categoria'];?></td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($f4['monto_desembolsado'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<?
}
elseif($row['cod_tipo_iniciativa']==4)
{
?>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">

<tr class="txt_titulo centrado">
	<td colspan="5">ATF DE PRIMER DESEMBOLSO</td>
</tr>

  <tr class="txt_titulo">
    <td width="42%" align="center">ACTIVIDADES</td>
    <td width="7%" align="center">% A DESEMBOLSAR </td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>Asistencia Técnica</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($f4['monto_1'],2);?></td>
    <td align="center">2.1.1.1</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Visita Guiada</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($f4['monto_2'],2);?></td>
    <td align="center">2.1.2.1</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Participación en Ferias</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($f4['monto_3'],2);?></td>
    <td align="center">2.1.2.2</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PDN</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($f4['monto_4'],2);?></td>
    <td align="center">2.3.2.1</td>
    <td align="center">I</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><?  echo number_format($f4['monto_1']+$f4['monto_2']+$f4['monto_3']+$f4['monto_4'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<?
}
elseif($row['cod_tipo_iniciativa']==5)
{
?>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">

<tr class="txt_titulo centrado">
	<td colspan="5">ATF DE PRIMER DESEMBOLSO</td>
</tr>

  <tr class="txt_titulo">
    <td width="41%" align="center">ACTIVIDADES</td>
    <td width="8%" align="center">% A DESEMBOLSAR</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>Premio para Concursos Inter Familiares</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($f4['desembolso_1'],2);?></td>
    <td align="center">1.1.1.1</td>
    <td align="center">II</td>
  </tr>
  <tr>
    <td>Asistencia Técnica (de campesino a campesino)</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($f4['desembolso_2'],2);?></td>
    <td align="center">1.2.1.1</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Visitas guiada</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($f4['desembolso_3'],2);?></td>
    <td align="center">1.3.3.2</td>
    <td align="center">III</td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PGRN</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($f4['desembolso_4'],2);?></td>
    <td align="center">1.3.2.1</td>
    <td align="center">I</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($f4['desembolso_1']+$f4['desembolso_2']+$f4['desembolso_3']+$f4['desembolso_4'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<?
}
?>
<!-- fin de cuandro de atf primer desembolso -->


<!-- Verificamos la informacion para el segundo desembolso (si la hubiera) -->
<?
if ($row['cod_tipo_iniciativa']==3 and $total<>0)
{
?>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr class="txt_titulo centrado">
	<td colspan="5">ATF DE SEGUNDO DESEMBOLSO</td>
</tr>
  <tr class="txt_titulo">
    <td width="43%" align="center">ACTIVIDADES</td>
    <td width="6%" align="center">% A DESEMBOLSAR</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>

  <tr>
    <td>Segundo desembolso</td>
    <td class="centrado">30.00 </td>
    <td align="right" class="txt_titulo"><?php  echo number_format($f5['monto_desembolsado'],2);?></td>
    <td align="center"><?php  echo $f5['poa'];?></td>
    <td align="center"><?php  echo $f5['categoria'];?></td>
  </tr>

  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><?php  echo number_format($f5['monto_desembolsado'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<?
}
elseif($row['cod_tipo_iniciativa']==4 and $total<>0)
{
?>
<br/>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
<tr class="txt_titulo centrado">
	<td colspan="5">ATF DE SEGUNDO DESEMBOLSO</td>
</tr>
  <tr class="txt_titulo">
    <td width="42%" align="center">ACTIVIDADES</td>
    <td width="7%" align="center">% A DESEMBOLSAR </td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>Asistencia Técnica</td>
    <td class="derecha">30.00</td>
    <td align="right"><?php  echo number_format($f5['monto_1'],2);?></td>
    <td align="center">2.1.1.2</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Visita Guiada</td>
    <td class="derecha">30.00</td>
    <td align="right"><?php  echo number_format($f5['monto_2'],2);?></td>
    <td align="center">2.1.2.1</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Participación en Ferias</td>
    <td class="derecha">30.00</td>
    <td align="right"><?php  echo number_format($f5['monto_3'],2);?></td>
    <td align="center">2.1.2.2</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PDN</td>
    <td class="derecha">30.00</td>
    <td align="right"><?php  echo number_format($f5['monto_4'],2);?></td>
    <td align="center">2.3.2.2</td>
    <td align="center">I</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><?php  echo number_format($f5['monto_1']+$f5['monto_2']+$f5['monto_3']+$f5['monto_4'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>

<?
}
elseif($row['cod_tipo_iniciativa']==5 and $total<>0)
{
?>
<br/>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
<tr class="txt_titulo centrado">
	<td colspan="5">ATF DE SEGUNDO DESEMBOLSO</td>
</tr>
  <tr class="txt_titulo">
    <td width="41%" align="center">ACTIVIDADES</td>
    <td width="8%" align="center">% A DESEMBOLSAR</td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td>Premio para Concursos Inter Familiares</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($f5['monto_1'],2);?></td>
    <td align="center">1.1.1.2</td>
    <td align="center">II</td>
  </tr>
  <tr>
    <td>Asistencia Técnica (de campesino a campesino)</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($f5['monto_2'],2);?></td>
    <td align="center">1.2.1.2</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Visitas guiada</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($f5['monto_3'],2);?></td>
    <td align="center">1.2.2.2</td>
    <td align="center">I</td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PGRN</td>
    <td class="derecha">30.00</td>
    <td align="right"><? echo number_format($f5['monto_4'],2);?></td>
    <td align="center">1.3.2.2</td>
    <td align="center">I</td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($f5['monto_1']+$f5['monto_2']+$f5['monto_3']+$f5['monto_4'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>

<?
}
?>
<!-- fin de la informacion para el segundo desembolso -->



<br/>
<div class="capa txt_titulo" align="left">MONTO DE LA PRESENTE ADENDA</div>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%">Monto a desembolsar</td>
    <td align="center" width="4%">:</td>
    <td align="right" width="61%"><strong>S/. </strong><? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
    <tr>
    <td width="35%">Contrapartida de la organización</td>
    <td align="center" width="4%">:</td>
    <td align="right" width="61%"><strong>S/. </strong><? echo number_format($row['aporte_org'],2);?></td>
  </tr>
</table> 
<br/>
<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
   <tr>
    <td>Acta de la organizacion solicitando una ampliacion de plazo y presupuesto</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td width="82%">Copia de la Ficha de Inscripción en la SUNARP </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de DNIs de los directivos de la Organización responsable</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>

  <tr>
    <td>Contrato de Donación sujeto a Cargo entre SIERRA SUR II y La Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia del Voucher de Depósito del Aporte de La Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_desembolso']);?></div>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>

<?
}
?>


<!-- -->

<div class="capa">
	<button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	<a href="../contratos/adenda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
</div>

</body>
</html>