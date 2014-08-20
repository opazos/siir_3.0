<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_demanda_gm
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
$n_contrato=$r1['n_demanda_gm']+1;

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
<form name="form5" method="post" action="gestor_contrato_gm.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="two columns">Nº de contrato</div>
<div class="ten columns"><input type="text" name="n_contrato" class="two required digits" readonly="readonly" value="<? echo $n_contrato;?>"><input type="hidden" name="codigo_numera" value="<? echo $r1['cod'];?>"></div>
<div class="two columns">Tema de la gira</div>
<div class="ten columns"><input type="text" name="tema" class="required"></div>
<div class="two columns">Inicio</div>
<div class="four columns"><input type="date" name="f_inicio" maxlength="10" placeholder="aaaa-mm-dd" class="required date seven"></div>
<div class="two columns">Termino</div>
<div class="four columns"><input type="date" name="f_termino" maxlength="10" placeholder="aaaa-mm-dd" class="required date seven"></div>
<div class="two columns">Fecha de firma de contrato</div>
<div class="four columns"><input type="date" name="f_contrato" maxlength="10" placeholder="aaaa-mm-dd" class="required date seven"></div>
<div class="two columns">Nº de participantes</div>
<div class="four columns"><input type="text" name="participantes" class="required digits five"></div>
<div class="twelve columns">Objetivos del evento</div>
<div class="twelve columns"><textarea name="objetivo" rows="3"></textarea></div>
<div class="twelve columns">Resultados esperados</div>
<div class="twelve columns"><textarea name="resultado" rows="3"></textarea></div>






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
		<option value="<? echo $f3['cod'];?>"><? echo $f3['codigo']."-".$f3['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Fuente Fto FIDA (%)</div>
<div class="four columns"><input type="text" name="fida" class="required five"></div>
<div class="two columns">Fuente Fto RO (%)</div>
<div class="four columns"><input type="text" name="ro" class="required five"></div>


<div class="twelve columns"><h6>II.- Itinerario</h6></div>

<table class="responsive">
	<tbody>
		<tr>
			<th>Fecha</th>
			<th>Departamento/Provincia/Distrito</th>
			<th>Institucion</th>
			<th>Tematica</th>
			<th>Actividades</th>
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
	<select name="org" id="entidad" class="hyjack" onchange="esconde_campo();">
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
			<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>"><? echo $f1['nombre'];?></option>
			<?
			}
			?>
			<option value="0">NINGUNA DE LAS ANTERIORES</option>
	</select>
</div>
<div class="twelve columns"><hr/></div>

<div id="capa" style="display:none;">	

<div class="twelve columns"><h6>Registrar nueva organizacion</h6></div>
	<div class="two columns">Tipo de Documento</div>
	<div class="four columns">
		<select name="tipo_doc" class="five" >
         <option value="" selected="selected">SELECCIONAR</option> 
<?
$sql="SELECT * FROM sys_bd_tipo_doc ORDER BY cod_tipo_doc DESC";
$result=mysql_query($sql) or die (mysql_error());
while($r2=mysql_fetch_array($result))
{
?>
<option value="<? echo $r2['cod_tipo_doc'];?>"><? echo $r2['descripcion'];?></option>
<?
}
?>

          </select>
	</div>
	<div class="two columns">Nº de documento</div>
	<div class="four columns"><input type="text" name="n_documento" class="five"></div>
	<div class="two columns">Nombre</div>
	<div class="four columns"><input type="text" name="nombre" class="eleven"></div>
	<div class="two columns">Tipo de organizacion</div>
	<div class="four columns">
	<select name="tipo_org" >
          <option value="" selected="selected">SELECCIONAR</option>
          <?
		  $sql="SELECT * FROM sys_bd_tipo_org ORDER BY descripcion ASC";
		  $result=mysql_query($sql) or die (mysql_error());
		  while($r3=mysql_fetch_array($result))
		  {
		  ?>
          <option value="<? echo $r3['cod_tipo_org'];?>"><? echo $r3['descripcion'];?></option>
          <?
		  }
		  ?>
          </select></div>
          <hr/>
	<div class="two columns">Departamento</div>
	<div class="ten columns">
		<?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select  name='select1' id='select1' onChange='cargaContenido(this.id)'>";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?>
	</div>
	<div class="two columns">Provincia</div>
	<div class="ten columns">
	<select disabled="disabled" name="select2" id="select2">
            <option value="0">Selecciona opci&oacute;n...</option>
          </select></div>
    <div class="two columns">Distrito</div> 
    <div class="ten columns">
	    <select disabled="disabled" name="select3" id="select3">
            <option value="0">Selecciona opci&oacute;n...</option>
          </select>
    </div>
	<div class="two columns">Direccion</div>
	<div class="ten columns"><input type="text" name="direccion"></div>
	<div class="twelve columns"><hr/></div>
</div>	



<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="nine"></div>

<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi" class="nine">
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
		<option value="<? echo $f7['cod_ifi'];?>"><? echo $f7['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><h6>- Contacto 1 -</h6></div>
<div class="two columns">DNI</div>
<div class="four columns"><input type="text" name="dni1" class="five"></div>
<div class="two columns">Cargo</div>
<div class="four columns"><input type="text" name="cargo1" class="five"></div>
<div class="two columns">Nombres completos</div>
<div class="ten columns"><input type="text" name="nombre1"></div>

<div class="twelve columns"><h6>- Contacto 2 -</h6></div>
<div class="two columns">DNI</div>
<div class="four columns"><input type="text" name="dni2" class="five"></div>
<div class="two columns">Cargo</div>
<div class="four columns"><input type="text" name="cargo2" class="five"></div>
<div class="two columns">Nombres completos</div>
<div class="ten columns"><input type="text" name="nombre2"></div>

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
for ($i=1;$i<=20;$i++)
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
