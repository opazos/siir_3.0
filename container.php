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

</head>
<body>
<div class="row">
  <div class="twelve columns fixed">
    <? include("menu.php");?>
  </div>
</div>

<!-- Iniciamos el contenido -->

<div class="row">
  <div class="twelve columns">
    <div class="panel">
      <div class="row">
        <!-- Menu Izquierda -->
          <div class="three columns">
          <?php include("consulta/menu_derecha_1.php");?>
          <hr>
          </div>
        <!-- Tablas de contenido -->
          <div class="nine columns">
          <?php include("plugins/buscar/buscador.html");?>
          <?php include("consulta/tabla_1.php");?>
          </div>
      </div>
      <? include("footer.php");?>
    </div>
  </div>
</div>
<!-- Footer -->


  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
  <script type="text/javascript" src="plugins/buscar/js/buscar-en-tabla.js"></script>
</body>
</html>
