<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//determino que actividad del concurso es la que esta funcionando
if ($id<>NULL)
{
	$sql="SELECT cif_bd_concurso.actividad_1, 
	cif_bd_concurso.actividad_2, 
	cif_bd_concurso.actividad_3
	FROM cif_bd_concurso
	WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
	$result=mysql_query($sql) or die (mysql_error());
	$r1=mysql_fetch_array($result);
	
	if ($r1['actividad_1']==$id)
	{
		$n_actividad=1;
	}
	elseif ($r1['actividad_2']==$id)
	{
		$n_actividad=2;
	}
	elseif($r1['actividad_3']==$id)
	{
		$n_actividad=3;
	}
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
<dd  class="active"><a href="">Registro de calificación de concursos interfamiliares</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" action="gestor_cif_lento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $id;?>&action=ADD_REG" onsubmit="return checkSubmit();" >
<?php
if ($id==NULL)
{
?>
	<div class="row">
	<div class="twelve columns"><h6>I.- SELECCIONAR ACTIVIDAD</h6></div>
		<div class="two columns">Seleccionar Actividad</div>
		<div class="four columns">
			<select name="actividad"  class="hyjack" <? if ($id<>NULL) echo "disabled";?>>
				<option value="" selected="selected">Seleccionar</option>
				<?php
				$sql="SELECT act1.descripcion AS nombre1, 
				act1.unidad AS unidad1, 
				cif_bd_concurso.actividad_1 AS cod1, 
				act2.descripcion AS nombre2, 
				act2.unidad AS unidad2, 
				cif_bd_concurso.actividad_2 AS cod2, 
				act3.descripcion AS nombre3, 
				act3.unidad AS unidad3, 
				cif_bd_concurso.actividad_3 AS cod3
			FROM sys_bd_actividad_mrn act1 LEFT JOIN cif_bd_concurso ON act1.cod = cif_bd_concurso.actividad_1
				 LEFT JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
				 LEFT JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
			WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
				$result=mysql_query($sql) or die (mysql_error());
				$f2=mysql_fetch_array($result);
				if ($f2['cod1']<>NULL) echo "<option value='".$f2['cod1']."'>".$f2['nombre1']."</option>";
				if ($f2['cod1']<>NULL) echo "<option value='".$f2['cod2']."'>".$f2['nombre2']."</option>";
				if ($f2['cod1']<>NULL) echo "<option value='".$f2['cod3']."'>".$f2['nombre3']."</option>";
				?>
			</select>
		</div>
		<div class="six columns">
			<input name="Submit2" type="button" class="success button" value="Seleccionar actividad" onClick="this.form.action='gestor_cif_lento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ACTIVIDAD'; this.form.submit()" id="btn_envia"/>
		</div>
	</div>	
<?php
}
?>	
	<div class="row">	
		<div class="twelve columns"><hr/></div>
		<div class="twelve columns"><h6>II.- REGISTRO DE INFORMACION DE PARTICIPANTES</h6></div>
		<div class="two columns">Seleccionar Nombre</div>
		<div class="ten columns">
			<select name="usuario"  class="hyjack">
				<option value="" selected="selected">Seleccionar</option>
				<?php
				if ($id<>NULL and $n_actividad==1)
				{
					$sql="SELECT org_ficha_usuario.nombre, 
					org_ficha_usuario.paterno, 
					org_ficha_usuario.materno, 
					cif_bd_participante_cif.n_documento, 
					cif_bd_ficha_cif.cod_ficha_cif
				FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
					 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
					 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
					 LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
				WHERE cif_bd_concurso.cod_concurso_cif='$cod' AND
				cif_bd_concurso.actividad_1='$id' AND
				cif_bd_ficha_cif.cod_ficha_cif IS NULL
				ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
				}
				elseif ($id<>NULL and $n_actividad==2)
				{
					$sql="SELECT org_ficha_usuario.nombre, 
					org_ficha_usuario.paterno, 
					org_ficha_usuario.materno, 
					cif_bd_participante_cif.n_documento, 
					cif_bd_ficha_cif.cod_ficha_cif
				FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
					 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
					 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
					 LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
				WHERE cif_bd_concurso.cod_concurso_cif='$cod' AND
				cif_bd_concurso.actividad_2='$id' AND
				cif_bd_ficha_cif.cod_ficha_cif IS NULL
				ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
				}
				elseif($id<>NULL and $n_actividad==3)
				{
					$sql="SELECT org_ficha_usuario.nombre, 
					org_ficha_usuario.paterno, 
					org_ficha_usuario.materno, 
					cif_bd_participante_cif.n_documento, 
					cif_bd_ficha_cif.cod_ficha_cif
				FROM cif_bd_concurso INNER JOIN cif_bd_participante_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
					 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
					 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
					 LEFT OUTER JOIN cif_bd_ficha_cif ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento
				WHERE cif_bd_concurso.cod_concurso_cif='$cod' AND
				cif_bd_concurso.actividad_3='$id' AND
				cif_bd_ficha_cif.cod_ficha_cif IS NULL
				ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";					
				}
				else
				{
					$sql="";
				}
				$result=mysql_query($sql) or die (mysql_error());
				while($f1=mysql_fetch_array($result))
				{
				?>
				<option value="<? echo $f1['n_documento'];?>"><? echo $f1['n_documento']." - ".$f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></option>
				<?
				}
				?>
			</select>
		</div>

		<div class="twelve columns"><br/></div>
		<div class="six columns"><h6>A.- Activos físicos antes del CIF.</h6></div>
		<div class="six columns"><h6>B.- Activos físicos logrados con el CIF.</h6></div>
		<div class="three columns">Cantidad</div>
		<div class="three columns">Valor del activo (S/.)</div>
		<div class="three columns">Cantidad</div>
		<div class="three columns">Valor del activo (S/.)</div>
		
		<div class="three columns"><input type="text" name="cantidad_a" class="required number"></div>
		<div class="three columns"><input type="text" name="valor_a" class="required number"></div>
		<div class="three columns"><input type="text" name="cantidad_b" class="required number"></div>
		<div class="three columns"><input type="text" name="valor_b" class="required number"></div>
		
		<div class="six columns"><h6>C.- Calificación</h6></div>
		<div class="six columns"><h6>D.- Premiación</h6></div>
		<div class="three columns">Puntaje</div>
		<div class="three columns">Puesto ocupado</div>
		<div class="three columns">Monto de premio Proyecto (S/.)</div>
		<div class="three columns">Monto de premio Otros (S/.)</div>
		
		<div class="three columns"><input type="text" name="puntaje" class="required number"></div>
		<div class="three columns"><input type="text" name="puesto" class="required number"></div>
		<div class="three columns"><input type="text" name="monto_pdss" class="required number"></div>
		<div class="three columns"><input type="text" name="monto_otro" class="required number"></div>
	</div>

	
	<div class="row">
		<div class="twelve columns">
			<button type="submit" class="button" <? if ($id==NULL) echo "disabled";?>>Guardar cambios</button>
			<a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=calif_lento" class="secondary button">Finalizar</a>
		</div>
	</div>
</form>

<div class="twelve columns"><hr/></div>
  <? include("../plugins/buscar/buscador.html");?>
<table class="responsive" id="lista">
	<thead>
		<tr>
			<th>N.</th>
			<th>Nombre del participante</th>
			<th>Actividad</th>
			<th>Unidad</th>
			<th>Cantidad</th>
			<th>Valor (S/.)</th>
			<th>Cantidad</th>
			<th>Valor(S/.)</th>
			<th>Puntaje</th>
			<th>Puesto</th>
			<th>Sierra Sur II</th>
			<th>Otro</th>
			<th><a class="tiny alert button" href="gestor_cif_lento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $id;?>&action=DELETE_ALL" onclick="return confirm('Va a eliminar permanentemente todos los registros, desea proceder ?')">Eliminar Todo</a></th>
		</tr>
	</thead>
	<tbody>
<?
$num=0;

if ($id<>NULL)
{
	$sql="SELECT org_ficha_usuario.nombre, 
	org_ficha_usuario.paterno, 
	org_ficha_usuario.materno, 
	sys_bd_actividad_mrn.descripcion, 
	sys_bd_actividad_mrn.unidad, 
	cif_bd_ficha_cif.meta_1, 
	cif_bd_ficha_cif.valor_1, 
	cif_bd_ficha_cif.meta_2, 
	cif_bd_ficha_cif.valor_2, 
	cif_bd_ficha_cif.puntaje, 
	cif_bd_ficha_cif.puesto, 
	cif_bd_ficha_cif.premio_pdss, 
	cif_bd_ficha_cif.premio_otro, 
	cif_bd_ficha_cif.cod_ficha_cif
FROM cif_bd_ficha_cif INNER JOIN cif_bd_concurso ON cif_bd_ficha_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif
	 INNER JOIN cif_bd_participante_cif ON cif_bd_participante_cif.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND cif_bd_participante_cif.n_documento = cif_bd_ficha_cif.n_documento
	 INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = cif_bd_ficha_cif.cod_actividad
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
	 INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND cif_bd_participante_cif.cod_concurso_cif = cif_bd_concurso.cod_concurso_cif AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_concurso.cod_concurso_cif='$cod' AND
cif_bd_ficha_cif.cod_actividad='$id'
ORDER BY cif_bd_ficha_cif.cod_actividad ASC, cif_bd_ficha_cif.puntaje DESC, cif_bd_ficha_cif.puesto ASC, org_ficha_usuario.nombre ASC";
}
else
{
	$sql="";
}


$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
	$num++
?>	
		<tr>
			<td><? echo $num;?></td>
			<td><? echo $f3['nombre']." ".$f3['paterno']." ".$f3['materno'];?></td>
			<td><? echo $f3['descripcion'];?></td>
			<td><? echo $f3['unidad'];?></td>
			<td><? echo number_format($f3['meta_1'],2);?></td>
			<td><? echo number_format($f3['valor_1'],2);?></td>
			<td><? echo number_format($f3['meta_2'],2);?></td>
			<td><? echo number_format($f3['valor_2'],2);?></td>
			<td><? echo number_format($f3['puntaje'],2);?></td>
			<td><? echo number_format($f3['puesto']);?></td>
			<td><? echo number_format($f3['premio_pdss'],2);?></td>
			<td><? echo number_format($f3['premio_otro'],2);?></td>
			<td><a href="gestor_cif_lento.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&id=<? echo $id;?>&reg=<? echo $f3['cod_ficha_cif'];?>&action=DELETE_REG" class="tiny alert button">Eliminar</a></td>
		</tr>
<?
}
?>		
	</tbody>
</table>

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

  <script src="../javascripts/app.js"></script>
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>


<!-- Combo Buscador -->
<link href="../plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="../plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="../plugins/combo_buscador/configuracion.js" type="text/javascript"></script>


</body>
</html>
