<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	$sql="INSERT INTO sys_bd_objetivo_ml VALUES('',UPPER('".$_POST['objetivo']."'))";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.-Redirecciono
	echo "<script>window.location ='ml.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==UPDATE)
{
	$sql="UPDATE sys_bd_objetivo_ml SET descripcion='".$_POST['objetivo']."' WHERE cod_objetivo='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.-Redirecciono
	echo "<script>window.location ='ml.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==DELETE)
{
	$sql="DELETE FROM sys_bd_objetivo_ml WHERE cod_objetivo='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.-Redirecciono
	echo "<script>window.location ='ml.php?SES=$SES&anio=$anio&modo=delete'</script>";
}

?>