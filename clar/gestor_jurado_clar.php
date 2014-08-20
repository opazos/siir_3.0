<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{
	$sql="INSERT IGNORE INTO clar_bd_miembro VALUES('008','".$_POST['n_documento']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['f_nacimiento']."','".$_POST['sexo']."','".$row['cod_dependencia']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='jurado_clar.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==UPDATE)
{
	$sql="UPDATE clar_bd_miembro SET nombre='".$_POST['nombre']."',paterno='".$_POST['paterno']."',materno='".$_POST['materno']."',f_nacimiento='".$_POST['f_nacimiento']."',sexo='".$_POST['sexo']."' WHERE n_documento='".$_POST['n_documento']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='jurado_clar.php?SES=$SES&anio=$anio&modo=edit'</script>";	
}
elseif($action==DELETE)
{
	$sql="DELETE clar_bd_miembro WHERE n_documento='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='jurado_clar.php?SES=$SES&anio=$anio&modo=edit'</script>";		
}


?>