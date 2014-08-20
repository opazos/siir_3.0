<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT clar_bd_ficha_sd_idl.cod_ficha_sd, 
	clar_bd_ficha_sd_idl.cod_clar, 
	clar_bd_ficha_sd_idl.cod_idl, 
	clar_bd_ficha_sd_idl.f_desembolso, 
	clar_bd_ficha_sd_idl.n_solicitud, 
	clar_atf_idl.n_atf, 
	clar_atf_idl.cod_atf_idl
FROM clar_atf_idl INNER JOIN clar_bd_ficha_sd_idl ON clar_atf_idl.cod_ficha_idl = clar_bd_ficha_sd_idl.cod_idl
WHERE clar_atf_idl.tipo_atf=2 AND
clar_bd_ficha_sd_idl.cod_ficha_sd='$id'";
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
<dd  class="active"><a href="">Nueva solicitud de desembolso para IDL</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="two columns">Nº de contrato</div>
<div class="four columns">
	<select name="idl">
		<option value="" selected="selected">Seleccionar</option>
		<?
		if ($row1['cod_dependencia']==001)
		{
		$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
		pit_bd_ficha_idl.n_contrato, 
		pit_bd_ficha_idl.f_contrato
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org";			
		}
		else
		{
		$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
		pit_bd_ficha_idl.n_contrato, 
		pit_bd_ficha_idl.f_contrato
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
		WHERE
		org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'";
		}
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod_ficha_idl'];?>" <? if ($r1['cod_ficha_idl']==$row['cod_idl']) echo "selected";?>><? echo numeracion($r1['n_contrato'])."-".periodo($r1['f_contrato']);?></option>
		<?
		}
		?>
		
	</select>
</div>
<div class="two columns">Fecha de desembolso</div>
<div class="four columns"><input type="date" name="f_desembolso" class="date require seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_desembolso'];?>">
<input type="hidden" name="codigo" value="<? echo $row['cod_ficha_sd'];?>">
<input type="hidden" name="cod_atf" value="<? echo $row['cod_atf_idl'];?>">
</div>
<div class="two columns">Nº de evento CLAR</div>
<div class="ten columns">
	<select name="clar">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_evento_clar.cod_clar, 
	clar_bd_evento_clar.f_evento, 
	sys_bd_dependencia.nombre
	FROM sys_bd_dependencia INNER JOIN clar_bd_evento_clar ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
	WHERE
	clar_bd_evento_clar.f_evento BETWEEN '$anio-01-01' AND '$anio-12-31'
	ORDER BY clar_bd_evento_clar.cod_clar ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($r2=mysql_fetch_array($result))
	{
		?>
		<option value="<? echo $r2['cod_clar'];?>" <? if ($r2['cod_clar']==$row['cod_clar']) echo "selected";?>><? echo numeracion($r2['cod_clar'])."-".periodo($r2['f_evento'])."/".$r2['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="two columns">Nº de Solicitud</div>
<div class="four columns"><input type="text" name="n_solicitud" class="required digits five" value="<? echo $row['n_solicitud'];?>"></div>
<div class="two columns">Nº de ATF</div>
<div class="four columns"><input type="text" name="n_atf" class="required digits five" value="<? echo $row['n_atf'];?>"></div>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="idl_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Cancelar</a>
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
