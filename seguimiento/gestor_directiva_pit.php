<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==UPDATE)
{

//1.- Busco los datos de la organizacion
$sql="SELECT pit_bd_ficha_pit.cod_tipo_doc_taz, 
	pit_bd_ficha_pit.n_documento_taz
FROM pit_bd_ficha_pit
WHERE pit_bd_ficha_pit.cod_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//2.- Genero los nuevos directivos
for($i=0;$i<=5;$i++)
{
	if ($_POST['nombre'][$i]<>NULL)
	{
		$sql="INSERT INTO org_ficha_directiva_taz VALUES('008','".$_POST['dni'][$i]."','".$_POST['nombre'][$i]."','".$_POST['apellidop'][$i]."',UPPER('".$_POST['apellidom'][$i]."'),'".$_POST['fecha'][$i]."','".$_POST['sexo'][$i]."','".$_POST['cargo'][$i]."','','".$_POST['vigencia'][$i]."','2','1','".$r1['cod_tipo_doc_taz']."','".$r1['n_documento_taz']."')";
		$result=mysql_query($sql) or die (mysql_error());
	}
}

//3.- Actualizo la vigencia de los directivos
foreach($vigente as $cad=>$a1)
{
	$sql="UPDATE org_ficha_directiva_taz SET vigente='".$_POST['vigente'][$cad]."' WHERE n_documento='$cad' AND cod_tipo_doc_taz='".$r1['cod_tipo_doc_taz']."' AND n_documento_taz='".$r1['n_documento_taz']."'";
	$result=mysql_query($sql) or die (mysql_error());
}



//4.- Busco el tesorero y presidente vigente
$sql="SELECT org_ficha_directiva_taz.n_documento
FROM org_ficha_directiva_taz
WHERE org_ficha_directiva_taz.cod_tipo_doc_taz='".$r1['cod_tipo_doc_taz']."' AND
org_ficha_directiva_taz.n_documento_taz='".$r1['n_documento_taz']."' AND
org_ficha_directiva_taz.vigente=1 AND
org_ficha_directiva_taz.cod_cargo_directivo=1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="SELECT org_ficha_directiva_taz.n_documento
FROM org_ficha_directiva_taz
WHERE org_ficha_directiva_taz.cod_tipo_doc_taz='".$r1['cod_tipo_doc_taz']."' AND
org_ficha_directiva_taz.n_documento_taz='".$r1['n_documento_taz']."' AND
org_ficha_directiva_taz.vigente=1 AND
org_ficha_directiva_taz.cod_cargo_directivo=3";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//5.- Actualizo el presidente y tesorero
$sql="UPDATE org_ficha_taz SET presidente='".$r2['n_documento']."',tesorero='".$r3['n_documento']."' WHERE cod_tipo_doc='".$r1['cod_tipo_doc_taz']."' AND n_documento='".$r1['n_documento_taz']."'";
$result=mysql_query($sql) or die (mysql_error());

//6.- Redirecciono
echo "<script>window.location ='directiva_pit.php?SES=$SES&anio=$anio&id=$id'</script>";

}


?>