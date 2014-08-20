<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


$sql="SELECT ficha_contrato_ls.n_contrato, 
	ficha_contrato_ls.f_contrato, 
	ficha_contrato_ls.mes, 
	ficha_contrato_ls.puesto, 
	ficha_contrato_ls.pago, 
	ficha_contrato_ls.condicion_servicio, 
	sys_bd_dependencia.nombre AS oficina, 
	sys_bd_dependencia.departamento AS dep, 
	sys_bd_dependencia.provincia AS prov, 
	sys_bd_dependencia.ubicacion AS dist, 
	sys_bd_dependencia.direccion AS lugar, 
	sys_bd_personal.n_documento AS dni, 
	sys_bd_personal.nombre AS nombres, 
	sys_bd_personal.apellido AS apellidos, 
	ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.direccion, 
	sys_bd_ubigeo_dist.descripcion AS distrito, 
	sys_bd_ubigeo_prov.descripcion AS provincia, 
	sys_bd_ubigeo_dep.descripcion AS departamento, 
	ficha_contrato_ls.contenido
FROM sys_bd_dependencia INNER JOIN ficha_contrato_ls ON sys_bd_dependencia.cod_dependencia = ficha_contrato_ls.cod_dependencia
	 INNER JOIN ficha_ag_oferente ON ficha_ag_oferente.cod_tipo_doc = ficha_contrato_ls.cod_tipo_doc AND ficha_ag_oferente.n_documento = ficha_contrato_ls.n_documento
	 INNER JOIN sys_bd_ubigeo_dist ON sys_bd_ubigeo_dist.cod = ficha_ag_oferente.ubigeo
	 INNER JOIN sys_bd_ubigeo_prov ON sys_bd_ubigeo_prov.cod = sys_bd_ubigeo_dist.relacion
	 INNER JOIN sys_bd_ubigeo_dep ON sys_bd_ubigeo_dep.cod = sys_bd_ubigeo_prov.relacion
	 INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE ficha_contrato_ls.cod_contrato='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />

  <!-- Set the viewport width to device width for mobile -->
  <meta name="viewport" content="width=device-width" />

  <title>::SIIR - Sistema de Informacion de Iniciativas Rurales::</title>
   <link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="../stylesheets/app.css">
  <link rel="stylesheet" href="../rtables/responsive-tables.css">
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../javascripts/btn_envia.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  

    <!-- Editor de texto -->
  <script type="text/javascript" src="../plugins/texteditor/jscripts/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="../plugins/texteditor/editor.js"></script>
  <!-- fin de editor de texto -->
  
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Edición de contenido del Contrato</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE" onsubmit="return checkSubmit();">
	
<textarea id="elm1" name="contenido" rows="50" cols="80" style="width: 100%">
<?
if ($modo==edit)
{
	echo $row['contenido'];
}
else
{
?>
<div class="capa justificado">
Conste por el presente documento el contrato de locación de servicios que celebran de una parte EL NÚCLEO EJECUTOR CENTRAL DEL PROYECTO DE DESARROLLO SIERRA SUR II, con RUC Nº 20456188118, con domicilio legal en <? echo $row['lugar'];?> en el distrito de <? echo $row['dist'];?>, provincia de <? echo $row['prov'];?> y departamento de <? echo $row['dep'];?> a quien en adelante se le denominará SIERRA SUR II representado por el Jefe de la Oficina Local de <? echo $row['oficina'];?>, <? echo $row['nombres']." ".$row['apellidos'];?> identificado con DNI. Nº <? echo $row['dni'];?>; y de otra parte <? echo $row['nombre']." ".$row['paterno']." ".$row['materno'];?>, identificado con DNI. Nº  <? echo $row['n_documento'];?>, con domicilio real en <? echo $row['direccion'];?>, a quien en lo sucesivo se denominará EL LOCADOR; en los términos contenidos en las cláusulas siguientes:</div>

<div class="capa justificado">
<p><strong>ANTECEDENTES:</strong></p>

<p><strong>PRIMERO .-</strong> 	SIERRA SUR II, es un ente colectivo de naturaleza temporal que tiene como objetivo promover, dentro de su ámbito de acción, que las familias campesinas y microempresarios incrementen sus ingresos, activos tangibles y valoricen sus conocimientos, organización social y autoestima. Para tal efecto, administra los recursos económicos provenientes del Convenio de Financiación que comprende el Préstamo N° 799 –PE y la Donación N° 1158 – PE, firmado entre la República del Perú y el Fondo Internacional de Desarrollo Agrícola – FIDA; dichos recursos son transferidos a "SIERRA SUR II" a través del Programa AGRORURAL del Ministerio de Agricultura-MINAG.</p>

<p><strong>SEGUNDO .-</strong> 	EL LOCADOR es una persona natural, que reúne los requisitos establecidos en los términos de referencia que son parte de este contrato.</p>
</div>

<div class="capa justificado">
<p><strong>OBJETO DEL CONTRATO:</strong></p>
<p><strong>TERCERO .-</strong> 	Por el presente contrato, EL LOCADOR se obliga a prestar sus servicios como <? echo $row['puesto'];?>  que se verá reflejada en la entrega de productos que se detallan en la cláusula cuarta, los que beneficiarán a usuarios del ámbito de intervención de SIERRA SUR II, a título de locación de servicios y en los términos pactados en este contrato.</p></div>

<div class="capa justificado">
<p><strong>CARACTERES Y FORMA DE PRESTAR EL SERVICIO:</strong></p>
<p><strong>CUARTO .-</strong>	Los servicios objeto de la prestación a cargo de EL LOCADOR consiste en facilitar temas de educación financiera, seguros de vida campesino y escolar, dichas actividades reflejarán las metas trazadas en la entrega de los siguientes productos, dentro del plazo establecido en el presente contrato:</p></div>

<div class="capa justificado"><? echo $row['condicion_servicio'];?></div>


<div class="capa justificado"><p><strong>QUINTO .-</strong> 	El servicio objeto de la prestación a cargo de EL LOCADOR tiene carácter personal, no podrá valerse de terceros para efectos de obtener los productos que entregará.</p></div>

<div class="capa justificado"><p><strong>RETRIBUCIÓN:</strong></p> 
<p><strong>SEXTO .-</strong> 	Las partes acuerdan que la retribución que pagará SIERRA SUR II en calidad de contraprestación por los servicios prestados por EL LOCADOR será por producto de la siguiente manera:</p>

<ul>
<li>Por facilitar, el cofinanciamiento de la contratación de las cuentas de ahorros que comprende: verificación de los grupos, tres capacitaciones y la elaboración de los planes de ahorro y retiro, ingreso  de datos en el SIIR, se retribuirá S/. 5.00 (Cinco con 00/100 Nuevos Soles) por cada cuenta de ahorro.</li>

<li>Por facilitar el cofinanciamiento de la contratación de  seguros de vida, que comprende la promoción, capacitación, renovación  e ingreso al SIIR, se retribuirá S/. 5.00 (Cinco con 00/100 Nuevos Soles), por cada seguro de vida campesino.</li>

<li>Por facilitar el cofinanciamiento de la contratación de  seguros de vida campesino escolar, que comprende la promoción, capacitación, renovación  e ingreso al SIIR, se retribuirá S/.3.00 (Tres con 00/100 Nuevos Soles), por cada seguro de vida campesino escolar.</li>

<li>El pago se realizará contra la presentación de los productos indicados en la  cláusula cuarta.</li>
</ul>
</div>

<div class="capa justificado"><p><strong>NATURALEZA DEL CONTRATO:</strong></p>
<p><strong>SÉPTIMO .-</strong> 	El presente contrato es de naturaleza civil, por lo tanto queda establecido que EL LOCADOR no está sujeto a relación de dependencia o vinculo laboral alguno frente a SIERRA SUR II</p> </div>

<div class="capa justificado"><p><strong>PLAZO DEL CONTRATO:</strong></p>

<p><strong>OCTAVO .-</strong> 	El plazo del presente contrato es de <? echo $row['mes'];?> meses, según el cumplimiento de las metas establecidas.</p></div> 

<div class="capa justificado"><p><strong>OBLIGACIONES DE LAS PARTES:</strong></p>
<p><strong>NOVENO .-</strong> 	SIERRA SUR II está obligado a pagar la retribución pactada en favor de EL LOCADOR, conforme a lo establecido en la cláusula Sexto, para ello EL LOCADOR deberá entregar un informe y su respectivo recibo por honorarios cancelado.</p>

<p><strong>DÉCIMO .-</strong> 	EL LOCADOR, por su parte, se obliga a ejecutar la prestación a su cargo en la forma más diligente posible, procurando concluirla dentro del plazo estipulado.</p>

<p><strong>UNDÉCIMO .-</strong> 	EL LOCADOR está obligado a informar a SIERRA SUR II sobre el avance de los productos, cuando menos una vez quincenalmente.</p>

<p><strong>COMPETENCIA TERRITORIAL:</strong></p>
<p><strong>DUODÉCIMO .-</strong> 	Para efectos de cualquier controversia que se genere con motivo de la celebración y ejecución de este contrato, las partes se someten a la competencia territorial de los jueces y tribunales de la ciudad de Arequipa.</p>

<p><strong>DOMICILIO:</strong></p>
<p><strong>DECIMOTERCERO .-</strong> 	Para la validez de todas las comunicaciones y notificaciones a las partes, con motivo de la ejecución de este contrato, ambas señalan como sus respectivos domicilios los indicados en la introducción de este documento. El cambio de domicilio de cualquiera de las partes surtirá efecto desde la fecha de comunicación de dicho cambio a la otra parte, por cualquier medio escrito.</p>

<p><strong>APLICACIÓN SUPLETORIA DE LA LEY:</strong></p>
<p><strong>DECIMOCUARTO .-</strong> 	En lo no previsto por las partes en el presente contrato, ambas se someten a lo establecido por las normas del Código Civil y demás del sistema jurídico que resulten aplicables.</p>

<p>En señal de conformidad las partes suscriben este documento en dos ejemplares en la ciudad de <? echo $row['dist'];?> siendo hoy <? echo traducefecha($row['f_contrato']);?></p></div>
<?
}
?>
</textarea>	
	
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Guardar e imprimir</button>
	<a href="contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
</div>	
	
</form>
</div>
</li>
</ul>
</div>
</div>





    </div>

  </div>

  <!-- Footer -->
<? include("../footer.php");?>


  <!-- Included JS Files (Uncompressed) -->
  <!--
  
  <script src="javascripts/jquery.js"></script>
  
  <script src="javascripts/jquery.foundation.mediaQueryToggle.js"></script>
  
  <script src="javascripts/jquery.foundation.forms.js"></script>
  
  <script src="javascripts/jquery.event.move.js"></script>
  
  <script src="javascripts/jquery.event.swipe.js"></script>
  
  <script src="javascripts/jquery.foundation.reveal.js"></script>
  
  <script src="javascripts/jquery.foundation.orbit.js"></script>
  
  <script src="javascripts/jquery.foundation.navigation.js"></script>
  
  <script src="javascripts/jquery.foundation.buttons.js"></script>
  
  <script src="javascripts/jquery.foundation.tabs.js"></script>
  
  <script src="javascripts/jquery.foundation.tooltips.js"></script>
  
  <script src="javascripts/jquery.foundation.accordion.js"></script>
  
  <script src="javascripts/jquery.placeholder.js"></script>
  
  <script src="javascripts/jquery.foundation.alerts.js"></script>
  
  <script src="javascripts/jquery.foundation.topbar.js"></script>
  
  <script src="javascripts/jquery.foundation.joyride.js"></script>
  
  <script src="javascripts/jquery.foundation.clearing.js"></script>
  
  <script src="javascripts/jquery.foundation.magellan.js"></script>
  
  -->
  
  <!-- Included JS Files (Compressed) -->
  <script src="../javascripts/jquery.js"></script>
  <script src="../javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="../javascripts/app.js"></script>
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
