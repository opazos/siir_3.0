<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT ml_liquida_pf.f_liquidacion, 
  ml_liquida_pf.resultado, 
  ml_liquida_pf.ejec_pdss, 
  ml_liquida_pf.ejec_org, 
  ml_liquida_pf.ejec_otro, 
  sys_bd_tipo_iniciativa.codigo_iniciativa, 
  ml_pf.nombre, 
  ml_pf.f_evento, 
  ml_pf.dia, 
  ml_pf.lugar, 
  ml_pf.n_participante, 
  ml_pf.objetivo, 
  ml_pf.resultados, 
  ml_pf.n_contrato, 
  ml_pf.f_contrato, 
  ml_pf.aporte_pdss, 
  ml_pf.aporte_org, 
  ml_pf.aporte_otro, 
  org_ficha_organizacion.nombre AS org, 
  sys_bd_dependencia.nombre AS oficina, 
  sys_bd_personal.n_documento AS dni, 
  sys_bd_personal.nombre AS nombres, 
  sys_bd_personal.apellido AS apellidos, 
  ml_liquida_pf.problemas, 
  org_ficha_organizacion.n_documento, 
  sys_bd_tipo_doc.descripcion AS tipo_doc, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  sys_bd_departamento.nombre AS departamento
FROM ml_pf INNER JOIN ml_liquida_pf ON ml_pf.cod_evento = ml_liquida_pf.cod_evento
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_pf.cod_tipo_iniciativa
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE ml_liquida_pf.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$total_contrato=$row['aporte_pdss']+$row['aporte_org'];
$total_ejecutado=$row['ejec_pdss']+$row['ejec_org'];

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
</style>
<!-- Fin -->
</head>

<body>
<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center">LIQUIDACION Y PERFECCIONAMIENTO DEL CONTRATO DE PARTICIPACION EN FERIAS</div>

<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">A</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">ING. JOSÉ SIALER PASCO</td>
  </tr>
  <tr>
    <td class="txt_titulo">&nbsp;</td>
    <td width="3%" class="txt_titulo">&nbsp;</td>
    <td width="75%" class="txt_titulo">Director Ejecutivo del Proyecto de Desarrollo Sierra Sur II</td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td class="txt_titulo">:</td>
    <td>Contrato N° <? echo numeracion($row['n_contrato'])."-".$row['codigo_iniciativa']."-".periodo($row['f_contrato'])."-".$row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fecha</td>
    <td class="txt_titulo">:</td>
    <td><? echo traducefecha($row['f_liquidacion']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<div class="capa justificado">
	<p>
		En relación al documento de la referencia, informo a su Despacho, que la organizacion <? echo $row['org'];?>, ha cumplido con sus obligaciones establecidas en el Contrato de Donación Sujeto a Cargo que están sustentadas en los siguientes documentos que se adjuntan:
	</p>
	
	<ol>
		<li>Informe Tecnico Administrativo de la participación en Feria.</li>
		<li>Archivo con documentación en ............. folios.</li>
	</ol>
	
<p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por LIQUIDADO el Contrato de la referencia por el monto total ejecutado de  S/ <? echo number_format($total_ejecutado,2);?> (<? echo vuelveletra($total_ejecutado);?>)</p>
<p>Por lo expuesto, esta jefatura procede al PERFECCIONAMIENTO de la Donación Sujeto a Cargo por el monto de S/. <? echo number_format($row['ejec_pdss'],2);?>. (<? echo vuelveletra($row['ejec_pdss']);?>) correspondiente al aporte del Proyecto de Desarrollo Sierra Sur II </p>
<p>Por lo indicado, mucho estimaré disponer la baja contable del contrato en referencia.</p>	
</div>

<div class="capa">Atentamente,</div>


<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="30%" align="center">___________________</td>
    <td width="35%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br/> JEFE DE LA OFICINA LOCAL DE ".$row['oficina'];?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">CONFORMIDAD PARA LA BAJA CONTABLE DEL CONTRATO DE PARTICIPACION EN FERIAS</div>

<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">Referencia</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">Contrato N° <? echo numeracion($row['n_contrato'])."-".$row['codigo_iniciativa']."-".periodo($row['f_contrato'])."-".$row['oficina'];?></td>
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
    <td colspan="2" align="center"><strong><u>LIQUIDACION DEL CONTRATO Y PERFECCIONAMIENTO DE LA DONACION SUJETO A CARGO</u></strong></td>
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

<!-- Informe -->
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>INFORME DE EJECUCION Y LIQUIDACION PARA PARTICIPACION EN FERIAS</u></div>

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
<td class="txt_titulo">Fecha del evento</td>
<td colspan="3"><? echo fecha_normal($row['f_evento']);?></td>

</tr>
</table>
<br/>
<div class="capa txt_titulo">II.- RESULTADOS DEL EVENTO</div>
<div class="capa justificado"><? echo $row['resultado'];?></div>
<div class="capa txt_titulo">III.- LOGROS ALCANZADOS POR LA ORGANIZACION</div>
<div class="capa txt_titulo">3.1.- Exhibición</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <thead>
    <tr class="txt_titulo centrado">
      <td>N.</td>
      <td>Productos en Exhibición</td>
      <td>Cantidad</td>
      <td>Unidad</td>
      <td>Caracteristicas resaltantes</td>
    </tr>
  </thead>
  <tbody>
  <?
  $na=0;
  $sql="SELECT * FROM ml_pf_muestra WHERE cod_liquida='$cod'";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $na++
  ?>
    <tr>
      <td class="centrado"><? echo $na;?></td>
      <td><? echo $f1['producto'];?></td>
      <td class="cemtrado"><? echo $f1['unidad'];?></td>
      <td class="derecha"><? echo number_format($f1['cantidad'],2);?></td>
      <td><? echo $f1['caracteristica'];?></td>
    </tr>
   <?
   }
   ?> 
  </tbody>
</table>
</br>
<div class="capa txt_titulo">3.2.- Ventas</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <thead>
    <tr class="centrado txt_titulo">
      <td>N.</td>
      <td>Productos en venta</td>
      <td>Unidad</td>
      <td>Cantidad</td>
      <td>Precio</td>
      <td>Total</td>
    </tr>
  </thead>

  <tbody>
  <?
  $nb=0;
  $sql="SELECT * FROM ml_pf_venta WHERE cod_liquida='$cod'";
  $result=mysql_query($sql) or die (mysql_error());
  while($f2=mysql_fetch_array($result))
  {
    $nb++
  ?>
    <tr>
      <td class="centrado"><? echo $nb;?></td>
      <td><? echo $f2['producto'];?></td>
      <td class="centrado"><? echo $f2['unidad'];?></td>
      <td class="derecha"><? echo number_format($f2['cantidad'],2);?></td>
      <td class="derecha"><? echo number_format($f2['precio'],2);?></td>
      <td class="derecha"><? echo number_format($f2['total'],2);?></td>
    </tr>
  <?
  }
  ?>  
  </tbody>
</table>
</br>
<div class="capa txt_titulo">3.3.- Contactos comerciales</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <thead>
    <tr class="centrado">
      <td>N.</td>
      <td>Nombre o razon social</td>
      <td>Mercado</td>
      <td>Acuerdos</td>
      <td>Producto</td>
    </tr>
  </thead>

  <tbody>
  <?
    $nc=0;
    $sql="SELECT ml_pf_contacto.contacto, 
    ml_pf_contacto.acuerdo, 
    ml_pf_contacto.producto, 
    sys_bd_tipo_mercado.descripcion
    FROM sys_bd_tipo_mercado INNER JOIN ml_pf_contacto ON sys_bd_tipo_mercado.cod = ml_pf_contacto.cod_mercado
    WHERE ml_pf_contacto.cod_liquida='$cod'";
    $result=mysql_query($sql) or die (mysql_error());
    while($f3=mysql_fetch_array($result))
    {
      $nc++
  ?>
    <tr>
      <td class="centrado"><? echo $nc;?></td>
      <td><? echo $f3['contacto'];?></td>
      <td class="centrado"><? echo $f3['descripcion'];?></td>
      <td><? echo $f3['acuerdo'];?></td>
      <td><? echo $f3['producto'];?></td>
    </tr>
  <?
  }
  ?>  
  </tbody>
</table>
</br>
<div class="capa txt_titulo">3.4.- Participación en eventos de capacitación</div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <thead>
    <tr class="txt_titulo centrado">
      <td>N.</td>
      <td>Nombre del evento</td>
      <td>Tema del evento</td>
      <td>N. de participantes</td>
    </tr>
  </thead>

  <tbody>
  <?
    $nd=0;
    $sql="SELECT * FROM ml_pf_evento WHERE cod_liquida='$cod'";
    $result=mysql_query($sql) or die (mysql_error());
    while($f4=mysql_fetch_array($result))
    {
      $nd++
  ?>
    <tr>
      <td class="centrado"><? echo $nd;?></td>
      <td><? echo $f4['nombre'];?></td>
      <td><? echo $f4['tema'];?></td>
      <td class="derecha"><? echo number_format($f4['n_participante']);?></td>
    </tr>
  <?
  }
  ?>  
  </tbody>
</table>
























<br/>
<div class="capa txt_titulo">IV.- EJECUCIÓN FINANCIERA</div>

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
    <td align="right"><? echo number_format($row['ejec_otro']+$row['ejec_mun'],2);?></td>
    <td align="right"><?
  @$potr=($row['ejec_otro']/$row['aporte_otro'])*100;
  echo number_format(@$potr,2);
  ?></td>
  </tr>
  <?
  $monto_programado=$row['aporte_pdss']+$row['aporte_org']+$row['aporte_otro'];
  $monto_total=$row['ejec_pdss']+$row['ejec_org']+$row['ejec_otr'];
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
  <p class="txt_titulo">IV.- COMENTARIOS/OBSERVACIONES</p>
  <p><? echo $row['problemas'];?></p>
</div>






<div class="capa">
	 <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_pf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_liquida" class="secondary button oculto">Finalizar</a>
</div>




</body>
</html>
