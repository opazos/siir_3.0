<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
	if ($_POST['pdn']==NULL)
	{
	echo "<script>window.location ='n_vg_pdn.php?SES=$SES&anio=$anio&error=vacio'</script>";	
	}
	else
	{
		//1.- Genero la visita guiada
		$sql="INSERT INTO ficha_vg VALUES('','4','".$_POST['pdn']."','".$_POST['f_inicio']."','".$_POST['f_termino']."',UPPER('".$_POST['lugar']."'),UPPER('".$_POST['objetivo']."'),UPPER('".$_POST['resultado']."'),'".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','".$_POST['aporte_otro']."')";
		$result=mysql_query($sql) or die (mysql_error());
		
		//2.- obtengo el ultimo registro generado
		$sql="SELECT * FROM ficha_vg ORDER BY cod_visita DESC LIMIT 0,1";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		$codigo=$r1['cod_visita'];
		
		//3.- Redirecciono
		echo "<script>window.location ='n_vg_pdn.php?SES=$SES&anio=$anio&cod=$codigo&modo=asistente'</script>";
	}
}
elseif($action==ADD_ASISTENTE)
{
	for($i=0;$i<count($_POST['campos']);$i++) 
	{
		$sql="INSERT IGNORE INTO ficha_participante_vg VALUES('008','".$_POST['campos'][$i]."','$cod')";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	echo "<script>window.location ='n_vg_pdn.php?SES=$SES&anio=$anio&cod=$cod&modo=asistente'</script>";
}
elseif($action==UPDATE)
{
$sql="UPDATE ficha_vg SET f_inicio='".$_POST['f_inicio']."',f_termino='".$_POST['f_termino']."',lugar=UPPER('".$_POST['lugar']."'),objetivo=UPPER('".$_POST['objetivo']."'),resultado=UPPER('".$_POST['resultado']."'),aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."',aporte_otro='".$_POST['aporte_otro']."' WHERE cod_visita='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

		echo "<script>window.location ='n_vg_pdn.php?SES=$SES&anio=$anio&cod=$codigo&modo=asistente'</script>";
}
elseif($action==DELETE)
{
$sql="DELETE FROM ficha_vg WHERE cod_visita='$id'";
$result=mysql_query($sql) or die (mysql_error());

echo "<script>window.location ='vg_pdn.php?SES=$SES&anio=$anio&modo=edit'</script>";
}

?>