<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	$sql="INSERT INTO sys_bd_subcomponente_poa VALUES('','".$_POST['codigo']."',UPPER('".$_POST['descripcion']."'),'$cod')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redireccionamos
	echo "<script>window.location ='subcomponente.php?SES=$SES&anio=$anio&cod=$cod&modo=edit'</script>";
}
elseif($action==UPDATE)
{
	$sql="UPDATE sys_bd_subcomponente_poa SET codigo='".$_POST['codigo']."',nombre=UPPER('".$_POST['descripcion']."') WHERE cod='".$_POST['cod_subcomponente']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redireccionamos
	echo "<script>window.location ='subcomponente.php?SES=$SES&anio=$anio&cod=$cod&modo=edit'</script>";	
}

?>