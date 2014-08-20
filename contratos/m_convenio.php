<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM gcac_bd_ficha_convenio WHERE cod_ficha='$id'";
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
<dd  class="active"><a href="">Modificar convenio</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_convenio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	
	<div class="two columns">Nº convenio</div>
	<div class="ten columns"><input name="n_convenio" type="text" class="two" value="<? echo $row['n_convenio'];?>" readonly="readonly">
	<input type="hidden" name="codigo" value="<? echo $row['cod_ficha'];?>"></div>
	<div class="two columns">Fecha de convenio</div>
	<div class="four columns"><input name="f_convenio" type="date" class="five" maxlength="10" placeholder="aaaa-mm-dd" value="<? echo $row['f_inicio'];?>"></div>
	<div class="two columns">Fecha de termino</div>
	<div class="four columns"><input name="f_termino" type="date" class="five" maxlength="10" placeholder="aaaa-mm-dd" value="<? echo $row['f_termino'];?>"></div>	
	<div class="twelve columns"><h6>Objetivo General</h6></div>
	<div class="twelve columns">
	<textarea id="elm1" name="objetivo_1" rows="10" cols="80" style="width: 100%"><? echo $row['objetivo_1'];?></textarea>
	</div>
	<div class="twelve columns"><h6>Objetivos Especificos</h6></div>
	<div class="twelve columns">
	<textarea id="elm2" name="objetivo_2" rows="10" cols="80" style="width: 100%"><? echo $row['objetivo_2'];?></textarea>		
	</div>
	<div class="twelve columns"><h6>II.- DATOS DE LA ENTIDAD CON LA QUE SE FIRMARA EL CONVENIO</h6></div>
	
	<div class="twelve columns"><h6>Usar organizacion existente</h6></div>
	<div class="twelve columns">
		<select name="org" id="entidad" onchange="esconde_campo();">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
			org_ficha_organizacion.n_documento, 
			org_ficha_organizacion.nombre
			FROM org_ficha_organizacion
			WHERE org_ficha_organizacion.cod_tipo_org=6 AND
			org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'
			ORDER BY org_ficha_organizacion.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>" <? if ($row['cod_tipo_doc']==$f1['cod_tipo_doc'] and $row['n_documento']==$f1['n_documento']) echo "selected";?>><? echo $f1['nombre'];?></option>
			<?
			}
			?>
			<option value="0">NINGUNA DE LAS ANTERIORES</option>
		</select>
	</div>
	
<div id="capa" style="display:none;">	
<div class="twelve columns"><br/></div>
<div class="twelve columns"><h6>Registrar nueva organizacion</h6></div>
	<div class="two columns">Tipo de Documento</div>
	<div class="four columns">
		<select name="tipo_doc" class="five" >
         <option value="" selected="selected">SELECCIONAR</option> 
<?
$sql="SELECT * FROM sys_bd_tipo_doc WHERE cod_tipo_doc='001' ORDER BY cod_tipo_doc DESC";
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
		  $sql="SELECT * FROM sys_bd_tipo_org WHERE cod_tipo_org=6 ORDER BY descripcion ASC";
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
</div>	
<div class="twelve columns"><h6>Información del Representante</h6></div>
<div class="two columns">Nº DNI</div>
<div class="four columns"><input type="text" name="dni1" class="required digits five" maxlength="8" value="<? echo $row['dni'];?>"></div>
<div class="two columns">Cargo</div>
<div class="four columns"><input type="text" name="cargo1" class="required five" value="<? echo $row['cargo'];?>"></div>
<div class="two columns">Nombres completos</div>
<div class="ten columns"><input type="text" name="nombre1" class="required" value="<? echo $row['representante'];?>"></div>



<div class="twelve columns"><br/></div>


	
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<a href="convenio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Cancelar operacion</a>
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
