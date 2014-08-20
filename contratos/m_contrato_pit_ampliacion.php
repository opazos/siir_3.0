<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);



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
if ($modo==pit)
{
echo "Paso 1.- Registrar ampliacion";
}
elseif($modo==pdn)
{
echo "Paso 2.- Registrar Planes de negocio";
}
?>
	
</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<?
if ($modo==pit)
{

$sql="SELECT * FROM clar_ampliacion_pit WHERE cod_ampliacion='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);
?>
<form name="form5" method="post" class="custom" action="gestor_amplia_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	
	<div class="two columns">Nº de contrato al que se le realizara la ampliacion</div>
	<div class="four columns">
		<select name="n_contrato">
			<option value="" selected="selected">Seleccionar</option>
			<?
			if($row1['cod_dependencia']==001)
			{
			$sql="SELECT pit_bd_ficha_pit.cod_pit, 
			pit_bd_ficha_pit.n_contrato, 
			pit_bd_ficha_pit.f_contrato
			FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			WHERE pit_bd_ficha_pit.n_contrato<>0 AND
			pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
			pit_bd_ficha_pit.cod_estado_iniciativa<>004
			ORDER BY pit_bd_ficha_pit.n_contrato ASC";				
			}
			else
			{
			$sql="SELECT pit_bd_ficha_pit.cod_pit, 
			pit_bd_ficha_pit.n_contrato, 
			pit_bd_ficha_pit.f_contrato
			FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			WHERE pit_bd_ficha_pit.n_contrato<>0 AND
			org_ficha_taz.cod_dependencia='".$row1['cod_dependencia']."' AND
			pit_bd_ficha_pit.cod_estado_iniciativa<>000 AND
			pit_bd_ficha_pit.cod_estado_iniciativa<>004
			ORDER BY pit_bd_ficha_pit.n_contrato ASC";
			}
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_pit'];?>" <? if ($f1['cod_pit']==$row['cod_pit']) echo "selected";?>><? echo numeracion($f1['n_contrato'])." - ".periodo($f1['f_contrato']);?></option>
			<?
			}
			?>
		</select>
		
		<input type="hidden" name="codigo" value="<? echo $row['cod_ampliacion'];?>">
	</div>
	<div class="two columns">Fecha de ampliacion</div>
	<div class="four columns"><input type="date" name="f_ampliacion" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_ampliacion'];?>"></div>
	<div class="two columns">Nº Evento CLAR donde se aprobo la ampliacion</div>
	<div class="four columns">
		<select name="clar">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT clar_bd_evento_clar.cod_clar, 
			clar_bd_evento_clar.f_evento, 
			sys_bd_dependencia.nombre
			FROM sys_bd_dependencia INNER JOIN clar_bd_evento_clar ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
			WHERE 
			clar_bd_evento_clar.f_evento BETWEEN '$anio-01-01' AND '$anio-12-31'";
			$result=mysql_query($sql) or die (mysql_error());
			while($f2=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f2['cod_clar'];?>" <? if ($f2['cod_clar']==$row['cod_clar']) echo "selected";?>><? echo numeracion($f2['cod_clar'])."-".periodo($f2['f_evento'])."/".$f2['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="two columns">Nº solicitud</div>
	<div class="four columns"><input type="text" name="n_solicitud" class="required digits five" readonly="readonly" value="<? echo $row['n_solicitud'];?>"></div>
	
	<div class="two columns">% Fuente FIDA</div>
	<div class="four columns"><input type="text" name="fte_fida" class="required number seven" value="<? echo $row['fte_fida'];?>"></div>
	<div class="two columns">% Fuente RO</div>
	<div class="four columns"><input type="text" name="fte_ro" class="required number seven" value="<? echo $row['fte_ro'];?>"></div>

	<div class="twelve columns"><br/></div>
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<a href="contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Cancelar</a>
	</div>
</form>
<?
}
elseif($modo==pdn)
{

//Busco la numeracion
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	$n_atf=$r1['n_atf_iniciativa']+1;

?>
<form name="form5" method="post" class="custom" action="gestor_amplia_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_PDN" onsubmit="return checkSubmit();">

<div class="twelve columns">Seleccionar Plan de negocio</div>
<div class="twelve columns">
	<select name="pdn">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT
		pit_bd_ficha_pdn.n_documento_org,
		org_ficha_organizacion.nombre,
		pit_bd_ficha_pdn.denominacion,
		pit_bd_ficha_pdn.cod_pdn
		FROM
		pit_bd_ficha_pit
		INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
		INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
		WHERE
		pit_bd_ficha_pit.concurso = 0 AND
		pit_bd_ficha_pdn.cod_estado_iniciativa = 002 AND
		org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_pdn'];?>"><? echo $f1['nombre']." / ".$f1['denominacion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Nº ATF</div>
<div class="ten columns"><input type="text" name="n_atf" class="required digits two" readonly="readonly" value="<? echo $n_atf;?>">
<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
</div>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="../print/print_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>" class="primary button">Imprimir</a>
	<a href="contrato_pit_ampliacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Finalizar</a>
</div>
<div class="twelve columns"><hr/></div>
</form>
<table class="responsive">
		<tbody>
		<tr>
			<th>Nº ATF</th>
			<th>Nº documento</th>
			<th>Nombre de la organizacion</th>
			<th>Denominacion</th>
		</tr>
<?
$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_atf_pdn.cod_atf_pdn, 
	clar_atf_pdn.n_atf
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 INNER JOIN clar_atf_pdn ON clar_atf_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_atf_pdn.cod_tipo_atf_pdn=3 AND
clar_atf_pdn.cod_relacionador='$cod'
ORDER BY clar_atf_pdn.n_atf ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>		
		<tr>
			<td><? echo numeracion($f2['n_atf']);?></td>
			<td><? echo $f2['n_documento'];?></td>
			<td><? echo $f2['nombre'];?></td>
			<td><? echo $f2['denominacion'];?></td>
		</tr>
<?
}
?>		
		</tbody>
</table>


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
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
