<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	$organizacion=$_POST['org'];
	$dato=explode(",",$organizacion);
	$tipo_documento=$dato[0];
	$n_documento=$dato[1];
	
	
	$sql="INSERT INTO sf_bd_grupo_ahorro VALUES('','$tipo_documento','$n_documento',UPPER('".$_POST['nombre']."'),'')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Busco el ultimo registro generado
	$sql="SELECT cod_grupo FROM sf_bd_grupo_ahorro ORDER BY cod_grupo DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_grupo'];
	
	//3.- Redirecciono
	echo "<script>window.location ='m_ahorro.php?SES=$SES&anio=$anio&id=$codigo'</script>";
}



?>