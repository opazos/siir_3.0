<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	$sql="INSERT INTO sys_bd_actividad_poa VALUES('','".$_POST['codigo']."',UPPER('".$_POST['descripcion']."'),'$cod')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redireccionamos
	echo "<script>window.location ='actividad.php?SES=$SES&anio=$anio&cod=$cod&modo=edit'</script>";
}

?>