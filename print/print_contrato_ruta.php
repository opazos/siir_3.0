<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();


$proyecto="<strong>SIERRA SUR II</strong>";



//Busco la info del contrato
$sql="SELECT gcac_bd_ruta.cod_ruta, 
	sys_bd_tipo_iniciativa.codigo_iniciativa, 
	gcac_bd_ruta.nombre AS ruta, 
	gcac_bd_ruta.f_inicio, 
	gcac_bd_ruta.f_termino, 
	org_ficha_organizacion.n_documento AS rrpp, 
	org_ficha_organizacion.nombre AS organizacion, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_departamento.nombre AS departamento, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_distrito.nombre AS distrito, 
	org_ficha_usuario.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.direccion, 
	gcac_bd_ruta.n_contrato, 
	gcac_bd_ruta.f_contrato, 
	gcac_bd_ruta.f_liquidacion, 
	gcac_bd_ruta.aporte_pdss, 
	gcac_bd_ruta.aporte_org, 
	gcac_bd_ruta.aporte_otro, 
	sys_bd_personal.n_documento AS dni_jefe, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	sys_bd_dependencia.direccion AS direccion_oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion, 
	gcac_bd_ruta.cod_tipo_ruta, 
	gcac_bd_ruta.otro_ruta
FROM sys_bd_tipo_iniciativa INNER JOIN gcac_bd_ruta ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gcac_bd_ruta.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_ruta.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_ruta.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = gcac_bd_ruta.cod_tipo_doc AND org_ficha_usuario.n_documento = gcac_bd_ruta.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
	 INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
	 INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
WHERE gcac_bd_ruta.cod_ruta='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$tipo_ruta=$row['cod_tipo_ruta'];

if($tipo_ruta==1)
{
	$denominacion="EL ENCUENTRO DEL CONOCIMIENTO DENOMINADO";
}
elseif($tipo_ruta==2)
{
	$denominacion="LA CAPACITACION DEL PERSONAL DENOMINADA";
}
elseif($tipo_ruta==3)
{
	$denominacion="LA CAPACITACION DE TALENTOS LOCALES DENOMINADA";
}
elseif($tipo_ruta==4)
{
	$denominacion="LA CAPACITACION DE OFERENTES TECNICOS DENOMINADA";
}
elseif($tipo_ruta==5)
{
	$denominacion="EL DIPLOMADO DENOMINADO";
}
elseif($tipo_ruta==6)
{
	$denominacion=$row['otro_ruta'];
}


if($tipo_ruta==5)
{
	$org="<strong>EL BECARIO</strong>";
}
else
{
	$org="<strong>EL RUTERO</strong>";
}

//Fecha de culminacion
$f_final=$row['f_termino'];

if($tipo_ruta==5)
{
$f_termino=dateadd1($f_final,180,0,0,0,0,0);
}
else
{
$f_termino=dateadd1($f_final,90,0,0,0,0,0);
}

if($f_termino>'2014-09-30')
{
	$f_termino='2014-09-30';
}
else
{
	$f_termino=$f_termino;
}

$dias=dias_transcurridos($row['f_contrato'],$f_termino);
$meses=$dias/30;


if ($dias <= 90)
{
	$tiempo=number_format($dias)." días";
}
else
{
	$tiempo=number_format($meses)." meses";
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
<br>
<div class="capa centrado txt_titulo">
CONTRATO Nº <? echo numeracion($row['n_contrato']);?> – <? echo $row['codigo_iniciativa'];?>/<? if($tipo_ruta==5) echo "DIPLOMADO"; else echo "RUTA";?> – <? echo periodo($row['f_contrato']);?> – OL <? echo $row['oficina'];?><br>
DE DONACIÓN SUJETO A CARGO PARA LA PARTICIPACION EN <? echo $denominacion;?>: <? echo $row['ruta'];?>
</div>
<br>
<div class="capa justificado">
Conste por el presente documento el Contrato de Donación sujeto a Cargo para la participación EN <? echo $denominacion;?>: <? echo $row['ruta'];?>,  que celebran, de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II con RUC Nº 20456188118, en adelante denominado "<? echo $proyecto;?>", representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>. Sr.(a) <? echo $row['nombres']." ".$row['apellidos'];?>, identificado(a) con DNI Nº <? echo $row['dni_jefe'];?>, con domicilio legal en <? echo $row['direccion_oficina'];?> del Distrito de <? echo $row['ubicacion'];?>, de la Provincia de <? echo $row['prov'];?> y Departamento de <? echo $row['dep'];?>; y de otra parte el Sr(a), <? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?> identificado(a) con DNI. Nº <? echo $row['dni'];?> En adelante denominado "<? echo $org;?>”, en los términos y condiciones establecidos en las cláusulas siguientes:
</div>
<br>
<table width="90%" cellpadding="1" cellspacing="1" align="center" class="justificado">
<tr class="txt_titulo">
<td colspan="2">CLAUSULA PRIMERA.- ANTECEDENTES</td>
</tr>

<tr>
	<td width="5%" valign="top">1.1</td>
	<td width="95%" class="justificado">"<? echo $proyecto;?>" es un ente colectivo de naturaleza temporal que tiene como objetivo promover, dentro de su ámbito de acción, que las familias campesinas y microempresarios incrementen sus ingresos, activos tangibles y valoricen sus conocimientos, organización social y autoestima. Para tal efecto, administra los recursos económicos provenientes del Convenio de Financiación que comprende el Préstamo N° 799 –PE y la Donación N° 1158 – PE, firmado entre la República del Perú y el Fondo Internacional de Desarrollo Agrícola – FIDA, dichos recursos son transferidos a "<? echo $proyecto;?>" a través del Programa AGRORURAL del Ministerio de Agricultura-MINAG.</td>
</tr>

<tr>
	<td valign="top">1.2</td>
	<td class="justificado">En el marco de la estrategia de ejecución de "<? echo $proyecto;?>", se ha establecido el apoyo a iniciativas rurales de inversión que contribuyan al cumplimiento del objetivo del Proyecto, bajo el enfoque de desarrollo territorial rural; para tal efecto, se promueve que las organizaciones rurales participen de eventos de promoción del conocimiento organizados por los municipios y otras instituciones públicas y privadas en el ámbito local, regional, nacional y/o internacional.</td>
</tr>
<?
if ($row['cod_tipo_ruta']==5)
{
?>
<tr>
	<td valign="top">1.3</td>
	<td class="justificado">Convenio N° 011-2013 de Cooperación Interinstitucional entre el Proyecto de Desarrollo Sierra Sur II y el Programa de Desarrollo Territorial Rural con Identidad Cultural DTR-IC/RIMISP- Centro Latinoamericano para el Desarrollo Rural de fecha tres de noviembre del 2013; con el objetivo de  potenciar las capacidades de desarrollo de emprendimientos y estrategias territoriales innovadoras para la lucha contra la pobreza a través del proceso de Talentos locales a ofertas formativas  tanto en el Perú  como en otros países de la región impulsadas por el Programa  DRT-IC/RIMISP en conjunto con instituciones de educación superior de primer nivel.</td>
</tr>

<tr>
	<td valign="top">1.4</td>
	<td>"<? echo $org;?>", ha sido designado por su Organización <? echo $row['organizacion'];?> y comunicado a la Oficina Local de <? echo $row['oficina'];?> para participar EN <? echo $denominacion;?>: <? echo $row['ruta'];?>, evento que se desarrollará en:
	<ol type="1">
	<!-- Aqui coloco el itinerario de la ruta -->
	<?
	$sql="SELECT gcac_bd_itinerario_ruta.lugar
	FROM gcac_bd_itinerario_ruta
	WHERE gcac_bd_itinerario_ruta.cod_ruta='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
	?>
	<li><? echo $fila['lugar'];?></li>
	<?
	}
	?>
	</ol>	
	
Entre  el <? echo traducefecha($row['f_inicio']);?> al <? echo traducefecha($row['f_termino']);?>, asimismo  cuenta con la opinión favorable  del  Jefe de la Oficina Local de <? echo $row['oficina'];?></td>
</tr>
<?
}
else
{
?>
<tr>
	<td valign="top">1.3</td>
	<td class="justificado">Convenio Marco de Cooperación  Interinstitucional suscrito el 10 de noviembre del 2011 entre SIERRA SUR y PROCASUR, se acordó como objetivo general: “Establecer  mecanismos de coordinación y colaboración interinstitucional dentro de las competencias de las partes que permitan contribuir a mejorar las condiciones de vida   de los hombres y mujeres de la Sierra Sur del Perú en concordancia con los lineamientos, objetivos específicos: “a) implementar actividades que faciliten el desarrollo de iniciativas innovadoras, con énfasis en jóvenes y mujeres rurales, b) realizar estudios , investigaciones, sistematizaciones, rutas de aprendizaje y otras actividades que permitan el rescate de las experiencias y del conocimiento en la ejecución de "<? echo $proyecto;?>" y las familias usuarias, y c) Facilitar procesos de promoción y difusión de las actividades de SIERRA SUR II y PROCASUR, considerando los lineamientos y estrategias de ambas partes”.</td>
</tr>


<tr>
	<td valign="top">1.4</td>
	<td class="justificado">Convenio específico N° 02-2012  Interinstitucional entre El Proyecto de Desarrollo "<? echo $proyecto;?>" y la Corporación Regional de Capacitación en Desarrollo, en el que acuerdan llevar a cabo una Experiencia conjunta “Fondo de Aprendizaje para apoyar inversiones de riesgo compartido en iniciativas con jóvenes rurales emprendedores en el área de ejecución del Proyecto Sierra Sur II.</td>
</tr>

<tr>
	<td valign="top">1.5</td>
	<td>"<? echo $org;?>", ha sido designado por su Organización <? echo $row['organizacion'];?> y comunicado a la Oficina Local de <? echo $row['oficina'];?> para participar EN <? echo $denominacion;?>: <? echo $row['ruta'];?>, evento que se desarrollará en:
	<ol type="1">
	<!-- Aqui coloco el itinerario de la ruta -->
	<?
	$sql="SELECT gcac_bd_itinerario_ruta.lugar
	FROM gcac_bd_itinerario_ruta
	WHERE gcac_bd_itinerario_ruta.cod_ruta='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
	?>
	<li><? echo $fila['lugar'];?></li>
	<?
	}
	?>
	</ol>	
	
Entre  el <? echo traducefecha($row['f_inicio']);?> al <? echo traducefecha($row['f_termino']);?>, asimismo  cuenta con la opinión favorable  del  Jefe de la Oficina Local de <? echo $row['oficina'];?></td>
</tr>
<?php
}
?>
</table>
<br>
<div class="capa justificado txt_titulo">
	CLAUSULA SEGUNDA: OBJETO DEL CONTRATO
</div>
<br>
<div class="capa justificado">
Por el presente contrato "<? echo $proyecto;?>"   transfiere en donación sujeto a cargo, el monto total de S/. <? echo number_format($row['aporte_pdss'],2);?> (<? echo vuelveletra($row['aporte_pdss']);?> Nuevos Soles) a  “<? echo $org;?>". El  Monto será destinado para financiar la participación en el evento  referido en el ítem 1.5 dela Cláusula Primera, según el siguiente cuadro:
</div>

<br>
<table width="90%" cellpadding="1" cellspacing="1" border="1" class="mini" align="center">
	<tr class="txt_titulo centrado">
		<td>NOMBRE DEL EVENTO</td>
		<td>APORTE<BR>SIERRA SUR II (S/.)</td>
		<td>APORTE<BR>ENTIDAD COFINANCIADORA (S/.)</td>
		<td>APORTE<BR>ORGANIZACION (S/.)</td>
		<td>TOTAL</td>
	</tr>
	<tr>
		<td><? echo $row['ruta'];?></td>
		<td class="derecha"><? echo number_format($row['aporte_pdss'],2);?></td>
		<td class="derecha"><? echo number_format($row['aporte_otro'],2);?></td>
		<td class="derecha"><? echo number_format($row['aporte_org'],2);?></td>
		<td class="derecha"><? echo number_format($row['aporte_pdss']+$row['aporte_org']+$row['aporte_otro'],2);?></td>
	</tr>
<?
//calculo de %
$total=$row['aporte_pdss']+$row['aporte_org']+$row['aporte_otro'];

@$pss=$row['aporte_pdss']/$total*100;
@$porg=$row['aporte_org']/$total*100;
@$potro=$row['aporte_otro']/$total*100;

?>		
	<tr>
		<td class="centrado">%</td>
		<td class="derecha"><? echo number_format(@$pss,2);?></td>
		<td class="derecha"><? echo number_format(@$potro,2);?></td>
		<td class="derecha"><? echo number_format(@$porg,2);?></td>
		<td class="derecha">100.00</td>
	</tr>
	
</table>

<br>


<div class="capa justificado">
	El  objetivo de la participación de  "<? echo $org;?>" es mejorar sus capacidades para fortalecer  territorios del conocimiento. 
</div>
<br>
<div class="capa justificado txt_titulo">CLAUSULA TERCERA: PLAZO DEL CONTRATO. </div>
<br>
<div class="capa justificado">El plazo establecido por las partes para la ejecución del presente contrato es de <? echo $tiempo;?>, inicia el <? echo traducefecha($row['f_contrato']);?> y culmina el <? echo traducefecha($f_termino);?>. Este plazo incluye las acciones de liquidación del Contrato y perfeccionamiento de la donación.</div>
<br>
<div class="capa justificado txt_titulo">CLAUSULA CUARTA: DE LA TRANSFERENCIA Y DESEMBOLSO DE LOS FONDOS</div>
<br>
<div class="capa justificado">“<? echo $org;?>” autoriza a "<? echo $proyecto;?>"  para que transfiera a la Corporación PROCASUR o a quien indique  la mencionada  institución   en un solo  desembolso.</div>
<br>
<div class="capa justificado txt_titulo">CLAUSULA QUINTA: OBLIGACIONES DE LAS PARTES</div>
<br>
<table width="90%" cellpadding="1" cellspacing="1" align="center" class="justificado">
<tr>
	<td width="5%" valign="top">5.1</td>
	<td width="95%" class="justificado">"<? echo $org;?>" se obliga a:<br>
	<ol type="a">
	<li>Participar a tiempo completo durante la Ruta de Aprendizaje.</li>
	<li>Presentar el informe sobre su participación en la Ruta de Aprendizaje, incluyendo en material fotográfico en el plazo estipulado en la Cláusula Tercera.</li>
	<li>Realizar una réplica del aprendizaje obtenido en la ruta a favor de las organizaciones de su territorio.</li>  

		</ol>
		
	</td>
</tr>

<tr>
	<td valign="top">5.2</td>
	<td>"<? echo $proyecto;?>" se obliga a:<br>
		<ol type="a">
			<li>Efectuar el desembolso que le corresponda, ante la Corporación PROCASUR  según lo establecido en la cláusula anterior.</li>
			<li>Mantener  informado a EL RUTERO sobre las coordinaciones  previas a la ruta.</li> 
		</ol>
	</td>
</tr>
</table>
<br>
<div class="justificado capa txt_titulo">CLAUSULA SEXTA: DEL CARGO, LIQUIDACIÓN Y PERFECCIONAMIENTO DEL CONTRATO.</div>
<br>
<div class="justificado capa">"<? echo $proyecto;?>" establece a "<? echo $org;?>" como cargo de la presente donación el cumplimiento del objeto del presente contrato, la responsabilidad y transparencia en el buen manejo de los fondos transferidos y de las obligaciones establecidas en la Cláusula Quinta. El contrato quedará liquidado y la donación perfeccionada con el informe presentado por el Rutero  y el informe favorable del jefe de la oficina  local.</div>
<br>
<div class="capa justificado txt_titulo">CLAUSULA SEPTIMA: RESOLUCIÓN DEL CONTRATO.</div>
<br>
<div class="capa justificado">El presente Contrato se resolverá automáticamente, por:<br>
	<ol type="a">
		<li>Incumplimiento de las obligaciones establecidas en el presente contrato por alguna de las partes.</li>
		<li>Incumplimiento de "<? echo $org;?>” en el desarrollo de la Ruta de Aprendizaje.</li>   
		<li>Mutuo acuerdo de las partes.</li>
	</ol>
</div>
<br>
<div class="capa txt_titulo justificado">CLAUSULA OCTAVA: DE LAS SANCIONES.</div>
<br>
<div class="capa justificado">Conllevan sanciones en la aplicación del presente Contrato:<br>
	<ol type="a">
		<li>En caso de resolución por incumplimiento de alguna de las partes, la parte agraviada iniciará las acciones penales y/o civiles a que haber lugar. Si la parte agraviada es "<? echo $proyecto;?>", éste se reserva el derecho de comunicar por cualquier medio de tal hecho a la sociedad civil del ámbito de su acción.</li>
		<li>En caso que  "<? echo $org;?>" no cumpla con participar a tiempo completo durante la Ruta de Aprendizaje, deberá remitir un informe a SIERRA SUR II explicando las causas que limitaron su participación.</li>

	</ol>
</div>
<br>
<div class="capa txt_titulo justificado">CLAUSULA NOVENA: SITUACIONES NO PREVISTAS</div>
<br>
<div class="capa justificado">En caso de ocurrir situaciones no previstas en el presente Contrato o que, estando previstas, escapen al control directo de alguna de las partes, mediante acuerdo mutuo se determinarán las medidas correctivas y  serán expresados en una , Adenda u otro instrumento, según el caso lo amerite.</div>
<br>
<div class="capa justificado txt_titulo">CLAUSULA DECIMA: COMPETENCIA TERRITORIAL y JURISDICCIONAL</div>
<br>
<div class="capa justificado">Para efectos de cualquier controversia que se genere con motivo de la celebración y ejecución de este contrato, las partes se someten a la competencia territorial de los jueces, tribunales y/o Jurisdicción Arbitral de la ciudad de AREQUIPA, en razón a que la Unidad Ejecutora de "<? echo $proyecto;?>" se encuentra ubicada en el distrito de Quequeña de la provincia de Arequipa.</div>
<br>
<div class="capa justificado txt_titulo">CLAUSULA DECIMO PRIMERA: DOMICILIO</div>
<br>
<div class="capa justificado">Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrato, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito.</div>
<br>
<div class="capa txt_titulo justificado">CLAUSULA DECIMO SEGUNDA: APLICACIÓN SUPLETORIA DE LA LEY</div>
<br>
<div class="capa justificado">En lo no previsto por las partes en el presente contrato, ambas se someten a lo establecido por las normas del Código Civil y demás del sistema jurídico que resulten aplicables.</div>
<p><br></p>
<div class="capa justificado">En fe de lo acordado, suscribimos el presente contrato en tres ejemplares, en la localidad de <? echo $row['ubicacion'];?>  el <? echo traducefecha($row['f_contrato']);?></div>
<p><br></p>
<p><br></p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4"  class="centrado txt_titulo">
  <tr>
    <td width="31%">_________________________</td>
    <td width="38%">&nbsp;</td>
    <td width="31%">_________________________</td>
  </tr>
  <tr>
    <td><? echo $row['nombres']." ".$row['apellidos'];?><br>JEFE DE LA OFICINA LOCAL DE <? echo $row['oficina'];?></td>
    <td>&nbsp;</td>
    <td><? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?><br><? echo $org;?><br>DNI N°: <? echo $row['dni'];?></td>
  </tr>
</table>


<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_ruta.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
    
    </td>
  </tr>
</table>

</body>
</html>