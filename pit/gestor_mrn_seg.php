<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	//1.- Guardo la Info de Segundo Desembolso
	$sql="INSERT INTO pit_bd_mrn_sd VALUES('','1','".$_POST['mrn']."','".$_POST['f_desembolso']."','".$_POST['n_cheque']."','','','','','','','".$_POST['hc_soc']."','".$_POST['just_soc']."','".$_POST['hc_dir']."','".$_POST['just_dir']."','".$_POST['mes']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//2.- Actualizo el estado de la iniciativa
	$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='006',f_presentacion_2='".$_POST['f_presentacion']."',n_voucher_2='".$_POST['n_voucher']."',monto_organizacion_2='".$_POST['monto_org']."' WHERE cod_mrn='".$_POST['mrn']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//3.- Busco el ultimo registro generado
	$sql="SELECT * FROM pit_bd_mrn_sd ORDER BY cod_ficha_sd DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_ficha_sd'];
	
	//4.-Redirecciono
	echo "<script>window.location ='m_pgrn_seg.php?SES=$SES&anio=$anio&id=$codigo'</script>";	
}
elseif($action==UPDATE)
{
	//1.- Actualizo la info de Segundo desembolso
	$sql="UPDATE pit_bd_mrn_sd SET f_desembolso='".$_POST['f_desembolso']."',n_cheque='".$_POST['n_cheque']."',ejec_cif_pdss='".$_POST['total1']."',ejec_at_pdss='".$_POST['total2']."',ejec_at_org='".$_POST['total3']."',ejec_vg_pdss='".$_POST['total4']."',ejec_vg_org='".$_POST['total5']."',ejec_ag_pdss='".$_POST['total6']."',hc_soc='".$_POST['hc_soc']."',just_soc='".$_POST['just_soc']."',hc_dir='".$_POST['hc_dir']."',just_dir='".$_POST['just_dir']."',mes='".$_POST['mes']."' WHERE cod_ficha_sd='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Actualizo el PGRN
	$sql="UPDATE pit_bd_ficha_mrn SET f_presentacion_2='".$_POST['f_presentacion']."',n_voucher_2='".$_POST['n_voucher']."',monto_organizacion_2='".$_POST['monto_org']."' WHERE cod_mrn='".$_POST['mrn']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//3.- Redirecciono
	echo "<script>window.location ='pgrn_seg.php?SES=$SES&anio=$anio&modo=imprime'</script>";	
}




?>