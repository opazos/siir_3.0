<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	
	//1.- Registro la información del PIT
	$sql="INSERT INTO pit_bd_pit_liquida VALUES ('','".$_POST['tipo_liquidacion']."','".$_POST['pit']."','".$_POST['f_desembolso']."','".$_POST['n_cheque']."','','','".$_POST['hc_dir']."',UPPER('".$_POST['just_dir']."'),UPPER('".$_POST['resultado']."'),UPPER('".$_POST['comentario']."'),'".$_POST['f_liquidacion']."','".$_POST['mapa']."','".$_POST['uso_mapa']."','".$_POST['concurso']."','".$_POST['puesto']."','".$_POST['premio']."')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Actualizo el estado del PIT
	$sql="UPDATE pit_bd_ficha_pit SET cod_estado_iniciativa='004' WHERE cod_pit='".$_POST['pit']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//3.- Busco el ultimo registro generado
	$sql="SELECT * FROM pit_bd_pit_liquida ORDER BY cod_ficha_liq DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_ficha_liq'];
	
	//4.- Redirecciono
	echo "<script>window.location ='m_liquida_pit.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==UPDATE)
{
	//1.- Actualizo la información de liquidacion del PIT
	$sql="UPDATE pit_bd_pit_liquida SET cod_tipo='".$_POST['tipo_liquidacion']."',f_desembolso='".$_POST['f_desembolso']."',n_cheque='".$_POST['n_cheque']."',ejec_an='".$_POST['monto_ejecutado']."',devolucion='".$_POST['saldo']."',hc_dir='".$_POST['hc_dir']."',just_dir=UPPER('".$_POST['just_dir']."'),resultado=UPPER('".$_POST['resultado']."'),comentario=UPPER('".$_POST['comentario']."'),f_liquidacion='".$_POST['f_liquidacion']."',aplicacion='".$_POST['mapa']."',uso='".$_POST['uso_mapa']."',concurso='".$_POST['concurso']."',puesto='".$_POST['puesto']."',premio='".$_POST['premio']."' WHERE cod_ficha_liq='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	$codigo=$_POST['codigo'];
	
	//2.-Redirecciono a la impresion
	echo "<script>window.location ='../print/print_liquida_pit.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
	
}



?>