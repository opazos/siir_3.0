<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
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
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Detalle del Presupuesto CLAR</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_DETALLE" onsubmit="return checkSubmit();">

<div class="two columns">Nº de RUC</div>
<div class="four columns"><input type="text" name="ruc" class="required digits seven" maxlength="11"></div>
<div class="two columns">Fecha</div>
<div class="four columns"><input type="date" name="f_detalle" class="date required seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">Proveedor</div>
<div class="ten columns"><input type="text" name="proveedor" class="required"></div>
<div class="two columns">Tipo documento</div>
<div class="four columns">
	<select name="tipo_doc">
	<option value="" selected="selected">Seleccionar</option>
	<?
	$sql="SELECT sys_bd_tipo_importe.cod_tipo_importe, 
	sys_bd_tipo_importe.descripcion
	FROM sys_bd_tipo_importe";
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
	?>
	<option value="<? echo $f1['cod_tipo_importe'];?>"><? echo $f1['descripcion'];?></option>
	<?
	}
	?>
		
	</select>
</div>
<div class="two columns">Serie - Nº Documento</div>
<div class="two columns"><input type="text" name="serie" class="required nine"></div>
<div class="two columns"><input type="text" name="n_documento" class="required twelve"></div>

<div class="two columns">Detalle</div>
<div class="ten columns"><input type="text" name="detalle" class="required"></div>
<div class="two columns">Concepto</div>
<div class="four columns">
	<select name="concepto">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT sys_bd_tipo_gasto.cod_tipo_gasto, 
		sys_bd_tipo_gasto.descripcion
		FROM sys_bd_tipo_gasto";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_tipo_gasto'];?>"><? echo $f2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Monto (S/.)</div>
<div class="four columns"><input type="text" name="monto" class="required number seven"></div>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="primary button">Añadir</button>
	<a href="../print/print_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>" class="success button">Imprimir</a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_rinde" class="secondary button">Finalizar</a>
</div>

	
</form>
<div class="twelve columns"><hr/></div>

<table class="responsive">
	
	<thead>
		<tr>
			<th>Nº</th>
			<th>Fecha</th>
			<th class="three">Concepto</th>
			<th class="three">Nº Documento</th>
			<th class="four">Descripcion</th>
			<th class="three">Monto (S/.)</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$num=0;
	$sql="SELECT clar_bd_detalle_gasto_clar.cod_detalle_clar, 
	clar_bd_detalle_gasto_clar.f_detalle, 
	sys_bd_tipo_gasto.descripcion AS tipo_gasto, 
	clar_bd_detalle_gasto_clar.serie, 
	clar_bd_detalle_gasto_clar.numero, 
	clar_bd_detalle_gasto_clar.concepto, 
	clar_bd_detalle_gasto_clar.monto
	FROM sys_bd_tipo_gasto INNER JOIN clar_bd_detalle_gasto_clar ON sys_bd_tipo_gasto.cod_tipo_gasto = clar_bd_detalle_gasto_clar.cod_tipo_gasto
	WHERE clar_bd_detalle_gasto_clar.cod_rinde_clar='$cod'
	ORDER BY clar_bd_detalle_gasto_clar.f_detalle ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($f3=mysql_fetch_array($result))
	{
		$num++
	
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo fecha_normal($f3['f_detalle']);?></td>
			<td><? echo $f3['tipo_gasto'];?></td>
			<td><? echo $f3['serie']."-".$f3['numero'];?></td>
			<td><? echo $f3['concepto'];?></td>
			<td><? echo number_format($f3['monto'],2);?></td>
			<td><a href="gestor_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $f3['cod_detalle_clar'];?>&action=DELETE_DETALLE" class="small alert button">Quitar</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
</table>




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
