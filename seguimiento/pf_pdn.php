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
  <li class="active"><a href="n_pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar nuevo evento</a></li>
  <li><a href="pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar evento</a></li>
  <li><a href="pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar evento</a></li>
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
				        <th>Organizacion participante</th>
				        <th>Nombre del evento</th>
				        <th>Fecha</th>
				        <th><br/></th>
			        </tr>
		        </thead> 
			        <?
			        $num=0;
			        $sql="SELECT ficha_pf.cod_feria, 
			        ficha_pf.denominacion, 
			        ficha_pf.f_inicio, 
			        org_ficha_organizacion.nombre
			        FROM pit_bd_ficha_pdn INNER JOIN ficha_pf ON pit_bd_ficha_pdn.cod_pdn = ficha_pf.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_pf.cod_tipo_iniciativa
			        INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
			        WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
			        ORDER BY org_ficha_organizacion.nombre ASC";
			        $result=mysql_query($sql) or die (mysql_error());
			        while($fila=mysql_fetch_array($result))
			        {
				        $num++
			        ?>
			        <tr>
				        <td><? echo $num;?></td>
				        <td><? echo $fila['nombre'];?></td>
				        <td><? echo $fila['denominacion'];?></td>
				        <td><? echo fecha_normal($fila['f_inicio']);?></td>
				        <td>
					        <?
					        if ($modo==edit)
					        {
					        ?>
					        <a href="m_pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_feria'];?>" class="small primary button">Editar</a>
					        <?
					        }
					        elseif($modo==delete)
					        {
					        ?>
					        <a href="gestor_pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_feria'];?>&action=DELETE" class="small alert button">Eliminar</a>
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
