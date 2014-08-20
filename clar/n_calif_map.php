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
<dd  class="active"><a href="">Panel de calificaciones</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<div class="panel">
<div class="row">
<form name="form5" class="custom" method="post" action="gestor_calif_map.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="two columns">Seleccionar participante</div>
<div class="ten columns">
	<select name="participante">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT gcac_participante_concurso.cod_participante, 
		gcac_participante_concurso.descripcion, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion INNER JOIN gcac_participante_concurso ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
		WHERE gcac_participante_concurso.cod_concurso='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_participante'];?>"><? echo $f1['nombre']." - ".$f1['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Seleccionar jurado</div>
<div class="ten columns">
	<select name="jurado">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM gcac_jurado_concurso WHERE cod_concurso='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_jurado'];?>"><? echo $f2['nombres']." ".$f2['apellidos'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><hr/></div>
<table>
	<thead>
		<tr>
			<th class="ten">Criterios de Evaluación</th>
			<th>Puntaje Asignado</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Originalidad y riqueza de los contenidos de los mapas territoriales</td>
			<td><input type="text" name="p1" class="required number"></td>
		</tr>
		<tr>
			<td>Calidad, imaginación, creatividad y arte para la construcción de los mapas territoriales</td>
			<td><input type="text" name="p2" class="required number"></td>
		</tr>
		<tr>
			<td>Diversidad de instrumentos para representar sus mapas territoriales: maquetas, paneles fotográficos u otros elementos innovativos</td>
			<td><input type="text" name="p3" class="required number"></td>
		</tr>
		<tr>
			<td>Claridad, coherencia y participación de los integrantes para presentar y sustentar sus mapas territoriales</td>
			<td><input type="text" name="p4" class="required number"></td>
		</tr>
	</tbody>
	
</table>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="calif_map.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica" class="secondary button">Finalizar</a>
</div>
	
</form>
</div>
</div>
<div class="twelve columns"><hr/></div>

<table>
	<thead>
		<tr>
			<th>Nº</th>
			<th class="five">Nombre del Participante</th>
			<th class="five">Jurado calificador</th>
			<th>Puntaje</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$num=0;
	$sql="SELECT gcac_ficha_mapa.cod_ficha, 
	gcac_ficha_mapa.p_total, 
	gcac_participante_concurso.descripcion, 
	org_ficha_organizacion.nombre, 
	gcac_jurado_concurso.nombres, 
	gcac_jurado_concurso.apellidos
FROM gcac_participante_concurso INNER JOIN gcac_ficha_mapa ON gcac_participante_concurso.cod_participante = gcac_ficha_mapa.cod_participante
	 INNER JOIN gcac_jurado_concurso ON gcac_jurado_concurso.cod_jurado = gcac_ficha_mapa.cod_jurado
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
WHERE gcac_participante_concurso.cod_concurso='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
			$num++
	
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $fila['nombre'];?></td>
			<td><? echo $fila['nombres']." ".$fila['apellidos'];?></td>
			<td><? echo number_format($fila['p_total'],2);?></td>
			<td><a href="gestor_calif_map.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $fila['cod_ficha'];?>&action=DELETE" class="small alert button">Eliminar</a></td>
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
