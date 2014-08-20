<?php
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{
	//1.- Ingresamos la información 
	$sql="INSERT INTO bd_cfinal VALUES('',UPPER('".$_POST['nombre']."'),'".$_POST['f_concurso']."','".$row['cod_dependencia']."',UPPER('".$_POST['departamento']."'),UPPER('".$_POST['provincia']."'),UPPER('".$_POST['distrito']."'),'".$_POST['nivel']."','".$_POST['max_cat_a']."','".$_POST['max_cat_b']."','".$_POST['max_cat_c']."','".$_POST['premio_flat']."','".$_POST['premio_concurso']."','".$_POST['aporte_otro']."','".$_POST['premio_otro']."','".$_POST['poa']."','".$_POST['fte_fto']."','0')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Obtengo el ultimo codigo generado
	$sql="SELECT * FROM bd_cfinal ORDER BY cod_concurso DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$codigo=$r1['cod_concurso'];

	//3.- Redireccionamos
	echo "<script>window.location ='m_cf.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==UPDATE)
{
	//1.- Guardamos la info del concurso
	$sql="UPDATE bd_cfinal SET nombre=UPPER('".$_POST['nombre']."'),f_concurso='".$_POST['f_concurso']."',departamento=UPPER('".$_POST['departamento']."'),provincia=UPPER('".$_POST['provincia']."'),distrito=UPPER('".$_POST['distrito']."'),cod_nivel='".$_POST['nivel']."',max_gan_a='".$_POST['max_cat_a']."',max_gan_b='".$_POST['max_cat_b']."',max_gan_c='".$_POST['max_cat_c']."',total_premio_flat='".$_POST['premio_flat']."',total_premio_concurso='".$_POST['premio_concurso']."',aporte_otro='".$_POST['aporte_otro']."',total_premio_otro='".$_POST['premio_otro']."',cod_poa='".$_POST['poa']."',cod_fte_fto='".$_POST['fte_fto']."' WHERE cod_concurso='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());

	$codigo=$_POST['codigo'];

	//2.- Guardamos la información de los jurados
	for ($i=0; $i<=5 ; $i++) 
	{ 
		if($_POST['nombres'][$i]<>NULL)
		{
			$sql="INSERT INTO bd_jurado_cfinal VALUES('','008','".$_POST['nombres'][$i]."','".$_POST['cargos'][$i]."','$codigo')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}

	foreach ($nombrea as $cad => $a) 
	{
		$sql="UPDATE bd_jurado_cfinal SET cod_tipo_doc='008',n_documento='".$_POST['nombrea'][$cad]."',cod_cargo_cf='".$_POST['cargoa'][$cad]."' WHERE cod_jurado='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}

	//4.- Redireccionamos
	echo "<script>window.location ='m_cf.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==DELETE)
{
	$sql="DELETE FROM bd_cfinal WHERE cod_concurso='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redireccionamos
	echo "<script>window.location ='cf.php?SES=$SES&anio=$anio'</script>";
}
elseif($action==ADD_CAT_A)
{
	$sql="INSERT INTO bd_ficha_cfinal VALUES('','3','".$_POST['pit']."','1',UPPER('".$_POST['danza']."'),UPPER('".$_POST['plato']."'),'0','0','0','0','0','0','$cod')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='participante_cf.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==DELETE_CAT_A)
{
	$sql="DELETE FROM bd_ficha_cfinal WHERE cod_participante='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='participante_cf.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==ADD_CAT_B)
{
	$sql="INSERT INTO bd_ficha_cfinal VALUES('','3','".$_POST['pit']."','2',UPPER('".$_POST['danza']."'),UPPER('".$_POST['plato']."'),'0','0','0','0','0','0','$cod')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='participante_cf_b.php?SES=$SES&anio=$anio&cod=$cod'</script>";	
}
elseif($action==DELETE_CAT_B)
{
	$sql="DELETE FROM bd_ficha_cfinal WHERE cod_participante='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='participante_cf_b.php?SES=$SES&anio=$anio&cod=$cod'</script>";	
}
elseif($action==ADD_CAT_C)
{
	$sql="INSERT INTO bd_ficha_cfinal VALUES('','4','".$_POST['pdn']."','3',UPPER('".$_POST['danza']."'),UPPER('".$_POST['plato']."'),'0','0','0','0','0','0','$cod')";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='participante_cf_c.php?SES=$SES&anio=$anio&cod=$cod'</script>";	
}
elseif($action==DELETE_CAT_C)
{
	$sql="DELETE FROM bd_ficha_cfinal WHERE cod_participante='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Redirecciono
	echo "<script>window.location ='participante_cf_c.php?SES=$SES&anio=$anio&cod=$cod'</script>";	
}
elseif($action==ADD_CAL_CAMPO)
{
	//1.- Guardamos la info
	if ($tipo==1)
	{
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9']+$_POST['p10']+$_POST['p11']+$_POST['p12']+$_POST['p13']+$_POST['p14'];

		$sql="INSERT INTO bd_ficha_campo_cf VALUES ('','".$_POST['iniciativa']."','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','".$_POST['p10']."','".$_POST['p11']."','".$_POST['p12']."','".$_POST['p13']."','".$_POST['p14']."','$total')";
	}
	elseif($tipo==2)
	{
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8'];

		$sql="INSERT INTO bd_ficha_campo_cf VALUES ('','".$_POST['iniciativa']."','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','0','0','0','0','0','0','$total')";				
	}
	elseif($tipo==3)
	{
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5'];

		$sql="INSERT INTO bd_ficha_campo_cf VALUES ('','".$_POST['iniciativa']."','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','0','0','0','0','0','0','0','0','0','$total')";				
	}
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Promediamos campo y clar y lo sumamos
	$sql="SELECT AVG(bd_ficha_campo_cf.total_calif) AS promedio
	FROM bd_ficha_campo_cf
	WHERE bd_ficha_campo_cf.cod_participante='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$sql="SELECT AVG(bd_ficha_publica_cf.total_calif) AS promedio
	FROM bd_ficha_publica_cf
	WHERE bd_ficha_publica_cf.cod_participante='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	$puntaje=$r1['promedio']+$r2['promedio'];

	//3.- Guardamos el promedio
	$sql="UPDATE bd_ficha_cfinal SET puntaje='$puntaje' WHERE cod_participante='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//4.- Redireccionamos
	echo "<script>window.location ='modulo_cal_campo.php?SES=$SES&anio=$anio&cod=$cod&tipo=$tipo'</script>";		
}
elseif($action==DELETE_CAL_CAMPO)
{
	//1.- Busco el codigo de participante
	$sql="SELECT bd_ficha_cfinal.cod_participante
	FROM bd_ficha_cfinal INNER JOIN bd_ficha_campo_cf ON bd_ficha_cfinal.cod_participante = bd_ficha_campo_cf.cod_participante
	WHERE bd_ficha_campo_cf.cod_ficha='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	//2.- Eliminamos la ficha
	$sql="DELETE FROM bd_ficha_campo_cf WHERE cod_ficha='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//3.- Promediamos campo y clar y lo sumamos
	$sql="SELECT AVG(bd_ficha_campo_cf.total_calif) AS promedio
	FROM bd_ficha_campo_cf
	WHERE bd_ficha_campo_cf.cod_participante='".$r1['cod_participante']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	$sql="SELECT AVG(bd_ficha_publica_cf.total_calif) AS promedio
	FROM bd_ficha_publica_cf
	WHERE bd_ficha_publica_cf.cod_participante='".$r1['cod_participante']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);

	$puntaje=$r2['promedio']+$r3['promedio'];

	//4.- Guardamos el promedio
	$sql="UPDATE bd_ficha_cfinal SET puntaje='$puntaje' WHERE cod_participante='".$r1['cod_participante']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//5.- Redireccionamos
	echo "<script>window.location ='modulo_cal_campo.php?SES=$SES&anio=$anio&cod=$cod&tipo=$tipo'</script>";			

}
elseif($action==ADD_CAL_PUBLIC)
{
	//1.- Guardamos la informacion
	if($tipo==1)
	{
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9']+$_POST['p10']+$_POST['p11']+$_POST['p12']+$_POST['p13']+$_POST['p14'];

		$sql="INSERT INTO bd_ficha_publica_cf VALUES('','".$_POST['iniciativa']."','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','".$_POST['p10']."','".$_POST['p11']."','".$_POST['p12']."','".$_POST['p13']."','".$_POST['p14']."','$total')";
	}
	elseif($tipo==2)
	{
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4']+$_POST['p5']+$_POST['p6']+$_POST['p7']+$_POST['p8']+$_POST['p9']+$_POST['p10'];

		$sql="INSERT INTO bd_ficha_publica_cf VALUES('','".$_POST['iniciativa']."','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','".$_POST['p5']."','".$_POST['p6']."','".$_POST['p7']."','".$_POST['p8']."','".$_POST['p9']."','".$_POST['p10']."','','','','','$total')";
	}
	elseif($tipo==3)
	{
		$total=$_POST['p1']+$_POST['p2']+$_POST['p3']+$_POST['p4'];

		$sql="INSERT INTO bd_ficha_publica_cf VALUES('','".$_POST['iniciativa']."','".$_POST['jurado']."','".$_POST['p1']."','".$_POST['p2']."','".$_POST['p3']."','".$_POST['p4']."','0 ','0','0','0','0','0','0','0','0','0','$total')";
	}
	$result=mysql_query($sql) or die (mysql_error());

	//2.- Promediamos campo y clar y lo sumamos
	$sql="SELECT AVG(bd_ficha_campo_cf.total_calif) AS promedio
	FROM bd_ficha_campo_cf
	WHERE bd_ficha_campo_cf.cod_participante='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$sql="SELECT AVG(bd_ficha_publica_cf.total_calif) AS promedio
	FROM bd_ficha_publica_cf
	WHERE bd_ficha_publica_cf.cod_participante='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	$puntaje=$r1['promedio']+$r2['promedio'];

	//3.- Guardamos el promedio
	$sql="UPDATE bd_ficha_cfinal SET puntaje='$puntaje' WHERE cod_participante='".$_POST['iniciativa']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//4.- Redireccionamos
	echo "<script>window.location ='modulo_cal_public.php?SES=$SES&anio=$anio&cod=$cod&tipo=$tipo'</script>";	
}
elseif($action==DELETE_CAL_PUBLIC)
{
	//1.- Busco el codigo de participante
	$sql="SELECT bd_ficha_cfinal.cod_participante
	FROM bd_ficha_cfinal INNER JOIN bd_ficha_campo_cf ON bd_ficha_cfinal.cod_participante = bd_ficha_campo_cf.cod_participante
	WHERE bd_ficha_campo_cf.cod_ficha='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	//2.- Eliminamos la ficha
	$sql="DELETE FROM bd_ficha_publica_cf WHERE cod_ficha='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//3.- Promediamos campo y clar y lo sumamos
	$sql="SELECT AVG(bd_ficha_campo_cf.total_calif) AS promedio
	FROM bd_ficha_campo_cf
	WHERE bd_ficha_campo_cf.cod_participante='".$r1['cod_participante']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	$sql="SELECT AVG(bd_ficha_publica_cf.total_calif) AS promedio
	FROM bd_ficha_publica_cf
	WHERE bd_ficha_publica_cf.cod_participante='".$r1['cod_participante']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);

	$puntaje=$r2['promedio']+$r3['promedio'];

	//4.- Guardamos el promedio
	$sql="UPDATE bd_ficha_cfinal SET puntaje='$puntaje' WHERE cod_participante='".$r1['cod_participante']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//5.- Redireccionamos
	echo "<script>window.location ='modulo_cal_public.php?SES=$SES&anio=$anio&cod=$cod&tipo=$tipo'</script>";			
}
elseif($action==PREMIA_CF)
{
	//1.- Verifico que la solicitud exista
	$sql="SELECT bd_cfinal.cod_concurso, 
	bd_cfinal.n_solicitud
	FROM bd_cfinal
	WHERE bd_cfinal.cod_concurso='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	//a.- Si la solicitud es igual a 0 entonces debemos generarla
	if ($r2['n_solicitud']==0)
	{
		//1.- Busco la numeracion de solicitud
		$sql="SELECT sys_bd_numera_dependencia.cod, 
		sys_bd_numera_dependencia.n_solicitud_iniciativa, 
		sys_bd_numera_dependencia.n_atf_iniciativa
		FROM sys_bd_numera_dependencia
		WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
		sys_bd_numera_dependencia.periodo='$anio'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);

		$n_solicitud=$r1['n_solicitud_iniciativa']+1;

		//2.- Actualizo la numeracion de la solicitud
		$sql="UPDATE sys_bd_numera_dependencia SET n_solicitud_iniciativa='$n_solicitud' WHERE cod='".$r1['cod']."'";
		$result=mysql_query($sql) or die (mysql_error());

		//3.- Guardo el número de solicitud
		$sql="UPDATE bd_cfinal SET n_solicitud='$n_solicitud' WHERE cod_concurso='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	//b.- Si la atf es igual a 0 entonces debemos generarlas
	if ($_POST['tiene_atf']==0)
	{
		//1.- Busco la numeracion de atf
		$sql="SELECT sys_bd_numera_dependencia.cod, 
		sys_bd_numera_dependencia.n_atf_iniciativa
		FROM sys_bd_numera_dependencia
		WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
		sys_bd_numera_dependencia.periodo='$anio'";
		$result=mysql_query($sql) or die (mysql_error());
		$r2=mysql_fetch_array($result);

		$n_atf=$_POST['n_atf'];

		//2.- Actualizo la numeracion de la atf
		$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='$n_atf' WHERE cod='".$r2['cod']."'";
		$result=mysql_query($sql) or die (mysql_error());

		//3.- Guardamos el detalle
		foreach ($atf as $cad => $a) 
		{
			$sql="UPDATE bd_ficha_cfinal SET puesto='".$_POST['puesto'][$cad]."',premio='".$_POST['premio'][$cad]."',n_atf='".$_POST['atf'][$cad]."' WHERE cod_participante='$cad'";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//c.- Si la atf ya existe entonces guardamos solo los datos de premio
	else
	{
		foreach ($atf as $cad => $a) 
		{
			$sql="UPDATE bd_ficha_cfinal SET puesto='".$_POST['puesto'][$cad]."',premio='".$_POST['premio'][$cad]."' WHERE cod_participante='$cad'";
			$result=mysql_query($sql) or die (mysql_error());
		}	
	}

	//4.- Redirecciono a la impresión
		echo "<script>window.location ='../print/print_cuadro_cf.php?SES=$SES&anio=$anio&cod=$cod&tipo=$tipo'</script>";		
}
?>