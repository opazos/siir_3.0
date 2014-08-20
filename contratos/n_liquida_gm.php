<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM gm_ficha_evento WHERE cod_ficha_gm='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);



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
<dd  class="active"><a href="">Rendir contrato de gira motivacional</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_contrato_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=LIQUIDA" onsubmit="return checkSubmit();">
	
	<div class="two columns">Nº contrato a rendir</div>
	<div class="four columns"><input type="text" name="n_contrato" class="required digits five" readonly="readonly" value="<? echo $r1['n_contrato'];?>"></div>
	<div class="two columns">Fecha de rendicion</div>
	<div class="four columns"><input type="date" name="f_rendicion" class="required date five" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>
	<div class="two columns">Dirigido a</div>
	<div class="ten columns">
		<select name="dirigido" class="required">
			<option value="" selected="selected">Seleccionar</option>
<?
$sql="SELECT * FROM sys_bd_personal WHERE cod_tipo_usuario='A' AND cod_dependencia='001'";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
?>
            <option value="<? echo $r2['n_documento'];?>"><? echo $r2['nombre']." ".$r2['apellido'];?></option>
            <?
}
?>
		</select>
	</div>
	<div class="twelve columns">Resultados alcanzados</div>
	<div class="twelve columns"><textarea name="resultado" rows="5"></textarea></div>
	<div class="twelve columns">Problemas u observaciones</div>
	<div class="twelve columns"><textarea name="problema" rows="5"></textarea></div>	
	
	<div class="twelve columns"><h6>II.- Participantes en el evento</h6></div>
	<table class="responsive">
		<tbody>
			<tr>
				<th class="seven">Clasificacion</th>
				<th>Nº Varones</th>
				<th>Nº Mujeres</th>
				<th>Nº Jovenes</th>
			</tr>
			
			<tr>
				<td>Autoridades Gubernamentales</td>
				<td><input type="text" name="a1" class="required digits"></td>
				<td><input type="text" name="a2" class="required digits"></td>
				<td><input type="text" name="a3" class="required digits"></td>
			</tr>

			<tr>
				<td>Representantes de Juntas Directivas</td>
				<td><input type="text" name="b1" class="required digits"></td>
				<td><input type="text" name="b2" class="required digits"></td>
				<td><input type="text" name="b3" class="required digits"></td>
			</tr>

			<tr>
				<td>Otros asistentes</td>
				<td><input type="text" name="c1" class="required digits"></td>
				<td><input type="text" name="c2" class="required digits"></td>
				<td><input type="text" name="c3" class="required digits"></td>
			</tr>			
			
						
		</tbody>
	</table>
	
	<div class="twelve columns"><h6>III.- Ejecucion del presupuesto</h6></div>
	
	<div class="three columns">Monto ejecutado Proyecto (S/.)</div>
	<div class="three columns">Monto ejecutado Organización (S/.)</div>	
	<div class="three columns">Monto ejecutado Municipio (S/.)</div>	
	<div class="three columns">Monto ejecutado Otro (S/.)</div>		
	
	<div class="three columns"><input type="text" name="ejec_pdss" class="required number nine"></div>
	<div class="three columns"><input type="text" name="ejec_org" class="required number nine"></div>
	<div class="three columns"><input type="text" name="ejec_mun" class="required number nine"></div>
	<div class="three columns"><input type="text" name="ejec_otr" class="required number nine"></div>			
	
	<div class="twelve columns"><br/></div>
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=liquida" class="primary button">Cancelar</a>
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
