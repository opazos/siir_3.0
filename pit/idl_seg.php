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
<? include("menu.php");?>


<!-- Iniciamos el contenido -->

<div class="row">
<div class="three panel columns">
<!-- Menu vertical -->
 <ul class="nav-bar vertical">
 <li class="has-flyout">
	 <a href="">PIT</a>
	 <ul class="flyout">
		 <li><a href="n_pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Presentar propuesta</a></li>
		 <li><a href="pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Carpeta</a></li>		 
		 <li><a href="pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Quitar propuesta</a></li>
	 </ul>
 </li>
 
 <li class="has-flyout">
	 <a href="">PGRN</a>
	 <ul class="flyout">
		 <li><a href="n_pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Presentar propuesta</a></li>
		 <li><a href="pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Carpeta</a></li>	
		 <li><a href="pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_familia">Imprimir relacion de participantes</a></li>	 
		 <li><a href="pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Quitar propuesta</a></li>
	 </ul>
 </li> 
 
 
 <li class="has-flyout">
	 <a href="">PDN</a>
	 <ul class="flyout">
		 <li><a href="n_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Presentar propuesta</a></li>
		 <li><a href="pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Carpeta</a></li>	
		 <li><a href="pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_familia">Imprimir relacion de participantes</a></li>	 
		 <li><a href="pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Quitar propuesta</a></li>
	 </ul>
 </li> 
 
  <li class="has-flyout">
	 <a href="">IDL</a>
	 <ul class="flyout">
		 <li><a href="n_idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Presentar propuesta</a></li>
		 <li><a href="idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Carpeta</a></li>		 
		 <li><a href="idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Quitar propuesta</a></li>
	 </ul>
 </li>
</ul>
<!-- fin del menu vertical -->
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
				        <th>Nº de documento</th>
				        <th>Nombre de la organizacion / Denominacion de la IDL</th>
				        <th>Fecha de presentacion</th>
				        <th>Estado</th>
				        <th><br/></th>
			        </tr>
		        </thead> 
			        <?
			        $num=0;
			        if ($row['cod_dependencia']==001)
			        {
			        $sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
			        pit_bd_ficha_idl.denominacion, 
			        pit_bd_ficha_idl.f_presentacion_2, 
			        org_ficha_organizacion.n_documento, 
			        org_ficha_organizacion.nombre, 
			        org_ficha_organizacion.cod_dependencia, 
			        sys_bd_estado_iniciativa.descripcion AS estado, 
			        pit_bd_idl_sd.cod_ficha_sd
			        FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
			        INNER JOIN pit_bd_idl_sd ON pit_bd_idl_sd.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
			        INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
			        WHERE pit_bd_ficha_idl.cod_estado_iniciativa=006";
			        }
			        else
			        {
			        $sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
			        pit_bd_ficha_idl.denominacion, 
			        pit_bd_ficha_idl.f_presentacion_2, 
			        org_ficha_organizacion.n_documento, 
			        org_ficha_organizacion.nombre, 
			        org_ficha_organizacion.cod_dependencia, 
			        sys_bd_estado_iniciativa.descripcion AS estado, 
			        pit_bd_idl_sd.cod_ficha_sd
			        FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
			        INNER JOIN pit_bd_idl_sd ON pit_bd_idl_sd.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
			        INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
			        WHERE pit_bd_ficha_idl.cod_estado_iniciativa=006 AND
			        org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
			        }
			        $result=mysql_query($sql) or die (mysql_error());
			        while($fila=mysql_fetch_array($result))
			        {
				        $num++		        
				   ?>
			        <tr>
				        <td><? echo $num;?></td>
				        <td><? echo $fila['n_documento'];?></td>
				        <td><? echo $fila['nombre']." ".$fila['denominacion'];?></td>
				        <td><? echo fecha_normal($fila['f_presentacion_2']);?></td>
				        <td><? echo $fila['estado'];?></td>
				        <td>
				        <?
				        if ($modo==imprime)
				        {
				        ?>
				        <a href="../print/print_informe_idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_ficha_sd'];?>" class="small success button">Imprimir</a>
				        <?
				        }
				        elseif($modo==edit)
				        {
				        ?>
				        <a href="m_idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_sd'];?>" class="small primary button">Editar</a>
				        <?
				        }
				        elseif($modo==delete)
				        {
				        ?>
				        <a href="gestor_idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_sd'];?>&action=DELETE" class="small alert button">Quitar</a>
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
