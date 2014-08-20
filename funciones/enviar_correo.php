<?php
require("sesion.php");
include("funciones.php");
conectarte();

//1.- Buscamos la Información de la persona que esta accediendo a la info
$sql="SELECT
sys_bd_dependencia.nombre AS oficina,
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_personal.correo
FROM
sys_bd_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.cod_dependencia = sys_bd_dependencia.cod_dependencia
WHERE
md5(sys_bd_personal.n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//2.- Genero un Mensaje de Correo Electronico
$email=$row['correo'];
$destinatario = $email;

$asunto = "MENSAJE DEL SISTEMA - SIIR : AVISO DE MODIFICACIÓN DE INFORMACIÓN DEL SISTEMA";

$cuerpo = '
<h1>MENSAJE DEL SISTEMA - SIIR</h1>
<p>El usuario '.$row['nombre'].' '.$row['apellido'].' a realizado una modificación de información del Sistema, Advertencia: Esta información es la base de un contrato, por tanto su modificación podria modificar los montos y datos que estan presentes en los contratos PIT.</p>
<p>NOTA: Este mensaje es para conocimiento y regularización.</p>';

//para el envío en formato HTML
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
//dirección del remitente
$headers .= "From: Administrador del Sistema <info@sierrasur.gob.pe>\r\n";

//dirección de respuesta, si queremos que sea distinta que la del remitente
$headers .= "Reply-To: opazos@sierrasur.gob.pe\r\n";

//ruta del mensaje desde origen a destino
//$headers .= "Return-path: holahola@desarrolloweb.com\r\n";

//direcciones que recibián copia
//$headers .= "Cc: jsalas@sierrasur.gob.pe,jvilcherrez@sierrasur.gob.pe\r\n";

//direcciones que recibirán copia oculta
//$headers .= "Bcc: ldelgado@sierrasur.gob.pe,opazos@sierrasur.gob.pe\r\n";

mail($destinatario,$asunto,$cuerpo,$headers);
//Fin del envio del correo electronico



?>