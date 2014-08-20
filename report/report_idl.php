<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

if ($modo==excell)
{
header('Content-type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=Consistencia_idl.xls");
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

<div class="twelve columns"><center><h5>REPORTE DE ESTADO DE INVERSIONES PARA EL DESARROLLO LOCAL</h5></center></div>
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
			<th>Denominacion de la IDL</th>
			<th>Tipo de IDL</th>
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
	if($row['cod_dependencia']==001)
	{
		$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.f_inicio, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.f_termino, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_cp.nombre AS cp, 
	sys_bd_tipo_idl.descripcion AS tipo_idl, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_tipo_idl ON sys_bd_tipo_idl.cod_tipo_idl = pit_bd_ficha_idl.cod_tipo_idl
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003";
	}
	else
	{
		$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.f_inicio, 
	pit_bd_ficha_idl.f_contrato, 
	pit_bd_ficha_idl.f_termino, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	sys_bd_cp.nombre AS cp, 
	sys_bd_tipo_idl.descripcion AS tipo_idl, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_estado_iniciativa.descripcion AS estado
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 INNER JOIN sys_bd_tipo_idl ON sys_bd_tipo_idl.cod_tipo_idl = pit_bd_ficha_idl.cod_tipo_idl
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($r1=mysql_fetch_array($result))
	{
		$plazo=meses($r1['f_inicio'], $r1['f_termino']);	
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
			<td><? echo $r1['tipo_idl'];?></td>
			<td><? echo fecha_normal($r1['f_contrato']);?></td>
			<td><? echo $plazo+1;?></td>
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
	<a href="report_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=excell" class="success button oculto">Exportar a Excell</a>
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
