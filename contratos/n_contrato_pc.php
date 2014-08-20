<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_contrato_iniciativa, 
	sys_bd_numera_dependencia.n_atf_iniciativa, 
	sys_bd_numera_dependencia.n_solicitud_iniciativa
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$n_contrato=$r1['n_contrato_iniciativa']+1;
$n_solicitud=$r1['n_solicitud_iniciativa']+1;
$n_atf=$r1['n_atf_iniciativa']+1;

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
<dd  class="active"><a href="">Registro de contrato de Promoción Comercial</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_contrato_pc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="twelve columns">Seleccionar organizacion</div>
<div class="twelve columns">
<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
	<select name="org" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion
		WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY org_ficha_organizacion.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>"><? echo $f1['n_documento']." - ".$f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Nombre del evento</div>
<div class="four columns"><input type="text" name="evento" class="required ten"></div>
<div class="two columns">Nº de participantes</div>
<div class="four columns"><input type="text" name="participantes" class="required digits five"></div>
<div class="two columns">Fecha de inicio</div>
<div class="four columns"><input type="date" name="f_inicio" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">Fecha de termino</div>
<div class="four columns"><input type="date" name="f_termino" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>


	<div class="two columns">Departamento</div>
	<div class="four columns">
		<?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select class='ten' name='select1' id='select1' onChange='cargaContenido(this.id)' >";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?>
	</div>
	<div class="two columns">Provincia</div>
	<div class="four columns"><select disabled="disabled" name="select2" id="select2" class="ten" >
      <option value="0">Selecciona opci&oacute;n...</option>
    </select></div>
    <div class="two columns">Distrito</div>
    <div class="four columns"><select disabled="disabled" name="select3" id="select3" class="ten" >
      <option value="0">Selecciona opci&oacute;n...</option>
    </select></div>
    <div class="two columns">Lugar del evento</div>
    <div class="four columns"><input type="text" name="direccion"></div>

<div class="twelve columns">Objetivo del evento</div>
<div class="twelve columns"><textarea name="objetivo" rows="5"></textarea></div>

<div class="twelve columns">Resultados esperados</div>
<div class="twelve columns"><textarea name="resultado" rows="5"></textarea></div>

<div class="twelve columns"><h6>Información del contrato</h6></div>
<div class="two columns">Nº de contrato</div>
<div class="four columns"><input type="text" name="n_contrato" class="required digits five" value="<? echo $n_contrato;?>" readonly></div>
<div class="two columns">Fecha de contrato</div>
<div class="four columns"><input type="date" name="f_contrato" class="required date five" value="<? echo $fecha_hoy;?>"></div>
<div class="two columns">Nº de solicitud</div>
<div class="four columns"><input type="text" name="n_solicitud" class="required digits five" value="<? echo $n_solicitud;?>"></div>
<div class="two columns">Nº de ATF</div>
<div class="four columns"><input type="text" name="n_atf" class="required digits five" value="<? echo $n_atf;?>"></div>
<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="required five"></div>
<div class="two columns">Entidad financiera</div>
<div class="four columns">
	<select name="ifi" class="required seven">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi ORDER BY descripcion ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_ifi'];?>"><? echo $f2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><h6>Afectación Presupuestal</h6></div>
<div class="twelve columns">
	
	<select name="poa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT sys_bd_subactividad_poa.cod, 
		sys_bd_subactividad_poa.codigo, 
		sys_bd_subactividad_poa.nombre
		FROM sys_bd_subactividad_poa
		WHERE sys_bd_subactividad_poa.periodo='$anio'
		ORDER BY sys_bd_subactividad_poa.codigo ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f3['cod'];?>"><? echo $f3['codigo']."-".$f3['nombre'];?></option>
		<?
		}
		?>
	</select>
	
	
</div>
   
<div class="twelve columns"><h6>Fuente de Financiamiento</h6></div>
<div class="two columns">Fuente Fida %</div>
<div class="four columns"><input type="text" name="fida" class="required number seven"></div>
<div class="two columns">Fuente RO %</div>
<div class="four columns"><input type="text" name="ro" class="required number seven"></div>

<div class="twelve columns"><h6>Cofinanciamiento del evento</h6></div>
<div class="four columns">Aporte NEC PDSS II</div>
<div class="four columns">Aporte Organizacion</div>
<div class="four columns">Aporte Otros</div>
<div class="four columns"><input type="text" name="aporte_pdss" class="six required number"></div>
<div class="four columns"><input type="text" name="aporte_org" class="six required number"></div>
<div class="four columns"><input type="text" name="aporte_otro" class="six required number"></div>





<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="contrato_pc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Cancelar operacion</a>
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

<script type="text/javascript" src="../plugins/combo_dinamico/select_dependientes_3_niveles_poa.js"></script>
</body>
</html>
