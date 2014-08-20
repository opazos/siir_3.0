<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


$organizacion=$_POST['contratante'];
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

//3.- Genero el nuevo registro
	$sql="INSERT INTO gcac_bd_evento_gc VALUES('','11','6',UPPER('".$_POST['nombre']."'),'".$_POST['f_evento']."',UPPER('".$_POST['lugar']."'),'".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."','','$n_contrato','".$_POST['f_contrato']."','$n_atf','$n_solicitud','".$_POST['ifi']."','".$_POST['n_cuenta']."','".$_POST['poa']."','".$_POST['fte_fto']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."','005','$fecha_hoy','','$tipo_documento','$n_documento')";
	$result=mysql_query($sql) or die (mysql_error());	

//4.- Busco el ultimo registro generado
	$sql="SELECT * FROM gcac_bd_evento_gc ORDER BY cod_evento_gc DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	$codigo=$r2['cod_evento_gc'];

//5.- REDIRECCIONAMOS
echo "<script>window.location ='../print/print_contrato_intercon.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==UPDATE)
{
	//1.- Actualizamos
	$sql="UPDATE gcac_bd_evento_gc SET nombre=UPPER('".$_POST['nombre']."'),f_evento='".$_POST['f_evento']."',lugar=UPPER('".$_POST['lugar']."'),cod_dep='".$_POST['select1']."',cod_prov='".$_POST['select2']."',cod_dist='".$_POST['select3']."',f_contrato='".$_POST['f_contrato']."',cod_ifi='".$_POST['ifi']."',n_cuenta='".$_POST['n_cuenta']."',cod_subactividad='".$_POST['poa']."',fte_fto='".$_POST['fte_fto']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."',cod_tipo_doc_org='$tipo_documento',n_documento_org='$n_documento' WHERE cod_evento_gc='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());

	$codigo=$_POST['codigo'];

	//2.- redireccionamos
	echo "<script>window.location ='../print/print_contrato_intercon.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}

?>