<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	$sql="INSERT INTO pit_bd_pdn_liquida VALUES('','".$_POST['iniciativa']."','".$_POST['f_desembolso']."','".$_POST['n_cheque']."','0','0','0','0','0','0','0','".$_POST['hc_soc']."',UPPER('".$_POST['just_soc']."'),'".$_POST['hc_dir']."',UPPER('".$_POST['just_dir']."'),'".$_POST['calificacion']."','".$_POST['f_liquidacion']."',UPPER('".$_POST['comentario']."'))";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Actualizo el estado de la iniciativa
	$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='004' WHERE cod_pdn='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.-Obtengo el ultimo registro generado
	$sql="SELECT * FROM pit_bd_pdn_liquida ORDER BY cod_ficha_liquida DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_ficha_liquida'];
	
	//4.- Redirecciono
	echo "<script>window.location ='m_liquida_pdn.php?SES=$SES&anio=$anio&id=$codigo'</script>";

}
elseif($action==UPDATE)
{
	//1.- Actualizamos el Plan de gestión
	$sql="UPDATE pit_bd_pdn_liquida SET f_desembolso='".$_POST['f_desembolso']."',n_cheque='".$_POST['n_cheque']."',ejec_at_pdss='".$_POST['total1']."',ejec_at_org='".$_POST['total2']."',ejec_pf_pdss='".$_POST['total3']."',ejec_pf_org='".$_POST['total4']."',ejec_vg_pdss='".$_POST['total5']."',ejec_vg_org='".$_POST['total6']."',ejec_ag_pdss='".$_POST['total7']."',hc_soc='".$_POST['hc_soc']."',just_soc='".$_POST['just_soc']."',hc_dir='".$_POST['hc_dir']."',just_dir='".$_POST['just_dir']."',cod_calificacion='".$_POST['calificacion']."',f_liquidacion='".$_POST['f_liquidacion']."',comentario=UPPER('".$_POST['comentario']."') WHERE cod_ficha_liquida='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Busco los datos del PDN
	$sql="SELECT pit_bd_pdn_liquida.cod_pdn, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
	FROM pit_bd_ficha_pdn INNER JOIN pit_bd_pdn_liquida ON pit_bd_ficha_pdn.cod_pdn = pit_bd_pdn_liquida.cod_pdn
	WHERE pit_bd_pdn_liquida.cod_ficha_liquida='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	if($r1['cod_estado_iniciativa']<>004)
	{
		$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='004' WHERE cod_pdn='".$r1['cod_pdn']."'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	

/***********************************************  Información por chequear 2do desembolso   ******************/
//3.- Actualizo la informacion de activos y ventas
foreach($tipo_activos as $cad=>$a1)
{
	$sql="UPDATE ficha_activo_pdn SET cod_tipo_activo='".$_POST['tipo_activos'][$cad]."',descripcion=UPPER('".$_POST['descripcions'][$cad]."'),valor_a='".$_POST['valor_as'][$cad]."',valor_b='".$_POST['valor_bs'][$cad]."',inversion_propia='".$_POST['aporte_propios'][$cad]."',aporte_otro='".$_POST['aporte_otros'][$cad]."' WHERE cod_produccion='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}
foreach($producto_vendidos as $cud=>$b1)
{
	$sql="UPDATE ficha_ventas_pdn SET producto=UPPER('".$_POST['producto_vendidos'][$cud]."'),unidad=UPPER('".$_POST['unidad_productos'][$cud]."'),cantidad_a='".$_POST['cantidad_as'][$cud]."',cantidad_b='".$_POST['cantidad_bs'][$cud]."',valor_a='".$_POST['valoras'][$cud]."',valor_b='".$_POST['valorbs'][$cud]."' WHERE cod_venta='$cud'";
	$result=mysql_query($sql) or die (mysql_error());
}

//4.- Guardo la informacion de ventas
for($i=0;$i<=5;$i++)
{
	if($_POST['producto_vendido'][$i]<>NULL)
	{
		$sql="INSERT INTO ficha_ventas_pdn VALUES('',UPPER('".$_POST['producto_vendido'][$i]."'),UPPER('".$_POST['unidad_producto'][$i]."'),'".$_POST['cantidad_a'][$i]."','".$_POST['cantidad_b'][$i]."','".$_POST['valora'][$i]."','".$_POST['valorb'][$i]."','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}


//5.- Guardo la información de activos
for($i=0;$i<=5;$i++)
{
	if ($_POST['descripcion'][$i]<>NULL)
	{
		
		$sql="INSERT INTO ficha_activo_pdn VALUES('','".$_POST['tipo_activo'][$i]."',UPPER('".$_POST['descripcion'][$i]."'),'".$_POST['valor_a'][$i]."','".$_POST['valor_b'][$i]."','".$_POST['aporte_propio'][$i]."','".$_POST['aporte_otro'][$i]."','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
	}

}

//6.- Registro la informacion de costos de produccion
for($i=0;$i<=3;$i++)
{
	if($_POST['producto'][$i]<>NULL)
	{
		$sql="INSERT INTO ficha_costo_produccion_pdn VALUES('',UPPER('".$_POST['producto'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['rubro1a'][$i]."','".$_POST['rubro1b'][$i]."','".$_POST['rubro2a'][$i]."','".$_POST['rubro2b'][$i]."','".$_POST['rubro3a'][$i]."','".$_POST['rubro3b'][$i]."','".$_POST['rubro4a'][$i]."','".$_POST['rubro4b'][$i]."','".$_POST['rubro5a'][$i]."','".$_POST['rubro5b'][$i]."','".$_POST['iniciativa']."')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

foreach ($productos as $cad => $a) 
{
	$sql="UPDATE ficha_costo_produccion_pdn SET producto=UPPER('".$_POST['productos'][$cad]."'),unidad=UPPER('".$_POST['unidads'][$cad]."'),costo_a='".$_POST['rubro1as'][$cad]."',costo_b='".$_POST['rubro1bs'][$cad]."',costo_c='".$_POST['rubro2as'][$cad]."',costo_d='".$_POST['rubro2bs'][$cad]."',costo_e='".$_POST['rubro3as'][$cad]."',costo_f='".$_POST['rubro3bs'][$cad]."',costo_g='".$_POST['rubro4as'][$cad]."',costo_h='".$_POST['rubro4bs'][$cad]."',costo_i='".$_POST['rubro5as'][$cad]."',costo_j='".$_POST['rubro5bs'][$cad]."' WHERE cod_costo='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}


//6.- Redirecciono
echo "<script>window.location ='pdn_liquida.php?SES=$SES&anio=$anio&modo=edit'</script>";
	
}

elseif($action==DELETE_COSTO)
{
	$sql="DELETE FROM ficha_costo_produccion_pdn WHERE cod_costo='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location ='m_liquida_pdn.php?SES=$SES&anio=$anio&id=$id'</script>";	
}
elseif($action==DELETE_PATRIMONIO)
{
	$sql="DELETE FROM ficha_activo_pdn WHERE cod_produccion='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location ='m_liquida_pdn.php?SES=$SES&anio=$anio&id=$id'</script>";	
}
elseif($action==DELETE_VENTA)
{
	$sql="DELETE FROM ficha_ventas_pdn WHERE cod_venta='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location ='m_liquida_pdn.php?SES=$SES&anio=$anio&id=$id'</script>";		
}


?>