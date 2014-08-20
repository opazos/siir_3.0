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
  <div class="twelve columns">
    <div class="panel">
    	<div class="row">
    		<div class="three columns">
			<ul class="nav-bar vertical">
			<li class="has-flyout active"><a href="">Nueva Organizacion</a>
			<ul class="flyout">
			<li><a href="n_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit">Nueva Organizacion perteneciente a un territorio</a></li>
			<li><a href="n_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Nueva Organizacion independiente</a></li>	
			</ul>
			</li>
			<li><a href="organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar información</a></li>
			<li><a href="organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar Organizacion</a></li>
			<li><a href="organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Reporte</a></li>
			</ul>
			<hr>
			</div>

			<div class="nine columns">
				<? include("plugins/buscar/buscador.html");?>

<table class="responsive" id="lista">
	<thead>
		<tr>
			<th><small>N.</small></th>
			<th><small>Documento</small></th>
			<th><small>Tipo documento</small></th>
			<th><small>Nombre</small></th>
			<th><small>Oficina</small></th>
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
	WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
	ORDER BY org_ficha_organizacion.nombre ASC";
	}
	$result=mysql_query($sql) or die(mysql_error());
	while($fila=mysql_fetch_array($result))
	{
	$num++
	?>		
	<tr>
		<td><small><? echo $num;?></small></td>
		<td><small><? echo $fila['n_documento'];?></small></td>
		<td><small><? echo $fila['tipo_doc'];?></small></td>
		<td><small><? echo $fila['nombre'];?></small></td>
		<td><small><? echo $fila['oficina'];?></small></td>
		<td>
			<?
			if ($modo==edit)
			{
			?>
			<a href="m_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $fila['cod_tipo_doc'];?>&numero=<? echo $fila['n_documento'];?>" class="tiny button">Editar</a>
			<?
			}
			elseif($modo==delete)
			{
			?>
			<a href="gestor_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $fila['cod_tipo_doc'];?>&numero=<? echo $fila['n_documento'];?>&action=DELETE" class="tiny delete button" onclick="return confirm('Va a eliminar permanentemente este registro, desea proceder ?')">Eliminar</a>
			<?
			}
			elseif($modo==imprime)
			{
			?>
			<a href="print/print_report_org.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $fila['cod_tipo_doc'];?>&numero=<? echo $fila['n_documento'];?>" class="tiny success button">Imprimir</a>
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
