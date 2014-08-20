<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);
?>
<!DOCTYPE html>
<html class="no-js" lang="en"> <!--<![endif]-->
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
		<li class="active"><a href="n_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar Territorio</a></li>
		<li><a href="territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar información</a></li>
		<li><a href="territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar Territorio</a></li>
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
			<?
			$num=0;

			if ($row['cod_dependencia']==001)
			{
			$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
				org_ficha_taz.n_documento, 
				org_ficha_taz.nombre, 
				sys_bd_dependencia.nombre AS oficina,
				org_ficha_taz.cod_tipo_doc
			FROM sys_bd_tipo_doc INNER JOIN org_ficha_taz ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
				 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
			WHERE
			org_ficha_taz.n_documento NOT LIKE '0000-0%'
			ORDER BY org_ficha_taz.n_documento ASC";
			}
			else
			{
			$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
				org_ficha_taz.n_documento, 
				org_ficha_taz.nombre, 
				sys_bd_dependencia.nombre AS oficina,
				org_ficha_taz.cod_tipo_doc
			FROM sys_bd_tipo_doc INNER JOIN org_ficha_taz ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_taz.cod_tipo_doc
				 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
			WHERE org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."' AND
			org_ficha_taz.n_documento NOT LIKE '0000-0%'
			ORDER BY org_ficha_taz.n_documento ASC";
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
						<a href="m_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_tipo_doc'].",".$fila['n_documento'];?>" class="tiny button">Editar</a>
						<?
						}
						elseif($modo==delete)
						{
						?>
						<a href="gestor_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_tipo_doc'].",".$fila['n_documento'];?>&action=DELETE" class="tiny alert button" onclick="return confirm('Va a eliminar permanentemente este registro, desea proceder ?')">Eliminar</a>
						<?
						}
						?>
						</td>
					</tr>
			<?
			}
			?>		
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
