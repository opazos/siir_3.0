<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row1=mysql_fetch_array($result);

$sql="SELECT * FROM pit_bd_pdn_liquida WHERE cod_ficha_liquida='$id'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//A.- obtengo los datos de ejecución*************************************
//***********************************************************************

//Asistencia Tecnica
$sql="SELECT SUM(ficha_sat.aporte_pdss) AS pdss, 
	SUM(ficha_sat.aporte_org) AS org, 
	SUM(ficha_sat.aporte_otro) AS otro
FROM pit_bd_ficha_pdn INNER JOIN ficha_sat ON pit_bd_ficha_pdn.cod_pdn = ficha_sat.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_sat.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);
$sat_pdss=$r1['pdss'];
$sat_org=$r1['org'];

//Participación en ferias
$sql="SELECT SUM(ficha_pf.aporte_pdss) AS pdss, 
	SUM(ficha_pf.aporte_org) AS org, 
	SUM(ficha_pf.aporte_otro) AS otro
FROM pit_bd_ficha_pdn INNER JOIN ficha_pf ON pit_bd_ficha_pdn.cod_pdn = ficha_pf.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_pf.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);
$fer_pdss=$r2['pdss'];
$fer_org=$r2['org'];

//Visita guiada
$sql="SELECT SUM(ficha_vg.aporte_pdss) AS pdss, 
	SUM(ficha_vg.aporte_org) AS org, 
	SUM(ficha_vg.aporte_otro) AS otro
FROM pit_bd_ficha_pdn INNER JOIN ficha_vg ON pit_bd_ficha_pdn.cod_pdn = ficha_vg.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_vg.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
$vg_pdss=$r3['pdss'];
$vg_org=$r3['org'];

//Apoyo a la gestión
$sql="SELECT SUM(ficha_aag.aporte_pdss) AS pdss, 
	SUM(ficha_aag.aporte_org) AS org, 
	SUM(ficha_aag.aporte_otro) AS otro
FROM pit_bd_ficha_pdn INNER JOIN ficha_aag ON pit_bd_ficha_pdn.cod_pdn = ficha_aag.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = ficha_aag.cod_tipo_iniciativa
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);
$ag_pdss=$r4['pdss'];
$ag_org=$r4['org'];


//B.- Monto programado según contrato ****************************************************
//****************************************************************************************
$sql="SELECT (pit_bd_ficha_pdn.total_apoyo+ 
	pit_bd_ficha_pdn.at_pdss+ 
	pit_bd_ficha_pdn.vg_pdss+ 
	pit_bd_ficha_pdn.fer_pdss) AS aporte_pdss, 
	(pit_bd_ficha_pdn.at_org+ 
	pit_bd_ficha_pdn.vg_org+ 
	pit_bd_ficha_pdn.fer_org) AS aporte_org, 
	pit_bd_ficha_pdn.total_apoyo, 
	pit_bd_ficha_pdn.at_pdss, 
	pit_bd_ficha_pdn.vg_pdss, 
	pit_bd_ficha_pdn.fer_pdss, 
	pit_bd_ficha_pdn.at_org, 
	pit_bd_ficha_pdn.vg_org, 
	pit_bd_ficha_pdn.fer_org
FROM pit_bd_ficha_pdn
WHERE pit_bd_ficha_pdn.cod_pdn='".$row['cod_pdn']."'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);

$total_contrato_pdss=$r5['aporte_pdss'];
$total_ejecutado_pdss=$sat_pdss+$fer_pdss+$vg_pdss+$ag_pdss;
$total_ejecutado_org=$sat_org+$fer_org+$vg_org+$ag_org;

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

  <!-- Con esto no podran dar click atras -->  
  <meta http-equiv="Expires" content="0" />
  <meta http-equiv="Pragma" content="no-cache" />

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
  
  <!-- Con este código deshabilito el click atras -->
  <script type="text/javascript">
  {
  if(history.forward(1))
  location.replace(history.forward(1))
  }
  </script> 
  
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Liquidacion de Planes de Negocio</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_liquida_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="row">
  <div class="twelve columns"><h6>I.- Datos de la iniciativa a liquidar</h6></div>
  <div class="two columns">Iniciativa a liquidar</div>
  <div class="ten columns">
  <input type="hidden" name="iniciativa" value="<? echo $row['cod_pdn'];?>">
  <select name="iniciativa" class="large" disabled="disabled">
    <option value="" selected="selected">Seleccionar</option>
    <?
    $sql="SELECT pit_bd_ficha_pdn.cod_pdn, 
    pit_bd_ficha_pdn.denominacion, 
    org_ficha_organizacion.nombre
    FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
    WHERE org_ficha_organizacion.cod_dependencia='".$row1['cod_dependencia']."'";
    $result=mysql_query($sql) or die (mysql_error());
    while($f1=mysql_fetch_array($result))
    {
    ?>
    <option value="<? echo $f1['cod_pdn'];?>" <? if ($f1['cod_pdn']==$row['cod_pdn']) echo "selected";?>><? echo $f1['nombre']." - ".$f1['denominacion'];?></option>
    <?
    }
    ?>
  </select>    
  </div>
  <div class="two columns">Fecha de liquidación</div>
  <div class="four columns"><input type="date" name="f_liquidacion" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_liquidacion'];?>"></div>
  <div class="two columns">Calificación de la iniciativa</div>
  <div class="four columns">
    <select name="calificacion" class="mini">
    <option value="" selected="selected">Seleccionar</option>
    <option value="1" <? if ($row['cod_calificacion']==1) echo "selected";?>>MALA</option>
    <option value="2" <? if ($row['cod_calificacion']==2) echo "selected";?>>REGULAR</option>
    <option value="3" <? if ($row['cod_calificacion']==3) echo "selected";?>>BUENA</option>
    <option value="4" <? if ($row['cod_calificacion']==4) echo "selected";?>>MUY BUENA</option>
  </select>
  </div>
 <div class="twelve columns"><br/></div> 
  <div class="two columns">Hubo cambios en la lista de participantes?</div>
  <div class="four columns">
    <select name="hc_soc" class="seven">
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
</div>
<div class="row">
  <div class="twelve columns"><h6>II.- Información sobre desembolsos</h6></div>
  <div class="two columns">Fecha de Desembolso</div>
  <div class="four columns"><input type="date" name="f_desembolso" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $row['f_desembolso'];?>"></div>
  <div class="two columns">Nº Cheque y/o C.O</div>
  <div class="four columns"><input type="text" name="n_cheque" class="required five" value="<? echo $row['n_cheque'];?>"></div>
</div> 
<div class="row">
  <div class="twelve columns"><h6>III.- Comentarios u observaciones</h6></div>
  <div class="twelve columns"><textarea name="comentario" rows="5"><? echo $row['comentario'];?></textarea></div> 
</div>
<div class="row">
	<div class="twelve columns"><h6>IV.- Estado de ejecución financiera (<? echo number_format($pp_ejecutado_pdss);?> %)</h6></div>
	<div class="twelve columns"><? if ($pp_ejecutado_pdss<99) echo "<a href='../seguimiento/index.php?SES=".$SES."&anio=".$anio."' class='tiny alert button'>ATENCION: AUN NO A EJECUTADO EL 100% DE LO ENTREGADO, PUEDE TERMINAR DE REGISTRAR LA INFORMACION DE EJECUCION DANDO CLICK AQUI</a>";?></div>
	<div class="twelve columns"><hr/></div>
</div>

<table>
	<thead>
		<tr>
			<th rowspan="2" class="six">Actividad a desarrollar</th>
			<th colspan="2">NEC PDSS II (S/.)</th>
			<th colspan="2">ORGANIZACIÓN (S/.)</th>
		</tr>
		<tr>
			<th>Programado</th>
			<th>Ejecutado</th>
			<th>Programado</th>
			<th>Ejecutado</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Asistencia técnica</td>
			<td><? echo number_format($r5['at_pdss'],2);?></td>
			<td><input type="text" name="total1" class="number" readonly="readonly" value="<? echo $sat_pdss;?>"></td>
			<td><? echo number_format($r5['at_org'],2);?></td>
			<td><input type="text" name="total2" class="number" readonly="readonly" value="<? echo $sat_org;?>"></td>
		</tr>
		<tr>
			<td>Participación en ferias</td>
			<td><? echo number_format($r5['fer_pdss'],2);?></td>
			<td><input type="text" name="total3" class="number" readonly="readonly" value="<? echo $fer_pdss;?>"></td>
			<td><? echo number_format($r5['fer_org'],2);?></td>
			<td><input type="text" name="total4" class="number" readonly="readonly" value="<? echo $fer_org;?>"></td>
		</tr>	
		<tr>
			<td>Visita guiada</td>
			<td><? echo number_format($r5['vg_pdss'],2);?></td>
			<td><input type="text" name="total5" class="number" readonly="readonly" value="<? echo $vg_pdss;?>"></td>
			<td><? echo number_format($r5['vg_org'],2);?></td>
			<td><input type="text" name="total6" class="number" readonly="readonly" value="<? echo $vg_org;?>"></td>
		</tr>	
		<tr>
			<td>Apoyo a la gestión</td>
			<td><? echo number_format($r5['total_apoyo'],2);?></td>
			<td><input type="text" name="total7" class="number" readonly="readonly" value="<? echo $ag_pdss;?>"></td>
			<td></td>
			<td></td>
		</tr>				
	</tbody>
	<tfoot>
		<tr>
			<td>TOTALES</td>
			<td><? echo number_format($r5['aporte_pdss'],2);?></td>
			<td><? echo number_format($total_ejecutado_pdss,2);?></td>
			<td><? echo number_format($r5['aporte_org'],2);?></td>
			<td><? echo number_format($total_ejecutado_org,2);?></td>
		</tr>
	</tfoot>
</table>

	
<!-- Informacion complementaria de Segundo desembolso -->
		<div class="twelve columns"><h6>V.- Activos del Plan de Negocio</h6></div>	
	<table>
		<tr>
			<th>Tipo de activo</th>
			<th class="three">Descripcion</th>
			<th>Valor estimado antes del PDN (S/.)</th>
			<th>Valor con el PDN (S/.)</th>
			<th>Monto de inversion propia en activos (S/.)</th>
			<th>Monto de aporte de otros en activos (S/.)</th>
			<th><br/></th>
		</tr>
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
				<a href="gestor_liquida_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f2['cod_produccion'];?>&action=DELETE_PATRIMONIO" class="tiny alert button">Eliminar</a>
			</td>
		</tr>		
<?
}
?>		
		
		
<?
for($i=1;$i<=5;$i++)
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

<div class="twelve columns"><h6>VI.- Ventas del Plan de Negocio</h6></div>	
<table>
	<tr>
		<th class="three">Producto vendido</th>
		<th>Unidad</th>
		<th>Cantidad vendida antes del PDN</th>
		<th>Valor de ventas antes del PDN</th>
		<th>Cantidad vendida con el PDN</th>
		<th>Valor de ventas con el PDN</th>
		<th><br/></th>
	</tr>
	
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
			<a href="gestor_liquida_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f3['cod_venta'];?>&action=DELETE_VENTA" class="tiny alert button">Eliminar</a>
		</td>
	</tr>
<?
}
?>	
	
<?
for ($i=1;$i<=5;$i++)
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
<div class="twelve columns"><h6>VII.- Costos de Produccion</h6></div>
<div class="twelve columns"><h6>7.1.- Añadir Nuevos Productos</h6></div>
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
<div class="twelve columns"><h6>7.2.- Modificar Productos</h6></div>
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
		<div class="twelve columns"><h6><a href="gestor_liquida_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f3['cod_costo'];?>&action=DELETE_COSTO" class="small alert button">Eliminar registro</a></h6></div>

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
	<a href="pdn_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
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
