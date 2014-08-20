<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
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
<li class="has-flyout"><a href="">Demanda de evento</a>
<ul class="flyout">
<li><a href="n_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Nueva demanda de evento</a></li>
<li><a href="n_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=donacion">Nueva demanda de evento con fondos de Donación</a></li>
</ul>
</li>
<li><a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir demanda de evento</a></li>
<li><a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar evento</a></li>
<li><a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=anula">Anular evento</a></li>
<li class="has-flyout"><a href="">Rendicion de Fondos</a>
<ul class="flyout">
<li><a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=rinde">Rendir evento</a></li>
<li><a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_rendicion">Imprimir rendicion</a></li>	
<li><a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_rendicion">Modificar rendicion</a></li>
</ul>
</li>
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
				        <th>Nº</th>
				        <th>Nombre del evento</th>
				        <th>Fecha de presentacion de la demanda</th>
				        <th>Estado</th>
				        <th></th>			        
			        </tr>
		        </thead>
		        <tbody>
	<?
	if ($row['cod_dependencia']==001 and $row['cod_tipo_usuario']==S)
	{
	$sql="SELECT epd_bd_demanda.cod_evento, 
	epd_bd_demanda.n_evento, 
	epd_bd_demanda.nombre, 
	epd_bd_demanda.f_presentacion, 
	epd_bd_demanda.estado, 
	sys_bd_dependencia.nombre AS oficina, 
	epd_informe_evento.cod_rendicion
FROM sys_bd_dependencia INNER JOIN epd_bd_demanda ON sys_bd_dependencia.cod_dependencia = epd_bd_demanda.cod_dependencia
	 LEFT JOIN epd_informe_evento ON epd_informe_evento.cod_evento = epd_bd_demanda.cod_evento
	 WHERE
	epd_bd_demanda.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY epd_bd_demanda.f_presentacion ASC";
	}
	else
	{
	$sql="SELECT epd_bd_demanda.cod_evento, 
	epd_bd_demanda.n_evento, 
	epd_bd_demanda.nombre, 
	epd_bd_demanda.f_presentacion, 
	epd_bd_demanda.estado, 
	sys_bd_dependencia.nombre AS oficina, 
	epd_informe_evento.cod_rendicion
FROM sys_bd_dependencia INNER JOIN epd_bd_demanda ON sys_bd_dependencia.cod_dependencia = epd_bd_demanda.cod_dependencia
	 LEFT JOIN epd_informe_evento ON epd_informe_evento.cod_evento = epd_bd_demanda.cod_evento
WHERE epd_bd_demanda.cod_dependencia='".$row['cod_dependencia']."' AND
	epd_bd_demanda.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY epd_bd_demanda.f_presentacion ASC";
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
	?>		        
			        <tr>
				        <td><? echo numeracion($fila['n_evento']);?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td><? echo fecha_normal($fila['f_presentacion']);?></td>
				        <td><? if ($fila['estado']==1) echo "Rendido"; elseif($fila['estado']==2) echo "Anulado"; else echo "Por rendir";?></td>
				        <td>

				        <? 
				        if($modo==edit and $anio==$anio_actual)
				        {
				        ?>
				        <a href="m_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_evento'];?>" class="small primary button">Editar</a>
				        <?
				        }
				        elseif($modo==anula and $fila['estado']<>2)
				        {
				        ?>
				        <a href="gestor_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_evento'];?>&action=ANULA" class="small alert button">Anular</a>
				        <?
				        }
				        elseif($fila['cod_rendicion']==NULL and $modo==rinde)
				        {
				        ?>
				        <a href="n_rinde_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_evento'];?>" class="small primary button">Rendir</a>
				        <?
				        }
				        elseif( $fila['cod_rendicion']<>NULL and $modo==imprime_rendicion)
				        {
				        ?>
				        <a href="../print/print_informe_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_rendicion'];?>" class="small success button">Imprimir rendicion</a>    
				        <?
				        }
				        elseif($modo==imprime)
				        {
				        ?>
				     <a href="../print/print_demanda_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_evento'];?>" class="small success button">Imprimir</a>
				        <?
				        }
				        elseif($fila['cod_rendicion']<>NULL and $modo==edit_rendicion)
				        {
				        ?>
				        <a href="m_rinde_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_rendicion'];?>" class="small primary button">Modificar rendicion</a>
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
