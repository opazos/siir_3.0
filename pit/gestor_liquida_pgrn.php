<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	//1.- Ingresamos la info de liquidacion
	$sql="INSERT INTO pit_bd_mrn_liquida VALUES('','".$_POST['iniciativa']."','".$_POST['f_desembolso']."','".$_POST['n_cheque']."','','','','','','','".$_POST['hc_socio']."','".$_POST['just_socio']."','".$_POST['hc_dir']."','".$_POST['just_dir']."',UPPER('".$_POST['observaciones']."'),'".$_POST['calificacion']."','".$_POST['f_liquidacion']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Actualizamos el estado de la iniciativa
	$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='004' WHERE cod_mrn='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Busco el ultimo registro generado
	$sql="SELECT * FROM pit_bd_mrn_liquida ORDER BY cod_ficha_liquida DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_ficha_liquida'];
	
	//3.- Redirecciono
	echo "<script>window.location ='m_liquida_pgrn.php?SES=$SES&anio=$anio&id=$codigo'</script>";
}
elseif($action==UPDATE)
{
	//1.- Actualizamos
	$sql="UPDATE pit_bd_mrn_liquida SET f_desembolso='".$_POST['f_desembolso']."',n_cheque='".$_POST['n_cheque']."',ejec_cif_pdss='".$_POST['total1']."',ejec_at_pdss='".$_POST['total2']."',ejec_at_org='".$_POST['total3']."',ejec_vg_pdss='".$_POST['total4']."',ejec_vg_org='".$_POST['total5']."',ejec_ag_pdss='".$_POST['total6']."',hc_soc='".$_POST['hc_socio']."',just_soc='".$_POST['just_socio']."',hc_dir='".$_POST['hc_dir']."',just_dir='".$_POST['just_dir']."',observaciones=UPPER('".$_POST['observaciones']."'),cod_calificacion='".$_POST['calificacion']."',f_liquidacion='".$_POST['f_liquidacion']."' WHERE cod_ficha_liquida='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Busco la informacion del PGRN
	$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
	pit_bd_ficha_mrn.cod_estado_iniciativa
	FROM pit_bd_ficha_mrn INNER JOIN pit_bd_mrn_liquida ON pit_bd_ficha_mrn.cod_mrn = pit_bd_mrn_liquida.cod_mrn
	WHERE pit_bd_mrn_liquida.cod_ficha_liquida='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	if ($r1['cod_estado_iniciativa']<>004)
	{
		$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='004' WHERE cod_mrn='".$r1['cod_mrn']."'";
		$result=mysql_query($sql) or die (mysql_error());
	}

	//3.-redireccionamos
	echo "<script>window.location ='pgrn_liquida.php?SES=$SES&anio=$anio&modo=imprime'</script>";
}

?>