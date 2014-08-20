<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento
FROM org_ficha_organizacion
WHERE org_ficha_organizacion.cod_tipo_doc='$cod1' AND
org_ficha_organizacion.n_documento='$cod2'";
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
<dd  class="active"><a href="">Modulo para el consistenciamiento de Familias</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_consistenciar_familias.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod1=<? echo $cod1;?>&cod2=<? echo $cod2;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="three columns"><h6>Nombre de la Organización :</h6></div>
<div class="nine columns"><h6><? echo $r1['nombre'];?></h6></div>
<div class="twelve columns"><hr/></div>

<table class="responsive" id="lista">

<thead>
	<tr>
		<th>Nº</th>
		<th>Titular</th>
		<th>Pareja</th>
	</tr>
</thead>

<tbody>
<?
$num=0;
	$sql="SELECT org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	org_ficha_usuario.n_documento_conyuge, 
	org_ficha_usuario.titular
FROM org_ficha_usuario
WHERE org_ficha_usuario.cod_tipo_doc_org='$cod1' AND
org_ficha_usuario.n_documento_org='$cod2' AND
org_ficha_usuario.titular=1
ORDER BY org_ficha_usuario.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$num++
?>
	<tr>
		<td><? echo $num;?></td>
		<td>
		<select name="titular[<? echo $num;?>]" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT org_ficha_usuario.n_documento, 
			org_ficha_usuario.nombre, 
			org_ficha_usuario.paterno, 
			org_ficha_usuario.materno
			FROM org_ficha_usuario
			WHERE org_ficha_usuario.cod_tipo_doc_org='$cod1' AND
			org_ficha_usuario.n_documento_org='$cod2'";
			$result1=mysql_query($sql) or die (mysql_error());
			while($f2=mysql_fetch_array($result1))
			{
			?>
			<option value="<? echo $f2['n_documento'];?>" <? if ($f2['n_documento']==$fila['n_documento'] and $fila['titular']==1) echo "selected";?>><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></option>
			<?
			}
			?>
		</select>	
		</td>
		<td>
		<select name="pareja[<? echo $num;?>]" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT org_ficha_usuario.n_documento, 
			org_ficha_usuario.nombre, 
			org_ficha_usuario.paterno, 
			org_ficha_usuario.materno
			FROM org_ficha_usuario
			WHERE org_ficha_usuario.cod_tipo_doc_org='$cod1' AND
			org_ficha_usuario.n_documento_org='$cod2'";
			$result2=mysql_query($sql) or die (mysql_error());
			while($f3=mysql_fetch_array($result2))
			{
			?>
			<option value="<? echo $f3['n_documento'];?>" <? if ($f3['n_documento']==$fila['n_documento_conyuge']) echo "selected";?>><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></option>
			<?
			}
			?>
		</select>	
		</td>
	</tr>
<?
}
?>	
</tbody>
	
</table>

	
<div class="twelve columns">
	<button type="submit" class="primary button">Guardar cambios</button>
	<a href="familias.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
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
  
  <!-- Combo Buscador -->
<link href="plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="plugins/combo_buscador/hyjack.js" type="text/javascript"></script>
<script src="plugins/combo_buscador/configuracion.js" type="text/javascript"></script>


</body>
</html>
