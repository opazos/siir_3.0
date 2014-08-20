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


    <div class="twelve columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	       <? include("../plugins/buscar/buscador.html");?>
	       <form name="form5" class="custom" method="post" action="gestor_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE_ESTADO">
	        <table class="responsive" id="lista">
		       <thead>
			        <tr>
				        <th>Nº</th>
				        <th>Nº documento</th>
				        <th>Nombre de la organizacion</th>
				        <th>Estado Situacional Actual</th>				        
				        <th>Estado Situacional Real</th>
			        </tr>
		       </thead> 
<?
$num=0;
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
	pit_bd_ficha_mrn.sector, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_mrn.mes, 
	pit_bd_ficha_mrn.cod_estado_iniciativa, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_mrn_sd.cod_ficha_sd
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 LEFT JOIN pit_bd_mrn_sd ON pit_bd_mrn_sd.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 
ORDER BY org_ficha_organizacion.nombre ASC";
}
else
{
$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
	pit_bd_ficha_mrn.sector, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_mrn.mes, 
	pit_bd_ficha_mrn.cod_estado_iniciativa, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_mrn_sd.cod_ficha_sd
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 LEFT JOIN pit_bd_mrn_sd ON pit_bd_mrn_sd.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_mrn.cod_estado_iniciativa
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.nombre ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$cad=$fila['cod_mrn'];
	$num++
?>			        
			        <tr>
				        <td><? echo $num;?></td>
				        <td><? echo $fila['n_documento'];?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td><? echo $fila['estado'];?></td>
				        <td>
				        <select name="estado[<? echo $cad;?>]">
					        <option value="" selected="selected">Seleccionar</option>
					      <?
					      $sql="SELECT sys_bd_estado_iniciativa.cod_estado_iniciativa, 
					      sys_bd_estado_iniciativa.descripcion
					      FROM sys_bd_estado_iniciativa
					      WHERE sys_bd_estado_iniciativa.cod_estado_iniciativa<>000 AND
					      sys_bd_estado_iniciativa.cod_estado_iniciativa<>001 AND
					      sys_bd_estado_iniciativa.cod_estado_iniciativa<>003";
					      $result1=mysql_query($sql) or die (mysql_error());
					      while($f1=mysql_fetch_array($result1))
					      {
					      ?>
					      <option value="<? echo $f1['cod_estado_iniciativa'];?>" <? if ($fila['cod_estado_iniciativa']==$f1['cod_estado_iniciativa']) echo "selected";?>><? echo $f1['descripcion'];?></option>
					      <?
					      }
					      ?>

				        </select>
				        </td>
			        </tr>
<?
}
?>			        
		    
	        </table> 
	        
	        	        <div class="twelve columns"><br/></div>
	        
	        <div class="twelve columns">
		        <button type="submit" class="primary button">Guardar cambios</button>
		        <a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
	        </div>
	       </form>
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
