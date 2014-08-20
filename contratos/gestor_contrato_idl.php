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
if ($_POST['org']==NULL)
{
echo "<script>window.location ='n_contrato_idl.php?SES=$SES&anio=$anio&error=vacio'</script>";
}
elseif ($_POST['desembolso']==NULL)
{
echo "<script>window.location ='n_contrato_idl.php?SES=$SES&anio=$anio&error=vacio'</script>";
}
else
{
$primer_pago=$_POST['desembolso'];
$segundo_pago=100-$primer_pago;


//1.- Actualizamos la numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_iniciativa='".$_POST['n_contrato']."',n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Actualizamos la ficha del IDL
$sql="UPDATE pit_bd_ficha_idl SET n_contrato='".$_POST['n_contrato']."',f_contrato='".$_POST['f_contrato']."',n_solicitud='".$_POST['n_solicitud']."',fuente_fida='".$_POST['fida']."',fuente_ro='".$_POST['ro']."',primer_pago='$primer_pago',segundo_pago='$segundo_pago',cod_estado_iniciativa='005' WHERE cod_ficha_idl='".$_POST['org']."'";
$result=mysql_query($sql) or die (mysql_error());

//3.- Obtengo los montos
$sql="SELECT ((pit_bd_ficha_idl.aporte_pdss*$primer_pago)/100) AS monto,
((pit_bd_ficha_idl.aporte_pdss*$segundo_pago)/100) AS saldo
FROM pit_bd_ficha_idl
WHERE pit_bd_ficha_idl.cod_ficha_idl='".$_POST['org']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//4.- Obtengo el codigo POA
$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.3.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$poa=$r2['cod'];

//5.- ingreso la informacion de la atf
$sql="INSERT INTO clar_atf_idl VALUES('','1','".$_POST['n_atf']."','2','$poa','".$r1['monto']."','".$r1['saldo']."','".$_POST['org']."')";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['org'];

//5.- Redirecciono
echo "<script>window.location ='../print/print_contrato_idl.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
}
elseif($action==UPDATE)
{
$primer_pago=$_POST['desembolso'];
$segundo_pago=100-$primer_pago;

//1.- Actualizar IDL
$sql="UPDATE pit_bd_ficha_idl SET n_contrato='".$_POST['n_contrato']."',f_contrato='".$_POST['f_contrato']."',n_solicitud='".$_POST['n_solicitud']."',fuente_fida='".$_POST['fida']."',fuente_ro='".$_POST['ro']."',primer_pago='$primer_pago',segundo_pago='$segundo_pago' WHERE cod_ficha_idl='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//2.- Obtengo los montos
$sql="SELECT ((pit_bd_ficha_idl.aporte_pdss*$primer_pago)/100) AS monto,
((pit_bd_ficha_idl.aporte_pdss*$segundo_pago)/100) AS saldo
FROM pit_bd_ficha_idl
WHERE pit_bd_ficha_idl.cod_ficha_idl='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//3.- Busco el codigo POA
$sql="SELECT sys_bd_subactividad_poa.cod
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.3.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$poa=$r2['cod'];

//4.- Actualizo la ATF
$sql="UPDATE clar_atf_idl SET n_atf='".$_POST['n_atf']."',cod_componente='2',cod_poa='$poa',monto_desembolsado='".$r1['monto']."',saldo='".$r1['saldo']."' WHERE cod_atf_idl='".$_POST['codigo_atf']."'";
$result=mysql_query($sql) or die (mysql_error());

//5.- Redirecciono
echo "<script>window.location ='../print/print_contrato_idl.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==ANULA)
{
//1.- Actualizo el estado de la iniciativa
$sql="UPDATE pit_bd_ficha_idl SET cod_estado_iniciativa='000' WHERE cod_ficha_idl='$id'";
$result=mysql_query($sql) or die (mysql_error());

//2.- Busco los datos de la IDL
$sql="SELECT pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato
FROM pit_bd_ficha_idl
WHERE pit_bd_ficha_idl.cod_ficha_idl='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

	//3.- Envio un correo electronico
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

	//4.- Generamos un correo electronico
	$email=$correo_jefe;
	$email_usuario=$r3['correo'];
	$contrato=numeracion($r4['n_contrato'])."-".periodo($r4['f_contrato'])."-".$r3['oficina'];
	$destinatario = $email;
	$asunto = "MENSAJE DEL SISTEMA - SIIR : ANULACION DE CONTRATO DE INVERSION PARA EL DESARROLLO LOCAL";
	$cuerpo = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<head>
	<title>CONSTANCIA DE ANULACION DE CONTRATOS DE INVERSION PARA EL DESARROLLO LOCAL</title>
	</head>
	<body>
	<h1>MENSAJE DEL SISTEMA - SIIR</h1>
	<p>
	El usuario '.$r3['nombre'].' '.$r3['apellido'].' a realizado LA ANULACION del Contrato IDL  N° '.$contrato.' con fecha y hora: '.date("d-m-Y H:i:s").'
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

//Redirecciono
echo "<script>window.location ='contrato_idl.php?SES=$SES&anio=$anio&modo=anula&alert=success_change'</script>";
}

?>