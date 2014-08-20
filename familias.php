<?
require("funciones/sesion.php");
require("funciones/error_page.php");
include("funciones/funciones.php");
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
   <link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="stylesheets/foundation.css">
  <link rel="stylesheet" href="stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="rtables/responsive-tables.css">
  
  <script src="javascripts/modernizr.foundation.js"></script>
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

<div class="three panel columns">
<ul class="nav-bar vertical">
<li class="active"><a href="familias.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Consistenciar Familias</a></li>
<li><a href="familias.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Ficha</a></li>
</ul>
<hr>
</div>

<div class="nine columns">
<!-- contenido -->        
<? echo $mensaje;?>
<!-- Contenedores -->
      <dl class="contained tabs">
        <dd class="active"><a href="">Organizaciones</a></dd>
      </dl>
<!-- Termino contenedores -->
<ul class="tabs-content contained">
<li id="req" class="active">
<div class="row collapse">

<? include("plugins/buscar/buscador.html");?>

<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº Doc.</th>
			<th>Tipo documento</th>
			<th>Nombre</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>

<tbody>		
<?
$num=0;
if ($row['cod_dependencia']==001)
{
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_organizacion.cod_tipo_doc, 
	sys_bd_tipo_doc.descripcion AS tipo_doc
FROM sys_bd_tipo_doc INNER JOIN org_ficha_organizacion ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE org_ficha_organizacion.cod_tipo_org <>6
ORDER BY org_ficha_organizacion.nombre ASC";
}
else
{
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	org_ficha_organizacion.cod_tipo_doc, 
	sys_bd_tipo_doc.descripcion AS tipo_doc
FROM sys_bd_tipo_doc INNER JOIN org_ficha_organizacion ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE org_ficha_organizacion.cod_tipo_org <>6 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.nombre ASC";
}
$result=mysql_query($sql) or die(mysql_error());
while($fila=mysql_fetch_array($result))
{
$num++
?>		
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['tipo_doc'];?></td>
			<td><? echo $fila['nombre'];?></td>
			<td><? echo $fila['oficina'];?></td>
			<td>
			<?
			if ($modo==edit)
			{
			?>
			<a href="consistenciar_familias.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod1=<? echo $fila['cod_tipo_doc'];?>&cod2=<? echo $fila['n_documento'];?>" class="small primary button">Consistenciar</a>
			<?
			}
			elseif($modo==imprime)
			{
			?>
			<a href="print/print_familia.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod1=<? echo $fila['cod_tipo_doc'];?>&cod2=<? echo $fila['n_documento'];?>" class="small success button">Imprimir</a>
			<?
			}
			?>
			</td>
		</tr>
<?
}
?>		
	</tbody>
</table>
</div>
</li>
</ul>
	        
	        
<!-- fin del contenido -->
</div>
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
  <script type="text/javascript" src="plugins/buscar/js/buscar-en-tabla.js"></script>
</body>
</html>
