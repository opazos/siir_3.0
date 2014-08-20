<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
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

<!--contenedores -->
<dl class="tabs">
<dd class="active"><a href="#simple1">Registrar premiacion</a></dd>
<dd><a href="#simple2">Tabla de resultados</a></dd>
</dl>
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<? include("../plugins/buscar/buscador.html");?>
<form name="form5" method="post" action="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=PREM_CIF" onsubmit="return checkSubmit();">



<table id="lista">
	<thead>
	<tr>
		<th>Actividad</th>
		<th>Participante</th>
		<th>Puntaje obtenido</th>
		<th>Puesto ocupado</th>
		<th>Premio PDSS (S/.)</th>
		<th>Premio Otro (S/.)</th>
	</tr>	
	</thead>	
	<tbody>
<?
$sql="SELECT cif_bd_ficha_cif.cod_ficha_cif, 
	cif_bd_ficha_cif.puntaje, 
	cif_bd_ficha_cif.puesto, 
	cif_bd_ficha_cif.premio_pdss, 
	cif_bd_ficha_cif.premio_otro, 
	org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	sys_bd_actividad_mrn.descripcion, 
	sys_bd_actividad_mrn.unidad
FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_ficha_cif.n_documento
	 INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = cif_bd_ficha_cif.cod_actividad
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento AND org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento
WHERE cif_bd_ficha_cif.cod_concurso_cif='$cod'
ORDER BY org_ficha_usuario.nombre ASC, cif_bd_ficha_cif.cod_actividad ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
	$cad=$f2['cod_ficha_cif'];
?>	
		<tr>
			<td class="three"><? echo $f2['descripcion'];?></td>
			<td class="three"><? echo $f2['nombre']." ".$f2['paterno']." ".$f2['materno'];?></td>
			<td><input type="text" name="puntaje[<? echo $cad;?>]" class="number" value="<? echo $f2['puntaje'];?>"></td>
			<td><input type="text" name="puesto[<? echo $cad;?>]" class="number" value="<? echo $f2['puesto'];?>"></td>
			<td><input type="text" name="aporte_pdss[<? echo $cad;?>]" class="number" value="<? echo $f2['premio_pdss'];?>"></td>
			<td><input type="text" name="aporte_otro[<? echo $cad;?>]" class="number" value="<? echo $f2['premio_otro'];?>"></td>
		</tr>
<?
}
?>		
	</tbody>
</table>

			        <div class="twelve columns">
				        <button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
				        <a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=premia" class="secondary button">Cancelar</a>
			        </div>
		        </form>
		    </div>	        
	        
        </li>
        <li id="simple2Tab">
	        
	        <table class="responsive">
		        <tbody>
			        <tr>
				        <th>Nº</th>
				        <th>DNI</th>
				        <th>Nombres y apellidos</th>
				        <th>Actividad</th>
				        <th>Unidad</th>
				        <th>Puesto</th>
				        <th>Puntaje</th>
				        <th>Premio (S/.)</th>
				        <th></th>
			        </tr>
	<?
	$num=0;
	$sql="SELECT cif_bd_ficha_cif.cod_ficha_cif, 
	cif_bd_ficha_cif.puntaje, 
	cif_bd_ficha_cif.puesto, 
	cif_bd_ficha_cif.premio_pdss, 
	cif_bd_ficha_cif.premio_otro, 
	org_ficha_usuario.n_documento, 
	org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	sys_bd_actividad_mrn.descripcion, 
	sys_bd_actividad_mrn.unidad
FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND pit_bd_user_iniciativa.n_documento = cif_bd_ficha_cif.n_documento
	 INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = cif_bd_ficha_cif.cod_actividad
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento AND org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento
WHERE cif_bd_ficha_cif.puesto<>0 AND
cif_bd_ficha_cif.cod_concurso_cif='$cod'
ORDER BY cif_bd_ficha_cif.cod_actividad ASC, cif_bd_ficha_cif.puesto ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
$num++
	?>		        
			        <tr>
				        <td><? echo $num;?></td>
				        <td><? echo $f1['n_documento'];?></td>
				        <td><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></td>
				        <td><? echo $f1['descripcion'];?></td>
				        <td><? echo $f1['unidad'];?></td>
				        <td><? echo numeracion($f1['puesto']);?></td>
				        <td><? echo number_format($f1['puntaje'],2);?></td>
				        <td><? echo number_format($f1['premio_pdss']+$f1['premio_otro'],2);?></td>
				        <td><a href="" class="small alert button">Eliminar</a></td>
			        </tr>
<?
}
?>			        
		        </tbody>
	        </table>
	        
        </li>
      </ul>
<!- fin de contenedores -->


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
  
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>  



</body>
</html>
