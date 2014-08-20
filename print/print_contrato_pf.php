<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT sys_bd_tipo_iniciativa.codigo_iniciativa, 
	ml_pf.nombre AS evento, 
	ml_pf.f_evento, 
	ml_pf.dia,
	ml_pf.lugar, 
	ml_pf.n_participante, 
	ml_pf.objetivo, 
	ml_pf.resultados, 
	ml_pf.n_contrato, 
	ml_pf.f_contrato, 
	ml_pf.n_atf, 
	ml_pf.n_solicitud, 
	ml_pf.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	sys_bd_componente_poa.codigo AS componente, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	ml_pf.aporte_pdss, 
	ml_pf.aporte_org, 
	ml_pf.aporte_otro, 
	ml_pf.f_presentacion, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	org_ficha_organizacion.cod_tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_organizacion.presidente, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	org_ficha_organizacion.tesorero, 
	tesorero.nombre AS nombre1, 
	tesorero.paterno AS paterno1, 
	tesorero.materno AS materno1, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS departamento1, 
	sys_bd_dependencia.provincia AS provincia1, 
	sys_bd_dependencia.ubicacion, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos,
	org_ficha_organizacion.sector	
FROM sys_bd_tipo_iniciativa INNER JOIN ml_pf ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_pf.cod_tipo_iniciativa
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = ml_pf.cod_ifi
	 INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = ml_pf.cod_componente
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = ml_pf.cod_subactividad
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 LEFT OUTER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN org_ficha_usuario tesorero ON org_ficha_organizacion.tesorero = tesorero.n_documento AND org_ficha_organizacion.cod_tipo_doc = tesorero.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = tesorero.n_documento_org
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
WHERE ml_pf.cod_evento='$cod'";
$result=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($result);



$sql="SELECT org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_usuario INNER JOIN org_ficha_directivo ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_directivo.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = org_ficha_directivo.n_documento_org
WHERE org_ficha_directivo.cod_cargo=6 AND
org_ficha_directivo.n_documento_org='".$row['n_documento']."'";
$result=mysql_query($sql) or die (mysql_error());
$row2=mysql_fetch_array($result);





$monto_total=$row['aporte_pdss']+$row['aporte_org'];

$pp_pdss=$row['aporte_pdss']/$monto_total*100;

$pp_org=$row['aporte_org']/$monto_total*100;



$proyecto="<strong>SIERRA SUR II</strong>";

if ($row['cod_tipo_org']==006)
{
	$org="<strong>LA MUNICIPALIDAD</strong>";
}
else
{
$org="<strong>LA ORGANIZACION</strong>";
}

$f_termino=diasFecha($row['f_evento'],$row['dia'],sumar);

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

<div class="capa txt_titulo centrado">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - <? echo $row['codigo_iniciativa'];?> - OL <? echo $row['oficina'];?><br>DE DONACIÓN SUJETO A CARGO, PARA LA PARTICIPACIÓN EN FERIAS</div>
<br>
<div class="capa justificado">
Conste por el presente documento, el Contrato de Donación sujeto a Cargo para la participación en Ferias, que celebran de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II , con RUC Nº 20456188118, en adelante se le denominará <? echo $proyecto;?> representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>, <? echo $row['nombres']." ".$row['apellidos'];?> identificado(a) con DNI. Nº <? echo $row['dni'];?>, con domicilio legal en <? echo $row['direccion'];?> del distrito de <? echo $row['ubicacion'];?>, de la provincia de <? echo $row['provincia1'];?> y departamento de <? echo $row['departamento1'];?> y de la otra parte “<? echo $org;?>” <? echo $row['organizacion'];?>,con <? echo $row['tipo_doc'];?> N° <? echo $row['n_documento'];?>,  con domicilio legal en <? echo $row['sector'];?> del  Distrito de <? echo $row['distrito'];?>, Provincia de <? echo $row['provincia'];?> y Departamento de <? echo $row['departamento'];?> a quien en adelante se le denominará <? echo $org;?>,
<?
if ($row['cod_tipo_org']==006)
{
?>
 representado por su Alcalde <? echo $row2['nombre']." ".$row2['paterno']." ".$row2['materno'];?> identificado(a) con DNI. N° <? echo $row2['dni'];?>
<?
}
else
{
?>
 representada(o) por su Presidente(a), <? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?>identificado con DNI. N° <? echo $row['presidente'];?> y su Tesorero(a) <? echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?>, identificado con DNI. N° <? echo $row['tesorero'];?>
<?
}
?> 
, en los términos y condiciones establecidas en las cláusulas siguientes: 
</div>
<br>
<table width="90%" cellpadding="1" cellspacing="1" border="0" align="center" class="justificado">
	<tr class="txt_titulo">
		<td colspan="2">CLAUSULA PRIMERA: ANTECEDENTES</td>
	</tr>
	<tr>
		<td width="5%" valign="top">1.1</td>
		<td width="95%">"<? echo $proyecto;?>”, es un ente colectivo de naturaleza temporal que tiene como objetivo promover que las familias campesinas y microempresarios incrementen sus ingresos, activos tangibles y valoricen sus conocimientos, organización social y autoestima de su ámbito de acción. Para tal efecto, administra los recursos económicos provenientes del Convenio de Financiación que comprende el Préstamo N° 799 -PE y la donación N° 1158 - PE, firmado entre la República del Perú y el Fondo Internacional de Desarrollo Agrícola - FIDA; dichos recursos son transferidos a “<? echo $proyecto;?>” a través del Programa AGRORURAL del Ministerio de Agricultura-MINAG.</td>
	</tr>
	<tr>
		<td valign="top">1.2</td>
		<td>En el marco de la estrategia de ejecución  de “<? echo $proyecto;?>”, se ha establecido el apoyo a iniciativas rurales de inversión que contribuyan al cumplimiento del objetivo del Proyecto, bajo el enfoque de desarrollo territorial rural; para tal efecto, se promueve que las organizaciones rurales participen de eventos de promoción comercial organizados por los municipios y otras instituciones  públicas y privadas en el ámbito regional, nacional o internacional.</td>
	</tr>	
<?
if ($row['cod_tipo_org']==006)
{
?>	
<tr>
<td valign="top">1.3</td>
<td>"<? echo $proyecto;?>" y "<? echo $org;?>" han suscrito un Convenio de Cooperación Interinstitucional con el objetivo de implementar y cofinanciar actividades conjuntas para el acompañamiento y fortalecimiento de las iniciativas rurales a favor de organizaciones campesinas y otras del ámbito de "<? echo $org;?>". </td>	
</tr>
<?
}
else
{
?>
	<tr>
		<td valign="top">1.3</td>
		<td>“<? echo $org;?>”, es una institución  que promueve el desarrollo de sus asociados  y las familias del  ámbito de su influencia socio-económica, consecuentemente del evento a desarrollarse se enmarca dentro de los objetivos de la   “<? echo $org;?>” y de “<? echo $proyecto;?>”.</td>
	</tr>
<?
}
?>	
	<tr>
		<td>1.4</td>
		<td>“<? echo $org;?>”, ha presentado a la oficina local de <? echo $row['oficina'];?> una propuesta con su solicitud de cofinanciamiento  para la participación en Feria denominada: <? echo $row['evento'];?>, a realizarse el <? echo traducefecha($row['f_evento']);?>, en <? echo $row['lugar'];?></td>
	</tr>
	<tr class="txt_titulo">
		<td colspan="2">CLAUSULA SEGUNDA: OBJETO DEL CONTRATO</td>
	</tr>
	<tr>
		<td colspan="2">Por el presente contrato  “<? echo $proyecto;?>”, transfiere en donación sujeto a cargo, el monto  total de S/ <? echo number_format($row['aporte_pdss'],2);?> (<? echo vuelveletra($row['aporte_pdss']);?> Nuevos soles). Ambos montos serán destinados para financiar la participación en el evento, según el siguiente cuadro:  </td>
	</tr>
</table>

<!-- Tabla con los montos -->
<br>
<table width="90%" border="1" cellpadding="1" cellspacing="1" align="center">
	<tr class="txt_titulo centrado">
		<td width="40%">Nombre del evento</td>
		<td width="20%">Aporte SIERRA SUR II</td>
		<td width="20%">Aporte <? echo $org;?></td>
		<td width="20%">TOTAL</td>
	</tr>
	<tr>
	<td><? echo $row['evento'];?></td>
	<td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($row['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($monto_total,2);?></td>
	</tr>
	<tr>
	<td class="centrado">TOTAL</td>
	<td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
	<td class="derecha"><? echo number_format($row['aporte_org'],2);?></td>
	<td class="derecha"><? echo number_format($monto_total,2);?></td>
	</tr>
	<tr>
	<td class="centrado">%</td>
	<td class="derecha"><? echo number_format($pp_pdss,2);?></td>
	<td class="derecha"><? echo number_format($pp_org,2);?></td>
	<td class="derecha">100.00</td>
	</tr>	
</table>
<br>
<!-- Fin de tabla con los montos -->

<div class="capa txt_titulo">
	CLAUSULA TERCERA: PLAZO DEL CONTRATO
</div>
<div class="capa justificado" align="center">
El plazo establecido por las partes  para la ejecución del presente contrato es <? echo $row['dia'];?> días; se inicia  el <? echo traducefecha($row['f_evento']);?> y culmina el <? echo traducefecha($f_termino);?> - Este plazo incluye vías acciones de liquidación del Contrato  y perfeccionamiento  de la donación.
</div>
<br>
<div class="capa txt_titulo">
	CLAUSULA CUARTA: DE LA TRANSFERENCIA  Y DESEMBOLSO DE LOS FONDOS
</div>
<div class="capa justificado">El aporte de “<? echo $proyecto;?>” será transferido a “<? echo $org;?>” en un desembolso  </div>
<br>
<div class="capa txt_titulo">CLAUSULA QUINTA: OBLIGACIONES DE LAS PARTES</div>
<div class="capa justificado">5.1 “<? echo $org;?>” se obliga a:</div>
<div class="capa">
	<ol type="a">
		<li>Alcanzar a “<? echo $proyecto;?>” el documento que demuestre la disponibilidad de recursos con cargo al cual apotará los fondos de  cofinanciamiento.</li>
  <li>Presentar un Informe Técnico -  Administrativo que incluya la ficha resumen del evento y la rendición de cuentas acompañada de documentos y comprobantes de pago a nombre de <? echo $org;?>, con lo que se acreditará los gastos realizados con el aporte de “<? echo $proyecto;?>”.  El mencionado informe deberá ser presentado en un plazo estipulado ren la cláusula tercera </li>
<li>Permitir el seguimiento, evaluación y/o verificación del cumplimiento del presente contrato, especialmente por parte del personal de “<? echo $proyecto;?>”</li>
<li>No utilizar no contar con la participación de menores de edad, como mano de obra, en la ejecución del evento de  conformidad a la legislación nacional vigente y los cenvenios internacionales existentes sobre la materia.</li>
<li>Ser depositarios de la documentación legal contable y técnica que permitan el seguimiento, evaluación y/o verificación del cumplimiento del presente contrato por parte del personal del “<? echo $proyecto;?>”, AGRORURAL, FIDA  y de los Organos del Sistema nacional de Control, por un período mínimo de 5 años después del cierre liquidación de “<? echo $proyecto;?>”.</li>
	</ol>
</div>
<div class="capa justificado">5.2 “<? echo $proyecto;?>” se obliga a:</div>
<div class="capa justificado">
	<ol type="a">
		<li>Efectuar el desembolso que corresponda, dependiendo de su disponibilidad de recursos económicos.</li>
<li>Verificar y hacer cumplir los cargos que permitan la liquidación del presente contrato y el prefeccionamiento de la donación.</li>
	</ol>
</div>
<div class="capa txt_titulo">CLAUSULA SEXTA: DEL CARGO Y PERFECCIONAMIENTO DEL CONTRATO</div>
<div class="capa justificado"> “<? echo $proyecto;?>”, establece a “<? echo $org;?>” como  cargo de la presente donación el  cumplimiento del objeto del presente contrato, la responsabilidad y transparencia en el buen manejo de los fondos transferidos y de las obligaciones establecidas en la Cláusula Quinta. El contrato quedará liquidado y la donación perfeccionada con el informe favorable de “<? echo $proyecto;?>” que contiene los documentos mencionados en el punto 5.1, inciso “B” de la cláusula anterior  </div>
<br>
<div class="capa txt_titulo">CLÁUSULA SEPTIMA: RESOLUCIÓN DEL CONTRATO</div>
<div class="capa justificado">
	<ol type="a">
		<li>Incumplimiento de las obligaciones establecidas en el presente contrato por ambas partes.</li>
		<li>Disolución de <? echo $org;?>.</li>
		<li>Presentación de información falsa ante <? echo $proyecto;?> por parte de <? echo $org;?>.</li>
	</ol>
</div>
<div class="capa txt_titulo">CLÁUSULA OCTAVA:  DE LAS SANCIONES.</div>
<div class="capa justificado">Conllevan sanciones en la aplicación del presente Contrato:
	<ol type="1">
		<li>En caso de resolución por incumplimiento de las partes, la parte agraviada iniciará las acciones penales y civiles a que hubieren lugar. Si la parte agraviada es <? echo $proyecto;?>, éste se reserva el derecho de comunicar por cualquier medio de tal hecho a la sociedad civil del ámbito de su acción.</li>
		<li>En caso “<? echo $org;?>” haya efectuado un uso inapropiado o desvío de fondos para otros fines no previstos en el presente Contrato  o presente información falsa; <? echo $proyecto;?> exigirá  la devolución de los fondos desembolsados a favor de  “<? echo $org;?>”. Para levantar esta medida “<? echo $org;?>” deberá comunicar y acreditar a  “<? echo $proyecto;?>”  que ha implementado las medidas correctivas y aplicado las sanciones a los responsables, si el caso lo amerita.</li>
		<li>En caso de no participación en  el evento objeto del presente contrato <? echo $org;?>, devolverá a <? echo $proyecto;?> los fondos transferidos.</li>
	</ol>
</div>
<div class="capa txt_titulo">CLAUSULA NOVENA: SITUACIONES NO PREVISTAS.</div>
<div class="capa justificado">
	En caso de ocurrir situaciones no previstas en el presente Contrato o que , estando previstas escapen al control directo de alguna  las partes , mediante acuerdo mutuo, determinarán las medidas correctivas. Los acuerdos que se deriven del tratamiento de un caso de esta naturaleza, serán expresados en un Acta, Adenda u otro instrumento, según el caso lo amerite.
</div>
<br>
<div class="capa txt_titulo">CLAUSULA DECIMA:  COMPETENCIA TERRITORIAL Y JURISDICCIONAL.</div>
<div class="capa justificado">Para efectos de cualquier controversia que se genere con motivo de la celebración y ejecución de este contrato, las partes se someten a la competencia territorial de los jueces, tribunales y/o Jurisdicción Arbitral de la ciudad de AREQUIPA, en razón de la  Unidad Ejecutora de  “<? echo $proyecto;?>” se encuentra ubicada en el distrito de Quequeña en la  provincia de Arequipa.</div>
<br>
<div class="capa txt_titulo">CLAUSULA DECIMO PRIMERA: DOMICILIO</div>
<div class="capa justificado">Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrsasto, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito. </div>
<br>
<div class="capa txt_titulo">CLAUSULA DECIMO SEGUNDA: APLICACIÓN SUPLETORIA DE LA LEY</div>
<div class="capa justificado">
	En lo no previsto por las partes del presente contrato, ambas se someten a lo establecido por las normas del Código Civil y demás del sistema jurídico que resulten aplicables.
	
<p><br></p>	
<p>En fe de lo acordado, suscribimos el presente contrato en tres ejemplares, en la localidad de <? echo $row['ubicacion'];?> siendo hoy <? echo traducefecha($row['f_contrato']);?></p>
</div>

<p><br></p>


<?
if ($row['cod_tipo_org']==006)
{
?>

<table width="90%" cellpadding="4" cellspacing="4" align="center" border="0" class="centrado">
<tr>
	<td width="30%">___________________</td>
	<td width="35%"></td>
	<td width="30%">___________________</td>
</tr>
<tr>
	<td><? echo $row2['nombre']." ".$row2['paterno']." ".$row2['materno'];?><br>ALCALDE DNI Nº <? echo $row2['dni'];?></td>
	<td></td>
	<td><? echo $row['nombres']." ".$row['apellidos'];?><br> JEFE DE LAS OFICINA LOCAL DE <? echo $row['oficina'];?><br><? echo $proyecto;?></td>
</tr>
</table>



<?
}
else
{
?>
<table width="90%" cellpadding="4" cellspacing="4" align="center" border="0" class="centrado">
	<tr>
		<td width="30%">___________________</td>
		<td width="35%"></td>
		<td width="30%">___________________</td>
	</tr>
	<tr>
		<td><? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?><br>PRESIDENTE DNI Nº <? echo $row['presidente'];?><br><? echo $org;?></td>
		<td></td>
		<td><? echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?><br>TESORERO DNI Nº <? echo $row['tesorero'];?><br><? echo $org;?></td>
	</tr>	
	<tr>
		<td></td>
		<td>___________________</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td><? echo $row['nombres']." ".$row['apellidos'];?><br> JEFE DE LAS OFICINA LOCAL DE <? echo $row['oficina'];?><br><? echo $proyecto;?></td>
		<td></td>
	</tr>
</table>
<?
}
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> –  <? echo periodo($row['f_contrato']);?> - <? echo $row['oficina'];?><BR>
A "<? echo $org;?>" PARA EL COFINANCIAMIENTO DE PARTICIPACION EN FERIAS</div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo"><? echo $org;?></td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad Financiera </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['banco'];?></td>
  </tr>
  		<tr>
			<td class="txt_titulo">N° de cuenta </td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2"><? echo $row['n_cuenta'];?></td>
		</tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - <? echo $row['codigo_iniciativa'];?> – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Codigo POA </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['poa'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Categoria de gasto</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['categoria'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: 100.00</td>
    <td width="31%">RO: 0.00</td>
  </tr>
</table>
<br>

<div class="capa txt_titulo" align="left">DETALLE DEL DESEMBOLSO</div>


<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo">
    <td width="42%" align="center">ACTIVIDADES</td>
    <td width="7%" align="center">% A DESEMBOLSAR </td>
    <td width="19%" align="center">MONTO A TRANSFERIR (S/.)</td>
    <td width="16%" align="center">CODIGO POA</td>
    <td width="16%" align="center">CATEGORIA DE GASTO</td>
  </tr>
  <tr>
    <td><? echo $row['evento'];?></td>
    <td class="derecha">100.00</td>
    <td align="right"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td align="center"><? echo $row['poa'];?></td>
    <td align="center"><? echo $row['categoria'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<br></br>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Solicitud de Cofinanciamiento presentada a SIERRA SUR II</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Propuesta de cofinanciamiento</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de los DNI de los responsables del Contrato</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato  de Donación sujeto a Cargo entre "<? echo $proyecto;?>" y "<? echo $org;?>"</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Documento que demuestre la disponibilidad de recursos con cargo al cual aportará los fondos de cofinanciamiento para la ejecución del evento. </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>
<BR>
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_contrato']);?></div>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>SOLICITUD DE DESEMBOLSO DE FONDOS N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?></u></div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="23%" class="txt_titulo">A</td>
    <td width="1%">:</td>
    <td width="76%">C.P.C JUAN SALAS ACOSTA </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="76%">ADMINISTRADOR DEL NEC PDSS II </td>
  </tr>
  <tr>
    <td class="txt_titulo">CC</td>
    <td width="1%">:</td>
    <td width="76%">Responsable de Componentes </td>
  </tr>
  <tr>
    <td class="txt_titulo">ASUNTO</td>
    <td width="1%">:</td>
    <td width="76%">Desembolso  de Evento de Promoción Comercial </td>
  </tr>
  <tr>
    <td><? echo $org;?></td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - <? echo $row['codigo_iniciativa'];?> – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_contrato']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al primer desembolso de las iniciativas correspondientes a las siguientes organizaciones que en resumen son las siguientes:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="19%">Nombre de <? echo $org;?> </td>
    <td width="20%">Nombre del Evento</td>
    <td width="7%">Tipo de Iniciativa </td>
    <td width="9%">ATF N° </td>
    <td width="20%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>
 
  <tr>
    <td><? echo $row['organizacion'];?></td>
    <td><? echo $row['evento'];?></td>
    <td class="centrado"><? echo $row['codigo_iniciativa'];?></td>
    <td class="centrado"><? echo numeracion($row['n_atf'])."-".periodo($row['f_contrato']);?></td>
    <td><? echo $row['banco'];?></td>
    <td class="centrado"><? echo $row['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
  
  <tr>
    <td colspan="6">TOTAL</td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>Se adjunta a la presente la autorización de transferencia de fondos. </td>
  </tr>
  <tr>
    <td><br>
    Atentamente,</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="29%">&nbsp;</td>
    <td width="36%"><hr></td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['nombres']." ".$row['apellidos']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_pf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Retornar al panel principal</a>

</td>
  </tr>
</table>

</body>
</html>
