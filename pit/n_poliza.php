<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
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
<dd  class="active"><a href="">Registro del Asegurado</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">
	
	<div class="twelve columns"><h6>I.- Si la persona a registrar es un usuario del Proyecto, seleccionelo de la lista:</h6></div>
	
	<div class="twelve columns">
		
		<select name="user" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT DISTINCT org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
			$result=mysql_query($sql) or die (mysql_error());
			while($r1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $r1['n_documento'];?>"><? echo $r1['n_documento']." - ".$r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?></option>
			<?
			}
			?>
		</select>
		
	</div>

<div class="twelve columns"><br/></div>
	
<!-- Generamos un acordeon para darle vistosidad a los registros -->
<div class="twelve columns">
<ul class="accordion">
<li>
<div class="title">
<h5>Si en caso la persona no existiera en la base de datos de usuarios del Proyecto, proceda a registrarla</h5>
</div>
<div class="content">
<div class="row">
<div class="two columns">Nº DNI</div>
<div class="four columns">

<input type="text" name="dni" class="five digits" maxlength="8"></div>


<div class="two columns">Fecha Nac.</div>
<div class="four columns"><input type="date" name="f_nacimiento" maxlength="10" placeholder="aaaa-mm-dd" class="seven date" ></div>

<div class="two columns">Nombres</div>
<div class="ten columns"><input type="text" name="nombre"></div>

<div class="two columns">Apellido paterno</div>
<div class="four columns"><input type="text" name="paterno"></div>

<div class="two columns">Apellido materno</div>
<div class="four columns"><input type="text" name="materno"></div>

<div class="two columns">Sexo</div>
<div class="four columns">
<select name="sexo" class="five">
	<option value="" selected="selected">Seleccionar</option>
	<option value="1">Hombre</option>
	<option value="0">Mujer</option>
</select>
</div>

<div class="two columns">Ubigeo</div>
<div class="four columns"><input type="text" name="ubigeo" class="five"></div>

<div class="two columns">Direccion</div>
<div class="ten columns"><input type="text" name="direccion"></div>



<div class="two columns">Nº Hijos < 5 años</div>
<div class="four columns"><input type="text" name="hijo" class="required digits five"></div>

<div class="two columns">Jefe de Familia / Pareja</div>
<div class="four columns">
	<select name="jefe" class="five">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">Titular</option>
		<option value="0">Conyuge</option>
	</select>
</div>

</div>
</div>
</li>

</ul>
</div>
<!-- fin del acordeon -->

<div class="twelve columns"><h6>II.- Datos de la Poliza de Seguro</h6></div>	
	
<div class="two columns">Nº de póliza</div>
<div class="four columns"><input type="text" name="n_poliza" class="seven required digits" maxlength="6"></div>	
<div class="two columns">Fecha de emisión de la póliza</div>
<div class="four columns"><input type="date" name="f_emision" class="required date seven" maxlength="10" placeholder="aaaa-mm-dd"></div>
<div class="two columns">Tipo de seguro</div>
<div class="ten columns">
	<select name="tipo_seguro">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_tipo_seguro";
		$result=mysql_query($sql) or die (mysql_error());
		while($r2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r2['cod'];?>"><? echo $r2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><h6>III.- Aportes y contrapartidas</h6></div>

<div class="two columns">Aporte Proyecto (S/.)</div>
<div class="four columns"><input type="text" name="aporte_pdss" class="required number five"></div>
<div class="two columns">Contrapartida Usuario (S/.)</div>
<div class="four columns"><input type="text" name="aporte_org" class="required number five"></div>

<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edita" class="secondary button">Finalizar</a>
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

<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>
  
</body>
</html>
