<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD_PIT)
{
	$sql="INSERT INTO gcac_pit_participante_concurso VALUES('','".$_POST['iniciativa']."','','','','','','','".$_POST['dni']."',UPPER('".$_POST['nombre']."'),'','','','$cod')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Busco el ultimo registro
	$sql="SELECT * FROM gcac_pit_participante_concurso ORDER BY cod_participante DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_participante'];
	
	echo "<script>window.location ='n_participante_concurso.php?SES=$SES&anio=$anio&cod=$cod&id=$codigo&modo=iniciativa'</script>";
}
elseif($action==ADD_INICIATIVA)
{
	$sql="UPDATE gcac_pit_participante_concurso SET cod_pgrn='".$_POST['pgrn']."',cod_pdn_1='".$_POST['pdn_1']."',cod_pdn_2='".$_POST['pdn_2']."',cod_pdn_3='".$_POST['pdn_3']."',plato=UPPER('".$_POST['plato']."'),danza=UPPER('".$_POST['danza']."') WHERE cod_participante='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_participante_concurso.php?SES=$SES&anio=$anio&cod=$cod&modo=pit'</script>";	
}
elseif($action==UPDATE)
{
	$sql="UPDATE gcac_pit_participante_concurso SET cod_pit='".$_POST['pit']."',cod_pgrn='".$_POST['pgrn']."',cod_pdn_1='".$_POST['pdn_1']."',cod_pdn_2='".$_POST['pdn_2']."',cod_pdn_3='".$_POST['pdn_3']."',plato='".$_POST['plato']."',danza='".$_POST['danza']."',dni_rep='".$_POST['dni']."',nombre_rep='".$_POST['nombre']."' WHERE cod_concurso='$cod'";
	$result=mysql_query($sql) or die (mysql_error());

	$codigo=$cod;

	echo "<script>window.location ='m_participante_concurso.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}
elseif($action==DELETE_PARTICIPANTE)
{
	$sql="DELETE FROM gcac_pit_participante_concurso WHERE cod_participante='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location='n_participante_concurso.php?SES=$SES&anio=$anio&cod=$cod&modo=pit'</script>";
}


?>