<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM gcac_bd_ruta WHERE cod_ruta='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

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
<dd  class="active"><a href="">
Edicion de contrato de Ruta de aprendizaje
</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_contrato_ruta.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	
	<div class="twelve columns"><h6>I.- Información del evento</h6></div>
	<div class="twelve columns">Organizacion a la que pertenece la persona que asistirá al evento</div>
	<div class="twelve columns">
		<select name="org">
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
			<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>" <? if ($f1['cod_tipo_doc']==$r1['cod_tipo_doc_org'] and $f1['n_documento']==$r1['n_documento_org']) echo "selected";?>><? echo $f1['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>

	<div class="twelve columns">Nombre del evento</div>
	<div class="twelve columns"><input type="text" name="evento" class="required" value="<? echo $r1['nombre'];?>"></div>

	<div class="two columns">Tipo de actividad</div>
	<div class="four columns">
		<select name="tipo_ruta" required="required">
			<option value="" selected="selected">Seleccionar</option>
			<option value="1" <? if ($r1['cod_tipo_ruta']==1) echo "selected";?>>Encuentro del conocimiento</option>
			<option value="2" <? if ($r1['cod_tipo_ruta']==2) echo "selected";?>>Capacitación del personal</option>
			<option value="3" <? if ($r1['cod_tipo_ruta']==3) echo "selected";?>>Capacitación de talentos locales</option>
			<option value="4" <? if ($r1['cod_tipo_ruta']==4) echo "selected";?>>Capacitación de oferentes técnicos</option>
			<option value="5" <? if ($r1['cod_tipo_ruta']==5) echo "selected";?>>Diplomado</option>
			<option value="6" <? if ($r1['cod_tipo_ruta']==6) echo "selected";?>>Otros</option>
		</select>
	</div>
	<div class="two columns">Especificar (Si eligio otro)</div>
	<div class="four columns"><input type="text" name="otro" class="nine" value="<? echo $r1['otro_ruta'];?>"></div>

	<div class="two columns">Fecha de inicio</div>
	<div class="four columns"><input type="date" name="f_inicio" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r1['f_inicio'];?>"></div>
	<div class="two columns">Fecha de termino</div>
	<div class="four columns"><input type="date" name="f_termino" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r1['f_termino'];?>"></div>

	<div class="two columns">Persona que asiste al evento</div>
	<div class="ten columns">
	<select name="rutero">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_usuario.cod_tipo_doc, 
		org_ficha_usuario.n_documento, 
		org_ficha_usuario.nombre, 
		org_ficha_usuario.paterno, 
		org_ficha_usuario.materno
		FROM org_ficha_organizacion INNER JOIN org_ficha_usuario ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
		INNER JOIN gcac_bd_ruta ON gcac_bd_ruta.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND gcac_bd_ruta.n_documento_org = org_ficha_organizacion.n_documento
		WHERE gcac_bd_ruta.cod_ruta='$id'
		ORDER BY org_ficha_usuario.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['n_documento'];?>" <? if($f1['n_documento']==$r1['n_documento']) echo "selected";?>><? echo $f1['n_documento']."-".$f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></option>
		<?
		}
		?>
	</select>
	</div>

	<div class="twelve columns">Objetivos del evento</div>
	<div class="twelve columns"><textarea name="objetivo"><? echo $r1['objetivo'];?></textarea></div>
	
	<div class="twelve columns"><h6>II.- Itinerario de la ruta de aprendizaje</h6></div>
	<table class="responsive">
		<tbody>
			<tr>
				<th class="nine">Lugar a visitar</th>
				<th>Llegada</th>
				<th>Salida</th>
			</tr>
	<?
	$sql="SELECT * FROM gcac_bd_itinerario_ruta WHERE cod_ruta='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	while($f6=mysql_fetch_array($result))
	{
		$cod=$f6['cod_itinerario'];
	
	?>
			<tr>
				<td><input type="text" name="lugars[<? echo $cod;?>]" value="<? echo $f6['lugar'];?>"></td>
				<td><input name="inicios[<? echo $cod;?>]" type="date" maxlength="10" placeholder="aaaa-mm-dd" class="date" value="<? echo $f6['f_inicio'];?>"></td>
				<td><input name="terminos[<? echo $cod;?>]" type="date" maxlength="10" placeholder="aaaa-mm-dd" class="date" value="<? echo $f6['f_termino'];?>"></td>
			</tr>	
	<?
	}
	?>
			
	<tr>
		<td colspan="3"><h6>Añadir nuevos registros</h6></td>
	</tr>		
			
			
	<?
	for($i=1;$i<=5;$i++)
	{
	?>		
			<tr>
				<td><input type="text" name="lugar[]"></td>
				<td><input name="inicio[]" type="date" maxlength="10" placeholder="aaaa-mm-dd" class="date"></td>
				<td><input name="termino[]" type="date" maxlength="10" placeholder="aaaa-mm-dd" class="date"></td>
			</tr>
	<?
	}
	?>		
		</tbody>
	</table>
	
	<div class="twelve columns"><h6>III.- Presupuesto y afectación</h6></div>

	<div class="two columns">Nº de contrato</div>
	<div class="four columns"><input type="text" name="n_contrato" class="required digits seven" readonly="readonly" value="<? echo $r1['n_contrato'];?>">
	
	<input type="hidden" name="codigo" value="<? echo $r1['cod_ruta'];?>">
	
	</div>
	<div class="two columns">Fecha de contrato</div>
	<div class="four columns"><input type="date" name="f_contrato" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r1['f_contrato'];?>"></div>
	<div class="two columns">Nº Solicitud</div>
	<div class="four columns"><input type="text" name="n_solicitud" class="required digits seven" readonly="readonly" value="<? echo $r1['n_solicitud'];?>"></div>
	<div class="two columns">Nº ATF</div>
	<div class="four columns"><input type="text" name="n_atf" class="required digits seven" readonly="readonly" value="<? echo $r1['n_atf'];?>"></div>
	<div class="twelve columns"><br/></div>
	<div class="two columns">Aporte Proyecto (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_pdss" class="required number seven" value="<? echo $r1['aporte_pdss'];?>"></div>
	<div class="two columns">Aporte Organización (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_org" class="required number seven" value="<? echo $r1['aporte_org'];?>"></div>	
	<div class="two columns">Aporte Otros (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_otro" class="required number seven" value="<? echo $r1['aporte_otro'];?>"></div>
	<div class="six columns"><br/></div>
	<div class="twelve columns"><h6>Banco y número de cuenta</h6></div>
	<div class="two columns">Número de cuenta</div>
	<div class="four columns"><input type="text" name="n_cuenta" class="required  ten" value="<? echo $r1['n_cuenta'];?>"></div>
	<div class="two columns">Banco</div>
	<div class="four columns">
		<select name="ifi" class="large">
			<option value="" selected="selected">Seleccionar</option>
			<?php
			$sql="SELECT * FROM sys_bd_ifi ORDER BY descripcion ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($r2=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $r2['cod_ifi'];?>" <? if ($r1['cod_ifi']==$r2['cod_ifi']) echo "selected";?>><? echo $r2['descripcion'];?></option>
			<?php
			}
			?>
		</select>
	</div>

	
	
	<div class="twelve columns"><br/></div>
	<div class="two columns">Afectación presupuestal</div>
	<div class="ten columns">
		<select name="poa">
			<option value="" selected="selected">Seleccionar</option>
			<?php
			$sql="SELECT sys_bd_subactividad_poa.cod, 
			sys_bd_subactividad_poa.codigo, 
			sys_bd_subactividad_poa.nombre
			FROM sys_bd_subactividad_poa
			WHERE sys_bd_subactividad_poa.periodo='$anio'
			ORDER BY sys_bd_subactividad_poa.codigo ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f2=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f2['cod'];?>" <? if($f2['cod']==$r1['cod_poa']) echo "selected";?>><? echo $f2['codigo']."-".$f2['nombre'];?></option>
			<?	
			}
			?>
		</select>
	</div>

	<div class="two columns">Fuente de Financiamiento</div>
	<div class="ten columns">
	<select name="fte_fto">
		<option value="" selected="selected">Seleccionar</option>
		<?php
			$sql="SELECT * FROM sys_bd_fuente_fto";
			$result=mysql_query($sql) or die (mysql_error());
			while($f3=mysql_fetch_array($result))
			{
		?>
			<option value="<? echo $f3['cod'];?>" <? if ($f3['cod']==$r1['cod_fte_fto']) echo "selected";?>><? echo $f3['descripcion'];?></option>
		<?php				
			}
		?>
	</select>
	</div>

	<div class="twelve columns"><br/></div>
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<a href="contrato_ruta.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Cancelar operacion</a>
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
