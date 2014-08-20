<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	//1.- Busco el numero de addendas creadas
	$sql="SELECT pit_bd_ficha_adenda_convenio.cod
	FROM pit_bd_ficha_adenda_convenio
	WHERE pit_bd_ficha_adenda_convenio.id_convenio='".$_POST['convenio']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$total=mysql_num_rows($result);

	$n_adenda=$total+1;

	//2.- Procedo a registrar la info
	$sql="INSERT INTO pit_bd_ficha_adenda_convenio VALUES('','$n_adenda','".$_POST['f_adenda']."','".$_POST['convenio']."','".$_POST['f_termino']."','','005')";
	$result=mysql_query($sql) or die (mysql_error());

	//3.- Busco el ultimo registro generado
	$sql="SELECT * FROM pit_bd_ficha_adenda_convenio ORDER BY cod DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$codigo=$r1['cod'];

	//4.- Redirecciono
	echo "<script>window.location='edit_adenda_convenio.php?SES=$SES&anio=$anio&cod=$codigo&modo=add'</script>";
}
elseif($action==EDIT)
{
	$sql="UPDATE pit_bd_ficha_adenda_convenio SET contenido='".$_POST['contenido']."' WHERE cod='$cod'";
	$result=mysql_query($sql) or die(mysql_error());

	echo "<script>window.location='../print/print_adenda_convenio.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==UPDATE)
{
	$sql="UPDATE pit_bd_ficha_adenda_convenio SET f_adenda='".$_POST['f_adenda']."',f_termino='".$_POST['f_termino']."' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());

	$codigo=$_POST['codigo'];

	echo "<script>window.location='edit_adenda_convenio.php?SES=$SES&anio=$anio&cod=$codigo&modo=add'</script>";
}
elseif($action==DELETE)
{
	$sql="DELETE FROM pit_bd_ficha_adenda_convenio WHERE cod='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location='adenda_convenio.php?SES=$SES&anio=$anio&modo=edit'</script>";
}


?>