<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if($action==UPDATE)
{
	foreach($puesto as $cad=>$a)
	{
		$sql="UPDATE gcac_participante_concurso SET puesto='".$_POST['puesto'][$cad]."',premio='".$_POST['premio'][$cad]."' WHERE cod_participante='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//2.- Redirecciono
	echo "<script>window.location ='n_premia_pdn.php?SES=$SES&anio=$anio&cod=$cod'</script>";
}


?>