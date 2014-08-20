<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.nombre, 
	clar_bd_evento_clar.f_evento, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_distrito.nombre AS distrito, 
	clar_bd_evento_clar.lugar
FROM sys_bd_dependencia INNER JOIN clar_bd_evento_clar ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
WHERE clar_bd_evento_clar.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>::Vista Preeliminar::</title>
<!-- cargamos el estilo de la pagina -->
<link href="../stylesheets/print.css" rel="stylesheet" type="text/css">
<style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
  @page 
  {
  size: A4 landscape; 
  }   
</style>
<!-- Fin -->
</head>
<body>




<!-- PIT -->
<? include("encabezado.php");?>

<div class="capa txt_titulo">OLP: <? echo $row['oficina'];?></div>
<br/>
<div class="capa txt_titulo centrado">CUADRO DE RESULTADOS OBTENIDOS EN EL EVENTO CLAR Nº <? echo numeracion($row['cod_clar']);?> "<? echo $row['nombre'];?>" <br/> INICIATIVAS QUE ACCEDEN A UN SEGUNDO DESEMBOLSO</div>
<div class="capa"><br/></div>


<table cellpadding="1" cellspacing="1" border="1" width="90%" align="center" class="mini">
<thead>
	<tr>
		<th colspan="11">PLANES DE INVERSION TERRITORIAL</th>
	</tr>
	<tr>
		<th>Nº</th>
		<th>TIPO DE DOCUMENTO</th>
		<th>NOMBRE DE LA ORGANIZACION</th>
		<th>DISTRITO</th>
		<th>Nº CUENTA</th>
		<th>MONTO TOTAL (S/.)</th>
		<th>MONTO A COF. PDSS II (S/.)</th>
		<th>MONTO A COF. ORG (S/.)</th>
		<th>PUNTAJE TOTAL</th>		
		<th>ESTADO</th>
		<th>MONTO A DESEMBOLSAR (30%) (S/.)</th>
	</tr>
</thead>

<tbody>
<?
$sql="SELECT org_ficha_taz.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_taz.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pit.n_cuenta, 
	(pit_bd_ficha_pit.aporte_pdss*0.30) AS aporte_30, 
	pit_bd_ficha_pit.calificacion_2 AS calificacion, 
	sys_bd_distrito.nombre AS distrito, 
	(pit_bd_ficha_pit.aporte_pdss+ 
	pit_bd_ficha_pit.aporte_org) AS aporte_total, 
	pit_bd_ficha_pit.aporte_pdss, 
	pit_bd_ficha_pit.aporte_org
FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit_2 ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE clar_bd_ficha_pit_2.cod_clar='$cod'
ORDER BY calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
$total_pit=mysql_num_rows($result);
while($f1=mysql_fetch_array($result))
{
?>
<tr>
	<td class="centrado"><? echo $f1['n_documento'];?></td>
	<td class="centrado"><? echo $f1['tipo_doc'];?></td>
	<td><? echo $f1['nombre'];?></td>
	<td class="centrado"><? echo $f1['distrito'];?></td>
	<td class="centrado"><? echo $f1['n_cuenta'];?></td>
	<td class="derecha"><? echo number_format($f1['aporte_total'],2);?></td>
	<td class="derecha"><? echo number_format($f1['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($f1['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($f1['calificacion']);?></td>
	<td class="centrado"><? if ($f1['calificacion']<70) echo "DESAPROBADO"; else echo "APROBADO"?></td>
	<td class="derecha"><? if ($f1['calificacion']>=70) echo number_format($f1['aporte_30'],2);?></td>
</tr>
<?
}
$sql="SELECT SUM(pit_bd_ficha_pit.aporte_pdss+ 
	pit_bd_ficha_pit.aporte_org) AS aporte_total, 
	SUM(pit_bd_ficha_pit.aporte_pdss) AS aporte_pdss, 
	SUM(pit_bd_ficha_pit.aporte_org) AS aporte_org, 
	SUM(pit_bd_ficha_pit.aporte_pdss*0.30) AS aporte_30
FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit_2 ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
WHERE pit_bd_ficha_pit.calificacion_2>=70 AND
clar_bd_ficha_pit_2.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
?>
<tr class="txt_titulo">
	<td colspan="5">TOTALES</td>
	<td class="derecha"><? echo number_format($r1['aporte_total'],2);?></td>
	<td class="derecha"><? echo number_format($r1['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($r1['aporte_org'],2);?></td>
	<td  class="centrado">-</td>
	<td  class="centrado">-</td>	
	<td class="derecha"><? echo number_format($r1['aporte_30'],2);?></td>
</tr>
<?
if($total_pit==0)
{
?>
<tr>
	<td colspan="11" class="centrado gran_titulo">SIN INFORMACION. NO SE PRESENTARON INICIATIVAS DE ESTE TIPO</td>
</tr>
<?
}
?>
</tbody>
</table>
<br>
<div class="capa mini txt_titulo">Lugar y Fecha : <? echo $row['distrito'];?>, <?php  echo traducefecha($row['f_evento']);?></div>
<H1 class=SaltoDePagina> </H1>









<!-- PGRN -->
<? include("encabezado.php");?>
<div class="capa txt_titulo">OLP: <? echo $row['oficina'];?></div>
<br/>
<div class="capa txt_titulo centrado">CUADRO DE RESULTADOS OBTENIDOS EN EL EVENTO CLAR Nº <? echo numeracion($row['cod_clar']);?> "<? echo $row['nombre'];?>" <br/> INICIATIVAS QUE ACCEDEN A UN SEGUNDO DESEMBOLSO</div>
<div class="capa"><br/></div>
<table cellpadding="1" cellspacing="1" border="1" width="90%" align="center" class="mini">
<thead>
	<tr>
		<th colspan="11">PLANES DE GESTION DE RECURSOS NATURALES</th>
	</tr>
	<tr>
		<th>Nº</th>
		<th>TIPO DE DOCUMENTO</th>
		<th>NOMBRE DE LA ORGANIZACION</th>
		<th>DISTRITO</th>
		<th>Nº CUENTA</th>
		<th>MONTO TOTAL (S/.)</th>
		<th>MONTO A COF. PDSS II (S/.)</th>
		<th>MONTO A COF. ORG (S/.)</th>
		<th>PUNTAJE TOTAL</th>		
		<th>ESTADO</th>
		<th>MONTO A DESEMBOLSAR (30%) (S/.)</th>
	</tr>
</thead>

<tbody>
<?
	$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_mrn.n_cuenta, 
	((pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss)*0.30) AS aporte_30, 
	pit_bd_ficha_mrn.calificacion_2 AS calificacion, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.vg_org+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_total, 
	sys_bd_distrito.nombre AS distrito
FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn_2 ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN sys_bd_distrito ON org_ficha_organizacion.cod_dist = sys_bd_distrito.cod
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_bd_ficha_mrn_2.cod_clar='$cod'
ORDER BY calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
$total_pgrn=mysql_num_rows($result);
while($f2=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f2['n_documento'];?></td>
		<td class="centrado"><? echo $f2['tipo_doc'];?></td>
		<td><? echo $f2['nombre'];?></td>
		<td class="centrado"><? echo $f2['distrito'];?></td>
		<td class="centrado"><? echo $f2['n_cuenta'];?></td>
		<td class="derecha"><? echo number_format($f2['aporte_total'],2);?></td>
		<td class="derecha"><? echo number_format($f2['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f2['aporte_org'],2);?></td>
		<td class="derecha"><? echo number_format($f2['calificacion']);?></td>
		<td class="centrado"><? if ($f2['calificacion']<70) echo "DESAPROBADO"; else echo "APROBADO"?></td>
		<td class="derecha"><? if ($f2['calificacion']>=70) echo number_format($f2['aporte_30'],2);?></td>
	</tr>
<?
}
$sql="SELECT SUM(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	SUM(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	SUM((pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss)*0.30) AS aporte_30, 
	SUM(pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.at_org+
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.vg_org+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_total
FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn_2 ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
WHERE pit_bd_ficha_mrn.calificacion_2>=70 AND
clar_bd_ficha_mrn_2.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);
?>
<tr class="txt_titulo">
	<td colspan="5">TOTALES</td>
	<td class="derecha"><? echo number_format($r2['aporte_total'],2);?></td>
	<td class="derecha"><? echo number_format($r2['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($r2['aporte_org'],2);?></td>
	<td  class="centrado">-</td>
	<td  class="centrado">-</td>	
	<td class="derecha"><? echo number_format($r2['aporte_30'],2);?></td>
</tr>
<?
if($total_pgrn==0)
{
?>
<tr>
	<td colspan="11" class="centrado gran_titulo">SIN INFORMACION. NO SE PRESENTARON INICIATIVAS DE ESTE TIPO</td>
</tr>
<?
}
?>
</tbody>
</table>
<br>
<div class="capa mini txt_titulo">Lugar y Fecha : <? echo $row['distrito'];?>, <?php  echo traducefecha($row['f_evento']);?></div>
<H1 class=SaltoDePagina> </H1>











<? include("encabezado.php");?>
<div class="capa txt_titulo">OLP: <? echo $row['oficina'];?></div>
<br/>
<div class="capa txt_titulo centrado">CUADRO DE RESULTADOS OBTENIDOS EN EL EVENTO CLAR Nº <? echo numeracion($row['cod_clar']);?> "<? echo $row['nombre'];?>" <br/> INICIATIVAS QUE ACCEDEN A UN SEGUNDO DESEMBOLSO</div>
<div class="capa"><br/></div>

<table cellpadding="1" cellspacing="1" border="1" width="90%" align="center" class="mini">
<thead>
	<tr>
		<th colspan="11">PLANES DE NEGOCIO</th>
	</tr>
	<tr>
		<th>Nº</th>
		<th>TIPO DE DOCUMENTO</th>
		<th>NOMBRE DE LA ORGANIZACION</th>
		<th>DISTRITO</th>
		<th>Nº CUENTA</th>
		<th>MONTO TOTAL (S/.)</th>
		<th>MONTO A COF. PDSS II (S/.)</th>
		<th>MONTO A COF. ORG (S/.)</th>
		<th>PUNTAJE TOTAL</th>		
		<th>ESTADO</th>
		<th>MONTO A DESEMBOLSAR (30%) (S/.)</th>
	</tr>
</thead>

<tbody>
<?
$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pdn.n_cuenta, 
	((pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss)*0.30) AS aporte_30, 
	pit_bd_ficha_pdn.calificacion_2 AS calificacion, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_distrito.nombre AS distrito, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_pdss+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_total
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn_2 ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_2.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_bd_ficha_pdn_2.cod_clar='$cod'
ORDER BY calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
$total_pdn=mysql_num_rows($result);
while($f3=mysql_fetch_array($result))
{
?>
	<tr>
		<td class="centrado"><? echo $f3['n_documento'];?></td>
		<td class="centrado"><? echo $f3['tipo_doc'];?></td>
		<td><? echo $f3['nombre']."/".$f3['denominacion'];?></td>
		<td class="centrado"><? echo $f3['distrito'];?></td>
		<td class="centrado"><? echo $f3['n_cuenta'];?></td>
		<td class="derecha"><? echo number_format($f3['aporte_total'],2);?></td>
		<td class="derecha"><? echo number_format($f3['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($f3['aporte_org'],2);?></td>
		<td class="derecha"><? echo number_format($f3['calificacion']);?></td>
		<td class="centrado"><? if ($f3['calificacion']<70) echo "DESAPROBADO"; else echo "APROBADO"?></td>
		<td class="derecha"><? if ($f3['calificacion']>=70) echo number_format($f3['aporte_30'],2);?></td>
	</tr>
<?
}
$sql="SELECT SUM(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	SUM(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	SUM((pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss)*0.30) AS aporte_30, 
	SUM(pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_pdss+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_total
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn_2 ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_2.cod_pdn
WHERE clar_bd_ficha_pdn_2.cod_clar='$cod' AND
pit_bd_ficha_pdn.calificacion_2>=70";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
?>
<tr class="txt_titulo">
	<td colspan="5">TOTALES</td>
	<td class="derecha"><? echo number_format($r3['aporte_total'],2);?></td>
	<td class="derecha"><? echo number_format($r3['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($r3['aporte_org'],2);?></td>
	<td  class="centrado">-</td>
	<td  class="centrado">-</td>	
	<td class="derecha"><? echo number_format($r3['aporte_30'],2);?></td>
</tr>
<?
if($total_pdn==0)
{
?>
<tr>
	<td colspan="8" class="centrado gran_titulo">SIN INFORMACION. NO SE PRESENTARON INICIATIVAS DE ESTE TIPO</td>
</tr>
<?
}
?>
</tbody>

</table>

<br>
<div class="capa mini txt_titulo">Lugar y Fecha : <? echo $row['distrito'];?>, <?php  echo traducefecha($row['f_evento']);?></div>
<H1 class=SaltoDePagina> </H1>



<? include("encabezado.php");?>
<div class="capa txt_titulo">OLP: <? echo $row['oficina'];?></div>
<br/>
<div class="capa txt_titulo centrado">CUADRO DE RESULTADOS OBTENIDOS EN EL EVENTO CLAR Nº <? echo numeracion($row['cod_clar']);?> "<? echo $row['nombre'];?>" <br/> INICIATIVAS QUE ACCEDEN A UN SEGUNDO DESEMBOLSO</div>
<div class="capa"><br/></div>
<table cellpadding="1" cellspacing="1" border="1" width="90%" align="center" class="mini">
<thead>
	<tr>
		<th colspan="8">INVERSIONES PARA EL DESARROLLO LOCAL</th>
	</tr>
	<tr>
		<th>Nº</th>
		<th>TIPO DE DOCUMENTO</th>
		<th>NOMBRE DE LA ORGANIZACION</th>
		<th>OFICINA</th>
		<th>Nº CUENTA</th>
		<th>MONTO A DESEMBOLSAR</th>
		<th>PUNTAJE TOTAL</th>		
		<th>ESTADO</th>
	</tr>
</thead>

<tbody>
<?
$sql="SELECT org_ficha_organizacion.n_documento, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_idl.n_cuenta, 
	((pit_bd_ficha_idl.aporte_pdss* 
	pit_bd_ficha_idl.segundo_pago)/100) AS aporte_pdss, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.calificacion_2 AS calificacion
FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_idl_2 ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl_2.cod_idl
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE clar_bd_ficha_idl_2.cod_clar='$cod'
ORDER BY calificacion DESC";
$result=mysql_query($sql) or die (mysql_error());
$total_idl=mysql_num_rows($result);
while($f4=mysql_fetch_array($result))
{
?>	
<tr>
	<td><? echo $f4['n_documento'];?></td>
	<td><? echo $f4['tipo_doc'];?></td>
	<td><? echo $f4['nombre']." / ".$f4['denominacion'];?></td>
	<td><? echo $f4['oficina'];?></td>
	<td><? echo $f4['n_cuenta'];?></td>
	<td class="derecha"><? echo number_format($f4['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($f4['calificacion'],2);?></td>
	<td class="centrado"><? if ($f4['calificacion']<70) echo "DESAPROBADO"; else echo "APROBADO"?></td>
</tr>	
<?
}
if($total_idl==0)
{
?>
<tr>
	<td colspan="8" class="centrado gran_titulo">SIN INFORMACION. NO SE PRESENTARON INICIATIVAS DE ESTE TIPO</td>
</tr>
<?
}
?>
</tbody>
</table>

<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../clar/calif_clar_segundo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
    </td>
   </tr>
</table>

</body>
</html>
