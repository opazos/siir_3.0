<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.cod_tipo_doc, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_pdn='$id'";
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
<dd  class="active"><a href="">Junta Directiva Vigente del PDN: <? echo $r1['nombre'];?></a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_directiva_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>I.- Añadir Nuevos Directivos</h6></div>

<table>
<thead>
	<tr>
		<th>Nº</th>
		<th class="six">Nombres y apellidos completos</th>
		<th class="five">Cargo</th>
		<th>Vigencia hasta</th>
	</tr>
</thead>	
<?
for($i=1;$i<=5;$i++)
{
?>	
	<tr>
		<td><? echo $i;?></td>
		<td>
			<select name="nombre[]">
				<option value="" selected="selected">Seleccionar</option>
				<?
				$sql="SELECT org_ficha_usuario.n_documento, 
				org_ficha_usuario.nombre, 
				org_ficha_usuario.paterno, 
				org_ficha_usuario.materno
				FROM org_ficha_usuario INNER JOIN pit_bd_ficha_pdn ON org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
				WHERE pit_bd_ficha_pdn.cod_pdn='$id'
				ORDER BY org_ficha_usuario.nombre ASC";
				$result=mysql_query($sql) or die (mysql_error());
				while($f1=mysql_fetch_array($result))
				{
				?>
				<option value="<? echo $f1['n_documento'];?>"><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></option>
				<?
				}
				?>
			</select>
		</td>
		<td>
			<select name="cargo[]">
				<option value="" selected="selected">Seleccionar</option>
				<?
				$sql="SELECT * FROM sys_bd_cargo_directivo ORDER BY cod_cargo";
				$result=mysql_query($sql) or die (mysql_error());
				while($f2=mysql_fetch_array($result))
				{
				?>
				<option value="<? echo $f2['cod_cargo'];?>"><? echo $f2['descripcion'];?></option>
				<?
				}
				?>
			</select>
		</td>
		<td><input type="date" name="f_vigencia[]" class="date" placeholder="aaaa-mm-dd" maxlength="10"></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><hr/></div>
<div class="twelve columns"><h6>II.- Indicar la Directiva Vigente</h6></div>
<table>
<thead>
	<tr>
		<th>Nº</th>
		<th>DNI</th>
		<th class="five">Nombres y apellidos completos</th>
		<th class="four">Cargo</th>
		<th>Vigencia hasta</th>
		<th>Vigente</th>
		<th>No vigente</th>
	</tr>
</thead>
<?
$num=0;
$sql="SELECT org_ficha_directivo.cod_directivo, 
	org_ficha_directivo.n_documento, 
	sys_bd_cargo_directivo.descripcion AS cargo, 
	org_ficha_directivo.f_termino, 
	org_ficha_directivo.vigente, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_directivo INNER JOIN pit_bd_ficha_pdn ON org_ficha_directivo.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_directivo.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_pdn='$id'
ORDER BY org_ficha_directivo.f_termino ASC, org_ficha_directivo.cod_cargo ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
$cad=$f3['cod_directivo'];
$num++
?>	
	<tr>
		<td><? echo $num;?></td>
		<td><? echo $f3['n_documento'];?></td>
		<td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
		<td><? echo $f3['cargo'];?></td>
		<td><? echo fecha_normal($f3['f_termino']);?></td>
		<td><input type="radio" name="vigente[<? echo $cad;?>]" value="1" <? if ($f3['vigente']==1) echo "checked";?>></td>
		<td><input type="radio" name="vigente[<? echo $cad;?>]" value="0" <? if ($f3['vigente']==0) echo "checked";?>></td>
	</tr>
<?
}
?>	
</table>

<div class="twelve columns"><hr/></div>
<div class="twelve columns">
	
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=directiva" class="secondary button">Finalizar</a>
	
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
