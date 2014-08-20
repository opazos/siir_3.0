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
  <script src="../javascripts/btn_envia.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
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
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Panel de Calificaciones</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&tipo=<? echo $tipo;?>&action=ADD_CAL_CAMPO" onsubmit="return checkSubmit();">

  <div class="row panel">
    <div class="twelve columns">Seleccionar Iniciativa</div>
    <div class="twelve columns">
      <select name="iniciativa" class="large">
        <option value="" selected="selected">Seleccionar</option>
        <?php
        if($tipo==1)
        {
          $sql="SELECT bd_ficha_cfinal.cod_participante, 
          org_ficha_taz.nombre, 
          pit_bd_ficha_pit.n_contrato, 
          pit_bd_ficha_pit.f_contrato
        FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
           INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
        WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
        bd_ficha_cfinal.cod_categoria=1
        ORDER BY pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC, org_ficha_taz.nombre ASC";
        }
        elseif($tipo==2)
        {
          $sql="SELECT bd_ficha_cfinal.cod_participante, 
          org_ficha_taz.nombre, 
          pit_bd_ficha_pit.n_contrato, 
          pit_bd_ficha_pit.f_contrato
        FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
           INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
        WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
        bd_ficha_cfinal.cod_categoria=2
        ORDER BY pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC, org_ficha_taz.nombre ASC";          
        }
        elseif($tipo==3)
        {
          $sql="SELECT org_ficha_organizacion.nombre, 
          pit_bd_ficha_pdn.n_contrato, 
          pit_bd_ficha_pdn.f_contrato, 
          bd_ficha_cfinal.cod_participante
        FROM pit_bd_ficha_pdn INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pdn.cod_pdn = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
           INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
        WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
        bd_ficha_cfinal.cod_categoria=3
        ORDER BY pit_bd_ficha_pdn.f_contrato ASC, pit_bd_ficha_pdn.n_contrato ASC, org_ficha_organizacion.nombre ASC";
        }
        $result=mysql_query($sql) or die (mysql_error());
        while($f1=mysql_fetch_array($result))
        {
          echo "<option value='".$f1['cod_participante']."'>".numeracion($f1['n_contrato'])."-".periodo($f1['f_contrato'])." - ".$f1['nombre']."</option>";
        }
        ?>
      </select>
    </div>
    <div class="twelve columns">Seleccionar Jurado</div>
    <div class="twelve columns">
      <select name="jurado" class="large">
        <option value="" selected="selected">Seleccionar</option>
        <?php
          $sql="SELECT bd_jurado_cfinal.cod_jurado, 
            clar_bd_miembro.nombre, 
            clar_bd_miembro.paterno, 
            clar_bd_miembro.materno, 
            sys_bd_cargo_cf.descripcion AS cargo
          FROM clar_bd_miembro INNER JOIN bd_jurado_cfinal ON clar_bd_miembro.cod_tipo_doc = bd_jurado_cfinal.cod_tipo_doc AND clar_bd_miembro.n_documento = bd_jurado_cfinal.n_documento
             INNER JOIN sys_bd_cargo_cf ON sys_bd_cargo_cf.cod_cargo = bd_jurado_cfinal.cod_cargo_cf
          WHERE bd_jurado_cfinal.cod_concurso='$cod'
          ORDER BY clar_bd_miembro.nombre ASC";
          $result=mysql_query($sql) or die (mysql_error());
          while($f2=mysql_fetch_array($result))
          {
            echo "<option value='".$f2['cod_jurado']."'>".$f2['nombre']." ".$f2['paterno']." ".$f2['materno']." - ".$f2['cargo']."</option>";
          }
        ?>
      </select>
    </div>
    <div class="twelve columns"><hr/></div>
<!-- Desde aqui se procede a cargar los cuadros segun sea el caso -->
<?php
if($tipo==1)
{
?>
<table>
  <thead>
    <tr>
      <th width="70%">Criterios</th>
      <th width="20%">Topes de puntaje</th>
      <th width="10%">Calificación</th>
    </tr>
  </thead>

  <tr>
    <td colspan="3">PLAN DE INVERSION TERRITORIAL</td>
  </tr>
  <tr>
    <td>Señales mostradas por las organizaciones conformantes del PIT sobre los niveles de coordinación implementadas entre ellas y gestiones desarrolladas en favor del PIT</td>
    <td class="centrado">01 a 04 puntos</td>
    <td><input type="text" name="p1" class="number"></td>
  </tr>
  <tr>
    <td>Valoración y uso de los Mapas Culturales para la gestión del territorio</td>
    <td class="centrado">01 a 04 puntos</td>
    <td><input type="text" name="p2" class="number"></td>
  </tr>
  <tr>
    <td>Conocimiento de la organización madre sobre la gestión de las iniciativas rurales conformantes del PIT</td>
    <td class="centrado">01 a 03 puntos</td>
    <td><input type="text" name="p3" class="number"></td>
  </tr>
  <tr>
    <td>Grado de conocimiento del Animador territorial sobre los avances, dificultades y alternativas de solución que se implementaron durante la ejecución del PIT</td>
    <td class="centrado">01 a 03 puntos</td>
    <td><input type="text" name="p4" class="number"></td>
  </tr>
  <tr>
    <td colspan="3">PLAN DE GESTION DE RECURSOS NATURALES</td>
  </tr>
  <tr>
    <td>Nivel de resultados obtenidos por el PGRN: organización e implementación de los CIF, % de participación de familias, pertinencia de los temas seleccionados en relación con el territorio</td>
    <td class="centrado">01 a 05 puntos</td>
    <td><input type="text" name="p5" class="number"></td>
  </tr>
  <tr>
    <td>Nivel de coordinación entre los diferentes actores relacionados con el PGRN: Junta Directiva, Animador Territorial, Gestor, familias participantes, otros</td>
    <td class="centrado">01 a 05 puntos</td>
    <td><input type="text" name="p6" class="number"></td>
  </tr>
  <tr>
    <td>Señales mostradas por la organización sobre gestiones desarrolladas que permitan la continuidad de sus iniciativas relacionadas con la gestión de RRNN (Coordinaciones, convenios, apalancamiento de recursos, otros )</td>
    <td class="centrado">01 a 03 puntos</td>
    <td><input type="text" name="p7" class="number"></td>
  </tr>
  <tr>
    <td>Evidencias sobre  innovaciones introducidas  en el PGRN </td>
    <td class="centrado">01 a 03 puntos</td>
    <td><input type="text" name="p8" class="number"></td>
  </tr>
  <tr>
    <td>Documentación pertinente, actualizada y ordenada  que sustenta la ejecución del PGRN</td>
    <td class="centrado">01 a 02 puntos</td>
    <td><input type="text" name="p9" class="number"></td>
  </tr>
  <tr>
    <td colspan="3">PLAN DE NEGOCIOS</td>
  </tr>
  <tr>
    <td>Nivel de resultados obtenidos por el PDN: calidad del producto o servicio, articulación a mercados (ventas), manejo de información (precios, costos, otros), % de participación de familias, otros</td>
    <td class="centrado">01 a 05 puntos</td>
    <td><input type="text" name="p10" class="number"></td>
  </tr>
  <tr>
    <td>Nivel de coordinación entre los diferentes actores relacionados con el PDN: Junta Directiva, Animador Territorial, Gestor, familias participantes, otros.</td>
    <td class="centrado">01 a 05 puntos</td>
    <td><input type="text" name="p11" class="number"></td>
  </tr>
  <tr>
    <td>Señales mostradas por la organización sobre gestiones desarrolladas que permitan la continuidad de PDN (Coordinaciones, convenios, apalancamiento de recursos, otros )</td>
    <td class="centrado">01 a 03 puntos</td>
    <td><input type="text" name="p12" class="number"></td>
  </tr>
  <tr>
    <td>Evidencias sobre  innovaciones introducidas  en el PDN</td>
    <td class="centrado">01 a 03 puntos</td>
    <td><input type="text" name="p13" class="number"></td>
  </tr>
  <tr>
    <td>Documentación pertinente, actualizada y ordenada  que sustenta la ejecución del PDN</td>
    <td class="centrado">01 a 02 puntos</td>
    <td><input type="text" name="p14" class="number"></td>
  </tr>
</table>
<?php
}
elseif($tipo==2)
{
?>
<table>
  <thead>
    <tr>
    <th width="70%">Criterios</th>
    <th width="20%">Topes de puntaje</th>
    <th width="10%">Calificación</th>
  </tr>    
  </thead>

  <tr>
    <td colspan="3">PLAN DE INVERSION TERRITORIAL</td>
  </tr>
  <tr>
    <td>Señales mostradas por las organizaciones conformantes del PIT  sobre niveles de coordinación implementadas entre ellas y gestiones desarrolladas en favor del PIT</td>
    <td class="centrado">01 a 07 puntos</td>
    <td><input type="text" name="p1" class="number"></td>
  </tr>
  <tr>
    <td>Valoración y uso de los Mapas Culturales para la gestión del territorio</td>
    <td class="centrado">01 a 07 puntos</td>
    <td><input type="text" name="p2" class="number"></td>
  </tr>
  <tr>
    <td>Conocimiento de la organización madre sobre la gestión de las iniciativas rurales conformantes del PIT</td>
    <td class="centrado">01 a 06 puntos</td>
    <td><input type="text" name="p3" class="number"></td>
  </tr> 

  <tr>
    <td colspan="3">PLAN DE NEGOCIOS</td>
  </tr>
  <tr>
    <td>Nivel de resultados obtenidos por el PDN: calidad del producto o servicio, articulación a mercados (ventas), manejo de información (precios, costos, otros), % de participación de familias, otros    </td>
    <td class="centrado">01 a 07 puntos</td>
    <td><input type="text" name="p4" class="number"></td>
  </tr>
  <tr>
    <td>Nivel de coordinación entre los diferentes actores relacionados con el PDN: Junta Directiva, Animador Territorial, Gestor, familias participantes, otros.</td>
    <td class="centrado">01 a 07 puntos</td>
    <td><input type="text" name="p5" class="number"></td>
  </tr>
  <tr>
    <td>Señales mostradas por la organización sobre gestiones desarrolladas que permitan la continuidad de PDN (Coordinaciones, convenios, apalancamiento de recursos, otros )</td>
    <td class="centrado">01 a 06 puntos</td>
    <td><input type="text" name="p6" class="number"></td>
  </tr>
  <tr>
    <td>Evidencias sobre  innovaciones introducidas  en el PDN</td>
    <td class="centrado">01 a 05 puntos</td>
    <td><input type="text" name="p7" class="number"></td>
  </tr>
  <tr>
    <td>Documentación pertinente, actualizada y ordenada  que sustenta la ejecución del PDN</td>
    <td class="centrado">01 a 05 puntos</td>
    <td><input type="text" name="p8" class="number"></td>
  </tr>       
</table>
<?php  
}
elseif($tipo==3)
{
?>
<table>
  <thead>
    <tr>
      <th width="70%">Criterios</th>
      <th width="20%">Topes de puntaje</th>
      <th width="10%">Calificación</th>
    </tr>    
  </thead>

  <tr>
    <td colspan="3">PLAN DE NEGOCIOS</td>
  </tr>
  <tr>
    <td>Nivel de resultados obtenidos por el PDN: calidad del producto o servicio, articulación a mercados (ventas), manejo de información (precios, costos, otros), % de participación de familias, otros    </td>
    <td class="centrado">01 a 15 puntos</td>
    <td><input type="text" name="p1" class="number"></td>
  </tr>
  <tr>
    <td>Nivel de coordinación entre los diferentes actores relacionados con el PDN: Junta Directiva,  Gestor, familias participantes, otros.</td>
    <td class="centrado">01 a 10 puntos</td>
    <td><input type="text" name="p2" class="number"></td>
  </tr>
  <tr>
    <td>Señales mostradas por la organización sobre gestiones desarrolladas que permitan la continuidad de PDN (Coordinaciones, convenios, apalancamiento de recursos, otros )</td>
    <td class="centrado">01 a 10 puntos</td>
    <td><input type="text" name="p3" class="number"></td>
  </tr>
  <tr>
    <td>Evidencias sobre  innovaciones introducidas  en el PDN</td>
    <td class="centrado">01 a 08 puntos</td>
    <td><input type="text" name="p4" class="number"></td>
  </tr>
  <tr>
    <td>Documentación pertinente, actualizada y ordenada  que sustenta la ejecución del PDN</td>
    <td class="centrado">01 a 07 puntos</td>
    <td><input type="text" name="p5" class="number"></td>
  </tr>       

</table>
<?  
}
?>
    <div class="twelve columns">
      <button type="submit" class="button" id="btn_envia">Guardar cambios</button>
      <a href="cal_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
    </div>
</div>
<hr/>	
</form>

<div class="row">
<table>
  <thead>
    <tr>
      <th>N.</th>
      <th class="five">Nombre de la organización</th>
      <th class="two">Contrato</th>
      <th class="four">Jurado</th>
      <th class="two">Puntaje</th>
      <th><br/></th>
    </tr>
  </thead>
  <tbody>
<?php
  $num=0;
  if ($tipo==1)
  {
    $sql="SELECT bd_ficha_campo_cf.cod_ficha, 
    bd_ficha_campo_cf.total_calif, 
    clar_bd_miembro.nombre, 
    clar_bd_miembro.paterno, 
    clar_bd_miembro.materno, 
    pit_bd_ficha_pit.n_contrato, 
    pit_bd_ficha_pit.f_contrato, 
    org_ficha_taz.nombre AS org
  FROM bd_ficha_cfinal INNER JOIN bd_ficha_campo_cf ON bd_ficha_cfinal.cod_participante = bd_ficha_campo_cf.cod_participante
     INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
     INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
     INNER JOIN bd_jurado_cfinal ON bd_jurado_cfinal.cod_jurado = bd_ficha_campo_cf.cod_jurado
     INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = bd_jurado_cfinal.cod_tipo_doc AND clar_bd_miembro.n_documento = bd_jurado_cfinal.n_documento
  WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
  bd_ficha_cfinal.cod_categoria=1
  ORDER BY org ASC, clar_bd_miembro.nombre ASC";
  }
  elseif($tipo==2)
  {
    $sql="SELECT bd_ficha_campo_cf.cod_ficha, 
    bd_ficha_campo_cf.total_calif, 
    clar_bd_miembro.nombre, 
    clar_bd_miembro.paterno, 
    clar_bd_miembro.materno, 
    pit_bd_ficha_pit.n_contrato, 
    pit_bd_ficha_pit.f_contrato, 
    org_ficha_taz.nombre AS org
  FROM bd_ficha_cfinal INNER JOIN bd_ficha_campo_cf ON bd_ficha_cfinal.cod_participante = bd_ficha_campo_cf.cod_participante
     INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
     INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
     INNER JOIN bd_jurado_cfinal ON bd_jurado_cfinal.cod_jurado = bd_ficha_campo_cf.cod_jurado
     INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = bd_jurado_cfinal.cod_tipo_doc AND clar_bd_miembro.n_documento = bd_jurado_cfinal.n_documento
  WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
  bd_ficha_cfinal.cod_categoria=2
  ORDER BY org ASC, clar_bd_miembro.nombre ASC";
  }
  elseif($tipo==3)
  {
    $sql="SELECT bd_ficha_campo_cf.cod_ficha, 
  bd_ficha_campo_cf.total_calif, 
  clar_bd_miembro.nombre, 
  clar_bd_miembro.paterno, 
  clar_bd_miembro.materno, 
  pit_bd_ficha_pdn.n_contrato, 
  pit_bd_ficha_pdn.f_contrato, 
  org_ficha_organizacion.nombre AS org
FROM bd_ficha_cfinal INNER JOIN bd_ficha_campo_cf ON bd_ficha_cfinal.cod_participante = bd_ficha_campo_cf.cod_participante
   INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN bd_jurado_cfinal ON bd_jurado_cfinal.cod_jurado = bd_ficha_campo_cf.cod_jurado
   INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = bd_jurado_cfinal.cod_tipo_doc AND clar_bd_miembro.n_documento = bd_jurado_cfinal.n_documento
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=3
ORDER BY org ASC, clar_bd_miembro.nombre ASC";
  }
  $result=mysql_query($sql) or die (mysql_error());
  while($fila=mysql_fetch_array($result))
  {
    $num++
?>  
    <tr>
      <td><? echo $num;?></td>
      <td><? echo $fila['org'];?></td>
      <td><? echo numeracion($fila['n_contrato'])."-".periodo($fila['f_contrato']);?></td>
      <td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
      <td><? echo number_format($fila['total_calif'],2);?></td>
      <td><a href="gestor_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&tipo=<? echo $tipo;?>&id=<? echo $fila['cod_ficha'];?>&action=DELETE_CAL_CAMPO" class="tiny alert button">Eliminar</a></td>
    </tr>
<?php
  }
?>    
  </tbody>
</table>
</div>



</div>
</li>
</ul>
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
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
