<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($modo==mrn)
{
$ruta="gestor_pit.php?SES=$SES&anio=$anio&action=ADD&modo=pit";
}
else
{
$ruta="gestor_pit.php?SES=$SES&anio=$anio&action=ADD";
}

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
<dd  class="active"><a href="">Registrar Propuesta de  Plan de Inversion Territorial</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="<? echo $ruta;?>" onsubmit="return checkSubmit();">
<div class="twelve columns"><h6>I.- Propuesta</h6></div>	
	<div class="twelve columns">Territorio que se desempeñará como PIT</div>
	<div class="twelve columns">
		<select name="territorio" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT org_ficha_taz.cod_tipo_doc, 
			org_ficha_taz.n_documento, 
			org_ficha_taz.nombre
			FROM org_ficha_taz
			WHERE org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
			ORDER BY org_ficha_taz.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($r1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $r1['cod_tipo_doc'].",".$r1['n_documento'];?>"><? echo $r1['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
<div class="twelve columns"><br/></div>	
	<div class="four columns">Presentara mapa cultural para concursar en CLAR?</div>
	<div class="one columns">Si <input type="radio" name="mapa" value="1" class="required"></div>
	<div class="one columns">No <input type="radio" name="mapa" value="0" class="required"></div>
	<div class="two columns">Fecha de presentacion de esta propuesta</div>
	<div class="four columns"><input type="date" name="f_presentacion" placeholder="aaaa-mm-dd" maxlength="10" class="date required five" value="<? echo $fecha_hoy;?>"></div>
<?
if ($modo==mrn)
{
?>	
	<div class="twelve columns"><h6>Informacion financiera</h6></div>
	
	<div class="two columns">Nº cuenta bancaria</div>
	<div class="two columns"><input type="text" name="n_cuenta" class="required eleven"></div>
	<div class="two columns">Entidad financiera</div>
	<div class="six columns">
		<select name="ifi" class="required seven">
			<option value="" selected="selected">Seleccionar</option>
<?
$sql="SELECT sys_bd_ifi.cod_ifi, 
	sys_bd_ifi.descripcion
FROM sys_bd_ifi
ORDER BY sys_bd_ifi.descripcion ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
?>
<option value="<? echo $r2['cod_ifi'];?>"><? echo $r2['descripcion'];?></option>
<?
}
?>
		</select>
	</div>
<hr/>
<div class="twelve columns"><h6>Propuesta de confinanciamiento y contrapartidas</h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>Nº animadores territoriales</th>
			<th>Incentivo por animador territorial</th>
			<th>Nº meses a trabajar</th>
			<th><span class="has-tip tip-top noradius" data-width="210" title="Esta información es opcional">Aporte de la Organizacion</span></th>
			<th><span class="has-tip tip-top noradius" data-width="210" title="Esta información es opcional">Nº voucher de deposito</span></th>
			<th><span class="has-tip tip-top noradius" data-width="210" title="Esta información es opcional">Monto depositado</span></th>
		</tr>
		<tr>
			<td><input type="text" name="n_animador" class="digits"></td>
			<td><input type="text" name="monto_animador" value="210" readonly="readonly"></td>
			<td><input type="text" name="n_mes" value="13" readonly="readonly"></td>
			<td><input type="text" name="aporte_org"></td>
			<td><input type="text" name="n_voucher"></td>
			<td><input type="text" name="deposito"></td>
		</tr>
	</tbody>
</table>

<div class="twelve columns"><h6>Propuesta de animadores territoriales</h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>DNI</th>
			<th>Nombres</th>
			<th>A. paterno</th>
			<th>A. materno</th>
			<th>Fecha nac.</th>
			<th>Sexo</th>
			<th>Nivel Educ.</th>
		</tr>
<?
for ($i = 1; $i <= 3; $i++) 
{
?>		
		<tr>
		<td><input name="dni[]" type="text" maxlength="8"></td>
		<td><input name="nombre[]" type="text"></td>
		<td><input type="text" name="paterno[]"></td>
		<td><input type="text" name="materno[]"></td>
		<td><input type="date" name="fecha[]" placeholder="aaaa-mm-dd" maxlength="10"></td>
		<td><select name="sexo[]"><option value="" selected="selected">Selecc.</option><option value="1">M</option><option value="0">F</option></select></td>
		<td><select name="nivel[]"><option value="" selected="selected">Selecc.</option><option value="001">Primaria</option><option value="002">Secundaria</option><option value="003">Superior</option><option value="004">Sin Instruccion</option></select></td>			
		</tr>
<?
}
?>		
	</tbody>
</table>
<?
}
?>
<div class="twelve columns"><h6>II.- Información general del Plan de inversión Territorial</h6></div>
<hr/>
<div class="six columns">a.- Principales cultivos (Por orden de importancia)</div>
<div class="six columns">b.- Principales crianzas de ganado</div>
<div class="six columns">
<table class="responsive">
	<tbody>
		<tr>
			<th>Tipo de cultivo</th>
			<th>Descripcion</th>
		</tr>
<?
for ($i = 1; $i <= 10; $i++) 
{
?>			
		<tr>
			<td>
				<select name="tipo_cultivo[]">
					<option value="" selected="selected">Seleccionar</option>
					<option value="1">Cultivos agricolas</option>
					<option value="2">Pastos y forrajes</option>
					<option value="3">Frutales</option>
					<option value="4">Plantaciones forestales</option>
				</select>
			</td>
			<td><input name="describe_cultivo[]" type="text"></td>
		</tr>
<?
}
?>		
	</tbody>
</table>
</div>
<div class="six columns">
<table class="responsive">
	<tbody>
		<tr>
			<th>Tipo de ganado</th>
			<th>Descripcion</th>
		</tr>
<?
for ($i = 1; $i <= 10; $i++) 
{
?>			
		<tr>
			<td>
				<select name="tipo_ganado[]">
					<option value="" selected="selected">Seleccionar</option>
					<option value="1">Ganado mayor</option>
					<option value="2">Ganado menor</option>
				</select>
			</td>
			<td><input name="describe_ganado[]" type="text"></td>
		</tr>
<?
}
?>		
	</tbody>
</table>	
</div>
<div class="six columns">c.- Areas del territorio</div>
<div class="six columns">d.- Tipo de actividades de transformacion y servicio</div>

<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Tipologia del área</th>
				<th>Nº Has</th>
			</tr>
			<tr>
				<td>Area Total</td>
				<td><input type="text" name="area1"></td>
			</tr>
			<tr>
				<td>Cultivos agricolas con riego</td>
				<td><input type="text" name="area2"></td>
			</tr>
			<tr>
				<td>Pastos con riego</td>
				<td><input type="text" name="area3"></td>
			</tr>
			<tr>
				<td>Areas forestales con riego</td>
				<td><input type="text" name="area4"></td>
			</tr>
			<tr>
				<td>Cultivos agricolas en secano</td>
				<td><input type="text" name="area5"></td>
			</tr>
			<tr>
				<td>Pastos en secano</td>
				<td><input type="text" name="area6"></td>
			</tr>
			<tr>
				<td>Areas forestales en secano</td>
				<td><input type="text" name="area7"></td>
			</tr>
			<tr>
				<td>Areas de pastos naturales</td>
				<td><input type="text" name="area8"></td>
			</tr>
			<tr>
				<td>Otras areas</td>
				<td><input type="text" name="area9"></td>
			</tr>																								
		</tbody>
	</table>
</div>

<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Tipo de actividad</th>
				<th>Descripcion</th>
			</tr>
<?
for ($i = 1; $i <= 9; $i++) 
{
?>			
			<tr>
				<td><input type="text" name="tipo_actividad[]"></td>
				<td><input type="text" name="describe_actividad[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
</div>

<div class="six columns">e.- Principales festividades en el territorio</div>
<div class="six columns">f.- Patrimonio y manisfestaciones culturales</div>

<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Dia</th>
				<th>Mes</th>
				<th>Festividad</th>
			</tr>
<?
for ($i = 1; $i <= 4; $i++) 
{
?>				
			<tr>
				<td><input type="text" name="festday[]"></td>
				<td>
					<select name="festmes[]">
				    <option value="" selected="selected">Seleccionar</option>
				    <option value="ENERO">ENERO</option>
				    <option value="FEBRERO">FEBRERO</option>
				    <option value="MARZO">MARZO</option>
				    <option value="ABRIL">ABRIL</option>
				    <option value="MAYO">MAYO</option>
				    <option value="JUNIO">JUNIO</option>
				    <option value="JULIO">JULIO</option>
				    <option value="AGOSTO">AGOSTO</option>
				    <option value="SEPTIEMBRE">SEPTIEMBRE</option>
				    <option value="OCTUBRE">OCTUBRE</option>
				    <option value="NOVIEMBRE">NOVIEMBRE</option>
				    <option value="DICIEMBRE">DICIEMBRE</option>
			    </select>
				</td>
				<td><input type="text" name="festdescribe[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
</div>
<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Tipo</th>
				<th>Descripcion</th>
			</tr>
<?
for ($i = 1; $i <= 4; $i++) 
{
?>			
			<tr>
				<td><select name="tipo_cult[]"><option value="" selected="selected">Seleccionar</option><option value="1">Patrimonio</option><option value="2">Manifestaciones culturales</option></select></td>
				<td><input type="text" name="descrip_cult[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
</div>	

<div class="twelve columns">g.- Recursos hidricos</div>
<div class="six columns">
	<table class="responsive">
		<tbody>
			<tr>
				<th>Principales fuentes de agua</th>
				<th>Uso actual</th>
				<th>Limitaciones</th>
			</tr>
<?
for ($i = 1; $i <= 5; $i++) 
{
?>				
			<tr>
				<td><input type="text" name="fuente[]"></td>
				<td><input type="text" name="uso[]"></td>
				<td><input type="text" name="limite[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
</div>
<div class="six columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
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
