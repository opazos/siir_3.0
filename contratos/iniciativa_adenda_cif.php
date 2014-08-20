<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Buscamos los datos del PIT
$sql="SELECT * FROM pit_bd_ficha_adenda_pit WHERE cod_adenda='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$pit=$r1['cod_pit'];

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
<dd  class="active"><a href="">Ampliación Presupuestal para Iniciativas del PIT</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<!-- Animador Territorial -->
<form name="form5" id="f1" method="post" class="custom" action="" onsubmit="return checkSubmit();">
	
	<div class="twelve columns"><h6>I.- Animador Territorial - Seleccionar Iniciativa</h6></div>
	<div class="twelve columns">
		<select name="pit">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT pit_bd_ficha_pit.cod_pit, 
			org_ficha_taz.nombre
			FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			WHERE pit_bd_ficha_pit.cod_pit='$pit'";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_pit'];?>"><? echo $f1['nombre'];?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="twelve columns"><br/></div>
	<div class="two columns">Aporte PDSS (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_pdss" class="number seven"></div>
	<div class="two columns">Aporte Organizacion (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_org" class="number seven"></div>
	<div class="twelve columns"><br/></div>
	<div class="twelve columns"><button type="button" class="success button" onclick="this.form.action='gestor_adenda_pit_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PIT'; this.form.submit()">Asignar</button></div>
	<div class="twelve columns"><hr/></div>
	<table>
		<thead>
			<tr>
				<th>Nº</th>
				<th>Nombre de la organizacion</th>
				<th>Aporte PDSS(S/.)</th>
				<th>Aporte Org.(S/.)</th>
				<th><br/></th>
			</tr>
		</thead>
		
		<tbody>
		<?
		$num=0;
		$sql="SELECT pit_bd_iniciativa_adenda.cod, 
		pit_bd_iniciativa_adenda.aporte_pdss, 
		pit_bd_iniciativa_adenda.aporte_org, 
		org_ficha_taz.nombre
		FROM pit_bd_ficha_pit INNER JOIN pit_bd_iniciativa_adenda ON pit_bd_ficha_pit.cod_pit = pit_bd_iniciativa_adenda.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = pit_bd_iniciativa_adenda.cod_tipo_iniciativa
		INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
		WHERE pit_bd_iniciativa_adenda.cod_ficha_adenda='$id'";
		$result=mysql_query($sql) or die (mysql_error());
		while($fila1=mysql_fetch_array($result))
		{
			$num++
		
		?>
			<tr>
				<td><? echo $num;?></td>
				<td><? echo $fila1['nombre'];?></td>
				<td><? echo number_format($fila1['aporte_pdss'],2);?></td>
				<td><? echo number_format($fila1['aporte_org'],2);?></td>
				<td><a href="" class="small alert button">Desvincular</a></td>
			</tr>
		<?
		}
		?>	
		</tbody>
	</table>
	
</form>
<!--   Fin Animador Territorial  -->
<form name="form6" id="f2" class="custom" method="post" action="" onsubmit="return checkSubmit();">
<div class="twelve columns"><h6>II.- Plan de Gestión de Recursos Naturales - Seleccionar Iniciativa</h6></div>	

<div class="twelve columns">
	<select name="pgrn">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
		pit_bd_ficha_mrn.sector, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
		WHERE pit_bd_ficha_mrn.cod_pit='$pit'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f2=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f2['cod_mrn'];?>"><? echo $f2['nombre']."-".$f2['sector'];?></option>
		<?
		}
		?>
	</select>
</div>

	<div class="twelve columns"><br/></div>
	<div class="two columns">Aporte PDSS (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_pdss" class="number seven"></div>
	<div class="two columns">Aporte Organizacion (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_org" class="number seven"></div>
	<div class="twelve columns"><br/></div>
	<div class="twelve columns"><button type="button" class="success button" onclick="this.form.action='gestor_adenda_pit_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_MRN'; this.form.submit()">Asignar</button></div>
	<div class="twelve columns"><hr/></div>
	<table>
		<thead>
			<tr>
				<th>Nº</th>
				<th>Nombre de la organizacion</th>
				<th>Aporte PDSS(S/.)</th>
				<th>Aporte Org.(S/.)</th>
				<th><br/></th>
			</tr>
		</thead>
		
		<tbody>
		<?
		$num1=0;
		$sql="SELECT pit_bd_iniciativa_adenda.cod, 
	pit_bd_iniciativa_adenda.aporte_pdss, 
	pit_bd_iniciativa_adenda.aporte_org, 
	pit_bd_ficha_mrn.sector, 
	org_ficha_organizacion.nombre
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_iniciativa_adenda ON pit_bd_ficha_mrn.cod_mrn = pit_bd_iniciativa_adenda.cod_iniciativa AND pit_bd_ficha_mrn.cod_tipo_iniciativa = pit_bd_iniciativa_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_iniciativa_adenda.cod_ficha_adenda='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila2=mysql_fetch_array($result))
	{
		$num1++
	
		?>
			<tr>
				<td><? echo $num1;?></td>
				<td><? echo $fila2['nombre'];?></td>
				<td><? echo number_format($fila2['aporte_pdss'],2);?></td>
				<td><? echo number_format($fila2['aporte_org'],2);?></td>
				<td><a href="" class="small alert button">Desvincular</a></td>
			</tr>
		<?
		}
		?>
		</tbody>
		
	</table>

</form>
<!-- Plan de gestión de recursos -->

<!-- Planes de Negocio -->
<form name="form5" class="custom" method="post" action="" onsubmit="return checkSubmit();">
<div class="twelve columns"><h6>III.- Plan de Negocio - Seleccionar Iniciativa</h6></div>	

<div class="twelve columns">
	<select name="pdn">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
		pit_bd_ficha_pdn.denominacion, 
		org_ficha_organizacion.nombre
		FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
		WHERE pit_bd_ficha_pdn.cod_pit='$pit'";
		$result=mysql_query($sql) or die (mysql_error());
		while($f3=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f3['cod_pdn'];?>"><? echo $f3['nombre']."-".$f3['denominacion'];?></option>
		<?
		}
		?>
	</select>
</div>
	<div class="twelve columns"><br/></div>
	<div class="two columns">Aporte PDSS (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_pdss" class="number seven"></div>
	<div class="two columns">Aporte Organizacion (S/.)</div>
	<div class="four columns"><input type="text" name="aporte_org" class="number seven"></div>
	<div class="twelve columns"><br/></div>
	<div class="twelve columns"><button type="button" class="success button" onclick="this.form.action='gestor_adenda_pit_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PDN'; this.form.submit()">Asignar</button></div>
	<div class="twelve columns"><hr/></div>
	<table>
		<thead>
			<tr>
				<th>Nº</th>
				<th>Nombre de la organizacion</th>
				<th>Aporte PDSS(S/.)</th>
				<th>Aporte Org.(S/.)</th>
				<th><br/></th>
			</tr>
		</thead>
		
		<tbody>
		<?
		$num2=0;
		$sql="SELECT pit_bd_iniciativa_adenda.cod, 
	pit_bd_iniciativa_adenda.aporte_pdss, 
	pit_bd_iniciativa_adenda.aporte_org, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.nombre
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_iniciativa_adenda ON pit_bd_ficha_pdn.cod_pdn = pit_bd_iniciativa_adenda.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_iniciativa_adenda.cod_tipo_iniciativa
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_iniciativa_adenda.cod_ficha_adenda='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($fila3=mysql_fetch_array($result))
{
	$num2++
		?>
			<tr>
				<td><? echo $num2;?></td>
				<td><? echo $fila3['nombre']."-".$fila3['denominacion'];?></td>
				<td><? echo number_format($fila3['aporte_pdss'],2);?></td>
				<td><? echo number_format($fila3['aporte_org'],2);?></td>
				<td><a href="" class="small alert button">Desvincular</a></td>
			</tr>
<?
}
?>			
		</tbody>
	</table>
			
</form>
<!-- Planes de Negocio -->

<div class="twelve columns"><hr/></div>
<div class="twelve columns">
	<a href="edit_adenda_pit_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>" class="primary button">Siguiente</a>
	<a href="" class="secondary button">Finalizar</a>
	
</div>

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
