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
 <li class="has-flyout">
   <a href="">Calificación - Campo</a>
   <ul class="flyout">
     <li><a href="cal_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=campo_a">Calificar : Categoria A</a></li>
     <li><a href="cal_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=campo_b">Calificar : Categoria B</a></li>
     <li><a href="cal_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=campo_c">Calificar : Categoria C</a></li>
   </ul>
 </li>
 <li class="has-flyout">
   <a href="">Calificación - Pública</a>
   <ul class="flyout">
     <li><a href="cal_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=public_a">Calificar : Categoria A</a></li>
     <li><a href="cal_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=public_b">Calificar : Categoria B</a></li>
     <li><a href="cal_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=public_c">Calificar : Categoria C</a></li>
   </ul>   
 </li>
</ul>
 
 
 <hr>


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
                  <th>N.</th>
                  <th>Nombre del concurso</th>
                  <th>Fecha</th>
                  <th>Nivel</th>
                  <th>Oficina</th>
                  <th><br/></th>
                </tr>
              </thead>
              <tbody>
              <?php
              $num=0;
              $sql="SELECT bd_cfinal.cod_concurso, 
              bd_cfinal.nombre, 
              bd_cfinal.f_concurso, 
              sys_bd_dependencia.nombre AS oficina, 
              sys_bd_nivel_cf.descripcion AS nivel
              FROM sys_bd_dependencia INNER JOIN bd_cfinal ON sys_bd_dependencia.cod_dependencia = bd_cfinal.cod_dependencia
              INNER JOIN sys_bd_nivel_cf ON sys_bd_nivel_cf.cod_nivel = bd_cfinal.cod_nivel
              WHERE bd_cfinal.cod_dependencia='".$row['cod_dependencia']."'
              ORDER BY bd_cfinal.f_concurso ASC";
              $result=mysql_query($sql) or die (mysql_error());
              while($fila=mysql_fetch_array($result))
              {
                $num++
              ?>
                <tr>
                  <td><? echo $num;?></td>
                  <td><? echo $fila['nombre'];?></td>
                  <td><? echo fecha_normal($fila['f_concurso']);?></td>
                  <td><? echo $fila['nivel'];?></td>
                  <td><? echo $fila['oficina'];?></td>
                  <td>
                    <?php
                       if($modo==campo_a)
                       {
                        echo "<a href='modulo_cal_campo.php?SES=".$SES."&anio=".$anio."&cod=".$fila['cod_concurso']."&tipo=1' class='tiny button'>Calificar</a>";
                       }         
                       elseif($modo==campo_b)
                       {
                        echo "<a href='modulo_cal_campo.php?SES=".$SES."&anio=".$anio."&cod=".$fila['cod_concurso']."&tipo=2' class='tiny button'>Calificar</a>";
                       }    
                       elseif($modo==campo_c)
                       {
                        echo "<a href='modulo_cal_campo.php?SES=".$SES."&anio=".$anio."&cod=".$fila['cod_concurso']."&tipo=3' class='tiny button'>Calificar</a>";
                       }
                       elseif($modo==public_a)
                       {
                        echo "<a href='modulo_cal_public.php?SES=".$SES."&anio=".$anio."&cod=".$fila['cod_concurso']."&tipo=1' class='tiny button'>Calificar</a>";
                       }
                       elseif($modo==public_b)
                       {
                        echo "<a href='modulo_cal_public.php?SES=".$SES."&anio=".$anio."&cod=".$fila['cod_concurso']."&tipo=2' class='tiny button'>Calificar</a>";
                       }
                       elseif($modo==public_c)
                       {
                        echo "<a href='modulo_cal_public.php?SES=".$SES."&anio=".$anio."&cod=".$fila['cod_concurso']."&tipo=3' class='tiny button'>Calificar</a>";
                       }
                       elseif($modo==imprime)
                       {
                        echo "<a href='' class='tiny success button'>Imprimir</a>";
                       }
                    ?>
                  </td>
                </tr>
              <?php
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
