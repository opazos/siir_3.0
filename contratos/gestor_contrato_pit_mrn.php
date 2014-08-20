<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//buscamos la oficina
$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$fila=mysql_fetch_array($result);

//Busco el correo del Jefe de la Oficina Local
$sql="SELECT
sys_bd_personal.correo
FROM
sys_bd_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE
sys_bd_dependencia.cod_dependencia='".$fila['cod_dependencia']."'";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);
$correo_jefe=$i1['correo'];

if ($action==ADD)
{
if ($_POST['pit']==NULL)
{
echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&modo=pit&error=vacio'</script>";
}
else
{
$fecha=$_POST['f_contrato'];
$mes=$_POST['duracion'];

$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);

	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-30';
	}
	else
	{
		$f_termino=$f_termino;
	}

//1.- Actualizo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_iniciativa='".$_POST['n_contrato']."',n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numeracion']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Guardamos la info del PIT
$sql="UPDATE pit_bd_ficha_pit SET n_contrato='".$_POST['n_contrato']."',f_contrato='".$_POST['f_contrato']."',mes='".$_POST['duracion']."',f_termino='$f_termino',n_solicitud='".$_POST['n_solicitud']."',fuente_fida='".$_POST['fte_fida']."',fuente_ro='".$_POST['fte_ro']."',cod_estado_iniciativa='005' WHERE cod_pit='".$_POST['pit']."'";
$result=mysql_query($sql) or die (mysql_error());

//3.- Realizo la busqueda y calculo de los Montos del PIT
$sql="SELECT pit_bd_ficha_pit.cod_pit,
pit_bd_ficha_pit.cod_tipo_iniciativa,
pit_bd_ficha_pit.cod_tipo_doc_taz,
pit_bd_ficha_pit.n_documento_taz,
pit_bd_ficha_pit.f_presentacion,
pit_bd_ficha_pit.n_contrato,
pit_bd_ficha_pit.f_contrato,
pit_bd_ficha_pit.n_animador,
pit_bd_ficha_pit.incentivo_animador,
pit_bd_ficha_pit.n_mes,
pit_bd_ficha_pit.aporte_pdss,
pit_bd_ficha_pit.aporte_org,
pit_bd_ficha_pit.fuente_fida,
pit_bd_ficha_pit.fuente_ro,
pit_bd_ficha_pit.tiene_cuenta,
pit_bd_ficha_pit.n_cuenta,
pit_bd_ficha_pit.cod_ifi,
pit_bd_ficha_pit.calificacion,
pit_bd_ficha_pit.cod_estado_iniciativa,
pit_bd_ficha_pit.n_voucher,
pit_bd_ficha_pit.monto_organizacion,
org_ficha_taz.nombre
FROM pit_bd_ficha_pit
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.cod_pit='".$_POST['pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);
	
$total=$row['aporte_pdss'];
$primer_desembolso=$total*0.70;
$saldo=$total-$primer_desembolso;

//4.- Busco los datos del Componente y subactividad
$sql="SELECT sys_bd_componente_poa.cod
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
WHERE sys_bd_subactividad_poa.cod='".$_POST['poa']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$componente=$r3['cod'];

//5.- Generamos el ATF	
$sql="INSERT INTO clar_atf_pit VALUES('','".$_POST['n_atf']."','$componente','".$_POST['poa']."','$primer_desembolso','$saldo','','','".$_POST['pit']."')";
$result=mysql_query($sql) or die (mysql_error());
	
$codigo=$_POST['pit'];
	
//6.- Redireccionamos
echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&cod=$codigo&modo=pdn'</script>";
}
}
elseif($action==ADD_PDN)
{
if ($_POST['pdn']==NULL)
{
echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn&error=vacio'</script>";
}
else
{
//1.- Actualizo la numeracion de la oficina
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Realizo los calculos
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


//3.- Ingreso la info del ATF
$sql="INSERT INTO clar_atf_pdn VALUES('','1','','','','".$_POST['n_atf']."','2','$poa_1','$poa_2','$poa_3','$poa_4','".$r1['monto_1']."','".$r1['saldo_1']."','".$r1['monto_2']."','".$r1['saldo_2']."','".$r1['monto_3']."','".$r1['saldo_3']."','".$r1['monto_4']."','".$r1['saldo_4']."','".$_POST['pdn']."','".$_POST['cod_atf']."')";
$result=mysql_query($sql) or die (mysql_error());

//4.- Actualizo el estado del plan de negocio
$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='005' WHERE cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());

//4.- Redirecciono
	echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn'</script>";
}
}
elseif($action==ADD_MRN)
{
if ($_POST['mrn']==NULL)
{
echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&cod=$cod&modo=mrn&error=vacio'</script>";
}
else
{
//1.- Guardo la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_atf_iniciativa='".$_POST['n_atf']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Realizo los calculos
$sql="SELECT (pit_bd_ficha_mrn.cif_pdss*0.70) as monto_1, 
	(pit_bd_ficha_mrn.at_pdss*0.70) as monto_2, 
	(pit_bd_ficha_mrn.vg_pdss*0.70) as monto_3, 
	(pit_bd_ficha_mrn.ag_pdss*0.70) as monto_4,
	(pit_bd_ficha_mrn.cif_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_mrn.at_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_mrn.vg_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_mrn.ag_pdss*0.30) as saldo_4
FROM pit_bd_ficha_mrn
WHERE pit_bd_ficha_mrn.cod_mrn='".$_POST['mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//3.- Busco los codigos POA segun el Periodo
$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.1.1.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.1.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.3.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$poa_1=$r2['cod'];
$poa_2=$r3['cod'];
$poa_3=$r4['cod'];
$poa_4=$r5['cod'];





//3.- Ingreso el ATF
$sql="INSERT INTO clar_atf_mrn VALUES('','".$_POST['n_atf']."','1','$poa_1','".$r1['monto_1']."','".$r1['saldo_1']."','$poa_2','".$r1['monto_2']."','".$r2['saldo_2']."','$poa_3','".$r1['monto_3']."','".$r1['saldo_3']."','$poa_4','".$r1['monto_4']."','".$r1['saldo_4']."','','','".$_POST['mrn']."','".$_POST['cod_atf']."')";
$result=mysql_query($sql) or die (mysql_error());

//4.- Actualizo el estado del MRN
$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='005' WHERE cod_mrn='".$_POST['mrn']."'";
$result=mysql_query($sql) or die (mysql_error());

//4.- Redirecciono
echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&cod=$cod&modo=mrn'</script>";
}

}
elseif($action==UPDATE)
{
$fecha=$_POST['f_contrato'];
$mes=$_POST['duracion'];

$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
$f_termino=dateadd1($fecha,5,$mes,0,0,0,0);

	if ($f_termino>='2014-09-15')
	{
		$f_termino='2014-09-30';
	}
	else
	{
		$f_termino=$f_termino;
	}

//1.- Actualizamos la informacion del PIT
$sql="UPDATE pit_bd_ficha_pit SET n_contrato='".$_POST['n_contrato']."',f_contrato='".$_POST['f_contrato']."',mes='".$_POST['duracion']."',f_termino='$f_termino',n_solicitud='".$_POST['n_solicitud']."' WHERE cod_pit='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Busco la informacion del PIT
$sql="SELECT (pit_bd_ficha_pit.aporte_pdss*0.70) as monto,
(pit_bd_ficha_pit.aporte_pdss*0.30) as saldo
FROM pit_bd_ficha_pit
WHERE pit_bd_ficha_pit.cod_pit='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Busco el componente al que pertenece el POA
$sql="SELECT sys_bd_componente_poa.cod
FROM sys_bd_actividad_poa INNER JOIN sys_bd_subactividad_poa ON sys_bd_actividad_poa.cod = sys_bd_subactividad_poa.relacion
	 INNER JOIN sys_bd_subcomponente_poa ON sys_bd_subcomponente_poa.cod = sys_bd_actividad_poa.relacion
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = sys_bd_subcomponente_poa.relacion
WHERE sys_bd_subactividad_poa.cod='".$_POST['poa']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$componente=$r2['cod'];


//3.- Guardo la informacion de la ATF
$sql="UPDATE clar_atf_pit SET n_atf='".$_POST['n_atf']."',cod_componente='$componente',cod_poa='".$_POST['poa']."',monto_desembolsado='".$r1['monto']."',saldo='".$r1['saldo']."' WHERE cod_atf_pit='".$_POST['codigo_atf']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//4.- Redirecciono
	echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&cod=$codigo&modo=pdn'</script>";
}
elseif($action==UPDATE_PDN)
{
//1.- Busco la informacion
$sql="SELECT (pit_bd_ficha_pdn.total_apoyo*0.70) AS monto_1, 
	(pit_bd_ficha_pdn.at_pdss*0.70) AS monto_2, 
	(pit_bd_ficha_pdn.vg_pdss*0.70) AS monto_3, 
	(pit_bd_ficha_pdn.fer_pdss*0.70) AS monto_4,
(pit_bd_ficha_pdn.total_apoyo*0.30) AS saldo_1, 
	(pit_bd_ficha_pdn.at_pdss*0.30) AS saldo_2, 
	(pit_bd_ficha_pdn.vg_pdss*0.30) AS saldo_3, 
	(pit_bd_ficha_pdn.fer_pdss*0.30) AS saldo_4
FROM pit_bd_ficha_pdn INNER JOIN clar_atf_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
WHERE clar_atf_pdn.cod_atf_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//2.- Obtengo la afectacion poa
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

//3.- Actualizo la informacion
$sql="UPDATE clar_atf_pdn SET cod_poa_1='$poa_1',cod_poa_2='$poa_2',cod_poa_3='$poa_3',cod_poa_4='$poa_4',monto_1='".$r1['monto_2']."',saldo_1='".$r1['saldo_2']."',monto_2='".$r1['monto_3']."',saldo_2='".$r1['saldo_3']."',monto_3='".$r1['monto_4']."',saldo_3='".$r1['saldo_4']."',monto_4='".$r1['monto_1']."',saldo_4='".$r1['saldo_1']."' WHERE cod_atf_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());

//4.- Redirecciono
	echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&cod=$cod&modo=pdn&alert=success_change'</script>";
}
elseif($action==UPDATE_MRN)
{
//1.- Busco la informacion
$sql="SELECT (pit_bd_ficha_mrn.cif_pdss*0.70) AS monto_1, 
	(pit_bd_ficha_mrn.at_pdss*0.70) AS monto_2, 
	(pit_bd_ficha_mrn.vg_pdss*0.70) AS monto_3, 
	(pit_bd_ficha_mrn.ag_pdss*0.70) AS monto_4,
	(pit_bd_ficha_mrn.cif_pdss*0.30) AS saldo_1, 
	(pit_bd_ficha_mrn.at_pdss*0.30) AS saldo_2, 
	(pit_bd_ficha_mrn.vg_pdss*0.30) AS saldo_3, 
	(pit_bd_ficha_mrn.ag_pdss*0.30) AS saldo_4
FROM pit_bd_ficha_mrn INNER JOIN clar_atf_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn.cod_mrn
WHERE clar_atf_mrn.cod_atf_mrn='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Busco los codigos POA segun el Periodo
$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.1.1.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.1.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.2.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '1.3.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$poa_1=$r2['cod'];
$poa_2=$r3['cod'];
$poa_3=$r4['cod'];
$poa_4=$r5['cod'];


//3.-Actualizo la informacion
$sql="UPDATE clar_atf_mrn SET cod_poa_1='$poa_1',desembolso_1='".$r1['monto_1']."',saldo_1='".$r1['saldo_1']."',cod_poa_2='$poa_2',desembolso_2='".$r1['monto_2']."',saldo_2='".$r1['saldo_2']."',cod_poa_3='$poa_3',desembolso_3='".$r1['monto_3']."',saldo_3='".$r1['saldo_3']."',cod_poa_4='$poa_4',desembolso_4='".$r1['monto_4']."',saldo_4='".$r1['saldo_4']."' WHERE cod_atf_mrn='$id'";
$result=mysql_query($sql) or die (mysql_error());

//4.- Redirecciono
	echo "<script>window.location ='n_contrato_pit_mrn.php?SES=$SES&anio=$anio&cod=$cod&modo=mrn&alert=success_change'</script>";
}
elseif($action==ANULA)
{
	//1.- Actualizamos el estado del PIT
	$sql="UPDATE pit_bd_ficha_pit SET cod_estado_iniciativa='000' WHERE cod_pit='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Actualizamos el estado de los pdn que pertenecen a este PIT
	$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='000' WHERE cod_pit='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//3.- Actualizamos el estado de los pgrn que pertenecen a este PIT
	$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='000' WHERE cod_pit='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//4.- Busco los datos del PIT
	$sql="SELECT
	pit_bd_ficha_pit.n_contrato,
	pit_bd_ficha_pit.f_contrato
	FROM
	pit_bd_ficha_pit
	WHERE
	pit_bd_ficha_pit.cod_pit='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r4=mysql_fetch_array($result);	
	
		//4.- Envio un correo electronico
	$sql="SELECT
	sys_bd_personal.n_documento,
	sys_bd_personal.nombre,
	sys_bd_personal.apellido,
	sys_bd_personal.correo,
	sys_bd_dependencia.nombre AS oficina
	FROM
	sys_bd_personal
	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = sys_bd_personal.cod_dependencia
	WHERE
	md5(sys_bd_personal.n_documento)='$SES'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	
	//Generamos un correo Electronico
	$email=$correo_jefe;
	$email_usuario=$r3['correo'];
	$contrato=numeracion($r4['n_contrato'])."-".periodo($r4['f_contrato'])."-".$r3['oficina'];
	$destinatario = $email;
	$asunto = "MENSAJE DEL SISTEMA - SIIR : ANULACION DE CONTRATO PIT";
	$cuerpo = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<head>
	<title>CONSTANCIA DE ANULACION DE CONTRATOS PIT</title>
	</head>
	<body>
	<h1>MENSAJE DEL SISTEMA - SIIR</h1>
	<p>
	El usuario '.$r3['nombre'].' '.$r3['apellido'].' a realizado LA ANULACION del Contrato PIT  N° '.$contrato.' con fecha y hora: '.date("d-m-Y H:i:s").'
	</p>
	<p>Al respecto, el Jefe de la Oficina Local deberá emitir un informe al Director Ejecutivo con copia (c.c) a Administración, Seguimiento y Evaluación, Responsable de Componentes y Responsable de Sistemas, informando sobre las causas de las anulación del contrato en mención.</p>
	<br>
	</body>
	</html>';
	
	//para el envío en formato HTML
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	//dirección del remitente
	$headers .= "From: Administrador del Sistema <info@sierrasur.gob.pe>\r\n";
	
	//dirección de respuesta, si queremos que sea distinta que la del remitente
	$headers .= "Reply-To: jsialer@sierrasur.gob.pe\r\n";
	
	//direcciones que recibián copia
	$headers .= "Cc: jsialer@sierrasur.gob.pe,jvilcherrez@sierrasur.gob.pe,jsalas@sierrasur.gob.pe,ldelgado@sierrasur.gob.pe,opazos@sierrasur.gob.pe,".$email_usuario."\r\n";
	
	//direcciones que recibirán copia oculta
	$headers .= "Bcc:opazos@sierrasur.gob.pe\r\n";
	
	mail($destinatario,$asunto,$cuerpo,$headers);
	//Fin del envio del correo electronicoo
	
	//5.- Redirecciono
	echo "<script>window.location ='contrato_pit_mrn.php?SES=$SES&anio=$anio&modo=anula&alert=success_change'</script>";
}



?>