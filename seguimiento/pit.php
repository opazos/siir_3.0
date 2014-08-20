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
 
<li class="has-flyout"><a href="">Editar Información</a>
 <ul class="flyout">
  <li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Primer desembolso</a></li>
  <li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=segundo_edit">Segundo desembolso</a></li> 
 </ul>
</li>
 
<li class="has-flyout"><a href="">Imprimir Carpeta</a>
 <ul class="flyout">
  <li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Primer desembolso</a></li>
  <li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_segundo">Segundo desembolso</a></li> 
 </ul>
</li>

<li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=directiva">Actualizar Directiva</a></li>
  
</ul>
 
 
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        <? include("../plugins/buscar/buscador.html");?>
	        <table class="responsive" id="lista">
		        <tbody>
			        <tr>
				        <th>Nº</th>
				        <th>Nº documento</th>
				        <th>Nombre de la organizacion</th>
				        <th>Duración</th>
				        <th>Estado situacional</th>				        
				        <th><br/></th>
			        </tr>
			        <?
			        $num=0;
			        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
	pit_bd_ficha_pit.n_documento_taz AS n_documento, 
	pit_bd_ficha_pit.mes, 
	org_ficha_taz.nombre, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	pit_bd_ficha_pit.cod_estado_iniciativa
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pit.cod_estado_iniciativa
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_taz.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$num++
			        ?>
			        <tr>
				        <td><? echo $num;?></td>
				        <td><? echo $fila['n_documento'];?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td><? echo $fila['mes'];?></td>
				        <td><? echo $fila['estado'];?></td>
				        <td>
				        <?
				        if ($modo==edit)
				        {
				        ?>
				        <a href="m_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_pit'];?>" class="small primary button">Editar</a>
				        <?
				        }
				        elseif($modo==segundo_edit and $fila['cod_estado_iniciativa']=='008')
				        {
				        ?>
				        <a href="m_pit_segundo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_pit'];?>" class="small primary button">Editar</a>
				        <?
				        }
				        elseif($modo==imprime)
				        {
				        ?>
				        <a href="../print/print_demanda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=1&cod=<? echo $fila['cod_pit'];?>" class="small success button">Imprimir</a>
				        <?
				        }
				        elseif($modo==imprime_segundo and $fila['cod_estado_iniciativa']=='008')
				        {
				        ?>
				        <a href="../print/print_demanda_pit_segundo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=1&cod=<? echo $fila['cod_pit'];?>" class="small success button">Imprimir</a>
				        <?
				        }
				        elseif($modo==directiva)
				        {
				        ?>
				        <a href="directiva_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_pit'];?>" class="small primary button">Actualizar Directiva</a>
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
