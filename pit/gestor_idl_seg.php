<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
//1.- Actualizo la IDL
$sql="UPDATE pit_bd_ficha_idl SET f_presentacion_2='".$_POST['f_presentacion']."',contrapartida_2='".$_POST['monto_org']."',cod_estado_iniciativa='006' WHERE cod_ficha_idl='".$_POST['idl']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Ingreso la ficha
$sql="INSERT INTO pit_bd_idl_sd VALUES('','1','".$_POST['idl']."','".$_POST['f_desembolso']."','".$_POST['f_presentacion']."','".$_POST['n_cheque']."','".$_POST['total1']."','".$_POST['total2']."','".$_POST['nivel']."','".$_POST['retraso']."',UPPER('".$_POST['comentario']."'),'','','','')";
$result=mysql_query($sql) or die (mysql_error());

//3.- Busco el ultimo registro generado
$sql="SELECT * FROM pit_bd_idl_sd ORDER BY cod_ficha_sd DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_ficha_sd'];

//4.- redirecciono
echo "<script>window.location ='m_idl_seg.php?SES=$SES&anio=$anio&id=$codigo'</script>";
}
elseif($action==UPDATE)
{
//1.- Actualizo
$sql="UPDATE pit_bd_idl_sd SET f_desembolso='".$_POST['f_desembolso']."',f_presentacion='".$_POST['f_presentacion']."',n_cheque='".$_POST['n_cheque']."',ejec_idl='".$_POST['total1']."',ejec_org='".$_POST['total2']."',pp_avance='".$_POST['nivel']."',cumple_plazo='".$_POST['retraso']."',just_plazo=UPPER('".$_POST['comentario']."') WHERE cod_ficha_sd='$id'";
$result=mysql_query($sql) or die (mysql_error());

//3.- redirecciono
echo "<script>window.location ='idl_seg.php?SES=$SES&anio=$anio&modo=imprime'</script>";

}
elseif($action==ADD_LIQUIDA)
{
	//1.- Realizo el ingreso de informacion
	$sql="INSERT INTO pit_bd_idl_sd VALUES('','2','".$_POST['idl']."','".$_POST['f_desembolso']."','".$_POST['f_presentacion']."','".$_POST['n_cheque']."','".$_POST['total1']."','".$_POST['total2']."','".$_POST['nivel']."','".$_POST['retraso']."',UPPER('".$_POST['comentario1']."'),'".$_POST['calif_1']."','".$_POST['calif_2']."','".$_POST['calif_3']."',UPPER('".$_POST['comentario']."'))";
	$result=mysql_query($sql) or die (mysql_error());
	//2.- Actualizo el estado
	$sql="UPDATE pit_bd_ficha_idl SET cod_estado_iniciativa='004' WHERE cod_ficha_idl='".$_POST['idl']."'";
	$result=mysql_query($sql) or die (mysql_error());
	//3.- Busco el ultimo registro generado
	$sql="SELECT * FROM pit_bd_idl_sd ORDER BY cod_ficha_sd DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_ficha_sd'];
	
//4.- redirecciono
echo "<script>window.location ='m_liquida_idl.php?SES=$SES&anio=$anio&id=$codigo'</script>";		
}
elseif($action==UPDATE_LIQUIDA)
{
	//1.- Actualizo
	$sql="UPDATE pit_bd_idl_sd SET f_desembolso='".$_POST['f_desembolso']."',f_presentacion='".$_POST['f_presentacion']."',n_cheque='".$_POST['n_cheque']."',ejec_idl='".$_POST['total1']."',ejec_org='".$_POST['total2']."',pp_avance='".$_POST['nivel']."',cumple_plazo='".$_POST['retraso']."',just_plazo=UPPER('".$_POST['comentario']."'),calif_1='".$_POST['calif_1']."',calif_2='".$_POST['calif_2']."',calif_3='".$_POST['calif_3']."',just_ejec=UPPER('".$_POST['comentario1']."') WHERE cod_ficha_sd='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='idl_liquida.php?SES=$SES&anio=$anio&modo=imprime'</script>";	
}

?>