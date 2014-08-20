<?
require("funciones/sesion.php");
include("funciones/funciones.php");
include("funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre, 
	sys_bd_subactividad_poa.unidad
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.cod='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

$sql="SELECT * FROM sys_bd_detalle_poa WHERE cod='$id'";
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
   <link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="stylesheets/foundation.css">
  <link rel="stylesheet" href="stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="rtables/responsive-tables.css">
  
  <script src="javascripts/modernizr.foundation.js"></script>
  <script src="javascripts/btn_envia.js"></script>
  <script src="rtables/javascripts/jquery.min.js"></script>
  <script src="rtables/responsive-tables.js"></script>
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
<dd  class="active"><a href="">Modificar Información</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_detalle_poa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="two columns">Subactividad</div>
<div class="ten columns"><? echo $f1['codigo']." - ".$f1['nombre'];?></div>
<div class="two columns">Unidad de medida</div>
<div class="ten columns"><? echo $f1['unidad'];?></div>
<div class="twelve columns"><hr/></div>


	
	<div class="two columns">Oficina local</div>
	<div class="four columns">
	<input type="hidden" name="codigo" value="<? echo $row['cod'];?>">
	
		<select name="oficina">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT sys_bd_dependencia.cod_dependencia, 
			sys_bd_dependencia.nombre
			FROM sys_bd_dependencia";
			$result=mysql_query($sql) or die (mysql_error());
			while($r1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $r1['cod_dependencia'];?>" <? if ($row['cod_dependencia']==$r1['cod_dependencia']) echo "selected";?>><? echo $r1['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="two columns">Periodo</div>
	<div class="four columns">
		<select name="periodo">
			<option value="" selected="selected">Seleccionar</option>
			<option value="2011" <? if ($row['anio']==2011) echo "selected";?>>2011</option>
			<option value="2012" <? if ($row['anio']==2012) echo "selected";?>>2012</option>
			<option value="2013" <? if ($row['anio']==2013) echo "selected";?>>2013</option>
		</select>
	</div>
	
	
	<div class="two columns">Meta fisica</div>
	<div class="ten columns"><input type="text" name="meta" class="required number two" value="<? echo $row['meta_fisica'];?>"></div>
	
	<div class="twelve columns"><h6>Montos Presupuestados NEC PDSS</h6></div>
	<div class="four columns"><h6>FIDA (S/.)</h6></div>
	<div class="four columns"><h6>RO (S/.)</h6></div>
	<div class="four columns"><h6>Donación (S/.)</h6></div>
	
	<div class="four columns"><input type="text" name="fida" class="required number seven" value="<? echo $row['monto_fida'];?>"></div>
	<div class="four columns"><input type="text" name="ro" class="required number seven" value="<? echo $row['monto_ro'];?>"></div>
	<div class="four columns"><input type="text" name="donacion" class="required number seven" value="<? echo $row['monto_donacion'];?>"></div>
	
	<div class="twelve columns"><h6>Contrapartidas</h6></div>	
	<div class="two columns">Cofinanciadores (S/.)</div>
	<div class="ten columns"><input type="text" name="cofinanciador" class="required number two" value="<? echo $row['monto_municipio'];?>"></div>
	
	<div class="two columns">Usuarios (S/.)</div>
	<div class="ten columns"><input type="text" name="usuario" class="required number two" value="<? echo $row['monto_usuario'];?>"></div>	
	
	<div class="twelve columns"><br/></div>
	
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<a href="detalle_poa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=edit" class="secondary button">Cancelar</a>
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
<? include("footer.php");?>


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
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
  <!-- VALIDADOR DE FORMULARIOS -->
  <script src="plugins/validation/jquery.validate.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="plugins/validation/stylesheet.css" />
  <script type="text/javascript" src="plugins/validation/jquery.maskedinput.js"></script>
  <script type="text/javascript" src="plugins/validation/mktSignup.js"></script> 
  
</body>
</html>