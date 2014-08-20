<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT org_ficha_organizacion.n_documento, 
	org_ficha_organizacion.nombre
FROM pit_bd_ficha_mrn INNER JOIN cif_bd_concurso ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
	 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

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
  @page { size: A4 landscape; }
  </style>
<!-- Fin -->
</head>

<body>
<div class="capa gran_titulo centrado">FORMATO DE REGISTRO DE AVANCES Y LOGROS CIF - PGRN</div>
<p>&nbsp;</p>
<div class="capa txt_titulo">CONCURSOS REALIZADOS POR LA ORGANIZACION: "<? echo $row['nombre'];?>"</div>
<hr/>



<p>&nbsp;</p>
<table width="99%" border="1" align="center" cellpadding="1" cellspacing="1" class="mini">
  <tr class="centrado txt_titulo">
    <td>Nº de Concurso</td>
    <td>Actividades del Concurso</td>
    <td>Fecha de concurso</td>
    <td>Total participantes <br/>(1)</td>
    <td>Participantes Mujeres</td>
    <td>Participantes Varones</td>
    <td>Unidad de medida</td>    
    <td>Meta lograda de la actividad</td>
    <td>Valor estimado de la meta lograda (S/.)</td>
    <td>Nº de Premios otorgados</td>
    <td>Monto total de premios (S/.)</td>
    <td>Monto del premio Maximo (S/.)</td>
    <td>Monto del premio Minimo (S/.)</td>
    <td>Monto de premio Otras entidades (S/.)</td>
  </tr>
<?
$sql="SELECT cif_bd_concurso.n_concurso, 
	sys_bd_actividad_mrn.descripcion AS actividad, 
	sys_bd_actividad_mrn.unidad, 
	cif_bd_ficha.n_participantes, 
	cif_bd_ficha.n_mujeres, 
	cif_bd_ficha.n_varones, 
	cif_bd_ficha.meta, 
	cif_bd_ficha.valor_meta, 
	cif_bd_ficha.n_premios, 
	cif_bd_ficha.monto_premios, 
	cif_bd_ficha.premio_max, 
	cif_bd_ficha.premio_min, 
	cif_bd_ficha.premio_otr, 
	cif_bd_concurso.f_concurso
FROM cif_bd_ficha INNER JOIN cif_bd_concurso ON cif_bd_ficha.cod_concurso = cif_bd_concurso.cod_concurso_cif
	 INNER JOIN sys_bd_actividad_mrn ON sys_bd_actividad_mrn.cod = cif_bd_ficha.cod_actividad
WHERE cif_bd_concurso.cod_concurso_cif='$cod'
ORDER BY cif_bd_concurso.n_concurso ASC, cif_bd_ficha.cod_actividad ASC";
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
?>  
  <tr>
    <td class="centrado"><? echo numeracion($fila['n_concurso']);?></td>
    <td><? echo $fila['actividad'];?></td>
    <td class="centrado"><? echo fecha_normal($fila['f_concurso']);?></td>
    <td class="derecha"><? echo number_format($fila['n_participantes']);?></td>
    <td class="derecha"><? echo number_format($fila['n_mujeres']);?></td>
    <td class="derecha"><? echo number_format($fila['n_varones']);?></td>
    <td class="centrado"><? echo $fila['unidad'];?></td>    
    <td class="derecha"><? echo number_format($fila['meta'],2);?></td>
    <td class="derecha"><? echo number_format($fila['valor_meta'],2);?></td>
    <td class="derecha"><? echo number_format($fila['n_premios']);?></td>
    <td class="derecha"><? echo number_format($fila['monto_premios'],2);?></td>
    <td class="derecha"><? echo number_format($fila['premio_max'],2);?></td>
    <td class="derecha"><? echo number_format($fila['premio_min'],2);?></td>
    <td class="derecha"><? echo number_format($fila['premio_otr'],2);?></td>
  </tr>
<?
}
?>  
</table>
<br/>
<div class="capa mini">
	(1) Según Registro de participantes adjunto y almacenado en el SIIR.<br/>
	Fuente: Acta de Concurso levantada por la organización del PGRN, según Nº de CIF.
</div>


<table width="90%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td>
    
    <button type="submit" class="secondary button oculto" onclick="window.print()">Imprimir</button>
    <a href="../seguimiento/cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=print_consolidado" class="secondary button oculto">Finalizar</a>

    </td>
  </tr>
</table>

</body>
</html>
