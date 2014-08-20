<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
if ($_POST['idl']==NULL or $_POST['clar']==NULL)
{
	echo "<script>window.location ='n_idl_sd.php?SES=$SES&anio=$anio&error=vacio'</script>";
}
else
{
//1.- actualizo la numeracion de las iniciativas
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Ingreso la info de la ficha de segundo desembolso
$sql="INSERT INTO clar_bd_ficha_sd_idl VALUES('','".$_POST['clar']."','".$_POST['idl']."','".$_POST['f_desembolso']."','".$_POST['n_solicitud']."')";
$result=mysql_query($sql) or die (mysql_error());

$sql="SELECT * FROM clar_bd_ficha_sd_idl ORDER BY cod_ficha_sd LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=mysql_fetch_array($result);

//3.- Busco los montos de la IDL
$sql="SELECT pit_bd_ficha_idl.aporte_pdss, 
	pit_bd_ficha_idl.segundo_pago
FROM pit_bd_ficha_idl
WHERE pit_bd_ficha_idl.cod_ficha_idl='".$_POST['idl']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$aporte=($r2['aporte_pdss']*$r2['segundo_pago'])/100;

//4.- Busco el codigo POA
$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.3.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$poa=$r3['cod'];

//5.- Genero mi atf
$sql="INSERT INTO clar_atf_idl VALUES('','2','".$_POST['n_atf']."','2','$poa','$aporte','0','".$_POST['idl']."')";
$result=mysql_query($sql) or die (mysql_error());

//6.- Redirecciono
echo "<script>window.location ='../print/print_sd_idl.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}

}
elseif($action==UPDATE)
{

//1.- Actualizo la ficha sd
$sql="UPDATE clar_bd_ficha_sd_idl SET cod_clar='".$_POST['clar']."',f_desembolso='".$_POST['f_desembolso']."',n_solicitud='".$_POST['n_solicitud']."' WHERE cod_ficha_sd='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Busco info del IDL
$sql="SELECT pit_bd_ficha_idl.aporte_pdss, 
pit_bd_ficha_idl.segundo_pago
FROM pit_bd_ficha_idl
WHERE pit_bd_ficha_idl.cod_ficha_idl='".$_POST['idl']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$aporte=($r2['aporte_pdss']*$r2['segundo_pago'])/100;

//3.- Busco el POA
$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.3.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$poa=$r3['cod'];

//3.- Actualizo la IDL
$sql="UPDATE clar_atf_idl SET n_atf='".$_POST['n_atf']."',cod_componente='2',cod_poa='$poa',monto_desembolsado='$aporte',saldo='0' WHERE cod_atf_idl='".$_POST['cod_atf']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//4.- Redirecciono
echo "<script>window.location ='../print/print_sd_idl.php?SES=$SES&anio=$anio&cod=$codigo'</script>";

}

?>