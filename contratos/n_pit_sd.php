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
<? echo $mensaje;?>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active">
<a href="">
	<?
	if ($modo==pit)
	{
		echo "1.- Registro de solicitud para segundo desembolso";
	}
	elseif($modo==animador)
	{
		echo "2.- Planes de inversion territorial que solicitan segundo desembolso";
	}
	elseif($modo==pdn)
	{
		echo "3.- Planes de negocio que solicitan segundo desembolso";
	}
	elseif($modo==mrn)
	{
		echo "4.- Planes de gestion de recursos naturales que solicitan segundo desembolso";
	}
	?>
</a>
</dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<?
if ($modo==pit)
{
$sql="SELECT sys_bd_numera_dependencia.cod, 
		sys_bd_numera_dependencia.n_solicitud_iniciativa
		FROM sys_bd_numera_dependencia
		WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
		sys_bd_numera_dependencia.periodo='$anio'";
		$result=mysql_query($sql) or die (mysql_error());
		$r1=mysql_fetch_array($result);
		
		$n_solicitud=$r1['n_solicitud_iniciativa']+1;
?>
<form name="form5" class="custom" method="post" action="gestor_pit_sd.php?SES=<? echo  $SES;?>&anio=<? echo $anio;?>&action=ADD" onsubmit="return checkSubmit();">	
	<div class="two columns">Nº contrato PIT</div>
	<div class="four columns">
		<select name="pit" class="medium">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT
			pit_bd_ficha_pit.cod_pit,
			pit_bd_ficha_pit.n_contrato,
			pit_bd_ficha_pit.f_contrato,
			org_ficha_taz.nombre
			FROM
			pit_bd_ficha_pit
			INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			WHERE
			pit_bd_ficha_pit.n_contrato <> 0 AND
			org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
			ORDER BY
			pit_bd_ficha_pit.n_contrato ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_pit'];?>"><? echo numeracion($f1['n_contrato'])."-".periodo($f1['f_contrato']);?></option>
			<?
			}
			?>
		</select>
	</div>
	<div class="two columns">Nº evento CLAR</div>
	<div class="four columns">
		<select name="clar" class="medium">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT clar_bd_evento_clar.cod_clar, 
			clar_bd_evento_clar.f_evento, 
			sys_bd_dependencia.nombre AS oficina
			FROM clar_bd_evento_clar INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
			WHERE clar_bd_evento_clar.f_evento BETWEEN '$anio-01-01' AND '$anio-12-31'
			ORDER BY clar_bd_evento_clar.cod_clar ASC";
			$result=mysql_query($sql) or die(mysql_error());
			while($f2=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f2['cod_clar'];?>"><? echo numeracion($f2['cod_clar'])."-".periodo($f2['f_evento'])." / ".$f2['oficina'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="two columns">Nº solicitud</div>
	<div class="four columns">
	<input type="text" name="n_solicitud" class="required digits seven" readonly="readonly" value="<? echo $n_solicitud;?>">
	<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
	</div>
	<div class="two columns">Fecha de desembolso</div>
	<div class="four columns"><input type="date" name="f_desembolso" class="date required seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $fecha_hoy;?>"></div>

	<div class="two columns"><label>Fuente Fida %</label></div>
	<div class="four columns"><input type="text" name="fida" class="required number seven"></div>
	<div class="two columns"><label>Fuente RO%</label></div>
	<div class="four columns"><input type="text" name="ro" class="required number seven"></div>



	<div class="twelve columns"><br/></div>
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<a href="pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Cancelar</a>
	</div>	
</form>
<?
}
elseif($modo==animador)
{
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$n_atf=$r1['n_atf_iniciativa']+1;
?>
<form name="form5" class="custom" method="post" action="gestor_pit_sd.php?SES=<? echo  $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_PIT" onsubmit="return checkSubmit();">	

<div class="twelve columns">Seleccionar Plan de Inversion Territorial</div>
<div class="twelve columns">
	<select name="pit">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT clar_bd_ficha_sd_pit.cod_pit, 
		org_ficha_taz.n_documento, 
		org_ficha_taz.nombre
		FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_sd_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_sd_pit.cod_pit
		INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
		WHERE clar_bd_ficha_sd_pit.cod_ficha_sd='$cod' AND
		pit_bd_ficha_pit.cod_estado_iniciativa=007";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_pit'];?>"><? echo $f1['n_documento']." - ".$f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Nº ATF</div>
<div class="ten columns">
<input type="text" name="n_atf" class="required digits two" readonly="readonly" value="<? echo $n_atf;?>">
<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
</div>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="n_pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=pdn" class="primary button">Siguiente >></a>
	<a href="pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Cancelar</a>
</div>
</form>
<div class="twelve columns"><hr/></div>

<table class="responsive">
<thead>
		<tr>
			<th>Nº ATF</th>
			<th>Nº documento</th>
			<th class="nine">Nombre de la Organización</th>
			<th><br/></th>
		</tr>
</thead>		
<tbody>
<?
$sql="SELECT clar_atf_pit_sd.cod_atf_pit, 
	clar_atf_pit_sd.n_atf, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre
FROM pit_bd_ficha_pit INNER JOIN clar_atf_pit_sd ON pit_bd_ficha_pit.cod_pit = clar_atf_pit_sd.cod_pit
	 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
WHERE clar_atf_pit_sd.cod_ficha_sd='$cod'
ORDER BY clar_atf_pit_sd.n_atf ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>		
		<tr>
			<td><? echo numeracion($fila['n_atf']);?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['nombre'];?></td>
			<td><a href="gestor_pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $fila['cod_atf_pit'];?>&action=UPDATE_PIT" class="small success button">Actualizar</a></td>
		</tr>
<?
}
?>		
	</tbody>
</table>

<?
}
elseif($modo==pdn)
{

$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$n_atf=$r1['n_atf_iniciativa']+1;
?>
<form name="form5" class="custom" method="post" action="gestor_pit_sd.php?SES=<? echo  $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_PDN" onsubmit="return checkSubmit();">	
<div class="twelve columns">Seleccionar iniciativa</div>
<div class="twelve columns">
	<select name="pdn">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT org_ficha_organizacion.nombre, 
		pit_bd_ficha_pdn.denominacion, 
		pit_bd_ficha_pdn.cod_pdn
		FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_sd_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_sd_pit.cod_pit
		INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
		INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND org_ficha_organizacion.n_documento_taz = org_ficha_taz.n_documento
		INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
		WHERE clar_bd_ficha_sd_pit.cod_ficha_sd='$cod' AND
		pit_bd_ficha_pdn.cod_estado_iniciativa=007";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_pdn'];?>"><? echo $f1['nombre']."-".$f1['denominacion'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Nº ATF</div>
<div class="ten columns"><input type="text" name="n_atf" class="required digits two" readonly="readonly" value="<? echo $n_atf;?>"><input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>"></div>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="n_pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=mrn" class="primary button">Siguiente >></a>
	<a href="pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Cancelar</a>
</div>
</form>
<div class="twelve columns"><hr/></div>
<table class="responsive">
	<tbody>
		<thead>
		<tr>
			<th>Nº ATF</th>
			<th>Nº documento</th>
			<th>Nombre de la organizacion / Denominacion del PDN</th>
			<th>Estado</th>
			<th><br/></th>
			<th><br/></th>
		</tr>
		</thead>
<?
$sql="SELECT clar_atf_pdn.cod_atf_pdn, 
	clar_atf_pdn.n_atf, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	sys_bd_estado_iniciativa.descripcion AS estado
FROM pit_bd_ficha_pdn INNER JOIN clar_atf_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pdn.cod_estado_iniciativa
WHERE clar_atf_pdn.cod_tipo_atf_pdn=2 AND
clar_atf_pdn.cod_relacionador='$cod'
ORDER BY clar_atf_pdn.n_atf ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>		
		<tr>
			<td><? echo numeracion($fila['n_atf']);?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['nombre']." - ".$fila['denominacion'];?></td>
			<td><? echo $fila['estado'];?></td>
			<td><a href="gestor_pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $fila['cod_atf_pdn'];?>&modo=pdn&action=UPDATE_ATF_PDN" class="small success button">Actualizar</a></td>
			<td><a href="gestor_pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $fila['cod_atf_pdn'];?>&modo=pdn&action=ANULA_ATF_PDN" class="small alert button">Anular</a></td>
		</tr>
<?
}
?>		
	</tbody>
</table>



<?
}
elseif($modo==mrn)
{
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);

	$n_atf=$r1['n_atf_iniciativa']+1;
?>
<form name="form5" class="custom" method="post" action="gestor_pit_sd.php?SES=<? echo  $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_MRN" onsubmit="return checkSubmit();">	
<div class="twelve columns">Seleccionar iniciativa</div>
<div class="twelve columns">
	<select name="mrn">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
          org_ficha_organizacion.n_documento, 
          org_ficha_organizacion.nombre
          FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_sd_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_sd_pit.cod_pit
          INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
          INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
          WHERE clar_bd_ficha_sd_pit.cod_ficha_sd='$cod' AND
          pit_bd_ficha_mrn.cod_estado_iniciativa=007";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $f1['cod_mrn'];?>"><? echo $f1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="two columns">Nº ATF</div>
<div class="ten columns"><input type="text" name="n_atf" class="required digits two" readonly="readonly" value="<? echo $n_atf;?>"><input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>"></div>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="../print/print_pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>" class="primary button">Imprimir</a>
	<a href="pit_sd.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Cancelar</a>
</div>
</form>
<div class="twelve columns"><hr/></div>
<table class="responsive">
	<tbody>
		<tr>
			<th>Nº ATF</th>
			<th>Nº documento</th>
			<th>Nombre de la Organizacion</th>
			<th><br/></th>
			<th><br/></th>
		</tr>
<?
$sql="SELECT clar_atf_mrn_sd.cod_atf_mrn, 
	clar_atf_mrn_sd.n_atf, 
	pit_bd_ficha_mrn.sector, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre
FROM pit_bd_ficha_mrn INNER JOIN clar_atf_mrn_sd ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn_sd.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE clar_atf_mrn_sd.cod_ficha_sd='$cod'
ORDER BY clar_atf_mrn_sd.n_atf ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>		
		<tr>
			<td><? echo numeracion($fila['n_atf']);?></td>
			<td><? echo $fila['n_documento'];?></td>
			<td><? echo $fila['nombre']." - ".$fila['sector'];?></td>
			<td><a href="gestor_pit_sd.php?SES=<? echo  $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $fila['cod_atf_mrn'];?>&action=UPDATE_ATF_MRN" class="small success button">Actualizar</a></td>
			<td><a href="" class="small alert button">Anular</a></td>
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
