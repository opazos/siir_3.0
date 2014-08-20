<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();



//1.- Información del PDN
$sql="SELECT
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.cod_tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
pit_bd_ficha_pdn.denominacion,
org_ficha_taz.nombre AS taz,
org_ficha_organizacion.f_creacion,
org_ficha_organizacion.f_formalizacion,
sys_bd_tipo_org.descripcion,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
sys_bd_distrito.latitud,
sys_bd_distrito.longitud,
sys_bd_dependencia.nombre AS oficina,
sys_bd_linea_pdn.descripcion AS linea,
pit_bd_ficha_pdn.f_presentacion,
pit_bd_ficha_pdn.mes,
pit_bd_ficha_pdn.f_termino,
pit_bd_ficha_pdn.total_apoyo,
pit_bd_ficha_pdn.at_pdss,
pit_bd_ficha_pdn.at_org,
pit_bd_ficha_pdn.vg_pdss,
pit_bd_ficha_pdn.vg_org,
pit_bd_ficha_pdn.fer_pdss,
pit_bd_ficha_pdn.fer_org,
org_ficha_taz.n_documento AS n_doc
FROM
pit_bd_ficha_pdn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
INNER JOIN sys_bd_linea_pdn ON sys_bd_linea_pdn.cod_linea_pdn = pit_bd_ficha_pdn.cod_linea_pdn
WHERE
pit_bd_ficha_pdn.cod_pdn = '$id'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Busco los participantes
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS participantes
FROM
pit_bd_user_iniciativa
WHERE
pit_bd_user_iniciativa.cod_tipo_iniciativa = 4 AND
pit_bd_user_iniciativa.cod_iniciativa = '$id'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$sql="SELECT Count(pit_bd_user_iniciativa.n_documento) AS participantes
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_ficha_pdn.cod_pdn = pit_bd_user_iniciativa.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = pit_bd_user_iniciativa.cod_tipo_iniciativa
	 INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE pit_bd_ficha_pdn.cod_pdn='$id' AND
pit_bd_user_iniciativa.momento=1 AND
org_ficha_usuario.titular=1";
$result=mysql_query($sql) or die (mysql_error());
$r14=mysql_fetch_array($result);

//Busco la info del PDN
$sql="SELECT
pit_bd_info_pdn.apoyo,
pit_bd_info_pdn.situacion_actual,
pit_bd_info_pdn.situacion_futura,
pit_bd_info_pdn.participo_evento,
pit_bd_info_pdn.describe_evento,
pit_bd_info_pdn.ambiente_contra,
pit_bd_info_pdn.ambiente_pro,
pit_bd_info_pdn.problema_negocio,
pit_bd_info_pdn.venta_actual,
pit_bd_info_pdn.nivel_venta_1,
pit_bd_info_pdn.nivel_venta_2,
pit_bd_info_pdn.nivel_venta_3,
pit_bd_info_pdn.nivel_venta_4,
pit_bd_info_pdn.nivel_venta_5,
pit_bd_info_pdn.nivel_venta_6,
pit_bd_info_pdn.propuesta_venta
FROM
pit_bd_info_pdn
WHERE
pit_bd_info_pdn.cod_pdn = '$id'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

//Verifico si tiene contrato Pit
$sql="SELECT
pit_bd_ficha_pdn.cod_pdn,
pit_bd_ficha_pit.f_contrato
FROM
pit_bd_ficha_pdn
INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = pit_bd_ficha_pdn.cod_pit
WHERE
pit_bd_ficha_pdn.cod_pdn = '$id'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);
$total_contrato=mysql_num_rows($result);




?>
<!DOCTYPE html><html>
  <head>
    <meta content="text/html;charset=UTF-8" http-equiv="Content-Type">
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
  <p>&nbsp;</p>
  <div class="gran_titulo capa centrado">PLAN DE NEGOCIO</div>
  
  <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr>
      <td width="26%">Nombre de la Organización Territorial </td>
      <td width="2%">:</td>
      <td width="72%"><? echo $row['taz'];?></td>
    </tr>
    <tr>
      <td>Nombre de la Organización de Plan de Negocio </td>
      <td>:</td>
      <td><? echo $row['nombre'];?></td>
    </tr>
  </table>

  <p>&nbsp;</p>
   <p>&nbsp;</p>
    <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr>
      <td class="centrado txt_titulo">DENOMINACIÓN DEL PLAN DE NEGOCIO </td>
    </tr>
    <tr class="gran_titulo centrado">
      <td><? echo $row['denominacion'];?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
   <p>&nbsp;</p>
    <p>&nbsp;</p>
     <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr>
      <td width="26%">Duración del Plan de Negocio</td>
      <td width="2%">:</td>
      <td width="72%"><? echo $row['mes'];?> meses</td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <div class="capa">LUGAR Y FECHA : <? echo $row['oficina'];?>, <? echo traducefecha($row['f_presentacion']);?></div>
  <H1 class=SaltoDePagina> </H1>
    <? include("encabezado.php");?>
  
  
  
  <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr class="txt_titulo">
      <td colspan="3">I.- DATOS DE LA ORGANIZACIÓN RESPONSABLE DEL PDN </td>
    </tr>
    <tr>
      <td width="24%" class="txt_titulo">Nombre de la Organización </td>
      <td width="1%">:</td>
      <td width="75%"><? echo $row['nombre'];?></td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3"> Situación Legal </td>
    </tr>
    <tr>
      <td>Tipo de Documento </td>
      <td>:</td>
      <td><? echo $row['tipo_doc'];?></td>
    </tr>
    <tr>
      <td>N° de documento </td>
      <td>:</td>
      <td><? echo $row['n_documento'];?></td>
    </tr>
    <tr>
      <td>Fecha de Inscripción a Registros Públicos</td>
      <td>:</td>
      <td><? echo fecha_normal($row['f_formalizacion']);?></td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3"> Relación de Directivos </td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr class="centrado txt_titulo">
      <td>CARGO</td>
      <td>NOMBRES Y APELLIDOS</td>
      <td>DNI</td>
      <td>SEXO</td>
      <td>FECHA DE <br>NACIMIENTO</td>
      <td>VIGENCIA HASTA</td>
    </tr>
<?
$sql="SELECT
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_usuario.n_documento,
org_ficha_usuario.f_nacimiento,
org_ficha_usuario.sexo,
sys_bd_cargo_directivo.descripcion AS cargo,
org_ficha_directivo.f_termino
FROM
org_ficha_organizacion
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = org_ficha_directivo.cod_tipo_doc AND org_ficha_usuario.n_documento = org_ficha_directivo.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
WHERE
org_ficha_organizacion.cod_tipo_doc='".$row['cod_tipo_doc']."' AND
org_ficha_organizacion.n_documento='".$row['n_documento']."'
ORDER BY
org_ficha_directivo.cod_cargo ASC,
org_ficha_directivo.f_termino ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r1=mysql_fetch_array($result))
{
?>	
    <tr>
      <td class="centrado"><? echo $r1['cargo'];?></td>
      <td><? echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?></td>
      <td class="centrado"><? echo $r1['n_documento'];?></td>
      <td class="centrado"><? if ($r1['sexo']==1) echo "Masculino"; else echo "Femenino";?></td>
      <td class="centrado"><? echo fecha_normal($r1['f_nacimiento']);?></td>
      <td class="centrado"><? echo fecha_normal($r1['f_termino']);?></td>
    </tr>
<?
}
?>	
  </table>
  <BR>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr>
      <td width="24%">N° de Participantes del Plan de Negocio </td>
      <td width="1%">:</td>
      <td width="75%"><? echo number_format($r2['participantes']);?></td>
    </tr>
    <tr>
      <td width="24%">N° de Familias que conforman el Plan de Negocio </td>
      <td width="1%">:</td>
      <td width="75%"><? echo number_format($r14['participantes']);?></td>
    </tr>
  </table>
    <H1 class=SaltoDePagina> </H1>
    <? include("encabezado.php");?>
 <div class="capa txt_titulo">II.- DATOS DEL NEGOCIO</div>
 <br>
 <div class="capa txt_titulo">2.1 Denominación del Plan de Negocio</div>
 <div class="capa"><?php  echo $row['denominacion'];?></div>   
 <br>
 <div class="capa txt_titulo">2.2 Situación actual y Perspectivas del Negocio</div>
 <br>
 <table class="capa mini" cellpading="1" cellspacing="1" border="1">
 <tr class="txt_titulo centrado">
 <td width="20%">N°</td>
 <td width="40%">SITUACION ACTUAL</td>
 <td width="40%">QUE ESPERAN DEL NEGOCIO</td>
 </tr>
 <?php 
 $num=0;
 $sql="SELECT * FROM pdn_situacion WHERE cod_pdn='$id'";
 $result=mysql_query($sql) or die (mysql_error());
 while($r6=mysql_fetch_array($result))
 {
 	$num++
 ?>
 <tr>
 <td class="centrado"><?php  echo $num;?></td>
 <td><?php  echo $r6['situacion_a'];?></td>
 <td><?php  echo $r6['situacion_b'];?></td>
 </tr>
 <?php 
 }
 ?>
 </table>
 <br>
 <div class="capa txt_titulo">2.3 Apoyo recibido en los 3 últimos años</div>
 <br>
  <table class="capa mini" cellpading="1" cellspacing="1" border="1">
  <tr class="centrado txt_titulo">
  <td width="10%">N°</td>
  <td width="40%">INSTITUCION</td>
  <td width="10%">TIEMPO DE APOYO (MESES)</td>
  <td width="40%">TIPO DE APOYO</td>
  </tr>
 <?php 
 $num1=0;
 $sql="SELECT * FROM pdn_apoyo WHERE cod_pdn='$id'";
 $result=mysql_query($sql) or die (mysql_error());
 while($r7=mysql_fetch_array($result))
 {
 	$num1++
 ?> 
  <tr>
  <td class="centrado"><?php  echo $num1;?></td>
  <td><?php  echo $r7['institucion'];?></td>
  <td class="centrado"><?php  echo $r7['mes'];?></td>
  <td><?php  echo $r7['tipo_apoyo'];?></td>
  </tr>
 <?php 
 }
 ?> 
  </table>
 <br>
 <div class="capa txt_titulo">2.4 Eventos comerciales en los que a participado durante el ultimo año</div>
 <br>
  <table class="capa mini" cellpading="1" cellspacing="1" border="1">
  <tr class="centrado txt_titulo">
  <td width="20%">N°</td>
  <td width="40%">LUGAR</td>
  <td width="40%">NOMBRE DEL EVENTO</td>
  </tr>
  <?php 
  $num2=0;
  $sql="SELECT * FROM pdn_evento WHERE cod_pdn='$id'";
  $result=mysql_query($sql) or die (mysql_error());
  while($r8=mysql_fetch_array($result))
  {
  	$num2++
  ?>
  <tr>
  <td class="centrado"><?php  echo $num2;?></td>
  <td><?php  echo $r8['lugar'];?></td>
  <td><?php echo $r8['evento'];?></td>
  </tr>
 <?php 
  }
  ?> 
  </table>
 <br>
 <div class="capa txt_titulo">2.5 La actividad del negocio y el medio ambiente</div>
 <br>
 <?php 
 $sql="SELECT * FROM pdn_ambiente WHERE cod_pdn='$id'";
 $result=mysql_query($sql) or die (mysql_error());
 $r9=mysql_fetch_array($result);
 ?>
  <table class="capa mini" cellpading="1" cellspacing="1" border="1">
  			<tr class="txt_titulo centrado">
				<td>EFECTOS AMBIENTALES</td>
				<td>ATRIBUTOS</td>
				<td>MARCAR</td>
				<td>BREVE EXPLICACIÓN DEL ATRIBUTO</td>
			</tr>
			<tr>
			<td rowspan="2" class="centrado">Efectos Positivos</td>
			<td>La Actividad de negocio esta contribuyendo favorablemente a la protección y cuidado del medio ambiente</td>
			<td class="centrado"><?php  if ($r9['opcion_1']==1) echo "X";?></td>
			<td><?php  echo $r9['descripcion_1'];?></td>
		</tr>
		<tr>
			<td>La actividad de negocio se está beneficiando de las buenas prácticas de manejo ambiental de otros</td>
			<td class="centrado"><?php  if ($r9['opcion_2']==1) echo "X";?></td>
			<td><?php  echo $r9['descripcion_2'];?></td>
		</tr>
		
		<tr>
			<td rowspan="2" class="centrado">Efectos Negativos</td>
			<td>La actividad de negocio esta afectando negativamente el medio ambiente</td>
			<td class="centrado"><?php  if ($r9['opcion_3']==1) echo "X";?></td>
			<td><?php  echo $r9['descripcion_3'];?></td>
		</tr>
		<tr>
			<td>La actividad de negocio se perjudica de las malas prácticas de manejo ambiental de otros</td>
			<td class="centrado"><?php  if ($r9['opcion_4']==1) echo "X";?></td>
			<td><?php  echo $r9['descripcion_4'];?></td>
		</tr>		
  </table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo">2.6 Situación patrimonial de la Organización o de la persona natural</div>
<br>
 <table class="capa mini" cellpading="1" cellspacing="1" border="1">
 <tr class="centrado txt_titulo">
 <td>N°</td>
 <td>TIPO</td>
 <td>DESCRIPCION</td>
 <td>UNIDAD</td>
 <td>CANTIDAD</td>
 <td>COSTO UNITARIO ESTIMADO (S/.)</td>
 <td>VALOR TOTAL ESTIMADO (S/.)</td>
 </tr>
 <?php 
 $num3=0;
 $sql="SELECT * FROM pdn_patrimonio WHERE cod_pdn='$id' ORDER BY tipo_patrimonio ASC";
 $result=mysql_query($sql) or die (mysql_error());
 while($r10=mysql_fetch_array($result))
 {
 	$sum=$r10['costo_total'];
 	$total_sum=$total_sum+$sum;
 	
 	$num3++
 ?>
 <tr>
 <td class="centrado"><?php  echo $num3;?></td>
 <td><?php  if ($r10['tipo_patrimonio']==1) echo "TERRENOS DE USO DEL NEGOCIO"; elseif($r10['tipo_patrimonio']==2) echo "CONSTRUCCIONES/INSTALACIONES"; elseif($r10['tipo_patrimonio']==3) echo "MAQUINARIA, EQUIPO, HERRAMIENTAS"; elseif($r10['tipo_patrimonio']==4) echo "GANADO REPRODUCTOR"; elseif($r10['tipo_patrimonio']==5) echo "MATERIA PRIMA/INSUMOS"; else echo "OTRO";?></td>
 <td><?php  echo $r10['descripcion'];?></td>
 <td class="centrado"><?php  echo $r10['unidad'];?></td>
 <td class="derecha"><?php  echo number_format($r10['cantidad'],2);?></td>
 <td class="derecha"><?php  echo number_format($r10['costo_unitario'],2);?></td>
 <td class="derecha"><?php  echo number_format($r10['costo_total'],2);?></td>
 </tr>
 <?php 
 }
 ?>
 <tr class="txt_titulo">
 <td colspan="6">TOTAL</td>
 <td class="derecha"><?php  echo number_format($total_sum,2);?></td>
 </tr>
 </table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
<div class="capa txt_titulo">III.- PROBLEMAS O LIMITACIONES Y PROPUESTAS DE SOLUCION</div>
<br></br>
<table class="capa mini" cellpadding="1" cellspacing="1" border="1">
<tr class="centrado txt_titulo">
<td>TIPO</td>
<td>DESCRIPCION DEL PROBLEMA</td>
<td>DESCRIPCION DE LA SOLUCION</td>
</tr>
<?php 
$sql="SELECT * FROM pdn_problema WHERE cod_pdn='$id' ORDER BY tipo ASC";
$result=mysql_query($sql) or die (mysql_error());
while($r11=mysql_fetch_array($result))
{
?>
<tr>
<td><?php  if ($r11['tipo']==1) echo "PROVISION DE MATERIAS PRIMAS";elseif($r11['tipo']==2) echo "PROCESO PRODUCTIVO"; elseif($r11['tipo']==3) echo "COMERCIALIZACIÓN"; else echo "OTROS PROBLEMAS";?></td>
<td><?php echo $r11['descripcion'];?></td>
<td><?php  echo $r11['solucion'];?></td>
</tr>
<?php 
}
?>
</table>






<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>
  <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr class="txt_titulo">
      <td>IV. REQUERIMIENTO DE ASISTENCIA TECNICA </td>
    </tr>
    <tr>
      <td>Especificaciones, Costo y Financiamiento </td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr class="centrado txt_titulo">
      <td rowspan="2">RUBRO DE ASISTENCIA TECNICA </td>
      <td rowspan="2">RESULTADO ESPERADO </td>
      <td rowspan="2">ESPECIALISTA</td>
      <td colspan="4">TIEMPO Y COSTO </td>
      <td colspan="3">APORTE EN S/. </td>
      <td colspan="12">CRONOGRAMA DE EJECUCION DE LOS SERVICIOS DE ASISTENCIA TECNICA </td>
    </tr>
    <tr class="centrado txt_titulo">
      <td>N° días a la semana </td>
      <td>Costo por día en S/. </td>
      <td>N° semanas al mes </td>
      <td>N° meses </td>
      <td>SS II </td>
      <td>Socios</td>
      <td>Total</td>
      <td>Ene</td>
      <td>Feb</td>
      <td>Mar</td>
      <td>Abr</td>
      <td>May</td>
      <td>Jun</td>
      <td>Jul</td>
      <td>Ago</td>
      <td>Sep</td>
      <td>Oct</td>
      <td>Nov</td>
      <td>Dic</td>
    </tr>
<?
$sql="SELECT
pit_bd_at_pdn.cod_at,
pit_bd_at_pdn.rubro,
pit_bd_at_pdn.resultado,
pit_bd_at_pdn.rubro_especialista,
pit_bd_at_pdn.n_dia,
pit_bd_at_pdn.costo_dia,
pit_bd_at_pdn.n_semana,
pit_bd_at_pdn.n_mes,
pit_bd_at_pdn.aporte_total,
pit_bd_at_pdn.aporte_pdss,
pit_bd_at_pdn.aporte_org,
pit_bd_at_pdn.ene,
pit_bd_at_pdn.feb,
pit_bd_at_pdn.mar,
pit_bd_at_pdn.abr,
pit_bd_at_pdn.may,
pit_bd_at_pdn.jun,
pit_bd_at_pdn.jul,
pit_bd_at_pdn.ago,
pit_bd_at_pdn.sep,
pit_bd_at_pdn.oct,
pit_bd_at_pdn.nov,
pit_bd_at_pdn.dic,
pit_bd_at_pdn.cod_pdn
FROM
pit_bd_at_pdn
WHERE
pit_bd_at_pdn.cod_pdn = '$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r4=mysql_fetch_array($result))
{

$sierra_sur=$r4['aporte_pdss'];
$total_sierrasur=$total_sierrasur+$sierra_sur;

$socio=$r4['aporte_org'];
$total_socio=$total_socio+$socio;

$total=$r4['aporte_pdss']+$r4['aporte_org'];
$suma_total=$suma_total+$total;

?>	
    <tr>
      <td><? echo $r4['rubro'];?></td>
      <td><? echo $r4['resultado'];?></td>
      <td><? echo $r4['rubro_especialista'];?></td>
      <td class="centrado"><? echo number_format($r4['n_dia']);?></td>
      <td class="derecha"><? echo number_format($r4['costo_dia'],2);?></td>
      <td class="centrado"><? echo number_format($r4['n_semana']);?></td>
      <td class="centrado"><? echo number_format($r4['n_mes']);?></td>
      <td class="derecha"><? echo number_format($r4['aporte_pdss'],2);?></td>
      <td class="derecha"><? echo number_format($r4['aporte_org'],2);?></td>
      <td class="derecha"><? echo number_format($r4['aporte_pdss']+$r4['aporte_org'],2);?></td>
      <td class="centrado"><? if ($r4['ene']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['feb']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['mar']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['abr']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['may']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['jun']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['jul']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['ago']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['sep']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['oct']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['nov']==1) echo "X";?></td>
      <td class="centrado"><? if ($r4['dic']==1) echo "X";?></td>
    </tr>
<?
}
?>	
    <tr class="txt_titulo">
      <td colspan="7">TOTAL ASISTENCIA TÉCNICA </td>
      <td class="derecha"><? echo number_format($total_sierrasur,2);?></td>
      <td class="derecha"><? echo number_format($total_socio,2);?></td>
      <td class="derecha"><? echo number_format($suma_total,2);?></td>
      <td colspan="12" rowspan="2" bgcolor="#333333">&nbsp;</td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="7">PROCENTAJE</td>
      <td class="derecha">
	  <?
	  $ss=($total_sierrasur/$suma_total)*100;
	  echo number_format($ss,2);
	  ?>	  </td>
      <td class="derecha">
	  <?
	  $so=($total_socio/$suma_total)*100;
	  echo number_format($so,2);
	  ?>	  </td>
      <td class="derecha">100.00</td>
    </tr>
  </table>
<H1 class=SaltoDePagina> </H1>
<? include("encabezado.php");?>         
<div class="capa txt_titulo">V.- PROPUESTA DE VISITA GUIADA O PASANTIA PARA MEJORAR CAPACIDADES</div>
<br/>
<table class="capa mini" cellpadding="1" cellspacing="1" border="1">
<tr class="centrado txt_titulo">
<td>N°</td>
<td>FECHA DE VISITA</td>
<td>LUGARES DE VISITA</td>
<td>QUE ESPERAN APRENDER</td>
<td>N° PARTICIPANTES (aprox.)</td>
<td>COSTO APROX (S/.)</td>
</tr>
<?php 
$num4=0;
$sql="SELECT * FROM pit_bd_visita_pdn WHERE cod_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r12=mysql_fetch_array($result))
{
	$num4++
?>
<tr>
<td class="centrado"><?php  echo $num4;?></td>
<td class="centrado"><?php  echo fecha_normal($r12['f_visita']);?></td>
<td><?php  echo $r12['itinerario'];?></td>
<td><?php  echo $r12['resultados'];?></td>
<td class="centrado"><?php  echo $r12['participantes'];?></td>
<td class="derecha"><?php  echo number_format($r12['aporte_total'],2);?></td>
</tr>
<?php 
}
?>
</table>
<br></br>
<div class="capa txt_titulo">VI.- PROPUESTA DE PARTICIPACION EN FERIAS</div>
<br/>
<table class="capa mini" cellpadding="1" cellspacing="1" border="1">
<tr class="centrado txt_titulo">
<td>N°</td>
<td>NOMBRE DE LA FERIA</td>
<td>FECHA DE REALIZACION</td>
<td>LUGAR</td>
<td>N° PARTICIPANTES (aprox.)</td>
<td>COSTO APROX (S/.)</td>
</tr>
<?php 
$num5=0;
$sql="SELECT * FROM pit_bd_feria_pdn WHERE cod_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
while($r13=mysql_fetch_array($result))
{
	$num5++
?>
<tr>
<td class="centrado"><?php  echo $num5;?></td>
<td><?php  echo $r13['nombre'];?></td>
<td class="centrado"><?php  echo fecha_normal($r13['f_realizacion']);?></td>
<td><?php echo $r13['lugar'];?></td>
<td class="centrado"><?php  echo $r13['participantes'];?></td>
<td class="derecha"><?php  echo number_format($r13['aporte_total'],2);?></td>
</tr>
<?php 
}
?>
</table>





          
          
          
          
          
          
          
          
<H1 class=SaltoDePagina> </H1>          
    <? include("encabezado.php");?>
  <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr class="txt_titulo">
      <td colspan="3">VII.- PRESUPUESTO Y APORTES PARA EJECUTAR EL PLAN DE NEGOCIO (S/.) </td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3"><u>REFERENCIAS</u></td>
    </tr>
    <tr>
      <td width="26%">Organización Territorial </td>
      <td width="2%">:</td>
      <td width="72%"><? echo $row['taz'];?></td>
    </tr>
    <tr>
      <td>Organización del Plan de Negocio </td>
      <td>:</td>
      <td><? echo $row['nombre'];?></td>
    </tr>
    <tr>
      <td colspan="3"><hr></td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr class="centrado txt_titulo">
      <td>CONCEPTO</td>
      <td>APORTE SIERRA SUR II</td>
      <td>%</td>
      <td>APORTE SOCIOS</td>
      <td>%</td>
      <td>TOTAL</td>
      <td>%</td>
    </tr>
    <?php 
    $total_at=$row['at_pdss']+$row['at_org'];
    @$p_at_ss=$row['at_pdss']/$total_at*100;
    @$p_at_org=$row['at_org']/$total_at*100;
    
    $total_vg=$row['vg_pdss']+$row['vg_org'];
    @$p_vg_ss=$row['vg_pdss']/$total_vg*100;
    @$p_vg_org=$row['vg_org']/$total_vg*100;
    
    $total_fer=$row['fer_pdss']+$row['fer_org'];
    @$p_fer_ss=$row['fer_pdss']/$total_fer*100;
    @$p_fer_org=$row['fer_org']/$total_fer*100;
    
    ?>
    <tr>
      <td>I.- ASISTENCIA TÉCNICA </td>
      <td class="derecha"><? echo number_format($row['at_pdss'],2);?></td>
      <td class="derecha"><?php  echo number_format(@$p_at_ss,2);?></td>
      <td class="derecha"><? echo number_format($row['at_org'],2);?></td>
      <td class="derecha"><?php  echo number_format(@$p_at_org,2);?></td>
      <td class="derecha"><? echo number_format($total_at,2);?></td>
      <td class="derecha">100.00</td>
    </tr>
    <tr>
      <td>II.- VISITA GUIADA </td>
      <td class="derecha"><? echo number_format($row['vg_pdss'],2);?></td>
      <td class="derecha"><?php  echo number_format(@$p_vg_ss,2);?></td>
      <td class="derecha"><? echo number_format($row['vg_org'],2);?></td>
      <td class="derecha"><?php  echo number_format(@$p_vg_org,2);?></td>
      <td class="derecha"><? echo number_format($total_vg,2);?></td>
      <td class="derecha">100.00</td>
    </tr>
    <tr>
      <td>III.- PARTICIPACIÓN EN FERIAS </td>
      <td class="derecha"><? echo number_format($row['fer_pdss'],2);?></td>
      <td class="derecha"><?php  echo number_format(@$p_fer_ss,2);?></td>
      <td class="derecha"><? echo number_format($row['fer_org'],2);?></td>
      <td class="derecha"><?php  echo number_format(@$p_fer_org,2);?></td>
      <td class="derecha"><? echo number_format($total_fer,2);?></td>
      <td class="derecha">100.00</td>
    </tr>
    <tr>
      <td>V.- APOYO A LA GESTIÓN DEL PLAN DE NEGOCIO </td>
      <td class="derecha"><? echo number_format($row['total_apoyo'],2);?></td>
      <td class="derecha">100.00</td>
      <td class="derecha">0.00</td>
      <td class="derecha">0.00</td>
      <td class="derecha"><? echo number_format($row['total_apoyo'],2);?></td>
      <td class="derecha">100.00</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td class="derecha"><? echo number_format($row['total_apoyo']+$row['vg_pdss']+$row['at_pdss']+$row['fer_pdss'],2);?></td>
      <td class="derecha">&nbsp;</td>
      <td class="derecha"><? echo number_format($row['vg_org']+$row['at_org']+$row['fer_org'],2);?></td>
      <td class="derecha">&nbsp;</td>
      <td class="derecha"><? echo number_format($row['total_apoyo']+$row['vg_pdss']+$row['at_pdss']+$row['fer_pdss']+$row['vg_org']+$row['at_org']+$row['fer_org'],2);?></td>
      <td class="derecha">100.00</td>
    </tr>
  </table>
<?
//Verificamos que existan datos
$sql="SELECT * FROM pit_bd_cofi_pdn WHERE cod_pdn='$id'";
$result=mysql_query($sql) or die (mysql_error());
$total=mysql_num_rows($result);

if($total<>NULL)
{
?>    
  <p><br/></p>
 <div class="capa txt_titulo">ENTIDADES COFINANCIADORAS</div> 
  
  
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
	  <thead>
		  <tr class="txt_titulo">
			  <th>Nº</th>
			  <th>Nombre de la Entidad Cofinanciadora</th>
			  <th>Tipo de entidad</th>
			  <th>Monto de Aporte(S/.)</th>
		  </tr>
	  </thead>
	  
	  <tbody>
	  <?
	  $num6=0;
	$sql="SELECT pit_bd_cofi_pdn.nombre, 
	pit_bd_cofi_pdn.aporte, 
	sys_bd_ente_cofinanciador.descripcion AS tipo_ente
	FROM sys_bd_ente_cofinanciador INNER JOIN pit_bd_cofi_pdn ON sys_bd_ente_cofinanciador.cod_ente = pit_bd_cofi_pdn.cod_tipo_ente
	WHERE pit_bd_cofi_pdn.cod_pdn='$id'";
	$result=mysql_query($sql) or die (mysql_error());
	while($r15=mysql_fetch_array($result))
	{
		$num6++
	
	  ?>
		  <tr>
			  <td class="centrado"><? echo $num6;?></td>
			  <td><? echo $r15['nombre'];?></td>
			  <td class="centrado"><? echo $r15['tipo_ente'];?></td>
			  <td class="derecha"><? echo number_format($r15['aporte'],2);?></td>
		  </tr>
		<?
		}
		?>	  
	  </tbody>
	  
  </table>
<?
}
?>  
  
  
  
  
  
    <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td align="right">
	    
	    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
	    <?
	    if ($modo==1)
	    {
	    ?>
		<a href="../seguimiento/pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>	
	    <?
	    }
	    else
	    {
	    ?>
		<a href="../pit/pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>	    
		<?
		}
		?>
    </td>
  </tr>
</table>
  </body>
</html>
