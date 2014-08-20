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
<dd  class="active"><a href="">

<?
if ($modo==pit)
{
	echo "Paso 1 de 2.- Plan de Inversión Territorial";
}
elseif($modo==iniciativa)
{
	echo "Paso 2 de 2.- Iniciativas que conforman un PIT";
}
elseif($modo==danza)
{
	echo "Paso 3 de 4.- Danza Tipica";
}
elseif($modo==gastro)
{
	echo "Paso 4 de 4.- Plato Tipico";
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
?>

<form name="form5" method="post" action="gestor_participante_concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_PIT" onsubmit="return checkSubmit();">


<div class="two columns">Seleccionar PIT</div>
<div class="ten columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pit.cod_pit, 
		org_ficha_taz.n_documento, 
		org_ficha_taz.nombre, 
		pit_bd_ficha_pit.n_animador
		FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
		WHERE pit_bd_ficha_pit.n_contrato<>0 AND
		pit_bd_ficha_pit.n_animador<>0";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod_pit'];?>"><? echo $r1['n_documento']." - ".$r1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><h6>Representante</h6></div>
<div class="two columns">DNI</div>
<div class="ten columns"><input type="text" name="dni" class="required digits three"></div>
<div class="two columns">Nombres</div>
<div class="ten columns"><input type="text" name="nombre" class="required"></div>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
</div>	
</form>
<div class="twelve columns"><hr/></div>

<table class="responsive">
	<thead>
		<tr>
			<td>N.</td>
			<td>Nombre deL PIT</td>
			<td>Nombre del Representante Legal</td>
			<td>Oficina</td>
			<td><br/></td>
		</tr>
	</thead>

	<tbody>
<?
	$sql="SELECT gcac_pit_participante_concurso.cod_participante, 
	org_ficha_taz.nombre, 
	gcac_pit_participante_concurso.nombre_rep, 
	org_ficha_taz.n_documento, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_pit INNER JOIN gcac_pit_participante_concurso ON pit_bd_ficha_pit.cod_pit = gcac_pit_participante_concurso.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE gcac_pit_participante_concurso.cod_concurso='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
?>	
		<tr>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre'];?></td>
			<td><? echo $f1['nombre_rep'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_participante_concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $f1['cod_participante'];?>&action=DELETE_PARTICIPANTE" class="small alert button">Quitar</a></td>
		</tr>
<?
}
?>		
	</tbody>

</table>




<?
}
elseif($modo==iniciativa)
{

$sql="SELECT * FROM gcac_pit_participante_concurso WHERE cod_participante='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

?>



<form name="form5" method="post" action="gestor_participante_concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $id;?>&action=ADD_INICIATIVA" onsubmit="return checkSubmit();">

<div class="two columns">Seleccionar PGRN</div>
<div class="ten columns">
	<select name="pgrn" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
		org_ficha_organizacion.n_documento, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
		WHERE pit_bd_ficha_mrn.cod_pit='".$r4['cod_pit']."' AND
		pit_bd_ficha_mrn.cod_estado_iniciativa<>000 AND
		pit_bd_ficha_mrn.cod_estado_iniciativa<>001 AND
		pit_bd_ficha_mrn.cod_estado_iniciativa<>003";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod_mrn'];?>"><? echo $r1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Plan de Negocio 1</div>
<div class="ten columns">
	<select name="pdn_1" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_organizacion.cod_tipo_doc_taz AND pit_bd_ficha_pit.n_documento_taz = org_ficha_organizacion.n_documento_taz
WHERE pit_bd_ficha_pit.cod_pit='".$r4['cod_pit']."' AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
		$result=mysql_query($sql) or die (mysql_error());
		while($r2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r2['cod_pdn'];?>"><? echo $r2['nombre']." - ".$r2['denominacion'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="two columns">Plan de Negocio 2</div>
<div class="ten columns">
	<select name="pdn_2" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_organizacion.cod_tipo_doc_taz AND pit_bd_ficha_pit.n_documento_taz = org_ficha_organizacion.n_documento_taz
WHERE pit_bd_ficha_pit.cod_pit='".$r4['cod_pit']."' AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
		$result=mysql_query($sql) or die (mysql_error());
		while($r3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r3['cod_pdn'];?>"><? echo $r3['nombre']." - ".$r3['denominacion'];?></option>
		<?
		}
		?>
	</select>
</div>

<div class="two columns">Plan de Negocio 3</div>
<div class="ten columns">
	<select name="pdn_3" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento
FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_organizacion.cod_tipo_doc_taz AND pit_bd_ficha_pit.n_documento_taz = org_ficha_organizacion.n_documento_taz
WHERE pit_bd_ficha_pit.cod_pit='".$r4['cod_pit']."' AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>000 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>001 AND
pit_bd_ficha_pdn.cod_estado_iniciativa<>003";
		$result=mysql_query($sql) or die (mysql_error());
		while($r4=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r4['cod_pdn'];?>"><? echo $r4['nombre']." - ".$r4['denominacion'];?></option>
		<?
		}
		?>		
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="two columns">Nombre del plato típico</div>
<div class="ten columns"><input type="text" name="plato" required></div>
<div class="two columns">Nombre de la danza</div>
<div class="ten columns"><input type="text" name="danza" required></div>

<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Guardar cambios</button>
	<a href="concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
	
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

<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>

</body>
</html>
