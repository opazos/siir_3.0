<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM pit_bd_ficha_pit WHERE cod_pit='$id'";
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
<? echo $mensaje;?>

<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       



      <dl class="tabs">
        <dd class="active">
        <a href="#simple1">
        <?
        if ($modo==pit)
        {
	        echo "1.- Información del contrato";
        }
        elseif($modo==pdn)
        {
	        echo "2.- Planes de Negocio";
        }
        ?>
        </a>
        </dd>
      </dl>

      <ul class="tabs-content">
        <li class="active" id="simple1Tab">

  
        <form name="form5" class="custom" method="post" action="gestor_contrato_pit_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	        
	        <div class="two columns">Nº contrato</div>
	        <div class="ten columns">
	        <input type="text" name="n_contrato" class="required two" value="<? echo $row['n_contrato'];?>">
	        <input type="hidden" name="codigo" value="<? echo $row['cod_pit'];?>">	        
	        </div>
	        
	        <div class="twelve columns">Seleccionar PIT</div>
	        <div class="twelve columns">
		        <select name="pit" disabled="disabled">
			        <option value="" selected="selected">Seleccionar</option>
			        <?
			        if ($row1['cod_dependencia']==001)
			        {
			        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
			        org_ficha_taz.nombre
			        FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			        WHERE 
			        pit_bd_ficha_pit.mancomunidad=1
			        ORDER BY org_ficha_taz.nombre ASC";  
			        }
			        else
			        {
			        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
			        org_ficha_taz.nombre
			        FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			        WHERE 
			        pit_bd_ficha_pit.mancomunidad=1 AND
			        org_ficha_taz.cod_dependencia='".$row1['cod_dependencia']."'
			        ORDER BY org_ficha_taz.nombre ASC";
			        }
			        $result=mysql_query($sql) or die (mysql_error());
			        while($f1=mysql_fetch_array($result))
			        {
			        ?>
			        <option value="<? echo $f1['cod_pit'];?>" <? if ($f1['cod_pit']==$row['cod_pit']) echo "selected";?>><? echo $f1['nombre'];?></option>
			        <?
			        }
			        ?>
		        </select>
	        </div>
	        <div class="two columns">Fecha de contrato</div>
	        <div class="four columns"><input type="date" name="f_contrato" class="required date seven" maxlength="10" placeholder="aaaa-mm-dd" value="<? echo $row['f_contrato'];?>"></div>
	        <div class="two columns">Duración (en meses)</div>
	        <div class="four columns"><input type="text" name="duracion" class="required digits five" value="<? echo $row['mes'];?>"></div>
	        
	        <div class="two columns">Nº solicitud</div>
	        <div class="ten columns"><input type="text" name="n_solicitud" class="required digits two" value="<? echo $row['n_solicitud'];?>"></div>
	        

	        <div class="twelve columns"><h6>Fuente de financiamiento</h6></div>
	        <div class="two columns">FIDA %</div>
	        <div class="four columns"><input type="text" name="fte_fida" class="required number five" value="<? echo $row['fuente_fida'];?>"></div>
	        <div class="two columns">Recursos Ordinarios %</div>
	        <div class="four columns"><input type="text" name="fte_ro" class="required number five" value="<? echo $row['fuente_ro'];?>"></div>
	        
	        <div class="twelve columns">
	        <button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	        <a href="contrato_pit_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Cancelar</a>
	        </div>
        </form>
   
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
