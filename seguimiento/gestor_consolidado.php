<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{

foreach($n_participante as $cad=>$a)
{
	$sql="UPDATE cif_bd_ficha SET n_participantes='".$_POST['n_participante'][$cad]."',n_mujeres='".$_POST['n_mujeres'][$cad]."',n_varones='".$_POST['n_varones'][$cad]."',meta='".$_POST['meta'][$cad]."',valor_meta='".$_POST['valor_meta'][$cad]."',n_premios='".$_POST['n_premio'][$cad]."',monto_premios='".$_POST['monto_premio'][$cad]."',premio_max='".$_POST['premio_max'][$cad]."',premio_min='".$_POST['premio_min'][$cad]."',premio_otr='".$_POST['premio_otro'][$cad]."' WHERE cod_ficha_cif='$cad'";
	$result=mysql_query($sql) or die (mysql_error());
}

//redireccionamos
echo "<script>window.location ='cif.php?SES=$SES&anio=$anio&modo=consolidado'</script>";

}

?>