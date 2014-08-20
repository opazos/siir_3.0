<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	$sql="INSERT INTO sys_bd_componente_poa VALUES('',UPPER('".$_POST['codigo']."'),UPPER('".$_POST['descripcion']."'))";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redireccionamos
	echo "<script>window.location ='poa.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==UPDATE)
{
$sql="UPDATE sys_bd_componente_poa SET codigo='".$_POST['codigo']."',nombre=UPPER('".$_POST['descripcion']."') WHERE cod='".$_POST['cod']."'";
$result=mysql_query($sql) or die (mysql_error());

	//2.- Redireccionamos
	echo "<script>window.location ='poa.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==DELETE)
{
	$sql="DELETE FROM sys_bd_componente_poa WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redireccionamos
	echo "<script>window.location ='poa.php?SES=$SES&anio=$anio&modo=edit'</script>";	
}


?>