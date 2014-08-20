<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);


$sql="SELECT pit_bd_pdn_sd.cod_ficha_sd, 
	pit_bd_pdn_sd.cod_pdn, 
	pit_bd_pdn_sd.f_desembolso, 
	pit_bd_pdn_sd.n_cheque, 
	pit_bd_pdn_sd.hc_soc, 
	pit_bd_pdn_sd.just_soc, 
	pit_bd_pdn_sd.hc_dir, 
	pit_bd_pdn_sd.just_dir, 
	pit_bd_pdn_sd.meses, 
	pit_bd_ficha_pdn.f_presentacion_2, 
	pit_bd_ficha_pdn.n_voucher_2, 
	pit_bd_ficha_pdn.monto_organizacion_2
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_pdn_sd ON pit_bd_ficha_pdn.cod_pdn = pit_bd_pdn_sd.cod_pdn
WHERE pit_bd_pdn_sd.cod_ficha_sd='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);


//obtengo los datos de ejecucion
$sql="SELECT SUM(ficha_sat.aporte_pdss) AS pdss, 
	SUM(ficha_sat.aporte_org) AS org, 
	SUM(ficha_sat.aporte_otro) AS otro
FROM pit_bd_ficha_pdn INNER JOIN ficha_sat ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
$sat_pdss=$r1['pdss'];
$sat_org=$r1['org'];

$sql="SELECT SUM(ficha_pf.aporte_pdss) AS pdss, 
	SUM(ficha_pf.aporte_org) AS org, 
	SUM(ficha_pf.aporte_otro) AS otro
FROM pit_bd_ficha_pdn INNER JOIN ficha_pf ON pit_bd_ficha_pdn.cod_pdn = ficha_pf.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_pf.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);
$fer_pdss=$r2['pdss'];
$fer_org=$r2['org'];

$sql="SELECT SUM(ficha_vg.aporte_pdss) AS pdss, 
	SUM(ficha_vg.aporte_org) AS org, 
	SUM(ficha_vg.aporte_otro) AS otro
FROM pit_bd_ficha_pdn INNER JOIN ficha_vg ON pit_bd_ficha_pdn.cod_pdn = ficha_vg.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
$vg_pdss=$r3['pdss'];
$vg_org=$r3['org'];

$sql="SELECT SUM(ficha_aag.aporte_pdss) AS pdss, 
	SUM(ficha_aag.aporte_org) AS org, 
	SUM(ficha_aag.aporte_otro) AS otro
FROM pit_bd_ficha_pdn INNER JOIN ficha_aag ON pit_bd_ficha_pdn.cod_pdn = ficha_aag.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_aag.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);
$ag_pdss=$r4['pdss'];
$ag_org=$r4['org'];


//Total segun contrato
$sql="SELECT ((pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss)*0.70) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$total_contrato_pdss=$r5['aporte_pdss'];
$total_ejecutado_pdss=$sat_pdss+$fer_pdss+$vg_pdss+$ag_pdss;

$pp_ejecutado_pdss=($total_ejecutado_pdss/$total_contrato_pdss)*100;


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
<dd  class="active"><a href="">Informacion del Plan de Negocio</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">

<form name="form5" method="post" action="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE" onsubmit="return checkSubmit();">
	
	<div class="twelve columns"><h6>Seleccionar Iniciativa</h6></div>
	<div class="twelve columns">
		<select name="pdn" class="hyjack">
			<option value="" selected="selected">Seleccionar</option>
			<?
			$sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
			org_ficha_organizacion.nombre, 
			pit_bd_ficha_pdn.denominacion, 
			pit_bd_ficha_pdn.cod_estado_iniciativa
			FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
			WHERE org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'
			ORDER BY org_ficha_organizacion.nombre ASC";
			$result=mysql_query($sql) or die (mysql_error());
			while($f1=mysql_fetch_array($result))
			{
			?>
			<option value="<? echo $f1['cod_pdn'];?>" <? if ($f1['cod_pdn']==$row['cod_pdn']) echo "selected";?>><? echo $f1['nombre']." / ".$f1['denominacion'];?></option>
			<?
			}
			?>
		</select>
	</div>
	
	<div class="twelve columns"><h6>II.- Información de contrapartida y desembolso</h6></div>
	<div class="two columns">Nº de voucher</div>
	<div class="four columns"><input type="text" name="n_voucher" class="required seven" value="<? echo $row['n_voucher_2'];?>"></div>
	<div class="two columns">Monto depositado por la Organizacion (S/.)</div>
	<div class="four columns"><input type="text" name="monto_org" class="required seven number" value="<? echo $row['monto_organizacion_2'];?>"></div>
	<div class="twelve columns"><h6>III.- Informacion de avance</h6></div>
	<div class="two columns">Fecha de desembolso</div>
	<div class="four columns"><input type="date" name="f_desembolso" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_desembolso'];?>"></div>
	<div class="two columns">Nº de CH/ o C/O</div>
	<div class="four columns"><input type="text" name="n_cheque" class="required seven" value="<? echo $row['n_cheque'];?>"></div>
	<div class="two columns">Nº de meses ejecutados</div>
<div class="four columns"><input type="text" name="mes" class="seven required" value="<? echo $row['meses'];?>"></div>
<div class="two columns">Fecha de presentacion</div>
<div class="four columns"><input type="date" name="f_presentacion" class="seven date required" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_presentacion_2'];?>"></div>

<div class="two columns">Hubo cambios en la lista de participantes?</div>
<div class="four columns">
	<select name="hc_socio" class="seven">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['hc_soc']==1) echo "selected";?>>Si</option>
		<option value="0" <? if ($row['hc_soc']==0) echo "selected";?>>No</option>
	</select>
</div>

<div class="two columns">Si hubo cambios indicar el motivo</div>
<div class="four columns">
	<textarea name="just_soc"><? echo $row['just_soc'];?></textarea>
</div>

<div class="two columns">Hubo cambios en la junta directiva?</div>
<div class="four columns">
	<select name="hc_dir" class="seven">
		<option value="" selected="selected">Seleccionar</option>
		<option value="1" <? if ($row['hc_dir']==1) echo "selected";?>>Si</option>
		<option value="0" <? if ($row['hc_dir']==0) echo "selected";?>>No</option>
	</select>
</div>

<div class="two columns">Si hubo cambios indicar el motivo</div>
<div class="four columns">
	<textarea name="just_dir"><? echo $row['just_dir'];?></textarea>
</div>

<div class="twelve columns"><hr/></div>

<div class="six columns"><h6>III.- EJECUCION FINANCIERA (<? echo number_format($pp_ejecutado_pdss);?> %) <? if ($pp_ejecutado_pdss<69) echo "ERROR: NO LLEGA AL 70% DE EJECUCION";?></h6></div>
	<div class="three columns"><h6>Sierra Sur II</h6></div>
	<div class="three columns"><h6>Organizacion</h6></div>
	<div class="twelve columns"><hr/></div>	
	<div class="six columns">Total ejecutado por Asistencia Tecnica (S/.)</div>
	<div class="three columns"><input type="text" name="total1" class="required five number" value="<? echo $sat_pdss;?>"></div>
	<div class="three columns"><input type="text" name="total2" class="required five number" value="<? echo $sat_org;?>"></div>
	<div class="six columns">Total ejecutado por Participacion en Ferias (S/.)</div>
	<div class="three columns"><input type="text" name="total3" class="required five number" value="<? echo $fer_pdss;?>"></div>
	<div class="three columns"><input type="text" name="total4" class="required five number" value="<? echo $fer_org;?>"></div>	
	<div class="six columns">Total ejecutado por Visita Guiada (S/.)</div>
	<div class="three columns"><input type="text" name="total5" class="required five number" value="<? echo $vg_pdss;?>"></div>
	<div class="three columns"><input type="text" name="total6" class="required five number" value="<? echo $vg_org;?>"></div>	
	<div class="six columns">Total ejecutado por Apoyo a la Gestión (S/.)</div>
	<div class="three columns"><input type="text" name="total7" class="required five number" value="<? echo $ag_pdss;?>"></div>	

<div class="twelve columns"><br/></div>

	

	
<div class="twelve columns"><h6>IV.- Patrimonio del Plan de Negocio</h6></div>	
	<table>
	<thead>
		<tr>
			<th>Tipo de patrimonio</th>
			<th class="three">Descripcion</th>
			<th>Valor estimado antes del PDN (S/.)</th>
			<th>Valor con el PDN (S/.)</th>
			<th>Monto de inversion propia en patrimonio (S/.)</th>
			<th>Monto de aporte de otros en patrimonio (S/.)</th>
			<th><br/></th>
		</tr>
	</thead>	
<?
$sql="SELECT * FROM ficha_activo_pdn WHERE cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
	$cad=$f2['cod_produccion'];
?>
		<tr>
			<td>
				<select name="tipo_activos[<? echo $cad;?>]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT * FROM sys_bd_tipo_activo";
					$result11=mysql_query($sql) or die (mysql_error());
					while($f44=mysql_fetch_array($result11))
					{
					?>
					<option value="<? echo $f44['cod'];?>" <? if ($f44['cod']==$f2['cod_tipo_activo']) echo "selected";?>><? echo $f44['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="descripcions[<? echo $cad;?>]" value="<? echo $f2['descripcion'];?>"></td>
			<td><input type="text" name="valor_as[<? echo $cad;?>]" value="<? echo $f2['valor_a'];?>"></td>
			<td><input type="text" name="valor_bs[<? echo $cad;?>]" value="<? echo $f2['valor_b'];?>"></td>
			<td><input type="text" name="aporte_propios[<? echo $cad;?>]" value="<? echo $f2['inversion_propia'];?>"></td>
			<td><input type="text" name="aporte_otros[<? echo $cad;?>]" value="<? echo $f2['aporte_otro'];?>"></td>
			<td>
			<?
			if ($tipo==1)
			{
			?>
			<a href="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f2['cod_produccion'];?>&tipo=1&action=DELETE_ACTIVO" class="small alert button">Eliminar</a>
			<?
			}
			else
			{
			?>
			<a href="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f2['cod_produccion'];?>&action=DELETE_ACTIVO" class="small alert button">Eliminar</a>
			<?
			}
			?>		
			</td>
		</tr>		
<?
}
?>		
		
		
<?
for($i=1;$i<=10;$i++)
{
?>		
		<tr>
			<td>
				<select name="tipo_activo[]">
					<option value="" selected="selected">Seleccionar</option>
					<?
					$sql="SELECT * FROM sys_bd_tipo_activo";
					$result1=mysql_query($sql) or die (mysql_error());
					while($f45=mysql_fetch_array($result1))
					{
					?>
					<option value="<? echo $f45['cod'];?>"><? echo $f45['descripcion'];?></option>
					<?
					}
					?>
				</select>
			</td>
			<td><input type="text" name="descripcion[]"></td>
			<td><input type="text" name="valor_a[]"></td>
			<td><input type="text" name="valor_b[]"></td>
			<td><input type="text" name="aporte_propio[]"></td>
			<td><input type="text" name="aporte_otro[]"></td>
			<td><br/></td>
		</tr>
<?
}
?>		
	</table>

<div class="twelve columns"><h6>V.- Ventas del Plan de Negocio</h6></div>	
<table>
<thead>
	<tr>
		<th class="three">Producto vendido</th>
		<th>Unidad</th>
		<th>Cantidad vendida antes del PDN</th>
		<th>Valor de ventas antes del PDN</th>
		<th>Cantidad vendida con el PDN</th>
		<th>Valor de ventas con el PDN</th>
		<th><br/></th>
	</tr>
</thead>
	
<?
$sql="SELECT * FROM ficha_ventas_pdn WHERE cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
	$cud=$f3['cod_venta'];
?>	
	<tr>
		<td><input type="text" name="producto_vendidos[<? echo $cud;?>]" value="<? echo $f3['producto'];?>"></td>
		<td><input type="text" name="unidad_productos[<? echo $cud;?>]" value="<? echo $f3['unidad'];?>"></td>
		<td><input type="text" name="cantidad_as[<? echo $cud;?>]" class="number" value="<? echo $f3['cantidad_a'];?>"></td>
		<td><input type="text" name="valoras[<? echo $cud;?>]" class="number" value="<? echo $f3['valor_a'];?>"></td>
		<td><input type="text" name="cantidad_bs[<? echo $cud;?>]" class="number" value="<? echo $f3['cantidad_b'];?>"></td>
		<td><input type="text" name="valorbs[<? echo $cud;?>]" class="number" value="<? echo $f3['valor_b'];?>"></td>
		<td>
		<?
		if ($tipo=1)
		{
		?>
		<a href="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f3['cod_venta'];?>&tipo=1&action=DELETE_VENTA" class="small alert button">Eliminar</a>
		<?
		}
		else
		{
		?>
		<a href="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f3['cod_venta'];?>&action=DELETE_VENTA" class="small alert button">Eliminar</a>
		<?
		}
		?>
		</td>
	</tr>
<?
}
?>	
	
<?
for ($i=1;$i<=10;$i++)
{
?>	
	<tr>
		<td><input type="text" name="producto_vendido[]"></td>
		<td><input type="text" name="unidad_producto[]"></td>
		<td><input type="text" name="cantidad_a[]" class="number"></td>
		<td><input type="text" name="valora[]" class="number"></td>
		<td><input type="text" name="cantidad_b[]" class="number"></td>
		<td><input type="text" name="valorb[]" class="number"></td>
		<td><br/></td>
	</tr>
<?
}
?>	
</table>
<div class="twelve columns"><h6>VI.- Costos de Produccion</h6></div>

<div class="twelve columns"><h6>6.1.- Añadir Nuevos Productos</h6></div>

<!-- Plantilla de costos de produccion -->
<?php
$num=0;
for($i=1;$i<=3;$i++)
{
	$num++
?>
<div class="row">
	<div class="twelve columns"><h6>Producto Final <? echo numeracion($num);?></h6></div>
	<div class="two columns">Producto</div>
	<div class="four columns"><input type="text" name="producto[<? echo $i;?>]" class="seven"></div>
	<div class="two columns">Unidad</div>
	<div class="four columns"><input type="text" name="unidad[<? echo $i;?>]" class="seven"></div>

	<table>
		<thead>
			<tr>
				<th class="seven">Rubros</th>
				<th>Costo de inversión antes del PDN</th>
				<th>Costo de inversión con el PDN</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td colspan="2">I.- Mano de Obra</td>
			</tr>
			<tr>
				<td>- Mano de Obra calificada</td>
				<td><input type="text" name="rubro1a[<? echo $i;?>]"></td>
				<td><input type="text" name="rubro1b[<? echo $i;?>]"></td>
			</tr>
			<tr>
				<td>- Mano de Obra no calificada</td>
				<td><input type="text" name="rubro2a[<? echo $i;?>]"></td>
				<td><input type="text" name="rubro2b[<? echo $i;?>]"></td>
			</tr>	
			<tr>
				<td>II.- Insumos/Materiales</td>
				<td><input type="text" name="rubro3a[<? echo $i;?>]"></td>
				<td><input type="text" name="rubro3b[<? echo $i;?>]"></td>
			</tr>
			<tr>
				<td>III.- Servicios</td>
				<td><input type="text" name="rubro4a[<? echo $i;?>]"></td>
				<td><input type="text" name="rubro4b[<? echo $i;?>]"></td>
			</tr>	
			<tr>
				<td>IV.- Otros</td>
				<td><input type="text" name="rubro5a[<? echo $i;?>]"></td>
				<td><input type="text" name="rubro5b[<? echo $i;?>]"></td>
			</tr>										
		</tbody>

	</table>
	<div class="twelve columns"><hr/></div>	
</div>
<?php
}
?>
<div class="twelve columns"><h6>6.2.- Modificar Productos</h6></div>
<?php
$na=$num;
$sql="SELECT * FROM ficha_costo_produccion_pdn WHERE cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
	$cad=$f3['cod_costo'];
	$na++
?>
<div class="row">
	<div class="twelve columns"><h6>Producto Final <? echo numeracion($na);?></h6></div>
	<div class="two columns">Producto</div>
	<div class="four columns"><input type="text" name="productos[<? echo $cad;?>]" class="seven" value="<? echo $f3['producto'];?>"></div>
	<div class="two columns">Unidad</div>
	<div class="four columns"><input type="text" name="unidads[<? echo $cad;?>]" class="seven" value="<? echo $f3['unidad'];?>"></div>
	<div class="twelve columns"><h6><a href="gestor_pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f3['cod_costo'];?>&tipo=<? echo $tipo;?>&action=DELETE_COSTO" class="small alert button">Eliminar registro</a></h6></div>

	<table>
		<thead>
			<tr>
				<th class="seven">Rubros</th>
				<th>Costo de inversión antes del PDN</th>
				<th>Costo de inversión con el PDN</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td colspan="2">I.- Mano de Obra</td>
			</tr>
			<tr>
				<td>- Mano de Obra calificada</td>
				<td><input type="text" name="rubro1as[<? echo $cad;?>]" value="<? echo $f3['costo_a'];?>"></td>
				<td><input type="text" name="rubro1bs[<? echo $cad;?>]" value="<? echo $f3['costo_b'];?>"></td>
			</tr>
			<tr>
				<td>- Mano de Obra no calificada</td>
				<td><input type="text" name="rubro2as[<? echo $cad;?>]" value="<? echo $f3['costo_c'];?>"></td>
				<td><input type="text" name="rubro2bs[<? echo $cad;?>]" value="<? echo $f3['costo_d'];?>"></td>
			</tr>	
			<tr>
				<td>II.- Insumos/Materiales</td>
				<td><input type="text" name="rubro3as[<? echo $cad;?>]" value="<? echo $f3['costo_e'];?>"></td>
				<td><input type="text" name="rubro3bs[<? echo $cad;?>]" value="<? echo $f3['costo_f'];?>"></td>
			</tr>
			<tr>
				<td>III.- Servicios</td>
				<td><input type="text" name="rubro4as[<? echo $cad;?>]" value="<? echo $f3['costo_g'];?>"></td>
				<td><input type="text" name="rubro4bs[<? echo $cad;?>]" value="<? echo $f3['costo_h'];?>"></td>
			</tr>	
			<tr>
				<td>IV.- Otros</td>
				<td><input type="text" name="rubro5as[<? echo $cad;?>]" value="<? echo $f3['costo_i'];?>"></td>
				<td><input type="text" name="rubro5bs[<? echo $cad;?>]" value="<? echo $f3['costo_j'];?>"></td>
			</tr>										
		</tbody>

	</table>
	<div class="twelve columns"><hr/></div>	
</div>
<?php	
}
?>



















	
	
	
	
	
	<div class="twelve columns"><br/></div>
	<div class="twelve columns">
		<button type="submit" class="success button" id="btn_envia">Guardar cambios</button>
		<button type="button" class="secondary button" onclick="document.form5.btn_envia.disabled=!document.form5.btn_envia.disabled">Desbloquear</button>
		<a href="pdn_seg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
	</div>	
	
	
</form>



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
