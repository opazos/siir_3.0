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
<dd  class="active"><a href="">Liquidacion de Contratos PIT</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_liquida_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="row">
  <div class="twelve columns"><h6>I.- Datos de la iniciativa a liquidar</h6></div>
  <div class="two columns">Número de contrato</div>
  <div class="four columns">
    <select name="pit" class="medium">
      <option value="" selected="selected">Seleccionar</option>
    <?
    $sql="SELECT pit_bd_ficha_pit.cod_pit, 
    pit_bd_ficha_pit.n_contrato, 
    pit_bd_ficha_pit.f_contrato, 
    sys_bd_tipo_iniciativa.codigo_iniciativa, 
    sys_bd_dependencia.nombre AS oficina
    FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pit ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
    INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
    INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
    WHERE pit_bd_ficha_pit.n_contrato<>0 AND
    pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
    pit_bd_ficha_pit.cod_estado_iniciativa<>004 AND
    org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
    ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
    $result=mysql_query($sql) or die (mysql_error());
    while($r1=mysql_fetch_array($result))
    {
    ?>
    <option value="<? echo $r1['cod_pit'];?>"><? echo numeracion($r1['n_contrato'])."-".periodo($r1['f_contrato'])."-".$r1['codigo_iniciativa']."-".$r1['oficina'];?></option>
    <?
    }
    ?>

    </select>
  </div>
  <div class="two columns">Fecha de liquidación</div>
  <div class="four columns"><input type="date" name="f_liquidacion" class="seven date" placeholder="aaaa-mm-dd" maxlength="10"></div>
  <div class="two columns">Tipo de liquidación</div>
  <div class="ten columns">
    <select name="tipo_liquidacion" class="large">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1">Liquidación Total - Se liquida el monto total del PIT</option>
      <option value="2">Liquidación Parcial - Se liquida el PIT parcialmente debido a que no se a ejecutado el total del presupuesto</option>
    </select>
  </div>
  <div class="twelve columns"><h6>1.1 Si el PIT se esta liquidando de forma parcial, indicar los motivos</h6></div>
  <div class="twelve columns"><textarea id="elm1" name="comentario" rows="10" cols="80" style="width: 100%"></textarea></div>
  <div class="twelve columns"><h6>1.2 Si el PIT recibió un segundo desembolso:</h6></div>
  <div class="two columns">Fecha de desembolso del monto de animador territorial</div>
  <div class="four columns"><input type="date" name="f_desembolso" class="date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
  <div class="two columns">Nº de cheque con el que se desembolso</div>
  <div class="four columns"><input type="text" name="n_cheque" class="seven"></div> 
  <div class="twelve columns"><br/></div> 
  <div class="two columns">Hubo cambios en la junta directiva</div>
  <div class="four columns">
    <select name="hc_dir" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1">SI</option>
      <option value="0">NO</option>
    </select>
  </div>
  <div class="two columns">Si hubo cambios indicar los motivos</div>
  <div class="four columns"><textarea name="just_dir"></textarea></div>

  <div class="twelve columns"><h6>II.- Resultados obtenidos en el Territorio (Resumir resultados de los Animadores Territoriales)</h6></div>
  <div class="twelve columns"><textarea id="elm1" name="resultado" rows="10" cols="80" style="width: 100%"></textarea></div>

  <div class="twelve columns"><h6>III.- Sobre el mapa territorial</h6></div>
  <div class="two columns">Se aplicó y utilizó el mapa territorial en el territorio?</div>
  <div class="four columns">
    <select name="mapa" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1">SI</option>
      <option value="0">NO</option>
    </select>
  </div>
  <div class="two columns">Si la respuesta es afirmativa. Que uso se le dió al mapa?</div>
  <div class="four columns">
    <select name="uso_mapa" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1">Para presentar avances</option>
      <option value="2">Evaluar resultados</option>
      <option value="3">Presentaciones diversas</option>
      <option value="4">Otros usos</option>
    </select>
  </div>
  <div class="twelve columns"><br/></div>
  <div class="two columns">Ha ganado algún concurso de mapas territoriales?</div>
  <div class="ten columns">
    <select name="concurso" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1">SI</option>
      <option value="0">NO</option>
    </select>
  </div>
<div class="twelve columns"><h6>Si la respuesta es afirmativa...</h6></div>
<div class="two columns">Puesto ocupado</div>
<div class="four columns"><input type="text" name="puesto" class="seven digits"></div>
<div class="two columns">Monto del premio obtenido</div>
<div class="four columns"><input type="text" name="premio" class="seven number"></div>
</div>


<br/>

<div class="twelve columns">
<button type="submit" class="success button">Guardar cambios</button>
<a href="pit_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
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
