<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT pit_bd_ficha_pit.cod_pit, 
	pit_bd_ficha_pit.n_contrato, 
	pit_bd_ficha_pit.f_contrato, 
	pit_bd_ficha_pit.mes, 
	pit_bd_ficha_pit.n_solicitud, 
	pit_bd_ficha_pit.fuente_fida, 
	pit_bd_ficha_pit.fuente_ro, 
	clar_atf_pit.n_atf, 
	clar_atf_pit.cod_componente, 
	clar_atf_pit.cod_poa, 
	clar_atf_pit.cod_atf_pit
FROM clar_atf_pit INNER JOIN pit_bd_ficha_pit ON clar_atf_pit.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE pit_bd_ficha_pit.cod_pit='$id'";
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
<? echo $mensaje;?>

<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       



<dl class="tabs">
<dd class="active">
<a href="#simple1">
<?
if ($modo==pit)
{
	        echo "1.- Información del contrato";
}
elseif($modo==pdn)
{
	        echo "2.- Planes de Negocio";
}
elseif($modo==mrn)
{
	        echo "3.- Planes de Gestion de Recursos Naturales";
}
?>
</a>
</dd>
</dl>

<ul class="tabs-content">
<li class="active" id="simple1Tab">
  <?
  if ($modo==pit)
  {
  ?>      
  
        <form name="form5" class="custom" method="post" action="gestor_contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
	        
	        <div class="two columns">Nº contrato</div>
	        <div class="ten columns"><input type="text" name="n_contrato" class="required two" value="<? echo $row['n_contrato'];?>" readonly>
	        <input type="hidden" name="codigo" value="<? echo $row['cod_pit'];?>">
	        <input type="hidden" name="codigo_atf" value="<? echo $row['cod_atf_pit'];?>">
	        
	        </div>
	        
	        <div class="twelve columns">Seleccionar PIT</div>
	        <div class="twelve columns">
		        <select name="pit" disabled="disabled">
			        <option value="" selected="selected">Seleccionar</option>
			        <?
			        if ($row1['cod_dependencia']==001)
			        {
			        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
			        org_ficha_taz.nombre
			        FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			        WHERE
			        pit_bd_ficha_pit.mancomunidad=0
			        ORDER BY org_ficha_taz.nombre ASC";   
			        }
			        else
			        {
			        $sql="SELECT pit_bd_ficha_pit.cod_pit, 
			        org_ficha_taz.nombre
			        FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
			        WHERE
			        pit_bd_ficha_pit.mancomunidad=0 AND
			        org_ficha_taz.cod_dependencia='".$row1['cod_dependencia']."'
			        ORDER BY org_ficha_taz.nombre ASC";
			        }
			        $result=mysql_query($sql) or die (mysql_error());
			        while($f1=mysql_fetch_array($result))
			        {
			        ?>
			        <option value="<? echo $f1['cod_pit'];?>" <? if ($f1['cod_pit']==$row['cod_pit']) echo "selected";?>><? echo $f1['nombre'];?></option>
			        <?
			        }
			        ?>
		        </select>
	        </div>
	        <div class="two columns">Fecha de contrato</div>
	        <div class="four columns"><input type="date" name="f_contrato" class="required date seven" maxlength="10" placeholder="aaaa-mm-dd" value="<? echo $row['f_contrato'];?>"></div>
	        <div class="two columns">Duración (en meses)</div>
	        <div class="four columns"><input type="text" name="duracion" class="required digits five" value="<? echo $row['mes'];?>"></div>
	        
	        <div class="two columns">Nº solicitud</div>
	        <div class="four columns"><input type="text" name="n_solicitud" class="required digits five" value="<? echo $row['n_solicitud'];?>" readonly></div>
	        <div class="two columns">Nº ATF</div>
	        <div class="four columns"><input type="text" name="n_atf" class="required digits five" value="<? echo $row['n_atf'];?>" readonly></div>
	        
		    <div class="twelve columns"><h6>Afectación POA</h6></div>
		    <div class="twelve columns">
			    <select name="poa">
				    <option value="" selected="selected">Seleccionar</option>
				    <?
				    $sql="SELECT sys_bd_subactividad_poa.cod, 
				    sys_bd_subactividad_poa.codigo, 
				    sys_bd_subactividad_poa.nombre, 
				    sys_bd_subactividad_poa.cod_categoria_poa
				    FROM sys_bd_subactividad_poa
				    WHERE sys_bd_subactividad_poa.codigo LIKE '3.1.2.7.' AND
				    sys_bd_subactividad_poa.periodo='$anio'";
				    $result=mysql_query($sql) or die (mysql_error());
				    while($r3=mysql_fetch_array($result))
				    {
				    ?>
				    <option value="<? echo $r3['cod'];?>" <? if ($r3['cod']==$row['cod_poa']) echo "selected";?>><? echo $r3['codigo']."-".$r3['nombre'];?></option>
				    <?
				    }
				    ?>
			    </select>
		    </div>

	        <div class="twelve columns"><h6>Fuente de financiamiento</h6></div>
	        <div class="two columns">FIDA %</div>
	        <div class="four columns"><input type="text" name="fte_fida" class="required number five" value="<? echo $row['fuente_fida'];?>"></div>
	        <div class="two columns">Recursos Ordinarios %</div>
	        <div class="four columns"><input type="text" name="fte_ro" class="required number five" value="<? echo $row['fuente_ro'];?>"></div>
	        
	        <div class="twelve columns">
	        <button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	        <a href="contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="primary button">Cancelar</a>
	        </div>
        </form>
 <?
 }
 elseif($modo==pdn)
 {
	 //Obtengo el numero de atf
	 $sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	$n_atf=$r1['n_atf_iniciativa']+1;
	
	//Busco el atf del pit
	$sql="SELECT clar_atf_pit.cod_atf_pit
	FROM clar_atf_pit
	WHERE clar_atf_pit.cod_pit='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
 
 ?>
 
<form name="form5" class="custom" method="post" action="gestor_contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_PDN" onsubmit="return checkSubmit();"> 
 <div class="twelve columns">Seleccionar Plan de negocio</div>
 <div class="twelve columns">
	 <select name="pdn">
		 <option value="" selected="selected">Seleccionar</option>
		 <?
		 $sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
		 pit_bd_ficha_pdn.denominacion, 
		 org_ficha_organizacion.nombre
		 FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
		 WHERE pit_bd_ficha_pdn.cod_estado_iniciativa=002 AND
		 pit_bd_ficha_pdn.cod_pit='$cod'
		 ORDER BY org_ficha_organizacion.nombre ASC";
		 $result=mysql_query($sql) or die (mysql_error());
		 while($f1=mysql_fetch_array($result))
		 {
		 ?>
		 <option value="<? echo $f1['cod_pdn'];?>"><? echo $f1['nombre']."/".$f1['denominacion'];?></option>
		 <?
		 }
		 ?>
	 </select>
 </div>

<div class="two columns">Nº de ATF</div>
<div class="ten columns"><input type="text" name="n_atf" class="required digits two" readonly="readonly" value="<? echo $n_atf;?>">
<input type="hidden" name="cod_atf" value="<? echo $r2['cod_atf_pit'];?>">
<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
</div>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="n_contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&modo=mrn" class="primary button">Siguiente >></a>
	<a href="contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Terminar</a>
</div>
 
</form> 
<div class="twelve columns"><hr/></div>

<table class="responsive">
	<tbody>
		<tr>
			<th>Nº ATF</th>
			<th>Nº documento</th>
			<th>Nombre de la organizacion</th>
			<th>Denominacion</th>
		</tr>
<?
$sql="SELECT clar_atf_pdn.cod_atf_pdn, 
	clar_atf_pdn.n_atf, 
	pit_bd_ficha_pdn.denominacion, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre
FROM pit_bd_ficha_pdn INNER JOIN clar_atf_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_atf_pdn.cod_pdn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
WHERE clar_atf_pdn.cod_tipo_atf_pdn=1 AND
pit_bd_ficha_pdn.cod_pit='$cod'
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
 elseif($modo==mrn)
 {
	 
	//Obtengo el numero de atf
	 $sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_atf_iniciativa
	FROM sys_bd_numera_dependencia
	WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
	sys_bd_numera_dependencia.periodo='$anio'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	$n_atf=$r1['n_atf_iniciativa']+1;
	
	//Busco el atf del pit
	$sql="SELECT clar_atf_pit.cod_atf_pit
	FROM clar_atf_pit
	WHERE clar_atf_pit.cod_pit='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	$r2=mysql_fetch_array($result);
 
 ?>
<form name="form5" class="custom" method="post" action="gestor_contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_MRN" onsubmit="return checkSubmit();"> 
 <div class="twelve columns">Seleccionar Plan de gestion</div>
 <div class="twelve columns">
	 <select name="mrn">
		 <option value="" selected="selected">Seleccionar</option>
		 <?
		 $sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
		 org_ficha_organizacion.n_documento, 
		 org_ficha_organizacion.nombre
		 FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_mrn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
		 WHERE pit_bd_ficha_mrn.cod_estado_iniciativa='002' AND
		 pit_bd_ficha_mrn.cod_pit='$cod'";
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

<div class="two columns">Nº de ATF</div>
<div class="ten columns"><input type="text" name="n_atf" class="required digits two" readonly="readonly" value="<? echo $n_atf;?>">
<input type="hidden" name="cod_atf" value="<? echo $r2['cod_atf_pit'];?>">
<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
</div>

<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
	<a href="../print/print_contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>" class="primary button">Imprimir</a>
	<a href="contrato_pit_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="primary button">Terminar</a>
</div>
 
</form> 
<div class="twelve columns"><hr/></div> 

<table class="responsive">
	<tbody>
		<tr>
			<th>Nº ATF</th>
			<th>Nº documento</th>
			<th>Nombre de la organizacion</th>
			<th>Lema</th>
		</tr>
<?
$sql="SELECT clar_atf_mrn.cod_atf_mrn, 
	clar_atf_mrn.n_atf, 
	pit_bd_ficha_mrn.lema, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre
FROM pit_bd_ficha_mrn INNER JOIN clar_atf_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_atf_mrn.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE pit_bd_ficha_mrn.cod_pit='".$cod."'
ORDER BY clar_atf_mrn.n_atf ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>		
		<tr>
			<td><? echo numeracion($f2['n_atf']);?></td>
			<td><? echo $f2['n_documento'];?></td>
			<td><? echo $f2['nombre'];?></td>
			<td><? echo $f2['lema'];?></td>
		</tr>
<?
}
?>		
</tbody>
</table>

 <?
 }
 ?>             
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
