<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($modo==donacion)
{
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_evento_don
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
		
	$n_evento=$r1['n_evento_don']+1;	
}
else
{
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_epd
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
		
	$n_evento=$r1['n_epd']+1;		
}		


if ($modo==donacion)
{
	$donacion=1;
}
else
{
	$donacion=0;
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
<dd  class="active"><a href="">Generar demanda de evento</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">
	
	<div class="two columns">Nº de evento</div>
	<div class="four columns"><input type="text" name="n_evento" class="required digits five" readonly="readonly" value="<? echo $n_evento;?>">
	<input type="hidden" name="cod_numeracion" value="<? echo $r1['cod'];?>">
	<input type="hidden" name="donacion" value="<? echo $donacion;?>">
	</div>
	<div class="two columns">Fecha de evento</div>
	<div class="four columns"><input type="date" name="f_evento" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
	<div class="two columns">Nombre del evento</div>
	<div class="ten columns"><input type="text" name="nombre" class="required "></div>
	<div class="two columns">Solicitante</div>
	<div class="four columns">
	<select name="dni" class="required medium">
	<option value="" selected="selected">Seleccionar</option>
	<?
	$sql="SELECT sys_bd_personal.n_documento, 
	sys_bd_personal.nombre, 
	sys_bd_personal.apellido
	FROM sys_bd_personal
	WHERE sys_bd_personal.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_personal.cod_tipo_usuario='A'";
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
	?>
	<option value="<? echo $f1['n_documento'];?>"><? echo $f1['nombre']." ".$f1['apellido'];?></option>
	<?
	}
	?>
	</select>
	</div>
	<div class="two columns">Nº de participantes</div>
	<div class="four columns"><input type="text" name="n_participante" class="required digits five"></div>
	<div class="two columns">Tipo de evento</div>
	<div class="four columns">
		<select name="tipo_evento" class="required medium">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT epd_bd_tipo_evento.cod_tipo_evento, 
			epd_bd_tipo_evento.descripcion
			FROM epd_bd_tipo_evento";
			$result=mysql_query($sql) or die (mysql_error());
			while($f2=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f2['cod_tipo_evento'];?>"><? echo $f2['descripcion'];?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="two columns">Especificar (Si eligio otro)</div>
	<div class="four columns"><input type="text" name="otro" class="ten"></div>
	
	<div class="two columns">Usa vehiculo oficial</div>
	<div class="four columns"><select name="vehiculo" class="required medium"><option value="" selected="selected">Seleccionar</option><option value="1">Si</option><option value="0">No</option></select></div>
	<div class="two columns">Fecha de presentacion de la demanda</div>
	<div class="four columns"><input type="date" name="f_presentacion" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>
	<div class="twelve columns"><h6>Objetivos del evento</h6></div>
	<div class="twelve columns"><textarea name="objetivo" rows="5"></textarea></div>
	<div class="twelve columns"><h6>Resultados esperados</h6></div>
	<div class="twelve columns"><textarea name="resultado" rows="5"></textarea></div>	
	
	<div class="two columns">Departamento</div>
	<div class="ten columns">
		<?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select class='medium' name='select1' id='select1' onChange='cargaContenido(this.id)' >";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?>
	</div>
	<div class="two columns">Provincia</div>
	<div class="ten columns"><select disabled="disabled" name="select2" id="select2" class="medium" >
      <option value="0">Selecciona opci&oacute;n...</option>
    </select></div>
    <div class="two columns">Distrito</div>
    <div class="ten columns"><select disabled="disabled" name="select3" id="select3" class="medium" >
      <option value="0">Selecciona opci&oacute;n...</option>
    </select></div>
    <div class="two columns">Direccion</div>
    <div class="ten columns"><input type="text" name="direccion"></div>

    <div class="twelve columns"><h6>II.- INFORMACIÓN FINANCIERA DEL EVENTO</h6></div>
    <div class="two columns">Afectación Presupuestal</div>
    <div class="four columns">
	<select name="poa" class="medium">
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
    <div class="two columns">Fuente de financiamiento</div>
    <div class="four columns">
	<select name="financiamiento" class="required medium">
		<option value="">Seleccionar</option>
		<option value="1">FIDA</option>
		<option value="2">RECURSOS ORDINARIOS</option>
		<option value="3">FIDA + RO</option>
		<?
		if ($modo==donacion)
		{
		?>
		<option value="4" <? if ($modo==donacion) echo "selected";?>>DONACION FIDA</option>
		<?
		}
		?>
	</select>    	
    </div>

    <div class="twelve columns"><h6>2.1.- Este evento forma parte de un "Evento Mayor"? Si es así completar la información siguiente:</h6></div>
    <div class="six columns"><label>Seleccionar el tipo de iniciativa</label>
    <select name="tipo_codigo" class="large">
    	<option value="" selected="selected">Seleccionar</option>
    	<option value="1">EPD - EVENTOS DE PROMOCION Y DIFUSION</option>
        <option value="7">CLAR - COMITE LOCAL DE ASIGNACION DE RECURSOS - CLAR - INTERCON</option>
    </select>
    </div>
    <div class="three columns"><label>N. de evento</label>
    <input type="text" name="codigo_evento" class="digits nine">
    </div>
    <div class="three columns"><label>Oficina que organiza</label>
    <select name="olp" class="medium">
    	<option value="" selected="selected">Seleccionar</option>
    	<?php
    	$sql="SELECT * FROM sys_bd_dependencia";
    	$result=mysql_query($sql) or die (mysql_error());
    	while($f1=mysql_fetch_array($result))
    	{
    		echo "<option value='".$f1['cod_dependencia']."'>".$f1['nombre']."</option>";
    	}
    	?>
    </select>
    </div>




	
<div class="twelve columns"><hr/></div>	
<div class="twelve columns"><h6>2.2.- Presupuesto detallado del evento</h6></div>

<table class="responsive">
	<tbody>
		<tr>
			<th>Concepto</th>
			<th class="seven">Descripcion</th>
			<th>Monto (S/.)</th>
			<th>Se requiere</th>
		</tr>
<?
for ($i = 1; $i <= 30; $i++) 
{
?>				
		<tr>
			<td>
				<select name="concepto[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_tipo_gasto.cod_tipo_gasto, 
					sys_bd_tipo_gasto.descripcion
					FROM sys_bd_tipo_gasto";
					$result1=mysql_query($sql) or die (mysql_error());
					while($f4=mysql_fetch_array($result1))
					{
					?>
					<option value="<? echo $f4['cod_tipo_gasto'];?>"><? echo $f4['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="describe[]"></td>
			<td><input type="text" name="monto[]"></td>
			<td><select name="requerido[]"><option value="" selected="selected">Seleccionar</option><option value="1">Si</option><option value="0">No</option></select></td>
		</tr>
<?
}
?>		
	</tbody>
</table>



<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
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
