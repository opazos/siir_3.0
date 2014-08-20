<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//Info del contrato
$sql="SELECT sys_bd_tipo_iniciativa.codigo_iniciativa, 
  ml_promocion_c.nombre AS evento, 
  ml_promocion_c.f_inicio, 
  ml_promocion_c.f_termino, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  ml_promocion_c.lugar, 
  ml_promocion_c.f_contrato, 
  ml_promocion_c.n_contrato, 
  ml_promocion_c.n_atf, 
  ml_promocion_c.n_solicitud, 
  sys_bd_componente_poa.codigo AS componente, 
  sys_bd_subactividad_poa.codigo AS poa, 
  ml_promocion_c.aporte_pdss, 
  ml_promocion_c.aporte_org, 
  ml_promocion_c.aporte_otro, 
  sys_bd_ifi.descripcion AS banco, 
  ml_promocion_c.n_cuenta, 
  ml_promocion_c.f_presentacion, 
  sys_bd_tipo_doc.descripcion AS tipo_doc, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre, 
  org_ficha_organizacion.cod_tipo_org, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  dep1.nombre AS dep, 
  prov1.nombre AS prov, 
  dist1.nombre AS dist, 
  org_ficha_organizacion.sector, 
  org_ficha_organizacion.cod_dependencia, 
  sys_bd_dependencia.nombre AS oficina, 
  org_ficha_organizacion.presidente, 
  presidente.nombre AS nombre_1, 
  presidente.paterno AS paterno_1, 
  presidente.materno AS materno_1, 
  org_ficha_organizacion.tesorero, 
  tesorero.nombre AS nombre_2, 
  tesorero.paterno AS paterno_2, 
  tesorero.materno AS materno_2, 
  ml_promocion_c.cod_estado_iniciativa, 
  sys_bd_categoria_poa.codigo, 
  sys_bd_categoria_poa.nombre AS categoria, 
  ml_promocion_c.fte_fida, 
  ml_promocion_c.fte_ro
FROM ml_promocion_c INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_promocion_c.cod_tipo_iniciativa
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = ml_promocion_c.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = ml_promocion_c.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = ml_promocion_c.cod_dist
   INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = ml_promocion_c.cod_componente
   INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = ml_promocion_c.cod_subactividad
   INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = ml_promocion_c.cod_ifi
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN sys_bd_departamento dep1 ON dep1.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_provincia prov1 ON prov1.cod = org_ficha_organizacion.cod_prov
   INNER JOIN sys_bd_distrito dist1 ON dist1.cod = org_ficha_organizacion.cod_dist
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   LEFT OUTER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
   LEFT OUTER JOIN org_ficha_usuario tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
   LEFT OUTER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = ml_promocion_c.cod_categoria
WHERE ml_promocion_c.cod_evento='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$total_evento=$row['aporte_pdss']+$row['aporte_org'];

//Busco al alcalde
$sql="SELECT
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno
FROM
ml_promocion_c
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
ml_promocion_c.cod_evento='$cod' AND
org_ficha_directivo.cod_cargo=6";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


//Busco al Gerente
$sql="SELECT
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno
FROM
ml_promocion_c
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE
ml_promocion_c.cod_evento='$cod' AND
org_ficha_directivo.cod_cargo=7";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//Busco la oficina
$sql="SELECT
sys_bd_dependencia.departamento,
sys_bd_dependencia.provincia,
sys_bd_dependencia.ubicacion,
sys_bd_dependencia.direccion,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_personal.n_documento
FROM
sys_bd_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE
sys_bd_dependencia.cod_dependencia='".$row['cod_dependencia']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$proyecto="<strong>SIERRA SUR II</strong>";

if ($row['cod_tipo_org']==6)
{
$org="<strong>LA MUNICIPALIDAD</strong>";
}
else
{
$org="<strong>LA ORGANIZACIÓN</strong>";
}

//Funcion para sumar 3 meses a la fecha
$fecha_db = $row['f_contrato'];
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1],$fecha_db[2]+90,$fecha_db[0]);
$fecha = date("Y-m-d", $fecha_cambiada);
$fecha_termino = $fecha;


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
<DIV class="capa centrado txt_titulo">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – <? echo $row['codigo_iniciativa'];?> – OL <? echo $row['oficina'];?><br>
DE DONACIÓN  SUJETO A CARGO PARA LA EJECUCIÓN DEL EVENTO DE PROMOCION COMERCIAL PROMOVIDO POR LA <? echo $row['nombre'];?>
</DIV>
<br>

<?php 
if ($row['cod_estado_iniciativa']==000)
{
?>
<center>
<div class="capa_borde centrado error gran_titulo" >CONTRATO ANULADO</div>
</center>
<?php 
}
?>
<br>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="justificado">
    <td colspan="2">Conste por el presente documento el Contrato de Donación sujeto a Cargo para la ejecución del evento de Promoción Comercial: <? echo $row['evento'];?>, que celebran de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II con RUC Nº 20456188118, en adelante denominado “<? echo $proyecto;?>”, representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>  <? echo $r2['nombre']." ".$r2['apellido'];?>, identificado  con DNI Nº <? echo $r2['n_documento'];?>, con domicilio legal en <? echo $r2['direccion'];?> del distrito de <? echo $r2['ubicacion'];?>, de la  Provincia de <? echo $r2['provincia'];?> y  Departamento de <? echo $r2['departamento'];?>; y de otra parte "<? echo $org;?>" <? echo $row['nombre'];?> con <? echo $row['tipo_doc'];?> Nº <? echo $row['n_documento'];?>, con domicilio legal  en <? echo $row['sector'];?> del  Distrito de <? echo $row['dist'];?>, Provincia de <? echo $row['prov'];?> y Departamento de <? echo $row['dep'];?>, a quien en adelante se le denominará "<? echo $org;?>", 
<?
if($row['cod_tipo_org']==6)
{
?>	
representada por su Alcalde <? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?>, identificado con DNI. Nº <? echo $r1['n_documento'];?>, en los términos y condiciones establecidos en las cláusulas siguientes:
<?
}
elseif ($row['cod_tipo_org']==14)
{
?>
representada(o) por su Gerente(a) Sr(a) <? echo $r3['nombre']." ".$r3['paterno']." ".$r3['materno'];?>, identificado(a) con DNI. Nº <? echo $r3['n_documento'];?>, en los términos y condiciones establecidos en las cláusulas siguientes:
<?php 
}
else
{
?>
representada(o) por su Presidente(a) Sr(a) <? echo $row['nombre_1']." ".$row['paterno_1']." ".$row['materno_1'];?>, identificado(a) con DNI. Nº <? echo $row['presidente'];?>, y su Tesorero(a) Sr(a) <? echo $row['nombre_2']." ".$row['paterno_2']." ".$row['materno_2'];?>, identificado(a) con DNI. Nº <? echo $row['tesorero'];?>, en los términos y condiciones establecidos en las cláusulas siguientes:
<?
}
?></td>
  </tr>
  <tr class="justificado">
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA PRIMERA</u>: ANTECEDENTES</td>
  </tr>
  <tr class="justificado">
    <td width="4%" valign="top">1.1</td>
    <td width="96%">"<? echo $proyecto;?>” es un ente colectivo de naturaleza temporal que tiene como objetivo promover, dentro de su ámbito de acción, que las familias campesinas y microempresarios incrementen sus ingresos, activos tangibles y valoricen sus conocimientos, organización social y autoestima. Para tal efecto, administra los recursos económicos provenientes del Convenio de Financiación que comprende el Préstamo N° 799 –PE y la Donación N° 1158 – PE, firmado entre la República del Perú y el Fondo Internacional de Desarrollo Agrícola – FIDA, dichos recursos son transferidos a “<? echo $proyecto;?>” a través del Programa AGRORURAL del Ministerio de Agricultura-MINAG.</td>
  </tr>
  <tr class="justificado">
    <td valign="top">1.2</td>
    <td width="96%">En el marco de la estrategia de ejecución de "<? echo $proyecto;?>", se ha establecido el apoyo a iniciativas rurales de inversión que contribuyan al cumplimiento del objetivo del Proyecto, bajo el enfoque de desarrollo territorial rural; para tal efecto, se promueve que las organizaciones rurales participen de eventos de promoción comercial organizados por los municipios y otras instituciones públicas y privadas en el ámbito local, regional, nacional y/o internacional.</td>
  </tr>
  <tr class="justificado">
    <td valign="top">1.3</td>
    <td width="96%">
	<?
	if ($row['cod_tipo_org']==6)
	{
	?>
	”<? echo $proyecto;?>” y “<? echo $org;?>” han suscrito un Convenio de Cooperación Interinstitucional con el objetivo de  implementar y cofinanciar actividades conjuntas para el acompañamiento y fortalecimiento de las iniciativas rurales a favor de organizaciones campesinas y otras del  ámbito  de “<? echo $org;?>”.
	<?
	}
	else
	{
	?>
	
	"<? echo $org;?>" es una Organización que promueve el desarrollo de sus asociados y las familias del ámbito de su influencia socio – económica, consecuentemente el evento a desarrollarse se enmarca dentro de los objetivos de “<? echo $org;?>” y de “<? echo $proyecto;?>”.
	<?
	}
	?>	</td>
  </tr>
  <tr class="justificado">
    <td valign="top">1.4</td>
    <td width="96%">"<? echo $org;?>" ha presentado a la Oficina Local de <? echo $row['oficina'];?> un presupuesto con su solicitud de cofinanciamiento para la ejecución del evento de Promoción Comercial – PC denominado:<? echo $row['evento'];?>, que se realizará  del <? echo traducefecha($row['f_inicio']);?> al <? echo traducefecha($row['f_termino']);?>, en <? echo $row['lugar'];?> del distrito <? echo $row['distrito'];?>, provincia de <? echo $row['provincia'];?> y cuenta con la autorización de la autoridad competente para la ejecución del evento. </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA SEGUNDA</u>: OBJETO DEL CONTRATO</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Por el presente contrato “<? echo $proyecto;?>” transfiere en donación sujeto a cargo, el monto total de S/. <? echo number_format($row['aporte_pdss'],2);?> (<? echo vuelveletra($row['aporte_pdss']);?> Nuevos Soles) a "<? echo $org;?>", la misma que se compromete a aportar el monto total  de S/. <? echo number_format($row['aporte_org'],2);?>(<? echo vuelveletra($row['aporte_org']);?> Nuevos Soles). Ambos montos serán destinados para financiar la ejecución del evento de promoción comercial según el siguiente cuadro:</td>
  </tr>
</table>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="49%">NOMBRE DEL EVENTO DE PROMOCIÓN COMERCIAL </td>
    <td width="17%">APORTE SIERRA SUR II (S/.) </td>
    <td width="17%">APORTE <? echo $org;?> (S/.)</td>
    <td width="17%">TOTAL (S/.) </td>
  </tr>
  <tr>
    <td><? echo $row['evento'];?></td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($row['aporte_org'],2);?></td>
    <td class="derecha"><? echo number_format($total_evento,2);?></td>
  </tr>
  <tr>
    <td>TOTAL</td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($row['aporte_org'],2);?></td>
    <td class="derecha"><? echo number_format($total_evento,2);?></td>
  </tr>
  <tr>
    <td>%</td>
    <td class="derecha"><? $pp_pdss=$row['aporte_pdss']/$total_evento*100; echo number_format($pp_pdss,2);?></td>
    <td class="derecha"><? $pp_org=$row['aporte_org']/$total_evento*100; echo number_format($pp_org,2);?></td>
    <td class="derecha"><? echo number_format($pp_pdss+$pp_org,2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA TERCERA</u>: PLAZO DEL CONTRATO.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">El plazo establecido por las partes para la ejecución del presente contrato es de 3 meses; se inicia el <? echo traducefecha($row['f_contrato']);?> y culmina el <? echo traducefecha($fecha_termino);?>. Este plazo incluye  las acciones de liquidación del Contrato y perfeccionamiento de la donación. </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA CUARTA</u>: DE LA TRANSFERENCIA Y DESEMBOLSO  DE LOS FONDOS</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">El aporte de “<? echo $proyecto;?>” será transferido a "<? echo $org;?>" en un desembolso.</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA QUINTA</u>: OBLIGACIONES DE LAS PARTES.</td>
  </tr>
  <tr class="justificado">
    <td width="4%" valign="top">5.1</td>
    <td width="96%">"<? echo $org;?>" se obliga a:</td>
  </tr>
  <tr class="justificado">
    <td valign="top">&nbsp;</td>
    <td width="96%">
	<ol type="a">
	<li>Alcanzar a “<? echo $proyecto;?>” el documento que demuestre la disponibilidad de recursos con cargo al cual aportará los fondos de cofinanciamiento para la ejecución del evento.</li>
<li>Presentar el informe Técnico - Económico del evento, en el plazo estipulado en la Cláusula Tercera, el que estará acompañado de las fotocopias de la documentación que acredite los gastos realizados con el aporte de “<? echo $proyecto;?>”.</li>
<li>Permitir el seguimiento, evaluación y/o verificación del cumplimiento del presente contrato, especialmente por parte del personal de “<? echo $proyecto;?>”.</li>
<li>No utilizar y no contar con la participación de menores de edad, como mano de obra, en la ejecución del evento de conformidad a la legislación nacional vigente y los Convenios Internacionales existentes sobre la materia.</li>
<li>Ser depositarios de la documentación legal, contable y técnica que permitan el seguimiento, evaluación y/o verificación del cumplimiento del presente contrato por parte del personal de "<? echo $proyecto;?>" , AGRORURAL, FIDA y de los Órganos del Sistema Nacional de Control, por un período mínimo de 5 años después del cierre y liquidación de “<? echo $proyecto;?>”</li>
</ol></td>
  </tr>
  <tr class="justificado">
    <td valign="top">5.2</td>
    <td width="96%">“<? echo $proyecto;?>” se obliga a:</td>
  </tr>
  <tr class="justificado">
    <td valign="top">&nbsp;</td>
    <td width="96%">
	<ol type="a">
	<li>Efectuar el desembolso que le corresponda, dependiendo de su disponibilidad de recursos económicos.</li>
<li>Verificar y hacer cumplir los cargos que permitan la liquidación del presente contrato y el perfeccionamiento de la donación.</li>
</ol></td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA SEXTA</u>: DEL CARGO, LIQUIDACIÓN Y PERFECCIONAMIENTO DEL CONTRATO.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">“<? echo $proyecto;?>” establece a "<? echo $org;?>" como cargo de la presente donación el cumplimiento del objeto del presente contrato, la responsabilidad y transparencia en el buen manejo de los fondos transferidos  y de las obligaciones establecidas en la Cláusula Quinta. El contrato quedará liquidado y la donación perfeccionada con el informe favorable de la oficina local de <? echo $row['oficina'];?> que contiene los documentos mencionados en el punto 5.1, inciso “<b>b</b>” de la cláusula  anterior.  </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA SEPTIMA:</u> RESOLUCIÓN DEL CONTRATO.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">El presente Contrato se resolverá automáticamente, por: </td>
  </tr>
  <tr class="justificado">
    <td colspan="2">
	<ol type="1">
	<li>Incumplimiento de las obligaciones establecidas en el presente contrato por alguna de las partes.</li>
<li>Mutuo acuerdo de las partes.</li>
<li>Presentación de información falsa ante "<? echo $proyecto;?>" por parte de "<? echo $org;?>".</li>
</ol></td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA OCTAVA</u>: DE LAS SANCIONES.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Conllevan sanciones en la aplicación del presente Contrato:</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">
	<ol type="1">
	<li>En caso de resolución por incumplimiento de alguna de las partes, la parte agraviada iniciará las acciones penales y civiles a que hubieren lugar. Si la parte agraviada es “<? echo $proyecto;?>”, éste se reserva el derecho de comunicar por cualquier medio de tal hecho a la sociedad civil del ámbito de su acción.</li>
<li>En caso que "<? echo $org;?>" haya efectuado un uso inapropiado o desvío de fondos para otros fines no previstos en el presente Contrato o presente información falsa, “<? echo $proyecto;?>” exigirá la devolución de los fondos desembolsados a favor de "<? echo $org;?>". Para levantar esta medida "<? echo $org;?>" deberá comunicar y acreditar a "<? echo $proyecto;?>" que ha implementado las medidas correctivas y aplicado las sanciones a los responsables, si el caso lo amerita.</li>
<li>En caso no se realice  el evento, "<? echo $org;?>" devolverá a “<? echo $proyecto;?>” los fondos transferidos.</li>
</ol></td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA NOVENA</u>: SITUACIONES NO PREVISTAS</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">En caso de ocurrir situaciones no previstas en el presente Contrato o que, estando previstas, escapen al control directo de alguna de las partes, mediante acuerdo mutuo se determinarán las medidas correctivas. Los acuerdos que se deriven del tratamiento de un caso de esta naturaleza, serán expresados en un Acta, Adenda u otro instrumento, según el caso lo amerite.</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA DECIMA</u>: COMPETENCIA TERRITORIAL y JURISDICCIONAL</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Para efectos de cualquier controversia que se genere con motivo de la celebración y ejecución de este contrato, las partes se someten a la competencia territorial de los jueces, tribunales y/o Jurisdicción Arbitral de la ciudad de AREQUIPA, en razón a que la Unidad Ejecutora de “<? echo $proyecto;?>” se encuentra ubicada en el distrito de Quequeña de la provincia de Arequipa.</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA DECIMO PRIMERA</u>: DOMICILIO</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrato, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito.</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2"><u>CLAUSULA DECIMO SEGUNDA</u>: APLICACIÓN SUPLETORIA DE LA LEY</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">En lo no previsto por las partes en el presente contrato, ambas se someten a lo establecido por las normas del Código Civil y demás del sistema jurídico que resulten aplicables.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">En fe de lo acordado, suscribimos el presente contrato en tres ejemplares, en la localidad  de <? echo $row['oficina'];?>, el <? echo traducefecha($row['f_contrato']);?> </td>
  </tr>
</table>
<p>&nbsp;</p>
<?
if ($row['cod_tipo_org']==6)
{
?>	
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4"  class="centrado txt_titulo">
  <tr>
    <td width="31%">_________________________</td>
    <td width="38%">&nbsp;</td>
    <td width="31%">_________________________</td>
  </tr>
  <tr>
    <td><? echo $r2['nombre']." ".$r2['apellido'];?><br>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></td>
    <td>&nbsp;</td>
    <td><? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?><br>ALCALDE<br>DNI N°: <? echo $r1['n_documento'];?></td>
  </tr>
</table>
<?
}
elseif($row['cod_tipo_org']==14)
{
?>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4"  class="centrado txt_titulo">
  <tr>
    <td width="31%">_________________________</td>
    <td width="38%">&nbsp;</td>
    <td width="31%">_________________________</td>
  </tr>
  <tr>
    <td><? echo $r2['nombre']." ".$r2['apellido'];?><br>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></td>
    <td>&nbsp;</td>
    <td><? echo $r3['nombre']." ".$r3['paterno']." ".$r3['materno'];?><br>GERENTE<br>DNI N°: <? echo $r3['n_documento'];?></td>
  </tr>
</table>
<?php 
}
else
{
?>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4"  class="centrado txt_titulo">
  <tr>
    <td width="31%">_________________________</td>
    <td width="38%">&nbsp;</td>
    <td width="31%">_________________________</td>
  </tr>
  <tr>
    <td><? echo $row[nombre_1]." ".$row['paterno_1']." ".$row['materno_1'];?><br>PRESIDENTE<br>DNI N°:<? echo $row['presidente'];?></td>
    <td>&nbsp;</td>
    <td><? echo $row[nombre_2]." ".$row['paterno_2']." ".$row['materno_2'];?><br>TESORERO<br>DNI N°:<? echo $row['tesorero'];?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>_________________________</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><? echo $r2['nombre']." ".$r2['apellido'];?><br>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<?
}
?>
<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> –  <? echo periodo($row['f_contrato']);?> - <? echo $row['oficina'];?><BR>
A "<? echo $org;?>" PARA EL COFINANCIAMIENTO DE PROMOCIÓN COMERCIAL </div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo"><? echo $org;?></td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['nombre'];?></td>
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
    <td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - PC – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Codigo POA </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['poa'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Categoria de gasto</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['codigo']."-".$row['categoria'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: <? echo number_format($row['fte_fida'],2);?></td>
    <td width="31%">RO: <? echo number_format($row['fte_ro'],2);?></td>
  </tr>
</table>
<br/>
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
    <td align="center"><? echo $row['codigo'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>
<br/>
<div class="capa txt_titulo">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
<tr>
	<td>MONTO DE APORTE</td>
	<td class="derecha"><? echo number_format($row['aporte_org'],2);?></td>
</tr>
<?
$total=$row['aporte_pdss']+$row['aporte_org'];
@$porg=($row['aporte_org']/$total)*100;
?>
<tr>
	<td>%</td>
	<td class="derecha"><? echo number_format(@$porg,2);?></td>
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
    <td>Copia de documento que acredite autorización de la autoridad competente para la ejecución del evento.</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de los DNI de los responsables del Contrato</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato  de Donación con  Cargo entre "<? echo $proyecto;?>" y "<? echo $org;?>"</td>
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
    <td align="center"><? echo $r2['nombre']." ".$r2['apellido']."<br> JEFE DE OFICINA LOCAL";?></td>
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
    <td width="76%"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - PC – OL <? echo $row['oficina'];?></td>
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
    <td width="20%">Nombre de la Iniciativa </td>
    <td width="7%">Tipo de Iniciativa </td>
    <td width="9%">ATF N° </td>
    <td width="20%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>
 
  <tr>
    <td><? echo $row['nombre'];?></td>
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
    <td align="center"><? echo $r2['nombre']." ".$r2['apellido']."<br> JEFE DE OFICINA LOCAL";?></td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
<button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_pc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Retornar al panel principal</a>

</td>
  </tr>
</table>
</body>
</html>