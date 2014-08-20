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
	if($_POST['org']==NULL and $_POST['tipo']==NULL and $_POST['ifi']==NULL)
	{
		echo "<script>window.location ='n_idl.php?SES=$SES&anio=$anio&error=vacio'</script>";		
	}
	else
	{
		//1.- Realizo el registro de la IDL
		$sql="INSERT INTO pit_bd_ficha_idl VALUES('','6','$tipo_documento','$n_documento',UPPER('".$_POST['denominacion']."'),'".$_POST['tipo']."',UPPER('".$_POST['objetivo']."'),'".$_POST['n_familia']."','".$_POST['f_inicio']."','".$_POST['f_termino']."','1','".$_POST['n_cuenta']."','".$_POST['ifi']."','','','','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."','','','','','','','','','001')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- obtengo el codigo
		$sql="SELECT * FROM pit_bd_ficha_idl ORDER BY cod_ficha_idl DESC LIMIT 0,1";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		$codigo=$r1['cod_ficha_idl'];
		
		//3.-Guardo la info de actividades de la IDL
		for($i=0;$i<=15;$i++)
		{
			if ($_POST['actividad_prog'][$i]<>NULL)
			{
				$sql="INSERT INTO idl_actividad_idl VALUES('','$codigo',UPPER('".$_POST['actividad_prog'][$i]."'),'1','0','0','','".$_POST['ene'][$i]."','".$_POST['feb'][$i]."','".$_POST['mar'][$i]."','".$_POST['abr'][$i]."','".$_POST['may'][$i]."','".$_POST['jun'][$i]."','".$_POST['jul'][$i]."','".$_POST['ago'][$i]."','".$_POST['sep'][$i]."','".$_POST['oct'][$i]."','".$_POST['nov'][$i]."','".$_POST['dic'][$i]."')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		//4.- Guardo la info del presupuesto detallado
		for($i=0;$i<=20;$i++)
		{
			if ($_POST['concepto'][$i]<>NULL)
			{
				$total=$_POST['cantidad_gasto'][$i]*$_POST['precio'][$i];
				
				$sql="INSERT INTO idl_detalle_financiamiento VALUES('','$codigo',UPPER('".$_POST['concepto'][$i]."'),UPPER('".$_POST['unidad_gasto'][$i]."'),'".$_POST['cantidad_gasto'][$i]."','','".$_POST['precio'][$i]."','','$total','')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		//5.- Guardo la informacion de metas fisicas
		for($i=0;$i<=15;$i++)
		{
			if ($_POST['actividad'][$i]<>NULL)
			{
				$sql="INSERT INTO idl_meta_fisica VALUES('','$codigo',UPPER('".$_POST['actividad'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['meta'][$i]."','')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		
		//6.- redireccionamos
		echo "<script>window.location ='idl.php?SES=$SES&anio=$anio&modo=edit'</script>";		
	}
}
elseif($action==UPDATE)
{
//1.- actualizo la informacion de la idl
$sql="UPDATE pit_bd_ficha_idl SET denominacion=UPPER('".$_POST['denominacion']."'),cod_tipo_idl='".$_POST['tipo']."',objetivo=UPPER('".$_POST['objetivo']."'),familias='".$_POST['n_familia']."',f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."' WHERE cod_ficha_idl='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.-Actualizo la informacion de cada uno de los 3 rubros
foreach($actividads as $cad=>$a1)
{
	$sql="UPDATE idl_meta_fisica SET descripcion=UPPER('".$_POST['actividads'][$cad]."'),unidad=UPPER('".$_POST['unidads'][$cad]."'),meta=UPPER('".$_POST['metas'][$cad]."') WHERE cod_meta='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}

foreach($actividad_progs as $ced=>$b1)
{
	$sql="UPDATE idl_actividad_idl SET descripcion=UPPER('".$_POST['actividad_progs'][$ced]."'),m1='".$_POST['enes'][$ced]."',m2='".$_POST['febs'][$ced]."',m3='".$_POST['mars'][$ced]."',m4='".$_POST['abrs'][$ced]."',m5='".$_POST['mays'][$ced]."',m6='".$_POST['juns'][$ced]."',m7='".$_POST['juls'][$ced]."',m8='".$_POST['agos'][$ced]."',m9='".$_POST['seps'][$ced]."',m10='".$_POST['octs'][$ced]."',m11='".$_POST['novs'][$ced]."',m12='".$_POST['dics'][$ced]."' WHERE cod_actividad='$ced'";
	$result=mysql_query($sql) or die (mysql_error());
}

foreach($conceptos as $cid=>$c1)
{
	$total_gasto=$_POST['cantidad_gastos'][$cid]*$_POST['precios'][$cid];
	
	$sql="UPDATE idl_detalle_financiamiento SET descripcion=UPPER('".$_POST['conceptos'][$cid]."'),unidad=UPPER('".$_POST['unidad_gastos'][$cid]."'),cantidad_1='".$_POST['cantidad_gastos'][$cid]."',costo_unitario_1='".$_POST['precios'][$cid]."',costo_total_1='$total_gasto'";
	$result=mysql_query($sql) or die (mysql_error());
}

//3.-Guardo la info de actividades de la IDL
for($i=0;$i<=15;$i++)
	{
		if ($_POST['actividad_prog'][$i]<>NULL)
		{
			$sql="INSERT INTO idl_actividad_idl VALUES('','$codigo',UPPER('".$_POST['actividad_prog'][$i]."'),'1','0','0','','".$_POST['ene'][$i]."','".$_POST['feb'][$i]."','".$_POST['mar'][$i]."','".$_POST['abr'][$i]."','".$_POST['may'][$i]."','".$_POST['jun'][$i]."','".$_POST['jul'][$i]."','".$_POST['ago'][$i]."','".$_POST['sep'][$i]."','".$_POST['oct'][$i]."','".$_POST['nov'][$i]."','".$_POST['dic'][$i]."')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
//4.- Guardo la info del presupuesto detallado
for($i=0;$i<=20;$i++)
	{
		if ($_POST['concepto'][$i]<>NULL)
		{
			$total=$_POST['cantidad_gasto'][$i]*$_POST['precio'][$i];
				
			$sql="INSERT INTO idl_detalle_financiamiento VALUES('','$codigo',UPPER('".$_POST['concepto'][$i]."'),UPPER('".$_POST['unidad_gasto'][$i]."'),'".$_POST['cantidad_gasto'][$i]."','','".$_POST['precio'][$i]."','','$total')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
//5.- Guardo la informacion de metas fisicas
for($i=0;$i<=15;$i++)
	{
		if ($_POST['actividad'][$i]<>NULL)
		{
			$sql="INSERT INTO idl_meta_fisica VALUES('','$codigo',UPPER('".$_POST['actividad'][$i]."'),UPPER('".$_POST['unidad'][$i]."'),'".$_POST['meta'][$i]."','')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
		
//6.- redireccionamos
echo "<script>window.location ='idl.php?SES=$SES&anio=$anio&modo=edit'</script>";
}
elseif($action==DELETE)
{
	$sql="DELETE FROM pit_bd_ficha_idl WHERE cod_ficha_idl='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='idl.php?SES=$SES&anio=$anio&modo=edit'</script>";
	
}

?>