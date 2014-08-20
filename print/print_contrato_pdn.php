<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT clar_atf_pdn.n_solicitud, 
	clar_atf_pdn.n_atf, 
	sys_bd_componente_poa.codigo AS componente, 
	poa1.codigo AS poa_1, 
	categoria1.codigo AS categoria_1, 
	poa2.codigo AS poa_2, 
	categoria2.codigo AS categoria_2, 
	poa3.codigo AS poa_3, 
	categoria3.codigo AS categoria_3, 
	poa4.codigo AS poa_4, 
	categoria4.codigo AS categoria_4, 
	clar_atf_pdn.monto_1, 
	clar_atf_pdn.saldo_1, 
	clar_atf_pdn.monto_2, 
	clar_atf_pdn.saldo_2, 
	clar_atf_pdn.monto_3, 
	clar_atf_pdn.saldo_3, 
	clar_atf_pdn.monto_4, 
	clar_atf_pdn.saldo_4, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.f_inicio, 
	pit_bd_ficha_pdn.mes, 
	pit_bd_ficha_pdn.f_termino, 
	pit_bd_ficha_pdn.total_apoyo, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.at_org, 
	pit_bd_ficha_pdn.vg_org, 
	pit_bd_ficha_pdn.fer_org, 
	pit_bd_ficha_pdn.n_voucher, 
	pit_bd_ficha_pdn.monto_organizacion, 
	pit_bd_ficha_pdn.n_cuenta, 
	sys_bd_ifi.descripcion AS banco, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	dist.nombre AS distrito, 
	org_ficha_organizacion.sector, 
	org_ficha_organizacion.presidente, 
	presidente.nombre, 
	presidente.paterno, 
	presidente.materno, 
	org_ficha_organizacion.tesorero, 
	tesorero.nombre AS nombre1, 
	tesorero.paterno AS paterno1, 
	tesorero.materno AS materno1, 
	sys_bd_dependencia_1.nombre AS oficina, 
	sys_bd_dependencia_1.departamento AS dep, 
	sys_bd_dependencia_1.provincia AS prov, 
	sys_bd_dependencia_1.ubicacion, 
	sys_bd_dependencia_1.direccion, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	clar_bd_evento_clar.f_evento, 
	clar_bd_acta.n_acta, 
	sys_bd_linea_pdn.descripcion AS linea, 
	clar_bd_evento_clar.nombre AS evento, 
	clar_bd_evento_clar.lugar AS lugar_clar, 
	sys_bd_distrito.nombre AS dist, 
	pit_bd_ficha_pdn.cod_estado_iniciativa, 
	sys_bd_dependencia.nombre AS oficina_2, 
	pit_bd_ficha_pdn.n_contrato, 
	pit_bd_ficha_pdn.f_contrato,
	pit_bd_ficha_pdn.fuente_fida, 
	pit_bd_ficha_pdn.fuente_ro
FROM sys_bd_componente_poa INNER JOIN clar_atf_pdn ON sys_bd_componente_poa.cod = clar_atf_pdn.cod_componente
	 INNER JOIN sys_bd_subactividad_poa poa1 ON poa1.cod = clar_atf_pdn.cod_poa_1
	 INNER JOIN sys_bd_subactividad_poa poa2 ON poa2.cod = clar_atf_pdn.cod_poa_2
	 INNER JOIN sys_bd_subactividad_poa poa3 ON poa3.cod = clar_atf_pdn.cod_poa_3
	 INNER JOIN sys_bd_subactividad_poa poa4 ON poa4.cod = clar_atf_pdn.cod_poa_4
	 INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN sys_bd_linea_pdn ON pit_bd_ficha_pdn.cod_linea_pdn = sys_bd_linea_pdn.cod_linea_pdn
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_pdn.cod_ifi
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN clar_bd_evento_clar ON clar_bd_evento_clar.cod_clar = clar_bd_ficha_pdn_suelto.cod_clar
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
	 LEFT OUTER JOIN clar_bd_acta ON clar_bd_acta.cod_clar = clar_bd_evento_clar.cod_clar
	 INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito dist ON dist.cod = org_ficha_organizacion.cod_dist
	 INNER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN org_ficha_usuario tesorero ON org_ficha_organizacion.tesorero = tesorero.n_documento AND tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_dependencia sys_bd_dependencia_1 ON sys_bd_dependencia_1.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia_1.dni_representante
	 INNER JOIN sys_bd_categoria_poa categoria4 ON categoria4.cod = poa4.cod_categoria_poa
	 INNER JOIN sys_bd_categoria_poa categoria3 ON categoria3.cod = poa3.cod_categoria_poa
	 INNER JOIN sys_bd_categoria_poa categoria2 ON categoria2.cod = poa2.cod_categoria_poa
	 INNER JOIN sys_bd_categoria_poa categoria1 ON categoria1.cod = poa1.cod_categoria_poa
WHERE clar_atf_pdn.cod_tipo_atf_pdn=4 AND
clar_atf_pdn.cod_atf_pdn='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$proyecto="<strong>SIERRA SUR II</strong>";

$org="<strong>LA ORGANIZACION</strong>";

$total_pdss=$row['at_pdss']+$row['vg_pdss']+$row['fer_pdss']+$row['total_apoyo'];
$total_org=$row['at_org']+$row['vg_org']+$row['fer_org'];
$total=$total_pdss+$total_org;

$pp_pdss=$total_pdss/$total*100;
$pp_org=$total_org/$total*100;

$total_70=$total_pdss*0.70;

$saldo=$row['saldo_1']+$row['saldo_2']+$row['saldo_3']+$row['saldo_4'];




$mes=$row['mes'];



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

<div class="capa txt_titulo centrado">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?><br>
DE DONACIÓN CON CARGO, ENTRE EL NEC-PROYECTO DE DESARROLLO SIERRA SUR II Y LA ORGANIZACION <? echo $row['organizacion'];?> PARA COFINANCIAMIENTO DE SERVICIOS DE ASISTENCIA TECNICA</div>
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
<div class="capa justificado">
	Conste por el presente documento el Contrato de Donación sujeto a Cargo para la contratación de Asistencia Técnica, que celebran de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II, con RUC Nº 20456188118, con domicilio legal en la Plaza Principal S/N, del Distrito de Quequeña, Provincia de Arequipa y Departamento de Arequipa, en adelante denominado “<? echo $proyecto;?>”, representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>, <? echo $row['nombres']." ".$row['apellidos'];?> identificado  con DNI. Nº <? echo $row['dni'];?>, con domicilio legal en <? echo $row['direccion'];?>, del distrito de <? echo $row['ubicacion'];?> de la  Provincia de <? echo $row['prov'];?> y  Departamento de <? echo $row['dep'];?>; y de la  otra parte la Organización <? echo $row['organizacion'];?> con <? echo $row['tipo_doc'];?> N° <? echo $row['n_documento'];?>,con domicilio legal en <? echo $row['sector'];?>, ubicada en el Distrito de <? echo $row['distrito'];?>, Provincia de <? echo $row['provincia'];?>, Departamento de <? echo $row['departamento'];?>, en adelante  denominada “<? echo $org;?>”, representada por su Presidente (a), <? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?> Identificado(a) con DNI. N° <? echo $row['presidente'];?> y su Tesorero(a), <? echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?> Identificado(a) con DNI. N° <? echo $row['tesorero'];?>, en los términos y condiciones siguientes:
</div>
<br>
<table width="90%" cellpadding="1" cellspacing="1" align="center" class="justificado">
	<tr class="txt_titulo">
		<td colspan="2">CLAUSULA PRIMERA.- ANTECEDENTES</td>
	</tr>
	<tr>
		<td width="5%" valign="top">1.1</td>
		<td width="95%">"<? echo $proyecto;?>”, es un ente colectivo de naturaleza temporal que tiene como objetivo promover, dentro de su ámbito de acción, que las familias campesinas y microempresarios incrementen sus ingresos, activos tangibles y valoricen sus conocimientos, organización social y autoestima. Para tal efecto, administra los recursos económicos provenientes del Convenio de Financiación que comprende el Préstamo N° 799 –PE y la Donación N° 1158 – PE, firmado entre la República del Perú y el Fondo Internacional de Desarrollo Agrícola – FIDA; dichos recursos son transferidos a “<? echo $proyecto;?>” a través del Programa AGRORURAL del Ministerio de Agricultura-MINAG.</td>
	</tr>
	
	<tr>
		<td valign="top">1.2</td>
		<td>En el marco de la estrategia de ejecución de “<? echo $proyecto;?>”, se ha establecido el apoyo a iniciativas rurales de inversión que contribuyan al cumplimiento del objetivo del Proyecto, bajo el enfoque de desarrollo territorial rural; para tal efecto, las organizaciones rurales podrán  presentar Planes de Negocio - PDN  que contribuyan al desarrollo de sus territorios. </td>
	</tr>
	
	<tr>
		<td valign="top">1.3</td>
		<td>“<? echo $org;?>” es una persona jurídica que, conforme consta en el Acta N° <? echo numeracion($row['n_acta']);?>  con fecha <? echo traducefecha($row['f_evento']);?>, ha sido seleccionada por el Comité Local de Asignación de Recursos – CLAR-, de la Oficina Local de <? echo $row['oficina_2'];?> de “<? echo $proyecto;?>”, para la ejecución  del  PDN,  que se indica en  el cuadro inserto en  la cláusula Segunda.</td>
	</tr>
	<tr class="txt_titulo">
		<td colspan="2">CLAUSULA SEGUNDA.- OBJETO DEL CONTRATO</td>
	</tr>
	<tr>
		<td colspan="2">Por el presente contrato “<? echo $proyecto;?>” transfiere el monto de S/. <? echo number_format($total_pdss,2);?> (<? echo vuelveletra($total_pdss);?> Nuevos Soles)  a “<? echo $org;?>” la misma que se compromete a aportar el monto de S/. <? echo number_format($total_org,2);?> (<? echo vuelveletra($total_org);?> Nuevos Soles). Ambos montos serán destinados  para financiar la ejecución del Plan de Negocio, según el  siguiente detalle: </td>
	</tr>
</table>
<br>
<table width="90%" cellpadding="1" cellspacing="1" border="1" align="center" class="mini_texto">
	<tr class="centrado txt_titulo">
		<td>DESCRIPCION</td>
		<td>APORTE SIERRA SUR (S/.)</td>
		<td>APORTE <? echo $org;?> (S/.)</td>
		<td>TOTAL (S/.)</td>
	</tr>
	<tr>
		<td>PDN:(<? echo $row['denominacion'];?>), según las especificaciones del Anexo Nº 1</td>
		<td class="derecha"><? echo number_format($total_pdss,2);?></td>
		<td class="derecha"><? echo number_format($total_org,2);?></td>
		<td class="derecha"><? echo number_format($total,2);?></td>
	</tr>
	<tr>
		<td>TOTAL</td>
		<td class="derecha"><? echo number_format($total_pdss,2);?></td>
		<td class="derecha"><? echo number_format($total_org,2);?></td>
		<td class="derecha"><? echo number_format($total,2);?></td>
	</tr>
	<tr>
		<td>%</td>
		<td class="derecha"><? echo number_format($pp_pdss,2);?></td>
		<td class="derecha"><? echo number_format($pp_org,2);?></td>
		<td class="derecha">100.00</td>
	</tr>
</table>
<br>
<div class="capa txt_titulo">CLAUSULA  TERCERA.- PLAZO DEL CONTRATO</div>
<div class="capa justificado">El plazo establecido por las partes para la ejecución del presente contrato es de <? echo $mes;?> meses; se inicia el <? echo traducefecha($row['f_contrato']);?> y culmina  el <? echo traducefecha($row['f_termino']);?>. El mismo que incluye el período de ejecución del Plan de Negocios  y las acciones de liquidación del contrato.</div>
<br>
<div class="capa txt_titulo">CLAUSULA CUARTA.- DE LA TRANSFERENCIA Y DESEMBOLSO DE LOS FONDOS</div>
<div class="capa justificado">Las partes acuerdan que la transferencia de los fondos será de la siguiente manera: 
	<ol type="1">
		<li>“<? echo $org;?>”, se obliga a la apertura de una cuenta bancaria en una entidad financiera regulada y supervisada directamente por la Superintendencia de Banca y Seguros (SBS), con la finalidad de que en ésta se realice  el depósito de los aportes señalados en la clausula segunda  del objeto del presente contrato.</li>
<li>Los aportes de “<? echo $proyecto;?>” serán transferidos en calidad de donación con cargo  a “<? echo $org;?>” en dos desembolsos, siempre que cumplan con las siguientes condiciones: </li>   
	</ol>
</div>
<div class="capa txt_titulo"><u>Primer Desembolso:</u></div>
<div class="capa justificado">Una vez suscrito el presente contrato, se procederá al desembolso  en las siguientes proporciones:<br>
- “<? echo $proyecto;?>”, depositará  como máximo el 70% del Plan de Negocio.<br>  
- “<? echo $org;?>”, depositará como mínimo  el 50 % del Plan de Negocio.</div>
<div class="capa txt_titulo"><u>Segundo Desembolso:</u></div>
<div class="capa justificado">Este se otorgará por los saldos pendientes de desembolso, previa evaluación y aprobación por parte del CLAR de los avances del PDN  que serán sustentados por  “<? echo $org;?>” y debe demostrar ante “<? echo $proyecto;?>”, en forma documentada, haber utilizado debidamente al menos 70%  de los fondos transferidos del Primer Desembolso.</div>
<br>
<div class="capa txt_titulo">CLAUSULA QUINTA.-  OBLIGACIONES DE LAS PARTES</div>
<div class="capa justificado">“<? echo $org;?>” se obliga a: 
	<ol type="a">
		<li>Realizar los aportes de cofinanciamiento de conformidad a lo establecido en la cláusula segunda del  presente contrato y presentar  a “<? echo $proyecto;?>” las fotocopias de los vouchers de depósitos.</li>
<li>Contratar a cada proveedor de asistencia técnica, entregando copia de dichos contratos  a “<? echo $proyecto;?>”, así como supervisar su desempeño en  concordancia con el plan de trabajo de cada uno de ellos y demás condiciones establecidas en el respectivo contrato de locación de servicios.</li>  
<li>Realizar las actividades del  PDN, conforme a los plazos y especificaciones contenidas en la propuesta del  PDN, aprobado por el CLAR.
Permitir el seguimiento, evaluación y/o verificación del cumplimiento del presente contrato, especialmente por parte del personal de “<? echo $proyecto;?>”, AGRORURAL, MINAG, MEF y FIDA.</li>
<li>No utilizar y no contar con la participación de menores de edad, como mano de obra en el desarrollo del  PDN, de conformidad a la legislación nacional vigente y los Convenios Internacionales existentes sobre la materia.</li>
<li>Presentar el informe de avance del “PDN” y adjuntar a éste las fotocopias de los comprobantes de pago y el estado de la cuenta que “<? echo $org;?>”  mantiene en la  entidad financiera, para que “<? echo $org;?>” pueda efectuar el segundo desembolso; siempre y cuando se cuente con la opinión favorable de la Oficina Local de <? echo $row['oficina'];?> y la aprobación del CLAR.</li>
<li>Presentar a “<? echo $proyecto;?>”, el Informe de Avance de Medio Tiempo y el Informe Final de Resultados  del  PDN que se ejecuta en mérito al presente contrato.</li>
	</ol>
</div>
<div class="capa txt_titulo">“<? echo $proyecto;?>” se  obliga a:</div>
<div class="capa justificado">
	<ol type="a">
		<li>Efectuar los desembolsos referidos en la Cláusula Cuarta, en observancia de su disponibilidad de recursos económicos.</li>
<li>Exigir a “<? echo $org;?>”  la entrega de la información que permita realizar el seguimiento y evaluación de los avances y resultados de “PDN”.</li>
<li>Verificar y hacer cumplir los cargos que permitan la liquidación del presente contrato y el perfeccionamiento de la donación.</li>
	</ol>
</div>
<br>
<div class="capa txt_titulo">CLAUSULA SEXTA.- DEL CARGO Y PERFECCIONAMIENTO DE LA DONACIÓN</div>
<div class="capa justificado">
	“<? echo $proyecto;?>” establece  a “<? echo $org;?>” como cargo de la presente donación el cumplimiento del Objeto del presente contrato, la responsabilidad  y transparencia en el buen manejo de los fondos transferidos por “<? echo $proyecto;?>”, demostrando su  cumplimiento a través de la presentación de : 
	<ol type="1">
<li>Informe Final del  PDN</li>
<li>El estado de cuenta bancaria del PDN</li>
<li>Fotocopias de los comprobantes de pago</li>
<li>Acta de Aprobación del Cierre del PDN</li>
	</ol>
El Contrato quedará liquidado y perfeccionado con el informe  favorable de “<? echo $proyecto;?>”, que contiene los documentos mencionados en los ítems de esta Cláusula. 	
</div>
<br>
<div class="capa txt_titulo">CLAUSULA SEPTIMA.- RESOLUCIÓN DEL CONTRATO</div>
<div class="capa justificado"> El presente Contrato se resolverá automáticamente, por: 
	<ol type="1">
		<li>Incumplimiento de las partes en los términos y condiciones establecidos en el presente Contrato.</li>
<li>Mutuo acuerdo de las partes.</li>
<li>Disolución de “<? echo $org;?>”, en cuyo caso los firmantes del presente Contrato por parte de “<? echo $org;?>”, asumirán responsabilidad solidaria ante “<? echo $org;?>”.</li>
<li>Presentación de información falsa ante “<? echo $proyecto;?>” por parte de “<? echo $org;?>”.</li>
	</ol>
</div>

<div class="capa txt_titulo">CLAUSULA OCTAVA.- DE LAS SANCIONES:</div>
<div class="capa justificado">
	Conllevan sanciones en la aplicación del presente Contrato: 
	<ol type="1">
	<li>En caso de resolución por incumplimiento de las partes pudiendo la parte agraviada iniciar las acciones penales y civiles a que hubieren lugar. Si la parte agraviada es “<? echo $proyecto;?>”, éste se reserva el derecho de comunicar por cualquier medio este hecho a la sociedad civil del ámbito de su acción.</li> 
<li>En caso de que “<? echo $org;?>”,  efectúe un uso inapropiado o desvío de dichos fondos para otros fines no previstos en el presente Contrato o presente información falsa sobre sus antecedentes y/o en la ejecución del presente contrato, “<? echo $proyecto;?>” suspenderá automáticamente los desembolsos pendientes. Para levantar esta medida “<? echo $org;?>” deberá comunicar y acreditar a “<? echo $proyecto;?>” que ha implementado las medidas correctivas y aplicado las sanciones a los responsables, si el caso lo amerita.</li>
<li>En caso de disolución de “<? echo $org;?>”, ésta devolverá a “<? echo $proyecto;?>” los fondos no utilizados y aquellos gastos no sustentados, acompañado de un informe justificatorio  a satisfacción de “<? echo $proyecto;?>”.</li>
	</ol>
</div>

<div class="capa txt_titulo">CLAUSULA NOVENA.- SITUACIONES NO PREVISTAS</div>
<div class="capa justificado">En caso de ocurrir situaciones no previstas en el presente Contrato o que, estando previstas, escapen al control directo de alguna de las partes; mediante acuerdo mutuo, se determinarán las medidas correctivas. Los acuerdos que se deriven del tratamiento de un caso de esta naturaleza, serán expresados en un Acta o Adenda, según el caso lo amerite.</div>
<br>
<div class="capa txt_titulo">CLAUSULA DECIMA.- COMPETENCIA TERRITORIAL y JURISDICCIONAL</div>
<div class="capa justificado">Para efectos de cualquier controversia que se genere con motivo de la celebración y ejecución de este contrato, las partes se someten a la competencia territorial de los jueces y tribunales y/o Jurisdicción Arbitral  de la ciudad de Arequipa.</div>
<br>
<div class="capa txt_titulo">CLAUSULA DECIMO PRIMERA.- DOMICILIO</div>
<div class="capa justificado">Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrato, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito.</div>
<br>
<div class="capa txt_titulo">CLAUSULA DECIMO SEGUNDA.- APLICACIÓN SUPLETORIA DE LA LEY</div>
<div class="capa justificado">En lo no previsto por las partes en el presente contrato, ambas se someten a lo establecido por las normas del Código Civil y demás del sistema jurídico que resulten aplicables.</div>
<p><br></p>
<div class="capa justificado">En fe de lo acordado, suscribimos el presente contrato en tres ejemplares, en la localidad  de <? echo $row['ubicacion'];?>, el <? echo traducefecha($row['f_contrato']);?></div>
<p><br></p>
<p><br></p>


<table width="90%" cellpadding="1" cellspacing="1" align="center" class="centrado txt_titulo mini">
<tr>
	<td width="35%">______________________</td>
	<td width="30%"></td>
	<td width="35%">______________________</td>
</tr>
<tr>
	<td><? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?><br>PRESIDENTE<BR><? echo $org;?></td>
	<td></td>
	<td><? echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?><br>TESORERO<BR><? echo $org;?></td>
</tr>
</table>
<p><br></p>
<p><br></p>
<table width="90%" cellpadding="1" cellspacing="1" align="center" class="centrado txt_titulo mini">
<tr>
	<td width="35%"></td>
	<td width="30%">______________________</td>
	<td width="35%"></td>
</tr>
<tr>
	<td></td>
	<td><? echo $row['nombres']." ".$row['apellidos'];?><br>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?><br><? echo $proyecto;?></td>
	<td></td>
</tr>
</table>
<H1 class=SaltoDePagina> </H1>



<? include("encabezado.php");?>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo centrado"><u>ANEXO N° 1</u></td>
  </tr>
  <tr class="centrado txt_titulo">
    <td>Aportes de cofinanciamiento de desembolsos del Plan de Negocio </td>
  </tr>
  <tr class="centrado txt_titulo">
    <td><div class="break"></div></td>
  </tr>
  <tr>
    <td><strong>Nombre de la Organización Responsable del PDN :</strong> <? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td><strong>Nombre del Plan de Negocio :</strong> <? echo $row['denominacion'];?></td>
  </tr>
  <tr>
  <td><strong>Referencia :</strong>CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?> con fecha <?php  echo traducefecha($row['f_contrato']);?></td>
  </tr>
  <tr>
    <td><strong>Plazo de ejecución :</strong> Hasta <? echo $mes;?> meses</td>
  </tr>
  <tr>
    <td><hr></td>
  </tr>
</table>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr class="txt_titulo centrado">
    <td>CONCEPTO</td>
    <td>Aporte<br>
      SIERRA SUR  II</td>
    <td>%</td>
    <td>Aporte<br>
      SOCIOS</td>
    <td>%</td>
    <td>TOTAL</td>
    <td>%</td>
  </tr>
  <tr>
    <td>I.- Asistencia  Técnica</td>
    <td align="right"><? echo number_format($row['at_pdss'],2);?></td>
    <td align="right"><? echo number_format(($row['at_pdss']/($row['at_pdss']+$row['at_org']))*100,2);?></td>
    <td align="right"><? echo number_format($row['at_org'],2);?></td>
    <td align="right"><? echo number_format(($row['at_org']/($row['at_pdss']+$row['at_org']))*100,2);?></td>
    <td align="right"><? echo number_format($row['at_pdss']+$row['at_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>II.- Visita  Guiada </td>
    <td align="right"><? echo number_format($row['vg_pdss'],2);?></td>
    <td align="right"><? @$ppvisita=($row['vg_pdss']/($row['vg_pdss']+$row['vg_org']))*100; echo number_format(@$ppvisita,2);?></td>
    <td align="right"><? echo number_format($row['vg_org'],2);?></td>
    <td align="right"><? 
     

    
	@$ppvis1=($row['vg_org']/($row['vg_pdss']+$row['vg_org']))*100; echo number_format(@$ppvis1,2);
	

	
	?></td>
    <td align="right"><? echo number_format($row['vg_pdss']+$row['vg_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>III.- Participación  en Ferias</td>
    <td align="right"><? echo number_format($row['fer_pdss'],2);?></td>
    <td align="right"><? @$ppfer=($row['fer_pdss']/($row['fer_pdss']+$row['fer_org']))*100; echo number_format(@$ppfer,2);?></td>
    <td align="right"><? echo number_format($row['fer_org'],2);?></td>
    <td align="right"><? @$ppfer1=$row['fer_org']/($row['fer_pdss']+$row['fer_org'])*100; echo number_format(@$ppfer1,2);?></td>
    <td align="right"><? echo number_format($row['fer_pdss']+$row['fer_org'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>IV.- Apoyo a  la Gestión del PDN</td>
    <td align="right"><? echo number_format($row['total_apoyo'],2);?></td>
    <td align="right">100.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right"><? echo number_format($row['total_apoyo'],2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td>V.- Inversiones  en Activos</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
    <td align="right">0.00</td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($total_pdss,2);?></td>
    <td align="right"><? echo number_format(($total_pdss/$total)*100,2);?></td>
    <td align="right"><? echo number_format($total_org,2);?></td>
    <td align="right"><? echo number_format(($total_org/$total)*100,2);?></td>
    <td align="right"><? echo number_format($total,2);?></td>
    <td align="right">100.00</td>
  </tr>
  <tr>
    <td colspan="7" align="center"><strong>N °  Desembolso PDN</strong></td>
  </tr>
  <tr>
    <td>Primero CH/ o C/O N°</td>
    <td align="right"><? echo number_format($total_pdss*0.70,2);?></td>
    <td align="right">70.00</td>
    <td align="right"><? echo number_format($total_org*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right"><?
	$pdnpdss1=$total_pdss*0.70;
	$pdnorg1=$total_org*0.50;
	$total_pdn1=$pdnpdss1+$pdnorg1;
	echo number_format($total_pdn1,2);
	?></td>
    <td align="right"><? echo number_format(($total_pdn1/$total)*100,2);?></td>
  </tr>
  <tr>
    <td>Segundo CH/ o C/O N°</td>
    <td align="right"><? echo number_format($total_pdss*0.30,2);?></td>
    <td align="right">30.00</td>
    <td align="right"><? echo number_format($total_org*0.50,2);?></td>
    <td align="right">50.00</td>
    <td align="right"><?
	$pdnpdss2=$total_pdss*0.30;
	$pdnorg2=$total_org*0.50;
	$total_pdn2=$pdnpdss2+$pdnorg2;
	echo number_format($total_pdn2,2);
	?></td>
    <td align="right"><? echo number_format(($total_pdn2/$total)*100,2);?></td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($total_pdss,2);?></td>
    <td align="right">100.00</td>
    <td align="right"><? echo number_format($total_org,2);?></td>
    <td align="right">100.00</td>
    <td align="right"><? echo number_format($total,2);?></td>
    <td align="right">100.00</td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" cellpadding="1" cellspacing="1" align="center" class="centrado txt_titulo mini">
<tr>
	<td width="35%"></td>
	<td width="30%">______________________</td>
	<td width="35%"></td>
</tr>
<tr>
	<td></td>
	<td><? echo $row['nombres']." ".$row['apellidos'];?><br>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?><br><? echo $proyecto;?></td>
	<td></td>
</tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> –  <? echo periodo($row['f_contrato']);?> - <? echo $row['oficina'];?> <BR>
PARA EL COFINANCIAMIENTO DEL PLAN DE NEGOCIO</div>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($total_70,2);?></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">En referencia a la Solicitud de Desembolso de Fondos N° <? echo numeracion($row['n_solicitud']);?> - <? echo periodo($row['f_contrato']);?> / OL <? echo $row['oficina'];?>; Por intermedio del presente, habiéndose cumplido los requisitos establecidos por el NEC PDSS II, el Jefe de la Oficina Local de <? echo $row['oficina'];?> autoriza la transferencia de fondos, de acuerdo al siguiente detalle : </div>
<br>
<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td class="txt_titulo">Organización a transferir </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Denominación del PDN </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['denominacion'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Linea de Negocio </td>
    <td width="4%" align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['linea'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de desembolso </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2">Primer Desembolso </td>
  </tr>
  <tr>
    <td class="txt_titulo">Entidad financiera </td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['banco'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">N° de cuenta bancaria </td>
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
    <td>Asistencia Técnica</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($row['monto_1'],2);?></td>
    <td align="center"><? echo $row['poa_1'];?></td>
    <td align="center"><? echo $row['categoria_1'];?></td>
  </tr>
  <tr>
    <td>Visita Guiada</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($row['monto_2'],2);?></td>
    <td align="center"><? echo $row['poa_2'];?></td>
    <td align="center"><? echo $row['categoria_2'];?></td>
  </tr>
  <tr>
    <td>Participación en Ferias</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($row['monto_3'],2);?></td>
    <td align="center"><? echo $row['poa_3'];?></td>
    <td align="center"><? echo $row['categoria_3'];?></td>
  </tr>
  <tr>
    <td>Apoyo a la Gestión del PDN</td>
    <td class="derecha">70.00</td>
    <td align="right"><? echo number_format($row['monto_4'],2);?></td>
    <td align="center"><? echo $row['poa_4'];?></td>
    <td align="center"><? echo $row['categoria_4'];?></td>
  </tr>
  <tr class="txt_titulo">
    <td colspan="2" align="center" class="txt_titulo">TOTAL</td>
    <td align="right"><?  echo number_format($total_70,2);?></td>
    <td align="center">-</td>
    <td align="center">-</td>
  </tr>
</table>


<div class="capa txt_titulo" align="left">SALDO POR DESEMBOLSAR</div>



<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="48%">MONTO</td>
    <td width="52%" align="right">S/. <? echo number_format($saldo,2);?></td>
  </tr>
  <tr>
    <td>%</td>
    <td width="52%" align="right">30.00</td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td width="35%">N° DE VOUCHER</td>
    <td width="4%" align="center">:</td>
    <td width="61%" align="right"><? echo $row['n_voucher'];?></td>
  </tr>
  <tr>
    <td>MONTO DE APORTE</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($row['monto_organizacion'],2);?></td>
  </tr>
  <tr>
    <td>SALDO POR APORTAR</td>
    <td align="center">:</td>
    <td align="right"><strong>S/.</strong> 
	<? 
	if ($row['monto_organizacion']>$total_org)
	{
	$saldo_pdn=0;
	}
	else
	{
	$saldo_pdn=$total_org-$row['monto_organizacion'];
	}
	
	echo number_format($saldo_pdn,2);
	?>
	</td>
  </tr>
  <tr>
    <td>%</td>
    <td align="center">:</td>
    <td align="right">
	<?
	if ($row['monto_organizacion']>$total_org)
	{
	$pp_saldo_pdn=0;
	}
	else
	{
	@$pp_saldo_pdn=$row['monto_organizacion']/$total_org*100;
	}
	echo number_format(@$pp_saldo_pdn,2);
	?>
	</td>
  </tr>
</table>

<div class="capa txt_titulo" align="left">EXPEDIENTE QUE SUSTENTA ESTE DESEMBOLSO:  Archivados en la Oficina Local</div>


<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr>
    <td width="82%">Copia de la Ficha de Inscripción en la SUNARP </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia de DNIs de los directivos de la Organización responsable del PDN </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Acta de acuerdo para trabajar con SIERRA SUR II y aportes de cofinanciamiento de la Organización responsable del PDN </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>PDN aprobado por el CLAR y suscrito por las partes </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Contrato de Donación sujeto a Cargo entre SIERRA SUR II y La Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
  <tr>
    <td>Copia del Voucher de Depósito del Aporte de La Organización </td>
    <td width="2%" align="center">:</td>
    <td width="16%" align="center">X</td>
  </tr>
</table>

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
    <td width="23%">A</td>
    <td width="1%">:</td>
    <td width="76%">C.P.C JUAN SALAS ACOSTA </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="1%">&nbsp;</td>
    <td width="76%">ADMINISTRADOR DEL NEC PDSS II </td>
  </tr>
  <tr>
    <td>CC</td>
    <td width="1%">:</td>
    <td width="76%">Responsable de Componentes </td>
  </tr>
  <tr>
    <td>ASUNTO</td>
    <td width="1%">:</td>
    <td width="76%">Desembolso  de CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>ORGANIZACIÓN</td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td>CONTRATO N° </td>
    <td width="1%">:</td>
    <td width="76%"><? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> - PDN – OL <? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>FECHA</td>
    <td width="1%">:</td>
    <td width="76%"><? echo traducefecha($row['f_contrato']);?></td>
  </tr>
</table>
<div class="break" align="center"></div>

<div class="capa justificado" align="center">Con la presente agradeceré se sirva disponer la transferencia de recursos correspondientes al primer desembolso de las iniciativas correspondientes a las siguientes organizaciones que en resumen son las siguientes:</div>
<br>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td width="26%">Nombre de la Organización </td>
    <td width="14%">Tipo de Iniciativa </td>
    <td width="12%">ATF N° </td>
    <td width="23%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>

  <tr>
    <td><? echo $row['organizacion'];?></td>
    <td class="centrado">PDN</td>
    <td class="centrado"><? echo numeracion($row['n_atf'])." - ".periodo($row['f_contrato']);?></td>
    <td><? echo $row['banco'];?></td>
    <td class="centrado"><? echo $row['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($total_70,2);?></td>
  </tr>
 
  <tr>
    <td colspan="5">TOTAL</td>
    <td class="derecha"><?
	
	echo number_format($total_70,2);
	?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td>Se adjunta a la presente las autorizaciones de transferencia de fondos de cada una de las organizaciones </td>
  </tr>
  <tr>
    <td><br>Atentamente,</td>
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
    <td align="center"><?php  echo $row['nombres']." ".$row['apellidos'];?><BR> JEFE DE OFICINA LOCAL </td>
  </tr>
</table>
<H1 class=SaltoDePagina></H1>


<p>&nbsp;</p>
<div class="capa txt_titulo centrado">RECIBO</div>
<p>&nbsp;</p>
<div class="capa justificado">
La <?php  echo $row['organizacion'];?> con <?php  echo $row['tipo_doc'];?> N° <?php  echo $row['n_documento'];?>; representada por su PRESIDENTE Sr(a). <?php  echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?>, identificado con DNI N° <?php  echo $row['presidente'];?>; hago constar que el día de hoy <?php  echo traducefecha($row['f_contrato']);?>, he recibido del NEC PROYECTO DE DESARROLLO SIERRA SUR II la cantidad de S/. <?php  echo number_format($total_70,2);?> (<?php  echo vuelveletra($total_70);?> NUEVOS SOLES) con Cheque N°(s) ________________________ del BCP; importe que contiene el cofinanciamiento que mi organización ha aprobado en el <?php  echo $row['evento'];?> de la Oficina Local de <?php  echo $row['oficina'];?>
, Relacionado con el Primer Desembolso, realizado en el Distrito de <?php  echo $row['dist'];?>; monto que depositaré en la cuenta que he aperturado para estos efectos; todo en cumplimiento de la CLAUSULA CUARTA del contrato N° <? echo numeracion($row['n_contrato']);?> – <? echo periodo($row['f_contrato']);?> – PDN – OL <? echo $row['oficina'];?>.</div>
<p>&nbsp;</p>
<div class="capa derecha"><?php  echo $row['dist'];?>,<?php  echo traducefecha($row['f_contrato']);?></div>



<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    
    
    </td>
  </tr>
</table>



</body>
</html>