<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	//1.- Actualizo la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_acta_clar='".$_POST['n_acta']."' WHERE cod='".$_POST['cod_numera']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Ingreso el acta
	$sql="INSERT INTO clar_bd_acta VALUES('','".$_POST['n_acta']."','".$_POST['contenido']."','$id')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Obtengo el ultimo registro generado
	$sql="SELECT * FROM clar_bd_acta ORDER BY cod_acta DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_acta'];
	
	//4.- Redirecciono
	echo "<script>window.location ='../print/print_acta_clar.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==UPDATE)
{
	//1.- Actualizo el acta
	$sql="UPDATE clar_bd_acta SET n_acta='".$_POST['n_acta']."',contenido='".$_POST['contenido']."' WHERE cod_acta='".$_POST['cod_acta']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- obtengo el codigo
	$codigo=$_POST['cod_acta'];
	
	//3.- Redirecciono
	echo "<script>window.location ='../print/print_acta_clar.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}


?>