<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM epd_bd_demanda WHERE cod_evento='$id'";
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
<dd  class="active"><a href="">Modificar demanda de evento</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	
	<div class="two columns">Nº de evento</div>
	<div class="four columns"><input type="text" name="n_evento" class="required digits five"  value="<? echo $row['n_evento'];?>"><input type="hidden" name="codigo" value="<? echo $row['cod_evento'];?>"></div>
	<div class="two columns">Fecha de evento</div>
	<div class="four columns"><input type="date" name="f_evento" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_evento'];?>"></div>
	<div class="two columns">Nombre del evento</div>
	<div class="ten columns"><input type="text" name="nombre" class="required " value="<? echo $row['nombre'];?>"></div>
	<div class="two columns">Solicitante</div>
	<div class="four columns">
	<select name="dni" class="required medium">
	<option value="" selected="selected">Seleccionar</option>
	<?
	if ($row1['cod_dependencia']==001)
	{
	$sql="SELECT sys_bd_personal.n_documento, 
	sys_bd_personal.nombre, 
	sys_bd_personal.apellido
	FROM sys_bd_personal
	WHERE
	sys_bd_personal.cod_tipo_usuario='A'";
	}
	else
	{
	$sql="SELECT sys_bd_personal.n_documento, 
	sys_bd_personal.nombre, 
	sys_bd_personal.apellido
	FROM sys_bd_personal
	WHERE sys_bd_personal.cod_dependencia='".$row1['cod_dependencia']."' AND
	sys_bd_personal.cod_tipo_usuario='A'";
	}
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
	?>
	<option value="<? echo $f1['n_documento'];?>" <? if ($f1['n_documento']==$row['n_doc_solicitante']) echo "selected";?>><? echo $f1['nombre']." ".$f1['apellido'];?></option>
	<?
	}
	?>
	</select>
	</div>
	<div class="two columns">Nº de participantes</div>
	<div class="four columns"><input type="text" name="n_participante" class="required digits five" value="<? echo $row['participantes'];?>"></div>
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
			<option value="<? echo $f2['cod_tipo_evento'];?>" <? if ($f2['cod_tipo_evento']==$row['cod_tipo_evento']) echo "selected";?>><? echo $f2['descripcion'];?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="two columns">Especificar (Si eligio otro)</div>
	<div class="four columns"><input type="text" name="otro" class="ten" value="<? echo $row['especificar'];?>"></div>
	
	<div class="two columns">Usa vehiculo oficial</div>
	<div class="four columns"><select name="vehiculo" class="required medium"><option value="" selected="selected">Seleccionar</option><option value="1" <? if ($row['oficial']==1) echo "selected";?>>Si</option><option value="0" <? if ($row['oficial']==0) echo "selected";?>>No</option></select></div>
	<div class="two columns">Fecha de presentacion de la demanda</div>
	<div class="four columns"><input type="date" name="f_presentacion" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_presentacion'];?>"></div>
	<div class="twelve columns"><h6>Objetivos del evento</h6></div>
	<div class="twelve columns"><textarea name="objetivo" rows="5"><? echo $row['objetivo'];?></textarea></div>
	<div class="twelve columns"><h6>Resultados esperados</h6></div>
	<div class="twelve columns"><textarea name="resultado" rows="5"><? echo $row['resultado'];?></textarea></div>	
	
	<div class="two columns">Departamento</div>
	<div class="ten columns">
<?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select class='medium' name='select1' id='select1' onChange='cargaContenido(this.id)'>";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
	if($row['cod_dep']==$registro[0])
	echo "<option value=".$registro[0]." selected='selected'>".$registro[1]."</option>";
	
	
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?>
	</div>
	<div class="two columns">Provincia</div>
	<div class="ten columns">
		<select name="select2" id="select2" class="medium">
      <option value="<? echo $row['cod_prov'];?>">
<? 
$prov=$row['cod_prov'];
$sql="SELECT * FROM sys_bd_provincia WHERE cod='$prov'";
$result=mysql_query($sql) or die (mysql_error());
$row_1=mysql_fetch_array($result);
echo $row_1['nombre'];
?>      
      </option>
    </select>
	</div>
    <div class="two columns">Distrito</div>
    <div class="ten columns">
	       <select name="select3" id="select3" class="medium">
      <option value="<? echo $row['cod_dist'];?>">
 <?
$distrito=$row['cod_dist'];
$sql="SELECT * FROM sys_bd_distrito WHERE cod='$distrito'";
$result=mysql_query($sql) or die (mysql_error());
$row_2=mysql_fetch_array($result);
echo $row_2['nombre'];
?>     
      </option>
    </select>
	    
    </div>
    <div class="two columns">Direccion</div>
    <div class="ten columns"><input type="text" name="direccion" value="<? echo $row['direccion'];?>"></div>
    
    
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
		<option value="<? echo $f3['cod'];?>" <? if ($f3['cod']==$row['cod_poa']) echo "selected";?>><? echo $f3['codigo']."-".$f3['nombre'];?></option>
		<?
		}
		?>
	</select>
    </div>
	<div class="two columns"><h6>Fuente de Financiamiento</h6></div>
	<div class="four columns">
		<select name="financiamiento" class="required medium">
			<option value="">Seleccionar</option>
			<option value="1" <? if ($row['fte_fto']==1) echo "selected";?>>FIDA</option>
			<option value="2" <? if ($row['fte_fto']==2) echo "selected";?>>RECURSOS ORDINARIOS</option>
			<option value="3" <? if ($row['fte_fto']==3) echo "selected";?>>FIDA + RO</option>
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
    	<option value="1" <? if ($row['cod_tipo_iniciativa_evento']==1) echo "selected";?>>EPD - EVENTOS DE PROMOCION Y DIFUSION</option>
        <option value="7" <? if ($row['cod_tipo_iniciativa_evento']==7) echo "selected";?>>CLAR - COMITE LOCAL DE ASIGNACION DE RECURSOS - CLAR - INTERCON</option>
    </select>
    </div>
    <div class="three columns"><label>N. de evento</label>
    <input type="text" name="codigo_evento" class="digits nine" value="<? echo $row['codigo_evento'];?>">
    </div>
    <div class="three columns"><label>Oficina que organiza</label>
    <select name="olp" class="medium">
    	<option value="" selected="selected">Seleccionar</option>
    	<?php
    	$sql="SELECT * FROM sys_bd_dependencia";
    	$result=mysql_query($sql) or die (mysql_error());
    	while($f1=mysql_fetch_array($result))
    	{
    	?>
    	<option value="<? echo $f1['cod_dependencia'];?>" <? if ($f1['cod_dependencia']==$row['olp_evento']) echo "selected";?>><? echo $f1['nombre'];?></option>
    	<?
    	}
    	?>
    </select>
    </div>


	
<div class="twelve columns"><hr/></div>	
    
    <div class="twelve columns"><h6>II.- Presupuesto del evento</h6></div>

<table class="responsive">
	<tbody>
		<tr>
			<th>Concepto</th>
			<th class="seven">Descripcion</th>
			<th>Monto (S/.)</th>
			<th>Se requiere</th>
		</tr>
		
<?
$sql="SELECT * FROM epd_bd_presupuesto WHERE cod_evento='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
$id=$f5['cod_presupuesto'];
?>		
		<tr>
			<td>
				<select name="conceptos[<? echo $id;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_tipo_gasto.cod_tipo_gasto, 
					sys_bd_tipo_gasto.descripcion
					FROM sys_bd_tipo_gasto";
					$result1=mysql_query($sql) or die (mysql_error());
					while($f4=mysql_fetch_array($result1))
					{
					?>
					<option value="<? echo $f4['cod_tipo_gasto'];?>" <? if ($f4['cod_tipo_gasto']==$f5['cod_tipo_gasto']) echo "selected";?>><? echo $f4['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="describes[<? echo $id;?>]" value="<? echo $f5['descripcion'];?>"></td>
			<td><input type="text" name="montos[<? echo $id;?>]" value="<? echo $f5['monto'];?>"></td>
			<td><select name="requeridos[<? echo $id;?>]"><option value="" selected="selected">Seleccionar</option><option value="1" <? if ($f5['monto_solicitado']==1) echo "selected";?>>Si</option><option value="0" <? if ($f5['monto_solicitado']==0) echo "selected";?>>No</option></select></td>
		</tr>
<?
}
?>		
		
<tr>
	<td colspan="4"><h6>Añadir registros</h6></td>
</tr>		
		
		
<?
for ($i = 1; $i <= 20; $i++) 
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
					$result2=mysql_query($sql) or die (mysql_error());
					while($f6=mysql_fetch_array($result2))
					{
					?>
					<option value="<? echo $f6['cod_tipo_gasto'];?>"><? echo $f6['descripcion'];?></option>
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
	<a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Cancelar operacion</a>
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
