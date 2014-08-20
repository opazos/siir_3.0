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
<dd  class="active"><a href="">Nuevo contrato para la realización de un INTERCON</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_intercon.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="row">
<div class="twelve columns"><h6>I.- Datos del evento</h6></div>
<div class="two columns">Nombre del evento</div>
<div class="ten columns"><input type="text" name="nombre" class="required ten"></div>

<div class="two columns">Fecha del evento</div>
<div class="four columns"><input type="date" name="f_evento" class="required seven date" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="six columns"><br/></div>

<div class="twelve columns"><h6>II- Ubicación del evento</h6></div>
<div class="two columns">Departamento</div>
<div class="four columns">
<?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select class='medium' name='select1' id='select1' onChange='cargaContenido(this.id)' >";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?>		
</div>
<div class="two columns">Provincia</div>
<div class="four columns">
<select disabled="disabled" name="select2" id="select2" class="medium" >
<option value="0">Selecciona opci&oacute;n...</option>
</select>		
</div>
<div class="two columns">Distrito</div>
<div class="four columns">
<select disabled="disabled" name="select3" id="select3" class="medium" >
<option value="0">Selecciona opci&oacute;n...</option>
</select>	
</div>
<div class="two columns">Lugar</div>
<div class="four columns"><input type="text" name="lugar" class="required ten"></div>

<div class="twelve columns"><h6>III.- Información de contrato</h6></div>
<div class="two columns">Organización que firma contrato</div>
<div class="ten columns">
	<select name="contratante" class="required large">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion
		WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY org_ficha_organizacion.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>"><? echo $f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="two columns">Fecha de contrato</div>
<div class="four columns"><input type="date" name="f_contrato" placeholder="aaaa-mm-dd" maxlength="10" class="required date seven"></div>
<div class="two columns">N. de cuenta bancaria</div>
<div class="four columns"><input type="text" name="n_cuenta" class="ten required"></div>
<div class="two columns">Entidad financiera</div>
<div class="ten columns">
	<select name="ifi" class="required large">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT sys_bd_ifi.cod_ifi, 
		sys_bd_ifi.descripcion
		FROM sys_bd_ifi
		ORDER BY sys_bd_ifi.descripcion ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f6=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f6['cod_ifi'];?>"><? echo $f6['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Aporte NEC PDSS II (S/.)</div>
<div class="ten columns"><input type="text" name="aporte_pdss" class="required number three"></div>
<div class="two columns">Aporte Organización (S/.)</div>
<div class="ten columns"><input type="text" name="aporte_org" class="required number three"></div>
<div class="two columns">Aporte Otros (S/.)</div>
<div class="ten columns"><input type="text" name="aporte_otro" class="required number three"></div>


<div class="twelve columns"><h6>III.- Afectación Presupuestal</h6></div>

<div class="two columns">Codigo POA</div>
<div class="ten columns">
	<select name="poa" class="required large">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT sys_bd_subactividad_poa.cod, 
		sys_bd_subactividad_poa.codigo, 
		sys_bd_subactividad_poa.nombre
		FROM sys_bd_subactividad_poa
		WHERE sys_bd_subactividad_poa.periodo='$anio'
		ORDER BY sys_bd_subactividad_poa.codigo ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
			echo "<option value='".$f1['cod']."'>".$f1['codigo']."-".$f1['nombre']."</option>";
		}
		?>
	</select>
</div>
<div class="two columns">Fte. Fto.</div>
<div class="ten columns">
	<select name="fte_fto" class="required large">
		<option value="" selected="selected">Seleccionar</option>
		<?php
		$sql="SELECT sys_bd_fuente_fto.cod, 
		sys_bd_fuente_fto.descripcion
		FROM sys_bd_fuente_fto";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
			echo "<option value='".$f2['cod']."'>".$f2['descripcion']."</option>";
		}
		?>
	</select>
</div>






<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="intercon.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
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
<script type="text/javascript" src="../plugins/jquery.js"></script>  
  
<!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>  

<script type="text/javascript" src="../plugins/combo_dinamico/select_dependientes_3_niveles_poa.js"></script>  

<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>  
</body>
</html>
