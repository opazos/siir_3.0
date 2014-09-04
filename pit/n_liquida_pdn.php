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
<dd  class="active"><a href="">Liquidacion de Planes de Negocio</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_liquida_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="row">
  <div class="twelve columns"><h6>I.- Datos de la iniciativa a liquidar</h6></div>
  <div class="two columns">Iniciativa a liquidar</div>
  <div class="ten columns">
  <select name="iniciativa" class="hyjack">
    <option value="" selected="selected">Seleccionar</option>
    <?
    $sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
    pit_bd_ficha_pdn.denominacion, 
    org_ficha_organizacion.nombre
    FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
    WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
    pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
    pit_bd_ficha_pdn.cod_estado_iniciativa<>002 AND
    pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
    pit_bd_ficha_pdn.cod_estado_iniciativa<>004 AND
    pit_bd_ficha_pdn.cod_estado_iniciativa<>006 AND
    pit_bd_ficha_pdn.cod_estado_iniciativa<>007 AND
    org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
    ORDER BY org_ficha_organizacion.nombre ASC";
    $result=mysql_query($sql) or die (mysql_error());
    while($f1=mysql_fetch_array($result))
    {
    ?>
    <option value="<? echo $f1['cod_pdn'];?>"><? echo $f1['nombre']." - ".$f1['denominacion'];?></option>
    <?
    }
    ?>
  </select>    
  </div>
  <div class="twelve columns"><hr/></div>
  <div class="two columns">Fecha de liquidación</div>
  <div class="four columns"><input type="date" name="f_liquidacion" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>
  <div class="two columns">Calificación de la iniciativa</div>
  <div class="four columns">
    <select name="calificacion" class="large">
    <option value="" selected="selected">Seleccionar</option>
    <option value="1">MALA</option>
    <option value="2">REGULAR</option>
    <option value="3">BUENA</option>
    <option value="4">MUY BUENA</option>
  </select>
  </div>
 <div class="twelve columns"><br/></div> 
  <div class="two columns">Hubo cambios en la lista de participantes?</div>
  <div class="four columns">
    <select name="hc_soc" class="seven">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1">Si</option>
      <option value="0">No</option>
    </select>
  </div>

  <div class="two columns">Si hubo cambios indicar el motivo</div>
  <div class="four columns">
    <textarea name="just_soc"></textarea>
  </div>

  <div class="two columns">Hubo cambios en la junta directiva?</div>
  <div class="four columns">
    <select name="hc_dir" class="seven">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1">Si</option>
      <option value="0">No</option>
    </select>
  </div>

  <div class="two columns">Si hubo cambios indicar el motivo</div>
  <div class="four columns">
    <textarea name="just_dir"></textarea>
  </div>  
</div>
<div class="row">
  <div class="twelve columns"><h6>II.- Información sobre desembolsos</h6></div>
  <div class="two columns">Fecha de Desembolso</div>
  <div class="four columns"><input type="date" name="f_desembolso" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
  <div class="two columns">Nº Cheque y/o C.O</div>
  <div class="four columns"><input type="text" name="n_cheque" class="required five"></div>
</div> 
<div class="row">
  <div class="twelve columns"><h6>III.- Comentarios u observaciones</h6></div>
  <div class="twelve columns"><textarea name="comentario" rows="5"></textarea></div>
</div>

<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="pdn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
  <script type="text/javascript" src="../plugins/jquery.js"></script>  
  
  
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    

<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>  

</body>
</html>
