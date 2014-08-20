<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	$sql="INSERT INTO gcac_concurso_clar VALUES('','".$_POST['tipo']."','".$_POST['f_concurso']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['dep']."'),UPPER('".$_POST['prov']."'),UPPER('".$_POST['dist']."'),UPPER('".$_POST['lugar']."'),'".$_POST['premio']."','".$_POST['flat']."','".$_POST['ganadores']."','".$_POST['factor']."','".$_POST['oficina']."')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- obtengo el ultimo registro
	$sql="SELECT * FROM gcac_concurso_clar ORDER BY cod_concurso DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_concurso'];
	
	//3.- Ingreso los jurados
	for($i=0;$i<=5;$i++)
	{
		if($_POST['dni'][$i]<>NULL)
		{
			$sql="INSERT INTO gcac_jurado_concurso VALUES('','".$_POST['dni'][$i]."',UPPER('".$_POST['nombres'][$i]."'),UPPER('".$_POST['apellidos'][$i]."'),'$codigo')";
			$result=mysql_query($sql) or die (mysql_error());
		}
	}
	
	//4.- Busco el tipo de concurso
	$tipo_concurso=$r1['cod_tipo_concurso'];
	
	if ($tipo_concurso<>6)
	{
		echo "<script>window.location ='n_concurso.php?SES=$SES&anio=$anio&cod=$codigo&modo=participante'</script>";
	}
	else
	{
		echo "<script>window.location ='n_participante_concurso.php?SES=$SES&anio=$anio&cod=$codigo&modo=pit'</script>";
	}
}
elseif($action==ADD_PARTICIPANTE)
{

$organizacion=$_POST['participante'];
$dato=explode(",",$organizacion);
$tipo_documento=$dato[0];
$n_documento=$dato[1];

	$sql="INSERT INTO gcac_participante_concurso VALUES('','$tipo_documento','$n_documento',UPPER('".$_POST['descripcion']."'),'".$_POST['dni']."',UPPER('".$_POST['nombres']."'),'','','','$cod')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='n_concurso.php?SES=$SES&anio=$anio&cod=$cod&modo=participante'</script>";
}
elseif($action==DELETE_PARTICIPANTE)
{
	$sql="DELETE FROM gcac_participante_concurso WHERE cod_participante='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redirecciono
	echo "<script>window.location ='n_concurso.php?SES=$SES&anio=$anio&cod=$cod&modo=participante'</script>";	
}
elseif($action==UPDATE)
{
$sql="UPDATE gcac_concurso_clar SET f_concurso='".$_POST['f_concurso']."',nombre=UPPER('".$_POST['nombre']."'),departamento=UPPER('".$_POST['dep']."'),provincia=UPPER('".$_POST['prov']."'),distrito=UPPER('".$_POST['dist']."'),lugar=UPPER('".$_POST['lugar']."'),premio='".$_POST['premio']."',incentivo='".$_POST['flat']."',n_ganadores='".$_POST['ganadores']."',factor='".$_POST['factor']."',cod_dependencia='".$_POST['oficina']."' WHERE cod_concurso='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- actualizamos los jurados
foreach($dni as $cad=>$a)
{
	$sql="UPDATE gcac_jurado_concurso SET n_documento='".$_POST['dni'][$cad]."',nombres='".$_POST['nombres'][$cad]."',apellidos='".$_POST['apellidos'][$cad]."' WHERE cod_jurado='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}

//3.- Busco el tipo de concurso
$sql="SELECT * FROM gcac_concurso_clar WHERE cod_concurso='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


	//4.- Busco el tipo de concurso
	$tipo_concurso=$r1['cod_tipo_concurso'];
	
	if ($tipo_concurso<>6)
	{
		echo "<script>window.location ='n_concurso.php?SES=$SES&anio=$anio&cod=$codigo&modo=participante'</script>";
	}
	else
	{
		echo "<script>window.location ='n_participante_concurso.php?SES=$SES&anio=$anio&cod=$codigo&modo=pit'</script>";
	}

}
elseif($action==DELETE)
{
	$sql="DELETE FROM gcac_concurso_clar WHERE cod_concurso='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redireccionamos
	echo "<script>window.location ='concurso.php?SES=$SES&anio=$anio&modo=edit'</script>";
}


?>