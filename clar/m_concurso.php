<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM gcac_concurso_clar WHERE cod_concurso='$id'";
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
<dd  class="active"><a href="">Registro de concursos</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">

<form name="form5" class="custom" method="post" action="gestor_concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="two columns">Fecha de concurso</div>
<div class="four columns"><input type="date" name="f_concurso" class="seven required date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_concurso'];?>"></div>
<div class="two columns">Tipo de concurso</div>
<div class="four columns">
<input type="hidden" name="codigo" value="<? echo $row['cod_concurso'];?>">
	<select name="tipo">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_tipo_concurso_clar";
		$result=mysql_query($sql) or die(mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['codigo'];?>" <? if ($row['cod_tipo_concurso']==$f1['codigo']) echo "selected";?>><? echo $f1['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"></div>
<div class="two columns">Nombre del evento</div>
<div class="ten columns"><input type="text" name="nombre" class="required eleven" value="<? echo $row['nombre'];?>"></div>

<div class="two columns">Departamento</div>
<div class="four columns"><input type="text" name="dep" class="required nine" value="<? echo $row['departamento'];?>"></div>
<div class="two columns">Provincia</div>
<div class="four columns"><input type="text" name="prov" class="required nine" value="<? echo $row['provincia'];?>"></div>
<div class="two columns">Distrito</div>
<div class="four columns"><input type="text" name="dist" class="required nine" value="<? echo $row['distrito'];?>"></div>
<div class="two columns">Lugar</div>
<div class="four columns"><input type="text" name="lugar" class="required nine" value="<? echo $row['lugar'];?>"></div>



<div class="two columns">Monto para premios (S/.)</div>
<div class="four columns"><input type="text" name="premio" class="required number five" value="<? echo $row['premio'];?>"></div>

<div class="two columns">Monto incentivo por participar (S/.)</div>
<div class="four columns"><input type="text" name="flat" class="required number five" value="<? echo $row['incentivo'];?>"></div>

<div class="two columns">Nº de participantes a premiar</div>
<div class="four columns"><input type="text" name="ganadores" class="required digits five" value="<? echo $row['n_ganadores'];?>"></div>
<div class="two columns">Factor para calcular el puntaje (Fijo)</div>
<div class="four columns"><input type="text" name="factor" class="required number five" value="<? echo $row['factor'];?>"></div>


<div class="two columns">Oficina que realiza el concurso</div>
<div class="ten columns">
	<select name="oficina">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_dependencia";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_dependencia'];?>" <? if ($f2['cod_dependencia']==$row['cod_dependencia']) echo "selected";?>><? echo $f2['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>


<div class="twelve columns"><h6>II.- Jurados Calificadores (Max. 3 Jurados)</h6></div>
<div class="two columns"><h6>Nº DNI</h6></div>
<div class="five columns"><h6>Nombres completos</h6></div>
<div class="five columns"><h6>Apellidos completos</h6></div>
<?
$sql="SELECT * FROM gcac_jurado_concurso WHERE cod_concurso='$id'";
$result=mysql_query($sql) or die(mysql_error());
while($f3=mysql_fetch_array($result))
{
	$cad=$f3['cod_jurado'];
?>
<div class="two columns"><input type="text" name="dni[<? echo $cad;?>]" class="require digits nine" maxlength="8" value="<? echo $f3['n_documento'];?>"></div>
<div class="five columns"><input type="text" name="nombres[<? echo $cad;?>]" class="require nine" value="<? echo $f3['nombres'];?>"></div>
<div class="five columns"><input type="text" name="apellidos[<? echo $cad;?>]" class="require nine" value="<? echo $f3['apellidos'];?>"></div>
<?
}
?>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambio</button>
	<a href="concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
