<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	if ($_POST['oficina']==NULL or $_POST['periodo']==NULL)
	{
		echo "<script>window.location ='n_detalle_poa.php?SES=$SES&anio=$anio&cod=$cod&error=vacio'</script>";
	}
	else
	{
	$total=$_POST['fida']+$_POST['ro']+$_POST['donacion'];
	
	$sql="INSERT INTO sys_bd_detalle_poa VALUES('','".$_POST['periodo']."','".$_POST['oficina']."','".$_POST['meta']."','".$_POST['fida']."','".$_POST['ro']."','".$_POST['donacion']."','$total','".$_POST['cofinanciador']."','".$_POST['usuario']."','','$cod')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//redireccionamos
	echo "<script>window.location ='detalle_poa.php?SES=$SES&anio=$anio&cod=$cod&modo=edit'</script>";
	
	}
	
}
elseif($action==UPDATE)
{
	$total=$_POST['fida']+$_POST['ro']+$_POST['donacion'];
	
	$sql="UPDATE sys_bd_detalle_poa SET anio='".$_POST['periodo']."',cod_dependencia='".$_POST['oficina']."',meta_fisica='".$_POST['meta']."',monto_fida='".$_POST['fida']."',monto_ro='".$_POST['ro']."',monto_donacion='".$_POST['donacion']."',monto_total='$total',monto_municipio='".$_POST['cofinanciador']."',monto_usuario='".$_POST['usuario']."' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//redireccionamos
	echo "<script>window.location ='detalle_poa.php?SES=$SES&anio=$anio&cod=$cod&modo=edit'</script>";	
}
elseif($action==DELETE)
{
	$sql="DELETE FROM sys_bd_detalle_poa WHERE cod='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//redireccionamos
	echo "<script>window.location ='detalle_poa.php?SES=$SES&anio=$anio&cod=$cod&modo=edit'</script>";		
}

?>