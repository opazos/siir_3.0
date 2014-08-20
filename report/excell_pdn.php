<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Reporte_pdn.xls");
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
      <td>TIPO DOCUMENTO</td>
      <td>N° DOCUMENTO</td>
      <td>ORGANIZACIÓN</td>
      <td>TIPO ORGANIZACIÓN</td>
      <td>DENOMINACION</td>
      <td>LINEA DE NEGOCIO</td>
      <td>DEPARTAMENTO</td>
      <td>PROVINCIA</td>
      <td>DISTRITO</td>
      <td>N° PARTICIPANTES DEL PDN</td>
      <td>IFI</td>
      <td>N° CUENTA</td>
      <td>APORTE SOLICITADO PDSS (S/.)</td>
      <td>APORTE SOLICITADO ORG (S/.)</td>
      <td>OFICINA</td>
    </tr>
<?
$num=0;

if ($row['cod_dependencia']==001)
{
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS fam,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
sys_bd_dependencia.nombre AS oficina,
pit_bd_ficha_pdn.denominacion,
sys_bd_linea_pdn.descripcion AS linea,
(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss,
(pit_bd_ficha_pdn.fer_org+
pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org) AS aporte_org,
sys_bd_ifi.descripcion AS banco,
pit_bd_ficha_pdn.n_cuenta
FROM
pit_bd_ficha_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 000
GROUP BY
sys_bd_tipo_doc.descripcion,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
sys_bd_tipo_org.descripcion,
sys_bd_departamento.nombre,
sys_bd_provincia.nombre,
sys_bd_distrito.nombre,
sys_bd_dependencia.nombre,
pit_bd_ficha_pdn.denominacion,
sys_bd_linea_pdn.descripcion
ORDER BY
org_ficha_organizacion.cod_dependencia ASC,
org_ficha_organizacion.n_documento ASC";
}
else
{
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS fam,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
sys_bd_dependencia.nombre AS oficina,
pit_bd_ficha_pdn.denominacion,
sys_bd_linea_pdn.descripcion AS linea,
(pit_bd_ficha_pdn.total_apoyo+
pit_bd_ficha_pdn.at_pdss+
pit_bd_ficha_pdn.vg_pdss+
pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss,
(pit_bd_ficha_pdn.fer_org+
pit_bd_ficha_pdn.at_org+
pit_bd_ficha_pdn.vg_org) AS aporte_org,
pit_bd_ficha_pdn.n_cuenta,
sys_bd_ifi.descripcion AS banco
FROM
pit_bd_ficha_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
WHERE
pit_bd_ficha_pdn.cod_estado_iniciativa <> 003 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa <> 000 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
GROUP BY
sys_bd_tipo_doc.descripcion,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
sys_bd_tipo_org.descripcion,
sys_bd_departamento.nombre,
sys_bd_provincia.nombre,
sys_bd_distrito.nombre,
sys_bd_dependencia.nombre,
pit_bd_ficha_pdn.denominacion,
sys_bd_linea_pdn.descripcion
ORDER BY
org_ficha_organizacion.cod_dependencia ASC,
org_ficha_organizacion.n_documento ASC";
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
      <td><? echo $r1['tipo_org'];?></td>
      <td><? echo $r1['denominacion'];?></td>
      <td><? echo $r1['linea'];?></td>
      <td><? echo $r1['departamento'];?></td>
      <td><? echo $r1['provincia'];?></td>
      <td><? echo $r1['distrito'];?></td>
      <td><? echo $r1['fam'];?></td>
      <td><?php  echo $r1['banco'];?></td>
      <td>Nº <?php  echo $r1['n_cuenta'];?></td>
      <td><? echo $r1['aporte_pdss'];?></td>
      <td><? echo $r1['aporte_org'];?></td>
      <td><? echo $r1['oficina'];?></td>
    </tr>
<?
}
?>	
  </table>

  </body>
</html>
