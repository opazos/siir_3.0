<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//obtengo la numeracion
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_solicitud_iniciativa, 
	sys_bd_numera_dependencia.n_atf_iniciativa
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$n_solicitud=$r1['n_solicitud_iniciativa']+1;

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
  <script src="../javascripts/btn_envia.js"></script>
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
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Solicitud de pago para Addenda</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_adenda_monto.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PAGO" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>I.- Datos de la solicitud de desembolso</h6></div>
<div class="two columns">Nº de Solicitud</div>
<div class="four columns"><input type="text" name="n_solicitud" class="required digits five" value="<? echo $n_solicitud;?>" readonly="readonly"><input type="hidden" name="codigo" value="<? echo $r1['cod'];?>"></div>	
<div class="two columns">Fecha de Solicitud</div>
<div class="four columns"><input type="date" name="f_solicitud" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>
<div class="two columns">Fte FIDA %</div>
<div class="four columns"><input type="text" name="fte_fida" class="required five number"></div>
<div class="two columns">Fte RO %</div>
<div class="four columns"><input type="text" name="fte_ro" class="required five number"></div>


<div class="twelve columns"><h6>II.- Autorizacion de Transferencia de Fondos para Plan de Inversión Territorial</h6></div>
<table class="responsive">
	<thead>
		<tr>
			<th>Nº de ATF</th>
			<th class="nine">Nombre de la Organizacion PIT</th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	$num=$r1['n_atf_iniciativa'];
	$sql="SELECT pit_adenda_pit.cod_iniciativa, 
	pit_adenda_pit.n_atf, 
	org_ficha_taz.nombre
FROM pit_bd_ficha_pit INNER JOIN pit_adenda_pit ON pit_bd_ficha_pit.cod_pit = pit_adenda_pit.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_adenda_pit.cod_adenda='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
		$cad=$f1['cod_iniciativa'];
		$num++
	?>
		<tr>
			<td><input type="text" name="n_atf_pit[<? echo $cad;?>]" class="required digits nine" value="<? echo $num;?>"></td>
			<td><? echo $f1['nombre'];?></td>
			<td><a href="" class="small success button">Actualizar</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
</table>
<div class="twelve columns"><h6>III.- Autorizacion de Transferencia de Fondos para Plan de Gestión de Recursos Naturales</h6></div>
	<table class="responsive">
	<thead>
		<tr>
			<th>Nº de ATF</th>
			<th>Nombre de la Organización de PGRN</th>
			<th>Número de voucher</th>
			<th>Monto de contrapartida(S/.)</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$nam=$num;
	$sql="SELECT pit_adenda_mrn.cod_iniciativa, 
	org_ficha_organizacion.nombre, 
	pit_adenda_mrn.n_atf
FROM pit_bd_ficha_mrn INNER JOIN pit_adenda_mrn ON pit_bd_ficha_mrn.cod_mrn = pit_adenda_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_adenda_mrn.cod_adenda='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	while($f2=mysql_fetch_array($result))
	{
		$cid=$f2['cod_iniciativa'];
		$nam++
	?>
		<tr>
			<td><input type="text" name="n_atf_mrn[<? echo $f2['cod_iniciativa'];?>]" class="required digits nine" value="<? echo $nam;?>"></td>
			<td><? echo $f2['nombre'];?></td>
			<td><input type="text" name="n_voucher[<? echo $f2['cod_iniciativa'];?>]" class="required nine"></td>
			<td><input type="text" name="monto_aporte[<? echo $f2['cod_iniciativa'];?>]" class="required number nine"></td>
			<td><a href="" class="small success button">Actualizar</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	</table>
	
	<div class="twelve columns">
		<input type="hidden" name="n_atf" value="<? echo $nam;?>">
		<button type="submit" class="primary button">Guardar cambios</button>
		<a href="adenda_monto.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pago" class="secondary button">Finalizar</a>
		
	</div>
	
</form>
</div>
</li>
</ul>
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
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
