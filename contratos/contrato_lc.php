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
  <li class="active"><a href="n_contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar nuevo</a></li>
  <li><a href="contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Editar información</a></li>
  <li><a href="contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir</a></li>
  <li><a href="contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=anula">Anular</a></li>
</ul>
 
 
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	        <table id="lista" class="responsive">
		        
		        <thead>
			        <tr>
				        <th>Nº</th>
				        <th>Fecha</th>
				        <th>Nombres y apellidos del contratado</th>
				        <th>Cargo a ocupar</th>
				        <th>Estado</th>
				        <th><br/></th>
			        </tr>
		        </thead>
		        
		        <tbody>
<?
$sql="SELECT ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_contrato_ls.n_contrato, 
	ficha_contrato_ls.f_contrato, 
	ficha_contrato_ls.puesto, 
	sys_bd_estado_iniciativa.descripcion AS estado, 
	ficha_contrato_ls.cod_contrato
FROM ficha_contrato_ls INNER JOIN ficha_ag_oferente ON ficha_contrato_ls.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_contrato_ls.n_documento = ficha_ag_oferente.n_documento
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = ficha_contrato_ls.cod_estado_inciativa
WHERE ficha_contrato_ls.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY ficha_contrato_ls.f_contrato ASC, ficha_contrato_ls.n_contrato ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>		        
			        <tr>
				        <td><? echo numeracion($fila['n_contrato']);?></td>
				        <td><? echo fecha_normal($fila['f_contrato']);?></td>
				        <td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
				        <td><? echo $fila['puesto'];?></td>
				        <td><? echo $fila['estado'];?></td>
				        <td>
					        <?
					        if ($modo==imprime)
					        {
					        ?>
					        <a href="../print/print_contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_contrato'];?>" class="small success button">Imprimir</a>
					        <?
					        }
					        elseif($modo==edit)
					        {
					        ?>
					        <a href="edit_contrato_lc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_contrato'];?>&modo=edit" class="small primary button">Editar</a>
					        <?
					        }
					        elseif($modo==anula)
					        {
					        ?>
					        <a href="" class="small alert button">Anular</a>
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
