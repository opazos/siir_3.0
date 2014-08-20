<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//Buscamos cuantas ampliaciones tiene este PIT
$sql="SELECT
Count(clar_ampliacion_pit.cod_ampliacion) AS ampliaciones
FROM
clar_ampliacion_pit
WHERE
clar_ampliacion_pit.cod_pit='".$_POST['n_contrato']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$total_ampliacion=$r1['ampliaciones']+1;

if ($action==ADD)
{
if ($_POST['n_contrato']==NULL or $_POST['clar']==NULL)
{
echo "<script>window.location ='n_contrato_pit_ampliacion.php?SES=$SES&anio=$anio&error=vacio'</script>";
}
else
{
//1.- Actualizo la numeracion de la solicitud
$sql="UPDATE sys_bd_numera_dependencia SET n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.-Ingreso la ampliacion
$sql="INSERT INTO clar_ampliacion_pit VALUES('','$total_ampliacion','".$_POST['f_ampliacion']."','".$_POST['n_solicitud']."','".$_POST['n_contrato']."','".$_POST['clar']."','".$_POST['fte_fida']."','".$_POST['fte_ro']."')";
$result=mysql_query($sql) or die (mysql_error());

//3.- Obtengo el codigo
$sql="SELECT * FROM clar_ampliacion_pit ORDER BY cod_ampliacion DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$codigo=$r2['cod_ampliacion'];

//4.- Redirecciono
echo "<script>window.location ='n_contrato_pit_ampliacion.php?SES=$SES&anio=$anio&cod=$codigo&modo=pdn'</script>";

}

}
elseif($action==ADD_PDN)
{
if ($_POST['pdn']==NULL)
{
echo "<script>window.location ='n_contrato_pit_ampliacion.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn&error=vacio'</script>";
}
else
{
//1.- Actualizo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Realizo calculos
$sql="SELECT (pit_bd_ficha_pdn.at_pdss*0.70) as monto_1, 
	(pit_bd_ficha_pdn.vg_pdss*0.70) as monto_2, 
	(pit_bd_ficha_pdn.fer_pdss*0.70) as monto_3, 
	(pit_bd_ficha_pdn.total_apoyo*0.70) as monto_4,
	(pit_bd_ficha_pdn.at_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_pdn.vg_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_pdn.fer_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_pdn.total_apoyo*0.30) as saldo_4
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Obtengo la afectacion poa
$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.1.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.3.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$poa_1=$r2['cod'];
$poa_2=$r3['cod'];
$poa_3=$r4['cod'];
$poa_4=$r5['cod'];

//4.- Registro el ATF
$sql="INSERT INTO clar_atf_pdn VALUES('','3','0','','0','".$_POST['n_atf']."','2','$poa_1','$poa_2','$poa_3','$poa_4','".$r1['monto_1']."','".$r1['saldo_1']."','".$r1['monto_2']."','".$r1['saldo_2']."','".$r1['monto_3']."','".$r1['saldo_3']."','".$r1['monto_4']."','".$r1['saldo_4']."','".$_POST['pdn']."','$cod')";
$result=mysql_query($sql) or die (mysql_error());

//5.- actualizo el estado del PDN
$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='005' WHERE cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());

//6.- redirecciono
echo "<script>window.location ='n_contrato_pit_ampliacion.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn'</script>";
}

}
elseif($action==UPDATE)
{
$sql="UPDATE clar_ampliacion_pit SET f_ampliacion='".$_POST['f_ampliacion']."',n_solicitud='".$_POST['n_solicitud']."',fte_fida='".$_POST['fte_fida']."',fte_ro='".$_POST['fte_ro']."' WHERE cod_ampliacion='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//redirecciono
echo "<script>window.location ='n_contrato_pit_ampliacion.php?SES=$SES&anio=$anio&cod=$codigo&modo=pdn'</script>";

}
elseif($action==UPDATE_PDN)
{
//1.- Busco la info del ATF del PDN
$sql="SELECT * FROM clar_atf_pdn WHERE cod_atf_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//2.- Realizo Calculos
$sql="SELECT (pit_bd_ficha_pdn.at_pdss*0.70) as monto_1, 
	(pit_bd_ficha_pdn.vg_pdss*0.70) as monto_2, 
	(pit_bd_ficha_pdn.fer_pdss*0.70) as monto_3, 
	(pit_bd_ficha_pdn.total_apoyo*0.70) as monto_4,
	(pit_bd_ficha_pdn.at_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_pdn.vg_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_pdn.fer_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_pdn.total_apoyo*0.30) as saldo_4
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$r1['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//3.- Ubico los codigos POA
$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.1.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.3.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

$poa_1=$r3['cod'];
$poa_2=$r4['cod'];
$poa_3=$r5['cod'];
$poa_4=$r6['cod'];

//4.- Actualizo el ATF
$sql="UPDATE clar_atf_pdn SET cod_poa_1='$poa_1',cod_poa_2='$poa_2',cod_poa_3='$poa_3',cod_poa_4='$poa_4',monto_1='".$r1['monto_1']."',saldo_1='".$r1['saldo_1']."',monto_2='".$r1['monto_2']."',saldo_2='".$r1['saldo_2']."',monto_3='".$r1['monto_3']."',saldo_3='".$r1['saldo_3']."',monto_4='".$r1['monto_4']."',saldo_4='".$r1['saldo_4']."' WHERE cod_atf_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());

//5.- Redirecciono
echo "<script>window.location ='n_contrato_pit_ampliacion.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn&alert=success_change'</script>";
}

?>