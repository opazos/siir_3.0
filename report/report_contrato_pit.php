<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
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
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
</head>
<body>

    <div class="twelve columns">
      <div>
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        <form name="form5" class="custom" method="post" action="">
	        <div class="twelve columns"><h5>Seleccione un Contrato PIT</h5></div>
	        
	        <div class="two columns">Nº de contrato</div>
	        <div class="ten columns">
		        <select name="n_contrato">
			        <option value="" selected="selected">Seleccionar</option>
			        <?
			        if ($row['cod_dependencia']==001)
			        {
			        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
			        pit_bd_ficha_pit.n_contrato, 
			        pit_bd_ficha_pit.f_contrato, 
			        org_ficha_taz.nombre, 
			        org_ficha_taz.cod_dependencia, 
			        sys_bd_dependencia.nombre AS oficina
			        FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			        INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
			        WHERE pit_bd_ficha_pit.n_contrato<>0 AND
			        pit_bd_ficha_pit.cod_estado_iniciativa<>000
			        ORDER BY pit_bd_ficha_pit.f_contrato ASC, org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";  
			        }
			        else
			        {
			        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
			        pit_bd_ficha_pit.n_contrato, 
			        pit_bd_ficha_pit.f_contrato, 
			        org_ficha_taz.nombre, 
			        org_ficha_taz.cod_dependencia, 
			        sys_bd_dependencia.nombre AS oficina
			        FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			        INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
			        WHERE pit_bd_ficha_pit.n_contrato<>0 AND
			        pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
			        org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
			        ORDER BY pit_bd_ficha_pit.f_contrato ASC, org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.n_contrato ASC";
			        }
			        $result=mysql_query($sql) or die (mysql_error());
			        while($fila=mysql_fetch_array($result))
			        {
			        ?>
			        <option value="<? echo $fila['cod_pit'];?>"><? echo numeracion($fila['n_contrato'])." - ".periodo($fila['f_contrato'])." - OL ".$fila['oficina'];?></option>
			        <?
			        }
			        ?>
			        
			        
		        </select>
	        </div>
	        <div class="twelve columns"><br/></div>
	        <div class="twelve columns">
		        <button type="submit" class="primary button">Generar reporte</button>
		        <button type="button" class="success button">Imprimir</button>
		        <a href="" class="secondary button">Finalizar</a>
	        </div>
	        <div class="twelve columns"><hr/></div>
	        </form>
	        <!-- fin del contenido -->
        </div>
        </div>
      </div>
    </div>

  </div>
  
  <div class="twelve columns">
	  <div>
        <div class="row">
        	<div class="twelve columns"><h6>Información del Contrato PIT</h6></div>
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
</body>
</html>
