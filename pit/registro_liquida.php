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
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
</head>
<body>
<? include("menu.php");?>

<? echo $mensaje;?>

<!-- Iniciamos el contenido -->
<div class="row">
<div class="three panel columns">
<!-- Menu vertical -->
 <ul class="nav-bar vertical">
  <li class="has-flyout">
	 <a href="">CONTRATO PIT</a>
	 <ul class="flyout">
		 <li><a href="n_liquida_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar Propuesta</a></li>
		 <li><a href="pit_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Informe</a></li>
		 <li><a href="pit_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Información</a></li>
	 </ul>
 </li>
 
 <li class="has-flyout">
	 <a href="">INIC. PGRN</a>
	 <ul class="flyout">
	 	<li><a href="n_liquida_pgrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar Propuesta</a></li>
	 	<li><a href="pgrn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Informe</a></li>
	 	<li><a href="pgrn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Información</a></li>
	 </ul>
 </li> 
 
 
 <li class="has-flyout">
	 <a href="">INIC. PDN</a>
	 <ul class="flyout"> 
	 	<li><a href="n_liquida_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar Propuesta</a></li>
	 	<li><a href="pdn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Informe</a></li>
	 	<li><a href="pdn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Información</a></li>
	 </ul>
 </li> 
 
  <li class="has-flyout">
	 <a href="">INIC. IDL</a>
	 <ul class="flyout">
	 	<li><a href="n_liquida_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Liquidar Propuesta</a></li>
	 	<li><a href="idl_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir Informe</a></li>
	 	<li><a href="idl_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar Información</a></li>
	 </ul>
 </li>
<? 
if ($row['cod_dependencia']==001)
{
?> 
 <li class="has-flyout">
   <a href="">LIQUIDACIONES</a>
   <ul class="flyout">
     <li><a href="n_registro_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar liquidacion</li>
     <li><a href="registro_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Editar informacion</a></li>
   </ul>
 </li>
<?
}
?> 
</ul>
<!-- fin del menu vertical -->
<hr>
</div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        <? include("../plugins/buscar/buscador.html");?>
        
        <table class="responsive" id="lista">	        
	        <thead>
		        <tr>
			        <th>Nº</th>
			        <th>Tipo iniciativa</th>
			        <th class="two">Contrato</th>
			        <th>Fecha de Liquidación</th>
			        <th>Fecha de ingreso a UEP</th>
			        <th>Oficina</th>
			        <th><br/></th>
		        </tr>
	        </thead>
	        
	        <tbody>
	        <?
	        $num=0;
	        $sql="SELECT bd_registro_liquida.cod, 
			sys_bd_tipo_iniciativa.codigo_iniciativa, 
			bd_registro_liquida.n_contrato, 
			bd_registro_liquida.f_contrato, 
			bd_registro_liquida.f_liquidacion, 
			bd_registro_liquida.f_ingreso, 
			sys_bd_dependencia.nombre AS oficina
			FROM sys_bd_tipo_iniciativa INNER JOIN bd_registro_liquida ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = bd_registro_liquida.cod_tipo_iniciativa
			INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = bd_registro_liquida.cod_dependencia
			ORDER BY bd_registro_liquida.f_ingreso ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($fila=mysql_fetch_array($result))
			{
				$num++
	        ?>
				<tr>
					<td><? echo $num;?></td>
					<td><? echo $fila['codigo_iniciativa'];?></td>
					<td><? echo numeracion($fila['n_contrato'])."-".periodo($fila['f_contrato']);?></td>
					<td><? echo fecha_normal($fila['f_liquidacion']);?></td>
					<td><? echo fecha_normal($fila['f_ingreso']);?></td>
					<td><? echo $fila['oficina'];?></td>
					<td><a href="n_registro_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod'];?>" class="small primary button">Editar</a></td>
				</tr>
			<?
			}
			?>	
	        </tbody>
	        
        </table>
        
        
        
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
