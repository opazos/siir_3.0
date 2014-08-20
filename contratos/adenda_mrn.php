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
<dd  class="active"><a href="">Añadir Presupuesto para Iniciativa de Plan de Gestión</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_adenda_monto.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_MRN" onsubmit="return checkSubmit();">
	
	<div class="twelve columns"><h6>I.- SELECCIONAR INICIATIVA DE PLAN DE GESTION</h6></div>
	<div class="twelve columns">
		<select name="mrn">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
			org_ficha_organizacion.nombre
			FROM pit_bd_ficha_adenda INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_mrn.cod_pit
			INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
			WHERE pit_bd_ficha_adenda.cod_adenda='$cod'";
			$result=mysql_query($sql) or die (mysql_error());
			while($r1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $r1['cod_mrn'];?>"><? echo $r1['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="twelve columns"><br/></div>
	
	<div class="two columns">Fecha de inicio</div>
	<div class="four columns"><input type="date" name="f_inicio" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
	<div class="two columns">Nº de meses</div>
	<div class="four columns"><input type="text" name="mes" class="required digits seven"></div>
	
	<div class="twelve columns"><h6>II.- PRESUPUESTO SOLICITADO</h6></div>
	
	<table class="responsive">
		<thead>
			<tr>
				<th class="seven">Concepto</th>
				<th>Aporte Sierra Sur (S/.)</th>
				<th>Aporte Organizacion (S/.)</th>
			</tr>
		</thead>
		
		<tbody>
			<tr>
				<td>1.- Premios para 4to CIF</td>
				<td><input type="text" name="cif_pdss" class="digits" readonly="readonly"></td>
				<td>-</td>
			</tr>
			
			<tr>
				<td>2.- Asistencia Tecnica de campesino a campesino</td>
				<td><input type="text" name="at_pdss" class="number required" value="1307"></td>
				<td><input type="text" name="at_org" class="number required" value="560"></td>
			</tr>
			
			<tr>
				<td>3.- Apoyo a la Gestión del PGRN</td>
				<td><input type="text" name="ag_pdss" class="number required" readonly="readonly" value="350"></td>
				<td>-</td>
			</tr>			
		</tbody>
	</table>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Añadir</button>
	<a href="edit_adenda_monto.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=add" class="primary button">Editar Addenda</a>
</div>	

<div class="twelve columns"><hr/></div>

<table class="responsive">
	<thead>
		<tr>
			<th>Nº Documento</th>
			<th>Nombre de la Organización</th>
			<th>Aporte PDSS (S/.)</th>
			<th>Aporte Organizacion (S/.)</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
		$sql="SELECT org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre, 
		(pit_adenda_mrn.cif_pdss+ 
		pit_adenda_mrn.ag_pdss+ 
		pit_adenda_mrn.at_pdss) AS aporte_pdss, 
		pit_adenda_mrn.at_org AS aporte_org, 
		pit_adenda_mrn.cod_iniciativa
		FROM pit_bd_ficha_mrn INNER JOIN pit_adenda_mrn ON pit_bd_ficha_mrn.cod_mrn = pit_adenda_mrn.cod_mrn
		INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
		WHERE pit_adenda_mrn.cod_adenda='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
		while($fila=mysql_fetch_array($result))
		{
	?>
		<tr>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['nombre'];?></td>
			<td><? echo number_format($fila['aporte_pdss'],2);?></td>
			<td><? echo number_format($fila['aporte_org'],2);?></td>
			<td>
				<a href="gestor_adenda_monto.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $fila['cod_iniciativa'];?>&action=DELETE_MRN" class="small alert button" onclick="return confirm('La iniciativa sera quitada, desea continuar ?')">Quitar</a>
			</td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
</table>
	
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
