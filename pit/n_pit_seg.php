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
<dd  class="active"><a href="">
	<?
	if ($modo==directiva)
	{
		echo "2 de 2.- Actualizacion de Junta Directiva";
	}
	else
	{
		echo "1 de 2.- Información del Plan de Inversión Territorial";
	}
	?>
	
</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<?
if ($modo==directiva)
{
$sql="SELECT * FROM pit_bd_pit_sd WHERE cod_pit='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
?>
<form id="form5" method="post" action="gestor_pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_DIR" onsubmit="return checkSubmit();">
<div class="two columns">Hubo cambios en la directiva?</div>
<div class="two columns">
	<select name="hc_dir" class="nine">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($r1['hc_dir']==1) echo "selected";?>>Si</option>
		<option value="0"<? if ($r1['hc_dir']==0) echo "selected";?>>No</option>
	</select>
</div>
<div class="two columns">Indicar el motivo de los cambios</div>
<div class="six columns">
	<textarea name="mc_dir"><? echo $r1['just_dir'];?></textarea>
</div>
<div class="twelve columns"><h6>II.- Registro de nuevos directivos</h6></div>

<table class="responsive">
<tbody>
	<tr>
		<th>DNI</th>
		<th>Nombres completos</th>
		<th>A. Paterno</th>
		<th>A. Materno</th>
		<th>Sexo</th>
		<th>Fec. Nac.</th>
		<th>Cargo</th>
		<th>Vigencia hasta</th>
	</tr>
<?
$num=0;
for($i=1;$i<=5;$i++)
{
$num++
?>	
	<tr>
		<td><input type="text" name="dni[]" class="digits" maxlength="8"></td>
		<td><input type="text" name="nombre[]" placeholder="Nombres" ></td>
		<td><input type="text" name="paterno[]" placeholder="A. Paterno"</td>
		<td><input type="text" name="materno[]" placeholder="A. Materno"></td>
		<td>
			<select name="sexo[]">
				<option value="" selected="selected">Seleccionar</option>
				<option value="1">M</option>
				<option value="0">F</option>
			</select>
		</td>
		<td><input type="date" name="f_nacimiento[]" class="date nine" placeholder="aaaa-mm-dd" maxlength="10"></td>
		<td>
			<select name="cargo[]">
				<option value="" selected="selected">Seleccionar</option>
				<?
				$sql="SELECT * FROM sys_bd_cargo_directivo ORDER BY cod_cargo";
				$result=mysql_query($sql) or die (mysql_error());
				while($f2=mysql_fetch_array($result))
				{
				?>
				<option value="<? echo $f2['cod_cargo'];?>"><? echo $f2['descripcion'];?></option>
				<?
				}
				?>
			</select>
		</td>
		<td class="one"><input type="date" name="f_vigencia[]" class="date nine" placeholder="aaaa-mm-dd" maxlength="10"></td>
	</tr>
<?
}
?>
</tbody>
</table>
<div class="twelve columns"><h6>III.- Actualizacion de cargos y vigencia</h6></div>
<table>
	<tr>
		<th>Nº</th>
		<th>DNI</th>
		<th class="five">Nombres y apellidos completos</th>
		<th class="four">Cargo</th>
		<th>Vigencia hasta</th>
		<th>Vigente</th>
		<th>No vigente</th>
	</tr>
<?
$nu=0;
$sql="SELECT org_ficha_directiva_taz.n_documento, 
	org_ficha_directiva_taz.nombre, 
	org_ficha_directiva_taz.paterno, 
	org_ficha_directiva_taz.materno, 
	org_ficha_directiva_taz.cod_cargo_directivo, 
	org_ficha_directiva_taz.vigente, 
	org_ficha_directiva_taz.f_termino
FROM org_ficha_directiva_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_directiva_taz.cod_tipo_doc_taz = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_directiva_taz.n_documento_taz = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.cod_pit='$cod'
ORDER BY org_ficha_directiva_taz.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
	$cad=$f3['n_documento'];
	$nu++
?>
	<tr>
		<td><? echo $nu;?></td>
		<td><? echo $f3['n_documento'];?></td>
		<td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
		<td>
			<select name="cargo[<? echo $cad;?>]">
				<option value="" selected="selected">Seleccionar</option>
				<?
				$sql="SELECT * FROM  sys_bd_cargo_directivo ORDER BY cod_cargo";
				$result1=mysql_query($sql) or die (mysql_error());
				while($f4=mysql_fetch_array($result1))
				{
				?>
				<option value="<? echo $f4['cod_cargo'];?>" <? if ($f4['cod_cargo']==$f3['cod_cargo_directivo']) echo "selected";?>><? echo $f4['descripcion'];?></option>
				<?
				}
				?>
			</select>
		</td>
		<td><? echo fecha_normal($f3['f_termino']);?></td>
		<td><input type="radio" name="vigente[<? echo $cad;?>]" value="1" <? if ($f3['vigente']==1) echo "checked";?>></td>
		<td><input type="radio" name="vigente[<? echo $cad;?>]" value="0" <? if ($f3['vigente']==0) echo "checked";?>></td>
	</tr>	
<?
}
?>
</table>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Terminar</a>
</div>

</form>
<?
}
else
{
?>
<form name="form5" method="post" action="gestor_pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">
	
	<div class="twelve columns">Seleccionar Plan de Inversion Territorial</div>
	<div class="twelve columns">
		<select name="pit" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT pit_bd_ficha_pit.cod_pit, 
			org_ficha_taz.nombre
			FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			WHERE pit_bd_ficha_pit.cod_estado_iniciativa=005 AND
			org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
			ORDER BY org_ficha_taz.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_pit'];?>"><? echo $f1['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="twelve columns"><br/></div>
	
	<div class="twelve columns"><h6>II.- Información de contrapartida de la organizacion (Solo si es el caso, en caso contrario dejar en blanco)</h6></div>
	<div class="two columns">Nº de voucher</div>
	<div class="four columns"><input type="text" name="n_voucher" class="five"></div>
	<div class="two columns">Monto depositado por la organizacion (S/.)</div>
	<div class="four columns"><input type="text" name="monto_org" class="five number"></div>
	<div class="twelve columns"><h6>III.- Informacion de Ejecucion Financiera</h6></div>
	<div class="three columns">Nº de CH/ o C/O con la que se realizo el deposito del NEC PDSS II</div>
	<div class="three columns"><input type="text" name="n_cheque" class="ten required"></div>
	<div class="two columns">Fecha de desembolso</div>
	<div class="four columns"><input type="date" name="f_desembolso" class="required date five" placeholder="aaaa-mm-dd" maxlength="10"></div>
	<div class="twelve columns"><hr/></div>
	<div class="five columns">Total ejecutado por Animador Territorial (S/.)</div>
	<div class="seven columns"><input type="text" name="total1" class="required two number"></div>	
	<div class="twelve columns"><br/></div>
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
		<a href="pit_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
	</div>
</form>
<?
}
?>
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
