<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


$sql="SELECT * FROM sf_bd_pago_poliza WHERE cod_pago='$id'";
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
<dd  class="active"><a href="">Solicitud de Desembolso</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_pago_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE" onsubmit="return checkSubmit();">


<div class="two columns">N de Solicitud</div>
<div class="four columns"><input type="text" name="n_solicitud" class="required digits five" readonly="readonly" value="<? echo $row['n_solicitud'];?>">
</div>
<div class="two columns">Fecha de solicitud</div>
<div class="four columns"><input type="date" name="f_solicitud" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_solicitud'];?>"></div>
<div class="two columns">N de ATF</div>
<div class="four columns"><input type="text" name="n_atf" class="required digits five" readonly="readonly" value="<? echo $row['n_atf'];?>"></div>
<div class="two columns">Afectacion Presupuestal</div>
<div class="four columns">
	
	<select name="poa" class="nine">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT sys_bd_subactividad_poa.cod, 
		sys_bd_subactividad_poa.codigo, 
		sys_bd_subactividad_poa.nombre, 
		sys_bd_subactividad_poa.relacion
		FROM sys_bd_subactividad_poa
		WHERE sys_bd_subactividad_poa.relacion=13 AND
		sys_bd_subactividad_poa.periodo='$anio'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod'];?>" <? if ($f1['cod']==$row['cod_poa']) echo "selected";?>><? echo $f1['codigo']."-".$f1['nombre'];?></option>
		<?
		}
		?>
		
	</select>
	
</div>

<div class="twelve columns"><strong>II.- DATOS DE LA ENTIDAD ASEGURADORA</strong></div>
<div class="two columns">Banco</div>
<div class="ten columns">
	
	<select name="ifi" class="nine">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_ifi ORDER BY descripcion ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_ifi'];?>" <? if ($f2['cod_ifi']==$row['cod_ifi']) echo "selected";?>><? echo $f2['descripcion'];?></option>
		<?
		}
		?>
	</select>
	
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">N° de cuenta</div>
<div class="four columns"><input type="text" name="n_cuenta" class="required seven" value="<? echo $row['n_cuenta'];?>"></div>
<div class="two columns">Entidad Aseguradora</div>
<div class="four columns">

	<select name="aseguradora" class="nine">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT * FROM sys_bd_aseguradora ORDER BY descripcion ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f3['cod'];?>" <? if ($f3['cod']==$row['cod_aseguradora']) echo "selected";?>><? echo $f3['descripcion'];?></option>
		<?
		}
		?>
	</select>



</div>


<div class="twelve columns"><hr/></div>

<table class="responsive" id='lista'>
<thead>
	<tr>
		<th>POLIZA</th>
		<th>DNI</th>
		<th class="twelve">NOMBRES Y APELLIDOS COMPLETOS</th>
		<th><br/></th>
	</tr>
</thead>

<tbody>

<?
$sql="SELECT DISTINCT org_ficha_usuario.n_documento,
	sf_bd_usuario_seguro.cod_usuario, 
	sf_bd_poliza.cod_poliza, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM sf_bd_poliza INNER JOIN sf_bd_usuario_seguro ON sf_bd_poliza.cod_poliza = sf_bd_usuario_seguro.cod_poliza
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = sf_bd_poliza.cod_tipo_doc AND org_ficha_usuario.n_documento = sf_bd_poliza.n_documento
WHERE sf_bd_usuario_seguro.cod_pago='$id'";
$result=mysql_query($sql) or die ();
while($f4=mysql_fetch_array($result))
{
?>
<tr>
	<td><? echo $f4['cod_poliza'];?></td>
	<td><? echo $f4['n_documento'];?></td>
	<td><? echo $f4['nombre']." ".$f4['paterno']." ".$f4['materno'];?></td>
	<td><a href="gestor_pago_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f4['cod_usuario'];?>&action=DESVINCULAR" class="small alert button" onclick="return confirm('Al desvincular este usuario, toda la información generada para este se eliminará. Desea continuar')">Desvincular</a></td>
</tr>
<?
}
?>


<?
	$num=0;
	$sql="SELECT DISTINCT org_ficha_usuario.n_documento,
	sf_bd_poliza.cod_poliza,  
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno
FROM org_ficha_usuario INNER JOIN sf_bd_poliza ON org_ficha_usuario.cod_tipo_doc = sf_bd_poliza.cod_tipo_doc AND org_ficha_usuario.n_documento = sf_bd_poliza.n_documento
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = org_ficha_usuario.n_documento_org
WHERE sf_bd_poliza.cod_estado_iniciativa='002' AND
org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
ORDER BY 
sf_bd_poliza.cod_poliza ASC,
org_ficha_usuario.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
	$num++
?>
	<tr>
		<td><? echo $fila['cod_poliza'];?></td>
		<td><? echo $fila['n_documento'];?></td>
		<td><? echo $fila['nombre']." ".$fila['paterno']." ".$fila['materno'];?></td>
		<td><input type="checkbox" name="campos[]" value="<? echo $fila['cod_poliza'];?>"></td>
	</tr>
<?
}
?>	
</tbody>

</table>

<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="pago_poliza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edita" class="secondary button">Finalizar</a>
</div>

	
</form>

<div class="twelve columns">
<p></p>
<? include("../plugins/buscar/buscador.html");?></div>

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
  
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>
</body>
</html>
