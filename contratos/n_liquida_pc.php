<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
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
<dd  class="active"><a href="">Liquidacion de contratos de Promocion Comercial</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_contrato_pc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=LIQUIDA" onsubmit="return checkSubmit();">

<div class="two columns">Nº de contrato a liquidar</div>
<div class="four columns">
	<select name="n_contrato">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT ml_promocion_c.cod_evento, 
		ml_promocion_c.n_contrato, 
		ml_promocion_c.f_contrato
		FROM org_ficha_organizacion INNER JOIN ml_promocion_c ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
		WHERE ml_promocion_c.cod_estado_iniciativa='005' AND
		org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_evento'];?>" <? if ($f1['cod_evento']==$id) echo "selected";?>><? echo numeracion($f1['n_contrato'])."-".periodo($f1['f_contrato']);?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Fecha de liquidacion</div>
<div class="four columns"><input type="date" name="f_liquidacion" class="date required five" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>
<div class="two columns">Nº de organizaciones participantes</div>
<div class="four columns"><input type="text" name="participantes" class="required digits five"></div>
<div class="two columns">Nº de publico asistente (estimado)</div>
<div class="four columns"><input type="text" name="publico" class="required digits five"></div>
<div class="two columns">Ingresos netos generados</div>
<div class="ten columns"><input type="text" name="ingreso" class="required number two"></div>
<div class="two columns">Monto ejecutado NEC PDSS II</div>
<div class="four columns"><input type="text" name="ejec_pdss" class="required number five"></div>
<div class="two columns">Monto ejecutado Organizacion</div>
<div class="four columns"><input type="text" name="ejec_org" class="required number five"></div>
</div>
<div class="twelve columns"><hr/></div>
<div class="twelve columns"><h6>Detalle del presupuesto del evento</h6></div>
<table class="responsive">
	
	<tbody>
		<tr>
			<th class="five">Concepto</th>
			<th>Unidad</th>
			<th>Costo unitario</th>
			<th>Cantidad</th>
		</tr>
<?
for ($i=1;$i<=20;$i++)
{
?>		
		<tr>
			<td><input type="text" name="concepto[]"></td>
			<td><input type="text" name="unidad[]"></td>
			<td><input type="text" name="costo[]"></td>
			<td><input type="text" name="cantidad[]"></td>
		</tr>
<?
}
?>		
	</tbody>
	
</table>


<div class="twelve columns"><hr/></div>


<div class="twelve columns"><h6>Resultados alcanzados</h6></div>
<div class="twelve columns"><textarea name="resultado" rows="5"></textarea></div>
<div class="twelve columns"><h6>Problemas u observaciones</h6></div>
<div class="twelve columns"><textarea name="problema" rows="5"></textarea></div>
<div class="twelve columns"><h6>Apoyo de otras entidades</h6></div>
<div class="two columns">Nombre de la entidad</div>
<div class="four columns"><input type="text" name="entidad" class="ten"></div>
<div class="two columns">Monto con el que apoyó (S/.)</div>
<div class="four columns"><input type="text" name="apoyo" class="number five"></div>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="contrato_pc.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_liquida" class="primary button">Cancelar operacion</a>
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
