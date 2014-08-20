<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	$sql="INSERT INTO bd_registro_liquida VALUES('','".$_POST['tipo_iniciativa']."','','','','','".$_POST['cod_dependencia']."','','','','','','')";
	$result=mysql_query($sql) or die (mysql_error());

	$sql="SELECT * FROM bd_registro_liquida ORDER BY cod DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$codigo=$r1['cod'];

	echo "<script>window.location='n_registro_liquida.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==UPDATE)
{

$iniciativa=$_POST['cod_iniciativa'];
$dato=explode(",",$iniciativa);
$cod_iniciativa=$dato[0];
$n_contrato=$dato[1];	
$f_contrato=$dato[2];

	$sql="UPDATE bd_registro_liquida SET cod_iniciativa='$cod_iniciativa',n_contrato='$n_contrato',f_contrato='$f_contrato',f_liquidacion='".$_POST['f_liquida']."',f_ingreso='".$_POST['f_ingreso']."',f_salida='".$_POST['f_salida']."',f_ingreso_2='".$_POST['f_ingreso_2']."',f_salida_2='".$_POST['f_salida_2']."',n_folio='".$_POST['n_folio']."',comentario=UPPER('".$_POST['comentario']."') WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());

	echo "<script>window.location='registro_liquida.php?SES=$SES&anio=$anio&modo=edit'</script>";
}


?>