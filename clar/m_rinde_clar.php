<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


$sql="SELECT clar_bd_rinde_clar.cod_rinde_clar, 
	clar_bd_rinde_clar.f_rendicion, 
	clar_bd_rinde_clar.resultado, 
	clar_bd_rinde_clar.problema, 
	clar_bd_rinde_clar.cod_dj, 
	clar_bd_rinde_clar.otro_monto, 
	clar_bd_rinde_clar.devolucion, 
	clar_bd_rinde_clar.cod_clar
FROM clar_bd_rinde_clar
WHERE clar_bd_rinde_clar.cod_clar='$id'";
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
<dd  class="active"><a href="">Rendición de Evento CLAR</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="two columns">Nº de Evento</div>
<div class="four columns">
<input type="hidden" name="codigo" value="<? echo $row['cod_rinde_clar'];?>">
	<select name="cod_clar">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_evento_clar.cod_clar, 
		clar_bd_evento_clar.f_evento
		FROM clar_bd_evento_clar
		WHERE clar_bd_evento_clar.estado=1 AND
		clar_bd_evento_clar.cod_dependencia='".$row1['cod_dependencia']."' AND
		clar_bd_evento_clar.n_contrato=0";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod_clar'];?>" <? if ($r1['cod_clar']==$row['cod_clar']) echo "selected";?>><? echo $r1['cod_clar']."-".periodo($r1['f_evento']);?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Fecha de rendicion</div>
<div class="four columns"><input type="date" name="f_rendicion" class="date seven required" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_rendicion'];?>"></div>	

<div class="two columns">Monto devuelto (S/.)</div>
<div class="four columns"><input type="text" name="monto_devuelto" class="number five" value="<? echo $row['devolucion'];?>"></div>

<div class="two columns">Nº Declaracion Jurada</div>
<div class="four columns">
<select name="dj">
	<option value="" selected="selected">Seleccionar</option>
	<?
	$sql="SELECT epd_dj_evento.cod_dj_evento, 
	epd_dj_evento.n_dj_evento, 
	epd_dj_evento.f_presentacion
	FROM epd_dj_evento
	WHERE epd_dj_evento.cod_dependencia='".$row1['cod_dependencia']."' AND
	epd_dj_evento.f_presentacion BETWEEN '$anio-01-01' AND '$anio-12-31'
	ORDER BY epd_dj_evento.n_dj_evento ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($r2=mysql_fetch_array($result))
	{
	?>
	<option value="<? echo $r2['cod_dj_evento'];?>" <? if ($r2['cod_dj_evento']==$row['cod_dj']) echo "selected";?>><? echo numeracion($r2['n_dj_evento'])."-".periodo($r2['f_presentacion']);?></option>
	<?
	}
	?>
</select>
</div>
<div class="two columns">Recibio fondos de la administración</div>
<div class="ten columns">
	<select name="recibio">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">Si</option>
		<option value="0">No</option>
	</select>
</div>

<div class="twelve columns"><h6>II.- Resultados Alcanzados</h6></div>

<div class="twelve columns">
	<textarea id="elm1" name="resultado" rows="15" cols="80" style="width: 100%"><? echo $row['resultado'];?></textarea>
</div>

<div class="twelve columns"><h6>III.- Problemas u Observaciones</h6></div>

<div class="twelve columns">
	<textarea id="elm2" name="problema" rows="15" cols="80" style="width: 100%"><? echo $row['problema'];?></textarea>
</div>
	
<div class="twelve columns"><h6>IV.- Entidades que cofinanciaron el Evento</h6></div>	

<table class="custom">
	<thead>
		<tr>
			<th class="seven">Nombre de la Entidad</th>
			<th>Tipo de entidad</th>
			<th class="three">Monto de aporte/valorización (S/.)</th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	for($i=1;$i<=5;$i++)
	{
	?>
		<tr>
			<td><input type="text" name="entidad[]" class="eleven"></td>
			<td>
				<select name="tipo_ente[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_ente_cofinanciador.cod_ente, 
					sys_bd_ente_cofinanciador.descripcion
					FROM sys_bd_ente_cofinanciador";
					$result1=mysql_query($sql) or die (mysql_error());
					while($r3=mysql_fetch_array($result1))
					{
					?>
					<option value="<? echo $r3['cod_ente'];?>"><? echo $r3['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="monto[]" class="eleven"></td>
			<td><br/></td>
		</tr>
	<?
	}
	?>	
	
	<?
	$sql="SELECT * FROM clar_bd_cofi_clar WHERE cod_rinde_clar='".$row['cod_rinde_clar']."'";
	$result=mysql_query($sql) or die (mysql_error());
	while($r4=mysql_fetch_array($result))
	{
		$cod=$r4['cod_cofi_clar'];
	?>
		<tr>
			<td><input type="text" name="entidads[<? echo $cod;?>]" class="eleven" value="<? echo $r4['entidad'];?>"></td>
			<td>
				<select name="tipo_entes[<? echo $cod;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_ente_cofinanciador.cod_ente, 
					sys_bd_ente_cofinanciador.descripcion
					FROM sys_bd_ente_cofinanciador";
					$result2=mysql_query($sql) or die (mysql_error());
					while($r5=mysql_fetch_array($result2))
					{
					?>
					<option value="<? echo $r5['cod_ente'];?>" <? if ($r5['cod_ente']==$r4['cod_ente']) echo "selected";?>><? echo $r5['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="montos[<? echo $cod;?>]" class="eleven" value="<? echo $r4['aporte'];?>"></td>
			<td><a href="gestor_rinde_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $r4['cod_cofi_clar'];?>&action=DELETE_COF" class="small alert button">Quitar</a></td>
		</tr>
	<?
	}
	?>
	
	</tbody>
</table>
	
<!-- -->	
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit_rinde" class="secondary button">Cancelar</a>
	
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
