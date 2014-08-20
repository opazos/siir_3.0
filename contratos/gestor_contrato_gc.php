<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$organizacion=$_POST['org'];
$dato=explode(",",$organizacion);

$tipo_documento=$dato[0];
$n_documento=$dato[1];

if ($action==ADD)
{

//1.- Buscamos la numeracion
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_contrato_iniciativa, 
	sys_bd_numera_dependencia.n_atf_iniciativa, 
	sys_bd_numera_dependencia.n_solicitud_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$n_contrato=$r1['n_contrato_iniciativa']+1;
	$n_solicitud=$r1['n_solicitud_iniciativa']+1;
	$n_atf=$r1['n_atf_iniciativa']+1;

//2.- Actualizamos la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_iniciativa='$n_contrato',n_atf_iniciativa='$n_atf',n_solicitud_iniciativa='$n_solicitud' WHERE cod='".$r1['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());

//3.- Realizo el ingreso de la información
	$sql="INSERT INTO gcac_bd_evento_gc VALUES('','11','".$_POST['tipo_evento']."',UPPER('".$_POST['nombre']."'),'".$_POST['f_evento']."',UPPER('".$_POST['lugar']."'),'','','','".$_POST['participantes']."','$n_contrato','".$_POST['f_contrato']."','$n_atf','$n_solicitud','".$_POST['ifi']."','".$_POST['n_cuenta']."','".$_POST['poa']."','".$_POST['fte_fto']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."','005','$fecha_hoy','".$_POST['detalle']."','$tipo_documento','$n_documento')";
	$result=mysql_query($sql) or die (mysql_error()); 		
//4.- obtengo el ultimo registro generado
	$sql="SELECT * FROM gcac_bd_evento_gc ORDER BY cod_evento_gc DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	$codigo=$r2['cod_evento_gc'];

//5.- Redireccionamos
	echo "<script>window.location ='../print/print_contrato_gc.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}
elseif($action==UPDATE)
{


//1.- actualizo
$sql="UPDATE gcac_bd_evento_gc SET nombre=UPPER('".$_POST['nombre']."'),f_evento='".$_POST['f_evento']."',lugar=UPPER('".$_POST['lugar']."'),participantes='".$_POST['participantes']."',f_contrato='".$_POST['f_contrato']."',cod_ifi='".$_POST['ifi']."',n_cuenta='".$_POST['n_cuenta']."',cod_subactividad='".$_POST['poa']."',fte_fto='".$_POST['fte_fto']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."',detalle=UPPER('".$_POST['detalle']."'),cod_tipo_doc_org='$tipo_documento',n_documento_org='$n_documento' WHERE cod_evento_gc='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- Redirecciono
	echo "<script>window.location ='../print/print_contrato_gc.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}
elseif($action==ANULA)
{
$sql="UPDATE gcac_bd_evento_gc SET cod_estado_iniciativa='000' WHERE cod_evento_gc='$id'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Redirecciono
	echo "<script>window.location ='contrato_gc.php?SES=$SES&anio=$anio&alert=success_change'</script>";	
}
elseif($action==LIQUIDA)
{
//1.- inserto la informacion
$sql="INSERT INTO gcac_bd_liquida_evento_gc VALUES('','".$_POST['f_rendicion']."',UPPER('".$_POST['resultado']."'),UPPER('".$_POST['problema']."'),'".$_POST['ejec_pdss']."','".$_POST['ejec_org']."','".$_POST['ejec_mun']."','".$_POST['ejec_otr']."','$id')";
$result=mysql_query($sql) or die (mysql_error());

//2.- Actualizo el estado
$sql="UPDATE gcac_bd_evento_gc SET cod_estado_iniciativa='004' WHERE cod_evento_gc='$id'";
$result=mysql_query($sql) or die (mysql_error());

//3.- busco el ultimo registro generado
$sql="SELECT * FROM gcac_bd_liquida_evento_gc ORDER BY cod_liquida_evento DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_liquida_evento'];

//3.- Redirecciono
	echo "<script>window.location ='../print/print_liquida_gc.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}
elseif($action==UPDATE_LIQUIDA)
{
//1.- Guardamos
$sql="UPDATE gcac_bd_liquida_evento_gc SET f_informe='".$_POST['f_rendicion']."',resultado=UPPER('".$_POST['resultado']."'),problema=UPPER('".$_POST['problema']."'),ejec_pdss='".$_POST['ejec_pdss']."',ejec_org='".$_POST['ejec_org']."',ejec_mun='".$_POST['ejec_mun']."',ejec_otr='".$_POST['ejec_otro']."' WHERE cod_liquida_evento='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//3.- Redirecciono
	echo "<script>window.location ='../print/print_liquida_gc.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}


?>