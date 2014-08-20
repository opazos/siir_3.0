<?
require("sesion.php");
include("funciones_externas.php");
conectarte();

$sql="SELECT * FROM maestro_reniec WHERE dni='".$_POST['dni']."'";
$result=mysql_query($sql) or die (mysql_error());
?>