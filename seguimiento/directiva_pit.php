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
<dd  class="active"><a href="">Actualización de Juntas Directivas</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_directiva_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE" onsubmit="return checkSubmit();">



<div class="twelve columns"><h6>I.- Añadir Junta directiva</h6></div>


<table>
	<thead>
		<tr>
			<th>DNI</th>
			<th>Nombres</th>
			<th>A. Paterno</th>
			<th>A. Materno</th>
			<th>Sexo</th>
			<th>Fec. Nac.</th>
			<th>Cargo</th>
			<th>Vigencia</th>
		</tr>
	</thead>
	
	<tbody>
<?
for ($i = 1; $i <= 5; $i++) 
{
?>
		<tr>
			<td><input type="text" name="dni[]" maxlength="8" class="ten dni"></td>
			<td><input type="text" name="nombre[]" class="ten"></td>
			<td><input type="text" name="apellidop[]" class="ten" placeholder="Paterno"></td>
			<td><input type="text" name="apellidom[]" class="ten" placeholder="Materno"></td>
			<td><select name="sexo[]" class="eleven"><option value="" selected="selected">Seleccionar</option><option value="1">M</option><option value="0">F</option></select></td>
			<td><input type="date" name="fecha[]" class="ten" placeholder="aaaa-mm-dd" maxlength="10"></td>
			<td><select name="cargo[]" class="ten">
<option value="" selected="selected">Seleccionar</option>
<?
$sql="SELECT * FROM sys_bd_cargo_directivo";
$result=mysql_query($sql) or die (mysql_error());
while($r3=mysql_fetch_array($result))
{
?>
<option value="<? echo $r3['cod_cargo'];?>"><? echo $r3['descripcion'];?></option>
<?
}
?>
</select></td>
			<td><input type="date" name="vigencia[]" class="date" placeholder="aaaa-mm-dd" maxlength="10"></td>
		</tr>
<?
}
?>		
	</tbody>
	
</table>



<div class="twelve columns"><h6>II.- Directiva Vigente</h6></div>

<table class="responsive">
	<thead>
		<tr>
			<th>DNI</th>
			<th class="seven">Nombres y apellidos</th>
			<th>Cargo</th>
			<th>Vigencia hasta</th>
			<th>Vigente</th>
			<th>Retirado</th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno, 
	org_ficha_directiva_taz.f_nacimiento, 
	org_ficha_directiva_taz.f_termino, 
	org_ficha_directiva_taz.vigente, 
	sys_bd_cargo_directivo.descripcion AS cargo
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN org_ficha_directiva_taz ON org_ficha_directiva_taz.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_directiva_taz.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directiva_taz.cod_cargo_directivo
WHERE pit_bd_ficha_pit.cod_pit='$id'
ORDER BY org_ficha_directiva_taz.nombre ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($r1=mysql_fetch_array($result))
	{
		$cad=$r1['n_documento'];
	
	?>
		<tr>
			<td><? echo $r1['n_documento'];?></td>
			<td><? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?></td>
			<td><? echo $r1['cargo'];?></td>
			<td><? echo fecha_normal($r1['f_termino']);?></td>
			<td><input type="radio" name="vigente[<? echo $cad;?>]" value="1" <? if ($r1['vigente']==1) echo "checked";?>></td>
			<td><input type="radio" name="vigente[<? echo $cad;?>]" value="0" <? if ($r1['vigente']==0) echo "checked";?>></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
	
</table>


<div class="twelve columns">
	
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=directiva" class="secondary button">Finalizar</a>
	
	
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
