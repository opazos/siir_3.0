<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- Buscamos los datos del evento
$sql="SELECT ml_pf.nombre, 
	ml_pf.n_contrato, 
	ml_pf.f_contrato, 
	org_ficha_organizacion.nombre AS org, 
	ml_pf.aporte_pdss, 
	ml_pf.aporte_org, 
	ml_pf.aporte_otro, 
	ml_pf.dia, 
	ml_pf.lugar, 
	ml_pf.f_evento
FROM org_ficha_organizacion INNER JOIN ml_pf ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
WHERE ml_pf.cod_evento='$id'";
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
<dd  class="active"><a href="">Liquidación de Contrato</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_contrato_pf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_LIQUIDA" onsubmit="return checkSubmit();">

<div class="two columns"><h6>Organizacion participante</h6></div>
<div class="ten columns"><h6><? echo $r1['org'];?></h6></div>

<div class="two columns"><h6>Nombre del evento ferial</h6></div>
<div class="ten columns"><h6><? echo $r1['nombre'];?></h6></div>

<div class="two columns">Fecha del evento</div>
<div class="four columns"><? echo fecha_normal($r1['f_evento']);?></div>
<div class="two columns">Fecha de contrato</div>
<div class="four columns"><? echo fecha_normal($r1['f_contrato']);?></div>

<div class="twelve columns"><hr/></div>

<div class="two columns">Nº de contrato</div>
<div class="four columns"><input type="text" name="n_contrato" class="required digits seven" readonly="readonly" value="<? echo $r1['n_contrato'];?>"></div>
<div class="two columns">Fecha de Liquidación</div>
<div class="four columns"><input type="date" name="f_liquidacion" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="twelve columns"><h6>II.- Resultados del evento</h6></div>
<div class="twelve columns"><textarea id="elm1" name="resultados" rows="5"  style="width: 100%"></textarea></div>

<div class="twelve columns"><h6>III.- Presupuesto Ejecutado</h6></div>

<table class="responsive">
	<thead>
		<tr>
			<th class="nine">Cofinanciamiento</th>
			<th>Presupuesto Aprobado</th>
			<th>Presupuesto Ejecutado</th>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td>Aporte Sierra Sur II</td>
			<td><? echo number_format($r1['aporte_pdss'],2);?></td>
			<td><input type="text" name="ejec_pdss" class="required number ten"></td>
		</tr>
		<tr>
			<td>Aporte Organización</td>
			<td><? echo number_format($r1['aporte_org'],2);?></td>
			<td><input type="text" name="ejec_org" class="required number ten"></td>
		</tr>	
		<tr>
			<td>Aporte Otros</td>
			<td><? echo number_format($r1['aporte_otro'],2);?></td>
			<td><input type="text" name="ejec_otro" class="required number ten"></td>
		</tr>			
	</tbody>
</table>

<div class="twelve columns"><h6>IV.- Logros alcanzados por la Organización</h6></div>
<div class="twelve columns"><h6>6.1.- Exhibición</h6></div>

<table class="responsive">
<thead>
	<tr>
		<th>Nº</th>
		<th class="four">Productos en exhibición</th>
		<th>Cantidad</th>
		<th>Unidad</th>
		<th class="four">Caracteristicas resaltantes</th>
	</tr>
</thead>

<tbody>
<?
for($i=1;$i<=10;$i++)
{
?>
	<tr>
		<td><? echo $i;?></td>
		<td><input type="text" name="producto[]"></td>
		<td><input type="text" name="cantidad[]" class="number"></td>
		<td><input type="text" name="unidad[]"></td>
		<td><input type="text" name="detalle[]"></td>
	</tr>
<?
}
?>	
</tbody>	
</table>

<div class="twelve columns"><h6>6.2.- Ventas</h6></div>

<table class="responsive">
<thead>
	<tr>
		<th>Nº</th>
		<th class="five">Productos en venta</th>
		<th>Cantidad</th>
		<th>Unidad</th>
		<th>Precio</th>
	</tr>
</thead>

<tbody>
<?
for($i=1;$i<=10;$i++)
{
?>
	<tr>
		<td><? echo $i;?></td>
		<td><input type="text" name="productov[]"></td>
		<td><input type="text" name="cantidadv[]" class="number"></td>
		<td><input type="text" name="unidadv[]"></td>
		<td><input type="text" name="preciov[]" class="number"></td>
	</tr>
<?
}
?>	
</tbody>
</table>

<div class="twelve columns"><h6>6.3.- Contactos comerciales</h6></div>
<table class="responsive">
<thead>
	<tr>
		<th>Nº</th>
		<th class="five">Nombre o Razon Social</th>
		<th>Mercado</th>
		<th>Acuerdos</th>
		<th>Producto</th>
	</tr>
</thead>

<tbody>
<?
	for($i=1;$i<=10;$i++)
	{
?>
	<tr>
		<td><? echo $i;?></td>
		<td><input type="text" name="empresa[]"></td>
		<td>	
		<select name="mercado[]">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT * FROM sys_bd_tipo_mercado";
			$result1=mysql_query($sql) or die (mysql_error());
			while($f3=mysql_fetch_array($result1))
			{
			?>
			<option value="<? echo $f3['cod'];?>"><? echo $f3['descripcion'];?></option>
			<?
			}
			?>
		</select>
		</td>
		<td><input type="text" name="acuerdos[]"></td>
		<td><input type="text" name="productos[]"></td>
	</tr>
<?
	}
?>	
</tbody>

</table>
<div class="twelve columns"><h6>6.4.- Participación en eventos de capacitación</h6></div>

<table class="responsive">
<thead>
	<tr>
		<th>Nº</th>
		<th class="five">Nombre del evento</th>
		<th>Tema del evento</th>
		<th>Nº de participantes</th>
	</tr>
</thead>

<tbody>
<?
for($i=1;$i<=5;$i++)
{
?>
	<tr>
		<td><? echo $i;?></td>
		<td class="five"><input type="text" name="evento[]"></td>
		<td class="five"><input type="text" name="tema[]"></td>
		<td><input type="text" name="n_participante[]"></td>
	</tr>
<?
}
?>	
</tbody>
</table>
<div class="twelve columns"><h6>6.5.- Participación en Concursos</h6></div>

<table class="responsive">
<thead>
	<tr>
		<th>Nº</th>
		<th class="ten">Concurso / Tema</th>
		<th>Calificación</th>
	</tr>
</thead>

<tbody>
<?
for($i=1;$i<=5;$i++)
{
?>
	<tr>
		<td><? echo $i;?></td>
		<td><input type="text" name="concurso[]"></td>
		<td><input type="text" name="resultado[]" class="number"></td>
	</tr>
<?
}
?>	
</tbody>
</table>

<div class="twelve columns"><h6>7.- Comentarios/Observaciones</h6></div>
<div class="twelve columns"><textarea id="elm2" name="observaciones" rows="5"  style="width: 100%"></textarea></div>


<div class="twelve columns"><br/></div>	

<div class="twelve columns">
	
	<button type="submit" class="primary button">Guardar cambios</button>
	<a href="contrato_pf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=liquida" class="secondary button">Cancelar</a>
	
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
