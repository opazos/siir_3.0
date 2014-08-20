<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Busco la numeracion
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_contrato_iniciativa, 
	sys_bd_numera_dependencia.n_solicitud_iniciativa, 
	sys_bd_numera_dependencia.n_atf_iniciativa
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.periodo='$anio' AND
sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."'";
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
  <!-- Editor de texto -->
  <script type="text/javascript" src="../plugins/texteditor/jscripts/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="../plugins/texteditor/editor.js"></script>
  <!-- fin de editor de texto -->
  
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Nuevo contrato para Participación en Ferias</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_contrato_pf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>I.- Seleccionar a la Organización Contratante</h6></div>
<div class="twelve columns">
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
while($fila=mysql_fetch_array($result))
{
?>
<option value="<? echo $fila['cod_tipo_doc'].",".$fila['n_documento'];?>"><? echo $fila['nombre'];?></option>
<?
}
?>
</select>
</div>
<div class="twelve columns"><h6>II.- Datos del Evento</h6></div>
<div class="two columns">Nombre de la Feria/Evento</div>	
<div class="ten columns"><input type="text" name="evento" class="ten required"></div>
<div class="two columns">Fecha del evento</div>
<div class="four columns"><input type="date" name="f_inicio" class="seven required date" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">Nº de días de duración</div>
<div class="four columns"><input type="text" name="dia" class="required digits five"></div>


<div class="two columns">Participantes</div>
<div class="four columns"><input type="text" name="participantes" class="five required digits"></div>
<div class="two columns">Lugar</div>
<div class="four columns"><input type="text" name="lugar" class="ten required"></div>

<div class="twelve columns"><h6>III.- Objetivos de la visita</h6></div>
<div class="twelve columns">
	<textarea id="elm1" name="objetivo" rows="5"  style="width: 100%">
		
	</textarea>
</div>
<div class="twelve columns"><h6>IV.- Resultados previstos</h6></div>
<div class="twelve columns">
	<textarea id="elm2" name="resultado" rows="5"  style="width: 100%">
		
	</textarea>
</div>

<div class="twelve columns"><h6>V.- Datos del contrato a firmar</h6></div>
<div class="two columns">Nº de contrato</div>
<div class="four columns"><input type="text" name="n_contrato" class="required digits five" value="<? echo $n_contrato;?>"></div>
<div class="two columns">Fecha de contrato</div>
<div class="four columns"><input type="date" name="f_contrato" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">Nº de Solicitud</div>
<div class="four columns"><input type="text" name="n_solicitud" class="required digits five" value="<? echo $n_solicitud;?>"></div>
<div class="two columns">Nº de ATF</div>
<div class="four columns"><input type="text" name="n_atf" class="required digits five" value="<? echo $n_atf;?>"></div>
<div class="two columns">Nº de cuenta bancaria</div>
<div class="four columns"><input type="text" name="n_cuenta" class="required seven"></div>
<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi ORDER BY descripcion ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod_ifi'];?>"><? echo $r1['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Afectación Presupuestal</div>
<div class="ten columns">
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
		while($r2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r2['cod'];?>"><? echo $r2['codigo']."-".$r2['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><h6>VI.- Montos a confinanciar</h6></div>
<div class="two columns">Aporte Proyecto (S/.)</div>
<div class="two columns"><input type="text" name="aporte_pdss" class="required number ten"></div>
<div class="two columns">Aporte Organizacion (S/.)</div>
<div class="two columns"><input type="text" name="aporte_org" class="required number ten"></div>
<div class="two columns">Aporte Otro (S/.)</div>
<div class="two columns"><input type="text" name="aporte_otro" class="required number ten">
	<input type="hidden" name="codigo" value="<? echo $r1['cod'];?>">
</div>

<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="primary button" id="btn_envia">Guardar cambios</button>
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="contrato_pf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar operacion</a>
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
