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
       <li class="active"><a href="n_adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar Nueva</a></li>
       <li><a href="adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir</a></li>
       <li><a href="adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Editar contenido</a></li>
       <li><a href="adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=reset">Resetear Addenda</a></li>
       <li><a href="adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar Addenda</a></li>
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
                  <th>N.</th>
                  <th>Plan de negocio</th>
                  <th class="two">N. Contrato</th>
                  <th>N. Addenda</th>
                  <th>Fecha</th>
                  <th><br/></th>
                </tr>
              </thead>

              <tbody>
              <?
              $num=0;
              $sql="SELECT pit_bd_ficha_adenda_pdn.cod, 
              pit_bd_ficha_adenda_pdn.n_adenda, 
              pit_bd_ficha_adenda_pdn.f_adenda, 
              pit_bd_ficha_pdn.n_contrato, 
              pit_bd_ficha_pdn.f_contrato, 
              pit_bd_ficha_pdn.denominacion, 
              org_ficha_organizacion.nombre
              FROM pit_bd_ficha_pdn INNER JOIN pit_bd_ficha_adenda_pdn ON pit_bd_ficha_pdn.cod_pdn = pit_bd_ficha_adenda_pdn.cod_pdn
              INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
              WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
              ORDER BY pit_bd_ficha_adenda_pdn.f_adenda ASC, pit_bd_ficha_adenda_pdn.n_adenda ASC, pit_bd_ficha_pdn.n_contrato ASC";
              $result=mysql_query($sql) or die (mysql_error());
              while($fila=mysql_fetch_array($result))
              {
                $num++
              ?>
                <tr>
                  <td><? echo $num;?></td>
                  <td><? echo $fila['nombre']." / ".$fila['denominacion'];?></td>
                  <td><? echo numeracion($fila['n_contrato'])."-".periodo($fila['f_contrato']);?></td>
                  <td><? echo numeracion($fila['n_adenda']);?></td>
                  <td><? echo fecha_normal($fila['f_adenda']);?></td>
                  <td>
                    <?
                    if ($modo==edit)
                    {
                    ?>
                    <a href="edit_adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod'];?>&modo=edit" class="small primary button">Editar</a>
                    <?
                    }
                    elseif($modo==reset)
                    {
                    ?>
                    <a href="m_adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod'];?>" class="small alert button">Resetear</a>
                    <?
                    }
                    elseif($modo==imprime)
                    {
                    ?>
                    <a href="../print/print_adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod'];?>" class="small success button">Imprimir</a>
                    <?
                    }
                    elseif($modo==delete)
                    {
                    ?>
                    <a href="gestor_adenda_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod'];?>&action=DELETE" class="small alert button">Eliminar</a>
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
