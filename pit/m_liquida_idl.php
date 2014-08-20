<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


$sql="SELECT * FROM pit_bd_idl_sd WHERE cod_ficha_sd='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

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
<dd  class="active"><a href="">Liquidación de Inversiones de Desarrollo Local</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_idl_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE_LIQUIDA" onsubmit="return checkSubmit();">
	
	<div class="twelve columns">Seleccionar IDL</div>
	<div class="twelve columns">
		<select name="idl" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
			org_ficha_organizacion.nombre, 
			pit_bd_ficha_idl.denominacion
			FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
			WHERE
			org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
			ORDER BY org_ficha_organizacion.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_ficha_idl'];?>" <? if ($f1['cod_ficha_idl']==$row1['cod_idl']) echo "selected";?>><? echo $f1['nombre']." / ".$f1['denominacion'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="twelve columns"><br/></div>
	<div class="two columns">Fecha de presentacion del informe final</div>
	<div class="ten columns"><input type="date" name="f_presentacion" class="three required date" placeholder="aaaa-mm-dd" maxlength="10"  value="<? echo $row1['f_desembolso'];?>"></div>	
	<div class="twelve columns"><h6>II.- Información de avance fisico financiero de la IDL</h6></div>
	
	<div class="two columns">Nº de Cheque/CO con que se entrego desembolso</div>
	<div class="four columns"><input type="text" name="n_cheque" class="five required" value="<? echo $row1['n_cheque'];?>"></div>
	<div class="two columns">Fecha de deposito Desembolso</div>
	<div class="four columns"><input type="date" name="f_desembolso" class="seven date required" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row1['f_desembolso'];?>"></div>
	
	<div class="two columns">Monto ejecutado PDSS(S/.)</div>
	<div class="four columns"><input type="text" name="total1" class="required number five" value="<? echo $row1['ejec_idl'];?>"></div>
	<div class="two columns">Monto ejecutado Municipio (S/.)</div>
	<div class="four columns"><input type="text" name="total2" class="required number five" value="<? echo $row1['ejec_org'];?>"></div>

	<div class="twelve columns">En caso la obra presente retrasos, o si desea añadir un comentario. Agregarlo a continuación</div>
	<div class="twelve columns">
		<textarea name="comentario1" rows="5"><? echo $row1['just_ejec'];?></textarea>
	</div>
	
	<div class="two columns">Nivel de avance de la obra</div>
	<div class="four columns">
		<select name="nivel" class="five required">
			<option value="" selected="selected">Seleccionar</option>
			<option value="25" <? if ($row1['pp_avance']==25) echo "selected";?>>25%</option>
			<option value="50" <? if ($row1['pp_avance']==50) echo "selected";?>>50%</option>
			<option value="75" <? if ($row1['pp_avance']==75) echo "selected";?>>75%</option>
			<option value="100"  <? if ($row1['pp_avance']==100) echo "selected";?>>100%</option>									
		</select>
	</div>
	<div class="two columns">La obra presenta retrasos?</div>
	<div class="four columns">
		<select name="retraso" class="five required">
			<option value="" selected="selected">Seleccionar</option>
			<option value="1" <? if ($row1['cumple_plazo']==1) echo "selected";?>>Si</option>
			<option value="0" <? if ($row1['cumple_plazo']==0) echo "selected";?>>No</option>
		</select>
	</div>
	<div class="twelve columns">En caso la obra presente retrasos, o si desea añadir un comentario. Agregarlo a continuación</div>
	<div class="twelve columns">
		<textarea name="comentario" rows="5"><? echo $row1['just_plazo'];?></textarea>
	</div>
	
	<div class="twelve columns"><h6>III.- Calificación de la IDL por la Oficina Local</h6></div>
	<div class="six columns">Cumplimiento de objetivos y metas</div>
	<div class="six columns">
		<select name="calif_1">
			<option value="" selected="selected">Seleccionar</option>
			<option value="1" <? if ($row1['calif_1']==1) echo "selected";?>>Regular</option>
			<option value="2" <? if ($row1['calif_1']==2) echo "selected";?>>Buena</option>
			<option value="3" <? if ($row1['calif_3']==1) echo "selected";?>>Muy Buena</option>
		</select>
	</div>

	<div class="six columns">Perspectivas de operación y mantenimiento</div>
	<div class="six columns">
		<select name="calif_2">
			<option value="" selected="selected">Seleccionar</option>
			<option value="1" <? if ($row1['calif_2']==1) echo "selected";?>>Regular</option>
			<option value="2" <? if ($row1['calif_2']==2) echo "selected";?>>Buena</option>
			<option value="3" <? if ($row1['calif_2']==3) echo "selected";?>>Muy Buena</option>
		</select>
	</div>	

	<div class="six columns">Nivel de aceptación de la población objetivo</div>
	<div class="six columns">
		<select name="calif_3">
			<option value="" selected="selected">Seleccionar</option>
			<option value="1" <? if ($row1['calif_3']==1) echo "selected";?>>Regular</option>
			<option value="2" <? if ($row1['calif_3']==2) echo "selected";?>>Buena</option>
			<option value="3" <? if ($row1['calif_3']==3) echo "selected";?>>Muy Buena</option>
		</select>
	</div>
	
	<div class="twelve columns"><br/></div>	
	
	
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
		<a href="idl_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
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
