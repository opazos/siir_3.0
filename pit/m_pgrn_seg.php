<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


$sql="SELECT pit_bd_mrn_sd.cod_ficha_sd, 
	pit_bd_mrn_sd.cod_mrn, 
	pit_bd_mrn_sd.f_desembolso, 
	pit_bd_mrn_sd.n_cheque, 
	pit_bd_mrn_sd.hc_soc, 
	pit_bd_mrn_sd.just_soc, 
	pit_bd_mrn_sd.hc_dir, 
	pit_bd_mrn_sd.just_dir, 
	pit_bd_mrn_sd.mes, 
	pit_bd_ficha_mrn.n_voucher_2, 
	pit_bd_ficha_mrn.monto_organizacion_2, 
	pit_bd_ficha_mrn.f_presentacion_2
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_mrn_sd ON pit_bd_ficha_mrn.cod_mrn = pit_bd_mrn_sd.cod_mrn
WHERE pit_bd_mrn_sd.cod_ficha_sd='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Calculos para la ejecucion financiera

//1.- CIF
$sql="SELECT SUM(cif_bd_concurso.costo) AS cif
FROM cif_bd_concurso
WHERE cif_bd_concurso.cod_mrn='".$row['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);
$cif=$f1['cif'];

//2.- SAT
$sql="SELECT SUM(ficha_sat.aporte_pdss) AS pdss, 
	SUM(ficha_sat.aporte_org) AS org, 
	SUM(ficha_sat.aporte_otro) AS otro
FROM pit_bd_ficha_mrn INNER JOIN ficha_sat ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='".$row['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f2=mysql_fetch_array($result);
$sat_pdss=$f2['pdss'];
$sat_org=$f2['org'];

//3.- Visita Guiada
$sql="SELECT SUM(ficha_vg.aporte_pdss) AS pdss, 
	SUM(ficha_vg.aporte_org) AS org, 
	SUM(ficha_vg.aporte_otro) AS otro
FROM pit_bd_ficha_mrn INNER JOIN ficha_vg ON pit_bd_ficha_mrn.cod_mrn = ficha_vg.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='".$row['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f3=mysql_fetch_array($result);
$vg_pdss=$f3['pdss'];
$vg_org=$f3['org'];

//4.- Apoyo a la gestión
$sql="SELECT SUM(ficha_aag.aporte_pdss) AS pdss, 
	SUM(ficha_aag.aporte_org) AS org, 
	SUM(ficha_aag.aporte_otro) AS otro
FROM pit_bd_ficha_mrn INNER JOIN ficha_aag ON pit_bd_ficha_mrn.cod_mrn = ficha_aag.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_aag.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='".$row['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);
$ag_pdss=$f4['pdss'];
$ag_org=$f4['org'];

//5.- Total contrato
$sql="SELECT ((pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss)*0.70) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org
FROM pit_bd_ficha_mrn
WHERE pit_bd_ficha_mrn.cod_mrn='".$row['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f5=mysql_fetch_array($result);

$total_contrato_pdss=$f5['aporte_pdss'];
$total_ejecutado_pdss=$cif+$sat_pdss+$vg_pdss+$ag_pdss;

$pp_ejecutado_pdss=($total_ejecutado_pdss/$total_contrato_pdss)*100;


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
<dd  class="active"><a href="">Información del Plan de Gestión</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">

<form name="form5" method="post" action="gestor_mrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE" onsubmit="return checkSubmit();">
<div class="twelve columns">Seleccionar Plan de Gestión de Recursos Naturales</div>
<div class="twelve columns">
	<select name="mrn" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
		org_ficha_organizacion.nombre, 
		pit_bd_ficha_mrn.sector
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
		WHERE
		org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'
		ORDER BY org_ficha_organizacion.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_mrn'];?>" <? if ($f1['cod_mrn']==$row['cod_mrn']) echo "selected";?>><? echo $f1['nombre']." ".$f1['sector'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><h6>II.- Informacion de contrapartida y desembolso</h6></div>
<div class="two columns">Nº de voucher</div>
<div class="four columns"><input type="text" name="n_voucher" class="required seven" value="<? echo $row['n_voucher_2'];?>"></div>
<div class="two columns">Monto depositado por la Organización(S/.)</div>
<div class="four columns"><input type="text" name="monto_org" class="required number seven" value="<? echo $row['monto_organizacion_2'];?>"></div>
<div class="twelve columns"><h6>III.- Información de avance</h6></div>
<div class="two columns">Fecha de desembolso</div>
<div class="four columns"><input type="date" name="f_desembolso" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_desembolso'];?>"></div>
<div class="two columns">Nº de CH/ o C/O</div>
<div class="four columns"><input type="text" name="n_cheque" class="seven required" value="<? echo $row['n_cheque'];?>"></div>

<div class="two columns">Nº de meses ejecutados</div>
<div class="four columns"><input type="text" name="mes" class="seven required" value="<? echo $row['mes'];?>"></div>
<div class="two columns">Fecha de presentacion</div>
<div class="four columns"><input type="date" name="f_presentacion" class="seven date required" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_presentacion_2'];?>"></div>



<div class="two columns">Hubo cambios en la lista de participantes?</div>
<div class="four columns">
	<select name="hc_soc" class="seven">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['hc_soc']==1) echo "selected";?>>Si</option>
		<option value="0" <? if ($row['hc_soc']==0) echo "selected";?>>No</option>
	</select>
</div>

<div class="two columns">Si hubo cambios indicar el motivo</div>
<div class="four columns">
	<textarea name="just_soc"><? echo $row['just_soc'];?></textarea>
</div>

<div class="two columns">Hubo cambios en la junta directiva?</div>
<div class="four columns">
	<select name="hc_dir" class="seven">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['hc_dir']==1) echo "selected";?>>Si</option>
		<option value="0" <? if ($row['hc_dir']==0) echo "selected";?>>No</option>
	</select>
</div>

<div class="two columns">Si hubo cambios indicar el motivo</div>
<div class="four columns">
	<textarea name="just_dir"><? echo $row['just_dir'];?></textarea>
</div>

<div class="six columns"><h6>III.- EJECUCION FINANCIERA (<? echo number_format($pp_ejecutado_pdss);?> %) <? if ($pp_ejecutado_pdss<69) echo "ERROR: NO LLEGA AL 70% DE EJECUCION";?></h6></div>
	<div class="three columns"><h6>Sierra Sur</h6></div>
	<div class="three columns"><h6>Organizacion</h6></div>
	<div class="twelve columns"><hr/></div>
	
	<div class="six columns">Total ejecutado por CIF (S/.)</div>
	<div class="three columns"><input type="text" name="total1" class="required six number" value="<? echo $cif;?>"></div>
	<div class="three columns"><br/></div>

	<div class="six columns">Total ejecutado por Asistencia Tecnica (S/.)</div>
	<div class="three columns"><input type="text" name="total2" class="required six number" value="<? echo $sat_pdss;?>"></div>
	<div class="three columns"><input type="text" name="total3" class="required six number" value="<? echo $sat_org;?>"></div>
	
	<div class="six columns">Total ejecutado por Visita Guiada (S/.)</div>
	<div class="three columns"><input type="text" name="total4" class="required six number" value="<? echo $vg_pdss;?>"></div>
	<div class="three columns"><input type="text" name="total5" class="required six number" value="<? echo $vg_org;?>"></div>
	
	<div class="six columns">Total ejecutado por Apoyo a la Gestión (S/.)</div>
	<div class="three columns"><input type="text" name="total6" class="required six number" value="<? echo $ag_pdss;?>"></div>	

	

<div class="twelve columns"><br/></div>
	
	
<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
		<a href="pgrn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
<script type="text/javascript" src="../plugins/jquery.js"></script>

<!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>   

<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>
 
</body>
</html>
