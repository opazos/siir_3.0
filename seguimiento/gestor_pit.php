<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==UPDATE_PRIMER)
{
//1.- Actualizo
$sql="UPDATE pit_bd_ficha_pit SET f_presentacion='".$_POST['f_presentacion']."',mes='".$_POST['mes']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',n_voucher='".$_POST['n_voucher']."',monto_organizacion='".$_POST['monto_org']."' WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Redirecciono
echo "<script>window.location ='pit.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==UPDATE_SEGUNDO)
{
//1.- Actualizo
$sql="UPDATE pit_bd_ficha_pit SET f_presentacion_2='".$_POST['f_presentacion']."',n_voucher_2='".$_POST['n_voucher']."',monto_organizacion_2='".$_POST['monto_org']."' WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Redirecciono
echo "<script>window.location ='pit.php?SES=$SES&anio=$anio&modo=segundo_edit'</script>";
}


?>