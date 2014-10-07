<?php
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ACTIVIDAD)
{
	//1.- Defino la variable actividad
	$actividad=$_POST['actividad'];
	
	//2.- Verifico si esta vacia y segun sea el caso redirecciono
	if ($actividad<>NULL)
	{
		echo "<script>window.location ='n_calif_cif.php?SES=$SES&anio=$anio&cod=$cod&id=$actividad'</script>";
	}
	else
	{
		echo "<script>window.location ='n_calif_cif.php?SES=$SES&anio=$anio&cod=$cod&error=vacio'</script>";
	}	
}
elseif($action==DELETE_REG)
{
	$sql="DELETE FROM cif_bd_ficha_cif WHERE cod_ficha_cif='$reg'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_calif_cif.php?SES=$SES&anio=$anio&cod=$cod&id=$id&alert=success_delete'</script>";
}
elseif($action==DELETE_ALL)
{
	$sql="DELETE FROM cif_bd_ficha_cif WHERE cod_concurso_cif='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_calif_cif.php?SES=$SES&anio=$anio&cod=$cod&id=$id&alert=success_delete'</script>";
}
elseif($action==ADD_REG)
{
	$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['usuario']."','$id','".$_POST['cantidad_a']."','".$_POST['valor_a']."','".$_POST['cantidad_b']."','".$_POST['valor_b']."','".$_POST['puntaje']."','".$_POST['puesto']."','".$_POST['monto_pdss']."','".$_POST['monto_otro']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_calif_cif.php?SES=$SES&anio=$anio&cod=$cod&id=$id&alert=success_insert'</script>";
}

?>