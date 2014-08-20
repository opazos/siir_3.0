<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

if ($action==ADD)
{

//1.- Actualizamos al titular y a la pareja
	
	foreach($titular as $num=>$a)
	{

		$sql="UPDATE org_ficha_usuario SET titular=1,n_documento_conyuge='".$_POST['pareja'][$num]."' WHERE n_documento='$a'";
		$result=mysql_query($sql) or die (mysql_error());

	}
	
	foreach($pareja as $num=>$b)
	{

		$sql="UPDATE org_ficha_usuario SET titular=0 WHERE n_documento='$b'";
		$result=mysql_query($sql) or die (mysql_error());

	}
	//2.- Redireccionamos
	echo "<script>window.location ='consistenciar_familias.php?SES=$SES&anio=$anio&cod1=$cod1&cod2=$cod2'</script>";

}


?>