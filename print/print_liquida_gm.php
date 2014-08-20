<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT
gm_ficha_cierre.n_informe,
gm_ficha_cierre.resultados,
gm_ficha_cierre.problemas,
gm_ficha_cierre.aut_var,
gm_ficha_cierre.aut_muj,
gm_ficha_cierre.aut_jov,
gm_ficha_cierre.dir_var,
gm_ficha_cierre.dir_muj,
gm_ficha_cierre.dir_jov,
gm_ficha_cierre.otr_var,
gm_ficha_cierre.otr_muj,
gm_ficha_cierre.otr_jov,
gm_ficha_cierre.ejec_pdss,
gm_ficha_cierre.ejec_org,
gm_ficha_cierre.ejec_mun,
gm_ficha_cierre.ejec_otro,
gm_ficha_cierre.f_informe,
gm_ficha_evento.cod_ficha_gm,
gm_ficha_evento.n_ficha_gm,
gm_ficha_evento.tema,
gm_ficha_evento.f_inicio,
gm_ficha_evento.f_termino,
gm_ficha_evento.dias,
gm_ficha_evento.f_propuesta,
gm_ficha_evento.f_conformidad,
gm_ficha_evento.participantes,
gm_ficha_evento.n_contrato,
gm_ficha_evento.f_presentacion,
sys_bd_dependencia.cod_dependencia,
sys_bd_dependencia.nombre AS oficina,
sys_bd_dependencia.dni_representante,
sys_bd_tipo_iniciativa.codigo_iniciativa,
sys_bd_personal.n_documento,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_cargo.descripcion AS cargo,
org_ficha_organizacion.n_documento AS rrpp,
org_ficha_organizacion.nombre AS organizacion
FROM
gm_ficha_cierre
INNER JOIN gm_ficha_evento ON gm_ficha_evento.cod_ficha_gm = gm_ficha_cierre.cod_ficha_gm
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = gm_ficha_evento.cod_dependencia
INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = gm_ficha_evento.cod_tipo_iniciativa
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = gm_ficha_cierre.persona_dirigido
INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
INNER JOIN gm_ficha_contratante ON gm_ficha_contratante.cod_ficha_gm = gm_ficha_evento.cod_ficha_gm
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gm_ficha_contratante.cod_tipo_doc AND org_ficha_organizacion.n_documento = gm_ficha_contratante.n_documento
WHERE
gm_ficha_cierre.cod_informe_gm='$cod' AND
gm_ficha_contratante.contratante = 1
";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$codigo=$row['cod_ficha_gm'];

$monto_total=$row['ejec_pdss']+$row['ejec_org']+$row['ejec_mun']+$row['ejec_otro'];


//2.- sacamos los datos de el jefe de oficina
$sql="SELECT
sys_bd_dependencia.dni_representante,
sys_bd_personal.nombre,
sys_bd_personal.apellido,
sys_bd_cargo.descripcion
FROM
sys_bd_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
INNER JOIN sys_bd_cargo ON sys_bd_cargo.cod_cargo = sys_bd_personal.cod_cargo
WHERE
sys_bd_dependencia.cod_dependencia='".$row['cod_dependencia']."'";
$result=mysql_query($sql) or die (mysql_error());
$row_1=mysql_fetch_array($result);

//3.- sacamos los monto programado
//a.- nec pdss
$sql="SELECT
Sum(gm_ficha_presupuesto.costo_total) AS costo
FROM
gm_ficha_presupuesto
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$codigo' AND
gm_ficha_presupuesto.cod_entidad = 1";
$result=mysql_query($sql) or die (mysql_error());
$fila1=mysql_fetch_array($result);

//b.- organizacion
$sql="SELECT
Sum(gm_ficha_presupuesto.costo_total) AS costo
FROM
gm_ficha_presupuesto
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$codigo' AND
gm_ficha_presupuesto.cod_entidad = 2";
$result=mysql_query($sql) or die (mysql_error());
$fila2=mysql_fetch_array($result);

//c.- municipio
$sql="SELECT
Sum(gm_ficha_presupuesto.costo_total) AS costo
FROM
gm_ficha_presupuesto
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$codigo' AND
gm_ficha_presupuesto.cod_entidad = 3";
$result=mysql_query($sql) or die (mysql_error());
$fila3=mysql_fetch_array($result);

//d.- otro
$sql="SELECT
Sum(gm_ficha_presupuesto.costo_total) AS costo
FROM
gm_ficha_presupuesto
WHERE
gm_ficha_presupuesto.cod_ficha_gm='$codigo' AND
gm_ficha_presupuesto.cod_entidad = 4";
$result=mysql_query($sql) or die (mysql_error());
$fila4=mysql_fetch_array($result);

$monto_programado=$fila1['costo']+$fila2['costo']+$fila3['costo']+$fila4['costo'];

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>::Vista Preeliminar::</title>
<!-- cargamos el estilo de la pagina -->
<link href="../stylesheets/print.css" rel="stylesheet" type="text/css">
<style type="text/css">

  @media print 
  {
    .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
  }
</style>
<!-- Fin -->
</head>

<body>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>LIQUIDACION Y PERFECCIONAMIENTO</u><br>GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS<br> N° <? echo numeracion($row['n_informe'])." - ".$row['codigo_iniciativa']." - ".periodo($row['f_informe'])." - ".$row['oficina'];?></div>


<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">A</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%"><? echo $row['nombre']." ".$row['apellido'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">&nbsp;</td>
    <td width="3%" class="txt_titulo">&nbsp;</td>
    <td width="75%" class="txt_titulo"><? echo $row['cargo'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Referencia</td>
    <td class="txt_titulo">:</td>
    <td>Contrato N° <? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_presentacion']);?>-<? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Fecha</td>
    <td class="txt_titulo">:</td>
    <td><? echo $row['oficina'];?>, <? echo traducefecha($row['f_informe']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>
<div class="capa" align="justify">
<p>
En relación al documento de la referencia, informo a su despacho, que la organizacion <? echo $row['organizacion'];?>, ha cumplido con sus obligaciones establecidad en el Contrato de Donación Sujeto a Cargo que estan sustentadas en los siguientes documentos que se adjuntan:
</p>
<ol>
	<li>Informe de Ejecución y Liquidación de la Gira de Aprendizaje e intercambio de Conocimientos.</li>
	<li>Archivo con documentación en ........... folios</li>
</ol>

<p>En virtud de lo cual, esta Jefatura de conformidad al Reglamento de Operaciones, da por LIQUIDADO el Contrato de la referencia por el monto total ejecutado de  S/ <? echo number_format($monto_total,2);?> (<? echo vuelveletra($monto_total);?>)</p>
<p>Por lo expuesto, esta jefatura procede al PERFECCIONAMIENTO de la Donación Sujeto a Cargo por el monto de S/. <? echo number_format($row['ejec_pdss'],2);?>. (<? echo vuelveletra($row['ejec_pdss']);?>) correspondiente al aporte del Proyecto de Desarrollo Sierra Sur II </p>
<p>Por lo indicado, mucho estimaré disponer la baja contable del contrato en referencia.</p>
</div>
<br>
<div class="capa">Atentamente,</div>


<p>&nbsp;</p>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="35%">&nbsp;</td>
    <td width="30%" align="center">___________________</td>
    <td width="35%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center"><? echo $row_1['nombre']." ".$row_1['apellido']."<br>".$row_1['descripcion'];?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo" align="center"><u>INFORME DE EJECUCIÓN Y LIQUIDACIÓN DE CONTRATO</u><BR>
  GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS<BR></div>


<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="28%">N° Contrato</td>
    <td width="2%">:</td>
    <td width="70%">Contrato N° <? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_presentacion']);?>-<? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td>Fecha de Contrato</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_presentacion']);?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">I.- INFORMACION GENERAL</td>
  </tr>
  <tr>
    <td>Tema del evento</td>
    <td>:</td>
    <td><? echo $row['tema'];?></td>
  </tr>
  <tr>
    <td>Fecha de Inicio</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_inicio']);?></td>
  </tr>
  <tr>
    <td>Fecha de término</td>
    <td>:</td>
    <td><? echo traducefecha($row['f_termino']);?></td>
  </tr>
  <tr>
    <td>N° de días efectivos</td>
    <td>:</td>
    <td><? echo $row['dias'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">II.- RESULTADOS Y COMENTARIOS</td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">2.1 Resultados alcanzados</td>
  </tr>
  <tr>
    <td colspan="3" align="justify"><? echo $row['resultados'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">2.2 Comentarios u observaciones</td>
  </tr>
  <tr>
    <td colspan="3" align="justify"><? echo $row['problemas'];?></td>
  </tr>
  <tr>
    <td colspan="3" class="txt_titulo">III.- PARTICIPANTES DEL EVENTO</td>
  </tr>
</table>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="46%" align="center"><strong>ASISTENTES</strong></td>
    <td width="14%" align="center"><strong>VARONES</strong></td>
    <td width="14%" align="center"><strong>MUJERES</strong></td>
    <td width="13%" align="center"><strong>TOTAL</strong></td>
    <td width="13%" align="center"><strong>TOTAL MENORES DE 30 AÑOS</strong></td>
  </tr>
  <tr>
    <td>Autoridades Gubernamentales</td>
    <td align="right"><? echo $row['aut_var'];?></td>
    <td align="right"><? echo $row['aut_muj'];?></td>
    <td align="right"><? echo $row['aut_var']+$row['aut_muj'];?></td>
    <td align="right"><? echo $row['aut_jov'];?></td>
  </tr>
  <tr>
    <td>Representantes de Juntas Directivas</td>
    <td align="right"><? echo $row['dir_var'];?></td>
    <td align="right"><? echo $row['dir_muj'];?></td>
    <td align="right"><? echo $row['dir_var']+$row['dir_muj'];?></td>
    <td align="right"><? echo $row['dir_jov'];?></td>
  </tr>
  <tr>
    <td>Otros</td>
    <td align="right"><? echo $row['otr_var'];?></td>
    <td align="right"><? echo $row['otr_muj'];?></td>
    <td align="right"><? echo $row['otr_var']+$row['otr_muj'];?></td>
    <td align="right"><? echo $row['otr_jov'];?></td>
  </tr>
  <tr>
    <td align="center"><strong>TOTAL</strong></td>
    <td align="right"><? echo $row['aut_var']+$row['dir_var']+$row['otr_var'];?></td>
    <td align="right"><? echo $row['aut_muj']+$row['dir_muj']+$row['otr_muj'];?></td>
    <td align="right"><? echo $row['aut_var']+$row['dir_var']+$row['otr_var']+$row['aut_muj']+$row['dir_muj']+$row['otr_muj'];?></td>
    <td align="right"><? echo $row['aut_jov']+$row['dir_jov']+$row['otr_jov'];?></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo">IV.- ENTIDADES Y LUGARES DE VISITA</td>
  </tr>
</table>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="5%" align="center">N°</td>
    <td width="12%" align="center">FECHA</td>
    <td width="32%">ENTIDAD VISITADA</td>
    <td width="17%" align="center">DEPARTAMENTO</td>
    <td width="17%" align="center">PROVINCIA</td>
    <td width="17%" align="center">DISTRITO</td>
  </tr>
<?
$num=0;
$sql="SELECT
gm_ficha_itinerario.f_itinerario,
gm_ficha_itinerario.lugar,
gm_ficha_itinerario.departamento,
gm_ficha_itinerario.provincia,
gm_ficha_itinerario.distrito
FROM
gm_ficha_itinerario
WHERE
gm_ficha_itinerario.cod_ficha_gm='$codigo'
ORDER BY
gm_ficha_itinerario.f_itinerario ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
	$num++;
?>  
  <tr>
    <td align="center"><? echo $num;?></td>
    <td align="center"><? echo fecha_normal($r1['f_itinerario']);?></td>
    <td><? echo $r1['lugar'];?></td>
    <td align="center"><? echo $r1['departamento'];?></td>
    <td align="center"><? echo $r1['provincia'];?></td>
    <td align="center"><? echo $r1['distrito'];?></td>
  </tr>
<?
}
?>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td class="txt_titulo">V.- ESQUEMA DE COFINANCIAMIENTO</td>
  </tr>
</table>

<table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" bordercolor="#000000" class="mini">
  <tr>
    <td width="57%" align="center"><strong>ENTIDAD</strong></td>
    <td width="17%" align="center"><strong>MONTO PROGRAMADO (S/.)</strong></td>
    <td width="15%" align="center"><strong>MONTO EJECUTADO (S/.)</strong></td>
    <td width="11%" align="center"><strong>%</strong></td>
  </tr>
  <tr>
    <td>SIERRA SUR II</td>
    <td align="right"><? echo number_format($fila1['costo'],2);?></td>
    <td align="right"><? echo number_format($row['ejec_pdss'],2);?></td>
    <td align="right">
    <?
	@$pss=($row['ejec_pdss']/$fila1['costo'])*100;
	echo number_format(@$pss,2);
	?>
    </td>
  </tr>
  <tr>
    <td>ORGANIZACION</td>
    <td align="right"><? echo number_format($fila2['costo'],2);?></td>
    <td align="right"><? echo number_format($row['ejec_org'],2);?></td>
    <td align="right"><?
	@$pog=($row['ejec_org']/$fila2['costo'])*100;
	echo number_format(@$pog,2);
	?></td>
  </tr>
  <tr>
    <td>MUNICIPIO</td>
    <td align="right"><? echo number_format($fila3['costo'],2);?></td>
    <td align="right"><? echo number_format($row['ejec_mun'],2);?></td>
    <td align="right">
	<?
	@$pmun=($row['ejec_mun']/$fila3['costo'])*100;
	echo number_format(@$pmun,2);
	?>
    </td>
  </tr>
  <tr>
    <td>OTRO</td>
    <td align="right"><? echo number_format($fila4['costo'],2);?></td>
    <td align="right"><? echo number_format($row['ejec_otro'],2);?></td>
    <td align="right"><?
	@$potr=($row['ejec_otro']/$fila4['costo'])*100;
	echo number_format(@$potr,2);
	?></td>
  </tr>
  <tr>
    <td><strong>TOTAL</strong></td>
    <td align="right"><? echo number_format($monto_programado,2);?></td>
    <td align="right"><? echo number_format($monto_total,2);?></td>
    <td align="right"><?
	@$ptol=($monto_total/$monto_programado)*100;
	echo number_format(@$ptol,2);
	?></td>
  </tr>
</table>
<br>
<div class="capa" align="right"><? echo $row['oficina'].", ".traducefecha($row['f_informe']);?></div>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>

<div class="capa txt_titulo" align="center">CONFORMIDAD PARA LA BAJA CONTABLE DEL CONTRATO GIRA DE APRENDIZAJE E INTERCAMBIO DE CONOCIMIENTOS</div>


<BR>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="22%" class="txt_titulo">Referencia</td>
    <td width="3%" class="txt_titulo">:</td>
    <td width="75%">Contrato N° <? echo numeracion($row['n_contrato']);?>-<? echo $row['codigo_iniciativa'];?>-<? echo periodo($row['f_presentacion']);?>-<? echo $row['oficina'];?></td>
  </tr>
  <tr>
    <td class="txt_titulo">Organización</td>
    <td class="txt_titulo">:</td>
    <td><? echo $row['organizacion'];?></td>
  </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
</table>
<br>
<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td colspan="2" align="center" class="txt_titulo"><U>PROVEIDO DE CONFORMIDAD</u></td>
  </tr>
  <tr>
    <td colspan="2" align="center" class="txt_titulo">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">RESPONSABLE DEL COMPONENTE</td>
  </tr>
  <tr>
    <td width="52%">Es conforme</td>
    <td width="48%"><input type="checkbox" name="checkbox" id="checkbox"></td>
  </tr>
  <tr>
    <td>Devuelto con observaciones</td>
    <td><input type="checkbox" name="checkbox2" id="checkbox2"></td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>Fecha</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="txt_titulo">ADMINISTRADOR</td>
  </tr>
  <tr>
    <td>Es conforme</td>
    <td><input type="checkbox" name="checkbox3" id="checkbox3"></td>
  </tr>
  <tr>
    <td>Devuelto con observaciones</td>
    <td><input type="checkbox" name="checkbox3" id="checkbox4"></td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td colspan="2">-</td>
  </tr>
  <tr>
    <td>Firma</td>
    <td>Fecha</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><strong><u>LIQUIDACION DEL CONTRATO Y PERFECCIONAMIENTO DE LA DONACION SUJETO A CARGO</u></strong></td>
  </tr>
</table>
<BR>
<div class="capa" align="justify">VISTO EL INFORME DE LIQUIDACION Y PERFECCIONAMIENTO DE LA DONACIÓN CORRESPONDIENTE A LOS DOCUMENTOS DE LA REFERENCIA, ESTANDO A LA CONFORMIDAD DEL RESPONSABLE DE COMPONENTES Y DEL ADMINISTRADOR, EL SUSCRITO DIRECTOR EJECUTIVO DISPONE A LA ADMINISTRACION  LA BAJA CONTABLE DE LA INICIATIVA DE LA REFERENCIA.</div>

<table width="90%" border="0" align="center" cellpadding="1" cellspacing="1">
  <tr>
    <td width="52%">Firma</td>
    <td width="48%">Fecha</td>
  </tr>
</table>

<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../contratos/contrato_gira.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_liquida" class="secondary button oculto">Finalizar</a>

    
    </td>
  </tr>
</table>
</body>
</html>