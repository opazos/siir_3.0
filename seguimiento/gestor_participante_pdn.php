<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==UPDATE)
{

//1.- Genero las nuevas familias
for($i=0;$i<count($_POST['campos']);$i++) 
{

//a.- Realizo una busqueda
$sql="SELECT pit_bd_user_iniciativa.n_documento
FROM pit_bd_user_iniciativa
WHERE pit_bd_user_iniciativa.n_documento='".$_POST['campos'][$i]."' AND
pit_bd_user_iniciativa.cod_tipo_iniciativa=4 AND
pit_bd_user_iniciativa.cod_iniciativa='$id'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

if ($total==0)
{
$sql="INSERT IGNORE INTO pit_bd_user_iniciativa VALUES('008','".$_POST['campos'][$i]."','2','1','4','$id')";
$result=mysql_query($sql) or die (mysql_error());
}

}

//2.- Actualizo el estado de las familias
foreach($estado as $cad=>$a1)
{
$sql="UPDATE pit_bd_user_iniciativa SET estado='".$_POST['estado'][$cad]."' WHERE n_documento='$cad' AND cod_tipo_iniciativa='4' AND cod_iniciativa='$id'";
$result=mysql_query($sql) or die (mysql_error());
}

//3.- Redirecciono
echo "<script>window.location ='participante_pdn.php?SES=$SES&anio=$anio&id=$id'</script>";
	

}


?>