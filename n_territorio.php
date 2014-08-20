<?
require("funciones/sesion.php");
include("funciones/funciones.php");
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
   <link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="stylesheets/foundation.css">
  <link rel="stylesheet" href="stylesheets/general_foundicons.css">
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="rtables/responsive-tables.css">
  

   
  
  <script src="javascripts/modernizr.foundation.js"></script>
  <script src="javascripts/btn_envia.js"></script>
  <script src="rtables/javascripts/jquery.min.js"></script>
  <script src="rtables/responsive-tables.js"></script>
  
  <!-- COMBO DE 3 NIVELES -->
<script type="text/javascript" src="plugins/combo_dinamico/select_dependientes_4_niveles.js"></script>
  



   
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->


        <div class="row">
        
        <div class="twelve columns">
<!-- contenido -->        
<!-- Contenedores -->
      <dl class="tabs">
        <dd <? if ($modo<>directiva) echo "class='active'";?>><a href="#simple1">Nuevo Territorio</a></dd>
      </dl>
<!-- Termino contenedores -->
	        
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<!-- Inicio del formulario -->
<form name="form5" id="form5" method="post" action="gestor_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD"  onsubmit="return checkSubmit();">
<div class="two columns">Tipo documento</div>
<div class="ten columns">
	<select name="tipo_doc" class="two required">
		<option value="" selected="selected">Seleccionar</option>
		<?
		 $sql="SELECT * FROM sys_bd_tipo_doc WHERE cod_tipo_doc<>'001' AND cod_tipo_doc<>'008'";
		 $result=mysql_query($sql) or die (mysql_error());
		 while($r1=mysql_fetch_array($result))
		 {
		?>
		<option value="<? echo $r1['cod_tipo_doc'];?>"><? echo $r1['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Nº documento</div>
<div class="two columns"><input type="text" name="n_documento" class="ten" maxlength="15" required="required"></div>
<div class="two columns">Tipo de organizacion</div>
<div class="six columns">
	<select name="tipo" class="six required">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_tipo_org WHERE cod_tipo_org<>6 AND cod_tipo_org<>8 AND cod_tipo_org<>10 AND cod_tipo_org<>13 ORDER BY descripcion ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r2['cod_tipo_org'];?>"><? echo $r2['descripcion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Nombre</div>
<div class="ten columns"><input type="text" name="organizacion" required="required"></div>
<div class="two columns">Fecha de inscripcion a RRPP</div>
<div class="ten columns"><input type="date" name="f_registro" class="three" placeholder="aaaa-mm-dd" maxlength="10"></div>

<div class="two columns">Departamento</div>
<div class="ten columns"><?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select  name='select1' id='select1' onChange='cargaContenido(this.id)' class='three'>";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?></div>
<div class="two columns">Provincia</div>
<div class="ten columns">
<select disabled="disabled" name="select2" id="select2" class="three">
<option value="0">Seleccionar</option>
</select>
</div>
<div class="two columns">Distrito</div>
<div class="ten columns">
<select disabled="disabled" name="select3" id="select3" class="three">
<option value="0">Seleccionar</option>
</select>
</div>

<div class="two columns">Centro Poblado</div>
<div class="ten columns">
	<select disabled="disabled" name="select4" id="select4" class="three">
		<option value="0">Seleccionar</option>
	</select>
</div>


<div class="two columns">Direccion/Sector</div>
<div class="ten columns"><input type="text" name="direccion"></div>

<div class="twelve columns"><h6>Junta directiva</h6></div>
<div class="twelve columns"><hr/></div>

<div class="one columns">DNI</div>
<div class="two columns">Nombres</div>
<div class="two columns">Apellidos</div>
<div class="one columns">Sexo</div>
<div class="two columns">Fecha de nacimiento</div>
<div class="two columns">Cargo</div>
<div class="two columns">Vigencia hasta</div>

<?
for ($i = 1; $i <= 10; $i++) 
{
?>
<div class="one columns"><input type="text" name="dni[]" maxlength="8" class="ten dni"></div>
<div class="two columns"><input type="text" name="nombre[]" class="ten"></div>
<div class="one columns"><input type="text" name="apellidop[]" class="ten" placeholder="Paterno"></div>
<div class="one columns"><input type="text" name="apellidom[]" class="ten" placeholder="Materno"></div>
<div class="one columns"><select name="sexo[]" class="eleven"><option value="" selected="selected">Seleccionar</option><option value="1">M</option><option value="0">F</option></select></div>
<div class="two columns"><input type="date" name="fecha[]" class="ten" placeholder="aaaa-mm-dd" maxlength="10"></div>
<div class="two columns">
<select name="cargo[]" class="ten">
<option value="" selected="selected">Seleccionar</option>
<?
$sql="SELECT * FROM sys_bd_cargo_directivo";
$result=mysql_query($sql) or die (mysql_error());
while($r3=mysql_fetch_array($result))
{
?>
<option value="<? echo $r3['cod_cargo'];?>"><? echo $r3['descripcion'];?></option>
<?
}
?>
</select>
</div>
<div class="two columns"><input type="date" name="vigencia[]" class="date required ten" placeholder="aaaa-mm-dd" maxlength="10"></div>
<?
}
?>



<div class="twelve columns"><hr/></div>
<div class="twelve columns">
<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
<a href="territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>

</div>
</form>
<!-- termino del formulario -->
</div>
</li>


</ul>       
<!-- fin del contenido -->
</div>
 </div>


  <!-- Footer -->
<? include("footer.php");?>


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
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>



  
  
  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>


  <!-- VALIDADOR DE FORMULARIOS -->
<script src="plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="plugins/validation/stylesheet.css" />
<script type="text/javascript" src="plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="plugins/validation/mktSignup.js"></script>



</body>
</html>
