<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT sys_bd_subactividad_poa.codigo, 
	sys_bd_subactividad_poa.nombre, 
	sys_bd_subactividad_poa.relacion_actividad
FROM sys_bd_subactividad_poa
WHERE sys_bd_subactividad_poa.cod='$cod'";
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
  <script src="rtables/javascripts/jquery.min.js"></script>
  <script src="rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->



</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
 <ul class="nav-bar vertical">
  <li class="active"><a href="n_detalle_poa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>">Añadir registro</a></li>
  <li><a href="detalle_poa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=edit">Modificar registros</a></li>
  <li><a href="detalle_poa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=delete">Eliminar registros</a></li>
  <li class="active"><a href="subactividad.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $r1['relacion_actividad'];?>&modo=relacion"><< Retornar</a></li>
</ul>
 
 
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns"><h6>DETALLE DE LA SUBACTIVIDAD: <? echo $r1['codigo']." - ".$r1['nombre'];?></h6></div>
        <? include("plugins/buscar/buscador.html");?>
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        <table class="responsive" id="lista">
		        <thead>
			        <tr>
				        <th>Nº</th>
				        <th>Año</th>
				        <th>Oficina</th>
				        <th>Meta Fisica</th>
				        <th>Unidad</th>
				        <th>Techo Presupuestal(S/.)</th>
				        <th><br/></th>
			        </tr>
		        </thead>
		        
		        <tbody>
		        <?
		        $num=0;
		        $sql="SELECT sys_bd_detalle_poa.anio, 
		        sys_bd_dependencia.nombre, 
		        sys_bd_detalle_poa.cod_dependencia, 
		        sys_bd_detalle_poa.meta_fisica, 
		        sys_bd_detalle_poa.monto_total, 
		        sys_bd_detalle_poa.cod, 
		        sys_bd_subactividad_poa.unidad
		        FROM sys_bd_dependencia INNER JOIN sys_bd_detalle_poa ON sys_bd_dependencia.cod_dependencia = sys_bd_detalle_poa.cod_dependencia
		        INNER JOIN sys_bd_subactividad_poa ON sys_bd_subactividad_poa.cod = sys_bd_detalle_poa.cod_subactividad
		        WHERE sys_bd_detalle_poa.cod_subactividad='$cod'
		        ORDER BY sys_bd_detalle_poa.cod_dependencia ASC, sys_bd_detalle_poa.anio ASC";
		        $result=mysql_query($sql) or die (mysql_error());
		        while($fila=mysql_fetch_array($result))
		        {
			        $num++
		        
		        ?>
			        <tr>
				        <td><? echo $num;?></td>
				        <td><? echo $fila['anio'];?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td><? echo number_format($fila['meta_fisica']);?></td>
				        <td><? echo $fila['unidad'];?></td>
				        <td><? echo number_format($fila['monto_total'],2);?></td>
				        <td>
				        <?
				        if ($modo==edit)
				        {
				        ?>
					     	<a href="m_detalle_poa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $fila['cod'];?>" class="small primary button">Editar</a>
					     <?
					     }
					     elseif($modo==delete)
					     {
					     ?>
					     	<a href="gestor_detalle_poa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $fila['cod'];?>&action=DELETE" class="small alert button">Eliminar</a>  
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
