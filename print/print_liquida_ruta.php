<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- Obtengo los datos
$sql="SELECT gcac_bd_ruta.cod_tipo_ruta, 
  sys_bd_tipo_iniciativa.codigo_iniciativa, 
  gcac_bd_ruta.otro_ruta, 
  gcac_bd_ruta.nombre AS evento, 
  gcac_bd_ruta.f_inicio, 
  gcac_bd_ruta.f_termino, 
  gcac_bd_ruta.objetivo, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre AS organizacion, 
  sys_bd_tipo_doc.descripcion AS tipo_doc, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  sys_bd_dependencia.nombre AS oficina, 
  org_ficha_usuario.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  gcac_bd_ruta.n_contrato, 
  gcac_bd_ruta.f_contrato, 
  gcac_bd_ruta.aporte_pdss, 
  gcac_bd_ruta.aporte_org, 
  gcac_bd_ruta.aporte_otro, 
  gcac_bd_ruta.f_liquidacion, 
  gcac_bd_ruta.resultado, 
  gcac_bd_ruta.b1_fav, 
  gcac_bd_ruta.b1_limit, 
  gcac_bd_ruta.b2_fav, 
  gcac_bd_ruta.b2_limit, 
  gcac_bd_ruta.b3_fav, 
  gcac_bd_ruta.b3_limit, 
  gcac_bd_ruta.b4_fav, 
  gcac_bd_ruta.b4_limit, 
  gcac_bd_ruta.recomendaciones, 
  gcac_bd_ruta.ejec_pdss, 
  gcac_bd_ruta.ejec_org, 
  gcac_bd_ruta.ejec_otro, 
  sys_bd_personal.nombre AS nombres, 
  sys_bd_personal.apellido AS apellidos, 
  gcac_bd_ruta.observaciones
FROM sys_bd_tipo_iniciativa INNER JOIN gcac_bd_ruta ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_ruta.cod_tipo_iniciativa
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_ruta.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_ruta.n_documento_org
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento AND org_ficha_usuario.cod_tipo_doc = gcac_bd_ruta.cod_tipo_doc AND org_ficha_usuario.n_documento = gcac_bd_ruta.n_documento
WHERE gcac_bd_ruta.cod_ruta='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

if ($r1['cod_tipo_ruta']==5)
{
  $tipo_evento="DIPLOMADO";
  $contratado="BECARIO";
}
else
{
  $tipo_evento="RUTA DE APRENDIZAJE";
  $contratado="RUTERO";
}

$total_programado=$r1['aporte_pdss']+$r1['aporte_org']+$r1['aporte_otro'];
$total_ejecutado=$r1['ejec_pdss']+$r1['ejec_org']+$r1['ejec_otro'];

if($r1['cod_tipo_ruta']==1)
{
  $tipo_ruta="ENCUENTRO DEL CONOCIMIENTO";
}
elseif($r1['cod_tipo_ruta']==2)
{
  $tipo_ruta="CAPACITACION DEL PERSONAL";
}
elseif($r1['cod_tipo_ruta']==3)
{
  $tipo_ruta="CAPACITACION DE TALENTOS LOCALES";
}
elseif($r1['cod_tipo_ruta']==4)
{
  $tipo_ruta="CAPACITACION DE OFERENTES TECNICOS";
}
elseif($r1['cod_tipo_ruta']==5)
{
  $tipo_ruta="DIPLOMADO";
}
elseif($r1['cod_tipo_ruta']==6)
{
  $tipo_ruta=$r1['otro_ruta'];
}

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
<div class="capa txt_titulo" align="center"><u>LIQUIDACION Y PERFECCIONAMIENTO</u><br>DE CONTRATO DE <? echo $tipo_evento;?></div>


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
    <td width="75%" class="txt_titulo">Director Ejecutivo del Proyecto Sierra Sur II</td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td class="txt_titulo">:</td>
    <td>Contrato N° <? echo numeracion($r1['n_contrato']);?>-<? echo $r1['codigo_iniciativa'];?>-<? echo periodo($r1['f_contrato']);?>-<? echo $r1['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fecha</td>
    <td class="txt_titulo">:</td>
    <td><? echo $r1['oficina'];?>, <? echo traducefecha($r1['f_liquidacion']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">
<p>En relación al documento de la referencia, informo a su Despacho, que el  <? echo $contratado;?> <? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?>, perteneciente a la Organización: "<? echo $r1['organizacion'];?>", motivo del contrato de la referencia, ha cumplido con sus obligaciones establecidas en el indicado Contrato de Donación Sujeto a Cargo que estan sustentadas en los siguientes documentos que se adjuntan:</p>

<ol>
	<li>Informe de Ejecución y Liquidación de <? echo $tipo_evento;?>.</li>
	<li>........... archivo con documentación en ........... folios</li>
</ol>

<p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por LIQUIDADO el Contrato de la referencia por el monto total ejecutado de  S/ <? echo number_format($total_ejecutado,2);?> (<? echo vuelveletra($total_ejecutado);?>)</p>
<p>Por lo expuesto, esta jefatura procede al PERFECCIONAMIENTO de la Donación Sujeto a Cargo por el monto de S/. <? echo number_format($r1['ejec_pdss'],2);?>. (<? echo vuelveletra($r1['ejec_pdss']);?>) correspondiente al aporte del Proyecto de Desarrollo Sierra Sur II </p>
<p>Por lo indicado, mucho estimaré disponer la baja contable del contrato en referencia.</p>
</div>
<br>
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
    <td align="center"><? echo $r1['nombres']." ".$r1['apellidos']."<br> Jefe de la Oficina Local ".$r1['oficina'];?></td>
    <td>&nbsp;</td>
  </tr>
</table>

<!-- Informe Final -->
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center"><u>INFORME DE EJECUCION Y LIQUIDACION DE <? echo $tipo_evento;?></u></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="28%">Nombre de la actividad</td>
    <td width="2%">:</td>
    <td width="70%"><? echo $r1['evento'];?></td>
  </tr>
  <tr>
    <td>Nombre de la Organización</td>
    <td>:</td>
    <td><? echo $r1['organizacion'];?></td>
  </tr>
  <tr>
    <td>Nombre del participante</td>
    <td>:</td>
    <td><? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?></td>
  </tr>
</table>  

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="28%">N. de contrato</td>
    <td width="2%">:</td>
    <td width="20%"><? echo numeracion($r1['n_contrato'])."-".$r1['codigo_iniciativa']."-".periodo($r1['f_contrato']);?></td>
    <td width="28%">Oficina Local</td>
    <td width="2%">:</td>
    <td width="20%"><? echo $r1['oficina'];?></td>
  </tr>

  <tr>
    <td>Fecha de inicio</td>
    <td>:</td>
    <td><? echo traducefecha($r1['f_inicio']);?></td>
    <td>Fecha de finalización</td>
    <td>:</td>
    <td><? echo traducefecha($r1['f_termino']);?></td>
  </tr>

  <tr>
    <td>Tipo de actividad</td>
    <td>:</td>
    <td colspan="4"><? echo $tipo_ruta;?></td>
  </tr>
</table>
<br/>
<div class="capa txt_titulo">OBJETIVO(s)</div>
<div class="capa"><? echo $r1['objetivo'];?></div>
<br/>
<div class="capa txt_titulo">DESCRIPCION DE LAS ACCIONES EJECUTADAS</div>
<div class="capa"><? echo $r1['resultado'];?></div>
<br/>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="30%">BALANCE DE LA ACTIVIDAD</td>
    <td width="35%">ASPECTOS FAVORABLES</td>
    <td width="35%">LIMITACIONES</td>
  </tr>
  <tr>
    <td>DE LA PARTICIPACION</td>
    <td><? echo $r1['b1_fav'];?></td>
    <td><? echo $r1['b1_limit'];?></td>
  </tr>
  <tr>
    <td>DE LA METODOLOGIA USADA</td>
    <td><? echo $r1['b2_fav'];?></td>
    <td><? echo $r1['b2_limit'];?></td>
  </tr>
  <tr>
    <td>DE LOS TEMAS TRATADOS</td>
    <td><? echo $r1['b3_fav'];?></td>
    <td><? echo $r1['b3_limit'];?></td>
  </tr>  
   <tr>
    <td>OTROS TEMAS RELEVANTES</td>
    <td><? echo $r1['b4_fav'];?></td>
    <td><? echo $r1['b4_limit'];?></td>
  </tr> 
</table>
<br/>
<div class="capa txt_titulo">RECOMENDACIONES</div>
<div class="capa"><? echo $r1['recomendaciones'];?></div>
<br/>
<div class="capa txt_titulo">DE LA INVERSION</div>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <thead>
    <tr class="txt_titulo centrado">
      <td rowspan="2">FUENTES DE FINANCIAMIENTO</td>
      <td colspan="2">PPTO. APROBADO</td>
      <td colspan="2">PPTO. EJECUTADO</td>
    </tr>
    <tr class="txt_titulo centrado">
      <td width="15%">MONTO (S/.)</td>
      <td width="15%">%</td>
      <td width="15%">MONTO (S/.)</td>
      <td width="15%">%</td>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>SIERRA SUR</td>
      <td class="derecha"><? echo number_format($r1['aporte_pdss'],2);?></td>
      <td class="derecha"><? $pp_pdss=(($r1['aporte_pdss']/$total_programado)*100); echo number_format($pp_pdss,2);?></td>
      <td class="derecha"><? echo number_format($r1['ejec_pdss'],2);?></td>
      <td class="derecha"><? $pe_pdss=(($r1['ejec_pdss']/$total_ejecutado)*100); echo number_format($pe_pdss,2);?></td>
    </tr>
    <tr>
      <td>ORGANIZACIÓN</td>
      <td class="derecha"><? echo number_format($r1['aporte_org'],2);?></td>
      <td class="derecha"><? $pp_org=(($r1['aporte_org']/$total_programado)*100); echo number_format($pp_org,2);?></td>
      <td class="derecha"><? echo number_format($r1['ejec_org'],2);?></td>
      <td class="derecha"><? $pe_org=(($r1['ejec_org']/$total_ejecutado)*100); echo number_format($pe_org,2);?></td>
    </tr> 
    <tr>
      <td>OTROS</td>
      <td class="derecha"><? echo number_format($r1['aporte_otro'],2);?></td>
      <td class="derecha"><? $pp_otro=(($r1['aporte_otro']/$total_programado)*100); echo number_format($pp_otro,2);?></td>
      <td class="derecha"><? echo number_format($r1['ejec_otro'],2);?></td>
      <td class="derecha"><? $pe_otro=(($r1['ejec_otro']/$total_ejecutado)*100); echo number_format($pe_otro,2);?></td>
    </tr>
    <tr class="txt_titulo">
      <td>TOTAL</td>
      <td class="derecha"><? echo number_format($total_programado,2);?></td>
      <td class="derecha">100.00</td>
      <td class="derecha"><? echo number_format($total_ejecutado,2);?></td>
      <td class="derecha">100.00</td>
    </tr>           
  </tbody>
</table>
<br/>
<div class="capa txt_titulo">OBSERVACIONES:</div>
<div class="capa"><? echo $r1['observaciones'];?></div>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo">ANEXO FOTOGRAFICO:</div>

<!-- Proveido de conformidad -->
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">CONFORMIDAD PARA LA BAJA CONTABLE DE EJECUCION DEL CONTRATO DE <? echo $tipo_evento;?> Y DE LA DONACION SUJETO A CARGO</div>
<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">Referencia</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">Contrato N° <? echo numeracion($r1['n_contrato']);?>-<? echo $r1['codigo_iniciativa'];?>-<? echo periodo($r1['f_contrato']);?>-<? echo $r1['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo"><? echo $contratado;?></td>
    <td class="txt_titulo">:</td>
    <td><? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?></td>
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
    <td colspan="2" align="center"><strong><u>LIQUIDACION DEL CONTRATO Y PERFECCIONAMIENTO - <? echo $tipo_evento;?> DE LA DONACION SUJETO A CARGO</u></strong></td>
  </tr>
</table>
<BR>
<div class="capa" align="justify">VISTO EL INFORME DE LIQUIDACION Y PERFECCIONAMIENTO DE LA DONACION CORRESPONDIENTE A LOS DOCUMENTOS DE LA REFERENCIA, ESTANDO A LA CONFORMIDAD DEL RESPONSABLE DEL COMPONENTE Y DEL ADMINISTRADOR, EL SUSCRITO DIRECTOR EJECUTIVO DISPONE A LA ADMINISTRACION LA BAJA CONTABLE POR EL MONTO PERFECCIONADO DEL CONTRATO DE LA REFERENCIA.</div>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="52%">Firma</td>
    <td width="48%">Fecha</td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_ruta.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_liquida" class="secondary button oculto">Finalizar</a>

    
    </td>
  </tr>
</table>
</body>
</html>