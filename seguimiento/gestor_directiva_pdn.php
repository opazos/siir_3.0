<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==UPDATE)
{

//1.- Busco los datos de la organizacion
$sql="SELECT pit_bd_ficha_pdn.cod_tipo_doc_org, 
	pit_bd_ficha_pdn.n_documento_org
	FROM pit_bd_ficha_pdn
	WHERE pit_bd_ficha_pdn.cod_pdn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
//2.- Genero los nuevos directivos
for($i=0;$i<=5;$i++)
{
if ($_POST['nombre'][$i]<>NULL)
{
$sql="INSERT INTO org_ficha_directivo VALUES('','008','".$_POST['nombre'][$i]."','".$_POST['cargo'][$i]."','','".$_POST['f_vigencia'][$i]."','2','1','".$r1['cod_tipo_doc_org']."','".$r1['n_documento_org']."')";
$result=mysql_query($sql) or die (mysql_error());
}
}	

//3.- Actualizamos la vigencia de la directiva
foreach($vigente as $cad=>$a1)
{
	$sql="UPDATE org_ficha_directivo SET vigente='".$_POST['vigente'][$cad]."' WHERE cod_directivo='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}

//4.- Busco el Presidente y Tesorero Vigente
$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=1 AND
org_ficha_directivo.vigente=1 AND
org_ficha_directivo.cod_tipo_doc_org='".$r1['cod_tipo_doc_org']."' AND
org_ficha_directivo.n_documento_org='".$r1['n_documento_org']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="SELECT org_ficha_directivo.n_documento
FROM org_ficha_directivo
WHERE org_ficha_directivo.cod_cargo=3 AND
org_ficha_directivo.vigente=1 AND
org_ficha_directivo.cod_tipo_doc_org='".$r1['cod_tipo_doc_org']."' AND
org_ficha_directivo.n_documento_org='".$r1['n_documento_org']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//5.- Actualizo los cargos de la junta directiva
$sql="UPDATE org_ficha_organizacion SET presidente='".$r2['n_documento']."' WHERE cod_tipo_doc='".$r1['cod_tipo_doc_org']."' AND n_documento='".$r1['n_documento_org']."'";
$result=mysql_query($sql) or die (mysql_error());

$sql="UPDATE org_ficha_organizacion SET tesorero='".$r3['n_documento']."' WHERE cod_tipo_doc='".$r1['cod_tipo_doc_org']."' AND n_documento='".$r1['n_documento_org']."'";
$result=mysql_query($sql) or die (mysql_error());


//5.- redirecciono
echo "<script>window.location ='directiva_pdn.php?SES=$SES&anio=$anio&id=$id'</script>";
	
}


?>