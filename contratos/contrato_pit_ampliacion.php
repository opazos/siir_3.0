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


<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
 <ul class="nav-bar vertical">
  <li class="active"><a href="n_contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit">Registrar nuevo contrato</a></li>
  <li><a href="contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir contrato</a></li>
  <li><a href="contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar contrato</a></li>
<?
if ($row['cod_tipo_usuario']<>'U')
{
?>   
  <li><a href="contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=anula">Anular contrato</a></li>
<?
}
?>  
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
				        <th class="two">Nº contrato</th>
				        <th>Nº ampliacion</th>
				        <th>Organizacion con la que se firmo contrato</th>
				        <th>Fecha de ampliacion</th>
				        <th><br/></th>
			        </tr>
		       </thead> 
 <tbody>
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT clar_ampliacion_pit.cod_ampliacion, 
	clar_ampliacion_pit.n_ampliacion, 
	clar_ampliacion_pit.f_ampliacion, 
	pit_bd_ficha_pit.n_contrato, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.f_contrato, 
	sys_bd_dependencia.nombre AS oficina
FROM clar_ampliacion_pit INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE
clar_ampliacion_pit.f_ampliacion BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY pit_bd_ficha_pit.n_contrato ASC, clar_ampliacion_pit.f_ampliacion ASC";
}
else
{
$sql="SELECT clar_ampliacion_pit.cod_ampliacion, 
	clar_ampliacion_pit.n_ampliacion, 
	clar_ampliacion_pit.f_ampliacion, 
	pit_bd_ficha_pit.n_contrato, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.f_contrato, 
	sys_bd_dependencia.nombre AS oficina
FROM clar_ampliacion_pit INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_ampliacion_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."' AND
clar_ampliacion_pit.f_ampliacion BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY pit_bd_ficha_pit.n_contrato ASC, clar_ampliacion_pit.f_ampliacion ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>			        
			        <tr>
				        <td><? echo numeracion($fila['n_contrato'])."-".periodo($fila['f_contrato']);?></td>
				        <td><? echo numeracion($fila['n_ampliacion']);?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td><? echo fecha_normal($fila['f_ampliacion']);?></td>
				        <td>
				        <?
				        if ($modo==imprime)
				        {
				        ?>
				        <a href="../print/print_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_ampliacion'];?>" class="small success button">Imprimir</a>
				        <?
				        }
				        elseif($modo==edit and $anio==$anio_actual)
				        {
					        ?>
					      <a href="m_contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ampliacion'];?>&modo=pit" class="small primary button">Editar</a>  
					        <?
				        }
				        elseif($modo==anula)
				        {
				        ?>
				        <a href="gestor_amplia_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ampliacion'];?>" class="small alert button">Anular</a>
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
