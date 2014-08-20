<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM bd_cfinal WHERE cod_concurso='$cod'";
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
  <!-- Con esto no podran dar click atras -->  
  <meta http-equiv="Expires" content="0" />
  <meta http-equiv="Pragma" content="no-cache" />

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
  
  <!-- Con este código deshabilito el click atras -->
  <script type="text/javascript">
  {
  if(history.forward(1))
  location.replace(history.forward(1))
  }
  </script> 
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Concursos finales - Iniciativas participantes - Categoria B</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_CAT_B" onsubmit="return checkSubmit();">

  <div class="row">
    <div class="twelve columns"><h6>I.- Datos del concurso</h6></div>
    <div class="two columns">Nombre del evento</div>
    <div class="ten columns"><input type="text" name="nombre" class="required ten" value="<? echo $r1['nombre'];?>" disabled="disabled"> <input type="hidden" name="codigo" value="<? echo $r1['cod_concurso'];?>"></div>

	<div class="two columns">Nivel del concurso</div>
  <div class="four columns">
    <select name="nivel" class="medium" disabled="disabled">
      <option value="" selected="selected">Seleccionar</option>
      <?php
        $sql="SELECT * FROM sys_bd_nivel_cf";
        $result=mysql_query($sql) or die (mysql_error());
        while($f1=mysql_fetch_array($result))
        {
        ?>
        <option value="<? echo $f1['cod_nivel'];?>" <? if ($f1['cod_nivel']==$r1['cod_nivel']) echo "selected";?>><? echo $f1['descripcion'];?></option>
        <?
        }
        ?>
    </select>
  </div>
  <div class="two columns">Fecha del concurso</div>
  <div class="four columns"><input type="date" name="f_concurso" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r1['f_concurso'];?>" disabled="disabled"></div>
  <hr/>
</div>

<div class="row">
  <div class="twelve columns"><h6>II.- Participantes - Categoria B</h6></div>

  <div class="twelve columns">Seleccionar Plan de Inversión Territorial</div> 
  <div class="twelve columns">
    <select name="pit" class="large">
      <option value="" selected="selected">Seleccionar</option>
      <?php
      if($r1['cod_nivel']==1)
      {      
        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
        pit_bd_ficha_pit.n_contrato, 
        pit_bd_ficha_pit.f_contrato, 
        org_ficha_taz.nombre, 
        sys_bd_dependencia.nombre AS oficina, 
        pit_bd_ficha_pit.cod_tipo_iniciativa
        FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
        INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
        WHERE org_ficha_taz.cod_dependencia='".$r1['cod_dependencia']."' AND
        pit_bd_ficha_pit.n_contrato<>0 AND
        pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
        pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
        pit_bd_ficha_pit.mancomunidad=1
        ORDER BY sys_bd_dependencia.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC, org_ficha_taz.nombre ASC";
      }
      else
      { 
        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
        pit_bd_ficha_pit.n_contrato, 
        pit_bd_ficha_pit.f_contrato, 
        org_ficha_taz.nombre, 
        sys_bd_dependencia.nombre AS oficina, 
        pit_bd_ficha_pit.cod_tipo_iniciativa
        FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
        INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
        WHERE pit_bd_ficha_pit.n_contrato<>0 AND
        pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
        pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
        pit_bd_ficha_pit.mancomunidad=1
        ORDER BY sys_bd_dependencia.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC, org_ficha_taz.nombre ASC";       
      }      
        $result=mysql_query($sql) or die (mysql_error());
        while($f1=mysql_fetch_array($result))
        {
       ?>
        <option value="<?php echo $f1['cod_pit'];?>"><? echo numeracion($f1['n_contrato'])."-".periodo($f1['f_contrato'])."-".$f1['oficina']." / ".$f1['nombre'];?></option>
       <?   
        }
      ?>
    </select>
  </div>
  <div class="two columns">Nombre de la danza que presentará</div>
  <div class="four columns"><input type="text" name="danza" class="required ten"></div>
  <div class="two columns">Nombre del plato típico que presentará</div>
  <div class="four columns"><input type="text" name="plato" class="required ten"></div>
  <div class="twelve columns">
    <button type="submit" class="button" id="btn_envia">Añadir participante</button>
  </div>
  <div class="twelve columns"><hr/></div>
</div>
<div class="row">
  <div class="twelve columns"><h6>III.- Participantes registrados - Categoria B</h6></div>
  <table>
    <thead>
      <tr>
        <th>N.</th>
        <th>Nombre de la Organización</th>
        <th class="one">Contrato</th>
        <th class="two">Nombre de la danza</th>
        <th class="two">Nombre del plato típico</th>
        <th>Oficina</th>
        <th>-</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $num=0;
    $sql="SELECT bd_ficha_cfinal.cod_participante, 
    pit_bd_ficha_pit.n_contrato, 
    pit_bd_ficha_pit.f_contrato, 
    org_ficha_taz.nombre, 
    bd_ficha_cfinal.name_danza, 
    bd_ficha_cfinal.name_plato, 
    sys_bd_dependencia.nombre AS oficina
    FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
    INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
    INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
    WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
    bd_ficha_cfinal.cod_categoria=2
    ORDER BY pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC, org_ficha_taz.nombre ASC";
    $result=mysql_query($sql) or die (mysql_error());
    while($f2=mysql_fetch_array($result))
    {
      $num++
    ?>
      <tr>
        <td><? echo $num;?></td>
        <td><? echo $f2['nombre'];?></td>
        <td><? echo numeracion($f2['n_contrato'])."-".periodo($f2['f_contrato']);?></td>
        <td><? echo $f2['name_danza'];?></td>
        <td><? echo $f2['name_plato'];?></td>
        <td><? echo $f2['oficina'];?></td>
        <td><a href="gestor_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $f2['cod_participante'];?>&action=DELETE_CAT_B" class="tiny alert button">Desvincular</a></td>
      </tr>
    <?
    }
    ?>  
    </tbody>
  </table>
</div>




  <div class="twelve columns">
    <a href="participante_cf_c.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>" class="button success">Siguiente >></a>
    <a href="cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
  </div>

</form>
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
