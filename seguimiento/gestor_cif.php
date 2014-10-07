<?php
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();



if ($action==ADD)
{

if ($_POST['mrn']==NULL)
{
echo "<script>window.location ='n_cif.php?SES=$SES&anio=$anio&error=vacio'</script>";
}
else
{

$costo_cif=$_POST['premio']+$_POST['otro'];

//1.- Genero la ficha de concurso
$sql="INSERT INTO cif_bd_concurso VALUES('','".$_POST['mrn']."','".$_POST['n_concurso']."','".$_POST['f_concurso']."','".$_POST['premio']."','".$_POST['otro']."','$costo_cif','".$_POST['actividad_1']."','".$_POST['actividad_2']."','".$_POST['actividad_3']."')";
$result=mysql_query($sql) or die (mysql_error());

//2.- Ubico el ultimo registro
$sql="SELECT * FROM cif_bd_concurso ORDER BY cod_concurso_cif DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_concurso_cif'];

//3.- Genero 3 fichas de Detalle para las 3 actividades

if ($_POST['actividad_1']<>NULL)
{
$sql="INSERT INTO cif_bd_ficha VALUES('','$codigo','".$r1['actividad_1']."','','','','','','','','','','')";
$result=mysql_query($sql) or die (mysql_error());
}

if ($_POST['actividad_2']<>NULL)
{
$sql="INSERT INTO cif_bd_ficha VALUES('','$codigo','".$r1['actividad_2']."','','','','','','','','','','')";
$result=mysql_query($sql) or die (mysql_error());
}

if ($_POST['actividad_3']<>NULL)
{
$sql="INSERT INTO cif_bd_ficha VALUES('','$codigo','".$r1['actividad_3']."','','','','','','','','','','')";
$result=mysql_query($sql) or die (mysql_error());
}

echo "<script>window.location ='n_cif.php?SES=$SES&anio=$anio&cod=$codigo&modo=participante'</script>";
}

}


elseif($action==ADD_PARTICIPANTE)
{
	for($i=0;$i<count($_POST['campos']);$i++) 
	{
		$sql="INSERT IGNORE INTO cif_bd_participante_cif VALUES('008','".$_POST['campos'][$i]."','$cod')";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	echo "<script>window.location ='n_cif.php?SES=$SES&anio=$anio&cod=$cod&modo=participante'</script>";
}


elseif($action==UPDATE)
{
if ($_POST['mrn']==NULL or $_POST['n_concurso']==NULL)
{
echo "<script>window.location ='m_cif.php?SES=$SES&anio=$anio&id=$id&error=vacio'</script>";
}
else
{
$costo_cif=$_POST['premio']+$_POST['otro'];

$sql="UPDATE cif_bd_concurso SET n_concurso='".$_POST['n_concurso']."',f_concurso='".$_POST['f_concurso']."',monto_premio='".$_POST['premio']."',monto_otro='".$_POST['otro']."',costo='$costo_cif',actividad_1='".$_POST['actividad_1']."',actividad_2='".$_POST['actividad_2']."',actividad_3='".$_POST['actividad_3']."' WHERE cod_concurso_cif='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//Busco la informacion
$sql="SELECT cif_bd_ficha.cod_ficha_cif
FROM cif_bd_ficha
WHERE cif_bd_ficha.cod_concurso='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);


if($total==0)
{

if ($_POST['actividad_1']<>NULL)
{
$sql="INSERT INTO cif_bd_ficha VALUES('','$codigo','".$_POST['actividad_1']."','','','','','','','','','','')";
$result=mysql_query($sql) or die (mysql_error());
}
if ($_POST['actividad_2']<>NULL)
{
$sql="INSERT INTO cif_bd_ficha VALUES('','$codigo','".$_POST['actividad_2']."','','','','','','','','','','')";
$result=mysql_query($sql) or die (mysql_error());
}
if ($_POST['actividad_3']<>NULL)
{
$sql="INSERT INTO cif_bd_ficha VALUES('','$codigo','".$_POST['actividad_3']."','','','','','','','','','','')";
$result=mysql_query($sql) or die (mysql_error());
}

}

echo "<script>window.location ='n_cif.php?SES=$SES&anio=$anio&cod=$codigo&modo=participante'</script>";
}
}

elseif($action==CALIF_CIF)
{
//1.- Para el caso de nuevos participantes
if ($_POST['dni']<>NULL and $_POST['participante']==NULL)
{
//a.- Ubicamos la organizacion y el MRN
$sql="SELECT cif_bd_concurso.cod_mrn, 
	pit_bd_ficha_mrn.cod_tipo_doc_org, 
	pit_bd_ficha_mrn.n_documento_org
FROM pit_bd_ficha_mrn INNER JOIN cif_bd_concurso ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//b.- Verifico si la persona ya existe en el padron de socios
$sql="SELECT org_ficha_usuario.n_documento
FROM org_ficha_usuario
WHERE org_ficha_usuario.cod_tipo_doc_org='".$r1['cod_tipo_doc_org']."' AND
org_ficha_usuario.n_documento_org='".$r1['n_documento_org']."'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

if ($total==0)
{
//c.- Genero la familia
$sql="INSERT INTO org_ficha_usuario VALUES('008','".$_POST['dni']."',UPPER('".$_POST['nombres']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['fecha']."','".$_POST['sexo']."','".$_POST['ubigeo']."',UPPER('".$_POST['address']."'),'".$_POST['titular']."','".$_POST['socio']."','".$_POST['hijo']."','','','2','".$r1['cod_tipo_doc_org']."','".$r1['n_documento_org']."')";
$result=mysql_query($sql) or die (mysql_error());
}

//d.- Genero el participante
$sql="INSERT INTO pit_bd_user_iniciativa VALUES('008','".$_POST['dni']."','2','1','5','".$r1['cod_mrn']."')";
$result=mysql_query($sql) or die (mysql_error());

//e.- Genero la ficha de calificacion
for($i=0;$i<=3;$i++)
{
if ($_POST['actividad'][$i]<>NULL)
{
$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['dni']."','".$_POST['actividad'][$i]."','".$_POST['logro_a'][$i]."','".$_POST['valor_a'][$i]."','".$_POST['logro_b'][$i]."','".$_POST['valor_b'][$i]."','','','','')";
$result=mysql_query($sql) or die (mysql_error());
}
}
}
//2.- Para el caso de participantes ya registrados
elseif($_POST['participante']<>NULL)
{

for($i=0;$i<=3;$i++)
{
if ($_POST['actividad'][$i]<>NULL)
{
$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['participante']."','".$_POST['actividad'][$i]."','".$_POST['logro_a'][$i]."','".$_POST['valor_a'][$i]."','".$_POST['logro_b'][$i]."','".$_POST['valor_b'][$i]."','','','','')";
$result=mysql_query($sql) or die (mysql_error());
}
}
}
echo "<script>window.location ='n_calif_cif.php?SES=$SES&anio=$anio&cod=$cod'</script>";

}
elseif($action==PREM_CIF)
{

foreach($puntaje as $cad=>$a1)
{
	$sql="UPDATE cif_bd_ficha_cif SET puntaje='".$_POST['puntaje'][$cad]."',puesto='".$_POST['puesto'][$cad]."',premio_pdss='".$_POST['aporte_pdss'][$cad]."',premio_otro='".$_POST['aporte_otro'][$cad]."' WHERE cod_ficha_cif='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}

echo "<script>window.location ='n_premia_cif.php?SES=$SES&anio=$anio&cod=$cod'</script>";

}

elseif($action==DELETE)
{
	$sql="DELETE FROM cif_bd_concurso WHERE cod_concurso_cif='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='cif.php?SES=$SES&anio=$anio&modo=delete'</script>";	
}
elseif($action==DELETE_PARTICIPANTE)
{
	$sql="DELETE FROM cif_bd_participante_cif WHERE cod_tipo_doc='008' AND n_documento='$id' AND cod_concurso_cif='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_cif.php?SES=$SES&anio=$anio&cod=$cod&modo=participante'</script>";	
}
elseif($action==DELETE_PARTICIPANTES)
{
	$sql="delete from cif_bd_participante_cif WHERE cod_concurso_cif='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_cif.php?SES=$SES&anio=$anio&cod=$cod&modo=participante'</script>";		
}

elseif($action==ADD_CALIF)
{
	//1.- calcular el numero de participantes
	$sql="SELECT COUNT(cif_bd_participante_cif.cod_tipo_doc) AS total
	FROM cif_bd_participante_cif
	WHERE cif_bd_participante_cif.cod_concurso_cif='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$registros=$r1['total']+1;

	//2.- Guardo nuevos registros
	for($i=0;$i<=$registros;$i++)
	{
		if($_POST['cantidad_inicial'][$i]<>NULL)
		{
			$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['dni'][$i]."','".$_POST['actividad']."','".$_POST['cantidad_inicial'][$i]."','".$_POST['valor_inicial'][$i]."','".$_POST['cantidad_final'][$i]."','".$_POST['valor_final'][$i]."','".$_POST['puntaje'][$i]."','".$_POST['puesto'][$i]."','".$_POST['premio_pdss'][$i]."','".$_POST['premio_otro'][$i]."')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	//3.- Actualizamos los registros si los hubiera
	foreach ($cantidad1a as $cad => $a) 
	{
		$sql="UPDATE cif_bd_ficha_cif SET meta_1='".$_POST['cantidad1a'][$cad]."',valor_1='".$_POST['valor1a'][$cad]."',meta_2='".$_POST['cantidad2a'][$cad]."',valor_2='".$_POST['valor2a'][$cad]."',puntaje='".$_POST['puntajea'][$cad]."',puesto='".$_POST['puestoa'][$cad]."',premio_pdss='".$_POST['premio_pdssa'][$cad]."',premio_otro='".$_POST['premio_otroa'][$cad]."' WHERE cod_ficha_cif='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}

		$codigo_actividad=$_POST['actividad'];

	//4.- Redirecciono
	echo "<script>window.location ='n_calif_cif.php?SES=$SES&anio=$anio&cod=$cod&actividad=$codigo_actividad'</script>";	
}
elseif($action==DELETE_CALIF)
{
	//1.- Eliminamos la calificacion
	$sql="DELETE FROM cif_bd_ficha_cif WHERE cod_ficha_cif='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	if($tipo==1)
	{
		$tab="#simple1";
	}
	elseif($tipo==2)
	{
		$tab="#simple2";
	}
	elseif($tipo==3)
	{
		$tab="#simple3";
	}
	

	//2.- Redirecciono
	echo "<script>window.location ='n_calif_cif_2.php?SES=$SES&anio=$anio&cod=$cod$tab'</script>";	
}























/*****************************************************************************************/
/*************************  PANEL PARA REGISTRAR LAS CALIFICACIONES  ********************/
elseif($action==ADD_FICHA_1)
{
	$total=$registros;
	if($tipo==1)
	{
		$tab="#simple1";
	}
	elseif($tipo==2)
	{
		$tab="#simple2";
	}
	elseif($tipo==3)
	{
		$tab="#simple3";
	}

	//A.- Probemos esta salida para cuando los registros son mayores a 100
		
	if($total>90)
	{
		//1.- realizo un insert para los nuevos registros
		for ($i=0; $i<=$total; $i++) 
		{ 
			if($_POST['dni'][$i])
			{
			$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['dni'][$i]."','".$_POST['actividad'][$i]."','".$_POST['cantidad1a'][$i]."','".$_POST['valor1a'][$i]."','".$_POST['cantidad2a'][$i]."','".$_POST['valor2a'][$i]."','".$_POST['puntajea'][$i]."','".$_POST['puestoa'][$i]."','".$_POST['premio_pdssa'][$i]."','".$_POST['premio_otroa'][$i]."')";
			$result=mysql_query($sql) or die (mysql_error());
			}
		}
		//2.- Actualizo los registros existentes
		foreach ($cantidad1as as $cad => $a) 
		{
			$sql="UPDATE cif_bd_ficha_cif SET meta_1='".$_POST['cantidad1as'][$cad]."',valor_1='".$_POST['valor1as'][$cad]."',meta_2='".$_POST['cantidad2as'][$cad]."',valor_2='".$_POST['valor2as'][$cad]."',puntaje='".$_POST['puntajeas'][$cad]."',puesto='".$_POST['puestoas'][$cad]."',premio_pdss='".$_POST['premio_pdssas'][$cad]."',premio_otro='".$_POST['premio_otroas'][$cad]."' WHERE cod_ficha_cif='$cad'";
			$result=mysql_query($sql) or die (mysql_error());
		}		
	}
	
	else
	{
		//1.- realizo un insert para los nuevos registros
		for ($i=0; $i<=$total; $i++) 
		{ 
			if($_POST['cantidad1a'][$i]<>NULL)
			{
				$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['dni'][$i]."','".$_POST['actividad'][$i]."','".$_POST['cantidad1a'][$i]."','".$_POST['valor1a'][$i]."','".$_POST['cantidad2a'][$i]."','".$_POST['valor2a'][$i]."','".$_POST['puntajea'][$i]."','".$_POST['puestoa'][$i]."','".$_POST['premio_pdssa'][$i]."','".$_POST['premio_otroa'][$i]."')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		//2.- Actualizo los registros existentes
		foreach ($cantidad1as as $cad => $a) 
		{
			$sql="UPDATE cif_bd_ficha_cif SET meta_1='".$_POST['cantidad1as'][$cad]."',valor_1='".$_POST['valor1as'][$cad]."',meta_2='".$_POST['cantidad2as'][$cad]."',valor_2='".$_POST['valor2as'][$cad]."',puntaje='".$_POST['puntajeas'][$cad]."',puesto='".$_POST['puestoas'][$cad]."',premio_pdss='".$_POST['premio_pdssas'][$cad]."',premio_otro='".$_POST['premio_otroas'][$cad]."' WHERE cod_ficha_cif='$cad'";
			$result=mysql_query($sql) or die (mysql_error());
		}		
	}	

	//3.- redirecciono
	echo "<script>window.location ='n_calif_cif_2.php?SES=$SES&anio=$anio&cod=$cod$tab'</script>";

}
elseif($action==ADD_FICHA_2)
{
	$total=$registros;

	if($tipo==1)
	{
		$tab="#simple1";
	}
	elseif($tipo==2)
	{
		$tab="#simple2";
	}
	elseif($tipo==3)
	{
		$tab="#simple3";
	}


	if($total>90)
	{
		//1.- realizo un insert para los nuevos registros
		for ($i=0; $i<=$total; $i++) 
		{ 
			if($_POST['dni'][$i])
			{
			$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['dni'][$i]."','".$_POST['actividad'][$i]."','".$_POST['cantidad1a'][$i]."','".$_POST['valor1a'][$i]."','".$_POST['cantidad2a'][$i]."','".$_POST['valor2a'][$i]."','".$_POST['puntajea'][$i]."','".$_POST['puestoa'][$i]."','".$_POST['premio_pdssa'][$i]."','".$_POST['premio_otroa'][$i]."')";
			$result=mysql_query($sql) or die (mysql_error());
			}
		}
		//2.- Actualizo los registros existentes
		foreach ($cantidad1as as $cad => $a) 
		{
			$sql="UPDATE cif_bd_ficha_cif SET meta_1='".$_POST['cantidad1as'][$cad]."',valor_1='".$_POST['valor1as'][$cad]."',meta_2='".$_POST['cantidad2as'][$cad]."',valor_2='".$_POST['valor2as'][$cad]."',puntaje='".$_POST['puntajeas'][$cad]."',puesto='".$_POST['puestoas'][$cad]."',premio_pdss='".$_POST['premio_pdssas'][$cad]."',premio_otro='".$_POST['premio_otroas'][$cad]."' WHERE cod_ficha_cif='$cad'";
			$result=mysql_query($sql) or die (mysql_error());
		}		
	}
	else
	{
		//1.- realizo un insert para los nuevos registros
		for ($i=0; $i<=$total; $i++) 
		{ 
			if($_POST['cantidad1a'][$i]<>NULL)
			{
				$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['dni'][$i]."','".$_POST['actividad'][$i]."','".$_POST['cantidad1a'][$i]."','".$_POST['valor1a'][$i]."','".$_POST['cantidad2a'][$i]."','".$_POST['valor2a'][$i]."','".$_POST['puntajea'][$i]."','".$_POST['puestoa'][$i]."','".$_POST['premio_pdssa'][$i]."','".$_POST['premio_otroa'][$i]."')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		//2.- Actualizo los registros existentes
		foreach ($cantidad1as as $cad => $a) 
		{
			$sql="UPDATE cif_bd_ficha_cif SET meta_1='".$_POST['cantidad1as'][$cad]."',valor_1='".$_POST['valor1as'][$cad]."',meta_2='".$_POST['cantidad2as'][$cad]."',valor_2='".$_POST['valor2as'][$cad]."',puntaje='".$_POST['puntajeas'][$cad]."',puesto='".$_POST['puestoas'][$cad]."',premio_pdss='".$_POST['premio_pdssas'][$cad]."',premio_otro='".$_POST['premio_otroas'][$cad]."' WHERE cod_ficha_cif='$cad'";
			$result=mysql_query($sql) or die (mysql_error());
		}		
	}

	//3.- redirecciono
	echo "<script>window.location ='n_calif_cif_2.php?SES=$SES&anio=$anio&cod=$cod$tab'</script>";

}
elseif($action==ADD_FICHA_3)
{
	$total=$registros;
	if($tipo==1)
	{
		$tab="#simple1";
	}
	elseif($tipo==2)
	{
		$tab="#simple2";
	}
	elseif($tipo==3)
	{
		$tab="#simple3";
	}

	if($total>90)
	{
		//1.- realizo un insert para los nuevos registros
		for ($i=0; $i<=$total; $i++) 
		{ 
			if($_POST['dni'][$i])
			{
			$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['dni'][$i]."','".$_POST['actividad'][$i]."','".$_POST['cantidad1a'][$i]."','".$_POST['valor1a'][$i]."','".$_POST['cantidad2a'][$i]."','".$_POST['valor2a'][$i]."','".$_POST['puntajea'][$i]."','".$_POST['puestoa'][$i]."','".$_POST['premio_pdssa'][$i]."','".$_POST['premio_otroa'][$i]."')";
			$result=mysql_query($sql) or die (mysql_error());
			}

		}
		//2.- Actualizo los registros existentes
		foreach ($cantidad1as as $cad => $a) 
		{
			$sql="UPDATE cif_bd_ficha_cif SET meta_1='".$_POST['cantidad1as'][$cad]."',valor_1='".$_POST['valor1as'][$cad]."',meta_2='".$_POST['cantidad2as'][$cad]."',valor_2='".$_POST['valor2as'][$cad]."',puntaje='".$_POST['puntajeas'][$cad]."',puesto='".$_POST['puestoas'][$cad]."',premio_pdss='".$_POST['premio_pdssas'][$cad]."',premio_otro='".$_POST['premio_otroas'][$cad]."' WHERE cod_ficha_cif='$cad'";
			$result=mysql_query($sql) or die (mysql_error());
		}		
	}
	else
	{
		//1.- realizo un insert para los nuevos registros
		for ($i=0; $i<=$total; $i++) 
		{ 
			if($_POST['cantidad1a'][$i]<>NULL)
			{
				$sql="INSERT INTO cif_bd_ficha_cif VALUES('','$cod','008','".$_POST['dni'][$i]."','".$_POST['actividad'][$i]."','".$_POST['cantidad1a'][$i]."','".$_POST['valor1a'][$i]."','".$_POST['cantidad2a'][$i]."','".$_POST['valor2a'][$i]."','".$_POST['puntajea'][$i]."','".$_POST['puestoa'][$i]."','".$_POST['premio_pdssa'][$i]."','".$_POST['premio_otroa'][$i]."')";
				$result=mysql_query($sql) or die (mysql_error());
			}
		}
		//2.- Actualizo los registros existentes
		foreach ($cantidad1as as $cad => $a) 
		{
			$sql="UPDATE cif_bd_ficha_cif SET meta_1='".$_POST['cantidad1as'][$cad]."',valor_1='".$_POST['valor1as'][$cad]."',meta_2='".$_POST['cantidad2as'][$cad]."',valor_2='".$_POST['valor2as'][$cad]."',puntaje='".$_POST['puntajeas'][$cad]."',puesto='".$_POST['puestoas'][$cad]."',premio_pdss='".$_POST['premio_pdssas'][$cad]."',premio_otro='".$_POST['premio_otroas'][$cad]."' WHERE cod_ficha_cif='$cad'";
			$result=mysql_query($sql) or die (mysql_error());
		}		
	}

	//3.- redirecciono
	echo "<script>window.location ='n_calif_cif_2.php?SES=$SES&anio=$anio&cod=$cod$tab'</script>";
}
?>