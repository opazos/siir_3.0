<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT
gcac_bd_ficha_convenio.cod_ficha,
gcac_bd_ficha_convenio.n_convenio,
gcac_bd_ficha_convenio.f_inicio,
gcac_bd_ficha_convenio.f_termino,
gcac_bd_ficha_convenio.objetivo_1,
gcac_bd_ficha_convenio.objetivo_2,
gcac_bd_ficha_convenio.f_presentacion,
gcac_bd_ficha_convenio.cod_dependencia,
gcac_bd_ficha_convenio.n_documento,
gcac_bd_ficha_convenio.dni,
gcac_bd_ficha_convenio.representante,
gcac_bd_ficha_convenio.cargo,
sys_bd_dependencia.nombre AS oficina,
sys_bd_dependencia.ubicacion,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.nombre AS organizacion,
org_ficha_organizacion.cod_tipo_org,
sys_bd_tipo_org.descripcion AS tipo_org,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
org_ficha_organizacion.sector
FROM
gcac_bd_ficha_convenio
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gcac_bd_ficha_convenio.cod_dependencia
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_ficha_convenio.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_bd_ficha_convenio.n_documento
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
WHERE
gcac_bd_ficha_convenio.cod_ficha='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//verificamos el tipo de organizacion
if ($row['cod_tipo_org']==6)
{
	$enunciado="LA MUNICIPALIDAD";
	$tono="LA";
}
else
{
	$enunciado="LA INSTITUCIÓN";
	$tono="LA INSTITUCIÓN";
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
<div class="capa txt_titulo" align="center">CONVENIO  Nº <? echo numeracion($row['n_convenio']);?>-<? echo periodo($row['f_presentacion']);?>-<? echo $row['oficina'];?> 
<br>
DE COOPERACIÓN INTERINSTITUCIONAL ENTRE EL PROYECTO “SIERRA SUR II” Y <? echo $tono;?>  <? echo $row['organizacion'];?>
</div>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr align="justify">
    <td align="justify">Conste por el presente documento, el  Convenio de Cooperación Interinstitucional que celebran, de una parte el <strong>NUCLEO EJECUTOR CENTRAL DEL PROYECTO DE  DESARROLLO SIERRA SUR II&rdquo;,</strong> con RUC Nº 20456188118, con domicilio legal en la Plaza de Armas S/N del distrito de  Quequeña, provincia de Arequipa, departamento de Arequipa, en adelante denominado <strong>&ldquo;SIERRA SUR II&rdquo;, </strong>representado por su  Director Ejecutivo, Sr. José Mercedes Sialer Pasco, con DNI Nº 29280211; y de  otra parte la <strong><? echo $tono." ".$row['organizacion'];?>,</strong> con <? echo $row['tipo_doc'];?> Nº <? echo $row['n_documento'];?>, con domicilio legal en  <? echo $row['sector'];?>, del distrito de <? echo $row['distrito'];?>, provincia de <? echo $row['provincia'];?> y  departamento de <? echo $row['departamento'];?>, en adelante denominada <strong>"<? echo $enunciado;?>", </strong>representada por su <? echo $row['cargo'];?>, Sr(a). <? echo $row['representante'];?>,  identificado con DNI Nº <? echo $row['dni'];?>, en los términos y condiciones siguientes:</td>
  </tr>
  <tr>
    <td align="justify"><p><strong>CLAUSULA PRIMERA:</u></strong><strong>              MARCO  INSTITUCIONAL</strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">1.1 De SIERRA SUR II</td>
  </tr>
  <tr>
    <td align="justify"><ol>
      <li>
        Es consecuencia del Convenio de préstamo Nº 799-PE  entre el Gobierno del Perú y el Fondo  Internacional de Desarrollo Agrícola - FIDA,  suscrito el 19 de agosto y el 06 de setiembre del 2010.
        </li>
        <br>
        <li>
        Es un ente colectivo de naturaleza temporal cuya meta es reducir los  niveles de pobreza en 15,911 familias pobres rurales de la Sierra Sur, mediante  el incremento sostenido de sus activos humanos, naturales, físicos,  financieros, culturales y sociales; administra los recursos económicos  provenientes del Convenio de Préstamo N° 799 -PE, firmado entre el FIDA y la  República del Perú. Dichos recursos son transferidos a SIERRA SUR II a través  de Agrorural del Ministerio de Agricultura – MINAG, en virtud del Decreto  Supremo Nº 014-2008-AG.. 
        </li>
        <br>
        <li>
        En el  marco de la estrategia de ejecución de <strong>&ldquo;SIERRA SUR II&rdquo;</strong>, se ha establecido el apoyo a  iniciativas rurales de inversión, que contribuyan al cumplimiento del objetivo  del Proyecto, bajo el enfoque de desarrollo territorial rural, para cuyo efecto  se contempla desarrollar mecanismos de cooperación y colaboración con los  gobiernos locales de su ámbito de acción</li>
    </ol></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">1.2 De "<? echo $enunciado;?>"</td>
  </tr>
  <tr>
 <?
 if ($row['cod_tipo_org']==6)
 {
 ?> 
    <td align="justify"><ol>
      <li><strong>"<? echo $enunciado;?>"</strong>, es un gobierno local  del distrito de <? echo $row['distrito'];?>, con autonomía administrativa, política y  económica, en asuntos de su competencia, amparada por el Artículo 194º de la  Constitución Política del Perú, y se rige por la Ley Orgánica de  Municipalidades N° 27972.</li>
      <br>
      <li>Al  amparo del Art. 141 de la Ley Orgánica de Municipalidades, sobre competencias  adicionales, las municipalidades ubicadas en zonas rurales, además de las  competencias básicas, tiene a su cargo aquellas relacionadas con la promoción  de la gestión sostenible de los recursos naturales: suelo, agua, flora, fauna,  biodiversidad, con la finalidad de integrar la lucha contra la degradación  ambiental, con la lucha contra la pobreza y la generación de empleo; en el  marco de los planes de desarrollo concertado. </li>
    </ol></td>
<?
}
else
{
?>
<td align="justify">
<ol>
	<li><strong><? echo $enunciado;?></strong>, se ha constituido para promover el desarrollo de las familias del ámbito de su influencia socio - económica, bajo criterios de identidad cultural, consecuentemente el evento en el que participará se enmarcan dentro de los objetivos de <strong><? echo $enunciado;?></strong> y de "<strong>SIERRA SUR II</strong>"</li>
</ol>	
</td>
<?
}
?>    
  </tr>
  <tr>
    <td align="justify"><p><strong><u>CLAUSULA SEGUNDA</u></strong><strong>:             OBJETIVOS  DEL CONVENIO</strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">2.1 Objetivo General</td>
  </tr>
  <tr>
    <td align="justify"><? echo $row['objetivo_1'];?></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">2.2 Objetivos Específicos</td>
  </tr>
  <tr>
    <td align="justify"><? echo $row['objetivo_2'];?></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>CLAUSULA TERCERA</u></strong><strong>:              COMPROMISOS DE LAS  PARTES.</strong></p></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">3.1 De &quot;SIERRA SUR II&quot;</td>
  </tr>
  <tr>
    <td align="justify"><ol>
      <li>Designar al Jefe de la Oficina Local de <? echo $row['oficina'];?> para coordinar y  operativizar con <strong>"<? echo $enunciado;?>"</strong> la  implementación de actividades materia del presente Convenio.</li>
      <li>Cofinanciar iniciativas rurales y actividades derivadas del presente  Convenio, en función a su disponibilidad presupuestaria.   </li>
      <li>Brindar asesoramiento técnico a <strong>"<? echo $enunciado;?>"</strong><strong>, </strong>respecto a los  procesos operativos que tengan vinculación con los objetivos del presente  Convenio. </li>
      <li>Facilitar el acceso a espacios de  capacitación y/o participación en eventos de aprendizaje que contribuyan a  mejorar la gestión de la  <strong>"<? echo $enunciado;?>", </strong>en favor del  desarrollo económico local. </li>
    </ol></td>
  </tr>
  <tr>
    <td align="justify" class="txt_titulo">3.2 De&quot; <? echo $enunciado;?>&quot;</td>
  </tr>
  <tr>
    <td align="justify"><ol>
      <li>Participar en la  promoción y difusión del <strong>&ldquo;SIERRA SUR II&rdquo;</strong> en coordinación con la Oficina Local de <span class="capa txt_titulo"><? echo $row['oficina'];?></span></li>
      <li>Facilitar la capacidad operativa de su Oficina de Desarrollo Económico  Local o la instancia que haga sus veces para la implementación de las iniciativas  rurales y actividades derivadas del presente Convenio.</li>
      <li>Participar en el  cofinanciamiento de iniciativas rurales y actividades que sean de interés de  las organizaciones apoyadas por <strong>&ldquo;SIERRA  SUR II&rdquo;</strong> y que promuevan competencias entre ellas para la asignación de  recursos; así como las presentadas por <strong>"<? echo $enunciado;?>".</strong></li>
    </ol></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>CLAUSULA CUARTA</u></strong><strong>:                MECANISMOS  OPERATIVOS</strong></p></td>
  </tr>
  <tr>
    <td align="justify"><ol>
      <li>Las actividades o eventos de ejecución conjunta a implementar en el marco  del presente Convenio serán acordados mediante intercambio de notas entre el  Jefe de Oficina Local de <? echo $row['oficina'];?> de <strong>&ldquo;SIERRA  SUR II&rdquo;</strong> y <strong>"<? echo $enunciado;?>".</strong></li>
      <li> Los fondos que destine<strong>"<? echo $enunciado;?>"</strong> para la  implementación conjunta de un determinado evento o actividad, materia del  presente Convenio, serán puestos a conocimiento de <strong>&ldquo;SIERRA SUR II&rdquo;</strong> mediante documento específico debidamente  firmado por la instancia competente de<strong>"<? echo $enunciado;?>"</strong> en el que se  describe la valorización de la inversión realizada.</li>
      <li>En caso de eventos o actividades que signifiquen transferencia de  fondos de <strong>&ldquo;SIERRA SUR II&rdquo;</strong> a<strong>"<? echo $enunciado;?>"</strong>, la ejecución se  hará mediante acuerdo o contrato específico suscrito entre<strong>"<? echo $enunciado;?>"</strong>y el Jefe de la Oficina Local de  <? echo $row['oficina'];?></li>
      <li>Cuando<strong>"<? echo $enunciado;?>"</strong>o <strong>&ldquo;SIERRA SUR II&rdquo;</strong> logre concertar  acuerdos de cooperación o colaboración con otras entidades públicas o privadas  que contribuyan a los objetivos del presente Convenio serán puestos a  conocimiento de las partes, a través de la valorización de la inversión  realizada, a fin de visibilizar el apoyo de la entidad.</li>
    </ol></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>CLAUSULA QUINTA</u></strong><strong>:                  DE LA  VIGENCIA.</strong></p></td>
  </tr>
  <tr>
    <td align="justify"><p>El presente Convenio  entra en vigencia desde la fecha de su suscripción hasta el <? echo traducefecha($row['f_termino']);?>, fecha en que está prevista la terminación de actividades de <strong>&ldquo;SIERRA SUR II&rdquo;</strong></p></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>CLAUSULA SEXTA</u></strong><strong>:                       DE LA RESOLUCIÓN DEL CONVENIO.</strong></p></td>
  </tr>
  <tr>
    <td align="justify"><p>El presente Convenio, podrá ser resuelto por  las siguientes causales:</p>
      <ol>
        <li>Cuando no se cumpla con los objetivos del presente Convenio.</li>
        <li>Cuando las partes no cumplan con las obligaciones asumidas en este  Convenio.</li>
        <li>Por decisión unilateral con expresión de causa.</li>
      </ol>
      <p>La Resolución del Convenio, no debe afectar  la culminación de los compromisos pendientes a la fecha de la Resolución.<br>
    Para efectivizar la resolución  del Convenio, la parte interesada comunicara  por escrito a su contraparte, con un plazo mínimo de un mes de anticipación.</p></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>CLAUSULA SÉPTIMA</u></strong><strong>:               DE LOS DOMICILIOS</strong></p></td>
  </tr>
  <tr>
    <td align="justify"><p>Para los  efectos del presente Convenio, las partes señalan como sus domicilios los que  aparecen consignados en la introducción del presente documento.</p></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>CLAUSULA OCTAVA</u></strong><strong>:                                SOLUCIÓN DE  CONTROVERSIAS</strong></p></td>
  </tr>
  <tr>
    <td align="justify"><p>Cualquier  discrepancia que pudiera suscitarse entre las partes, se solucionará mediante  la coordinación entre las partes siguiendo las reglas de la buena fe y común  intención de las partes, comprometiéndose estas a realizar sus mejores  esfuerzos para lograr una solución armoniosa, teniendo en cuenta los principios  que inspira el presente convenio. En su defecto estas convienen en someterse a  la Jurisdicción Arbitral de la Cámara de Comercio de la ciudad de Arequipa. </p></td>
  </tr>
  <tr>
    <td align="justify"><p><strong><u>CLAUSULA NOVENA</u></strong><strong>:                DISPOSICIONES  FINALES.</strong></p></td>
  </tr>
  <tr>
    <td align="justify"><ul>
      <li>Cualquier comunicación que deba ser cursada entre las partes, se  entenderá válidamente realizada en los domicilios legales consignados en la  parte introductoria del presente Convenio.</li>
      <li>Cualquier situación no prevista en el presente Convenio, será resuelto  por las partes de común acuerdo, a través de comunicaciones escritas.</li>
    </ul>
    <p>En señal de aceptación, las partes suscriben  el presente Convenio, en tres ejemplares de igual tenor y efecto legal, en la localidad  de <? echo $row['ubicacion'];?>, siendo hoy <? echo traducefecha($row['f_presentacion']);?>.</p></td>
  </tr>
</table>
<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="33%" align="center" class="txt_titulo">SIERRA SUR II</td>
    <td width="32%" rowspan="2">&nbsp;</td>
    <td width="35%" align="center" class="txt_titulo"><? echo $enunciado;?></td>
  </tr>
  <tr>
    <td><hr></td>
    <td width="35%"><hr></td>
  </tr>
  <tr>
    <td align="center">JOSÉ MERCEDES SIALER PASCO<br>DIRECTOR EJECUTIVO</td>
    <td>&nbsp;</td>
    <td align="center"><? echo $row['representante']."<br>".$row['cargo'];?></td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/convenio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>

    
    </td>
  </tr>
</table>
</body>
</html>