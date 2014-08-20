<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT ml_liquida_pc.cod_liquida_pc, 
	ml_liquida_pc.f_rendicion, 
	ml_liquida_pc.resultado, 
	ml_liquida_pc.problema, 
	(ml_liquida_pc.ejec_pdss+ 
	ml_liquida_pc.ejec_org+ 
	ml_liquida_pc.ejec_otro) AS ejec_contrato, 
	ml_liquida_pc.entidad_otro, 
	ml_liquida_pc.participantes, 
	ml_liquida_pc.publico, 
	ml_liquida_pc.ingresos, 
	ml_promocion_c.nombre, 
	ml_promocion_c.f_inicio, 
	ml_promocion_c.f_termino, 
	ml_promocion_c.lugar, 
	ml_promocion_c.f_contrato, 
	ml_promocion_c.n_contrato, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS org, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	ml_promocion_c.aporte_pdss, 
	ml_promocion_c.aporte_org, 
	ml_promocion_c.aporte_otro, 
	ml_promocion_c.objetivo, 
	ml_promocion_c.cod_evento, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	sys_bd_dependencia.nombre AS oficina, 
	ml_liquida_pc.ejec_pdss, 
	ml_liquida_pc.ejec_org, 
	ml_liquida_pc.ejec_otro, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos
FROM ml_promocion_c INNER JOIN ml_liquida_pc ON ml_promocion_c.cod_evento = ml_liquida_pc.cod_evento
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_promocion_c.cod_tipo_iniciativa
WHERE ml_liquida_pc.cod_liquida_pc='$cod'";
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
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::Vista Preeliminar::</title>
<!-- cargamos el estilo de la pagina -->
<link href="../stylesheets/print.css" rel="stylesheet" type="text/css">
<style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
</style>
<!-- Fin -->
</head>
<body>
<div class="row">
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>LIQUIDACION Y PERFECCIONAMIENTO DEL CONTRATO DE EJECUCION DE PROMOCION COMERCIAL</u></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">A</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">ING. JOSE MERCEDES SIALER PASCO</td>
  </tr>
  <tr>
    <td class="txt_titulo">&nbsp;</td>
    <td width="3%" class="txt_titulo">&nbsp;</td>
    <td width="75%" class="txt_titulo">DIRECTOR EJECUTIVO DEL NEC PROYECTO DE DESARROLLO SIERRA SUR II</td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td class="txt_titulo">:</td>
    <td>Contrato N° <? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_contrato']);?>-<? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fecha</td>
    <td class="txt_titulo">:</td>
    <td><? echo traducefecha($row['f_rendicion']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">

<p>En relación al documento de la referencia, informo a su despacho, que la organizacion <? echo $row['org'];?>, ha cumplido con sus obligaciones establecidas en el Contrato de Donación sujeto a Cargo que están sustentadas en los siguientes documentos que se adjuntan:</p>

<ul>
	<li>Informe de Ejecución y Liquidación de Promoción Comercial.</li>
	<li>Archivo con documentación en ......... folios.</li>
</ul>

<p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por LIQUIDADO el Contrato de la referencia por el monto total ejecutado de  S/ <? echo number_format($row['ejec_contrato'],2);?> (<? echo vuelveletra($row['ejec_contrato']);?>)</p>
<p>Por lo expuesto, esta jefatura procede al PERFECCIONAMIENTO de la Donación Sujeto a Cargo por el monto de S/. <? echo number_format($row['ejec_pdss'],2);?>. (<? echo vuelveletra($row['ejec_pdss']);?>) correspondiente al aporte del Proyecto de Desarrollo Sierra Sur II </p>
<p>Por lo indicado, mucho estimaré disponer la baja contable del contrato en referencia.</p>
</div>
<p><br></p>
<div class="capa">Atentamente,</div>

<p><br></p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="30%" align="center">___________________</td>
    <td width="35%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br>JEFE DE OFICINA LOCAL";?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<!-- Informe -->
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>INFORME DE EJECUCION Y LIQUIDACION PARA LA PROMOCION COMERCIAL / AUSPICIO PARA LA REALIZACION DE FERIAS</u></div>
<br>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
	<td class="txt_titulo" colspan="4">Nombre de la Organización / Municipio</td>
</tr>
<tr>
	<td colspan="4"><? echo $row['org'];?></td>
</tr>
<tr>
	<td class="txt_titulo" width="20%">Tipo de Documento</td>
	<td width="30%"><? echo $row['tipo_doc'];?></td>
	<td class="txt_titulo" width="20%">Nº de documento</td>
	<td width="30%"><? echo $row['n_documento'];?></td>
</tr>

<tr>
	<td class="txt_titulo">Contrato Nº</td>
	<td><? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_contrato']);?>-<? echo $row['oficina'];?></td>
	<td class="txt_titulo">Fecha de contrato</td>
	<td><? echo fecha_normal($row['f_contrato']);?></td>
</tr>

<tr>
	<td colspan="4" class="txt_titulo">Nombre del Evento</td>
</tr>
<tr>
	<td colspan="4"><? echo $row['nombre'];?></td>
</tr>
<tr>
	<td class="txt_titulo" colspan="4">Duración del Evento</td>
</tr>
<tr>
<td class="txt_titulo">Fecha de Inicio</td>
<td><? echo fecha_normal($row['f_inicio']);?></td>
<td class="txt_titulo">Fecha de Termino</td>
<td><? echo fecha_normal($row['f_termino']);?></td>
</tr>

<tr>
	<td class="txt_titulo" colspan="4"><br/>II.- PRESUPUESTO TOTAL DEL EVENTO</td>
</tr>
</table>
<br/>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">

<tr class="txt_titulo centrado">
	<td>Concepto</td>
	<td>Unidad</td>
	<td>Cantidad</td>
	<td>Costo Unitario (S/.)</td>
	<td>Costo Total (S/.)</td>
</tr>
<?
	$sql="SELECT ml_presupuesto_pc.cod_presupuesto, 
	ml_presupuesto_pc.descripcion, 
	ml_presupuesto_pc.unidad, 
	ml_presupuesto_pc.costo_unitario, 
	ml_presupuesto_pc.cantidad, 
	ml_presupuesto_pc.costo_total
FROM ml_presupuesto_pc
WHERE ml_presupuesto_pc.cod_liquida_pc='$cod'";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
?>
<tr>
	<td><? echo $f1['descripcion'];?></td>
	<td class="centrado"><? echo $f1['unidad'];?></td>
	<td class="derecha"><? echo number_format($f1['cantidad'],2);?></td>
	<td class="derecha"><? echo number_format($f1['costo_unitario'],2);?></td>
	<td class="derecha"><? echo number_format($f1['costo_total'],2);?></td>
</tr>
<?
}
?>
</table>
<br/>
<div class="capa txt_titulo">III.- ESQUEMA DE COFINANCIAMIENTO APROBADO</div>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="57%" align="center"><strong>COFINANCIAMIENTO</strong></td>
    <td width="17%" align="center"><strong>MONTO PROGRAMADO (S/.)</strong></td>
    <td width="15%" align="center"><strong>MONTO EJECUTADO (S/.)</strong></td>
    <td width="11%" align="center"><strong>%</strong></td>
  </tr>
  <tr>
    <td>SIERRA SUR II</td>
    <td align="right"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td align="right"><? echo number_format($row['ejec_pdss'],2);?></td>
    <td align="right">
    <?
	@$pss=($row['ejec_pdss']/$row['aporte_pdss'])*100;
	echo number_format(@$pss,2);
	?>
    </td>
  </tr>
  <tr>
    <td>ORGANIZACION</td>
    <td align="right"><? echo number_format($row['aporte_org'],2);?></td>
    <td align="right"><? echo number_format($row['ejec_org'],2);?></td>
    <td align="right"><?
	@$pog=($row['ejec_org']/$row['aporte_org'])*100;
	echo number_format(@$pog,2);
	?></td>
  </tr>
  <tr>
    <td>OTRO</td>
    <td align="right"><? echo number_format($row['aporte_otro'],2);?></td>
    <td align="right"><? echo number_format($row['ejec_otr']+$row['ejec_mun'],2);?></td>
    <td align="right"><?
	@$potr=($row['ejec_otr']+$row['ejec_mun']/$row['aporte_otro'])*100;
	echo number_format(@$potr,2);
	?></td>
  </tr>
  <?
  $monto_programado=$row['aporte_pdss']+$row['aporte_org']+$row['aporte_otro'];
  $monto_total=$row['ejec_pdss']+$row['ejec_org']+$row['ejec_mun']+$row['ejec_otr'];
  ?>
  <tr>
    <td><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($monto_programado,2);?></td>
    <td align="right"><? echo number_format($monto_total,2);?></td>
    <td align="right"><?
	@$ptol=($monto_total/$monto_programado)*100;
	echo number_format(@$ptol,2);
	?></td>
  </tr>
</table>
<br/>
<div class="capa">
  <p class="txt_titulo">IV.- Resultados alcanzados</p>
  <p><? echo $row['resultado'];?></p>
</div>


<br/>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
	<td class="txt_titulo" width="70%">Numero de organizaciones participantes en el evento</td>
	<td width="30%"><? echo number_format($row['participantes']);?></td>
</tr>

<tr>
	<td class="txt_titulo">Estimado de público asistente</td>
	<td><? echo number_format($row['publico']);?></td>
</tr>

<tr>
	<td class="txt_titulo">Ingresos Netos Generados</td>
	<td><? echo number_format($row['ingresos'],2);?> Nuevos Soles</td>
</tr>

</table>
<br/>
<div class="capa">
  <p class="txt_titulo">V.- Observaciones/Comentarios</p>
  <p><? echo $row['problema'];?></p>
</div>


<!-- Memorandun -->
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>CONFORMIDAD PARA LA BAJA CONTABLE DEL CONTRATO DE PROMOCIÓN COMERCIAL</u></div>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">Referencia</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">Contrato N° <? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_contrato']);?>-<? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización</td>
    <td class="txt_titulo">:</td>
    <td><? echo $row['org'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>

<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" align="center" class="txt_titulo"><U>PROVEIDO DE CONFORMIDAD</u></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="txt_titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">RESPONSABLE DEL COMPONENTE</td>
  </tr>
  <tr>
    <td width="52%">Es conforme</td>
    <td width="48%"><input type="checkbox" name="checkbox" id="checkbox"></td>
  </tr>
  <tr>
    <td>Devuelto con observaciones</td>
    <td><input type="checkbox" name="checkbox2" id="checkbox2"></td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>Fecha</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">ADMINISTRADOR</td>
  </tr>
  <tr>
    <td>Es conforme</td>
    <td><input type="checkbox" name="checkbox3" id="checkbox3"></td>
  </tr>
  <tr>
    <td>Devuelto con observaciones</td>
    <td><input type="checkbox" name="checkbox3" id="checkbox4"></td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>Fecha</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="centrado txt_titulo"><u>LIQUIDACION DEL CONTRATO Y PERFCCIONAMIENTO DE LA DONACION SUJETO A CARGO</u></td>
  </tr>
</table>
<BR>
<div class="capa" align="justify">VISTO EL INFORME DE LIQUIDACION Y PERFECCIONAMIENTO DE LA DONACIÓN CORRESPONDIENTE A LOS DOCUMENTOS DE LA REFERENCIA, ESTANDO A LA CONFORMIDAD DEL RESPONSABLE DE COMPONENTES Y DEL ADMINISTRADOR, EL SUSCRITO DIRECTOR EJECUTIVO DISPONE A LA ADMINISTRACION  LA BAJA CONTABLE DE LA INICIATIVA DE LA REFERENCIA.</div>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="52%">Firma</td>
    <td width="48%">Fecha</td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_pc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_liquida" class="secondary button oculto">Finalizar</a>    
    </td>
  </tr>
</table>




</div>


</body>
</html>
