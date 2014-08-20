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
 	<li class="active"><a href="calif_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica">Calificar</a></li>
 	<li><a href="calif_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=premio">Distribuir premios</a></li>
 	<li><a href="calif_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_result">Imprimir resultados</a></li>
 	<li><a href="calif_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_acta">Imprimir acta</a></li>
 

</ul>
 
 
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	        <!-- aca pego el contenido -->
	        
	        <? include("../plugins/buscar/buscador.html");?> 
	        
	        <table class="responsive" id="lista">
		        <thead>
			        <tr>
				        <th>Nº</th>
				        <th>Nombre del concurso</th>
				        <th>Tipo de concurso</th>
				        <th>Fecha</th>
				        <th>Oficina</th>
				        <th><br/></th>
			        </tr>
		        </thead>
		        
		        <tbody>
		        <?
		        	if ($row['cod_dependencia']==001)
		        	{
		        	$sql="SELECT gcac_concurso_clar.cod_concurso, 
		        	sys_bd_tipo_concurso_clar.descripcion AS tipo, 
		        	gcac_concurso_clar.f_concurso, 
		        	gcac_concurso_clar.nombre, 
		        	sys_bd_dependencia.nombre AS oficina
		        	FROM sys_bd_tipo_concurso_clar INNER JOIN gcac_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
		        	INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gcac_concurso_clar.cod_dependencia
		        	WHERE gcac_concurso_clar.cod_tipo_concurso=3
		        	ORDER BY gcac_concurso_clar.f_concurso ASC";
	 				}
	 				else
	 				{
		 			$sql="SELECT gcac_concurso_clar.cod_concurso, 
		 			sys_bd_tipo_concurso_clar.descripcion AS tipo, 
		 			gcac_concurso_clar.f_concurso, 
		 			gcac_concurso_clar.nombre, 
		 			sys_bd_dependencia.nombre AS oficina
		 			FROM sys_bd_tipo_concurso_clar INNER JOIN gcac_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
		 			INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gcac_concurso_clar.cod_dependencia
		 			WHERE gcac_concurso_clar.cod_tipo_concurso=3 AND
		 			sys_bd_dependencia.cod_dependencia='".$row['cod_dependencia']."'
		 			ORDER BY gcac_concurso_clar.f_concurso ASC";	
	 				}
	 				$n=0;
	 				$result=mysql_query($sql) or die (mysql_error());
	 				while($fila=mysql_fetch_array($result))
	 				{
		 				$n++
	 				
		        ?>
			        <tr>
				        <td><? echo $n;?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td><? echo $fila['tipo'];?></td>
				        <td><? echo fecha_normal($fila['f_concurso']);?></td>
				        <td><? echo $fila['oficina'];?></td>
				        <td>
					        <?
					        if($modo==califica)
					        {
					        ?>
					        <a href="n_calif_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_concurso'];?>" class="small secondary button">Calificar</a>
					        <?
					        }
					        elseif($modo==premio)
					        {
					        ?>
					        <a href="n_premia_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_concurso'];?>" class="small secondary button">Premiar</a>
					        <?
					        }
					        elseif($modo==print_result)
					        {
					        ?>
					        <a href="../print/print_result_concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_concurso'];?>&modo=danza" class="small success button">Imprimir cuadro</a>
					        <?
					        }
					        elseif($modo==print_acta)
					        {
					        ?>
					        <a href="../print/print_acta_concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_concurso'];?>&modo=danza" class="small success button">Imprimir acta</a>
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
