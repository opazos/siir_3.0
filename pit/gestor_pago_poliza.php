<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{
	//1.- Guardo la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Procedo a guardar la info del pago
	$sql="INSERT INTO sf_bd_pago_poliza VALUES('','".$_POST['f_solicitud']."','".$_POST['poa']."','".$_POST['n_solicitud']."','".$_POST['n_atf']."','".$_POST['n_cuenta']."','".$_POST['ifi']."','".$_POST['aseguradora']."','','".$row['cod_dependencia']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Obtengo el numero de la solicitud de pago
	$sql="SELECT * FROM sf_bd_pago_poliza ORDER BY cod_pago DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_pago'];
	
	//4.- Procedo a guardar la info de seguros
	for($i=0;$i<count($_POST['campos']);$i++) 
	{
		$sql="INSERT INTO sf_bd_usuario_seguro VALUES('','$codigo','".$_POST['campos'][$i]."','')";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//5.- Procedo a actualizar la informacion de asegurados
	for($i=0;$i<count($_POST['campos']);$i++) 
	{
		$sql="UPDATE sf_bd_poliza SET cod_estado_iniciativa='005' WHERE cod_poliza='".$_POST['campos'][$i]."'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//6.- Redirecciono
	echo "<script>window.location ='../print/print_pago_poliza.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
	
	
}
elseif($action==DESVINCULAR)
{
	//1.- Busco la info
	$sql="SELECT sf_bd_usuario_seguro.cod_poliza
	FROM sf_bd_usuario_seguro
	WHERE sf_bd_usuario_seguro.cod_usuario='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//2.- Actualizo la poliza
	$sql="UPDATE sf_bd_poliza SET cod_estado_iniciativa='002' WHERE cod_poliza='".$r1['cod_poliza']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Elimino la info
	$sql="DELETE FROM sf_bd_usuario_seguro WHERE cod_usuario='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Redirecciono
	echo "<script>window.location ='m_pago_poliza.php?SES=$SES&anio=$anio&id=$id'</script>";
}
elseif($action==UPDATE)
{

	//1.- Actualizo la informacion del pago
	$sql="UPDATE sf_bd_pago_poliza SET f_solicitud='".$_POST['f_solicitud']."',cod_poa='".$_POST['poa']."',n_solicitud='".$_POST['n_solicitud']."',n_atf='".$_POST['n_atf']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',cod_aseguradora='".$_POST['aseguradora']."' WHERE cod_pago='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	$codigo=$id;
	
	//2.- Procedo a guardar la info de seguros
	for($i=0;$i<count($_POST['campos']);$i++) 
	{
		$sql="INSERT INTO sf_bd_usuario_seguro VALUES('','$codigo','".$_POST['campos'][$i]."','')";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//3.- Procedo a actualizar la informacion de asegurados
	for($i=0;$i<count($_POST['campos']);$i++) 
	{
		$sql="UPDATE sf_bd_poliza SET cod_estado_iniciativa='005' WHERE cod_poliza='".$_POST['campos'][$i]."'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//4.- Redirecciono
	echo "<script>window.location ='../print/print_pago_poliza.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
	

}


?>