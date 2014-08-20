<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- Busco la información de la liquidación
$sql="SELECT * FROM pit_bd_mrn_liquida WHERE cod_ficha_liquida='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//Calculos para la ejecucion financiera
//1a.- CIF
$sql="SELECT SUM(cif_bd_concurso.costo) AS cif
FROM cif_bd_concurso
WHERE cif_bd_concurso.cod_mrn='".$r2['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);
$cif=$f1['cif'];

//1b.- SAT
$sql="SELECT SUM(ficha_sat.aporte_pdss) AS pdss, 
	SUM(ficha_sat.aporte_org) AS org, 
	SUM(ficha_sat.aporte_otro) AS otro
FROM pit_bd_ficha_mrn INNER JOIN ficha_sat ON pit_bd_ficha_mrn.cod_mrn = ficha_sat.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='".$r2['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f2=mysql_fetch_array($result);
$sat_pdss=$f2['pdss'];
$sat_org=$f2['org'];

//1c.- Visita Guiada
$sql="SELECT SUM(ficha_vg.aporte_pdss) AS pdss, 
	SUM(ficha_vg.aporte_org) AS org, 
	SUM(ficha_vg.aporte_otro) AS otro
FROM pit_bd_ficha_mrn INNER JOIN ficha_vg ON pit_bd_ficha_mrn.cod_mrn = ficha_vg.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='".$r2['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f3=mysql_fetch_array($result);
$vg_pdss=$f3['pdss'];
$vg_org=$f3['org'];

//1d.- Apoyo a la gestión
$sql="SELECT SUM(ficha_aag.aporte_pdss) AS pdss, 
	SUM(ficha_aag.aporte_org) AS org, 
	SUM(ficha_aag.aporte_otro) AS otro
FROM pit_bd_ficha_mrn INNER JOIN ficha_aag ON pit_bd_ficha_mrn.cod_mrn = ficha_aag.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = ficha_aag.cod_tipo_iniciativa
WHERE pit_bd_ficha_mrn.cod_mrn='".$r2['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f4=mysql_fetch_array($result);
$ag_pdss=$f4['pdss'];
$ag_org=$f4['org'];

//2.- Verificamos si este PGRN tiene adenda
$sql="SELECT pit_bd_ficha_adenda.n_adenda, 
  pit_bd_ficha_adenda.f_adenda, 
  pit_bd_ficha_adenda.meses, 
  pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_adenda.cod_pit = pit_bd_ficha_mrn.cod_pit
WHERE pit_bd_ficha_mrn.cod_mrn='".$r2['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f7=mysql_fetch_array($result);
$existe_adenda=mysql_num_rows($result);

//2.- Verificamos si este PGRN tiene adenda
$sql="SELECT (pit_adenda_mrn.cif_pdss+ 
	pit_adenda_mrn.at_pdss+ 
	pit_adenda_mrn.ag_pdss) AS total_pdss, 
	pit_bd_ficha_adenda.cod_adenda, 
	pit_adenda_mrn.cod_iniciativa, 
	pit_adenda_mrn.cif_pdss, 
	pit_adenda_mrn.at_pdss, 
	pit_adenda_mrn.at_org, 
	pit_adenda_mrn.ag_pdss, 
	pit_bd_ficha_adenda.n_adenda, 
	pit_bd_ficha_adenda.f_adenda, 
	pit_bd_ficha_adenda.f_inicio, 
	pit_bd_ficha_adenda.meses, 
	pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda INNER JOIN pit_adenda_mrn ON pit_bd_ficha_adenda.cod_adenda = pit_adenda_mrn.cod_adenda
WHERE pit_adenda_mrn.cod_mrn='".$r2['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f6=mysql_fetch_array($result);



//3.- Total contrato
$sql="SELECT (pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss) AS aporte_pdss, 
	(pit_bd_ficha_mrn.at_org+ 
	pit_bd_ficha_mrn.vg_org) AS aporte_org, 
	pit_bd_ficha_mrn.cif_pdss, 
	pit_bd_ficha_mrn.at_pdss, 
	pit_bd_ficha_mrn.vg_pdss, 
	pit_bd_ficha_mrn.ag_pdss, 
	pit_bd_ficha_mrn.at_org, 
	pit_bd_ficha_mrn.vg_org
FROM pit_bd_ficha_mrn
WHERE pit_bd_ficha_mrn.cod_mrn='".$r2['cod_mrn']."'";
$result=mysql_query($sql) or die (mysql_error());
$f5=mysql_fetch_array($result);

$total_contrato_pdss=$f5['aporte_pdss']+$f6['total_pdss'];
$total_ejecutado_pdss=$cif+$sat_pdss+$vg_pdss+$ag_pdss;

$pp_ejecutado_pdss=($total_ejecutado_pdss/$total_contrato_pdss)*100;


?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang="en"> 
<!--<![endif]-->
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
<dd  class="active"><a href="">Liquidacion de Planes de Recursos Naturales</a></dd>
</dl>

<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_liquida_pgrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="row">
  <div class="twelve columns"><h6>I.- Datos de la iniciativa a liquidar</h6></div>
  <div class="two columns">Iniciativa a liquidar</div>
  <div class="ten columns">
    <select name="iniciativa" class="large" disabled="disabled">
     <option value="" selected="selected">Seleccionar</option>
    <?
    $sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
    org_ficha_organizacion.n_documento, 
    org_ficha_organizacion.nombre, 
    pit_bd_ficha_mrn.cod_estado_iniciativa, 
    org_ficha_organizacion.cod_dependencia
    FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
    WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
    $result=mysql_query($sql) or die (mysql_error());
    while($r1=mysql_fetch_array($result))
    {
    ?>
    <option value="<? echo $r1['cod_mrn'];?>" <? if ($r1['cod_mrn']==$r2['cod_mrn']) echo "selected";?>><? echo $r1['nombre'];?></option>
    <?
    }
    ?> 
    </select>
  </div>
  <div class="two columns">Fecha de liquidación</div>
  <div class="four columns"><input type="date" name="f_liquidacion" placeholder="aaaa-mm-dd" maxlength="10" class="required date seven" value="<? echo $r2['f_liquidacion'];?>"></div>
  <div class="two columns">Calificación de la iniciativa</div>
  <div class="four columns">
    <select name="calificacion" class="mini">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1" <? if ($r2['cod_calificacion']==1) echo "selected";?>>MALA</option>
      <option value="2" <? if ($r2['cod_calificacion']==2) echo "selected";?>>REGULAR</option>
      <option value="3" <? if ($r2['cod_calificacion']==3) echo "selected";?>>BUENA</option>
      <option value="4" <? if ($r2['cod_calificacion']==4) echo "selected";?>>MUY BUENA</option>
    </select>  
  </div>
  <div class="twelve columns"><br/></div>
  <div class="two columns">Hubo cambios en la lista de participantes?</div>
  <div class="four columns">
    <select name="hc_socio" class="seven">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1" <? if ($r2['hc_soc']==1) echo "selected";?>>Si</option>
      <option value="0" <? if ($r2['hc_soc']==0) echo "selected";?>>No</option>
    </select>
  </div>

  <div class="two columns">Si hubo cambios indicar el motivo</div>
  <div class="four columns">
    <textarea name="just_socio"><? echo $r2['just_soc'];?></textarea>
  </div>

  <div class="two columns">Hubo cambios en la junta directiva?</div>
  <div class="four columns">
    <select name="hc_dir" class="seven">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1" <? if ($r2['hc_dir']==1) echo "selected";?>>Si</option>
      <option value="0" <? if ($r2['hc_dir']==0) echo "selected";?>>No</option>
    </select>
  </div>

  <div class="two columns">Si hubo cambios indicar el motivo</div>
  <div class="four columns">
    <textarea name="just_dir"><? echo $r2['just_dir'];?></textarea>
  </div>
  <div class="twelve columns"><h6>II.- Información sobre desembolsos</h6></div>

  <div class="two columns">Fecha de Desembolso</div>
  <div class="four columns"><input type="date" name="f_desembolso" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r2['f_desembolso'];?>"></div>
  <div class="two columns">Nº Cheque y/o C.O</div>
  <div class="four columns"><input type="text" name="n_cheque" class="required five" value="<? echo $r2['n_cheque'];?>"></div>

  <div class="twelve columns"><h6>III.- Comentarios/Observaciones</h6></div>
  <div class="twelve columns"><textarea name="observaciones" rows="5"  style="width: 100%"><? echo $r2['observaciones'];?></textarea></div>
</div>

<div class="row">
	<div class="twelve columns"><h6>IV.- Addendas (Información generada de forma automática)</h6></div>
	<div class="two columns">Esta iniciativa tiene addenda</div>
	<div class="four columns">
		<select name="tiene_addenda" class="mini" disabled="disabled">
			<option value="1" <? if($existe_adenda<>0) echo "selected";?>>SI</option>
			<option value="0" <? if($existe_adenda==0) echo "selected";?>>NO</option>
		</select>
	</div>
	<div class="two columns">Número de addenda</div>
	<div class="four columns"><input type="text" class="seven" value="<?php if($existe_adenda<>0) echo numeracion($f7['n_adenda']);?>" disabled="disabled"></div>
	<div class="two columns">Fecha de firma</div>
	<div class="four columns"><input type="date" placeholder="aaaa-mm-dd" class="date seven" value="<?php if($existe_adenda<>0) echo $f7['f_adenda'];?>" disabled="disabled"></div>
	<div class="two columns">Fecha de termino</div>
	<div class="four columns"><input type="date" placeholder="aaaa-mm-dd" class="date seven" value="<?php if($existe_adenda<>0) echo $f7['f_termino'];?>" disabled="disabled"></div>
</div>

<div class="row">
	<div class="twelve columns"><h6>V.- Estado de ejecución Financiera (<? echo number_format($pp_ejecutado_pdss);?> %)</h6></div>
	<div class="twelve columns"><? if ($pp_ejecutado_pdss<99) echo "<a href='../seguimiento/index.php?SES=".$SES."&anio=".$anio."' class='tiny alert button'>ATENCION: AUN NO A EJECUTADO EL 100% DE LO ENTREGADO, PUEDE TERMINAR DE REGISTRAR LA INFORMACION DE EJECUCION DANDO CLICK AQUI</a>";?></div>
	<div class="twelve columns"><hr/></div>
</div>

<table>
	<thead>
		<tr>
			<th rowspan="2" class="six">Actividad a desarrollar</th>
			<th colspan="2">NEC PDSS II (S/.)</th>
			<th colspan="2">ORGANIZACIÓN (S/.)</th>
		</tr>
		<tr>
			<th>Programado</th>
			<th>Ejecutado</th>
			<th>Programado</th>
			<th>Ejecutado</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Concursos Interfamiliares</td>
			<td><? echo number_format($f5['cif_pdss']+$f6['cif_pdss'],2);?></td>
			<td><input type="text" name="total1" value="<? echo $cif;?>" readonly="readonly"></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td>Asistencia Técnica</td>
			<td><? echo number_format($f5['at_pdss']+$f6['at_pdss'],2);?></td>
			<td><input type="text" name="total2" value="<? echo $sat_pdss;?>" readonly="readonly"></td>
			<td><? echo number_format($f5['at_org']+$f6['at_org'],2);?></td>
			<td><input type="text" name="total3" value="<? echo $sat_org;?>" readonly="readonly"></td>
		</tr>
		<tr>
			<td>Visita Guiada</td>
			<td><? echo number_format($f5['vg_pdss'],2);?></td>
			<td><input type="text" name="total4" value="<? echo $vg_pdss;?>" readonly="readonly"></td>
			<td><? echo number_format($f5['vg_org'],2);?></td>
			<td><input type="text" name="total5" value="<? echo $vg_org;?>" readonly="readonly"></td>
		</tr>
		<tr>
			<td>Apoyo a la Gestión</td>
			<td><? echo number_format($f5['ag_pdss']+$f6['ag_pdss'],2);?></td>
			<td><input type="text" name="total6" value="<? echo $ag_pdss;?>" readonly="readonly"></td>
			<td></td>
			<td></td>
		</tr>	

	<tfoot>	
		<tr>
			<td>TOTALES</td>
			<td><? echo number_format($f5['aporte_pdss'],2);?></td>
			<td><? echo number_format($cif+$sat_pdss+$vg_pdss+$ag_pdss,2);?></td>
			<td><? echo number_format($f5['aporte_org'],2);?></td>
			<td><? echo number_format($sat_org+$vg_org,2);?></td>
		</tr>
	</tfoot>					
	</tbody>
</table>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>	
	<a href="pgrn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
