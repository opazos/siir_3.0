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
  <li class="active"><a href="n_jurado_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar nuevo</a></li>
  <li><a href="jurado_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar informacion</a></li>
  <li><a href="jurado_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar</a></li>
</ul>
 
 
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        <? include("../plugins/buscar/buscador.html");?>
	        <table id="lista">
		        <thead>
			        <tr>
				        <th>Nº</th>
				        <th>DNI</th>
				        <th>Nombres y apellidos</th>
				        <th>Fecha de nacimiento</th>
				        <th>Oficina</th>
				        <th><br/></th>
			        </tr>
		        </thead>
		        
		        <tbody>
		        <?
		        $num=0;
		        if ($row['cod_dependencia']==001)
		        {
		        $sql="SELECT clar_bd_miembro.n_documento, 
		        clar_bd_miembro.nombre, 
		        clar_bd_miembro.paterno, 
		        clar_bd_miembro.materno, 
		        clar_bd_miembro.f_nacimiento, 
		        sys_bd_dependencia.nombre AS oficina
		        FROM sys_bd_dependencia INNER JOIN clar_bd_miembro ON sys_bd_dependencia.cod_dependencia = clar_bd_miembro.cod_dependencia
		        ORDER BY clar_bd_miembro.nombre ASC"; 
		        }
		        else
		        {
		        $sql="SELECT clar_bd_miembro.n_documento, 
		        clar_bd_miembro.nombre, 
		        clar_bd_miembro.paterno, 
		        clar_bd_miembro.materno, 
		        clar_bd_miembro.f_nacimiento, 
		        sys_bd_dependencia.nombre AS oficina
		        FROM sys_bd_dependencia INNER JOIN clar_bd_miembro ON sys_bd_dependencia.cod_dependencia = clar_bd_miembro.cod_dependencia
		        WHERE clar_bd_miembro.cod_dependencia='".$row['cod_dependencia']."'
		        ORDER BY clar_bd_miembro.nombre ASC";
		        }
		        $result=mysql_query($sql) or die (mysql_error());
		        while($fila=mysql_fetch_array($result))
		        {
			        $num++
		        
		        ?>
			        <tr>
				        <td><? echo $num;?></td>
				        <td><? echo $fila['n_documento'];?></td>
				        <td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
				        <td><? echo fecha_normal($fila['f_nacimiento']);?></td>
				        <td><? echo $fila['oficina'];?></td>
				        <td>
					        <?
					        if ($modo==edit)
					        {
						    ?>
						    <a href="m_jurado_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['n_documento'];?>" class="small primary button">Editar</a>
						    <?
					        }
					        elseif($modo==delete)
					        {
					        ?>
					        <a href="gestor_jurado_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['n_documento'];?>&action=DELETE" class="small alert button">Eliminar</a>
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
