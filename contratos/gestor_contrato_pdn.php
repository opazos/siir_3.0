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
if ($_POST['pdn']==NULL)
{
echo "<script>window.location ='n_contrato_pdn.php?SES=$SES&anio=$anio&error=vacio'</script>";
}
else
{
//1.- Guardo la info de numeracion
$sql="UPDATE sys_bd_numera_dependencia SET n_contrato_iniciativa='".$_POST['n_contrato']."',n_atf_iniciativa='".$_POST['n_atf']."',n_solicitud_iniciativa='".$_POST['n_solicitud']."' WHERE cod='".$_POST['cod_numera']."'";
$result=mysql_query($sql) or die (mysql_error());

//1.2.- Calculo la fecha de termino del contrato
$sql="SELECT mes FROM pit_bd_ficha_pdn WHERE cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);


$fecha=$_POST['f_contrato'];
$mes=$r3['mes'];

$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
$f_termino_a=dateadd1($fecha,5,$mes,0,0,0,0);

if ($f_termino_a<"2014-09-15")
{
	$f_termino=$f_termino_a;
}
else
{
	$f_termino="2014-09-15";
}


//2.-Actualizo el estado del plan de negocio
$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='005',n_contrato='".$_POST['n_contrato']."',f_contrato='".$_POST['f_contrato']."',f_termino='$f_termino',fuente_fida='".$_POST['fte_fida']."',fuente_ro='".$_POST['fte_ro']."' WHERE cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());

//3.- Realizo los calculos
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


//4.- Busco los codigos POA
$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.1.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.1.2.2.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.cod, 
	sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.codigo LIKE '2.3.2.1.' AND
sys_bd_subactividad_poa.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r7=mysql_fetch_array($result);

$poa_1=$r4['cod'];
$poa_2=$r5['cod'];
$poa_3=$r6['cod'];
$poa_4=$r7['cod'];

//5.-ingreso el atf
$sql="INSERT INTO clar_atf_pdn VALUES('','4','','','".$_POST['n_solicitud']."','".$_POST['n_atf']."','2','$poa_1','$poa_2','$poa_3','$poa_4','".$r1['monto_1']."','".$r1['saldo_1']."','".$r1['monto_2']."','".$r1['saldo_2']."','".$r1['monto_3']."','".$r1['saldo_3']."','".$r1['monto_4']."','".$r1['saldo_4']."','".$_POST['pdn']."','')";
$result=mysql_query($sql) or die (mysql_error());

//5.- obtengo el codigo
$sql="SELECT * FROM clar_atf_pdn ORDER BY cod_atf_pdn DESC LIMIT 0,1";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$codigo=$r2['cod_atf_pdn'];

//6.- Redirecciono
echo "<script>window.location ='../print/print_contrato_pdn.php?SES=$SES&anio=$anio&cod=$codigo'</script>";

}

}
elseif($action==UPDATE)
{
//1.-Busco la info del pdn
$sql="SELECT (pit_bd_ficha_pdn.at_pdss*0.70) as monto_1, 
	(pit_bd_ficha_pdn.vg_pdss*0.70) as monto_2, 
	(pit_bd_ficha_pdn.fer_pdss*0.70) as monto_3, 
	(pit_bd_ficha_pdn.total_apoyo*0.70) as monto_4,
	(pit_bd_ficha_pdn.at_pdss*0.30) as saldo_1, 
	(pit_bd_ficha_pdn.vg_pdss*0.30) as saldo_2, 
	(pit_bd_ficha_pdn.fer_pdss*0.30) as saldo_3, 
	(pit_bd_ficha_pdn.total_apoyo*0.30) as saldo_4,
	pit_bd_ficha_pdn.mes
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

//1.2.- Calculo la fecha de termino del contrato
$fecha=$_POST['f_contrato'];
$mes=$r1['mes'];

$fecha_actualizada = dateadd1($fecha,7,0,0,0,0,0); // suma 7 dias a la fecha dada
$f_termino_a=dateadd1($fecha,5,$mes,0,0,0,0);

if ($f_termino_a<"2014-09-15")
{
	$f_termino=$f_termino_a;
}
else
{
	$f_termino="2014-09-15";
}

//2.- Actualizo la fecha
$sql="UPDATE pit_bd_ficha_pdn SET f_inicio='".$_POST['f_contrato']."',f_termino='$f_termino',n_contrato='".$_POST['n_contrato']."',f_contrato='".$_POST['f_contrato']."',fuente_fida='".$_POST['fte_fida']."',fuente_ro='".$_POST['fte_ro']."' WHERE cod_pdn='".$_POST['pdn']."'";
$result=mysql_query($sql) or die (mysql_error());

//3.- Busco los codigos POA
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

//4.- Actualizo mi información
$sql="UPDATE clar_atf_pdn SET n_solicitud='".$_POST['n_solicitud']."',n_atf='".$_POST['n_atf']."',cod_poa_1='$poa_1',cod_poa_2='$poa_2',cod_poa_3='$poa_3',cod_poa_4='$poa_4',monto_1='".$r1['monto_1']."',saldo_1='".$r1['saldo_1']."',monto_2='".$r1['monto_2']."',saldo_2='".$r1['saldo_2']."',monto_3='".$r1['monto_3']."',saldo_3='".$r1['saldo_3']."',monto_4='".$r1['monto_4']."',saldo_4='".$r1['saldo_4']."' WHERE cod_atf_pdn='".$_POST['codigo']."'";
$result=mysql_query($sql) or die (mysql_error());

$codigo=$_POST['codigo'];

//6.- Redirecciono
echo "<script>window.location ='../print/print_contrato_pdn.php?SES=$SES&anio=$anio&cod=$codigo'</script>";
}
elseif($action==ANULA)
{
	//1.- Actualizamos en estado del PDN
	$sql="UPDATE pit_bd_ficha_pdn SET cod_estado_iniciativa='000' WHERE cod_pdn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	
	//2.- Busco los datos del PDN
	$sql="SELECT pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato
	FROM pit_bd_ficha_pdn
	WHERE pit_bd_ficha_pdn.cod_pdn='$id'";
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
	
	//5.- Generamos un correo electronico
	$email=$correo_jefe;
	$email_usuario=$r3['correo'];
	$contrato=numeracion($r4['n_contrato'])."-".periodo($r4['f_contrato'])."-".$r3['oficina'];
	$destinatario = $email;
	$asunto = "MENSAJE DEL SISTEMA - SIIR : ANULACION DE CONTRATO DE PLAN DE NEGOCIO";
	$cuerpo = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<head>
	<title>CONSTANCIA DE ANULACION DE CONTRATOS DE PLAN DE NEGOCIO</title>
	</head>
	<body>
	<h1>MENSAJE DEL SISTEMA - SIIR</h1>
	<p>
	El usuario '.$r3['nombre'].' '.$r3['apellido'].' a realizado LA ANULACION del Contrato PDN  N° '.$contrato.' con fecha y hora: '.date("d-m-Y H:i:s").'
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
	echo "<script>window.location ='contrato_pdn.php?SES=$SES&anio=$anio&modo=anula&alert=success_change'</script>";
}

?>