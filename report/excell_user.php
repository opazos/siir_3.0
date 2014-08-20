<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Reporte_usuario.xls");
header("Pragma: no-cache");

//Busco la oficina Local
$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);
?>
<!DOCTYPE html><html>
  <head>
    <meta content="text/html;charset=UTF-8" http-equiv="Content-Type">
    <meta charset="utf-8">
    <title>::Vista Preeliminar::</title>
  </head>
  <body>
  <table width="100%" border="1" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td>N°</td>
      <td>TIPO DOCUMENTO </td>
      <td>N° DOCUMENTO </td>
      <td>NOMBRE</td>
      <td>PATERNO</td>
      <td>MATERNO</td>
      <td>SEXO</td>
      <td>FECHA DE NACIMIENTO </td>
      <td>TITULAR/CONYUGE</td>
      <td>SOCIO/NO SOCIO</td>
      <td>N° HIJOS MENORES DE 5 AÑO (SOLO TITULAR) </td>
      <td>UBIGEO</td>
      <td>DIRECCION</td>
      <td>N° DOCUMENTO ORGANIZACION </td>
      <td>NOMBRE DE ORGANIZACION</td>
      <td>OFICINA LOCAL </td>
    </tr>
<?
$num=0;

if ($row['cod_dependencia']==001)
{
$sql="SELECT
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_usuario.f_nacimiento,
org_ficha_usuario.sexo,
org_ficha_usuario.ubigeo,
org_ficha_usuario.direccion,
org_ficha_usuario.titular,
org_ficha_usuario.socio,
org_ficha_usuario.n_hijo,
sys_bd_dependencia.nombre AS oficina,
org_ficha_usuario.n_documento_org,
org_ficha_organizacion.nombre AS org
FROM
org_ficha_usuario
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
ORDER BY
org_ficha_organizacion.cod_dependencia ASC,
org_ficha_usuario.n_documento ASC";
}
else
{
$sql="SELECT
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_usuario.f_nacimiento,
org_ficha_usuario.sexo,
org_ficha_usuario.ubigeo,
org_ficha_usuario.direccion,
org_ficha_usuario.titular,
org_ficha_usuario.socio,
org_ficha_usuario.n_hijo,
sys_bd_dependencia.nombre AS oficina,
org_ficha_usuario.n_documento_org,
org_ficha_organizacion.nombre AS org
FROM
org_ficha_usuario
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY
org_ficha_organizacion.cod_dependencia ASC,
org_ficha_usuario.n_documento ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
$num++
?>	
    <tr>
      <td><? echo $num;?></td>
      <td><? echo $r1['tipo_doc'];?></td>
      <td><? echo $r1['n_documento'];?></td>
      <td><? echo $r1['nombre'];?></td>
      <td><? echo $r1['paterno'];?></td>
      <td><? echo $r1['materno'];?></td>
      <td><? if ($r1['sexo']==0) echo "F"; else echo "M";?></td>
      <td><? echo $r1['f_nacimiento'];?></td>
      <td><? if ($r1['titular']==1) echo "TITULAR"; else echo "CONYUGE";?></td>
      <td><? if ($r1['socio']==1) echo "SOCIO"; else echo "NO SOCIO";?></td>
      <td><? echo $r1['n_hijo'];?></td>
      <td><? echo $r1['ubigeo'];?></td>
      <td><? echo $r1['direccion'];?></td>
      <td><? echo $r1['n_documento_org'];?></td>
      <td><? echo $r1['org'];?></td>
      <td><? echo $r1['oficina'];?></td>
    </tr>
<?
}
?>	
  </table>

  </body>
</html>