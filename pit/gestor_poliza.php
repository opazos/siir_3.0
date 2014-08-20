<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{

	//1.- Verificamos si nos envia un registro el combo
	if ($_POST['user']<>NULL)
	{
		$usuario=$_POST['user'];
	}
	else
	{
		$codigo="0000-0".$row['cod_dependencia'];;
		
		//1.a.- Ingresamos al usuario
		$sql="INSERT INTO org_ficha_usuario VALUES('008','".$_POST['dni']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['f_nacimiento']."','".$_POST['sexo']."','".$_POST['ubigeo']."','".$_POST['direccion']."','".$_POST['jefe']."','0','".$_POST['hijo']."','','','','1','000','$codigo')";
		$result=mysql_query($sql) or die (mysql_error());		

		
		$usuario=$_POST['dni'];
	}
	//2.- Procedo a guardar la info de poliza
	$sql="INSERT INTO sf_bd_poliza VALUES('".$_POST['n_poliza']."','".$_POST['f_emision']."','".$_POST['tipo_seguro']."','".$_POST['aporte_pdss']."','".$_POST['aporte_org']."','008','$usuario','002')";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='n_poliza.php?SES=$SES&anio=$anio'</script>";
}
elseif($action==UPDATE)
{
	$sql="UPDATE sf_bd_poliza SET f_emision='".$_POST['f_emision']."',cod_tipo_seguro='".$_POST['tipo_seguro']."',aporte_pdss='".$_POST['aporte_pdss']."',aporte_org='".$_POST['aporte_org']."' WHERE cod_poliza='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='poliza.php?SES=$SES&anio=$anio&modo=edita'</script>";
}
elseif($action==DELETE)
{
	$sql="DELETE FROM sf_bd_poliza WHERE cod_poliza='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	echo "<script>window.location ='poliza.php?SES=$SES&anio=$anio&modo=edita'</script>";	
}

?>