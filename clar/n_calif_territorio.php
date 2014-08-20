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
<form name="form5" class="custom" method="post" action="gestor_calif_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="two columns">Seleccionar participante</div>
<div class="ten columns">
	<select name="participante">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT gcac_pit_participante_concurso.cod_participante, 
		org_ficha_organizacion.nombre
		FROM pit_bd_ficha_pit INNER JOIN gcac_pit_participante_concurso ON pit_bd_ficha_pit.cod_pit = gcac_pit_participante_concurso.cod_pit
		INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_organizacion.n_documento = pit_bd_ficha_pit.n_documento_taz
		WHERE gcac_pit_participante_concurso.cod_concurso='$cod'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_participante'];?>"><? echo $f1['nombre'];?></option>
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
			<td>Presentación del Stand: Diversidad de instrumentos para representar sus mapas territoriales: maquetas, paneles fotograficos u otros elementos innovativos.</td>
			<td><input type="text" name="p1" class="required number"></td>
		</tr>
		<tr>
			<td>El representante del PIT presenta las caracteristicas principales del territorio y señala los avances y resultados producto de la ejecución de las iniciativas (PGRN, PDN) utilizando su mapa territorial.</td>
			<td><input type="text" name="p2" class="required number"></td>
		</tr>
		<tr>
			<td>El animador territorial señala las principales actividades realizadas en el territorio y la forma como las familias se organizaron para la ejecución de sus iniciativas de PGRN, PDN.</td>
			<td><input type="text" name="p3" class="required number"></td>
		</tr>
		<tr>
			<td>Muestra sus avances de resultados y conocimientos adquiridos con la ejecución de PGRN N. de familias, concursos interfamiliares, actividades, valorización.</td>
			<td><input type="text" name="p4" class="required number"></td>
		</tr>
		
		<tr>
			<td>Claridad, coherencia y participación de los integrantes para presentar y sustentar su PGRN (Mujeres, jovenes, hombres)</td>
			<td><input type="text" name="p5" class="required number"></td>
		</tr>
		<tr>
			<td>Evidencias sobre innovaciones introducidas en el PGRN</td>
			<td><input type="text" name="p6" class="required number"></td>
		</tr>
		<tr>
			<td>Acciones, gestiones que permitan la continuidad de las actividades del PGRN</td>
			<td><input type="text" name="p7" class="required number"></td>
		</tr>
		<tr>
			<td>Muestra su archivador con su documentación organizada</td>
			<td><input type="text" name="p8" class="required number"></td>
		</tr>
		<tr>
			<td>Muestra sus avances, resultados y conocimientos adquiridos con la ejecución de familias, ventas, contactos comerciales</td>
			<td><input type="text" name="p9" class="required number"></td>
		</tr>
		<tr>
			<td>Claridad, coherencia y participación de los integrantes para presentar y sustentar su PDN (Mujeres, jóvenes, hombres)</td>
			<td><input type="text" name="p10" class="required number"></td>
		</tr>
		<tr>
			<td>Evidencias sobre innovaciones introducidas en el PDN</td>
			<td><input type="text" name="p11" class="required number"></td>
		</tr>
		<tr>
			<td>Acciones, gestiones que permitan la continuidad de las actividades del PDN</td>
			<td><input type="text" name="p12" class="required number"></td>
		</tr>
		<tr>
			<td>Muestra su archivador con su documentación organizada</td>
			<td><input type="text" name="p13" class="required number"></td>
		</tr>
		<tr>
			<td>Comidas Típicas: Presentación, capacidad para explicar el origen del plato típico y como se relaciona con las actividades del territorio: ingredientes y forma de preparación</td>
			<td><input type="text" name="p14" class="required number"></td>
		</tr>
		<tr>
			<td>Danza Típica: Referencia del origen, significado de la danza, originalidad de la danza y vestimenta, despliegue y su articulacion con las actividades del territorio</td>
			<td><input type="text" name="p15" class="required number"></td>
		</tr>
		<tr>
			<td>Es muy probable que en caso de ganar, parte del premio se invierta en la iniciativa.</td>
			<td><input type="text" name="p16" class="required number"></td>
		</tr>
	</tbody>
	
</table>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="calif_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica" class="secondary button">Finalizar</a>
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
	$sql="SELECT gcac_ficha_territorio.p_total, 
	gcac_jurado_concurso.nombres, 
	gcac_jurado_concurso.apellidos, 
	gcac_ficha_territorio.cod_ficha, 
	org_ficha_taz.nombre
FROM gcac_jurado_concurso INNER JOIN gcac_ficha_territorio ON gcac_jurado_concurso.cod_jurado = gcac_ficha_territorio.cod_jurado
	 INNER JOIN gcac_pit_participante_concurso ON gcac_pit_participante_concurso.cod_participante = gcac_ficha_territorio.cod_participante
	 INNER JOIN pit_bd_ficha_pit ON gcac_pit_participante_concurso.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE gcac_pit_participante_concurso.cod_concurso='$cod'";
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
