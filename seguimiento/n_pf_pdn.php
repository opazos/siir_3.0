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
	if ($modo==asistente)
	{
		echo "2 de 2.- Asistentes al evento";
	}
	else
	{
		echo "1 de 2.- Información del evento";
	}
	?>
	
</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">

<?
if ($modo==asistente)
{
?>
<? include("../plugins/buscar/buscador.html");?>
<form name="form5" class="custom" method="post" action="gestor_pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_ASISTENTE" onsubmit="return checkSubmit();">
<table class="responsive" id="lista">
	<tbody>
		<tr>
			<th>Nº</th>
			<th>DNI</th>
			<th class="ten">Nombres y apellidos completos</th>
			<th>Asistió?</th>
			<th><br/></th>
		</tr>
		<?
		$num=0;
		$sql="SELECT pit_bd_user_iniciativa.n_documento AS dni, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	ficha_participante_pf.n_documento
FROM pit_bd_ficha_pdn INNER JOIN ficha_pf ON pit_bd_ficha_pdn.cod_pdn = ficha_pf.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_pf.cod_tipo_iniciativa
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
	 LEFT OUTER JOIN ficha_participante_pf ON ficha_participante_pf.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND ficha_participante_pf.n_documento = pit_bd_user_iniciativa.n_documento AND ficha_participante_pf.cod_feria = ficha_pf.cod_feria
WHERE ficha_pf.cod_feria='$cod'
ORDER BY org_ficha_usuario.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
			$num++
		
		?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['dni'];?></td>
			<td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
			<td><input type="checkbox" name="campos[]" value="<? echo $f1['dni'];?>" <? if ($f1['n_documento']<>NULL) echo "checked";?>></td>
			<td>
			<? 
			if ($f1['n_documento']<>NULL) 
			{
			?>
			<a href="gestor_pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $f1['dni'];?>&action=DELETE_ASISTENTE" class="tiny alert button">Desvincular</a>
			<?
			}
			?>
			</td>
		</tr>
		<?
		}
		?>
	</tbody>
</table>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
</div>

</form>
<?
}
else
{
?>
<form name="form5" method="post" action="gestor_pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">
	
	<div class="twelve columns">
		<select name="pdn" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.nombre
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY org_ficha_organizacion.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
			?>
			<option value="<? echo $f1['cod_pdn'];?>"><? echo $f1['nombre']."-".$f1['denominacion'];?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="twelve columns"><br/></div>
	<div class="two columns">Fecha de inicio</div>
	<div class="four columns"><input type="date" name="f_inicio" class="required date six" placeholder="aaaa-mm-dd" maxlength="10"></div>
	<div class="two columns">Fecha de termino</div>
	<div class="four columns"><input type="date" name="f_termino" class="required date six" placeholder="aaaa-mm-dd" maxlength="10"></div>
	<div class="two columns">Nombre del evento</div>
	<div class="ten columns"><input type="text" name="evento" class="required"></div>
	<div class="twelve columns">Objetivo del evento</div>
	<div class="twelve columns"><textarea name="objetivo" rows="5"></textarea></div>
	<div class="two columns">Departamento</div>
	<div class="four columns"><input type="text" name="departamento" class="required five"></div>
	<div class="two columns">Provincia</div>
	<div class="four columns"><input type="text" name="provincia" class="required five"></div>
	<div class="two columns">Distrito</div>
	<div class="four columns"><input type="text" name="distrito" class="required five"></div>
	<div class="two columns">Calificacion del evento</div>
	<div class="four columns">
		<select name="calificacion" class="five required">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT * FROM sys_bd_califica";
			$result=mysql_query($sql) or die (mysql_error());
			while($f2=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f2['cod'];?>"><? echo $f2['descripcion'];?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="twelve columns"></div>
	<div class="five columns">Aporte Proyecto (S/.)</div>
	<div class="seven columns"><input type="text" name="aporte_pdss" class="required three number"></div>
	<div class="five columns">Aporte Organizacion (S/.)</div>
	<div class="seven columns"><input type="text" name="aporte_org" class="required three number"></div>
	<div class="five columns">Aporte Otro (S/.)</div>
	<div class="seven columns"><input type="text" name="aporte_otro" class="required three number"></div>
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
$n=0;
for($i=1;$i<=10;$i++)
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
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
<?
if ($modo==asistente)
{
?>
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>
<?
}
else
{
?>  
<script type="text/javascript" src="../plugins/jquery.js"></script>
<?
}
?>
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
