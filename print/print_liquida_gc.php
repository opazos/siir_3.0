<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT gcac_bd_liquida_evento_gc.f_informe, 
  gcac_bd_liquida_evento_gc.resultado, 
  gcac_bd_liquida_evento_gc.problema, 
  (gcac_bd_liquida_evento_gc.ejec_pdss+ 
  gcac_bd_liquida_evento_gc.ejec_org+ 
  gcac_bd_liquida_evento_gc.ejec_mun) AS ejec_contrato, 
  gcac_bd_liquida_evento_gc.ejec_otr, 
  gcac_bd_liquida_evento_gc.ejec_mun,
  gcac_bd_evento_gc.nombre AS evento, 
  gcac_bd_evento_gc.f_evento, 
  gcac_bd_evento_gc.lugar, 
  gcac_bd_evento_gc.participantes, 
  gcac_bd_evento_gc.n_contrato, 
  gcac_bd_evento_gc.f_contrato, 
  gcac_bd_evento_gc.aporte_pdss, 
  gcac_bd_evento_gc.aporte_org, 
  gcac_bd_evento_gc.aporte_otro, 
  sys_bd_tipo_evento_gc.descripcion AS tipo_evento, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre AS organizacion, 
  sys_bd_tipo_iniciativa.codigo_iniciativa, 
  sys_bd_dependencia.nombre AS oficina, 
  sys_bd_personal.n_documento AS dni, 
  sys_bd_personal.nombre, 
  sys_bd_personal.apellido, 
  gcac_bd_liquida_evento_gc.ejec_pdss, 
  gcac_bd_liquida_evento_gc.ejec_org
FROM gcac_bd_evento_gc INNER JOIN gcac_bd_liquida_evento_gc ON gcac_bd_evento_gc.cod_evento_gc = gcac_bd_liquida_evento_gc.cod_evento_gc
   INNER JOIN sys_bd_tipo_evento_gc ON sys_bd_tipo_evento_gc.cod = gcac_bd_evento_gc.cod_tipo_evento_gc
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_evento_gc.cod_tipo_iniciativa
WHERE gcac_bd_liquida_evento_gc.cod_liquida_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$monto_total=$row['ejec_pdss']+$row['ejec_org'];

?>
<!DOCTYPE HTML>
<html>
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
<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center"><u>LIQUIDACION Y PERFECCIONAMIENTO DEL CONTRATO PARA PARTICIPACION EN CONCURSOS DEL CONOCIMIENTO Y VALORIZACION DE ACTIVOS CULTURALES</u></div>

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
    <td><? echo $row['oficina'];?>, <? echo traducefecha($row['f_informe']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">

<p>En relación al documento de la referencia, informo a su despacho, que la organización <? echo $row['organizacion'];?>, ha cumplido con sus obligaciones establecidas en el Contrato de Donación Sujeto a Cargo que están sustentadas en los siguientes documentos que se adjuntan:</p>
<ol>
	<li>Informe de Ejecución y liquidación del Contrato para participar en Concursos del Conocimiento y Activos Culturales</li>
	<li>Archivo con documentación en ........... folios.</li>
</ol>

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
    <td align="center"><? echo $row['nombre']." ".$row['apellido']."<br>JEFE DE OFICINA LOCAL";?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>INFORME DE EJECUCIÓN Y LIQUIDACIÓN DE CONTRATO</u><BR>
 PARA PARTICIPACION EN CONCURSOS DEL CONOCIMIENTO Y VALORIZACIÓN DE ACTIVOS CULTURALES<BR></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="28%">N° Contrato</td>
    <td width="2%">:</td>
    <td width="70%">Contrato N° <? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_contrato']);?>-<? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>Fecha de Contrato</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_contrato']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">I.- INFORMACION GENERAL</td>
  </tr>
  <tr>
    <td>Nombre del evento</td>
    <td>:</td>
    <td><? echo $row['evento'];?></td>
  </tr>
  <tr>
    <td>Fecha del evento</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_evento']);?></td>
  </tr>
  <tr>
	  <td>Lugar de realización</td>
	  <td>:</td>
	  <td><? echo $row['lugar'];?></td>
  </tr>
    <tr>
	  <td>Nº Participantes</td>
	  <td>:</td>
	  <td><? echo $row['participantes'];?></td>
  </tr>

  <tr>
    <td colspan="3" class="txt_titulo">II.- RESULTADOS Y COMENTARIOS</td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">2.1 Resultados alcanzados</td>
  </tr>
  <tr>
    <td colspan="3" align="justify"><? echo $row['resultado'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">2.2 Comentarios u observaciones</td>
  </tr>
  <tr>
    <td colspan="3" align="justify"><? echo $row['problema'];?></td>
  </tr>
</table>
<br>


<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo">III.- ESQUEMA DE COFINANCIAMIENTO</td>
  </tr>
</table>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="57%" align="center"><strong>ENTIDAD</strong></td>
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
<br>
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_informe']);?></div>
<H1 class=SaltoDePagina> </H1>

<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center"><u>CONFORMIDAD PARA LA BAJA CONTABLE DEL CONTRATO GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS</u></div>
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
    <td><? echo $row['organizacion'];?></td>
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
    <td colspan="2" class="centrado txt_titulo"><u>LIQUIDACIÓN Y CIERRE DEL CONTRATO Y PERFECCIONAMIENTO DE LA DONACIÓN CON CARGO</u></td>
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
    <a href="../contratos/contrato_gc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_informe" class="secondary button oculto">Finalizar</a>    
    </td>
  </tr>
</table>




</body>
</html>