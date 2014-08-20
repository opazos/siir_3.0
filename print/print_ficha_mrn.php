<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

//1.- Busco la información del PGRN
$sql="SELECT
pit_bd_ficha_mrn.cod_mrn,
sys_bd_tipo_doc.descripcion AS tipo_doc,
org_ficha_organizacion.n_documento,
org_ficha_organizacion.nombre,
sys_bd_tipo_org.descripcion AS tipo_org,
org_ficha_organizacion.f_creacion,
org_ficha_organizacion.f_formalizacion,
pit_bd_ficha_mrn.sector,
pit_bd_ficha_mrn.lema,
pit_bd_ficha_mrn.f_presentacion,
pit_bd_ficha_mrn.mes,
pit_bd_ficha_mrn.f_termino,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
org_ficha_organizacion.sector,
sys_bd_dependencia.nombre AS oficina,
org_ficha_taz.n_documento AS n_documento_1,
org_ficha_taz.nombre AS nombre_1,
pit_bd_ficha_mrn.tiene_cuenta,
pit_bd_ficha_mrn.n_cuenta,
sys_bd_ifi.descripcion AS ifi,
pit_bd_ficha_mrn.cif_pdss,
pit_bd_ficha_mrn.at_pdss,
pit_bd_ficha_mrn.at_org,
pit_bd_ficha_mrn.vg_pdss,
pit_bd_ficha_mrn.vg_org,
pit_bd_ficha_mrn.ag_pdss
FROM
pit_bd_ficha_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc_taz AND org_ficha_taz.n_documento = org_ficha_organizacion.n_documento_taz
INNER JOIN sys_bd_ifi ON sys_bd_ifi.cod_ifi = pit_bd_ficha_mrn.cod_ifi
WHERE
pit_bd_ficha_mrn.cod_mrn ='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$total_proyecto=$row['cif_pdss']+$row['at_pdss']+$row['vg_pdss']+$row['ag_pdss'];
$total_org=$row['at_org']+$row['vg_org'];

$total=$total_proyecto+$total_org;

$total_at=$row['at_pdss']+$row['at_org'];
$total_vg=$row['vg_pdss']+$row['vg_org'];

/**************************************************** FAMILIAS **************************************************/
//Obtengo N° de Familias
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS familias
FROM
pit_bd_ficha_mrn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE
pit_bd_ficha_mrn.cod_mrn = '$cod' AND
org_ficha_usuario.titular = 1";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);




//N° Familias mujeres
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS familias
FROM
pit_bd_ficha_mrn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE
org_ficha_usuario.titular = 1 AND
org_ficha_usuario.sexo = 0 AND
pit_bd_ficha_mrn.cod_mrn = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i2=mysql_fetch_array($result);

//N° Familias hombres
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS familias
FROM
pit_bd_ficha_mrn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE
org_ficha_usuario.titular = 1 AND
org_ficha_usuario.sexo = 1 AND
pit_bd_ficha_mrn.cod_mrn = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i3=mysql_fetch_array($result);

//Obtengo la fecha actual - 30 años
$fecha_db = $fecha_hoy;
$fecha_db = explode("-",$fecha_db);

$fecha_cambiada = mktime(0,0,0,$fecha_db[1],$fecha_db[2],$fecha_db[0]-30);
$fecha = date("Y-m-d", $fecha_cambiada);
$fecha_30 = "'".$fecha."'";


//N° Familias Mujeres Mayores de 30 años
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS familias
FROM
pit_bd_ficha_mrn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE
org_ficha_usuario.titular = 1 AND
org_ficha_usuario.sexo = 0 AND
org_ficha_usuario.f_nacimiento < $fecha_30 AND
pit_bd_ficha_mrn.cod_mrn = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i4=mysql_fetch_array($result);


//N° Familias Mujeres Menores  de 30 años
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS familias
FROM
pit_bd_ficha_mrn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE
org_ficha_usuario.titular = 1 AND
org_ficha_usuario.sexo = 0 AND
org_ficha_usuario.f_nacimiento > $fecha_30 AND
pit_bd_ficha_mrn.cod_mrn = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i5=mysql_fetch_array($result);

//N° Familias Varones Mayores de 30 años
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS familias
FROM
pit_bd_ficha_mrn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE
org_ficha_usuario.titular = 1 AND
org_ficha_usuario.sexo = 1 AND
org_ficha_usuario.f_nacimiento < $fecha_30 AND
pit_bd_ficha_mrn.cod_mrn = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i6=mysql_fetch_array($result);

//N° Familias Varones Menores de 30 años
$sql="SELECT
Count(pit_bd_user_iniciativa.n_documento) AS familias
FROM
pit_bd_ficha_mrn
INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn AND pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE
org_ficha_usuario.titular = 1 AND
org_ficha_usuario.sexo = 1 AND
org_ficha_usuario.f_nacimiento > $fecha_30 AND
pit_bd_ficha_mrn.cod_mrn = '$cod'";
$result=mysql_query($sql) or die (mysql_error());
$i7=mysql_fetch_array($result);


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
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr class="txt_titulo">
      <td width="33%">Nombre de la Organización </td>
      <td width="1%">:</td>
      <td width="66%"><?php  echo $row['nombre'];?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <div class="gran_titulo capa centrado"><u>PROPUESTA</u> <br>PLAN DE GESTIÓN DE RECURSOS NATURALES</div>
  

  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr class="txt_titulo">
      <td width="33%">Lema del PGRN </td>
      <td width="1%">:</td>
      <td width="66%"><?php  echo $row['lema'];?></td>
    </tr>
    <tr class="txt_titulo">
      <td>&nbsp;</td>
      <td width="1%">&nbsp;</td>
      <td width="66%">&nbsp;</td>
    </tr>
    
    <tr class="txt_titulo">
      <td>Fecha de presentación</td>
      <td width="1%">:</td>
      <td width="66%"><?php  echo traducefecha($row['f_presentacion']);?></td>
    </tr>
    <tr class="txt_titulo">
      <td>Duración</td>
      <td width="1%">:</td>
      <td width="66%"><?php  echo $row['mes'];?> meses</td>
    </tr>
  </table>
    <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <div class="capa">LUGAR Y FECHA : <? echo $row['oficina'];?>, <? echo traducefecha($row['f_presentacion']);?></div>
  <H1 class=SaltoDePagina> </H1>
    <? include("encabezado.php");?>
  <p>&nbsp;</p>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr class="txt_titulo">
      <td colspan="3">I.- DATOS DE LA ORGANIZACIÓN RESPONSABLE DEL PGRN</td>
    </tr>
    <tr>
      <td width="24%" class="txt_titulo">Nombre de la Organización </td>
      <td width="1%">:</td>
      <td width="75%"><?php  echo $row['nombre'];?></td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3"> Situación Legal </td>
    </tr>
    <tr>
      <td>Tipo de Documento </td>
      <td>:</td>
      <td><?php  echo $row['tipo_doc'];?></td>
    </tr>
    <tr>
      <td>N° de documento </td>
      <td>:</td>
      <td><?php  echo $row['n_documento'];?></td>
    </tr>
    <tr>
      <td>Fecha de Inscripción a Registros Públicos</td>
      <td>:</td>
      <td><?php  echo fecha_normal($row['f_formalizacion']);?></td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3"> Relación de Directivos </td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr class="txt_titulo centrado">
      <td>CARGO</td>
      <td>NOMBRES Y APELLIDOS </td>
      <td>DNI</td>
      <td>SEXO</td>
      <td>FECHA DE NACIMIENTO </td>
      <td>VIGENCIA HASTA </td>
    </tr>
 <?php 
 $sql="SELECT
org_ficha_usuario.n_documento,
org_ficha_usuario.nombre,
org_ficha_usuario.paterno,
org_ficha_usuario.materno,
org_ficha_usuario.f_nacimiento,
org_ficha_usuario.sexo,
org_ficha_directivo.f_inicio,
org_ficha_directivo.f_termino,
sys_bd_cargo_directivo.descripcion AS cargo
FROM
pit_bd_ficha_mrn
INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN org_ficha_directivo ON org_ficha_directivo.cod_tipo_doc = org_ficha_usuario.cod_tipo_doc AND org_ficha_directivo.n_documento = org_ficha_usuario.n_documento AND org_ficha_directivo.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_directivo.n_documento_org = org_ficha_organizacion.n_documento
INNER JOIN sys_bd_cargo_directivo ON sys_bd_cargo_directivo.cod_cargo = org_ficha_directivo.cod_cargo
WHERE
pit_bd_ficha_mrn.cod_mrn = '$cod'
ORDER BY
sys_bd_cargo_directivo.cod_cargo ASC";
 $result=mysql_query($sql) or die (mysql_error());
 while($r1=mysql_fetch_array($result))
 {
 ?>   
    <tr>
      <td class="centrado"><?php  echo $r1['cargo'];?></td>
      <td><?php  echo $r1['nombre']." ".$r1['paterno']." ".$r1['materno'];?></td>
      <td class="centrado"><?php  echo $r1['n_documento'];?></td>
      <td class="centrado"><?php  if ($r1['sexo']==0) echo "FEMENINO"; else echo "MASCULINO";?></td>
      <td class="centrado"><?php  echo fecha_normal($r1['f_nacimiento']);?></td>
      <td class="centrado"><?php  echo fecha_normal($r1['f_termino']);?></td>
    </tr>
   <?php 
 }
 ?> 
  </table>
  <br>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr>
      <td width="24%">N° de Familias participantes en el PGRN </td>
      <td width="1%">:</td>
      <td width="75%"><?php  echo number_format($i1['familias']);?> Familias</td>
    </tr>
    <tr class="txt_titulo">
      <td colspan="3">Composición de las Familias Participantes en el PGRN </td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1">
    <tr class="txt_titulo centrado">
      <td width="25%">Sexo</td>
      <td width="25%">Mayores de 30 años </td>
      <td width="25%">Menores de 30 años </td>
      <td width="25%">Total</td>
    </tr>
    <tr>
      <td>Varones</td>
      <td class="derecha"><?php  echo number_format($i6['familias']);?></td>
      <td class="derecha"><?php  echo number_format($i7['familias']);?></td>
      <td class="derecha"><? echo number_format($i6['familias']+$i7['familias']);?></td>
    </tr>
    <tr>
      <td>Mujeres</td>
      <td class="derecha"><?php  echo number_format($i4['familias']);?></td>
      <td class="derecha"><?php  echo number_format($i5['familias']);?></td>
       <td class="derecha"><? echo number_format($i4['familias']+$i5['familias']);?></td>
    </tr>
    <tr>
      <td>Total</td>
      <td class="derecha"><?php  echo number_format($i6['familias']+$i4['familias']);?></td>
       <td class="derecha"><?php  echo number_format($i7['familias']+$i5['familias']);?></td>
       <td class="derecha"><?php  echo number_format($i6['familias']+$i4['familias']+$i7['familias']+$i5['familias']);?></td>
    </tr>
  </table>
    <H1 class=SaltoDePagina> </H1>
    
    
     <? include("encabezado.php");?>   
    <div class="capa txt_titulo">II.- PROPUESTA DE ACTIVIDADES DEL PLAN DE GESTIÓN DE RECURSOS NATURALES</div>
    <br />
    <div class="capa txt_titulo">2.1 Actividades que actualmente realizan</div>
    <br/>
    <table width="90%" cellpadding="1" cellspacing="1" border="1" class="mini" align="center">
    <tr class="txt_titulo centrado">
    <td>Actividades</td>
    <td>A nivel familiar</td>
    <td>N° de familias (Aprox.)</td>
    <td>A nivel de Organización</td>
    </tr>
    <?php 
    $sql="SELECT
	mrn_actividad_actual.nivel,
	mrn_actividad_actual.n_familia,
	sys_bd_actividad_mrn.descripcion AS actividad,
	mrn_actividad_actual.cod_mrn
	FROM
	mrn_actividad_actual
	INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = mrn_actividad_actual.cod_actividad
	WHERE
	mrn_actividad_actual.cod_mrn='$cod'";
    $result=mysql_query($sql) or die (mysql_error());
	while($r2=mysql_fetch_array($result))
	{    
    ?>
    <tr>
    <td><?php  echo $r2['actividad'];?></td>
    <td class="centrado"><?php  if ($r2['nivel']==1) echo "X"; else echo "-";?></td>
    <td class="centrado"><?php  echo number_format($r2['n_familia']);?></td>
    <td class="centrado"><?php  if ($r2['nivel']==2) echo "X"; else echo "-";?></td>
    </tr>
    <?php 
	}
	?>
    </table>
    <br></br>
    <div class="capa txt_titulo">2.2 Actividades que se proponen mejorar o realizar</div>
    <br />
    <table width="90%" cellpadding="1" cellspacing="1" border="1" class="mini" align="center">
    <tr class="centrado txt_titulo">
    <td>Actividades</td>
    <td>A nivel familiar</td>
    <td>N° de familias (Aprox.)</td>
    <td>A nivel de Organización</td>
    </tr>
    <?php 
    $sql="SELECT
	mrn_actividad_futuro.nivel,
	mrn_actividad_futuro.n_familia,
	sys_bd_actividad_mrn.descripcion AS actividad,
	mrn_actividad_futuro.cod_mrn
	FROM
	mrn_actividad_futuro
	INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = mrn_actividad_futuro.cod_actividad
	WHERE
	mrn_actividad_futuro.cod_mrn='$cod'";
    $result=mysql_query($sql) or die (mysql_error());
	while($r3=mysql_fetch_array($result))
	{    
    ?>
    <tr>
    <td><?php  echo $r3['actividad'];?></td>
    <td class="centrado"><?php  if ($r3['nivel']==1) echo "X"; else echo "-";?></td>
    <td class="centrado"><?php  echo number_format($r3['n_familia']);?></td>
    <td class="centrado"><?php  if ($r3['nivel']==2) echo "X"; else echo "-";?></td>
    </tr>
    <?php 
	}
	?>    
    </table>
    <br></br>
    <div class="capa txt_titulo">2.3 Areas destinadas al Plan de Gestión de Recursos Naturales</div>
    <br>
    <?php 
    $sql="SELECT * FROM mrn_area WHERE cod_mrn='$cod'";
    $result=mysql_query($sql) or die (mysql_error());
    $r4=mysql_fetch_array($result);
    ?>
    <table width="90%" cellpadding="1" cellspacing="1" border="1" class="mini" align="center">
    <tr>
    <td>Area que destinará cada familia, en promedio, para trabajar en el PGRN(Has)</td>
    <td class="derecha"><?php  echo number_format($r4['a1'],2);?></td>
    <td class="derecha"><?php  echo number_format($r4['a2'],2);?></td>
    <td class="derecha"><?php  echo number_format($r4['a3'],2);?></td>
    </tr>
    <tr>
    <td>Area que destinará la organización para trabajar en el PGRN(Has)</td>
    <td class="derecha"><?php  echo number_format($r4['a4'],2);?></td>
    <td class="derecha"><?php  echo number_format($r4['a5'],2);?></td>
    <td class="derecha"><?php  echo number_format($r4['a6'],2);?></td>
    </tr>
    </table>
    <br></br>
    <div class="capa txt_titulo">2.4 Propuesta de Calendario de Concursos</div>
    <br />
    <table width="90%" cellpadding="1" cellspacing="1" border="1" class="mini" align="center">
    <tr class="centrado txt_titulo">
    <td>N° DE CONCURSO</td>
    <td>MES</td>
    <td>AÑO</td>
    <td>PRINCIPALES LÍNEAS DE ACTIVIDAD A CONCURSAR</td>
    </tr>
    <?php 
    $num=0;
    $sql="SELECT * FROM mrn_concurso WHERE cod_mrn='$cod'";
    $result=mysql_query($sql) or die (mysql_error());
    while($r5=mysql_fetch_array($result))
    {
    	$num++
    ?>
    <tr>
    <td class="centrado"><?php  echo $num;?></td>
    <td class="centrado"><?php  echo $r5['mes'];?></td>
    <td class="centrado"><?php  echo $r5['anio'];?></td>
    <td><?php  echo $r5['linea'];?></td>
    </tr>
    <?php 
    }
    ?>
    </table>
<H1 class=SaltoDePagina> </H1>
    <? include("encabezado.php");?>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
    <tr class="txt_titulo">
      <td>III. PRESUPUESTO Y APORTES PARA EJECUTAR EL PGRN (S/.) </td>
    </tr>
  </table>
  <br>
  <table width="90%" border="0" align="center" cellpadding="4" cellspacing="4" class="mini">

    <tr class="txt_titulo">
      <td width="31%"><u>REFERENCIAS</u></td>
      <td width="1%">:</td>
      <td width="68%">&nbsp;</td>
    </tr>
    <tr>
      <td>ORGANIZACIÓN TERRITORIAL </td>
      <td>:</td>
      <td><?php  echo $row['nombre_1'];?></td>
    </tr>
    <tr>
      <td>ORGANIZACION DEL PGRN </td>
      <td>:</td>
      <td><?php  echo $row['nombre'];?></td>
    </tr>
    <tr>
      <td>N° DE FAMILIAS PARTICIPANTES DEL PGRN </td>
      <td>:</td>
      <td><?php  echo number_format($i1['familias']);?></td>
    </tr>
    <tr>
      <td>ENTIDADES COFINANCIADORAS </td>
      <td>:</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>PLAZO DE EJECUCIÓN (MESES) </td>
      <td>:</td>
      <td><?php  echo $row['mes'];?></td>
    </tr>
    <tr>
      <td colspan="3"><hr></td>
    </tr>
  </table>
  <br>
  <table width="90%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
    <tr class="centrado txt_titulo">
      <td>CONCEPTO</td>
      <td>APORTE SIERRA SUR (S/.) </td>
      <td>%</td>
      <td>APORTE SOCIOS (S/.) </td>
      <td>%</td>
      <td>TOTAL GENERAL (S/.) </td>
      <td>%</td>
    </tr>
    <tr>
      <td>I.- Premios para Concursos Interfamiliares - CIF </td>
      <td class="derecha"><?php  echo number_format($row['cif_pdss'],2);?></td>
      <td class="derecha">100.00</td>
      <td class="derecha">0.00</td>
      <td class="derecha">0.00</td>
      <td class="derecha"><?php  echo number_format($row['cif_pdss'],2);?></td>
      <td class="derecha">100.00</td>
    </tr>
    <tr>
      <td>II.- Asistencia Técnica de campesino a campesino </td>
      <td class="derecha"><?php  echo number_format($row['at_pdss'],2);?></td>
      <td class="derecha"><?php  echo number_format($row['at_pdss']/$total_at*100,2);?></td>
      <td class="derecha"><?php  echo number_format($row['at_org'],2);?></td>
      <td class="derecha"><?php  echo number_format($row['at_org']/$total_at*100,2);?></td>
      <td class="derecha"><?php  echo number_format($row['at_pdss']+$row['at_org'],2);?></td>
      <td class="derecha">100.00</td>
    </tr>
    <tr>
      <td>III.- Visitas Guiadas </td>
      <td class="derecha"><?php  echo number_format($row['vg_pdss'],2);?></td>
      <td class="derecha"><?php  echo number_format($row['vg_pdss']/$total_vg*100,2);?></td>
      <td class="derecha"><?php  echo number_format($row['vg_org'],2);?></td>
      <td class="derecha"><?php  echo number_format($row['vg_org']/$total_vg*100,2);?></td>
      <td class="derecha"><?php  echo number_format($row['vg_pdss']+$row['vg_org'],2);?></td>
      <td class="derecha">100.00</td>
    </tr>
    <tr>
      <td>IV.- Apoyo a la Gestión del PGRN </td>
      <td class="derecha"><?php  echo number_format($row['ag_pdss'],2);?></td>
      <td class="derecha">100.00</td>
      <td class="derecha">0.00</td>
      <td class="derecha">0.00</td>
      <td class="derecha"><?php  echo number_format($row['ag_pdss'],2);?></td>
      <td class="derecha">100.00</td>
    </tr>
    <tr>
      <td>TOTAL</td>
      <td class="derecha"><?php  echo number_format($total_proyecto,2);?></td>
      <td class="derecha"><?php  echo number_format($total_proyecto/$total*100,2);?></td>
      <td class="derecha"><?php  echo number_format($total_org,2)?></td>
      <td class="derecha"><?php  echo number_format($total_org/$total*100,2);?></td>
      <td class="derecha"><?php  echo number_format($total_proyecto+$total_org,2);?></td>
      <td class="derecha">100.00</td>
    </tr>
  </table>
  
  
<?
//Verificamos que existan datos
$sql="SELECT * FROM pit_bd_cofi_mrn WHERE cod_mrn='$cod'";
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
	$sql="SELECT pit_bd_cofi_mrn.nombre, 
	pit_bd_cofi_mrn.aporte, 
	sys_bd_ente_cofinanciador.descripcion AS tipo_ente
	FROM sys_bd_ente_cofinanciador INNER JOIN pit_bd_cofi_mrn ON sys_bd_ente_cofinanciador.cod_ente = pit_bd_cofi_mrn.cod_tipo_ente
	WHERE pit_bd_cofi_mrn.cod_mrn='$cod'";
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
	    <a href="../seguimiento/mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
	    <?
	    }
	    else
	    {
	    ?>
	    <a href="../pit/mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button oculto">Finalizar</a>
	    <?
	    }
	    ?>
	    
    </td>
  </tr>
</table>
  </body>
</html>