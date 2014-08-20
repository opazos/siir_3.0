<?php
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{
	//1.- Ingreso los datos
	$sql="INSERT INTO clar_bd_miembro VALUES('008','".$_POST['dni']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['f_nacimiento']."','".$_POST['sexo']."','".$row['cod_dependencia']."')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='jurado_cf.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==UPDATE)
{
	//1.- Actualizo los datos
	$sql="UPDATE clar_bd_miembro SET nombre='".$_POST['nombre']."',paterno='".$_POST['paterno']."',materno='".$_POST['materno']."',f_nacimiento='".$_POST['f_nacimiento']."',sexo='".$_POST['sexo']."' WHERE n_documento='".$_POST['dni']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='jurado_cf.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==DELETE)
{
	//1.- Elimino la informacion
	$sql="DELETE FROM clar_bd_miembro WHERE n_documento='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='jurado_cf.php?SES=$SES&anio=$anio&modo=delete'</script>";	
}

?>