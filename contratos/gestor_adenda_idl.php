<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	//1.- Buscar la numeracion
	$sql="SELECT pit_bd_ficha_adenda_idl.cod_adenda
	FROM pit_bd_ficha_adenda_idl
	WHERE pit_bd_ficha_adenda_idl.cod_idl='".$_POST['idl']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$total=mysql_num_rows($result);
	
	$n_adenda=$total+1;

	//2.- Calculo fecha de termino
	$fecha=$_POST['f_inicio'];
	$mes=$_POST['mes'];

	$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
	$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);
	
	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-15';
	}
	else
	{
		$f_termino=$f_termino;
	}
	
	//3.- Ingreso la informacion
	$sql="INSERT INTO pit_bd_ficha_adenda_idl VALUES('','$n_adenda','".$_POST['f_adenda']."','".$_POST['idl']."',UPPER('".$_POST['referencia']."'), '".$_POST['f_inicio']."','".$_POST['mes']."','$f_termino','','005')";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//4.-Obtengo el ultimo registro generado
	$sql="SELECT * FROM pit_bd_ficha_adenda_idl ORDER BY cod_adenda DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_adenda'];
	
	//5.- Redirecciono	
	echo "<script>window.location ='edit_adenda_idl.php?SES=$SES&anio=$anio&cod=$codigo&modo=add'</script>";
}
elseif($action==EDIT)
{
	//1.- 
	$sql="UPDATE pit_bd_ficha_adenda_idl SET contenido='".$_POST['contenido']."' WHERE cod_adenda='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//5.- Redirecciono	
	echo "<script>window.location ='../print/print_adenda_idl.php?SES=$SES&anio=$anio&cod=$cod'</script>";	
}
elseif($action==UPDATE)
{
	//1.- Calculo fecha de termino
	$fecha=$_POST['f_inicio'];
	$mes=$_POST['mes'];

	$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
	$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);
	
	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-15';
	}
	else
	{
		$f_termino=$f_termino;
	}
	
	//2.- Actualizo la información
	$sql="UPDATE pit_bd_ficha_adenda_idl SET f_adenda='".$_POST['f_adenda']."',referencia=UPPER('".$_POST['referencia']."'),f_inicio='".$_POST['f_inicio']."',meses='".$_POST['mes']."',f_termino='$f_termino' WHERE cod_adenda='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	$codigo=$_POST['codigo'];
	
	//3.- Redirecciono
	echo "<script>window.location ='edit_adenda_idl.php?SES=$SES&anio=$anio&cod=$codigo&modo=add'</script>";
}
elseif($action==DELETE)
{
	$sql="DELETE FROM pit_bd_ficha_adenda_idl WHERE cod_adenda='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='adenda_idl.php?SES=$SES&anio=$anio&modo=delete'</script>";	
}



?>