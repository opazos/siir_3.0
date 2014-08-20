<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM sf_bd_grupo_ahorro WHERE cod_grupo='$id'";
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
<dd  class="active"><a href="">Registro de Grupos de Ahorro</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">

	<div class="twelve columns"><h6>I.- INFORMACION DEL GRUPO DE AHORRO</h6></div>
	
	<div class="twelve columns">Seleccionar Organizacion</div>
	<div class="twelve columns">
		<select name="org" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
			org_ficha_organizacion.n_documento, 
			org_ficha_organizacion.nombre
			FROM org_ficha_organizacion
			WHERE org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."' AND
			org_ficha_organizacion.cod_tipo_org<>6
			ORDER BY org_ficha_organizacion.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>" <? if ($row['cod_tipo_doc']==$f1['cod_tipo_doc'] and $row['n_documento']==$f1['n_documento']) echo "selected";?>><? echo $f1['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="twelve columns"><br/></div>
	
	<div class="two columns">Nombre del Grupo de Ahorro</div>
	<div class="ten columns"><input type="text" name="nombre" class="required nine" value="<? echo $row['nombre'];?>"></div>
	<div class="twelve columns"><h6>II.- USUARIAS MIEMBROS DEL GRUPO DE AHORRO (PARA EL CASO DE NUEVAS USUARIAS REGISTRAR AQUI)</h6></div>
	
	<div class="twelve columns"><br/></div>
	
	<table class="responsive">
		<thead>
			<tr>
				<th>DNI</th>
				<th class="ten">Nombres y Apellidos</th>
				<th>Fecha de nacimiento</th>
				<th><br/></th>
			</tr>
		</thead>
		
		<tbody>
		<?
		$sql="SELECT org_ficha_usuario.n_documento, 
		org_ficha_usuario.nombre, 
		org_ficha_usuario.paterno, 
		org_ficha_usuario.materno, 
		org_ficha_usuario.f_nacimiento
		FROM org_ficha_usuario
		WHERE org_ficha_usuario.cod_tipo_doc_org='".$row['cod_tipo_doc']."' AND
		org_ficha_usuario.n_documento_org='".$row['n_documento']."' AND
		org_ficha_usuario.sexo=0
		ORDER BY org_ficha_usuario.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
			<tr>
				<td><? echo $f2['n_documento'];?></td>
				<td><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></td>
				<td><? echo fecha_normal($f2['f_nacimiento']);?></td>
				<td><input type="checkbox" name=""></td>
			</tr>
		<?
		}
		?>	
		</tbody>
	</table>
	
	<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
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
