<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT ficha_ag_oferente.n_documento, 
	ficha_ag_oferente.nombre, 
	ficha_ag_oferente.paterno, 
	ficha_ag_oferente.materno, 
	ficha_ag_oferente.f_nacimiento, 
	ficha_ag_oferente.sexo, 
	ficha_ag_oferente.ubigeo, 
	ficha_ag_oferente.direccion, 
	ficha_ag_oferente.cod_tipo_oferente, 
	ficha_ag_oferente.especialidad, 
	ficha_aag.cod_aag, 
	ficha_aag.cod_tipo_iniciativa, 
	ficha_aag.cod_iniciativa, 
	ficha_aag.f_inicio, 
	ficha_aag.f_termino, 
	ficha_aag.aporte_pdss, 
	ficha_aag.aporte_org, 
	ficha_aag.aporte_otro, 
	ficha_aag.resultado, 
	ficha_aag.cod_tipo_designacion, 
	ficha_aag.cod_calificacion, 
	ficha_aag.cod_estado_iniciativa
FROM ficha_aag INNER JOIN ficha_ag_oferente ON ficha_aag.cod_tipo_doc = ficha_ag_oferente.cod_tipo_doc AND ficha_aag.n_documento = ficha_ag_oferente.n_documento
WHERE ficha_aag.cod_aag='$id'";
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
<? echo $mensaje;?>
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Edicion de Apoyo a la Gestión</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_ag_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	

<div class="twelve columns"><h6>I.- Datos del Apoyo a la Gestión</h6></div>	

<div class="two columns">Nº DNI</div>
<div class="two columns">
<input type="text" name="dni" class="dni required ten digits" maxlength="8" value="<? echo $row['n_documento'];?>" readonly="readonly">
<input type="hidden" name="codigo" value="<? echo $row['cod_animador'];?>">
</div>
<div class="one columns">
<br/>
</div>
<div class="one columns"><br/></div>
<div class="two columns">Fecha de nacimiento</div>
<div class="four columns"><input type="date" name="f_nacimiento" class="required date six" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_nacimiento'];?>"></div>
<div class="two columns">Apellido Paterno</div>
<div class="four columns"><input type="text" name="paterno" class="required ten" value="<? echo $row['paterno'];?>"></div>
<div class="two columns">Apellido Materno</div>
<div class="four columns"><input type="text" name="materno" class="required ten" value="<? echo $row['materno'];?>"></div>
<div class="two columns">Nombres</div>
<div class="four columns"><input type="text" name="nombre" class="required ten" value="<? echo $row['nombre'];?>"></div>
<div class="two columns">Sexo</div>
<div class="four columns">
	<select name="sexo">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['sexo']==1) echo "selected";?>>M</option>
		<option value="0" <? if ($row['sexo']==0) echo "selected";?>>F</option>
	</select>
</div>
<div class="twelve columns"></div>
<div class="two columns">Direccion</div>
<div class="four columns"><input type="text" name="direccion" class="ten" value="<? echo $row['direccion'];?>"></div>
<div class="two columns">Ubigeo (6 digitos)</div>
<div class="four columns"><input type="text" name="ubigeo" class="required digits five" maxlength="6" value="<? echo $row['ubigeo'];?>"></div>
<div class="two columns">Tipo de Profesional</div>
<div class="four columns">
	<select name="tipo_oferente">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_tipo_oferente";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod'];?>" <? if ($row['cod_tipo_oferente']==$f2['cod']) echo "selected";?>><? echo $f2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Especialidad/Profesion</div>
<div class="four columns"><input type="text" name="especialidad" class="required ten" value="<? echo $row['especialidad'];?>"></div>




<div class="twelve columns"><h6>II.- Informacion del Apoyo a la Gestión</h6></div>
	<div class="twelve columns">Seleccionar Plan de Negocio</div>
	<div class="twelve columns">
	<input type="hidden" name="codigo" value="<? echo $row['cod_aag'];?>">
	
		<select name="pdn">
			<option value="" selected="selected">Seleccionar</option>
<?
$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.nombre
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'
ORDER BY org_ficha_organizacion.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
?>
<option value="<? echo $f1['cod_pdn'];?>" <? if ($f1['cod_pdn']==$row['cod_iniciativa'] and $row['cod_tipo_iniciativa']==4) echo "selected";?>><? echo $f1['nombre']."-".$f1['denominacion'];?></option>
<?
}
?>
		</select>
	</div>	
	
<div class="two columns">Fecha de inicio</div>	
<div class="four columns"><input type="date" name="f_inicio" class="required date six" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_inicio'];?>"></div>

<div class="two columns">Fecha de termino</div>	
<div class="four columns"><input type="date" name="f_termino" class="required date six" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_termino'];?>"></div>

<div class="two columns">Calificacion de desempeño</div>
<div class="four columns">
	<select name="calificacion">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_califica";
		$result=mysql_query($sql) or die (mysql_error());
		while($f4=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f4['cod'];?>" <? if ($f4['cod']==$row['cod_calificacion']) echo "selected";?>><? echo $f4['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Como se asigno al personal</div>
<div class="four columns">
	<select name="asignacion">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_tipo_designacion";
		$result=mysql_query($sql) or die (mysql_error());
		while($f3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f3['cod'];?>" <? if ($f3['cod']==$row['cod_tipo_designacion']) echo "selected";?>><? echo $f3['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns">Resultados del Apoyo a la Gestión</div>
<div class="twelve columns">
	<textarea name="resultado" rows="5"><? echo $row['resultado'];?></textarea>
</div>
<div class="twelve columns"><br/></div>
<div class="five columns">Monto pagado NEC PDSS II (S/.)</div>
<div class="seven columns"><input type="text" name="aporte_pdss" class="required number two" value="<? echo $row['aporte_pdss'];?>"></div>
<div class="five columns">Monto pagado Organizacion (S/.)</div>
<div class="seven columns"><input type="text" name="aporte_org" class="required number two" value="<? echo $row['aporte_org'];?>"></div>
<div class="five columns">Monto pagado Otros (S/.)</div>
<div class="seven columns"><input type="text" name="aporte_otro" class="required number two" value="<? echo $row['aporte_otro'];?>"></div>
<div class="five columns">Estado situacional del contrato</div>
<div class="seven columns">
	<select name="estado">
		<option value="" selected="selected">Seleccionar</option>
		<option value="005" <? if ($row['cod_estado_iniciativa']==005) echo "selected";?>>EN EJECUCION</option>
		<option value="004" <? if ($row['cod_estado_iniciativa']==004) echo "selected";?>>CONCLUIDO</option>
	</select>
</div>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="ag_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
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
