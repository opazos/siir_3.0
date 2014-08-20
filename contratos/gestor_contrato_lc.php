<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($action==ADD)
{

	//1.- Guardo la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_pd='".$_POST['n_contrato']."' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Busco si existe el oferente
	
	$sql="SELECT * FROM ficha_ag_oferente WHERE cod_tipo_doc='008' AND n_documento='".$_POST['dni']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$total=mysql_num_rows($result);
	
	
	if ($total==0)
	{
		$sql="INSERT INTO ficha_ag_oferente VALUES('008','".$_POST['dni']."',UPPER('".$_POST['nombre']."'),UPPER('".$_POST['paterno']."'),UPPER('".$_POST['materno']."'),'".$_POST['f_nacimiento']."','".$_POST['sexo']."','','','','".$_POST['ubigeo']."',UPPER('".$_POST['direccion']."'),'".$_POST['tipo_oferente']."',UPPER('".$_POST['especialidad']."'))";
		$result=mysql_query($sql) or die (mysql_error());
	}
	else
	{
		$sql="UPDATE ficha_ag_oferente SET nombre=UPPER('".$_POST['nombre']."'),paterno=UPPER('".$_POST['paterno']."'),materno=UPPER('".$_POST['materno']."'),f_nacimiento='".$_POST['f_nacimiento']."',sexo='".$_POST['sexo']."',ubigeo='".$_POST['ubigeo']."',direccion=UPPER('".$_POST['direccion']."'),cod_tipo_oferente='".$_POST['tipo_oferente']."',especialidad=UPPER('".$_POST['especialidad']."') WHERE cod_tipo_doc='008' AND n_documento='".$_POST['dni']."'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	
	//3.- Registro el contrato
	$sql="INSERT INTO ficha_contrato_ls VALUES('','008','".$_POST['dni']."','".$_POST['n_contrato']."','".$_POST['f_contrato']."','".$_POST['mes']."',UPPER('".$_POST['puesto']."'),'".$_POST['monto']."','".$_POST['condiciones']."','".$_POST['poa']."','005','".$row['cod_dependencia']."','')";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Busco el ultimo registro generado
	$sql="SELECT * FROM ficha_contrato_ls ORDER BY cod_contrato DESC LIMIT 0,1";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$codigo=$r1['cod_contrato'];
	
	//5.- Actualizo la numeracion
	$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_pd='".$_POST['n_contrato']."' WHERE cod='".$_POST['codigo']."'";
	$result=mysql_query($sql) or die (mysql_error());
	
	
	//6.- Redirecciono
	echo "<script>window.location ='edit_contrato_lc.php?SES=$SES&anio=$anio&id=$codigo'</script>";
	
}
elseif($action==UPDATE)
{
	//1.- Guardamos cambios
	$sql="UPDATE ficha_contrato_ls SET contenido='".$_POST['contenido']."' WHERE cod_contrato='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Redireccionamos
	echo "<script>window.location ='../print/print_contrato_lc.php?SES=$SES&anio=$anio&cod=$id'</script>";
}


?>