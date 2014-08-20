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
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->



</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
 <ul class="nav-bar vertical">
 <li class="has-flyout"><a href="">Módulo CIF</a>
 <ul class="flyout">
	 <li><a href="n_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar CIF</a></li>
	 <li><a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar CIF</a></li>
	 <li><a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar CIF</a></li>
	 <li><a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir fichas de calificación</a></li>
 </ul>
 </li>
 <li class="has-flyout"><a href="">Modulo Calificación</a>
 <ul class="flyout">
 	<li><a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=calif">Calificar</a></li>
 	<li><a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_calif">Imprimir calificación</li>
 </ul>
 </li>
</div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        <? include("../plugins/buscar/buscador.html");?>


<table class="responsive" id="lista">
<thead>
	<tr>
		<th><h5><small>Nº</small></h5></th>
		<th><h5><small>Nombre de la organizacion</small></h5></th>
		<th><h5><small>Nº de concurso</small></h5></th>
		<th><h5><small>Actividad 1</small></h5></th>
		<th><h5><small>Actividad 2</small></h5></th>
		<th><h5><small>Actividad 3</small></h5></th>
		<th><br/></th>
	</tr>
</thead>	
<tbody>
<?php
$num=0;
$sql="SELECT cif_bd_concurso.cod_concurso_cif, 
	org_ficha_organizacion.nombre, 
	cif_bd_concurso.n_concurso, 
	cif_bd_concurso.actividad_1 AS act1, 
	cif_bd_concurso.actividad_2 AS act2, 
	cif_bd_concurso.actividad_3 AS act3, 
	act1.descripcion AS actividad1, 
	act2.descripcion AS actividad2, 
	act3.descripcion AS actividad3
FROM pit_bd_ficha_mrn INNER JOIN cif_bd_concurso ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
	 LEFT OUTER JOIN sys_bd_actividad_mrn act1 ON act1.cod = cif_bd_concurso.actividad_1
	 LEFT OUTER JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
	 LEFT OUTER JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.nombre ASC, cif_bd_concurso.n_concurso ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$num++
?>
	<tr>
		<td><h6><small><? echo $num;?></small></h6></td>
		<td><h6><small><? echo $fila['nombre'];?></small></h6></td>
		<td><h6><small><? echo $fila['n_concurso'];?></small></h6></td>
		<td><h6><small><? echo $fila['actividad1'];?></small></h6></td>
		<td><h6><small><? echo $fila['actividad2'];?></small></h6></td>
		<td><h6><small><? echo $fila['actividad3'];?></small></h6></td>
		<td>
			<?php
			if($modo==edit)
			{
			?>
			<a href="m_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_concurso_cif'];?>" class="tiny button">Editar</a>
			<?php
			}
			elseif($modo==delete)
			{
			?>
			<a href="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_concurso_cif'];?>&action=DELETE" class="tiny alert button" onclick="return confirm('Va a eliminar permanentemente este registro y su contenido, desea proceder ?')">Eliminar</a>
			<?php	
			}
			elseif($modo==imprime)
			{
			?>
			<a href="../print/print_ficha_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;;?>&cod=<? echo $fila['cod_concurso_cif'];?>" class="tiny success button">Imprimir</a>
			<?php
			}
			elseif($modo==calif)
			{
			?>
			<a href="n_calif_cif_2.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_concurso_cif'];?>" class="tiny button">Calificar</a>
			<?php	
			}
			elseif($modo==imprime_calif)
			{
			?>
			<a href="../print/print_calif_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_concurso_cif'];?>" class="tiny success button">Imprimir</a>
			<?php	
			}
			?>
		</td>
	</tr>
<?
}
?>	
</tbody>
</table>	        
	        <!-- fin del contenido -->
        </div>
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
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>
</body>
</html>
