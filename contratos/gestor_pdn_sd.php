<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
if ($_POST['pdn']==NULL or $_POST['clar']==NULL)
{
echo "<script>window.location ='n_pdn_sd.php?SES=$SES&anio=$anio&error=vacio'</script>";
}
else
{
//1.- Actualizamos la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Genero la ficha desegundo desembolso
$sql="INSERT INTO clar_bd_ficha_sd_pdn VALUES('','".$_POST['clar']."','".$_POST['pdn']."','".$_POST['f_desembolso']."','".$_POST['n_solicitud']."','".$_POST['fida']."','".$_POST['ro']."')";
$result=mysql_query($sql) or die (mysql_error());

//3.- ubico el registro generado
$sql="SELECT * FROM clar_bd_ficha_sd_pdn ORDER BY cod_ficha_sd DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_ficha_sd'];

//4.- ubicamos los datos del PDN
$sql="SELECT (pit_bd_ficha_pdn.total_apoyo*0.30) as monto1, 
	(pit_bd_ficha_pdn.at_pdss*0.30) as monto2, 
	(pit_bd_ficha_pdn.vg_pdss*0.30) as monto3, 
	(pit_bd_ficha_pdn.fer_pdss*0.30) as monto4
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);


//5.- Ubico los codigos POA
	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.1.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	$poa_1=$r3['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.1.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r4=mysql_fetch_array($result);
	$poa_2=$r4['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r5=mysql_fetch_array($result);
	$poa_3=$r5['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '2.3.2.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r6=mysql_fetch_array($result);
	$poa_4=$r6['cod'];	

//6.- Genero mi atf
$sql="INSERT INTO clar_atf_pdn VALUES('','6','','','','".$_POST['n_atf']."','2','$poa_1','$poa_2','$poa_3','$poa_4','".$r2['monto2']."','0','".$r2['monto3']."','0','".$r2['monto4']."','0','".$r2['monto1']."','0','".$_POST['pdn']."','$codigo')";
$result=mysql_query($sql) or die (mysql_error());

//7.- actualizo el estado del pdn
$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='008' WHERE cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());

//8.- Redirecciono
echo "<script>window.location ='../print/print_pdn_sd.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}

}
elseif($action==UPDATE)
{
//1.- procedo a actualizar la ficha de segundo desembolso
$sql="UPDATE clar_bd_ficha_sd_pdn SET cod_clar='".$_POST['clar']."',f_desembolso='".$_POST['f_desembolso']."',n_solicitud='".$_POST['n_solicitud']."',fte_fida='".$_POST['fida']."',fte_ro='".$_POST['ro']."' WHERE cod_ficha_sd='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- busco la informacion del PDN
$sql="SELECT (pit_bd_ficha_pdn.total_apoyo*0.30) as monto1, 
	(pit_bd_ficha_pdn.at_pdss*0.30) as monto2, 
	(pit_bd_ficha_pdn.vg_pdss*0.30) as monto3, 
	(pit_bd_ficha_pdn.fer_pdss*0.30) as monto4
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//3.- Ubico los Codigos POA
	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.1.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	$poa_1=$r3['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.1.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r4=mysql_fetch_array($result);
	$poa_2=$r4['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r5=mysql_fetch_array($result);
	$poa_3=$r5['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '2.3.2.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r6=mysql_fetch_array($result);
	$poa_4=$r6['cod'];	


//3.- actualizo el ATF
$sql="UPDATE clar_atf_pdn SET n_atf='".$_POST['n_atf']."',cod_poa_1='$poa_1',cod_poa_2='$poa_2',cod_poa_3='$poa_3',cod_poa_4='$poa_4',monto_1='".$r2['monto2']."',monto_2='".$r2['monto3']."',monto_3='".$r2['monto4']."',monto_4='".$r2['monto1']."' WHERE cod_atf_pdn='".$_POST['cod_atf']."'";
$result=mysql_query($sql) or die (mysql_error());

//4.- redirecciono
echo "<script>window.location ='../print/print_pdn_sd.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}



?>