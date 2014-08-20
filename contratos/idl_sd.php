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
  <li class="active"><a href="n_idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar solicitud y ATF's</a></li>
  <li><a href="idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir solicitud y ATF's</a></li>
  <li><a href="idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar solicitud y ATF's</a></li> 
</ul>
 
 
 <hr>


    </div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        
        <div class="twelve columns">
	        <!-- aca pego el contenido -->
	        
	        <table class="responsive" id="lista">
		        
			      <thead> 
			        <tr>
				        <th class="two">Nº de contrato</th>
				        <th>Nº de documento</th>
				        <th>Nombre de la entidad/ denominacion de la IDL</th>
				        <th>Fecha de desembolso</th>
				        <th><br/></th>
			        </tr>
			      </thead>
		<tbody>
		<?
		if ($row['cod_dependencia']==001)
		{
		$sql="SELECT clar_bd_ficha_sd_idl.cod_ficha_sd, 
		clar_bd_ficha_sd_idl.f_desembolso, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre, 
		pit_bd_ficha_idl.n_contrato, 
		pit_bd_ficha_idl.f_contrato, 
		pit_bd_ficha_idl.denominacion
		FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_sd_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_sd_idl.cod_idl
		INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
		WHERE
		clar_bd_ficha_sd_idl.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
		ORDER BY clar_bd_ficha_sd_idl.f_desembolso ASC";
		}
		else
		{
		$sql="SELECT clar_bd_ficha_sd_idl.cod_ficha_sd, 
		clar_bd_ficha_sd_idl.f_desembolso, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre, 
		pit_bd_ficha_idl.n_contrato, 
		pit_bd_ficha_idl.f_contrato, 
		pit_bd_ficha_idl.denominacion
		FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_sd_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_sd_idl.cod_idl
		INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
		WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
		clar_bd_ficha_sd_idl.f_desembolso BETWEEN '$anio-01-01' AND '$anio-12-31'
		ORDER BY clar_bd_ficha_sd_idl.f_desembolso ASC";
		}
		$result=mysql_query($sql) or die (mysql_error());
		while($fila=mysql_fetch_array($result))
		{
		?>	        
			        <tr>
				        <td><? echo numeracion($fila['n_contrato'])."-".periodo($fila['f_contrato']);?></td>
				        <td><? echo $fila['n_documento'];?></td>
				        <td><? echo $fila['nombre']." / ".$fila['denominacion'];?></td>
				        <td><? echo fecha_normal($fila['f_desembolso']);?></td>
				        <td>
				        <?
				        if ($modo==imprime)
				        {
				        ?>
				        <a href="../print/print_sd_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_ficha_sd'];?>" class="small success button">Imprimir</a>
				        <?
				        }
				        elseif($modo==edit and $anio==$anio_actual)
				        {
				        ?>
				        <a href="m_idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_sd'];?>" class="small primary button">Editar</a>
				        <?
				        }
				        elseif($modo==anula)
				        {
				        ?>
				        <a href="gestor_idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_sd'];?>&action=ANULA" class="small alert button">Anular</a>
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
