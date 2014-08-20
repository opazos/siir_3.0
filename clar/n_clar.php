<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//buscamos el correlativo de eventos CLAR
$sql="SELECT sys_bd_numera_dependencia.n_contrato_clar, 
	sys_bd_numera_dependencia.n_atf_clar
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$n_contrato=$r2['n_contrato_clar']+1;
$n_atf=$r2['n_atf_clar']+1;

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
if ($modo==jurado)
{
	echo "Paso 2 de 3.- Asignación de Jurados para el evento CLAR";
}
elseif($modo==costo)
{
	echo "Paso 3 de 3.- Presupuesto a solicitar para el evento CLAR";
}
else
{
	echo "Paso 1 de 3.- Información del evento";
}
?>
</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<?
if($modo==jurado)
{
?>
<form name="form5" method="post" action="gestor_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_JURADO" onsubmit="return checkSubmit();">

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="n_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=costo" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
</div>
<div class="twelve columns"><hr/></div>

<table class="responsive">
	<thead>
		<tr>
			<th>Nombres completos</th>
			<th>Cargo</th>
			<th>Califica Campo</th>
			<th>Califica CLAR</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	
<?
$sql="SELECT clar_bd_jurado_evento_clar.cod_jurado, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	sys_bd_cargo_jurado_clar.descripcion AS cargo, 
	clar_bd_jurado_evento_clar.calif_campo, 
	clar_bd_jurado_evento_clar.calif_clar
FROM clar_bd_miembro INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
WHERE clar_bd_jurado_evento_clar.cod_clar='$id'
ORDER BY clar_bd_miembro.nombre ASC";
$result=mysql_query($sql) or die (mysql_error);
while($f3=mysql_fetch_array($result))
{
?>	
	<tr>
		<td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
		<td><? echo $f3['cargo'];?></td>
		<td><? if ($f3['calif_campo']==1) echo "SI"; else echo "NO";?></td>
		<td><? if ($f3['calif_clar']==1) echo "SI"; else echo "NO";?></td>
		<td>
			<a href="gestor_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f3['cod_jurado'];?>&action=DELETE_JURADO" class="small alert button">Eliminar</a>
		</td>
	</tr>
<?
}
?>	
	
	<?
	for($i=1;$i<=10;$i++)
	{
	?>
		<tr>
			<td>
				<select name="jurado[]" class="hyjack">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT clar_bd_miembro.n_documento, 
					clar_bd_miembro.nombre, 
					clar_bd_miembro.paterno, 
					clar_bd_miembro.materno
					FROM clar_bd_miembro
					ORDER BY clar_bd_miembro.nombre ASC";
					$result=mysql_query($sql) or die (mysql_error());
					while($f1=mysql_fetch_array($result))
					{
					?>
					<option value="<? echo $f1['n_documento'];?>"><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td>
				<select name="cargo[]" class="hyjack">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT sys_bd_cargo_jurado_clar.cod_cargo_jurado, 
					sys_bd_cargo_jurado_clar.descripcion
					FROM sys_bd_cargo_jurado_clar";
					$result=mysql_query($sql) or die (mysql_error());
					while($f2=mysql_fetch_array($result))
					{
					?>
					<option value="<? echo $f2['cod_cargo_jurado'];?>"><? echo $f2['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td>
				<select name="calif_campo[]">
					<option value="" selected="selected">Seleccionar</option>
					<option value="1">Si</option>
					<option value="0">No</option>
				</select>
			</td>
			<td>
				<select name="calif_clar[]">
					<option value="" selected="selected">Seleccionar</option>
					<option value="1">Si</option>
					<option value="0">No</option>
				</select>
			</td>
			<td>
				<br/>
			</td>
		</tr>
	<?
	}
	?>	
	</tbody>
</table>


</form>
<?
}
elseif($modo==costo)
{
?>
<form name="form5" class="custom" method="post" action="gestor_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PRESUPUESTO" onsubmit="return checkSubmit();">
<div class="two columns">Concepto</div>
<div class="four columns">
	<select name="concepto" class="five">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_tipo_gasto";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_tipo_gasto'];?>"><? echo $f1['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Requerido</div>
<div class="four columns">
	<select name="requerido" class="five">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">Si</option>
		<option value="0">No</option>
	</select>
</div>

<div class="two columns">Detalle</div>
<div class="ten columns"><input type="text" name="detalle" class="required nine"></div>
<div class="two columns">Unidad</div>
<div class="four columns"><input type="text" name="unidad" class="required five"></div>
<div class="two columns">Precio Unitario</div>
<div class="four columns"><input type="text" name="costo" class="required five"></div>
<div class="two columns">Cantidad</div>
<div class="four columns"><input type="text" name="cantidad" class="required five"></div>
<div class="two columns">Financia</div>
<div class="four columns">
	<select name="financia" class="five">
<option value="" selected="selected">Seleccionar</option>
 <?
 $sql="SELECT
sys_bd_ente_cofinanciador.cod_ente,
sys_bd_ente_cofinanciador.descripcion
FROM
sys_bd_ente_cofinanciador";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>
<option value="<? echo $f4['cod_ente'];?>"><? echo $f4['descripcion'];?></option>
<?
}
?>
	</select>
</div>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
</div>

<div class="twelve columns"><hr/></div>
</form>

<table>
	<thead>
		<tr>
			<th>Concepto</th>
			<th class="five">Detalle</th>
			<th>Unidad</th>
			<th>Costo Unitario (S/.)</th>
			<th>Cantidad</th>
			<th>Total (S/.)</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$sql="SELECT clar_bd_ficha_presupuesto.cod_presupuesto, 
	sys_bd_tipo_gasto.descripcion AS tipo_gasto, 
	clar_bd_ficha_presupuesto.detalle, 
	clar_bd_ficha_presupuesto.unidad, 
	clar_bd_ficha_presupuesto.cantidad, 
	clar_bd_ficha_presupuesto.costo_unitario, 
	clar_bd_ficha_presupuesto.costo_total
FROM sys_bd_tipo_gasto INNER JOIN clar_bd_ficha_presupuesto ON sys_bd_tipo_gasto.cod_tipo_gasto = clar_bd_ficha_presupuesto.cod_tipo_gasto
WHERE clar_bd_ficha_presupuesto.cod_clar='$id'
ORDER BY clar_bd_ficha_presupuesto.cod_tipo_gasto ASC";
	$result=mysql_query($sql) or die ();
	while($fila=mysql_fetch_array($result))
	{
	?>
		<tr>
			<td><? echo $fila['tipo_gasto'];?></td>
			<td><? echo $fila['detalle'];?></td>
			<td><? echo $fila['unidad'];?></td>
			<td><? echo number_format($fila['costo_unitario'],2);?></td>
			<td><? echo number_format($fila['cantidad']);?></td>
			<td><? echo number_format($fila['costo_total'],2);?></td>
			<td><a href="gestor_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $fila['cod_presupuesto'];?>&action=DELETE_PRESUPUESTO" class="small alert button">Eliminar</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
</table>
<?
}
else
{
?>
<form name="form5" method="post" action="gestor_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>I.- Información del Evento</h6></div>
<div class="two columns">Nombre del evento</div>
<div class="four columns"><input type="text" name="nombre" class="required eleven"></div>
<div class="two columns">Tipo de Evento</div>
<div class="four columns">
	<select name="tipo_clar" class="seven">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_tipo_clar";
		$result=mysql_query($sql) or die (mysql_error());
		while($f5=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f5['cod'];?>"><? echo $f5['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><br/></div>

<div class="two columns">Departamento</div>
<div class="four columns">
<?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select class='seven' name='select1' id='select1' onChange='cargaContenido(this.id)' >";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?>	
</div>
<div class="two columns">Provincia</div>
<div class="four columns">
<select disabled="disabled" name="select2" id="select2" class="seven" >
<option value="0">Selecciona opci&oacute;n...</option>
</select>
</div>

<div class="two columns">Distrito</div>
<div class="four columns">
<select disabled="disabled" name="select3" id="select3" class="seven" >
<option value="0">Selecciona opci&oacute;n...</option>
</select>
</div>
<div class="two columns">Evento organizado por</div>
<div class="four columns">
<select name="tipo" class="required seven" id="tipo">
      <option value="" selected="selected">SELECCIONAR</option>
      <option value="1">SIERRA SUR</option>
      <option value="2">MUNICIPIO</option>
      <option value="3">OTRO</option>
</select>
</div>
	
<div class="twelve columns"><br/></div>

<div class="two columns">Lugar</div>
<div class="ten columns"><input type="text" name="lugar" class="required ten"></div>
<div class="twelve columns"><h6>Periodo de Evaluación de Campo</h6></div>
<div class="two columns">Desde</div>
<div class="four columns"><input name="f_inicio" type="date" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">Hasta</div>
<div class="four columns"><input name="f_termino" type="date" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="twelve columns"><h6>Periodo de Evaluación Pública</h6></div>
<div class="two columns">Fecha</div>
<div class="ten columns"><input name="f_evento" type="date" class="required date three" placeholder="aaaa-mm-dd" maxlength="10"></div>

<div class="twelve columns"><h6>II.- Objetivos</h6></div>
<div class="twelve columns">
	<textarea name="objetivo" rows="5"></textarea>
</div>

<div class="twelve columns"><h6>III.- Resultados</h6></div>
<div class="twelve columns">
	<textarea name="resultado" rows="5"></textarea>
</div>

<div class="twelve columns"><h6>3.1.- Detalle del evento</h6></div>
<div class="three columns">Realizará concurso de Danzas?</div>
<div class="nine columns">
	<select name="concurso_1" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">SI</option>
		<option value="0">NO</option>
	</select>
</div>

<div class="three columns">Realizará concurso de Gastronomia?</div>
<div class="nine columns">
	<select name="concurso_2" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">SI</option>
		<option value="0">NO</option>
	</select>
</div>

<div class="three columns">Realizará concurso de Planes de Negocio?</div>
<div class="nine columns">
	<select name="concurso_3" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">SI</option>
		<option value="0">NO</option>
	</select>
</div>

<div class="three columns">Realizará concurso de Mapas Territoriales?</div>
<div class="nine columns">
	<select name="concurso_4" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1">SI</option>
		<option value="0">NO</option>
	</select>
</div>


<div class="twelve columns"><br/></div>




<div class="twelve columns"><h6>IV.- <span class="has-tip tip-top noradius" data-width="210" title="Si no se realizarán concursos de Mapas Territoriales entonces dejar en blanco">Presupuesto para Concurso de Mapas Territoriales</span></h6></div>
<div class="two columns">Nº de PIT's a premiar por presentacion de Mapas</div>
<div class="four columns"><input type="text" name="n_pit" class="digits seven"></div>
<div class="two columns">Monto para premios de Mapas (S/.)</div>
<div class="four columns"><input type="text" name="premio" class="number seven"></div>
<div class="twelve columns"><h6>V.- <span class="has-tip tip-top noradius" data-width="210" title="Llenar solo en caso de que el evento sea realizado con algun Municipio u Organización">Entidad con la que se firmará contrato para la realización del CLAR (Opcional)</span></h6></div>

<div class="two columns">Nº de contrato</div>
<div class="four columns"><input type="text" name="n_contrato" class="digits required five" readonly="readonly" value="<? echo $n_contrato;?>"></div>
<div class="two columns">Nº de ATF</div>
<div class="four columns"><input type="text" name="n_atf" class="digits required five" readonly="readonly" value="<? echo $n_atf;?>"></div>

<div class="two columns">Entidad/organización</div>
<div class="ten columns">
	<select name="contratante" class="hyjack">
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
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="seven"></div>
<div class="two columns">Banco</div>
<div class="four columns">
	<select name="ifi" class="seven">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi ORDER BY descripcion";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_ifi'];?>"><? echo $f2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><h6>Representante</h6></div>
<div class="two columns">Nº DNI</div>
<div class="four columns"><input type="text" name="dni" class="digits five" maxlength="8"></div>
<div class="two columns">Cargo</div>
<div class="four columns"><input type="text" name="cargo" class="seven"></div>
<div class="two columns">Nombres completos</div>
<div class="ten columns"><input type="text" name="nombres" class="ten"></div>

<div class="twelve columns"><h6>VI.- Afectación Presupuestal</h6></div>
<div class="two columns">Fuente FIDA %</div>
<div class="four columns"><input type="text" name="fte_fida" class="five required"></div>
<div class="two columns">Fuente RO %</div>
<div class="four columns"><input type="text" name="fte_ro" class="five required"></div>





<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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

<script type="text/javascript" src="../plugins/combo_dinamico/select_dependientes_3_niveles_poa.js"></script>  

<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>
</body>
</html>
