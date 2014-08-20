<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT sys_bd_subcomponente_poa.cod, 
	sys_bd_subcomponente_poa.codigo, 
	sys_bd_subcomponente_poa.nombre, 
	sys_bd_subcomponente_poa.relacion
FROM sys_bd_subcomponente_poa
WHERE sys_bd_subcomponente_poa.cod='$cod'";
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
  <li class="active"><a href="n_actividad.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>">Nueva Actividad</a></li>
  <li><a href="actividad.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=edit">Modificar Actividad</a></li>
  <li><a href="actividad.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=delete">Eliminar Actividad</a></li>
  <li><a href="actividad.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=relacion">Subactividades</a></li>
  <li class="active"><a href="subcomponente.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $r1['relacion'];?>&modo=relacion"><< Regresar</a></li>
</ul>
 
 
 <hr>
    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns"><h6>ACTIVIDADES DEL SUBCOMPONENTE: <? echo $r1['codigo']." - ".$r1['nombre'];?></h6></div>
        <? include("plugins/buscar/buscador.html");?>
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	        <table class="responsive" id="lista">
		        
		        <thead>
			        <tr>
				        <th>Nº</th>
				        <th>Codigo</th>
				        <th class="nine">Nombre de la Actividad</th>
				        <th><br/></th>
			        </tr>
		        </thead>
		        
		        <tbody>
		        <?
		        $num=0;
		        $sql="SELECT sys_bd_actividad_poa.cod, 
		        sys_bd_actividad_poa.codigo, 
		        sys_bd_actividad_poa.nombre
		        FROM sys_bd_actividad_poa
		        WHERE sys_bd_actividad_poa.relacion='$cod'
		        ORDER BY sys_bd_actividad_poa.codigo ASC";
		        $result=mysql_query($sql) or die (mysql_error());
		        while($fila=mysql_fetch_array($result))
		        {
			        $num++
		        
		        ?>
			        <tr>
				        <td><? echo $num;?></td>
				        <td><? echo $fila['codigo'];?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td>
				        <?
				        if ($modo==edit)
				        {
				        ?>
					        <a href="" class="small primary button">Editar</a>
					    <?
					    }
					    elseif($modo==delete)
					    {
					    ?>
					    	<a href="" class="small alert button">Eliminar</a>
					    <?
					    }
					    elseif($modo==relacion)
					    {
					    ?>
					    	<a href="subactividad.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod'];?>&modo=edit" class="small success button">Subactividades</a>
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
