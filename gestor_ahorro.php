<?php
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if($tipo==SOL and $action==ADD)
{
	//1.- Actualizamos la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Procedo a ingresar la informacion
	$sql="INSERT INTO mh_bd_desembolso VALUES('','".$row['cod_dependencia']."','".$_POST['n_solicitud']."','".$_POST['f_solicitud']."','".$_POST['poa']."','".$_POST['fte_fto']."','".$_POST['incentivo']."')";
	$result=mysql_query($sql) or die (mysql_error());

	//3.- Extraigo el registro generado
	$sql="SELECT * FROM mh_bd_desembolso ORDER BY cod DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$codigo=$r1['cod'];

	//4.- Registro los datos de las atf's
	for($i=0;$i<=30;$i++)
	{
		if($_POST['grupo'][$i]<>NULL)
		{
			$organizacion=$_POST['grupo'][$i];
			$dato=explode(",",$organizacion);
			$tipo_documento=$dato[0];
			$n_documento=$dato[1];

			$sql="INSERT INTO mh_bd_grupo VALUES('','$tipo_documento','$n_documento','".$_POST['n_ahorrista'][$i]."','".$_POST['monto'][$i]."','".$_POST['contrapartida'][$i]."','".$_POST['ifi'][$i]."','','$codigo')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}

	//5.- Redirecciono 
	echo "<script>window.location ='form_ahorro.php?SES=$SES&anio=$anio&tipo=EDIT_SOL&cod=$codigo'</script>";	
}
elseif($action==UPDATE)
{
	//1.- Guardamos la informacion de la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."' WHERE cod='".$_POST['cod']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Actualizamos la informacion
	foreach ($grupo as $cad => $a) 
	{
		$sql="UPDATE mh_bd_grupo SET n_ahorristas='".$_POST['n_ahorrista'][$cad]."',monto='".$_POST['monto'][$cad]."',contrapartida='".$_POST['contrapartida'][$cad]."',n_atf='".$_POST['n_at'][$cad]."' WHERE cod='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	$codigo=$_POST['codigo'];

	//3.- Redirecciono 
	echo "<script>window.location ='print/print_ahorro.php?SES=$SES&anio=$anio&cod=$codigo'</script>";		
}
elseif($action==UPDATE_MODULO)
{
	//1.- actualizo la info de desembolso
	$sql="UPDATE mh_bd_desembolso SET cod_poa='".$_POST['poa']."',fte_fto='".$_POST['fte_fto']."',cod_tipo_incentivo='".$_POST['incentivo']."' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- actualizo las atf
	foreach ($grupo as $cad => $a) 
	{
		$sql="UPDATE mh_bd_grupo SET n_ahorristas='".$_POST['n_ahorrista'][$cad]."',monto='".$_POST['monto'][$cad]."',contrapartida='".$_POST['contrapartida'][$cad]."' WHERE cod='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	$codigo=$_POST['codigo'];

	//3.- Redirecciono 
	echo "<script>window.location ='print/print_ahorro.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	

}

?>