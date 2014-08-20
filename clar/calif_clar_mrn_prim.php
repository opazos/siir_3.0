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
<dd class="active"><a href="#simple1">Registro de calificaciones</a></dd>
<dd><a href="#simple2">Calificaciones ingresadas</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_calif_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD&modo=PRIMERO_CLAR" onsubmit="return checkSubmit();">
<div class="two columns">Seleccionar PGRN</div>
<div class="four columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_ficha_mrn.cod_ficha_mrn_clar, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 WHERE clar_bd_ficha_mrn.cod_clar='$id'
	 ORDER BY org_ficha_organizacion.nombre ASC";
	 	$result=mysql_query($sql) or die (mysql_error());
	 	while($f1=mysql_fetch_array($result))
	 	{
		?>
		<option value="<? echo $f1['cod_ficha_mrn_clar'];?>"><? echo $f1['n_documento']." - ".$f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Seleccionar Jurado</div>
<div class="four columns">
	<select name="jurado" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_jurado_evento_clar.cod_jurado, 
		clar_bd_miembro.n_documento, 
		clar_bd_miembro.nombre, 
		clar_bd_miembro.paterno, 
		clar_bd_miembro.materno
		FROM clar_bd_miembro INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
		WHERE clar_bd_jurado_evento_clar.cod_clar='$id' AND
		clar_bd_jurado_evento_clar.calif_clar=1
		ORDER BY clar_bd_miembro.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_jurado'];?>"><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="twelve columns"><hr/></div>

<table>
<thead>
        <tr>
          <th>EJES</th>
          <th>CRITERIOS</th>
          <th>DESCRIPCION</th>
          <th>PUNTAJE MAXIMO</th>
        </tr>
</thead>       
        <tr>
          <td colspan="4" class="txt_titulo">EVALUACION CLAR</td>
        </tr>
        <tr>
          <td rowspan="4">Presentación del PGRN</td>
          <td rowspan="2">1.1.Mapa Cultural</td>
          <td>Se evidencia la incorporación de la iniciativa en el Mapa Cultural del PIT</td>
          <td align="center"><input name="p1" type="text" class="required number mini"/></td>
        </tr>
        <tr>
          <td>Participación en la construcción del Mapa Cultural del PIT</td>
          <td align="center"><input name="p2" type="text" class="required number mini"/></td>
        </tr>
        <tr>
          <td>1.2.- Claridad</td>
          <td>Evalúa la claridad en la presentación del PGRN, utilizando los medios que son accesibles o ingeniados por el grupo (fotos, mapas, productos, sociodrama, etc.) </td>
          <td align="center"><input name="p3" type="text" class="required number mini"/></td>
        </tr>
        <tr>
          <td>1.4.- Participacion de los socios, mujeres, jovenes y niños</td>
          <td>Evalua el nivel de participación de los socios de la organización, mujeres, jovenes y niños en la presentación del PGRN</td>
          <td align="center"><input name="p4" type="text" class="required number mini"/></td>
        </tr>
        </table>
        
      <div class="twelve columns"><br/></div>
      <div class="twelve columns">
	      <button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	      <a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
      </div>

	
</form>
</div>
</li>

<li id="simple2Tab">

<table>
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº Documento</th>
			<th>Nombre de la Organización</th>
			<th>Nombre del Jurado</th>
			<th>Puntaje</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$num=0;
	$sql="SELECT clar_calif_ficha_mrn_clar.total_puntaje, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre AS organizacion, 
	clar_bd_miembro.nombre, 
	clar_bd_miembro.paterno, 
	clar_bd_miembro.materno, 
	clar_calif_ficha_mrn_clar.cod_ficha_calif_mrn
FROM clar_bd_ficha_mrn INNER JOIN clar_calif_ficha_mrn_clar ON clar_bd_ficha_mrn.cod_ficha_mrn_clar = clar_calif_ficha_mrn_clar.cod_ficha_mrn_clar
	 INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_jurado_evento_clar.cod_jurado = clar_calif_ficha_mrn_clar.cod_jurado
	 INNER JOIN clar_bd_miembro ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE clar_bd_ficha_mrn.cod_clar='$id'
ORDER BY org_ficha_organizacion.nombre ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
		$num++
	
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['organizacion'];?></td>
			<td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
			<td><? echo number_format($fila['total_puntaje'],2);?></td>
			<td><a href="gestor_calif_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $fila['cod_ficha_calif_mrn'];?>&action=DELETE&modo=PRIMERO_CLAR" class="small alert button">Eliminar</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
	
</table>

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

<!-- Script de combo buscador -->
<!-- CARGAMOS EL JQUERY -->
<script type="text/javascript" src="../plugins/jquery.js"></script>

<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>

</body>
</html>
