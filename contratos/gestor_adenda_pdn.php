<?php
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	//1.- Revisamos el numero de adenda
	$sql="SELECT pit_bd_ficha_adenda_pdn.cod
	FROM pit_bd_ficha_adenda_pdn
	WHERE pit_bd_ficha_adenda_pdn.cod_pdn='".$_POST['pdn']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$total=mysql_num_rows($result);

	$n_adenda=$total+1;

	//2.- Calculamos la fecha de termino
	$fecha=$_POST['f_inicio'];
	$mes=$_POST['meses'];

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

	//3.- Guardamos los datos
	$sql="INSERT INTO pit_bd_ficha_adenda_pdn VALUES('','$n_adenda','".$_POST['f_adenda']."','".$_POST['pdn']."',UPPER('".$_POST['referencia']."'),'".$_POST['f_inicio']."','".$_POST['meses']."','$f_termino','','005')";
	$result=mysql_query($sql) or die (mysql_error());

	//4.- Obtengo el ultimo registro generado
	$sql="SELECT * FROM pit_bd_ficha_adenda_pdn ORDER BY cod DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$codigo=$r1['cod'];

	//5.- Redirecciono	
	echo "<script>window.location ='edit_adenda_pdn.php?SES=$SES&anio=$anio&cod=$codigo&modo=add'</script>";
}
elseif($action==EDIT)
{
	//1.- Actualizamos el contenido
	$sql="UPDATE pit_bd_ficha_adenda_pdn SET contenido='".$_POST['contenido']."' WHERE cod='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	$codigo=$cod;

	//2.- Redirecciono
	echo "<script>window.location='../print/print_adenda_pdn.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==UPDATE)
{
	//1.- Calculamos la fecha de termino
	$fecha=$_POST['f_inicio'];
	$mes=$_POST['meses'];

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

	$sql="UPDATE pit_bd_ficha_adenda_pdn SET f_adenda='".$_POST['f_adenda']."',referencia=UPPER('".$_POST['referencia']."'),f_inicio='".$_POST['f_inicio']."',meses='".$_POST['meses']."',f_termino='$f_termino' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die(mysql_error($result));

	//2.- Redirecciono
	echo "<script>window.location ='edit_adenda_pdn.php?SES=$SES&anio=$anio&cod=$codigo&modo=add'</script>";	
}
elseif($action==DELETE)
{
	$sql="DELETE FROM pit_bd_ficha_adenda_pdn WHERE cod='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- redirecciono
		echo "<script>window.location ='adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime'</script>";	
}

?>