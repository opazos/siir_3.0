<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$organizacion=$_POST['org'];
$dato=explode(",",$organizacion);

$tipo_documento=$dato[0];
$n_documento=$dato[1];

if ($action==ADD)
{
//1.- Actualizo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_iniciativa='".$_POST['n_contrato']."',n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Busco el componente y la categoria
$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.cod_categoria_poa, 
	sys_bd_subcomponente_poa.relacion
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
WHERE sys_bd_subactividad_poa.cod='".$_POST['poa']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//3.- Ingreso la info
$sql="INSERT INTO ml_promocion_c VALUES('','8',UPPER('".$_POST['evento']."'),'".$_POST['f_inicio']."','".$_POST['f_termino']."','".$_POST['select1']."','".$_POST['select2']."','".$_POST['select3']."',UPPER('".$_POST['direccion']."'),'".$_POST['participantes']."',UPPER('".$_POST['objetivo']."'),UPPER('".$_POST['resultado']."'),'".$_POST['f_contrato']."','".$_POST['n_contrato']."','".$_POST['n_atf']."','".$_POST['n_solicitud']."','".$r2['relacion']."','".$_POST['poa']."','".$r2['cod_categoria_poa']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."','".$_POST['fte_fida']."','".$_POST['fte_ro']."','".$_POST['ifi']."','".$_POST['n_cuenta']."','005','".$_POST['f_contrato']."','$tipo_documento','$n_documento')";
$result=mysql_query($sql) or die (mysql_error());

//4.- Busco el ultimo registro
$sql="SELECT * FROM ml_promocion_c ORDER BY cod_evento DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_evento'];

//5.- Redirecciono
	echo "<script>window.location ='../print/print_contrato_pc.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}
elseif($action==UPDATE)
{
//1.- Busco el codigo POA
$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.cod_categoria_poa, 
	sys_bd_subcomponente_poa.relacion
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
WHERE sys_bd_subactividad_poa.cod='".$_POST['poa']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//1.- Actualizo la informacion del contrato
$sql="UPDATE ml_promocion_c SET nombre=UPPER('".$_POST['evento']."'),f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',cod_dep='".$_POST['select1']."',cod_prov='".$_POST['select2']."',cod_dist='".$_POST['select3']."',lugar=UPPER('".$_POST['direccion']."'),participantes='".$_POST['participantes']."',objetivo=UPPER('".$_POST['objetivo']."'),resultado=UPPER('".$_POST['resultado']."'),f_contrato='".$_POST['f_contrato']."',n_contrato='".$_POST['n_contrato']."',n_atf='".$_POST['n_atf']."',n_solicitud='".$_POST['n_solicitud']."',cod_componente='".$r1['relacion']."',cod_subactividad='".$_POST['poa']."',cod_categoria='".$r1['cod_categoria_poa']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."',fte_fida='".$_POST['fida']."',fte_ro='".$_POST['ro']."',cod_ifi='".$_POST['ifi']."',n_cuenta='".$_POST['n_cuenta']."' WHERE cod_evento='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- Redirecciono
	echo "<script>window.location ='../print/print_contrato_pc.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	
}
elseif($action==ANULA)
{
//1.- Anulo el contrato
$sql="UPDATE ml_promocion_c SET cod_estado_iniciativa='000' WHERE cod_evento='$id'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Redirecciono
	echo "<script>window.location ='contrato_pc.php?SES=$SES&anio=$anio&modo=imprime'</script>";	
}
elseif($action==LIQUIDA)
{
$sql="SELECT * FROM ml_promocion_c WHERE cod_evento='".$_POST['n_contrato']."'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//2.- Actualizo la informacion
$sql="INSERT INTO ml_liquida_pc VALUES('','".$_POST['n_contrato']."','".$_POST['f_liquidacion']."',UPPER('".$_POST['resultado']."'),UPPER('".$_POST['problema']."'),'".$_POST['ejec_pdss']."','".$_POST['ejec_org']."','".$_POST['apoyo']."','".$_POST['entidad']."','".$_POST['participantes']."','".$_POST['publico']."','".$_POST['ingreso']."')";
$result=mysql_query($sql) or die (mysql_error());

//3.- Busco el registro generado
$sql="SELECT * FROM ml_liquida_pc ORDER BY cod_liquida_pc DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_liquida_pc'];

//4.- ingreso la info del detalle
for($i=0;$i<=20;$i++)
{
if ($_POST['concepto'][$i]<>NULL)
{

$total=$_POST['cantidad'][$i]*$_POST['costo'][$i];

$sql="INSERT INTO ml_presupuesto_pc VALUES('',UPPER('".$_POST['concepto'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['costo'][$i]."','".$_POST['cantidad'][$i]."','$total','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}

}

//5.- Procedo a actualizar el estado de la iniciativa
$sql="UPDATE ml_promocion_c SET cod_estado_iniciativa='004' WHERE cod_evento='".$_POST['n_contrato']."'";
$result=mysql_query($sql) or die (mysql_error());

//6.- Redirecciono
	echo "<script>window.location ='../print/print_liquida_pc.php?SES=$SES&anio=$anio&cod=$codigo'</script>";	

}
elseif($action==UPDATE_LIQUIDA)
{
	//1.- Actualizamos lo datos de liquidacion
	$sql="UPDATE ml_liquida_pc SET f_rendicion='".$_POST['f_liquidacion']."',resultado='".$_POST['resultado']."',problema='".$_POST['problema']."',ejec_pdss='".$_POST['ejec_pdss']."',ejec_org='".$_POST['ejec_org']."',ejec_otro='".$_POST['apoyo']."',entidad_otro='".$_POST['entidad']."',participantes='".$_POST['participantes']."',publico='".$_POST['publico']."',ingresos='".$_POST['ingreso']."' WHERE cod_liquida_pc='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.-Actualizo los registros del presupuesto
	foreach($conceptos as $cad=>$a)
	{
		$total_costo=$_POST['costos'][$cad]*$_POST['cantidads'][$cad];
		
		$sql="UPDATE ml_presupuesto_pc SET descripcion='".$_POST['conceptos'][$cad]."',unidad='".$_POST['unidads'][$cad]."',costo_unitario='".$_POST['costos'][$cad]."',cantidad='".$_POST['cantidads'][$cad]."',costo_total='$total_costo' WHERE cod_presupuesto='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
		
	}
	
	//3.- Registramos los nuevos registros
	for($i=0;$i<=10;$i++)
	{
		if ($_POST['concepto'][$i]<>NULL)
		{
			$total=$_POST['cantidad'][$i]*$_POST['costo'][$i];

			$sql="INSERT INTO ml_presupuesto_pc VALUES('',UPPER('".$_POST['concepto'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['costo'][$i]."','".$_POST['cantidad'][$i]."','$total','$id')";
			$result=mysql_query($sql) or die (mysql_error());

		}
	}
	
	
	//5.- Redirecciono
	echo "<script>window.location ='../print/print_liquida_pc.php?SES=$SES&anio=$anio&cod=$id'</script>";
}


?>
