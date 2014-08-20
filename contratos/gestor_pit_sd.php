<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($action==ADD)
{
//1.- guardo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Guardo la informacion
$sql="INSERT INTO clar_bd_ficha_sd_pit VALUES('','".$_POST['clar']."','".$_POST['pit']."','".$_POST['f_desembolso']."','".$_POST['n_solicitud']."','".$_POST['fida']."','".$_POST['ro']."')";
$result=mysql_query($sql) or die (mysql_error());

//3.- Busco el codigo generado
$sql="SELECT * FROM clar_bd_ficha_sd_pit ORDER BY cod_ficha_sd DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$codigo=$r1['cod_ficha_sd'];

//4.- Redirecciono
echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$codigo&modo=animador'</script>";
}
elseif($action==ADD_PIT)
{
//1.- guardo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Realizo los calculos
$sql="SELECT (pit_bd_ficha_pit.aporte_pdss*0.30) as monto
FROM pit_bd_ficha_pit
WHERE pit_bd_ficha_pit.cod_pit='".$_POST['pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
	
//3.- Busco el codigo POA
$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '3.1.2.8.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$poa=$r2['cod'];
	
//4.- ingreso el atf
$sql="INSERT INTO clar_atf_pit_sd VALUES('','".$_POST['pit']."','$cod','".$_POST['n_atf']."','3','$poa','".$r1['monto']."','0')";
$result=mysql_query($sql) or die (mysql_error());

//4.- Actualizo el estado del PIT
$sql="UPDATE pit_bd_ficha_pit SET cod_estado_iniciativa='008' WHERE cod_pit='".$_POST['pit']."'";
$result=mysql_query($sql) or die (mysql_error());

//5.- Redirecciono
echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn'</script>";
}
elseif($action==UPDATE_PIT)
{
//1.- Busco la información del ATF del PIT
$sql="SELECT clar_atf_pit_sd.cod_pit
FROM clar_atf_pit_sd
WHERE cod_atf_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//2.- Realizo calculos
$sql="SELECT (pit_bd_ficha_pit.aporte_pdss*0.30) as monto
FROM pit_bd_ficha_pit
WHERE pit_bd_ficha_pit.cod_pit='".$r1['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//3.- Busco el codigo POA
$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '3.1.2.8.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$poa=$r3['cod'];

//4.- Actualizo la ficha ATF
$sql="UPDATE clar_atf_pit_sd SET cod_poa='$poa',monto_desembolsado='".$r2['monto']."',saldo='0' WHERE cod_atf_pit='$id'";
$result=mysql_query($sql) or die (mysql_error());

//5.- Redirecciono
echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$cod&modo=animador&alert=success_change'</script>";
}
elseif($action==ADD_PDN)
{
//1.- guardo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- realizo los calculos
$sql="SELECT (pit_bd_ficha_pdn.at_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_pdn.vg_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_pdn.fer_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_pdn.total_apoyo*0.30) as saldo_4
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Busco los codigos POA
$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.1.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);
$poa_1=$r2['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
$poa_2=$r3['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);
$poa_3=$r4['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.3.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);
$poa_4=$r5['cod'];

//4.- Guardo la ATF
$sql="INSERT INTO clar_atf_pdn VALUES('','2','','','','".$_POST['n_atf']."','2','$poa_1','$poa_2','$poa_3','$poa_4','".$r1['saldo_1']."','0','".$r1['saldo_2']."','0','".$r1['saldo_3']."','0','".$r1['saldo_4']."','0','".$_POST['pdn']."','$cod')";
$result=mysql_query($sql) or die (mysql_error());

//5.- Actualizo el PDN
$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='008' WHERE cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());

//6.- Redirecciono
echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn'</script>";
}
elseif($action==UPDATE_ATF_PDN)
{
//1.-Busco la informacion de la ATF
$sql="SELECT * FROM clar_atf_pdn
WHERE clar_atf_pdn.cod_atf_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//2.- Realizo los calculos
$sql="SELECT (pit_bd_ficha_pdn.at_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_pdn.vg_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_pdn.fer_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_pdn.total_apoyo*0.30) as saldo_4
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$r1['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result); 

//3.- Busco los codigos POA
$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.1.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
$poa_1=$r3['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);
$poa_2=$r4['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);
$poa_3=$r5['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.3.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);
$poa_4=$r6['cod'];

//4.- Actualizo el atf
$sql="UPDATE clar_atf_pdn SET cod_poa_1='$poa_1',cod_poa_2='$poa_2',cod_poa_3='$poa_3',cod_poa_4='$poa_4',monto_1='".$r2['saldo_1']."',monto_2='".$r2['saldo_2']."',monto_3='".$r2['saldo_3']."',monto_4='".$r2['saldo_4']."' WHERE cod_atf_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());

//5.- Redirecciono
echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn&alert=success_change'</script>";
}

elseif($action==ADD_MRN)
{
//1.- guardo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Realizo calculos
$sql="SELECT (pit_bd_ficha_mrn.cif_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_mrn.at_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_mrn.vg_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_mrn.ag_pdss*0.30) as saldo_4
FROM pit_bd_ficha_mrn
WHERE pit_bd_ficha_mrn.cod_mrn='".$_POST['mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Busco los codigos POA
$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.1.1.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);
$poa_1=$r2['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.1.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
$poa_2=$r3['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);
$poa_3=$r4['cod'];

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.3.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);
$poa_4=$r5['cod'];

//4.- Registro el ATF
$sql="INSERT INTO clar_atf_mrn_sd VALUES('','".$_POST['mrn']."','$cod','".$_POST['n_atf']."','1','$poa_1','$poa_2','$poa_3','$poa_4','".$r1['saldo_1']."','0','".$r1['saldo_2']."','0','".$r1['saldo_3']."','0','".$r1['saldo_4']."','0')";
$result=mysql_query($sql) or die (mysql_error());

//4.- Actualizo estados
$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='008' WHERE cod_mrn='".$_POST['mrn']."'";
$result=mysql_query($sql) or die (mysql_error());

//5.- redirecciono
echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$cod&modo=mrn'</script>";
}
elseif($action==UPDATE_ATF_MRN)
{
	//1.- Busco los datos del PGRN
	$sql="SELECT clar_atf_mrn_sd.cod_atf_mrn, 
	clar_atf_mrn_sd.cod_mrn
	FROM clar_atf_mrn_sd
	WHERE clar_atf_mrn_sd.cod_atf_mrn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//2.- Realizo calculos
	$sql="SELECT (pit_bd_ficha_mrn.cif_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_mrn.at_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_mrn.vg_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_mrn.ag_pdss*0.30) as saldo_4
	FROM pit_bd_ficha_mrn
	WHERE pit_bd_ficha_mrn.cod_mrn='".$r1['cod_mrn']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
	
	//3.- Busco los codigos POA
	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '1.1.1.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	$poa_1=$r3['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.1.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r4=mysql_fetch_array($result);
	$poa_2=$r4['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.2.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r5=mysql_fetch_array($result);
	$poa_3=$r5['cod'];

	$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo
	FROM sys_bd_subactividad_poa
	WHERE sys_bd_subactividad_poa.codigo LIKE '1.3.2.2.' AND
	sys_bd_subactividad_poa.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r6=mysql_fetch_array($result);
	$poa_4=$r6['cod'];	
	
	//4.- Actualizo el ATF
	$sql="UPDATE clar_atf_mrn_sd SET cod_poa_1='$poa_1',cod_poa_2='$poa_2',cod_poa_3='$poa_3',cod_poa_4='$poa_4',monto_1='".$r2['saldo_1']."',monto_2='".$r2['saldo_2']."',monto_3='".$r2['saldo_3']."',monto_4='".$r2['saldo_4']."' WHERE cod_atf_mrn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//5.- Redirecciono
	echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$cod&modo=mrn&alert=success_change'</script>";	

}













elseif($action==UPDATE)
{
//1.- Actualizo la ficha de segundo desembolso
$sql="UPDATE clar_bd_ficha_sd_pit SET f_desembolso='".$_POST['f_desembolso']."',n_solicitud='".$_POST['n_solicitud']."',fte_fida='".$_POST['fida']."',fte_ro='".$_POST['ro']."' WHERE cod_ficha_sd='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- Redirecciono
echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$codigo&modo=animador'</script>";
}

elseif($action==ANULA_ATF_PDN)
{
	//1.- busco el PDN
	$sql="SELECT clar_atf_pdn.cod_pdn
	FROM clar_atf_pdn
	WHERE clar_atf_pdn.cod_atf_pdn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//2.- Actualizo el ATF
	$sql="UPDATE clar_atf_pdn SET monto_1='0',monto_2='0',monto_3='0',monto_4='0' WHERE cod_atf_pdn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- redirecciono
	echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn'</script>";
}
elseif($action==UPDATE_ATF_MRN)
{
	//1.- Busco el MRN
	$sql="SELECT clar_atf_mrn_sd.cod_mrn
	FROM clar_atf_mrn_sd
	WHERE clar_atf_mrn_sd.cod_atf_mrn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	//2.- Realizo calculos
	$sql="SELECT (pit_bd_ficha_mrn.cif_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_mrn.at_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_mrn.vg_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_mrn.ag_pdss*0.30) as saldo_4
	FROM pit_bd_ficha_mrn
	WHERE pit_bd_ficha_mrn.cod_mrn='".$r1['cod_mrn']."'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);

	//3.- Actualizo el ATF
	$sql="UPDATE clar_atf_mrn_sd SET monto_1='".$r2['saldo_1']."',monto_2='".$r2['saldo_2']."',monto_3='".$r2['saldo_3']."',monto_4='".$r2['saldo_4']."' WHERE cod_atf_mrn='$id'";
	$result=mysql_query($sql) or die (mysql_error());

	//4.- Actualizo estados
	$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='008' WHERE cod_mrn='".$r1['cod_mrn']."'";
	$result=mysql_query($sql) or die (mysql_error());

	//5.- redirecciono
	echo "<script>window.location ='n_pit_sd.php?SES=$SES&anio=$anio&cod=$cod&modo=mrn'</script>";
	
}




?>