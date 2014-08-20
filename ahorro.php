<?
require("funciones/sesion.php");
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



</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
 <ul class="nav-bar vertical">
   <li><a href="form_ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=SOL">Generar Solicitud</a></li>
   <li class="divider"></li>
   <li><a href="ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Solicitud</a></li>
   <li class="divider"></li>
   <li><a href="ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Solicitud</a></li>
   <li class="divider"></li>
   <li><a href="ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=anula">Anular Solicitud</a></li>
 </ul>
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	        <div class="twelve columns"><h6>SERVICIOS FINANCIEROS: MODULO DE AHORROS</h6></div>

          <table>
            <thead>
              <tr>
                <th>N.</th>
                <th>Nombre del Representante</th>
                <th>OLP</th>
                <th>N. Solicitud</th>
                <th>Fecha</th>
                <th><br/></th>
              </tr>
            </thead>

            <tbody>
            <?
            $num=0;
            $sql="SELECT mh_bd_desembolso.cod, 
              sys_bd_dependencia.nombre AS oficina, 
              mh_bd_desembolso.n_solicitud, 
              mh_bd_desembolso.f_solicitud, 
              sys_bd_personal.nombre, 
              sys_bd_personal.apellido
            FROM sys_bd_dependencia INNER JOIN mh_bd_desembolso ON sys_bd_dependencia.cod_dependencia = mh_bd_desembolso.cod_dependencia
               INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
            WHERE mh_bd_desembolso.cod_dependencia='".$row['cod_dependencia']."' AND
            mh_bd_desembolso.f_solicitud BETWEEN '$anio-01-01' AND '$anio-12-31'
            ORDER BY mh_bd_desembolso.f_solicitud ASC, mh_bd_desembolso.n_solicitud ASC";
            $result=mysql_query($sql) or die (mysql_error());
            while($fila=mysql_fetch_array($result))
            {
              $num++
            ?>
              <tr>
                <td><? echo $num;?></td>
                <td><? echo $fila['nombre']." ".$fila['apellido'];?></td>
                <td><? echo $fila['oficina'];?></td>
                <td><? echo numeracion($fila['n_solicitud']);?></td>
                <td><? echo fecha_normal($fila['f_solicitud']);?></td>
                <td>
                  <?
                  if ($modo==edit)
                  {
                    echo "<a href='form_ahorro.php?SES=$SES&anio=$anio&cod=".$fila['cod']."&tipo=UPDATE' class='small primary button'>Editar</a>";
                  }
                  elseif($modo==imprime)
                  {
                    echo "<a href='print/print_ahorro.php?SES=$SES&anio=$anio&cod=".$fila['cod']."' class='small success button'>Imprimir</a>";
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
