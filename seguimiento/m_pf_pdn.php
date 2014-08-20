<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM ficha_pf WHERE cod_feria='$id'";
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
1 de 2.- Información del evento
</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	
	<div class="twelve columns">
	<input type="hidden" name="codigo" value="<? echo $row['cod_feria'];?>">
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
	<div class="two columns">Nombre del evento</div>
	<div class="ten columns"><input type="text" name="evento" class="required" value="<? echo $row['denominacion'];?>"></div>
	<div class="twelve columns">Objetivo del evento</div>
	<div class="twelve columns"><textarea name="objetivo" rows="5"><? echo $row['objetivo'];?></textarea></div>
	<div class="two columns">Departamento</div>
	<div class="four columns"><input type="text" name="departamento" class="required five" value="<? echo $row['departamento'];?>"></div>
	<div class="two columns">Provincia</div>
	<div class="four columns"><input type="text" name="provincia" class="required five" value="<? echo $row['provincia'];?>"></div>
	<div class="two columns">Distrito</div>
	<div class="four columns"><input type="text" name="distrito" class="required five" value="<? echo $row['distrito'];?>"></div>
	<div class="two columns">Calificacion del evento</div>
	<div class="four columns">
		<select name="calificacion">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT * FROM sys_bd_califica";
			$result=mysql_query($sql) or die (mysql_error());
			while($f2=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f2['cod'];?>" <? if ($f2['cod']==$row['cod_calificacion']) echo "selected";?>><? echo $f2['descripcion'];?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="twelve columns"></div>
	<div class="five columns">Aporte Proyecto (S/.)</div>
	<div class="seven columns"><input type="text" name="aporte_pdss" class="required three number" value="<? echo $row['aporte_pdss'];?>"></div>
	<div class="five columns">Aporte Organizacion (S/.)</div>
	<div class="seven columns"><input type="text" name="aporte_org" class="required three number" value="<? echo $row['aporte_org'];?>"></div>
	<div class="five columns">Aporte Otro (S/.)</div>
	<div class="seven columns"><input type="text" name="aporte_otro" class="required three number" value="<? echo $row['aporte_otro'];?>"></div>
	<div class="twelve columns"><h6>II.- Ventas realizadas en el evento</h6></div>
	<table>
		<tr>
			<th>Nº</th>
			<th class="five">Producto comercializado</th>
			<th>Unidad</th>
			<th>Precio unitario</th>
			<th>Cantidad</th>
		</tr>
<?
$sql="SELECT * FROM ficha_ventas_pf WHERE cod_feria='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
$cad=$f4['cod_venta'];
?>		
		<tr>
			<td>-</td>
			<td><input type="text" name="productos[<? echo $cad;?>]" value="<? echo $f4['producto'];?>"></td>
			<td><input type="text" name="unidads[<? echo $cad;?>]" value="<? echo $f4['unidad'];?>"></td>
			<td><input type="text" name="precios[<? echo $cad;?>]" class="number" value="<? echo $f4['precio'];?>"></td>
			<td><input type="text" name="cantidads[<? echo $cad;?>]" class="number" value="<? echo $f4['cantidad'];?>"></td>
		</tr>
<?
}
?>

		
<?
$n=0;
for($i=1;$i<=5;$i++)
{
$n++
?>		
		<tr>
			<td><? echo $n;?></td>
			<td><input type="text" name="producto[]"></td>
			<td><input type="text" name="unidad[]"></td>
			<td><input type="text" name="precio[]" class="number"></td>
			<td><input type="text" name="cantidad[]" class="number"></td>
		</tr>
<?
}
?>		
	</table>
<div class="twelve columns"><h6>III.- Contactos Comerciales</h6></div>
<table>
	<tr>
		<th>Nº</th>
		<th class="four">Nombre del contacto</th>
		<th>Tipo de Mercado</th>
		<th>Acuerdos</th>
		<th>Producto a comercializar</th>
	</tr>
	
<?
$sql="SELECT * FROM ficha_contacto_pf WHERE cod_feria='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
	$cud=$f5['cod_contacto'];
?>	
	<tr>
		<td>-</td>
		<td><input type="text" name="contactos[<? echo $cud;?>]" value="<? echo $f5['nombre'];?>"></td>
		<td>
			<select name="mercados[<? echo $cud;?>]">
				<option value="" selected="selected">Seleccionar</option>
				<?
				$sql="SELECT * FROM sys_bd_tipo_mercado";
				$result1=mysql_query($sql) or die (mysql_error());
				while($f3=mysql_fetch_array($result1))
				{
				?>
				<option value="<? echo $f3['cod'];?>" <? if ($f3['cod']==$f5['cod_mercado']) echo "selected";?>><? echo $f3['descripcion'];?></option>
				<?
				}
				?>
			</select>
		</td>
		<td><input type="text" name="acuerdos[<? echo $cud;?>]" value="<? echo $f5['acuerdos'];?>"></td>
		<td><input type="text" name="producto_finals[<? echo $cud;?>]" value="<? echo $f5['producto'];?>"></td>
	</tr>
<?
}
?>
	
	
<?
$num=0;
for($i=1;$i<=5;$i++)
{
	$num++
?>	
	<tr>
		<td><? echo $num;?></td>
		<td><input type="text" name="contacto[]"></td>
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
		<td><input type="text" name="acuerdo[]"></td>
		<td><input type="text" name="producto_final[]"></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
