<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


$sql="SELECT * FROM clar_bd_evento_clar WHERE cod_clar='$id'";
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
<dd  class="active"><a href="">Paso 1 de 3.- Información del Evento</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">

<form name="form5" method="post" action="gestor_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>I.- Información del Evento</h6></div>

<div class="two columns">Nombre del evento</div>
<div class="four columns"><input type="text" name="nombre" class="required eleven" value="<? echo $row['nombre'];?>"><input type="hidden" name="codigo" value="<? echo $row['cod_clar'];?>"></div>
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
		<option value="<? echo $f5['cod'];?>" <? if ($row['tipo_evento']==$f5['cod']) echo "selected";?>><? echo $f5['descripcion'];?></option>
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
echo "<select  name='select1' id='select1' onChange='cargaContenido(this.id)' class='seven'>";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
if($row['cod_dep']==$registro[0])
echo "<option value=".$registro[0]." selected='selected'>".$registro[1]."</option>";
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?>	
</div>
<div class="two columns">Provincia</div>
<div class="four columns">
<select name="select2" id="select2" class="seven">
<option value="<? echo $row['cod_prov'];?>">
<? 
$prov=$row['cod_prov'];
$sql="SELECT * FROM sys_bd_provincia WHERE cod='$prov'";
$result=mysql_query($sql) or die (mysql_error());
$row_1=mysql_fetch_array($result);
echo $row_1['nombre'];
?>
</option>
</select>
</div>

<div class="two columns">Distrito</div>
<div class="four columns">
<select  name="select3" id="select3" class="seven">
<option value="<? echo $row['cod_dist'];?>">
<?
$distrito=$row['cod_dist'];
$sql="SELECT * FROM sys_bd_distrito WHERE cod='$distrito'";
$result=mysql_query($sql) or die (mysql_error());
$row_2=mysql_fetch_array($result);
echo $row_2['nombre'];
?>
</option>
</select>
</div>
<div class="two columns">Evento organizado por</div>
<div class="four columns">
<select name="tipo" class="required seven" id="tipo">
      <option value="" selected="selected">SELECCIONAR</option>
      <option value="1" <? if ($row['cod_tipo_clar']==1) echo "selected";?>>SIERRA SUR</option>
      <option value="2" <? if ($row['cod_tipo_clar']==2) echo "selected";?>>MUNICIPIO</option>
      <option value="3" <? if ($row['cod_tipo_clar']==3) echo "selected";?>>OTRO</option>
</select>
</div>
	
<div class="twelve columns"><br/></div>

<div class="two columns">Lugar</div>
<div class="ten columns"><input type="text" name="lugar" class="required ten" value="<? echo $row['lugar'];?>"></div>
<div class="twelve columns"><h6>Periodo de Evaluación de Campo</h6></div>
<div class="two columns">Desde</div>
<div class="four columns"><input name="f_inicio" type="date" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_campo1'];?>"></div>
<div class="two columns">Hasta</div>
<div class="four columns"><input name="f_termino" type="date" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_campo2'];?>"></div>
<div class="twelve columns"><h6>Periodo de Evaluación Pública</h6></div>
<div class="two columns">Fecha</div>
<div class="ten columns"><input name="f_evento" type="date" class="required date three" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_evento'];?>"></div>

<div class="twelve columns"><h6>II.- Objetivos</h6></div>
<div class="twelve columns">
	<textarea name="objetivo" rows="5"><? echo $row['objetivo'];?></textarea>
</div>

<div class="twelve columns"><h6>III.- Resultados</h6></div>
<div class="twelve columns">
	<textarea name="resultado" rows="5"><? echo $row['resultado'];?></textarea>
</div>

<div class="twelve columns"><h6>3.1.- Detalle del evento</h6></div>
<div class="three columns">Realizará concurso de Danzas?</div>
<div class="nine columns">
	<select name="concurso_1" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['concurso_danza']==1) echo "selected";?>>SI</option>
		<option value="0" <? if ($row['concurso_danza']==0) echo "selected";?>>NO</option>
	</select>
</div>

<div class="three columns">Realizará concurso de Gastronomia?</div>
<div class="nine columns">
	<select name="concurso_2" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['concurso_comida']==1) echo "selected";?>>SI</option>
		<option value="0" <? if ($row['concurso_comida']==0) echo "selected";?>>NO</option>
	</select>
</div>

<div class="three columns">Realizará concurso de Planes de Negocio?</div>
<div class="nine columns">
	<select name="concurso_3" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['concurso_pdn']==1) echo "selected";?>>SI</option>
		<option value="0" <? if ($row['concurso_pdn']==0) echo "selected";?>>NO</option>
	</select>
</div>

<div class="three columns">Realizará concurso de Mapas Territoriales?</div>
<div class="nine columns">
	<select name="concurso_4" class="three">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['concurso_mapa']==1) echo "selected";?>>SI</option>
		<option value="0" <? if ($row['concurso_mapa']==0) echo "selected";?>>NO</option>
	</select>
</div>


<div class="twelve columns"><br/></div>

<div class="twelve columns"><h6>IV.- <span class="has-tip tip-top noradius" data-width="210" title="Si no se realizarán concursos de Mapas Territoriales entonces dejar en blanco">Presupuesto para Concurso de Mapas Territoriales</span></h6></div>
<div class="two columns">Nº de PIT's a premiar por presentacion de Mapas</div>
<div class="four columns"><input type="text" name="n_pit" class="digits seven" value="<? echo $row['ganadores'];?>"></div>
<div class="two columns">Monto para premios de Mapas (S/.)</div>
<div class="four columns"><input type="text" name="premio" class="number seven" value="<? echo $row['premio'];?>"></div>

<?
$sql="SELECT * FROM clar_bd_ficha_contratante WHERE cod_clar='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row2=mysql_fetch_array($result);

$total=mysql_num_rows($result);

if ($total<>0)
{
?>
<div class="twelve columns"><h6>V.- <span class="has-tip tip-top noradius" data-width="210" title="Llenar solo en caso de que el evento sea realizado con algun Municipio u Organización">Entidad con la que se firmará contrato para la realización del CLAR (Opcional)</span></h6></div>

<div class="two columns">Nº de contrato</div>
<div class="four columns"><input type="text" name="n_contrato" class="digits required five" readonly="readonly" value="<? echo $row['n_contrato'];?>"> <input type="hidden" name="codigo_contratante" value="<? echo $row2['cod_ficha_contratante'];?>"></div>
<div class="two columns">Nº de ATF</div>
<div class="four columns"><input type="text" name="n_atf" class="digits required five" readonly="readonly" value="<? echo $row['n_atf'];?>"></div>

<div class="two columns">Entidad/organización</div>
<div class="ten columns">
	<select name="contratante" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion
		WHERE org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'
		ORDER BY org_ficha_organizacion.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>" <? if ($f1['cod_tipo_doc']==$row2['cod_tipo_doc'] and $f1['n_documento']==$row2['n_documento']) echo "selected";?>><? echo $f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Nº de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="seven" value="<? echo $row2['n_cuenta'];?>"></div>
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
		<option value="<? echo $f2['cod_ifi'];?>" <? if ($f2['cod_ifi']==$row2['cod_ifi']) echo "selected";?>><? echo $f2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><h6>Representante</h6></div>
<div class="two columns">Nº DNI</div>
<div class="four columns"><input type="text" name="dni" class="digits five" maxlength="8" value="<? echo $row2['dni_1'];?>"></div>
<div class="two columns">Cargo</div>
<div class="four columns"><input type="text" name="cargo" class="seven" value="<? echo $row2['cargo_1'];?>"></div>
<div class="two columns">Nombres completos</div>
<div class="ten columns"><input type="text" name="nombres" class="ten" value="<? echo $row2['representante_1'];?>"></div>
<?
}
?>
<div class="twelve columns"><h6>VI.- Afectación Presupuestal</h6></div>
<div class="two columns">Fuente FIDA %</div>
<div class="four columns"><input type="text" name="fte_fida" class="five required" value="<? echo $row['fte_fida'];?>"></div>
<div class="two columns">Fuente RO %</div>
<div class="four columns"><input type="text" name="fte_ro" class="five required"value="<? echo $row['fte_ro'];?>"></div>


<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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

<script type="text/javascript" src="../plugins/combo_dinamico/select_dependientes_3_niveles_poa.js"></script>  

<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>
</body>
</html>
