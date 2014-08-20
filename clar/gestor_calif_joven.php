<?
require("../funciones/sesion.php");
require("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM gcac_concurso_clar WHERE cod_concurso='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$factor=$r3['factor'];

if ($action==ADD)
{

	//1.- Ingreso puntajes
	$total_puntaje=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8'];
	
	$sql="INSERT INTO gcac_ficha_jovenes VALUES('','".$_POST['participante']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','$total_puntaje','".$_POST['jurado']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Realizo promedios
	$sql="SELECT AVG(gcac_ficha_jovenes.p_total) AS promedio
	FROM gcac_ficha_jovenes
	WHERE gcac_ficha_jovenes.cod_participante='".$_POST['participante']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$puntaje=$r1['promedio']*$factor;
	
	//3.- Actualizo
	$sql="UPDATE gcac_participante_concurso SET puntaje='$puntaje' WHERE cod_participante='".$_POST['participante']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_calif_joven.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==DELETE)
{
	$sql="SELECT * FROM gcac_ficha_jovenes WHERE cod_ficha='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	$sql="DELETE FROM gcac_ficha_jovenes WHERE cod_ficha='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Realizo promedios
	$sql="SELECT AVG(gcac_ficha_jovenes.p_total) AS promedio
	FROM gcac_ficha_jovenes
	WHERE gcac_ficha_jovenes.cod_participante='".$r2['participante']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$puntaje=$r1['promedio']*$factor;
	
	//3.- Actualizo
	$sql="UPDATE gcac_participante_concurso SET puntaje='$puntaje' WHERE cod_participante='".$r2['participante']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='n_calif_joven.php?SES=$SES&anio=$anio&cod=$cod'</script>";
	
	
}

?>