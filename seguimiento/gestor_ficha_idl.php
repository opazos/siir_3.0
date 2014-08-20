<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();


if ($action==UPDATE_PRIM)
{

	$sql="UPDATE pit_bd_ficha_idl SET f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."' WHERE cod_ficha_idl='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- redirecciono
	echo "<script>window.location ='idl.php?SES=$SES&anio=$anio&modo=edit'</script>";

}


?>