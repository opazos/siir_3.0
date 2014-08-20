<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM gm_ficha_evento WHERE cod_ficha_gm='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM gm_ficha_contratante WHERE cod_ficha_gm='$id' AND contratante='1'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

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

  <!-- COMBO DE 3 NIVELES -->
<script type="text/javascript" src="../plugins/combo_dinamico/select_dependientes_3_niveles_ex.js"></script>

<!-- Funcion que MODIFICA VALORES DE TEXTEBOX SEGUN RESPUESTA -->
<script> 
function esconde_campo()
{
var capa = document.getElementById( 'capa' );
var org = document.getElementById( 'entidad' );

/* Aplicamos la condicion*/  
if( org.options[org.selectedIndex].value == '0' )
{
	capa.style.display = 'block';
}
else
{
	capa.style.display = 'none';
}

}
</script>
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Nueva demanda de Gira de aprendizaje e intercambio de conocimientos</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_contrato_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="two columns">Nº de contrato</div>
<div class="ten columns">
<input type="hidden" name="codigo" value="<? echo $row['cod_ficha_gm'];?>">
<input type="text" name="n_contrato" class="two required digits" value="<? echo $row['n_contrato'];?>"></div>
<div class="two columns">Tema de la gira</div>
<div class="ten columns"><input type="text" name="tema" class="required" value="<? echo $row['tema'];?>"></div>
<div class="two columns">Inicio</div>
<div class="four columns"><input type="date" name="f_inicio" maxlength="10" placeholder="aaaa-mm-dd" class="required date seven" value="<? echo $row['f_inicio'];?>"></div>
<div class="two columns">Termino</div>
<div class="four columns"><input type="date" name="f_termino" maxlength="10" placeholder="aaaa-mm-dd" class="required date seven" value="<? echo $row['f_termino'];?>"></div>
<div class="two columns">Fecha de firma de contrato</div>
<div class="four columns"><input type="date" name="f_contrato" maxlength="10" placeholder="aaaa-mm-dd" class="required date seven" value="<? echo $row['f_propuesta'];?>"></div>
<div class="two columns">Nº de participantes</div>
<div class="four columns"><input type="text" name="participantes" class="required digits five" value="<? echo $row['participantes'];?>"></div>
<div class="twelve columns">Objetivos del evento</div>
<div class="twelve columns"><textarea name="objetivo" rows="3"><? echo $row['objetivo'];?></textarea></div>
<div class="twelve columns">Resultados esperados</div>
<div class="twelve columns"><textarea name="resultado" rows="3"><? echo $row['resultado'];?></textarea></div>

<div class="twelve columns"><h6>Afectación Presupuestal</h6></div>
<div class="twelve columns">
	
	<select name="poa" class="hyjack">
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
		<option value="<? echo $f3['cod'];?>" <? if ($f3['cod']==$row['cod_subactividad']) echo "selected";?>><? echo $f3['codigo']."-".$f3['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>

<div class="two columns">Fuente Fto FIDA (%)</div>
<div class="four columns"><input type="text" name="fida" class="required five" value="<? echo $row['cof_fida'];?>"></div>
<div class="two columns">Fuente Fto RO (%)</div>
<div class="four columns"><input type="text" name="ro" class="required five" value="<? echo $row['cof_ro'];?>"></div>


<div class="twelve columns"><h6>II.- Itinerario</h6></div>

<table class="responsive">
	<tbody>
		<tr>
			<th>Fecha</th>
			<th>Departamento/Provincia/Distrito</th>
			<th>Institucion</th>
			<th>Tematica</th>
			<th>Actividad</th>
		</tr>
<?
	$sql="SELECT * FROM gm_ficha_itinerario WHERE cod_ficha_gm='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	while($r1=mysql_fetch_array($result))
	{	
	$ca=$r1['cod_itinerario'];
?>	
		<tr>
			<td><input type="date" name="fechas[<? echo $ca;?>]" maxlength="10" placeholder="aaaa-mm-dd" class="date" value="<? echo $r1['f_itinerario'];?>"></td>
			<td><input type="text" name="lugars[<? echo $ca;?>]" placeholder="Departamento/Provincia/Distrito" value="<? echo $r1['departamento'];?>/<? echo $r1['provincia'];?>/<? echo $r1['distrito'];?>"></td>
			<td><input type="text" name="institucions[<? echo $ca;?>]" value="<? echo $r1['lugar'];?>"></td>
			<td><input type="text" name="tematicas[<? echo $ca;?>]" value="<? echo $r1['tematica'];?>"></td>
			<td><input type="text" name="actividadess[<? echo $ca;?>]" value="<? echo $r1['actividades'];?>"></td>
		</tr>		
<?
}
?>
<tr>
	<td colspan="5"><h6>Añadir nuevos registros</h6></td>
</tr>		
		
<?
for($i=1;$i<=5;$i++)
{
?>		
		<tr>
			<td><input type="date" name="fecha[]" maxlength="10" placeholder="aaaa-mm-dd" class="date"></td>
			<td><input type="text" name="lugar[]" placeholder="Departamento/Provincia/Distrito"></td>
			<td><input type="text" name="institucion[]"></td>
			<td><input type="text" name="tematica[]"></td>
			<td><input type="text" name="actividades[]"></td>
		</tr>
<?
}
?>		
	</tbody>
</table>
<div class="twelve columns"><h6>III.- Entidad contratante</h6></div>
<div class="twelve columns">Seleccionar una organizacion existente</div>
<div class="twelve columns">

<!-- codigo de entidad -->
<input type="hidden" name="codigo_entidad" value="<? echo $r2['cod_ficha_contratante'];?>">

	<select name="org" id="entidad" onchange="esconde_campo();" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
			<?
			if ($row1['cod_dependencia']==001)
			{
			$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
			org_ficha_organizacion.n_documento, 
			org_ficha_organizacion.nombre
			FROM org_ficha_organizacion
			ORDER BY org_ficha_organizacion.nombre ASC";
			}
			else
			{
			$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
			org_ficha_organizacion.n_documento, 
			org_ficha_organizacion.nombre
			FROM org_ficha_organizacion
			WHERE
			org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'
			ORDER BY org_ficha_organizacion.nombre ASC";
			}
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>" <? if ($f1['cod_tipo_doc']==$r2['cod_tipo_doc'] and $f1['n_documento']==$r2['n_documento']) echo "selected"?>><? echo $f1['n_documento']."-".$f1['nombre'];?></option>
			<?
			}
			?>
		</select>
</div>
<div class="twelve columns"><hr/></div>
<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="nine" value="<? echo $r2['n_cuenta'];?>"></div>

<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi" class="seven">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT sys_bd_ifi.cod_ifi, 
		sys_bd_ifi.descripcion
		FROM sys_bd_ifi
		ORDER BY sys_bd_ifi.descripcion ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f7=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f7['cod_ifi'];?>" <? if ($f7['cod_ifi']==$r2['cod_ifi']) echo "selected";?>><? echo $f7['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><h6>- Contacto 1 -</h6></div>
<div class="two columns">DNI</div>
<div class="four columns"><input type="text" name="dni1" class="five" value="<? echo $r2['dni_1'];?>"></div>
<div class="two columns">Cargo</div>
<div class="four columns"><input type="text" name="cargo1" class="five" value="<? echo $r2['cargo_1'];?>"></div>
<div class="two columns">Nombres completos</div>
<div class="ten columns"><input type="text" name="nombre1" value="<? echo $r2['representante_1'];?>"></div>

<div class="twelve columns"><h6>- Contacto 2 -</h6></div>
<div class="two columns">DNI</div>
<div class="four columns"><input type="text" name="dni2" class="five" value="<? echo $r2['dni_2'];?>"></div>
<div class="two columns">Cargo</div>
<div class="four columns"><input type="text" name="cargo2" class="five" value="<? echo $r2['cargo_2'];?>"></div>
<div class="two columns">Nombres completos</div>
<div class="ten columns"><input type="text" name="nombre2" value="<? echo $r2['representante_2'];?>"></div>

<div class="twelve columns"><h6>IV.- Presupuesto del evento</h6></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>Cofinanciador</th>
			<th>Concepto</th>
			<th>Detalle</th>
			<th>Unidad</th>
			<th>Precio Unitario</th>
			<th>Cantidad</th>
		</tr>
		
<?
$sql="SELECT * FROM gm_ficha_presupuesto WHERE cod_ficha_gm='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r3=mysql_fetch_array($result))
{
$cb=$r3['cod_presupuesto'];
?>		
		<tr>
			<td>
				<select name="cofs[<? echo $cb;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_ente_cofinanciador.cod_ente, 
					sys_bd_ente_cofinanciador.descripcion
					FROM sys_bd_ente_cofinanciador";
					$result1=mysql_query($sql) or die (mysql_error());
					while($f5=mysql_fetch_array($result1))
					{
					?>
					<option value="<? echo $f5['cod_ente'];?>" <? if ($f5['cod_ente']==$r3['cod_entidad']) echo "selected";?>><? echo $f5['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td>
				<select name="tipo_gastos[<? echo $cb;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_tipo_gasto.cod_tipo_gasto,sys_bd_tipo_gasto.descripcion FROM sys_bd_tipo_gasto";
					$result2=mysql_query($sql) or die (mysql_error());
					while($f6=mysql_fetch_array($result2))
					{
					?>
					<option value="<? echo $f6['cod_tipo_gasto'];?>" <? if ($f6['cod_tipo_gasto']==$r3['cod_tipo_gasto']) echo "selected";?>><? echo $f6['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="detalles[<? echo $cb;?>]" value="<? echo $r3['detalle'];?>"></td>
			<td><input type="text" name="unidads[<? echo $cb;?>]" value="<? echo $r3['unidad'];?>"></td>
			<td><input type="text" name="precios[<? echo $cb;?>]" value="<? echo $r3['costo_unitario'];?>"></td>
			<td><input type="text" name="cantidads[<? echo $cb;?>]" value="<? echo $r3['cantidad'];?>"></td>
		</tr>
<?
}
?>
<tr>
	<td colspan="6"><h6>Añadir nuevos registros</h6></td>
</tr>		
		
<?
for ($i=1;$i<=10;$i++)
{
?>		
		<tr>
			<td>
				<select name="cof[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_ente_cofinanciador.cod_ente, 
					sys_bd_ente_cofinanciador.descripcion
					FROM sys_bd_ente_cofinanciador";
					$result=mysql_query($sql) or die (mysql_error());
					while($f5=mysql_fetch_array($result))
					{
					?>
					<option value="<? echo $f5['cod_ente'];?>"><? echo $f5['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td>
				<select name="tipo_gasto[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_tipo_gasto.cod_tipo_gasto,sys_bd_tipo_gasto.descripcion FROM sys_bd_tipo_gasto";
					$result=mysql_query($sql) or die (mysql_error());
					while($f6=mysql_fetch_array($result))
					{
					?>
					<option value="<? echo $f6['cod_tipo_gasto'];?>"><? echo $f6['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="detalle[]"></td>
			<td><input type="text" name="unidad[]"></td>
			<td><input type="text" name="precio[]"></td>
			<td><input type="text" name="cantidad[]"></td>
		</tr>
<?
}
?>		
	</tbody>
</table>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Cancelar operacion</a>
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

</body>
</html>
