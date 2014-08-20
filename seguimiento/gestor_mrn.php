<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//Obtengo la oficina local
$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Busco el correo del Jefe de la Oficina Local
$sql="SELECT
sys_bd_personal.correo
FROM
sys_bd_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE
sys_bd_dependencia.cod_dependencia='".$r1['cod_dependencia']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$correo_jefe=$r1['correo'];

if ($action==UPDATE_PRIMERO)
{
//1.- Actualizamos
$sql="UPDATE pit_bd_ficha_mrn SET f_presentacion='".$_POST['f_presentacion']."',mes='".$_POST['mes']."',n_cuenta='".$_POST['n_cuenta']."',cod_ifi='".$_POST['ifi']."',n_voucher='".$_POST['n_voucher']."',monto_organizacion='".$_POST['monto_org']."' WHERE cod_mrn='$id'";
$result=mysql_query($sql) or die (mysql_error());

if ($_POST['codigo']==002)
{
$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='005' WHERE cod_mrn='$id'";
$result=mysql_query($sql) or die (mysql_error());
}

//2.- redirecciono
echo "<script>window.location ='mrn.php?SES=$SES&anio=$anio&modo=edit'</script>";

}
elseif($action==UPDATE_SEGUNDO)
{
//1.- Actualizamos
$sql="UPDATE pit_bd_ficha_mrn SET f_presentacion_2='".$_POST['f_presentacion']."',n_voucher_2='".$_POST['n_voucher']."',monto_organizacion_2='".$_POST['monto_org']."' WHERE cod_mrn='$id'";
$result=mysql_query($sql) or die (mysql_error());
//2.- Redirecciono
echo "<script>window.location ='mrn.php?SES=$SES&anio=$anio&modo=imprime'</script>";
}
elseif($action==DELETE)
{
//1.- Actualizamos
$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='000' WHERE cod_mrn='$id'";
$result=mysql_query($sql) or die (mysql_error());

//3.- Generamos un correo de aviso
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
	$r2=mysql_fetch_array($result);
	
	$sql="SELECT pit_bd_ficha_mrn.sector, 
	org_ficha_organizacion.nombre
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_mrn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r3=mysql_fetch_array($result);
	
	//Generamos un correo Electronico
	$email=$correo_jefe;
	$email_usuario=$r2['correo'];
	$destinatario = $email;
	$asunto = "MENSAJE DEL SISTEMA - SIIR : BAJA DE INICIATIVA DE PLAN DE GESTION DE RECURSOS NATURALES";
	$cuerpo = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<head>
	<title>AVISO DE BAJA DE INICIATIVA DE PLAN DE GESTION DE RECURSOS NATURALES</title>
	</head>
	<body>
	<h1>MENSAJE DEL SISTEMA - SIIR:</h1>
	<p>
	El usuario '.$r2['nombre'].' '.$r2['apellido'].' a DADO DE BAJA a la INICIATIVA DE PLAN DE GESTION DE RECURSOS NATURALES perteneciente a la ORGANIZACION '.$r3['nombre'].' '.$r3['sector'].', de la Oficina Local de '.$r2['oficina'].', en la fecha y hora siguientes: '.date("d-m-Y H:i:s").'
	</p>
	<p>Al respecto, el Jefe de la Oficina Local debera emitir un informe al Director Ejecutivo con copia (c.c) a Administración, Seguimiento y Evaluación, Responsable de Componentes y Responsable de Sistemas, informando sobre las causas de la baja de la iniciativa en mención.</p>
	<br>
	</body>
	</html>';
	
	//para el envío en formato HTML
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	//dirección del remitente
	$headers .= "From: Administrador del Sistema <opazos@sierrasur.gob.pe>\r\n";
	
	//dirección de respuesta, si queremos que sea distinta que la del remitente
	$headers .= "Reply-To: jsialer@sierrasur.gob.pe\r\n";
	
	//direcciones que recibián copia
	$headers .= "Cc: jsialer@sierrasur.gob.pe,jvilcherrez@sierrasur.gob.pe,jsalas@sierrasur.gob.pe,ldelgado@sierrasur.gob.pe,opazos@sierrasur.gob.pe,".$email_usuario."\r\n";
	
	//direcciones que recibirán copia oculta
	$headers .= "Bcc:opazos@sierrasur.gob.pe\r\n";
	
	mail($destinatario,$asunto,$cuerpo,$headers);
	//Fin del envio del correo electronico

	echo "<script>window.location ='mrn.php?SES=$SES&anio=$anio&modo=delete'</script>";	


}
elseif($action==UPDATE_ESTADO)
{
	foreach($estado as $cad=>$a1)
	{
		$sql="UPDATE pit_bd_ficha_mrn SET cod_estado_iniciativa='".$_POST['estado'][$cad]."' WHERE cod_mrn='$cad'";
		$result=mysql_query($sql) or die (mysql_error());
	}
	//2.- Enviamos un aviso
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
	$r2=mysql_fetch_array($result);
	
	
	//Generamos un correo Electronico
	$email=$correo_jefe;
	$email_usuario=$r2['correo'];
	$destinatario = $email;
	$asunto = "MENSAJE DEL SISTEMA - SIIR : ACTUALIZACION DE ESTADOS DE PLANES DE GESTION DE RECURSOS NATURALES";
	$cuerpo = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<head>
	<title>AVISO DE ACTUALIZACION DE ESTADOS DE PLANES DE GESTION DE RECURSOS NATURALES</title>
	</head>
	<body>
	<h1>MENSAJE DEL SISTEMA - SIIR:</h1>
	<p>
	El usuario '.$r2['nombre'].' '.$r2['apellido'].' a REALIZADO UNA ACTUALIZACION EN BLOQUE DE LOS ESTADOS SITUACIONALES DE LOS PLANES DE GESTION DE RECURSOS NATURALES, de la Oficina Local de '.$r2['oficina'].', en la fecha y hora siguientes: '.date("d-m-Y H:i:s").'
	</p>
	<p>Al respecto, el Jefe de la Oficina Local debera emitir un informe y consistencia al Director Ejecutivo con copia (c.c) a Administración, Seguimiento y Evaluación, Responsable de Componentes y Responsable de Sistemas, justificando los cambios realizados en los estados situacionales.</p>
	<br>
	</body>
	</html>';
	
	//para el envío en formato HTML
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	//dirección del remitente
	$headers .= "From: Administrador del Sistema <opazos@sierrasur.gob.pe>\r\n";
	
	//dirección de respuesta, si queremos que sea distinta que la del remitente
	$headers .= "Reply-To: jsialer@sierrasur.gob.pe\r\n";
	
	//direcciones que recibián copia
	$headers .= "Cc: jsialer@sierrasur.gob.pe,jvilcherrez@sierrasur.gob.pe,jsalas@sierrasur.gob.pe,ldelgado@sierrasur.gob.pe,opazos@sierrasur.gob.pe,".$email_usuario."\r\n";
	
	//direcciones que recibirán copia oculta
	$headers .= "Bcc:opazos@sierrasur.gob.pe\r\n";
	
	mail($destinatario,$asunto,$cuerpo,$headers);
	//Fin del envio del correo electronico

	echo "<script>window.location ='mrn.php?SES=$SES&anio=$anio&modo=edit'</script>";		
	
}

?>