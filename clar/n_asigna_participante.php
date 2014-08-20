<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


if($modo==pit)
{
	$titulo="1.- Planes de Inversión Territorial - Primer Desembolso";
}
elseif($modo==pgrn)
{
	$titulo="2.- Planes de Gestión de Recursos Naturales - Primer Desembolso";
}
elseif($modo==pdn)
{
	$titulo="3.- Planes de Negocio pertenecientes a un PIT - Primer Desembolso";
}
elseif($modo==pdn_ind)
{
	$titulo="4.- Planes de Negocio Independientes - Primer Desembolso";
}
elseif($modo==idl)
{
	$titulo="5.- Inversiones de Desarrollo Local - Primer Desembolso";
}
elseif($modo==pit_2)
{
	$titulo="6.- Planes de Inversión Territorial - Segundo Desembolso";
}
elseif($modo==pgrn_2)
{
	$titulo="7.- Planes de Gestión de Recursos Naturales - Segundo Desembolso";
}
elseif($modo==pdn_2)
{
	$titulo="8.- Planes de Negocio - Segundo Desembolso";
}
elseif($modo==idl_2)
{
	$titulo="9.- Inversiones de Desarrollo Local - Segundo Desembolso";
}

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
<dd  class="active"><a href=""><? echo $titulo;?></a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<?
if ($modo==pit)
{
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PIT" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar PIT</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pit.cod_pit, 
		org_ficha_taz.n_documento, 
		org_ficha_taz.nombre, 
		clar_bd_ficha_pit.cod_ficha_pit_clar, 
		sys_bd_dependencia.nombre AS oficina
		FROM pit_bd_ficha_pit INNER JOIN org_ficha_taz ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
	 LEFT OUTER JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 WHERE pit_bd_ficha_pit.cod_estado_iniciativa='001' AND
	 clar_bd_ficha_pit.cod_pit is NULL
	 ORDER BY org_ficha_taz.nombre ASC, oficina ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($r1=mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $r1['cod_pit'];?>"><? echo $r1['nombre'];?></option>
		<?
		}
		?>
	</select>
</div>
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=pgrn" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asigna" class="secondary button">Finalizar</a>
</div>

</form>
<div class="twelve columns"><hr/></div>
<table id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Nombre de la Organización</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
<?
$num=0;
$sql="SELECT pit_bd_ficha_pit.cod_pit, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	clar_bd_ficha_pit.cod_ficha_pit_clar, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_pit INNER JOIN org_ficha_taz ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
	 LEFT OUTER JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pit.cod_pit
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE
clar_bd_ficha_pit.cod_clar='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$num++
?>	
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_pit_clar'];?>&action=DELETE_PIT" class="small alert button">Desvincular</a></td>
		</tr>
<?
}
?>		
	</tbody>
</table>
<?
}
elseif($modo==pgrn)
{
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PGRN" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar Plan de Gestión</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
		pit_bd_ficha_mrn.sector, 
		pit_bd_ficha_mrn.lema, 
		pit_bd_ficha_mrn.cod_pit, 
		org_ficha_organizacion.nombre, 
		org_ficha_organizacion.n_documento, 
		clar_bd_ficha_mrn.cod_ficha_mrn_clar, 
		clar_bd_ficha_pit.cod_ficha_pit_clar, 
		sys_bd_dependencia.nombre AS oficina
		FROM pit_bd_ficha_mrn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
		LEFT OUTER JOIN clar_bd_ficha_mrn ON clar_bd_ficha_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
		LEFT OUTER JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
		INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
		WHERE pit_bd_ficha_mrn.cod_estado_iniciativa=001 AND
		clar_bd_ficha_mrn.cod_mrn is NULL
		ORDER BY pit_bd_ficha_mrn.cod_mrn ASC";
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
<div class="twelve columns"><br/></div>
<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=pdn" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion" class="secondary button">Finalizar</a>
</div>
</form>
<div class="twelve columns"><hr/></div>
<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Nombre de la Organización</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$num=0;
	$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.lema, 
	pit_bd_ficha_mrn.cod_pit, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	clar_bd_ficha_mrn.cod_ficha_mrn_clar, 
	clar_bd_ficha_pit.cod_ficha_pit_clar, 
	sys_bd_dependencia.nombre AS oficina
	FROM pit_bd_ficha_mrn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN clar_bd_ficha_mrn ON clar_bd_ficha_mrn.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 LEFT OUTER JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_mrn.cod_pit
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 WHERE pit_bd_ficha_mrn.cod_estado_iniciativa = 001 AND
	 clar_bd_ficha_mrn.cod_clar='$id'
	 ORDER BY pit_bd_ficha_mrn.cod_mrn ASC";
	 $result=mysql_query($sql) or die (mysql_error());
	 while($f1=mysql_fetch_array($result))
	 {
		 $num++
	 
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_mrn_clar'];?>&action=DELETE_PGRN" class="small alert button">Desvincular</a></td>
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
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PDN" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar Plan de Negocio</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.cod_pit, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	clar_bd_ficha_pdn.cod_ficha_pdn_clar, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa = 001 AND
pit_bd_ficha_pdn.tipo=0 AND
clar_bd_ficha_pdn.cod_ficha_pdn_clar IS NULL
ORDER BY org_ficha_organizacion.nombre ASC, org_ficha_organizacion.cod_dependencia ASC";
	 $result=mysql_query($sql) or die (mysql_error());
	 while($r1=mysql_fetch_array($result))
	 {
	   ?>
	   <option value="<? echo $r1['cod_pdn'];?>"><? echo $r1['nombre'];?></option>
	   <?
	   }
	   ?>
	</select>
</div>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=pdn_ind" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion" class="secondary button">Finalizar</a>
</div>

</form>
<div class="twelve columns"><hr/></div>
<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Nombre de la Organización / Denominación</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$num=0;
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.cod_pit, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	clar_bd_ficha_pdn.cod_ficha_pdn_clar, 
	sys_bd_dependencia.nombre AS oficina, 
	pit_bd_ficha_pdn.tipo
	FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 LEFT OUTER JOIN clar_bd_ficha_pdn ON clar_bd_ficha_pdn.cod_pdn = pit_bd_ficha_pdn.cod_pdn
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 WHERE pit_bd_ficha_pdn.cod_estado_iniciativa = 001 AND
	 clar_bd_ficha_pdn.cod_clar='$id'
	 ORDER BY org_ficha_organizacion.nombre ASC, org_ficha_organizacion.cod_dependencia ASC";
	 $result=mysql_query($sql) or die (mysql_error());
	 while($f1=mysql_fetch_array($result))
	 {
		 $num++
	 
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre']." / ".$f1['denominacion'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_pdn_clar'];?>&action=DELETE_PDN" class="small alert button">Desvincular</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
</table>
<?
}
elseif($modo==pdn_ind)
{
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PDN_IND" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar Plan de Negocio</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
		<option value="" selected="selected">Seleccionar</option>
		<?
		$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
		pit_bd_ficha_pdn.denominacion, 
		pit_bd_ficha_pdn.cod_pit, 
		org_ficha_organizacion.nombre, 
		org_ficha_organizacion.n_documento, 
		sys_bd_dependencia.nombre AS oficina
		FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
		INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
		LEFT JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
		WHERE pit_bd_ficha_pdn.cod_estado_iniciativa = 001 AND
		pit_bd_ficha_pdn.tipo<>0 AND
		clar_bd_ficha_pdn_suelto.cod_ficha_pdn_clar IS NULL
		ORDER BY org_ficha_organizacion.nombre ASC, org_ficha_organizacion.cod_dependencia ASC";
	 $result=mysql_query($sql) or die (mysql_error());
	 while($r1=mysql_fetch_array($result))
	 {
	   ?>
	   <option value="<? echo $r1['cod_pdn'];?>"><? echo $r1['nombre'];?></option>
	   <?
	   }
	   ?>
	</select>
</div>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=idl" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion" class="secondary button">Finalizar</a>
</div>

</form>
<div class="twelve columns"><hr/></div>
<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Nombre de la Organización / Denominación</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	
	<tbody>
	<?
	$num=0;
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.cod_pit, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_ficha_pdn_suelto.cod_ficha_pdn_clar, 
	pit_bd_ficha_pdn.cod_estado_iniciativa
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa = 001 AND
pit_bd_ficha_pdn.tipo <>0 AND
clar_bd_ficha_pdn_suelto.cod_clar='$id'
ORDER BY org_ficha_organizacion.nombre ASC, org_ficha_organizacion.cod_dependencia ASC";
	 $result=mysql_query($sql) or die (mysql_error());
	 while($f1=mysql_fetch_array($result))
	 {
		 $num++
	 
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre']." / ".$f1['denominacion'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_pdn_clar'];?>&action=DELETE_PDN_IND" class="small alert button">Desvincular</a></td>
		</tr>
	<?
	}
	?>	
	</tbody>
</table>
<?
}
elseif($modo==idl)
{
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_IDL" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar IDL</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
	<option value="" selected="selected">Seleccionar</option>
	<?
	$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.denominacion, 
	clar_bd_ficha_idl.cod_ficha_idl_clar, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_idl INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 LEFT OUTER JOIN clar_bd_ficha_idl ON clar_bd_ficha_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_idl.cod_estado_iniciativa=001 AND
clar_bd_ficha_idl.cod_ficha_idl_clar IS NULL";
	$result=mysql_query($sql) or die (mysql_error());
	while($r1=mysql_fetch_array($result))
	{
	?>
	<option value="<? echo $r1['cod_ficha_idl'];?>"><? echo $r1['nombre']."-".$r1['denominacion'];?></option>
	<?
	}
	?>
	</select>
</div>

<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=pit_2" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion" class="secondary button">Finalizar</a>
</div>

</form>


<div class="twelve columns"><hr/></div>

<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Entidad / Inversion</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	$num=0;
	$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.denominacion, 
	clar_bd_ficha_idl.cod_ficha_idl_clar, 
	sys_bd_dependencia.nombre AS oficina
	FROM pit_bd_ficha_idl INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 LEFT OUTER JOIN clar_bd_ficha_idl ON clar_bd_ficha_idl.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 WHERE pit_bd_ficha_idl.cod_estado_iniciativa=001 AND
	 clar_bd_ficha_idl.cod_clar='$id'
	 ORDER BY org_ficha_organizacion.nombre ASC, org_ficha_organizacion.cod_dependencia ASC";
	 $result=mysql_query($sql) or die (mysql_error());
	 while($f1=mysql_fetch_array($result))
	 {
		 $num++
	 
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre']." / ".$f1['denominacion'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_idl_clar'];?>&action=DELETE_IDL" class="small alert button">Desvincular</a></td>
		</tr>
	<?
		}
	?>
	</tbody>
</table>
<?
}
elseif($modo==pit_2)
{
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PIT_2" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar Plan de Inversion Territorial</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
	<option value="" selected="selected">Seleccionar</option>
	<?
	$sql="SELECT pit_bd_ficha_pit.cod_pit, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_ficha_pit_2.cod_ficha_pit_clar
	FROM pit_bd_ficha_pit INNER JOIN org_ficha_taz ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
	 LEFT OUTER JOIN clar_bd_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_pit = pit_bd_ficha_pit.cod_pit
	 WHERE pit_bd_ficha_pit.cod_estado_iniciativa='006' AND
	 clar_bd_ficha_pit_2.cod_ficha_pit_clar IS NULL
	 ORDER BY org_ficha_taz.nombre ASC";
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

<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=pgrn_2" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion" class="secondary button">Finalizar</a>
</div>

</form>
<div class="twelve columns"><hr/></div>
<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Organizacion</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
		$num=0;
		$sql="SELECT pit_bd_ficha_pit.cod_pit, 
		org_ficha_taz.n_documento, 
		org_ficha_taz.nombre, 
		sys_bd_dependencia.nombre AS oficina, 
		clar_bd_ficha_pit_2.cod_ficha_pit_clar
		FROM pit_bd_ficha_pit INNER JOIN org_ficha_taz ON pit_bd_ficha_pit.cod_tipo_doc_taz = org_ficha_taz.cod_tipo_doc AND pit_bd_ficha_pit.n_documento_taz = org_ficha_taz.n_documento
		INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
		LEFT OUTER JOIN clar_bd_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_pit = pit_bd_ficha_pit.cod_pit
		WHERE pit_bd_ficha_pit.cod_estado_iniciativa='006' AND
		clar_bd_ficha_pit_2.cod_clar='$id'
		ORDER BY org_ficha_taz.nombre ASC";
		$result=mysql_query($sql) or die (mysql_error());
		while($f1=mysql_fetch_array($result))
		{
			$num++
		
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_pit_clar'];?>&action=DELETE_PIT_2" class="small alert button">Desvincular</a></td>
		</tr>
		<?
		}
		?>
	</tbody>
</table>
<?
}
elseif($modo==pgrn_2)
{
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PGRN_2" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar Plan de Gestión de Recursos Naturales</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
	<option value="" selected="selected">Seleccionar</option>
	<?
	$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.lema, 
	pit_bd_ficha_mrn.cod_pit, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_ficha_mrn_2.cod_ficha_mrn_clar
FROM pit_bd_ficha_mrn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN clar_bd_ficha_mrn_2 ON clar_bd_ficha_mrn_2.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE pit_bd_ficha_mrn.cod_estado_iniciativa = 006 AND
clar_bd_ficha_mrn_2.cod_ficha_mrn_clar IS NULL
ORDER BY org_ficha_organizacion.nombre ASC";
	$result=mysql_query($sql) or die (mysql_error());
	while($fila=mysql_fetch_array($result))
	{
	?>
	<option value="<? echo $fila['cod_mrn'];?>"><? echo $fila['nombre'];?></option>
	<?
	}
	?>
	</select>
</div>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=pdn_2" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion" class="secondary button">Finalizar</a>
</div>

</form>
<div class="twelve columns"><hr/></div>
<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Organizacion</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	$num=0;
	$sql="SELECT pit_bd_ficha_mrn.cod_mrn, 
	pit_bd_ficha_mrn.sector, 
	pit_bd_ficha_mrn.lema, 
	pit_bd_ficha_mrn.cod_pit, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_ficha_mrn_2.cod_ficha_mrn_clar
	FROM pit_bd_ficha_mrn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN clar_bd_ficha_mrn_2 ON clar_bd_ficha_mrn_2.cod_mrn = pit_bd_ficha_mrn.cod_mrn
	 WHERE pit_bd_ficha_mrn.cod_estado_iniciativa = 006 AND
	 clar_bd_ficha_mrn_2.cod_clar='$id'
	 ORDER BY org_ficha_organizacion.nombre ASC";
	 $result=mysql_query($sql) or die (mysql_error());
	 while($f1=mysql_fetch_array($result))
	 {
		 $num++
	 
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_mrn_clar'];?>&action=DELETE_PGRN_2" class="small alert button">Desvincular</a></td>
		</tr>
		<?
		}
		?>
	</tbody>
</table>
<?
}
elseif($modo==pdn_2)
{
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_PDN_2" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar Plan de Negocio</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
	<option value="" selected="selected">Seleccionar</option>
	<?
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.cod_pit, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_ficha_pdn_2.cod_ficha_pdn_clar
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa = 006 AND
clar_bd_ficha_pdn_2.cod_ficha_pdn_clar IS NULL
ORDER BY org_ficha_organizacion.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
	?>
	<option value="<? echo $r1['cod_pdn'];?>"><? echo $r1['nombre']." / ".$r1['denominacion'];?></option>
	<?
	}
	?>
	</select>
</div>

<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="n_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&modo=idl_2" class="primary button">Siguiente >></a>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion" class="secondary button">Finalizar</a>
</div>

</form>
<div class="twelve columns"><hr/></div>
<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Organizacion / Denominacion</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	$num=0;
	$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
	pit_bd_ficha_pdn.denominacion, 
	pit_bd_ficha_pdn.cod_pit, 
	org_ficha_organizacion.nombre, 
	org_ficha_organizacion.n_documento, 
	sys_bd_dependencia.nombre AS oficina, 
	clar_bd_ficha_pdn_2.cod_ficha_pdn_clar
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON pit_bd_ficha_pdn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_pdn.n_documento_org = org_ficha_organizacion.n_documento
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
	 LEFT OUTER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE pit_bd_ficha_pdn.cod_estado_iniciativa = 006 AND
clar_bd_ficha_pdn_2.cod_clar='$id'
ORDER BY org_ficha_organizacion.nombre ASC";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
	$num++
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre']." / ".$f1['denominacion'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_pdn_clar'];?>&action=DELETE_PDN_2" class="small alert button">Desvincular</a></td>
		</tr>
<?
}
?>		
</tbody>
</table>
<?
}
elseif($modo==idl_2)
{
?>
<form name="form5" method="post" action="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=ADD_IDL_2" onsubmit="return checkSubmit();">

<div class="twelve columns"><h6>Seleccionar Inversion de Desarrollo Local</h6></div>
<div class="twelve columns">
	<select name="iniciativa" class="hyjack">
	<option value="" selected="selected">Seleccionar</option>
	<?
	$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.denominacion, 
	clar_bd_ficha_idl_2.cod_ficha_idl_clar, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_idl INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 LEFT OUTER JOIN clar_bd_ficha_idl_2 ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_idl.cod_estado_iniciativa=006 AND
clar_bd_ficha_idl_2.cod_ficha_idl_clar IS NULL";
	$result=mysql_query($sql) or die (mysql_error());
	while($r1=mysql_fetch_array($result))
	{
	?>
	<option value="<? echo $r1['cod_ficha_idl'];?>"><? echo $r1['nombre']." - ".$r1['denominacion'];?></option>
	<?
	}
	?>
	</select>
</div>
<div class="twelve columns"><br/></div>

<div class="twelve columns">
	<button type="submit" class="success button">Asignar</button>
	<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=asignacion" class="secondary button">Finalizar</a>
</div>

</form>
<div class="twelve columns"><hr/></div>
<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>Nº</th>
			<th>Nº de Documento</th>
			<th class="seven">Entidad / Inversion</th>
			<th>Oficina</th>
			<th><br/></th>
		</tr>
	</thead>
	<tbody>
	<?
	$num=0;
	$sql="SELECT pit_bd_ficha_idl.cod_ficha_idl, 
	org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre, 
	pit_bd_ficha_idl.denominacion, 
	clar_bd_ficha_idl_2.cod_ficha_idl_clar, 
	sys_bd_dependencia.nombre AS oficina
FROM pit_bd_ficha_idl INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
	 LEFT OUTER JOIN clar_bd_ficha_idl_2 ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
	 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE pit_bd_ficha_idl.cod_estado_iniciativa=006 AND
clar_bd_ficha_idl_2.cod_clar='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	while($f1=mysql_fetch_array($result))
	{
		$num++
	
	?>
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f1['n_documento'];?></td>
			<td><? echo $f1['nombre']." / ".$f1['denominacion'];?></td>
			<td><? echo $f1['oficina'];?></td>
			<td><a href="gestor_asigna_participante.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f1['cod_ficha_idl_clar'];?>&action=DELETE_IDL_2" class="small alert button">Desvincular</a></td>
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
