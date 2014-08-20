<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM gcac_concurso_clar WHERE cod_concurso='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$max_ganador=$r1['n_ganadores'];

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
<dd  class="active"><a href="">Premiación de Ganadores</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_premia_map.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=UPDATE" onsubmit="return checkSubmit();">

<table>
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nombre del participante</th>
			<th>Rubro en el que concursa</th>
			<th>Puntaje</th>
			<th>Puesto</th>
			<th>Premio</th>
		</tr>
	</thead>
	
	<tbody>
	<?
		$num=0;
		$sql="SELECT gcac_participante_concurso.cod_participante, 
		gcac_participante_concurso.descripcion, 
		gcac_participante_concurso.puntaje, 
		gcac_participante_concurso.puesto, 
		gcac_participante_concurso.premio, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion INNER JOIN gcac_participante_concurso ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
		WHERE gcac_participante_concurso.cod_concurso='$cod'
		ORDER BY gcac_participante_concurso.puntaje DESC LIMIT 0,$max_ganador";
		$result=mysql_query($sql) or die (mysql_error());
		while($fila=mysql_fetch_array($result))
		{
			$cad=$fila['cod_participante'];
			$num++
		
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $fila['nombre'];?></td>
			<td><? echo $fila['descripcion'];?></td>
			<td><? echo number_format($fila['puntaje'],2);?></td>
			<td><input type="text" name="puesto[<? echo $cad;?>]" value="<? echo $fila['puesto'];?>" class="required digits seven"></td>
			<td><input type="text" name="premio[<? echo $cad;?>]" value="<? echo $fila['premio'];?>" class="required number seven"></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
</table> 

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="calif_map.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica" class="secondary button">Finalizar</a>
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
