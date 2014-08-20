<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	//1.- Buscar la numeracion
	$sql="SELECT pit_bd_ficha_adenda.cod_adenda
	FROM pit_bd_ficha_adenda
	WHERE pit_bd_ficha_adenda.cod_pit='".$_POST['pit']."'";
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
	$sql="INSERT INTO pit_bd_ficha_adenda VALUES('','$n_adenda','".$_POST['f_adenda']."','".$_POST['pit']."','".$_POST['referencia']."','1','".$_POST['f_inicio']."','".$_POST['mes']."','$f_termino','','','','','','005')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.-Obtengo el ultimo registro generado
	$sql="SELECT * FROM pit_bd_ficha_adenda ORDER BY cod_adenda DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_adenda'];
	
	//5.- Redirecciono	
	echo "<script>window.location ='edit_adenda_plazo.php?SES=$SES&anio=$anio&cod=$codigo&modo=add'</script>";

}
elseif($action==EDIT)
{
	$sql="UPDATE pit_bd_ficha_adenda SET contenido='".$_POST['contenido']."' WHERE cod_adenda='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='../print/print_adenda_plazo.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==UPDATE)
{
	//1.- Calculo fecha de termino
	$fecha=$_POST['f_inicio'];
	$mes=$_POST['mes'];

	$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
	$f_termino=dateadd1($fecha,1,$mes,0,0,0,0);
	
	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-15';
	}
	else
	{
		$f_termino=$f_termino;
	}	
	
	//2.- Actualizamos la información
	$sql="UPDATE pit_bd_ficha_adenda SET f_adenda='".$_POST['f_adenda']."',referencia=UPPER('".$_POST['referencia']."'),f_inicio='".$_POST['f_inicio']."',meses='".$_POST['mes']."',f_termino='$f_termino' WHERE cod_adenda='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	$codigo=$_POST['codigo'];
	
	//5.- Redirecciono	
	echo "<script>window.location ='edit_adenda_plazo.php?SES=$SES&anio=$anio&cod=$codigo&modo=add'</script>";

}
elseif($action==DELETE)
{
	//1.- 
	$sql="DELETE FROM pit_bd_ficha_adenda WHERE cod_adenda='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='adenda_plazo.php?SES=$SES&anio=$anio&modo=elimina'</script>";
}




?>