<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Busco el numero de evento
$sql="SELECT epd_bd_demanda.cod_evento, 
	epd_bd_demanda.n_evento
FROM epd_bd_demanda
WHERE epd_bd_demanda.cod_evento='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

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
<dd  class="active"><a href="">Rendicion de evento</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=RINDE_EVENTO" onsubmit="return checkSubmit();" class="custom">
	
	<div class="two columns">Nº de evento a rendir</div>
	<div class="four columns"><input type="text" name="n_evento" class="digits required five" readonly="readonly" value="<? echo $r5['n_evento'];?>"></div>	
	<div class="two columns">Fecha de rendicion</div>
	<div class="four columns"><input type="date" name="f_rendicion" class="required date five" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>
	<div class="two columns">Dirigido a</div>
	<div class="ten columns">
		<select name="dni">
			<option value="" selected="selected">Seleccionar</option>
<?
$sql="SELECT * FROM sys_bd_personal WHERE cod_tipo_usuario='A' AND cod_dependencia='001'";	
$result=mysql_query($sql) or die (mysql_error());
while($r3=mysql_fetch_array($result))
{
?>
<option value="<? echo $r3['n_documento'];?>"><? echo $r3['nombre']." ".$r3['apellido'];?></option>
<?
}
?>

		</select>
	</div>
	
	<div class="twelve columns"><h6>Resultados alcanzados</h6></div>
	<div class="twelve columns"><textarea name="resultado" rows="5"></textarea></div>
	<div class="twelve columns"><h6>Problemas u observaciones</h6></div>
	<div class="twelve columns"><textarea name="problema" rows="5"></textarea></div>
	<div class="twelve columns"><h6>Participantes del evento</h6></div>
	<table class="responsive">
		<tbody>
			<tr>
				<th class="ten">Descripcion</th>
				<th class="one">Varones</th>
				<th class="one">Mujeres</th>
			</tr>
			<tr>
				<td>Autoridades Gubernamentales</td>
				<td><input type="text" name="p1" class="digits required"></td>
				<td><input type="text" name="p2" class="digits required"></td>
			</tr>
			<tr>
				<td>Representantes de Juntas Directivas</td>
				<td><input type="text" name="p3" class="digits required"></td>
				<td><input type="text" name="p4" class="digits required"></td>
			</tr>
			<tr>
				<td>Representantes del NEC PDSS</td>
				<td><input type="text" name="p5" class="digits required"></td>
				<td><input type="text" name="p6" class="digits required"></td>
			</tr>
			<tr>
				<td>Otros asistentes</td>
				<td><input type="text" name="p7" class="digits required"></td>
				<td><input type="text" name="p8" class="digits required"></td>	
			</tr>				
		</tbody>
	</table>
	
	<div class="twelve columns"><h6>Instituciones participantes en el evento</h6></div>
	
	<table class="responsive">
		<tbody>
			<tr>
				<th class="nine">Nombre de la Organización, entidad participante</th>
				<th>Tipo </th>
				<th>Monto de aporte (S/.)</th>
			</tr>
<?
for ($i=1; $i<=3; $i++)
{
?>			
			<tr>
				<td><input type="text" name="participante[]"></td>
				<td>
					<select name="tipo[]">
						<option value="" selected="selected">Seleccionar</option>
						<option value="1">MUNICIPIO</option>
						<option value="2">MINERA</option>
						<option value="0">OTRO</option>
					</select>
				</td>
				<td><input type="text" name="monto[]"></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
	
	
	
	<div class="twelve columns"><h6>II.- Ejecucion del presupuesto del evento</h6></div>
	<div class="two columns">Monto desembolsado por administración (S/.)</div>
	<div class="four columns"><input type="text" name="monto_desembolsado" class="number required five"></div>
	<div class="two columns">Monto devuelto segun Voucher</div>
	<div class="four columns"><input type="text" name="monto_devuelto" class="number required five"></div>
	
	<div class="twelve columns"><h6>Detalle de ejecucion</h6></div>
	<hr>
	
	<table class="responsive">
		<tbody>
			<tr>
				<th>Fecha</th>
				<th>Proveedor</th>
				<th>RUC</th>
				<th>Detalle</th>
				<th>Concepto</th>
				<th>Tipo doc.</th>
				<th>Nº</th>
				<th>Monto (S/.)</th>
			</tr>
<?
for($i=1;$i<=30;$i++)
{
?>			
			<tr>
			<td><input type="date" name="fecha[]" class="date" placeholder="aaaa-mm-dd" maxlength="10"></td>
			<td><input type="text" name="proveedor[]"></td>
			<td><input type="text" name="ruc[]"></td>
			<td><input type="text" name="detalle[]"></td>
			<td>
				<select name="concepto[]">
            <option value="" selected="selected">SELECCIONAR</option>
            <?
	            $sql="SELECT * FROM sys_bd_tipo_gasto";
	            $result=mysql_query($sql) or die (mysql_error());
	            while($r1=mysql_fetch_array($result))
	            {
	            ?>
            <option value="<? echo $r1['cod_tipo_gasto'];?>"><? echo $r1['descripcion'];?></option>
               <?
	            }
	            ?>
          </select>
			</td>
			<td>
			<select name="tipo_doc[]" class="five">
          <option value="" selected="selected">SELECCIONAR</option>
          <?
	          $sql="SELECT * FROM sys_bd_tipo_importe";
	          $result=mysql_query($sql) or die (mysql_error());
	          while($r2=mysql_fetch_array($result))
	          {
	          ?>
	          <option value="<? echo $r2['cod_tipo_importe'];?>"><? echo $r2['descripcion'];?></option>
	          <?
		         }
		          ?>

            </select>				
			</td>
			<td class="one"><input type="text" name="n_doc[]"></td>
			<td><input type="text" name="precio[]"></td>	
			</tr>
<?
}
?>				
		</tbody>
	</table>
<div class="two columns">Nº declaracion jurada (si la hubiera)</div>
<div class="ten columns">
	<select name="n_dj">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT epd_dj_evento.cod_dj_evento, 
		epd_dj_evento.n_dj_evento, 
		epd_dj_evento.f_presentacion
		FROM epd_dj_evento
		WHERE epd_dj_evento.cod_dependencia='".$row['cod_dependencia']."'
		ORDER BY epd_dj_evento.n_dj_evento ASC";
		$result=mysql_query($sql) or die(mysql_error());
		while($r3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r3['cod_dj_evento'];?>"><? echo numeracion($r3['n_dj_evento'])."-".periodo($r3['f_presentacion']);?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><hr/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="evento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=rinde" class="primary button">Cancelar operacion</a>
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
