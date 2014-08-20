<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Consistencia_PDN.xls");
header("Pragma: no-cache");
}

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>::SIIR - Sistema de Informacion de Iniciativas Rurales::</title>
   <link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/general_foundicons.css">
  
  <link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
    <style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
   @page { size: A4 landscape; }
</style>
 
  <link rel="stylesheet" href="../stylesheets/app.css">
  <link rel="stylesheet" href="../rtables/responsive-tables.css">
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
</head>
<body>
<? include("../print/encabezado.php");?>

<div class="twelve columns"><center><h5>REPORTE DE ESTADO DE PLANES DE NEGOCIO</h5></center></div>
<div class="twelve columns"><hr/></div>

<table class="responsive">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Tipo Documento</th>
			<th>Nº Documento</th>
			<th>Nombre de la Organización</th>
			<th>Oficina</th>
			<th>Departamento</th>
			<th>Provincia</th>
			<th>Distrito</th>
			<th>Centro Poblado</th>
			<th>Denominacion</th>
			<th>Linea de Negocio</th>
			<th>Fecha de Inicio</th>
			<th>Duración en meses</th>
			<th>Fecha de Termino</th>
			<th>Estado</th>
			<th>Comentario</th>
		</tr>
	</thead>
	
	
	<tbody>
	<?
	$num=0;
	if ($row['cod_dependencia']==001)
	{
		$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_linea_pdn.descripcion AS linea, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.f_contrato, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_cp.nombre AS cp, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_taz.n_documento AS n_doc_taz, 
	org_ficha_taz.nombre AS territorio, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_pdn.tipo, 
	pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pit.cod_pit, 
	pit_bd_ficha_pit.f_contrato AS f_contrato_pit
FROM sys_bd_linea_pdn INNER JOIN pit_bd_ficha_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 LEFT OUTER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN org_ficha_taz ON org_ficha_organizacion.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_organizacion.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003
ORDER BY sys_bd_dependencia.cod_dependencia ASC, pit_bd_ficha_pdn.f_termino ASC";
	}
	else
	{
		$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_linea_pdn.descripcion AS linea, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.f_contrato, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_cp.nombre AS cp, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_taz.n_documento AS n_doc_taz, 
	org_ficha_taz.nombre AS territorio, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_pdn.tipo, 
	pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pit.cod_pit, 
	pit_bd_ficha_pit.f_contrato AS f_contrato_pit
FROM sys_bd_linea_pdn INNER JOIN pit_bd_ficha_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
	 LEFT OUTER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN org_ficha_taz ON org_ficha_organizacion.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_organizacion.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY sys_bd_dependencia.cod_dependencia ASC, pit_bd_ficha_pdn.f_termino ASC";
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
			<td><? echo $r1['oficina'];?></td>
			<td><? echo $r1['departamento'];?></td>
			<td><? echo $r1['provincia'];?></td>
			<td><? echo $r1['distrito'];?></td>
			<td><? echo $r1['cp'];?></td>
			<td><? echo $r1['denominacion'];?></td>
			<td><? echo $r1['linea'];?></td>
			<td>
<?

if($r1['tipo']==1)
{
	$f_inicio=$r1['f_contrato'];
}
elseif($r1['tipo']==0 and $r1['f_contrato_pit']<>'0000-00-00')
{
	$f_inicio=$r1['f_contrato_pit'];
}
echo fecha_normal($f_inicio);
?>
			</td>
			<td><? echo $r1['mes'];?></td>
			<td><? if ($r1['f_termino']<>'0000-00-00') echo fecha_normal($r1['f_termino']);?></td>
			<td><? echo $r1['estado'];?></td>
			<td><? if ($fecha_hoy>$r1['f_termino'] and $r1['f_termino']<>'0000-00-00') echo "PLAZO VENCIDO";?></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
	
</table>


<div class="twelve columns">
	<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
	<a href="report_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>

<!-- Footer -->
<? include("../footer.php");?>


  <!-- Included JS Files (Uncompressed) -->
  <!--
  
  <script src="javascripts/jquery.js"></script>
  
  <script src="javascripts/jquery.foundation.mediaQueryToggle.js"></script>
  
  <script src="javascripts/jquery.foundation.forms.js"></script>
  
  <script src="javascripts/jquery.event.move.js"></script>
  
  <script src="javascripts/jquery.event.swipe.js"></script>
  
  <script src="javascripts/jquery.foundation.reveal.js"></script>
  
  <script src="javascripts/jquery.foundation.orbit.js"></script>
  
  <script src="javascripts/jquery.foundation.navigation.js"></script>
  
  <script src="javascripts/jquery.foundation.buttons.js"></script>
  
  <script src="javascripts/jquery.foundation.tabs.js"></script>
  
  <script src="javascripts/jquery.foundation.tooltips.js"></script>
  
  <script src="javascripts/jquery.foundation.accordion.js"></script>
  
  <script src="javascripts/jquery.placeholder.js"></script>
  
  <script src="javascripts/jquery.foundation.alerts.js"></script>
  
  <script src="javascripts/jquery.foundation.topbar.js"></script>
  
  <script src="javascripts/jquery.foundation.joyride.js"></script>
  
  <script src="javascripts/jquery.foundation.clearing.js"></script>
  
  <script src="javascripts/jquery.foundation.magellan.js"></script>
  
  -->
  
  <!-- Included JS Files (Compressed) -->
  <script src="../javascripts/jquery.js"></script>
  <script src="../javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="../javascripts/app.js"></script>
</body>
</html>
