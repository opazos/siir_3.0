<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM epd_dj_evento WHERE cod_dj_evento='$id'";
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
<dd  class="active"><a href="">Nueva declaracion jurada</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_dj.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="two columns">Nº</div>
<div class="four columns"><input type="text" name="n_dj" class="required digits five" readonly="readonly" value="<? echo $row['n_dj_evento'];?>">
<input type="hidden" name="codigo" value="<? echo $row['cod_dj_evento'];?>">
</div>
<div class="two columns">Fecha de presentacion</div>
<div class="four columns"><input type="date" name="f_presentacion" class="required date five" maxlength="10" placeholder="aaaa-mm-dd" value="<? echo $row['f_presentacion'];?>"></div>
<div class="two columns">Fecha de inicio</div>
<div class="four columns"><input type="date" name="f_inicio" class="required date five" maxlength="10" placeholder="aaaa-mm-dd" value="<? echo $row['f_inicio'];?>"></div>
<div class="two columns">Fecha de termino</div>
<div class="four columns"><input type="date" name="f_termino" class="required date five" maxlength="10" placeholder="aaaa-mm-dd" value="<? echo $row['f_termino'];?>"></div>
<div class="twelve columns"><hr/></div>
<div class="twelve columns"><h6>Detalle de gastos</h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>Fecha</th>
			<th class="five">Descripcion</th>
			<th>Concepto</th>
			<th>Monto (S/.)</th>
		</tr>
		
<?
$sql="SELECT * FROM epd_detalle_dj_evento WHERE cod_dj_evento='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
	$cod=$r1['cod_detalle_dj'];
?>
		<tr>
			<td><input type="date" name="fechas[<? echo $cod;?>]" class="date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r1['f_declaracion'];?>"></td>
			<td><input type="text" name="descripcions[<? echo $cod;?>]" value="<? echo $r1['concepto'];?>"></td>
			<td>
				<select name="conceptos[<? echo $cod;?>]">
					<option value="" selected="selected">Seleccionar</option>
				<?
	            $sql="SELECT * FROM sys_bd_tipo_gasto";
	            $result1=mysql_query($sql) or die (mysql_error());
	            while($r2=mysql_fetch_array($result1))
	            {
	            ?>
            <option value="<? echo $r2['cod_tipo_gasto'];?>" <? if ($r2['cod_tipo_gasto']==$r1['cod_tipo_gasto']) echo "selected";?>><? echo $r2['descripcion'];?></option>
               <?
	            }
	            ?>
				</select>
				
			</td>
			<td><input type="text" name="montos[<? echo $cod;?>]" class="number" value="<? echo $r1['monto'];?>"></td>
		</tr>
<?
}
?>		
		
		<tr>
			<td colspan="5"><h6>Añadir nuevos registros</h6></td>
		</tr>
		
		
		
<?
for($i=1;$i<=20;$i++)
{
?>		
		<tr>
			<td><input type="date" name="fecha[]" class="date" placeholder="aaaa-mm-dd" maxlength="10"></td>
			<td><input type="text" name="descripcion[]"></td>
			<td>
				<select name="concepto[]">
					<option value="" selected="selected">Seleccionar</option>
				<?
	            $sql="SELECT * FROM sys_bd_tipo_gasto";
	            $result=mysql_query($sql) or die (mysql_error());
	            while($r3=mysql_fetch_array($result))
	            {
	            ?>
            <option value="<? echo $r3['cod_tipo_gasto'];?>"><? echo $r3['descripcion'];?></option>
               <?
	            }
	            ?>
				</select>
				
			</td>
			<td><input type="text" name="monto[]" class="number"></td>
		</tr>
<?
}
?>		
	</tbody>
</table>

<div class="twelve columns"><br/></div>
	
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="dj.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Cancelar</a>
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
