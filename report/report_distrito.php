<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

//1.- Datos del distrito
$sql="SELECT sys_bd_distrito.nombre, 
	sys_bd_distrito.latitud, 
	sys_bd_distrito.longitud, 
	sys_bd_distrito.altitud, 
	sys_bd_distrito.ubigeo, 
	sys_bd_distrito.nivel_pobreza, 
	sys_bd_distrito.poblacion, 
	sys_bd_distrito.objetivo, 
	sys_bd_provincia.nombre AS provincia, 
	sys_bd_departamento.nombre AS departamento
FROM sys_bd_provincia INNER JOIN sys_bd_distrito ON sys_bd_provincia.cod = sys_bd_distrito.relacion
	 INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = sys_bd_provincia.relacion
WHERE sys_bd_distrito.cod='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

if ($_POST['select3']==NULL)
{
	$latitud=-34.397;
	$longitud=150.644;
}
else
{
	$latitud=$r1['latitud'];
	$longitud=$r1['longitud'];
}

echo $_POST['select3'];

/* INICIATIVAS ATENDIDAS POR DISTRITO */
//PIT
$sql="SELECT pit_bd_ficha_pit.cod_pit
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
pit_bd_ficha_pit.n_animador<>0 AND
org_ficha_taz.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$total_pit=mysql_num_rows($result);

$sql="SELECT  SUM(pit_bd_ficha_pit.aporte_pdss) as aporte_pdss
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE pit_bd_ficha_pit.n_contrato<>0 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pit.cod_estado_iniciativa<>003 AND
org_ficha_taz.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//PGRN
$sql="SELECT pit_bd_ficha_mrn.cod_mrn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$total_mrn=mysql_num_rows($result);

$sql="SELECT SUM((pit_bd_ficha_mrn.cif_pdss+ 
	pit_bd_ficha_mrn.at_pdss+ 
	pit_bd_ficha_mrn.vg_pdss+ 
	pit_bd_ficha_mrn.ag_pdss)) AS aporte_pdss
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_mrn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$total_fam_mrn=mysql_num_rows($result);

//PDN
$sql="SELECT pit_bd_ficha_pdn.cod_pdn
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$total_pdn=mysql_num_rows($result);

$sql="SELECT SUM((pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss)) AS aporte_pdss
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$sql="SELECT DISTINCT pit_bd_user_iniciativa.n_documento
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$total_fam_pdn=mysql_num_rows($result);

//IDL
$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$total_idl=mysql_num_rows($result);

$sql="SELECT SUM(pit_bd_ficha_idl.familias) AS familias
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$sql="SELECT SUM(pit_bd_ficha_idl.aporte_pdss) AS aporte_pdss
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
WHERE pit_bd_ficha_idl.cod_estado_iniciativa<>000 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>001 AND
pit_bd_ficha_idl.cod_estado_iniciativa<>003 AND
org_ficha_organizacion.cod_dist='".$_POST['select3']."'";
$result=mysql_query($sql) or die (mysql_error());
$r6=mysql_fetch_array($result);

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
   
  <link href="../stylesheets/printer.css" rel="stylesheet" type="text/css" media="print">
  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="../stylesheets/app.css">
  <link rel="stylesheet" href="../rtables/responsive-tables.css">
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 92%;
      }
    </style>
    
    <style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
  @page { size: A4 landscape; }
  </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
var map;
function initialize() {
  var mapOptions = {
    zoom: 16,
    center: new google.maps.LatLng(-<? echo $latitud;?>, -<? echo $longitud;?>),
    mapTypeId: google.maps.MapTypeId.HYBRID
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>  
    
  <!-- COMBO DE 3 NIVELES -->
  <script type="text/javascript" src="../plugins/combo_dinamico/select_dependientes_3_niveles_ex.js"></script>
</head>
<body>

<div class="twelve columns oculto">
	<div class="panel">
		<div class="row">       

<!-- aca pego el contenido --> 
<div class="twelve columns"><h6>INICIATIVAS ATENDIDAS A NIVEL DISTRITAL</h6></div>
<form name="form5" method="post" action="report_distrito.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">
<div class="three columns">Seleccionar Departamento</div>
<div class="nine columns">
<?
$consulta=mysql_query("SELECT cod,nombre FROM sys_bd_departamento");
// Voy imprimiendo el primer select compuesto por los paises
echo "<select  name='select1' id='select1' onChange='cargaContenido(this.id)' class='three'>";
echo "<option value='0' selected='selected'>Seleccionar</option>";
while($registro=mysql_fetch_row($consulta))
{
echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
}
echo "</select>";

?>
</div>

<div class="three columns">Seleccionar Provincia</div>
<div class="nine columns">
<select disabled="disabled" name="select2" id="select2" class="three">
<option value="0">Seleccionar</option>
</select>
</div>

<div class="three columns">Seleccionar Distrito</div>
<div class="nine columns">
<select disabled="disabled" name="select3" id="select3" class="three">
<option value="0">Seleccionar</option>
</select>
</div>	

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="primary button">Generar Reporte</button>
	<button type="button" class="success button" onClick="window.print()">Imprimir</button>
	<a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button">Finalizar</a>
</div>	        
</form>

<div class="twelve columns"><hr/></div>
<!-- fin del contenido -->
	    	        
		</div>
	</div>
</div>

<!-- capa del contenido -->
<div class="nine columns">
	<div class="panel">
		<div class="row">
		
		<div class="twelve columns"><h5>REPORTE DE INICIATIVAS ATENDIDAS PARA EL DISTRITO DE <? echo $r1['nombre'];?>, al <? echo traducefecha($fecha_hoy);?></h5></div>
		
		<div class="twelve columns"><h6>I.- Datos del Distrito</h6></div>
		<div class="three columns">Departamento</div>
		<div class="three columns"><? echo $r1['departamento'];?></div>
		<div class="three columns">Provincia</div>
		<div class="three columns"><? echo $r1['provincia'];?></div>
		<div class="twelve columns"><br/></div>
		<div class="three columns">Distrito</div>
		<div class="nine columns"><? echo $r1['nombre'];?></div>
		<div class="twelve columns"><br/></div>
		<div class="three columns">Altitud</div>
		<div class="three columns"><? echo number_format($r1['altitud'],2);?> msnm.</div>
		<div class="three columns">Nivel de pobreza</div>
		<div class="three columns">Quintil <? echo $r1['nivel_pobreza'];?></div>
		<div class="twelve columns"><br/></div>
		<div class="three columns">Población aprox (2008)</div>
		<div class="three columns"><? echo number_format($r1['poblacion']);?> habitantes</div>
		<div class="three columns">Población Objetivo</div>
		<div class="three columns"><? echo number_format($r1['objetivo']);?> familias</div>
		<div class="twelve columns"><h6>II.- Iniciativas atendidas en el distrito</h6></div>
		
		<table>
			<thead>
				<tr>
					<th class="three">Tipo de Iniciativa</th>
					<th class="three">Nº de Iniciativas Aprobadas</th>
					<th class="three">Monto Aprobado (S/.)</th>
					<th class="three">Nº de Beneficiarios</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>PIT con Animadores Territoriales</td>
					<td><? echo number_format($total_pit);?></td>
					<td><? echo number_format($r2['aporte_pdss'],2);?></td>
					<td><? echo number_format($total_fam_mrn);?></td>
				</tr>
				<tr>
					<td>Plan de Gestion de Recursos Naturales</td>
					<td><? echo number_format($total_mrn);?></td>
					<td><? echo number_format($r3['aporte_pdss'],2);?></td>
					<td><? echo number_format($total_fam_mrn);?></td>
				</tr>	
				<tr>
					<td>Plan de Negocios</td>
					<td><? echo number_format($total_pdn);?></td>
					<td><? echo number_format($r4['aporte_pdss'],2);?></td>
					<td><? echo number_format($total_fam_pdn);?></td>
				</tr>	
				<tr>
					<td>Inversion de Desarrollo Local</td>
					<td><? echo number_format($total_idl);?></td>
					<td><? echo number_format($r6['aporte_pdss'],2);?></td>
					<td><? echo number_format($r5[familias]);?> (Directos e indirectos)</td>
				</tr>	
			</tbody>
			
			<tfoot>
				<tr>
					<td>TOTAL</td>
					<td><? echo number_format($total_pit+$total_mrn+$total_pdn+$total_idl);?></td>
					<td><? echo number_format($r2['aporte_pdss']+$r3['aporte_pdss']+$r4['aporte_pdss']+$r6['aporte_pdss'],2);?></td>
					<td>-</td>
				</tr>
			</tfoot>
			
		</table>
		
		
		
		</div>
	</div>
</div>
<!-- fin de la capa del contenido -->

<!-- capa del mapa -->
<div class="three columns" id="map-canvas"></div>
<!-- fin de la capa del mapa -->




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
<!-- -->
</body>
</html>
