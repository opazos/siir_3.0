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
  <li class="active"><a href="n_at_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar nuevo</a></li>
  <li><a href="at_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar informacion</a></li>
  <li><a href="at_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar</a></li>
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
				        <th><h5><small>Nº</small></h5></th>
				        <th><h5><small>Nombres completos</small></h5></th>
				        <th><h5><small>Organizacion a la que pertenece</small></h5></th>
				        <th><h5><small>Inicio</small></h5></th>
				        <th><h5><small>Termino</small></h5></th>
				        <th><h5><small>Monto</small></h5></th>
				        <th><h5><small>Calificacion</small></h5></th>
				        <th><br/></th>
			        </tr>
		       </thead>   
	<?
	$num=0;
	$sql="SELECT ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_sat.cod_sat, 
	sys_bd_califica.descripcion AS calificacion, 
	org_ficha_organizacion.nombre AS organizacion, 
	ficha_sat.f_inicio, 
	ficha_sat.f_termino, 
	(ficha_sat.aporte_pdss+ 
	ficha_sat.aporte_org+ 
	ficha_sat.aporte_otro) AS monto
FROM ficha_sat INNER JOIN ficha_ag_oferente ON ficha_sat.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_sat.n_documento = ficha_ag_oferente.n_documento
	 INNER JOIN sys_bd_califica ON sys_bd_califica.cod = ficha_sat.cod_calificacion
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY ficha_ag_oferente.nombre ASC";
	 $result=mysql_query($sql) or die (mysql_error());
	 while($fila=mysql_fetch_array($result))
	 {
		 $num++
	 
			       ?> 
			        <tr>
				        <td><h5><small><? echo $num;?></small></h5></td>
				        <td><h5><small><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></small></h5></td>
				        <td><h5><small><? echo $fila['organizacion'];?></small></h5></td>
				        <td><h5><small><? echo fecha_normal($fila['f_inicio']);?></small></h5></td>
				        <td><h5><small><? echo fecha_normal($fila['f_termino']);?></small></h5></td>
				        <td><h5><small><? echo number_format($fila['monto'],2);?></small></h5></td>
				        <td><h5><small><? echo $fila['calificacion'];?></small></h5></td>
				        <td>
				        <?
				        if ($modo==edit)
				        {
				        ?>
				        <a href="m_at_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_sat'];?>" class="small primary button">Editar</a>
				        <?
				        }
				        elseif($modo==delete)
				        {
				        ?>
				        <a href="gestor_at_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_sat'];?>&action=DELETE" class="small alert button">Eliminar</a>
				        <?
				        }
				        ?>
				        </td>
			        </tr>
		<?
		}
		?>	        
		       
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
