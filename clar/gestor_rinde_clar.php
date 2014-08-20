<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	//1.- Ingreso la informacion de la rendicion
	$sql="INSERT INTO clar_bd_rinde_clar VALUES('','".$_POST['cod_clar']."','".$_POST['f_rendicion']."','".$_POST['resultado']."','".$_POST['problema']."','".$_POST['dj']."','','".$_POST['monto_devuelto']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Actualizo el estado del evento
	$sql="UPDATE clar_bd_evento_clar SET estado='1' WHERE cod_clar='".$_POST['cod_clar']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Obtengo el ultimo registro generado
	$sql="SELECT * FROM clar_bd_rinde_clar ORDER BY cod_clar DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_rinde_clar'];
	
	//4.- Realizo el insert de las entidades participantes
	for($i=0;$i<=5;$i++)
	{
		if($_POST['entidad'][$i]<>NULL)
		{
			
			$sql="INSERT INTO clar_bd_cofi_clar VALUES('','".$_POST['entidad'][$i]."','".$_POST['tipo_ente'][$i]."','".$_POST['monto'][$i]."','$codigo')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	
	//5.- Redirecciono
	echo "<script>window.location ='n_detalle_clar.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==ADD_DETALLE)
{
	//1.- Añado el detalle
	$sql="INSERT INTO clar_bd_detalle_gasto_clar VALUES('','".$_POST['f_detalle']."','".$_POST['concepto']."',UPPER('".$_POST['proveedor']."'),'".$_POST['ruc']."','".$_POST['serie']."','".$_POST['n_documento']."',UPPER('".$_POST['detalle']."'),'".$_POST['tipo_doc']."','".$_POST['monto']."','$cod')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='n_detalle_clar.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==DELETE_DETALLE)
{
	$sql="DELETE FROM clar_bd_detalle_gasto_clar WHERE cod_detalle_clar='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='n_detalle_clar.php?SES=$SES&anio=$anio&cod=$cod'</script>";	
}
elseif($action==DELETE_COF)
{
	$sql="DELETE FROM clar_bd_cofi_clar WHERE cod_cofi_clar='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='m_rinde_clar.php?SES=$SES&anio=$anio&id=$id'</script>";		
}
elseif($action==UPDATE)
{
	//1.- Actualizo
	$sql="UPDATE clar_bd_rinde_clar SET f_rendicion='".$_POST['f_rendicion']."',resultado='".$_POST['resultado']."',problema='".$_POST['problema']."',cod_dj='".$_POST['dj']."',devolucion='".$_POST['monto_devuelto']."' WHERE cod_rinde_clar='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	$codigo=$_POST['codigo'];
	
	//2.- Actualizo entidades cofinanciadoras con un foreach
	foreach($entidads as $cod=>$a)
	{
		$sql="UPDATE clar_bd_cofi_clar SET entidad='".$_POST['entidads'][$cod]."',cod_ente='".$_POST['tipo_entes'][$cod]."',aporte='".$_POST['montos'][$cod]."' WHERE cod_cofi_clar='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	//3.- Ingreso nuevos entes
	for($i=0;$i<=5;$i++)
	{
		if($_POST['entidad'][$i]<>NULL)
		{
			
			$sql="INSERT INTO clar_bd_cofi_clar VALUES('','".$_POST['entidad'][$i]."','".$_POST['tipo_ente'][$i]."','".$_POST['monto'][$i]."','$codigo')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	
	//5.- Redirecciono
	echo "<script>window.location ='n_detalle_clar.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
		
	
}
elseif($action==ADD_LIQUIDA)
{
	//1.- Ingreso la info
	$sql="INSERT INTO clar_bd_liquida_clar VALUES('','".$_POST['cod_clar']."','".$_POST['f_rendicion']."','".$_POST['resultado']."','".$_POST['problema']."','".$_POST['ejec_pdss']."','".$_POST['ejec_org']."','".$_POST['ejec_mun']."','".$_POST['ejec_otro']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Actualizo el evento CLAR
	$sql="UPDATE clar_bd_evento_clar SET estado='1' WHERE cod_clar='".$_POST['cod_clar']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Busco el ultimo registro generado
	$sql="SELECT * FROM clar_bd_liquida_clar ORDER BY cod_liquida DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	$codigo=$r1['cod_liquida'];
	
	//4.- Redirecciono
	echo "<script>window.location ='../print/print_liquida_clar.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==UPDATE_LIQUIDA)
{
	//1.- Actualizo la info
	$sql="UPDATE clar_bd_liquida_clar SET f_liquidacion='".$_POST['f_rendicion']."',resultados='".$_POST['resultado']."',problemas='".$_POST['problema']."',ejec_pdss='".$_POST['ejec_pdss']."',ejec_org='".$_POST['ejec_org']."',ejec_mun='".$_POST['ejec_mun']."',ejec_otro='".$_POST['ejec_otro']."' WHERE cod_liquida='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die(mysql_error());
	
	$codigo=$_POST['codigo'];
	
	//2.- Redirecciono
	echo "<script>window.location ='../print/print_liquida_clar.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
?>