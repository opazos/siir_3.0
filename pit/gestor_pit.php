<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
$organizacion=$_POST['territorio'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];

$aporte_pdss=$_POST['n_animador']*$_POST['monto_animador']*$_POST['n_mes'];

//1.- Guardo la info del PIT
if ($modo==pit)
{
$sql="INSERT INTO pit_bd_ficha_pit VALUES('','3','$tipo_documento','$n_documento','".$_POST['f_presentacion']."','0','0000-00-00','0','0000-00-00','0','".$_POST['n_animador']."','".$_POST['monto_animador']."','".$_POST['n_mes']."','$aporte_pdss','".$_POST['aporte_org']."','0','0','1','".$_POST['n_cuenta']."','".$_POST['ifi']."','".$_POST['mapa']."','0','0','0','0','001','".$_POST['n_voucher']."','".$_POST['deposito']."','0000-00-00','','0','0','0')";
}
else
{
$sql="INSERT INTO pit_bd_ficha_pit VALUES('','3','$tipo_documento','$n_documento','".$_POST['f_presentacion']."','0','0000-00-00','0','0000-00-00','0','0','0','0','0','0','0','0','0','','13','".$_POST['mapa']."','0','0','0','0','001','','0','0000-00-00','','0','1','0')";
}
$result=mysql_query($sql) or die (mysql_error());

//2.- obtengo el ultimo registro generado
$sql="SELECT * FROM pit_bd_ficha_pit ORDER BY cod_pit DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_pit'];

//3.- Inserto el animador territorial
for ($i = 0; $i <= 3; $i++) 
{
if ($_POST['dni'][$i]<>NULL)
{
$sql="INSERT INTO pit_bd_animador VALUES('008','".$_POST['dni'][$i]."',UPPER('".$_POST['nombre'][$i]."'),UPPER('".$_POST['paterno'][$i]."'),UPPER('".$_POST['materno'][$i]."'),'".$_POST['fecha'][$i]."','".$_POST['sexo'][$i]."','','','".$_POST['nivel'][$i]."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//4.- Ingreso la info de cultivos
for ($i = 0; $i <= 10; $i++) 
{
if ($_POST['tipo_cultivo'][$i]<>NULL)
{
$sql="INSERT INTO pit_tipo_cultivo VALUES('','".$_POST['tipo_cultivo'][$i]."',UPPER('".$_POST['describe_cultivo'][$i]."'),'$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//5.- Ingreso la info de ganado
for ($i = 0; $i <= 10; $i++) 
{
if ($_POST['tipo_ganado'][$i]<>NULL)
{
$sql="INSERT INTO pit_ganado_pit VALUES('','".$_POST['tipo_ganado'][$i]."',UPPER('".$_POST['describe_ganado'][$i]."'),'$codigo')";
$result=mysql_query($sql) or die(mysql_error());
}
}
//6.-Ingreso la info de area
$sql="INSERT INTO pit_area_pit VALUES('','".$_POST['area1']."','".$_POST['area2']."','".$_POST['area3']."','".$_POST['area4']."','".$_POST['area5']."','".$_POST['area6']."','".$_POST['area7']."','".$_POST['area8']."','".$_POST['area9']."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());

//7.- Activiades de transformacion
for ($i = 0; $i <= 9; $i++) 
{
if ($_POST['tipo_actividad'][$i]<>NULL)
{
$sql="INSERT INTO pit_actividad_pit VALUES('',UPPER('".$_POST['tipo_actividad'][$i]."'),UPPER('".$_POST['describe_actividad'][$i]."'),'$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//8.- Principales festividades
for ($i = 0; $i <= 4; $i++) 
{
if ($_POST['festday'][$i]<>NULL)
{
$sql="INSERT INTO pit_festividad_pit VALUES('','".$_POST['festday'][$i]."','".$_POST['festmes'][$i]."',UPPER('".$_POST['festdescribe'][$i]."'),'$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//9.- Patrimonio
for ($i = 0; $i <= 4; $i++) 
{
if ($_POST['tipo_cult'][$i]<>NULL)
{
$sql="INSERT INTO pit_patrimonio_pit VALUES('','".$_POST['tipo_cult'][$i]."',UPPER('".$_POST['descrip_cult'][$i]."'),'$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//10.- Recursos hidricos
for ($i = 0; $i <= 5; $i++) 
{
if ($_POST['fuente'][$i]<>NULL)
{
$sql="INSERT INTO pit_agua_pit VALUES('',UPPER('".$_POST['fuente'][$i]."'),UPPER('".$_POST['uso'][$i]."'),UPPER('".$_POST['limite'][$i]."'),'$codigo')";
$result=mysql_query($sql) or die (mysql_error());
}
}
//1.- Redirecciono
echo "<script>window.location ='pit.php?SES=$SES&anio=$anio&modo=imprime'</script>";
 

}
/*
===============================================================================================================================
*/
elseif($action==DELETE)
{
$sql="DELETE FROM pit_bd_ficha_pit WHERE cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Redirecciono
echo "<script>window.location ='pit.php?SES=$SES&anio=$anio&modo=imprime&alert=success_delete'</script>";
}
/*
===============================================================================================================================
*/
elseif($action==UPDATE)
{
	$aporte_pdss=$_POST['n_animador']*$_POST['monto_animador']*$_POST['n_mes'];
	
	//1.- Guardamos la info segun sega el caso
	if ($_POST['mancomunidad']==0)
	{
		$sql="UPDATE pit_bd_ficha_pit SET f_presentacion='".$_POST['f_presentacion']."',n_animador='".$_POST['n_animador']."',incentivo_animador='".$_POST['monto_animador']."',n_mes='".$_POST['n_mes']."',aporte_pdss='$aporte_pdss',aporte_org='".$_POST['aporte_org']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',concurso='".$_POST['mapa']."',n_voucher='".$_POST['n_voucher']."',monto_organizacion='".$_POST['deposito']."' WHERE cod_pit='".$_POST['codigo']."'";
	}
	else
	{
		$sql="UPDATE pit_bd_ficha_pit SET f_presentacion='".$_POST['f_presentacion']."',concurso='".$_POST['mapa']."' WHERE cod_pit='".$_POST['codigo']."'";
	}
	$result=mysql_query($sql) or die (mysql_error());
	
	$codigo=$_POST['codigo'];
	
	//2.- Procedemos a actualizar toda la info de animadores territoriales si los hubiera
	if ($_POST['mancomunidad']==0)
	{
		foreach($dni as $cod=>$a)
		{
			$sql="UPDATE pit_bd_animador SET n_documento='$a' WHERE n_documento='$cod'";
			$result=mysql_query($sql) or die (mysql_error());
		}
		foreach($nombre as $cod=>$b)
		{
			$sql="UPDATE pit_bd_animador SET nombres=UPPER('$b') WHERE n_documento='$cod'";
			$result=mysql_query($sql) or die (mysql_error());
		}
		foreach($paterno as $cod=>$c)
		{
			$sql="UPDATE pit_bd_animador SET paterno=UPPER('$c') WHERE n_documento='$cod'";
			$result=mysql_query($sql) or die (mysql_error());
		}
		foreach($materno as $cod=>$d)
		{
			$sql="UPDATE pit_bd_animador SET materno=UPPER('$d') WHERE n_documento='$cod'";
			$result=mysql_query($sql) or die (mysql_error());
		}
		foreach($fecha as $cod=>$e)
		{
			$sql="UPDATE pit_bd_animador SET f_nacimiento='$e' WHERE n_documento='$cod'";
			$result=mysql_query($sql) or die (mysql_error());
		}
		foreach($sexo as $cod=>$f)
		{
			$sql="UPDATE pit_bd_animador SET sexo='$f' WHERE n_documento='$cod'";
			$result=mysql_query($sql) or die (mysql_error());
		}
		foreach($nivel as $cod=>$g)
		{
			$sql="UPDATE pit_bd_animador SET cod_grado_instruccion='$g' WHERE n_documento='$cod'";
			$result=mysql_query($sql) or die (mysql_error());
		}
		
		for ($i = 0; $i <= 3; $i++) 
		{
			if($_POST['dnis'][$i]<>NULL)
			{
			$sql="INSERT INTO pit_bd_animador VALUES('008','".$_POST['dnis'][$i]."',UPPER('".$_POST['nombres'][$i]."'),UPPER('".$_POST['paternos'][$i]."'),UPPER('".$_POST['maternos'][$i]."'),'".$_POST['fechas'][$i]."','".$_POST['sexos'][$i]."','','','".$_POST['nivels'][$i]."','$codigo')";
			$result=mysql_query($sql) or die(mysql_error());
			}
		}
		
	}
	//3.- Actualizamos la info de cultivos
	foreach($tipo_cultivo as $cod=>$a1)
	{
		$sql="UPDATE pit_tipo_cultivo SET tipo_cultivo='$a1' WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	foreach($describe_cultivo as $cod=>$b1)
	{
		$sql="UPDATE pit_tipo_cultivo SET descripcion=UPPER('$b1') WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	for ($i = 0; $i <= 5; $i++) 
	{
		if ($_POST['tipo_cultivos'][$i]<>NULL)
		{
		$sql="INSERT INTO pit_tipo_cultivo VALUES('','".$_POST['tipo_cultivos'][$i]."',UPPER('".$_POST['describe'][$i]."'),'$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		}
	}

	//4.- Actualizamos la info de ganado
	foreach($tipo_ganado as $cod=>$a2)
	{
	$sql="UPDATE pit_ganado_pit SET tipo='$a2' WHERE cod='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	}
	foreach($describe_ganado as $cod=>$b2)
	{
	$sql="UPDATE pit_ganado_pit SET descripcion=UPPER('$b2') WHERE cod='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	}

	for ($i = 0; $i <= 5; $i++) 
	{
	if ($_POST['tipo_ganados'][$i]<>NULL)
	{
	$sql="INSERT INTO pit_ganado_pit VALUES('','".$_POST['tipo_ganados'][$i]."',UPPER('".$_POST['describe_ganados'][$i]."'),'$codigo')";
	$result=mysql_query($sql) or die (mysql_error());
	}
	}
	
	//5.-  Actualizo la info de area
	$sql="UPDATE pit_area_pit SET a1='".$_POST['area1']."',a2='".$_POST['area2']."',a3='".$_POST['area3']."',a4='".$_POST['area4']."',a5='".$_POST['a5']."',a6='".$_POST['a6']."',a7='".$_POST['a7']."',a8='".$_POST['a8']."',a9='".$_POST['a10']."' WHERE cod_pit='$codigo'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//6.- Actualizo la info de actividades del PIT
	foreach($tipo_actividad as $cod=>$a3)
	{
		$sql="UPDATE pit_actividad_pit SET tipo=UPPER('$a3') WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	foreach($describe_actividad as $cod=>$b3)
	{
		$sql="UPDATE pit_actividad_pit SET descripcion=UPPER('$b3') WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	for ($i = 0; $i <= 5; $i++) 
	{
		if ($_POST['tipo_actividads'][$i]<>NULL)
		{
		$sql="INSERT INTO pit_actividad_pit VALUES('',UPPER('".$_POST['tipo_actividads'][$i]."'),UPPER('".$_POST['describe_actividads'][$i]."'),'$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		}
	}	
	
	//7.- Actualizo la info de las festividades del territorio
	foreach($festday as $cod=>$a4)
	{
		$sql="UPDATE pit_festividad_pit SET dia='$a4' WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	foreach($festmes as $cod=>$b4)
	{
		$sql="UPDATE pit_festividad_pit SET mes='$b4' WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	foreach($festdescribe as $cod=>$c4)
	{
		$sql="UPDATE pit_festividad_pit SET descripcion=UPPER('$c4') WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	for ($i = 0; $i <= 3; $i++) 
	{
		if ($_POST['festdays'][$i]<>NULL)
		{
		$sql="INSERT INTO pit_festividad_pit VALUES('','".$_POST['festdays']."','".$_POST['festmess']."',UPPER('".$_POST['festdescribes']."'),'$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		}
	}

	//8.- Actualizo la info de patrimonio
	foreach($tipo_cult as $cod=>$a5)
	{
		$sql="UPDATE pit_patrimonio_pit SET cod_tipo='$a5' WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	foreach($descrip_cult as $cod=>$b5)
	{
		$sql="UPDATE pit_patrimonio_pit SET descripcion=UPPER('$b5') WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}

	for ($i = 0; $i <= 2; $i++) 
	{
		if($_POST['tipo_cults'][$i]<>NULL)
		{
		$sql="INSERT INTO pit_patrimonio_pit VALUES('','".$_POST['tipo_cults'][$i]."',UPPER('".$_POST['descrip_cults'][$i]."'),'$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		}
	}

	//9.- Actualizo la info de hidrografia
	foreach($fuente as $cod=>$a6)
	{
		$sql="UPDATE pit_agua_pit SET descripcion=UPPER('$a6') WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	foreach($uso as $cod=>$b6)
	{
		$sql="UPDATE pit_agua_pit SET uso=UPPER('$b6') WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	foreach($limite as $cod=>$c6)
	{
		$sql="UPDATE pit_agua_pit SET limitaciones=UPPER('$c6') WHERE cod='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}

	for ($i = 0; $i <= 3; $i++) 
	{
		if($_POST['fuentes'][$i]<>NULL)
		{
		$sql="INSERT INTO pit_agua_pit VALUES('',UPPER('".$_POST['fuentes'][$i]."'),UPPER('".$_POST['usos'][$i]."'),UPPER('".$_POST['limites'][$i]."'),'$codigo')";
		$result=mysql_query($sql) or die (mysql_error());
		}
	}
	
	//10.- Redirecciono
	echo "<script>window.location ='pit.php?SES=$SES&anio=$anio&modo=imprime'</script>";

}


?>