<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT
sys_bd_tipo_iniciativa.codigo_iniciativa,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
pit_bd_ficha_idl.denominacion,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_tipo_idl.descripcion AS tipo_idl,
pit_bd_ficha_idl.f_inicio,
pit_bd_ficha_idl.f_termino,
pit_bd_ficha_idl.n_cuenta,
sys_bd_ifi.descripcion AS banco,
pit_bd_ficha_idl.n_contrato,
pit_bd_ficha_idl.f_contrato,
pit_bd_ficha_idl.n_solicitud,
pit_bd_ficha_idl.aporte_pdss,
pit_bd_ficha_idl.aporte_org,
pit_bd_ficha_idl.aporte_otro,
pit_bd_ficha_idl.fuente_fida,
pit_bd_ficha_idl.fuente_ro,
pit_bd_ficha_idl.primer_pago,
pit_bd_ficha_idl.segundo_pago,
clar_atf_idl.n_atf,
sys_bd_componente_poa.codigo AS componente,
sys_bd_subactividad_poa.codigo AS poa,
sys_bd_categoria_poa.codigo AS categoria,
clar_atf_idl.monto_desembolsado,
clar_atf_idl.saldo,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
org_ficha_organizacion.sector,
sys_bd_dependencia.nombre AS oficina,
org_ficha_organizacion.cod_dependencia,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.presidente,
presidente.nombre AS nombre_1,
presidente.paterno AS paterno_1,
presidente.materno AS materno_1,
org_ficha_organizacion.tesorero,
tesorero.nombre AS nombre_2,
tesorero.paterno AS paterno_2,
tesorero.materno AS materno_2,
clar_bd_evento_clar.f_evento,
clar_bd_evento_clar.nombre AS clar,
clar_bd_acta.n_acta,
org_ficha_organizacion.cod_tipo_org,
pit_bd_ficha_idl.cod_estado_iniciativa
FROM
pit_bd_ficha_idl
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_idl.cod_tipo_iniciativa
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_tipo_idl ON sys_bd_tipo_idl.cod_tipo_idl = pit_bd_ficha_idl.cod_tipo_idl
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_idl.cod_ifi
INNER JOIN clar_atf_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_atf_idl.cod_ficha_idl
INNER JOIN sys_bd_componente_poa ON sys_bd_componente_poa.cod = clar_atf_idl.cod_componente
INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = clar_atf_idl.cod_poa
INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
LEFT JOIN org_ficha_usuario AS presidente ON presidente.n_documento = org_ficha_organizacion.presidente
LEFT JOIN org_ficha_usuario AS tesorero ON tesorero.n_documento = org_ficha_organizacion.tesorero
INNER JOIN clar_bd_ficha_idl ON clar_bd_ficha_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
INNER JOIN clar_bd_evento_clar ON clar_bd_ficha_idl.cod_clar = clar_bd_evento_clar.cod_clar
INNER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
WHERE
pit_bd_ficha_idl.cod_ficha_idl = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$total_idl=$row['aporte_pdss']+$row['aporte_org'];

//Busco al alcalde
$sql="SELECT
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
sys_bd_cargo_directivo.descripcion,
org_ficha_usuario.n_documento
FROM
pit_bd_ficha_idl
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_idl.n_documento_org
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_directivo.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = org_ficha_directivo.n_documento_org
INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
WHERE
pit_bd_ficha_idl.cod_ficha_idl = '$cod' AND
org_ficha_directivo.cod_cargo = 6";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);


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
<DIV class="capa centrado txt_titulo">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - IDL – OL <? echo $row['oficina'];?><br>
DE DONACIÓN SUJETO A CARGO,  PARA LA EJECUCION  DE LA INVERSION PARA EL DESARROLLO LOCAL DE LA <? echo $row['tipo_org'];?> "<? echo $row['nombre'];?>"
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
    <td colspan="2">Conste por el presente documento el Contrato de Donación sujeto a Cargo para la Ejecución de la Inversión para el Desarrollo Local: <? echo $row['denominacion'];?>. que celebran, de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II con RUC Nº 20456188118, en adelante denominado “<? echo $proyecto;?>”, representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>, <? echo $r2['nombre']." ".$r2['apellido'];?>, identificado con DNI. Nº <? echo $r2['n_documento'];?>, con domicilio legal en <? echo $r2['direccion'];?>, del distrito de <? echo $r2['ubicacion'];?> de la Provincia de <? echo $r2['provincia'];?> y Departamento de <? echo $r2['departamento'];?>; y de otra parte LA <? echo $row['nombre'];?>, con <? echo $row['tipo_doc'];?> Nº <? echo $row['n_documento'];?>., con domicilio legal  en <? echo $row['sector'];?> del  Distrito de <? echo $row['distrito'];?>, Provincia de <? echo $row['provincia'];?> y Departamento de <? echo $row['departamento'];?>, a quien en adelante se le denominará “<? echo $org;?>”, 
<?
if ($row['cod_tipo_org']==6)
{
?>	
representada por su Alcalde <? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?>, identificado con DNI. Nº <? echo $r1['n_documento'];?>.,
<?
}
else
{
?>
representada(o) por su Presidente(a) Sr(a) <? echo $row['nombre_1']." ".$row['paterno_1']." ".$row['materno_1'];?>, identificado (a) con DNI. Nº <? echo $row['presidente'];?>., y su Tesorero(a) Sr(a) <? echo $row['nombre_2']." ".$row['paterno_2']." ".$row['materno_2'];?>, identificado (a) con DNI. Nº <? echo $row['tesorero'];?>.,
<?
}
?>
en los términos y condiciones establecidos en las cláusulas siguientes:</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA PRIMERA: ANTECEDENTES</td>
  </tr>
  <tr class="justificado">
    <td width="4%" valign="top">1.1</td>
    <td width="96%">"<? echo $proyecto;?>” es un ente colectivo de naturaleza temporal que tiene como objetivo promover, dentro de su ámbito de acción, que las familias campesinas y microempresarios incrementen sus ingresos, activos tangibles y valoricen sus conocimientos, organización social y autoestima. Para tal efecto, administra los recursos económicos provenientes del Convenio de Financiación que comprende el Préstamo N° 799 –PE y la Donación N° 1158 – PE, firmado entre la República del Perú y el Fondo Internacional de Desarrollo Agrícola – FIDA; dichos recursos son transferidos a “<? echo $proyecto;?>” a través del Programa AGRORURAL del Ministerio de Agricultura-MINAG.</td>
  </tr>
  <tr class="justificado">
    <td valign="top">1.2</td>
    <td width="96%">En el marco de la estrategia de ejecución de “<? echo $proyecto;?>”, se ha establecido el apoyo a iniciativas rurales de inversión que contribuyan al cumplimiento del objetivo del Proyecto, bajo el enfoque de desarrollo territorial rural; para tal efecto, se contempla desarrollar mecanismos de cooperación y colaboración con los gobiernos locales y/u otras instituciones públicas o privadas que promuevan el desarrollo de los territorios del ámbito de acción de “<? echo $proyecto;?>”.</td>
  </tr>
  <tr class="justificado">
    <td valign="top">1.3</td>
    <td width="96%">
	<?
	if ($row['cod_tipo_org']==6)
	{
	?>
	"<? echo $proyecto;?>” y “<? echo $org;?>” han suscrito un Convenio de Cooperación Interinstitucional con el objetivo de  implementar y cofinanciar actividades conjuntas para el acompañamiento y fortalecimiento de las iniciativas rurales a favor de organizaciones campesinas y otras del  ámbito  de “<? echo $org;?>”.
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
    <td width="96%">“<? echo $org;?>” ha presentado a la Oficina Local de <? echo $row['oficina'];?>, su solicitud de cofinanciamiento de una propuesta de Inversión de Desarrollo Local – IDL para ejecutar el Proyecto  denominado: <? echo $row['denominacion'];?>, adjuntando el Expediente Técnico aprobado por la instancia competente.</td>
  </tr>
  <tr class="justificado">
    <td valign="top">1.5</td>
    <td width="96%">El Comité Local de Asignación de Recursos – CLAR, con opinión favorable de la Oficina Local de <? echo $row['oficina'];?> respecto a la solicitud y propuesta de IDL presentado por "<? echo $org;?>", ha seleccionado el Proyecto denominado: <? echo $row['denominacion'];?>, conforme consta en el Acta N° <? echo numeracion($row['n_acta']);?> del <? echo traducefecha($row['f_evento']);?>, </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA SEGUNDA: OBJETO DEL CONTRATO</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Por el presente contrato “<? echo $proyecto;?>” transfiere en donación sujeto a cargo, el monto total de S/. <? echo number_format($row['aporte_pdss'],2);?>  (<? echo vuelveletra($row['aporte_pdss']);?>) a "<? echo $org;?>", la misma que se compromete a aportar el monto total  de S/. <? echo number_format($row['aporte_org'],2);?>  (<? echo vuelveletra($row['aporte_org']);?>). Ambos montos serán destinados para financiar ejecución de la  Inversión de Desarrollo Local denominada <? echo $row['denominacion'];?> , según se resume en el siguiente cuadro:</td>
  </tr>
</table>
<br>
<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="49%">NOMBRE DEL PROYECTO DE INVERSION PARA EL DESARROLLO LOCAL </td>
    <td width="17%">APORTE "<? echo $proyecto;?>" (S/.) </td>
    <td width="17%">APORTE "<? echo $org;?>" (S/.) </td>
    <td width="17%">TOTAL (S/.) </td>
  </tr>
  <tr>
    <td>IDL DENOMINADA: <? echo $row['denominacion'];?></td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($row['aporte_org'],2);?></td>
    <td class="derecha"><? echo number_format($total_idl,2);?></td>
  </tr>
  <tr>
    <td>TOTAL</td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
    <td class="derecha"><? echo number_format($row['aporte_org'],2);?></td>
    <td class="derecha"><? echo number_format($total_idl,2);?></td>
  </tr>
  <tr>
    <td>%</td>
    <td class="derecha">
	<?
	$pp_pdss=$row['aporte_pdss']/$total_idl*100;
	echo number_format($pp_pdss,2);
	?>
	</td>
    <td class="derecha"><?
	$pp_org=$row['aporte_org']/$total_idl*100;
	echo number_format($pp_org,2);
	?></td>
    <td class="derecha"><? echo number_format($pp_pdss+$pp_org,2);?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr class="justificado">
    <td colspan="2">El  Expediente Técnico de la IDL, aprobado por el CLAR y debidamente suscrito por las partes,  forma parte  de  este contrato</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA TERCERA: PLAZO DEL CONTRATO</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">El plazo establecido por las partes para la ejecución del presente contrato es de <?php $plazo=meses($row['f_inicio'], $row['f_termino']); echo $plazo+1;?> meses; se inicia el <?php  echo traducefecha($row['f_inicio'])?> y culmina el <?php  echo traducefecha($row['f_termino'])?>. Este plazo incluye  las acciones de liquidación del Contrato y perfeccionamiento de la donación. </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA CUARTA: DE LA TRANSFERENCIA Y DESEMBOLSO  DE LOS FONDOS</td>
  </tr>

  
<?php 
if ($row['primer_pago']==80)
{
?>  
  <tr class="justificado">
    <td colspan="2">Los aportes de “<? echo $proyecto;?>” serán transferidos a "<? echo $org;?>" en dos desembolsos:</td>
  </tr>
  <tr class="justificado">
    <td colspan="2"><u><strong>Primer Desembolso</strong></u>: <br>
Una vez suscrito el presente Contrato “<? echo $proyecto;?>”, depositará como máximo:<br>
-  El 80%  de su aporte comprometido como cofinanciamiento.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2"><u><strong>Segundo Desembolso</strong></u>:<br> 
	Éste se otorgará por el saldo pendiente  de desembolso, previa aprobación en el  CLAR de los avances de la IDL sustentados  por "<? echo $org;?>" al medio tiempo de ejecución de  la IDL. Para la solicitud del Segundo desembolso y sustentación de avances ante el CLAR, "<? echo $org;?>" debe demostrar ante “S<? echo $proyecto;?>”,  haber utilizado debidamente al menos el 70% de los fondos transferidos del Primer Desembolso, con los informes de avance de la obra.<br>
Dependiendo de la magnitud de la IDL, la oficina local con el informe sustentatorio pertinente y la autorización de la UEP, podrá autorizar  en un solo  desembolso el 100% del aporte de “<? echo $proyecto;?>”.  </td>
  </tr>
<?php 
}
elseif($row['primer_pago']==100)
{
?>
  <tr class="justificado">
    <td colspan="2">Los aportes de “<? echo $proyecto;?>” serán transferidos a "<? echo $org;?>" en un desembolso:</td>
  </tr>
<tr class="justificado">
<td colspan="2"><u><strong>Primer Desembolso</strong></u>: <br>
Una vez suscrito el presente Contrato “<? echo $proyecto;?>”, depositará como máximo:<br>
-  El 100%  de su aporte comprometido como cofinanciamiento.</td>
</tr>
<?php 
}
else
{
?>
 <tr class="justificado">
    <td colspan="2">Los aportes de “<? echo $proyecto;?>” serán transferidos a "<? echo $org;?>" en dos desembolsos:</td>
  </tr>
  <tr class="justificado">
    <td colspan="2"><u><strong>Primer Desembolso</strong></u>: <br>
Una vez suscrito el presente Contrato “<? echo $proyecto;?>”, depositará como máximo:<br>
-  El 70%  de su aporte comprometido como cofinanciamiento.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2"><u><strong>Segundo Desembolso</strong></u>:<br> 
	Éste se otorgará por el saldo pendiente  de desembolso, previa aprobación en el  CLAR de los avances de la IDL sustentados  por "<? echo $org;?>" al medio tiempo de ejecución de  la IDL. Para la solicitud del Segundo desembolso y sustentación de avances ante el CLAR, "<? echo $org;?>" debe demostrar ante “S<? echo $proyecto;?>”,  haber utilizado debidamente al menos el 70% de los fondos transferidos del Primer Desembolso, con los informes de avance de la obra.<br>
Dependiendo de la magnitud de la IDL, la oficina local con el informe sustentatorio pertinente y la autorización de la UEP, podrá autorizar  en un solo  desembolso el 100% del aporte de “<? echo $proyecto;?>”.  </td>
  </tr>
<?
}
?> 
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA QUINTA: OBLIGACIONES DE LAS PARTES</td>
  </tr>
  <tr class="justificado">
    <td width="4%" valign="top">5.1</td>
    <td width="96%">"<? echo $org;?>" se obliga a:</td>
  </tr>
  <tr class="justificado">
    <td valign="top">&nbsp;</td>
    <td width="96%"><ol type="a">
      <li>Alcanzar a “<? echo $proyecto;?>” el documento que demuestre la disponibilidad de recursos con cargo al cual aportará los fondos de cofinanciamiento para la ejecución de la inversión. </li>
      <li>Realizar la contratación de los servicios de un supervisor ad hoc, cuya función será la de monitorear la ejecución de la obra o inversión. El costo que esto involucre deberá figurar dentro del expediente técnico, parte de este costo  podrá ser asumido por  “<? echo $proyecto;?>” dentro de su  aporte.</li>
      <li>Realizar las actividades de la Inversión de Desarrollo Local, conforme a los plazos y especificaciones contenidas en el expediente Técnico aprobado por el CLAR. </li>
      <li>Presentar la liquidación de la Inversión de Desarrollo Local, dentro del plazo estipulado en la cláusula tercera del presente contrato, la que estará acompañada de la  valorización de la inversión o  documentación sustentatoria según el tipo de inversión. </li>
      <li>Es responsabilidad  de "<? echo $org;?>" pagar sobreprecios por no adquirir los materiales en forma oportuna además de aquellos que no hayan sido contemplados en el presupuesto.</li>
      <li>Permitir el seguimiento, evaluación y/o verificación del cumplimiento del presente contrato, especialmente por parte del personal de “<? echo $proyecto;?>”, AGRORURAL, MINAG, MEF y FIDA </li>
      <li>No utilizar y no contar con la participación de menores de edad, como mano de obra en la ejecución de la inversión de conformidad a la legislación nacional vigente y los Convenios Internacionales existentes sobre la materia. </li>
      <li>Ser depositarios de la documentación legal, contable y técnica que permitan el seguimiento, evaluación y/o verificación del cumplimiento del presente contrato por parte del personal de "<? echo $proyecto;?>", AGRORURAL, FIDA y de los Órganos del Sistema Nacional de Control, por un período mínimo de 5 años después del cierre y liquidación de “<? echo $proyecto;?>”</li>
    </ol></td>
  </tr>
  <tr class="justificado">
    <td valign="top">5.2</td>
    <td width="96%">“<? echo $proyecto;?>” se encuentra obligada a: </td>
  </tr>
  <tr class="justificado">
    <td valign="top">&nbsp;</td>
    <td width="96%"><ol type="a">
      <li>Efectuar los desembolsos que le corresponden, referidos en la Cláusula anterior, dependiendo de su disponibilidad de recursos económicos. </li>
      <li>Verificar y hacer cumplir los cargos que permitan la liquidación del presente contrato y el perfeccionamiento de la donación.</li>
    </ol></td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA SEXTA: DEL CARGO, LIQUIDACIÓN Y PERFECCIONAMIENTO DEL CONTRATO.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">“<? echo $proyecto;?>” establece  a  "<? echo $org;?>" como cargo de la presente donación, el cumplimiento del objeto del presente contrato, la responsabilidad y transparencia en el buen manejo de los fondos transferidos,  y de las obligaciones establecidas en la Cláusula Quinta. El contrato quedará liquidado y la donación perfeccionada con el informe favorable de “<? echo $proyecto;?>”, que contiene los documentos mencionados en el punto 5.1, inciso “d” de la cláusula  anterior.  </td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA SEPTIMA:  RESOLUCIÓN DEL CONTRATO.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">El presente Contrato, se resolverá automáticamente, por: <br>
	<ol>
<li>Incumplimiento de las obligaciones establecidas en el presente contrato por alguna de las partes.</li>
<li>Mutuo auerdo de las partes.</li>
<li>Presentación de información falsa ante SIERRA SUR II por parte de "<? echo $org;?>".</li>
</ol></td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA OCTAVA : DE LAS SANCIONES.</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Conllevan sanciones en la aplicación del presente Contrato:
	<ol>
<li>En caso de resolución por incumplimiento de alguna de las partes, la parte agraviada iniciará las acciones penales y civiles a que hubieren lugar. Si la parte agraviada es “<? echo $proyecto;?>”, éste se reserva el derecho de comunicar por cualquier medio de tal hecho a la sociedad civil del ámbito de su acción.</li>
<li>En caso que  "<? echo $org;?>",haya efectuado un uso inapropiado o desvío de fondos para otros fines no previstos en el presente Contrato o presente información falsa, “<? echo $proyecto;?>” exigirá la devolución de los fondos desembolsados a favor de "<? echo $org;?>". Para levantar esta medida "<? echo $org;?>" deberá comunicar y acreditar a “<? echo $proyecto;?>” que ha implementado las medidas correctivas  y aplicado las sanciones a los responsables, si el caso lo amerita.</li>
<li>En caso de no ejecución de la inversión, "<? echo $org;?>" devolverá a “<? echo $proyecto;?>”  los fondos  no utilizados y aquellos gastos no sustentados, acompañado de un informe justificatorio a satisfacción de “<? echo $proyecto;?>”.</li>
</ol></td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA NOVENA: SITUACIONES NO PREVISTAS</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">En caso de ocurrir situaciones no previstas en el presente Contrato o que, estando previstas, escapen al control directo de alguna de las partes, mediante acuerdo mutuo, se determinarán las medidas correctivas. Los acuerdos que se deriven del tratamiento de un caso de esta naturaleza, serán expresados en un Acta o Adenda, según el caso lo amerite.</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA DECIMA: COMPETENCIA TERRITORIAL y JURISDICCIONAL</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Para efectos de cualquier controversia que se genere con motivo de la celebración y ejecución de este contrato, las partes se someten a la competencia territorial de los jueces, tribunales y/o Jurisdicción Arbitral de la ciudad de AREQUIPA, en razón a que la Unidad Ejecutora de “<? echo $proyecto;?>” se encuentra ubicada en el distrito de QUEQUEÑA de la provincia de AREQUIPA.</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA DECIMO  PRIMERA: DOMICILIO</td>
  </tr>
  <tr class="justificado">
    <td colspan="2">Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrato, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito.</td>
  </tr>
  <tr class="justificado txt_titulo">
    <td colspan="2">CLAUSULA  DECIMO SEGUNDA: APLICACIÓN SUPLETORIA DE LA LEY</td>
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
A "<? echo $org;?>" PARA LA EJECUCIÓN DE LA INVERSION DE DESARROLLO LOCAL</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row['monto_desembolsado'],2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%" class="txt_titulo">&quot;<? echo $org;?>&quot;</td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Acta de Sesión de CLAR de fecha </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2">Acta N° <? echo numeracion($row['n_acta']);?> del <? echo traducefecha($row['f_evento']);?></td>
  </tr>
  		<tr>
			<td class="txt_titulo">Referencia</td>
			<td class="txt_titulo centrado">:</td>
			<td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - IDL – OL <? echo $row['oficina'];?></td>
		</tr>
  <tr>
    <td class="txt_titulo">Denominación de la IDL </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['denominacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Tipo de IDL </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['tipo_idl'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° Desembolso </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">PRIMER DESEMBOLSO </td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad Financiera </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['banco'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° Cuenta Bancaria </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['n_cuenta'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: <? echo number_format($row['fuente_fida'],2);?></td>
    <td width="31%">RO: <? echo number_format($row['fuente_ro'],2);?></td>
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
    <td>COFINANCIAMIENTO DE IDL - PRIMER DESEMBOLSO</td>
    <td class="derecha"><?php  echo number_format($row['primer_pago'],2);?></td>
    <td align="right"><? echo number_format($row['monto_desembolsado'],2);?></td>
    <td align="center"><? echo $row['poa'];?></td>
    <td align="center"><? echo $row['categoria'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><? echo number_format($row['monto_desembolsado'],2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>


<div class="capa txt_titulo" align="left">SALDO POR DESEMBOLSAR</div>



<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="48%">MONTO</td>
    <td width="52%" align="right">S/. <? echo number_format($row['saldo'],2);?></td>
  </tr>
  <tr>
    <td>%</td>
    <td width="52%" align="right"><?php  echo number_format($row['segundo_pago'],2);?></td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Solicitud  de cofinanciamiento de la  IDL presentado por <? echo $org;?></td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de acuerdo <? if ($row['cod_tipo_org']==6) echo "Municipal"; else echo "con la Organización";?> y compromiso de  aportes</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de DNI del <? if ($row['cod_tipo_org']==6) echo "Alcalde"; else echo "Presidente y Tesorero";?></td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de aprobación del CLAR</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Expediente técnico aprobado por el CLAR  y suscrito por las partes</td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato de Donación sujeto a Cargo entre SIERRA SUR II y <? echo $org;?></td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Documento que demuestre la disponibilidad de recursos con cargo al cual aportará los fondos de cofinanciamiento para la ejecución de la inversión</td>
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
    <td width="76%">Desembolso  de Iniciativa de Inversión de Desarrollo Local</td>
  </tr>
  <tr>
    <td><? echo $org;?></td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['nombre'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> - <? echo periodo($row['f_contrato']);?> - IDL – OL <? echo $row['oficina'];?></td>
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
    <td width="20%">Nombre de la IDL </td>
    <td width="7%">Tipo de Iniciativa </td>
    <td width="9%">ATF N° </td>
    <td width="20%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>
 
  <tr>
    <td><? echo $row['nombre'];?></td>
    <td><? echo $row['denominacion'];?></td>
    <td class="centrado">IDL</td>
    <td class="centrado"><? echo numeracion($row['n_atf'])."-".periodo($row['f_contrato']);?></td>
    <td><? echo $row['banco'];?></td>
    <td class="centrado"><? echo $row['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($row['monto_desembolsado'],2);?></td>
  </tr>
  
  <tr>
    <td colspan="6">TOTAL</td>
    <td class="derecha"><? echo number_format($row['monto_desembolsado'],2);?></td>
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
<H1 class=SaltoDePagina></H1>
<!-- Generamos los recibos de recepcion de cheque -->
<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
“<? echo $org;?>” <? echo $row['nombre'];?> con <? echo $row['tipo_doc'];?> Nº <? echo $row['n_documento'];?>; 


<?
if ($row['cod_tipo_org']==6)
{
?>	
representada por su Alcalde <? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?>, identificado con DNI. Nº <? echo $r1['n_documento'];?>.
<?
}
else
{
?>
representada(o) por su Presidente(a) Sr(a) <? echo $row['nombre_1']." ".$row['paterno_1']." ".$row['materno_1'];?>, identificado (a) con DNI. Nº <? echo $row['presidente'];?>.
<?
}
?>


; hago constar que el día de hoy <?php  echo traducefecha($row['f_contrato']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <? echo number_format($row['monto_desembolsado'],2);?>(<?php  echo vuelveletra($row['monto_desembolsado']);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $row['clar'];?> de la Oficina Local de <?php  echo $row['oficina'];?>, Relacionado con el Primer Desembolso; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato IDL N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – IDL – OL <? echo $row['oficina'];?>. 
</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $row['oficina'];?>,<?php  echo traducefecha($row['f_contrato']);?></div>









<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
    
    </td>
  </tr>
</table>
</body>
</html>