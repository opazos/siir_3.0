<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	
	//1.- Ingreso la info del PIT a la base de datos
	$sql="INSERT INTO pit_bd_pit_liquida VALUES('','1','".$_POST['pit']."','".$_POST['f_desembolso']."','".$_POST['n_cheque']."','','".$_POST['hc_dir']."','".$_POST['just_dir']."','".$_POST['f_liquidacion']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Actualizo el estado del contrato
	$sql="UPDATE pit_bd_ficha_pit SET cod_estado_iniciativa='004' WHERE cod_pit='".$_POST['pit']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//3.- Busco el ultimo registro generado
	$sql="SELECT * FROM pit_bd_pit_liquida ORDER BY cod_ficha_liq DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_ficha_liq'];
	
	//4.- Redirecciono
	echo "<script>window.location ='m_liquida_pit.php?SES=$SES&anio=$anio=cod=$codigo'</script>";
	
	
}



?>