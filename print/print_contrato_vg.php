<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();


$proyecto="<strong>SIERRA SUR II</strong>";
$org="<strong>LA ORGANIZACION</strong>";


//Busco la info del contrato
$sql="SELECT sys_bd_tipo_iniciativa.codigo_iniciativa, 
	sys_bd_tipo_doc.descripcion AS tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_tipo_org.descripcion AS tipo_org, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	presidente.n_documento AS dni1, 
	presidente.nombre AS nombre1, 
	presidente.paterno AS paterno1, 
	presidente.materno AS materno1, 
	tesorero.n_documento AS dni2, 
	tesorero.nombre AS nombre2, 
	tesorero.paterno AS paterno2, 
	tesorero.materno AS materno2, 
	ml_bd_contrato_vg.objeto, 
	ml_bd_contrato_vg.f_inicio, 
	ml_bd_contrato_vg.f_termino, 
	ml_bd_contrato_vg.f_aprobacion, 
	ml_bd_contrato_vg.n_contrato, 
	ml_bd_contrato_vg.f_contrato, 
	ml_bd_contrato_vg.vigencia, 
	ml_bd_contrato_vg.n_solicitud, 
	ml_bd_contrato_vg.n_atf, 
	ml_bd_contrato_vg.n_cuenta, 
	ml_bd_contrato_vg.n_participantes, 
	ml_bd_contrato_vg.aporte_pdss, 
	ml_bd_contrato_vg.aporte_org, 
	ml_bd_contrato_vg.aporte_otro, 
	ml_bd_contrato_vg.valorizacion, 
	ml_bd_contrato_vg.aporte_valorizacion, 
	ml_bd_contrato_vg.f_presentacion, 
	sys_bd_ifi.descripcion AS ifi, 
	sys_bd_subactividad_poa.codigo AS poa, 
	sys_bd_subactividad_poa.nombre AS nombre_poa, 
	sys_bd_categoria_poa.codigo AS categoria, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	sys_bd_personal.n_documento AS dni, 
	org_ficha_organizacion.sector
FROM sys_bd_tipo_iniciativa INNER JOIN ml_bd_contrato_vg ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = ml_bd_contrato_vg.cod_tipo_iniciativa
	 INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = ml_bd_contrato_vg.cod_ifi
	 INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = ml_bd_contrato_vg.cod_poa
	 INNER JOIN sys_bd_categoria_poa ON sys_bd_categoria_poa.cod = sys_bd_subactividad_poa.cod_categoria_poa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = ml_bd_contrato_vg.cod_tipo_doc AND org_ficha_organizacion.n_documento = ml_bd_contrato_vg.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 LEFT OUTER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
	 LEFT OUTER JOIN org_ficha_usuario presidente ON presidente.n_documento = org_ficha_organizacion.presidente AND presidente.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND presidente.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN org_ficha_usuario tesorero ON tesorero.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND tesorero.n_documento_org = org_ficha_organizacion.n_documento AND tesorero.n_documento = org_ficha_organizacion.tesorero
WHERE ml_bd_contrato_vg.cod_contrato='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$total_contrato=$row['aporte_pdss']+$row['aporte_org']+$row['aporte_otro']+$row['aporte_valorizacion'];
$aporte_org=$row['aporte_org']+$row['aporte_valorizacion'];

$dias=$row['vigencia'];
$f_termino=dateadd1($f_final,$dias,0,0,0,0,0);
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

<div class="capa centrado txt_titulo">
CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo $row['codigo_iniciativa'];?> – <? echo periodo($row['f_contrato']);?> – OL <? echo $row['oficina'];?><br>
DE DONACIÓN SUJETO A CARGO PARA LA PARTICIPACION EN UNA PASANTIA
</div>


<div class="capa justificado">
<p>Conste por el presente documento, el Contrato de Donación con Cargo para realizar una Pasantia, que celebran de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II, con RUC Nº 20456188118, con domicilio legal en <? echo $row['direccion'];?> en el Distrito de <? echo $row['dist'];?>, Provincia de <? echo $row['prov'];?> y Departamento de <? echo $row['dep'];?>, a quien en adelante se le denominará <? echo $proyecto;?> representado por el jefe de la Oficina Local de <? echo $row['oficina'];?>, <? echo $row['nombres']." ".$row['apellidos'];?>, con DNI. Nº <? echo $row['dni'];?>, y de otra parte la <? echo $row['nombre'];?>, con <? echo $row['tipo_doc'];?> N° <? echo $row['n_documento'];?>, con domicilio real en <? echo $row['sector'];?>, del Distrito de <? echo $row['distrito'];?>, Provincia de <? echo $row['provincia'];?>, Departamento de <? echo $row['departamento'];?>, a quien en adelante se le denominará <? echo $org;?>, representada por su PRESIDENTE, <? echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?> identificado con DNI  Nº <? echo $row['dni1'];?>  y su TESORERO, <? echo $row['nombre2']." ".$row['paterno2']." ".$row['materno2'];?> identificado con DNI. N° <? echo $row['dni2'];?>, quienes se constituyen como responsables solidarios; en los términos y condiciones establecidos en las cláusulas siguientes:</p>

<p><strong><u>ANTECEDENTES:</u></strong></p>
<p><strong>PRIMERO</strong></p>
<p><? echo $proyecto;?>, es un ente colectivo de naturaleza temporal cuya meta es reducir los niveles de pobreza en 15,911 familias pobres rurales de la Sierra Sur con un aumento sostenido de sus activos humanos, naturales, físicos, financieros, culturales y sociales; administra los recursos económicos provenientes del Convenio de Préstamo N° 799-PE, firmado entre el Fondo Internacional de Desarrollo Agrícola – FIDA y la República del Perú.  Dichos recursos son transferidos a <? echo $proyecto;?>, a través de Agrorural del Ministerio de Agricultura – MINAG, en virtud del Decreto Supremo N° 014-2008-AG.</p>
<p><strong>SEGUNDO</strong></p>
<p><? echo $org;?>, es una persona jurídica, que con fecha <? echo traducefecha($row['f_presentacion']);?>, ha presentado a la Oficina Local de <? echo $row['oficina'];?> una solicitud de cofinanciamiento de una propuesta para la realización de una <strong>Pasantia</strong>a fin de fortalecer las capacidades de sus socios que favorezcan el desarrollo de sus iniciativas de negocio según su propuesta adjunta.</p>
<p>La Oficina Local de <? echo $row['oficina'];?>, con fecha <? echo traducefecha($row['f_aprobacion']);?> ha emitido opinión favorable respecto a la solicitud y propuesta de cofinanciamiento presentada por <? echo $org;?>.

<p><strong><u>OBJETO DEL CONTRATO</u></strong></p>
<p><strong>CUARTO</strong></p>
<p>El Objeto del presente contrato es el cofinanciamiento de la Pasantia, para que <? echo $org;?> a través  de sus socios participe y adquiera experiencias y de esta manera pueda dinamizar las actividades de su organización, promoviendo, impulsando y replicando las experiencias entre sus socios. El cofinanciamiento otorgado por <? echo $proyecto;?> tiene carácter de donación con cargo.</p>

<p><strong><u>DEL COFINANCIAMIENTO</u></strong></p>
<p><strong>QUINTO</strong></p>
<p>El cofinanciamiento se regirá por el siguiente esquema:</p>

<table border="1" cellpadding="1" cellspacing="1" width="90%" align="center" class="mini">
	<tr class="txt_titulo centrado">
		<td colspan="3">ESQUEMA DE DESEMBOLSOS</td>
	</tr>
	<tr class="txt_titulo">
		<td width="50%">ENTIDAD</td>
		<td width="25%">APORTE PORCENTAJE (%)</td>
		<td width="25%">APORTE EN MONEDA NACIONAL (S/.)</td>
	</tr>
	<tr>
		<td class="txt_titulo">NEC PDSS</td>
		<td class="derecha"><? echo number_format($row['aporte_pdss']/$total_contrato*100);?></td>
		<td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
	</tr>
	<tr>
		<td class="txt_titulo">ORGANIZACION</td>
		<td class="derecha"><? echo number_format($aporte_org/$total_contrato*100);?></td>
		<td class="derecha"><? echo number_format($aporte_org,2);?></td>
	</tr>

	<tr>
		<td class="txt_titulo">OTRO</td>
		<td class="derecha"><? echo number_format($row['aporte_otro']/$total_contrato*100);?></td>
		<td class="derecha"><? echo number_format($row['aporte_otro'],2);?></td>
	</tr>

	<tr class="txt_titulo">
		<td>TOTAL</td>
		<td class="derecha">100.00</td>
		<td class="derecha"><? echo number_format($total_contrato,2);?></td>
	</tr>
</table>

<p><strong><u>VIGENCIA DEL CONTRATO</u></strong></p>
<p><strong>SEXTO</strong></p>
<p>La vigencia del presente contrato es de <? echo $row['vigencia'];?> días; rige a partir del desembolso realizado por <? echo $proyecto;?>, y culmina con la presentación del Informe Técnico - Administrativo por parte de <? echo $org;?></p>

<p><strong><u>COMPROMISOS DE LAS PARTES</u></strong></p>
<p><strong>SEPTIMO</strong></p>
<p><? echo $proyecto;?>, se compromete a efectuar el desembolso, que le corresponde, referido en la Cláusula Sexta, dependiendo de su disponibilidad de recursos económicos</p>
<p><? echo $org;?> se compromete a:
<ol>
<li>Aportar el fondo de su cofinanciamiento según la propuesta aprobada.</li>
<li>Presentar un Informe Técnico -  Administrativo de la Pasantía, en un plazo que no exceda los veinte (30) días hábiles posteriores a la mencionada Pasantía el que estará acompañado de la rendición  de cuentas, en forma documentada con los comprobantes de pago y/o Declaraciones Juradas de los gastos realizados por <? echo $org;?> con los fondos transferidos por <? echo $proyecto;?> y el aporte de <? echo $org;?>.  Los comprobantes de pago de los gastos realizados deberán estar a nombre de <? echo $org;?>.</li>
<li><? echo $org;?> se compromete a realizar una réplica del aprendizaje obtenido por los participantes en la Pasantía a favor de sus asociados.</li>
</ol>
</p>

<p><strong><u>DEL CARGO Y PERFECCIONAMIENTO DE LA DONACIÓN</u></strong></p>
<p><strong>OCTAVO</strong></p>
<p>El cargo del presente contrato es el cumplimiento de su objeto y de las obligaciones de <? echo $org;?>, establecidas en la Cláusula Octava.  La donación quedará perfeccionada con el Informe favorable de el JEFE DE OFICINA LOCAL de <? echo $row['oficina'];?> respecto de los documentos indicados en la Cláusula anterior.</p>
<p><strong><u>RESOLUCIÓN DEL CONTRATO.</u></strong></p>
<p><strong>NOVENO</strong></p>
<p>Son causales de resolución del presente Contrato de Donación con Cargo, las siguientes:
<ol>
<li>Incumplimiento de las obligaciones establecidas en el presente contrato por las partes.</li>
<li>Incumplimiento de <? echo $org;?> en la ejecución de su Pasantía</li>
<li>Disolución de <? echo $org;?>.</li>
<li>Presentación de información falsa ante SIERRA SUR II por parte de <? echo $org;?>.</li>
</ol>
</p>

<p><strong><u>DE LAS SANCIONES</u></strong></p>
<p>DECIMO</p>
<p>Conllevan sanciones en la aplicación del presente Contrato:
<ol>
<li>En caso de resolución por incumplimiento de las partes, la parte agraviada iniciará las acciones penales y civiles a que hubieren lugar. Si la parte agraviada es <? echo $proyecto;?>, éste se reserva el derecho de comunicar por cualquier medio de tal hecho a la sociedad civil del ámbito de su acción.</li>
<li>En caso <? echo $org;?> haya efectuado un uso inapropiado o desvío de fondos para otros fines no previstos en el presente Contrato o presente información falsa, <? echo $proyecto;?> exigirá la devolución de los fondos desembolsados a favor de la organización. Para levantar esta medida <? echo $org;?> deberá comunicar y acreditar a <? echo $proyecto;?> que ha implementado las medidas correctivas  y aplicado las sanciones a los responsables, si el caso lo amerita.</li>
<li>En caso de disolución de <? echo $org;?>, ésta devolverá a <? echo $proyecto;?> los fondos no utilizados y presentará los documentos que acrediten los gastos realizados.</li>
</ol>
</p>

<p><strong><u>SITUACIONES NO PREVISTAS</u></strong></p>
<p><strong>UNDÉCIMO</strong></p>
<p>En caso de ocurrir situaciones no previstas en el presente Contrato, las partes, mediante acuerdo mutuo, determinarán las medidas correctivas; que serán expresados en una Adenda.</p>
<p><strong><u>COMPETENCIA JURISDICCIONAL</u></strong></p>
<p><strong>DECIMOPRIMERO</strong>
<p>En caso de surgir alguna controversia entre las partes, respecto a la aplicación del presente Contrato, éstas convienen en someterse a la competencia de los jueces o tribunales de la localidad de QUEQUEÑA y podrá recurrirse a la jurisdicción arbitral, dentro del ámbito del departamento de AREQUIPA.</p>
<p>En fe de lo acordado, suscribimos el presente contrato en tres ejemplares, en la localidad de <? echo $row['oficina'];?> con fecha <? echo traducefecha($row['f_contrato']);?>.</p>
</div>
<p><br></p>
<p><br></p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4"  class="centrado txt_titulo">
  <tr>
    <td width="20%">_________________________</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">_________________________</td>
    <td width="20%">&nbsp;</td>
    <td width="20%">_________________________</td>
  </tr>
  <tr>
    <td><? echo $row['nombres']." ".$row['apellidos'];?><br>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></td>
    <td>&nbsp;</td>
    <td><? echo $row['nombre1']." ".$row['paterno1']." ".$row['materno1'];?><br>PRESIDENTE<br>DNI N°: <? echo $row['dni1'];?></td>
    <td>&nbsp;</td>
    <td><? echo $row['nombre2']." ".$row['paterno2']." ".$row['materno2'];?><br>TESORERO<br>DNI N°: <? echo $row['dni2'];?></td>  
  </tr>
</table>



<H1 class=SaltoDePagina></H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center">AUTORIZACION DE TRANSFERENCIA DE FONDOS N° <? echo numeracion($row['n_atf']);?> –  <? echo periodo($row['f_contrato']);?> - <? echo $row['oficina'];?><BR>
A "<? echo $org;?>" PARA EL COFINANCIAMIENTO DE PASANTIA</div>
<br>
<table width="30%" border="1" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td align="CENTER" class="txt_titulo">S/. <? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
</table>
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
    <td colspan="2"><? echo $row['ifi'];?></td>
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
    <td colspan="2"><? echo $row['poa']." ".$row['nombre_poa'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Categoria de gasto</td>
    <td align="center" class="txt_titulo">:</td>
    <td colspan="2"><? echo $row['categoria'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fuente de Financiamiento </td>
    <td align="center" class="txt_titulo">:</td>
    <td width="30%">FIDA: </td>
    <td width="31%">RO: </td>
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

<div class="capa txt_titulo" align="left">DETALLE DE LA CONTRAPARTIDA DE LA ORGANIZACION</div>

<table width="90%" border="1" align="center" cellpadding="0" cellspacing="0" class="mini">
  <tr>
    <td>MONTO DE APORTE EN EFECTIVO</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($row['aporte_org'],2);?></td>
  </tr>
   <tr>
    <td>MONTO DE APORTE EN VALORIZACION</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($row['aporte_valorizacion'],2);?></td>
  </tr> 

   <tr class="txt_titulo">
    <td>TOTAL APORTE ORGANIZACION</td>
    <td align="center">:</td>
    <td align="right"><strong>S/. </strong><? echo number_format($row['aporte_org']+$row['aporte_valorizacion'],2);?></td>
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
    <td width="76%">Desembolso de Pasantia </td>
  </tr>
  <tr>
    <td><? echo $org;?></td>
    <td width="1%">:</td>
    <td width="76%"><? echo $row['nombre'];?></td>
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
    <td width="7%">Tipo de Iniciativa </td>
    <td width="9%">ATF N° </td>
    <td width="20%">Institución Financiera </td>
    <td width="12%">N° de Cuenta </td>
    <td width="13%">Monto a Transferir (S/.) </td>
  </tr>
 
  <tr>
    <td><? echo $row['nombre'];?></td>
    <td class="centrado"><? echo $row['codigo_iniciativa'];?></td>
    <td class="centrado"><? echo numeracion($row['n_atf'])."-".periodo($row['f_contrato']);?></td>
    <td><? echo $row['ifi'];?></td>
    <td class="centrado"><? echo $row['n_cuenta'];?></td>
    <td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
  </tr>
  
  <tr>
    <td colspan="5">TOTAL</td>
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
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_vg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
    
    </td>
  </tr>
</table>

</body>
</html>