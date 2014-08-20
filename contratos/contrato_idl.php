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
  <li class="active"><a href="n_contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar nuevo contrato</a></li>
  <li><a href="contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir contrato</a></li>
  <li><a href="contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar contrato</a></li>
<?
if ($row['cod_tipo_usuario']<>'U')
{
?>   
  <li><a href="contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=anula">Anular contrato</a></li>
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
				        <th>Nº</th>
				        <th>Nombre de la organizacion</th>
				        <th>Denominacion de la IDL</th>
				        <th>Fecha de contrato</th>
				        <th>Estado</th>
				        <th><br/></th>
			        </tr>
			        </thead>
<tbody>
<?
if ($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	sys_bd_estado_iniciativa.descripcion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre
FROM sys_bd_estado_iniciativa INNER JOIN pit_bd_ficha_idl ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.n_contrato<>0 AND
pit_bd_ficha_idl.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY pit_bd_ficha_idl.f_contrato ASC";
}
else
{
$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	pit_bd_ficha_idl.denominacion, 
	pit_bd_ficha_idl.n_contrato, 
	pit_bd_ficha_idl.f_contrato, 
	sys_bd_estado_iniciativa.descripcion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre
FROM sys_bd_estado_iniciativa INNER JOIN pit_bd_ficha_idl ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_idl.cod_estado_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.n_contrato<>0 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."' AND
pit_bd_ficha_idl.f_contrato BETWEEN '$anio-01-01' AND '$anio-12-31'
ORDER BY pit_bd_ficha_idl.f_contrato ASC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>			        
			        <tr>
				    <td><? echo numeracion($fila['n_contrato']);?></td>
				    <td><? echo $fila['nombre'];?></td>
				    <td><? echo $fila['denominacion'];?></td>
				    <td><? echo fecha_normal($fila['f_contrato']);?></td>
				    <td><? echo $fila['descripcion'];?></td>
				    <td>
				    <?
				    if ($modo==imprime)
				    {
				    ?>
				    <a href="../print/print_contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_ficha_idl'];?>" class="small success button">Imprimir</a>
				    <?
				    }
				    elseif($modo==edit and $anio==$anio_actual)
				    {
				    ?>
				    <a href="m_contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_idl'];?>" class="small primary button">Editar</a>
				    <?
				    }
				    elseif($modo==anula)
				    {
				    ?>
				    <a href="gestor_contrato_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_ficha_idl'];?>&action=ANULA" class="small alert button" onclick="return confirm('Va a anular este contrato, desea proceder ?')">Anular</a>
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
