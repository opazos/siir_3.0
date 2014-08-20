<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

if ($_POST['clave']<>NULL)
{
$sql="UPDATE sys_bd_personal SET nombre=UPPER('".$_POST['nombre']."'),apellido=UPPER('".$_POST['apellido']."'),clave='".md5($_POST['clave'])."' WHERE n_documento='".$_POST['dni']."'";
$result=mysql_query($sql) or die (mysql_error());
}
else
{
$sql="UPDATE sys_bd_personal SET nombre=UPPER('".$_POST['nombre']."'),apellido=UPPER('".$_POST['apellido']."') WHERE n_documento='".$_POST['dni']."'";
}
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='principal.php?SES=$SES&anio=$anio'</script>";


?>