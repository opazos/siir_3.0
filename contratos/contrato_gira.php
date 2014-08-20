<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$anio_actual=date('Y');
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
<? echo $mensaje;?>

<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
 <ul class="nav-bar vertical">
  <li class="active"><a href="n_contrato_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Generar contrato</a></li>
  <li><a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir contrato</a></li>
  <li><a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar contrato</a></li>
  <li><a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=anula">Anular contrato</a></li>
  
  <li class="active"><a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=liquida">Generar informe</a></li>
  <li><a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_liquida">Imprimir informe</a></li>
  <li><a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_liquida">Modificar informe</a></li>  
  
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
		        <thead>
			        <tr>
				        <th>Nº contrato</th>
				        <th>Tema de la gira</th>
				        <th>Fecha de contrato</th>
				        <th>Oficina</th>
				        <th>Estado</th>
				        <th><br/></th>
			        </tr>
		        </thead>
		        <tbody>
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT gm_ficha_evento.cod_ficha_gm, 
	gm_ficha_evento.n_contrato, 
	gm_ficha_evento.f_propuesta, 
	gm_ficha_evento.tema, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	gm_ficha_cierre.cod_informe_gm, 
	gm_ficha_evento.cod_estado_iniciativa, 
	sys_bd_dependencia.nombre AS oficina, 
	gm_ficha_evento.f_presentacion
FROM sys_bd_estado_iniciativa INNER JOIN gm_ficha_evento ON sys_bd_estado_iniciativa.cod_estado_iniciativa = gm_ficha_evento.cod_estado_iniciativa
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gm_ficha_evento.cod_dependencia
	 LEFT OUTER JOIN gm_ficha_cierre ON gm_ficha_cierre.cod_ficha_gm = gm_ficha_evento.cod_ficha_gm
WHERE gm_ficha_evento.f_propuesta BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY gm_ficha_evento.n_contrato ASC, gm_ficha_evento.f_presentacion ASC";
}
else
{
$sql="SELECT gm_ficha_evento.cod_ficha_gm, 
	gm_ficha_evento.n_contrato, 
	gm_ficha_evento.f_propuesta, 
	gm_ficha_evento.tema, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	gm_ficha_cierre.cod_informe_gm, 
	gm_ficha_evento.cod_estado_iniciativa, 
	sys_bd_dependencia.nombre AS oficina, 
	gm_ficha_evento.f_presentacion
FROM sys_bd_estado_iniciativa INNER JOIN gm_ficha_evento ON sys_bd_estado_iniciativa.cod_estado_iniciativa = gm_ficha_evento.cod_estado_iniciativa
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gm_ficha_evento.cod_dependencia
	 LEFT OUTER JOIN gm_ficha_cierre ON gm_ficha_cierre.cod_ficha_gm = gm_ficha_evento.cod_ficha_gm
WHERE gm_ficha_evento.f_propuesta BETWEEN '$anio-01-01' AND '$anio-12-31' AND
gm_ficha_evento.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY gm_ficha_evento.n_contrato ASC, gm_ficha_evento.f_presentacion ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>			        
			        <tr>
				        <td><? echo numeracion($fila['n_contrato']);?></td>
				        <td><? echo $fila['tema'];?></td>
				        <td><? echo fecha_normal($fila['f_propuesta']);?></td>
				        <td><? echo $fila['oficina'];?></td>
				        <td><? echo $fila['estado'];?></td>
				        <td>
					        <?
					        if ($modo==imprime)
					        {
						    ?>
						   <a href="../print/print_contrato_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_ficha_gm'];?>" class="small success button">Imprimir</a>
						    <?
					        }
					        elseif($modo==edit)
					        {
					        ?>
					        <a href="m_contrato_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_gm'];?>" class="small primary button">Editar</a>
					        <?
					        }
					        elseif($modo==anula)
					        {
					        ?>
					        <a href="gestor_contrato_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_gm'];?>&action=ANULA" class="small alert button">Anular</a>
					        <?
					        }
					        elseif($fila['cod_estado_iniciativa']==005 and $modo==liquida)
					        {
					        ?>
					        <a href="n_liquida_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_gm'];?>" class="small primary button">Liquidar</a>
					        <?
					        }
					        elseif($fila['cod_estado_iniciativa']==004 and $modo==imprime_liquida)
					        {
					        ?>
					        <a href="../print/print_liquida_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_informe_gm'];?>" class="small success button">Imprimir</a>
					        <?
					        }
					        elseif($fila['cod_estado_iniciativa']==004 and $modo==edit_liquida)
					        {
					        ?>
					        <a href="m_liquida_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_informe_gm'];?>" class="small primary button">Editar</a>
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
