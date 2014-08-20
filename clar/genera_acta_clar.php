<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- obtengo la numeracion
$sql="SELECT sys_bd_numera_dependencia.cod, 
	sys_bd_numera_dependencia.n_acta_clar
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$n_acta=$r1['n_acta_clar']+1;

//2.- Obtengo los datos del CLAR
$sql="SELECT
clar_bd_evento_clar.nombre,
clar_bd_evento_clar.f_evento,
sys_bd_departamento.nombre AS departamento,
sys_bd_provincia.nombre AS provincia,
sys_bd_distrito.nombre AS distrito,
clar_bd_evento_clar.lugar,
sys_bd_dependencia.nombre AS oficina,
clar_bd_evento_clar.objetivo,
clar_bd_evento_clar.resultado,
sys_bd_personal.nombre AS nombres,
sys_bd_personal.apellido,
clar_bd_evento_clar.f_campo1,
clar_bd_evento_clar.f_campo2,
clar_bd_evento_clar.premio,
clar_bd_evento_clar.ganadores
FROM
clar_bd_evento_clar
INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = clar_bd_evento_clar.cod_dep
INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = clar_bd_evento_clar.cod_prov
INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
INNER JOIN sys_bd_personal ON sys_bd_personal.n_documento = sys_bd_dependencia.dni_representante
WHERE
clar_bd_evento_clar.cod_clar = '$id'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//3.- Obtengo el acta
$sql="SELECT clar_bd_acta.contenido, 
	clar_bd_acta.n_acta, 
	clar_bd_acta.cod_acta
FROM clar_bd_acta
WHERE clar_bd_acta.cod_clar='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);

if ($modo==nuevo)
{
	$action=ADD;
}
else
{
	$action=UPDATE;
}

?>
<!DOCTYPE html>
<html class="no-js" lang="en"> <!--<![endif]-->
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
  <!-- Editor de texto -->
  <script type="text/javascript" src="../plugins/texteditor/jscripts/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="../plugins/texteditor/editor.js"></script>
  <!-- fin de editor de texto -->
</head>
<body>
<? include("menu.php");?>
<div class="row">
	<div class="twelve columns">
		<div class="panel">
		<form name="form5" method="post" action="gestor_acta_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&action=<? echo $action;?>" onsubmit="return checkSubmit();">
			<div class="row">
				<div class="twelve columns"><h6>GENERAR ACTA DEL CLAR</h6></div>
				<div class="three columns"><label class="inline">Número de Acta</label></div>
				<div class="nine columns"><input type="text" name="n_acta" class="required two" value="<? if ($modo==nuevo) echo $n_acta; else echo $r3['n_acta'];?>">
					<input type="hidden" name="cod_numera" value="<? echo $r1['cod'];?>">
					<input type="hidden" name="cod_acta" value="<? echo $r3['cod_acta'];?>">
				</div>
			</div>

			<div class="row">
				<div class="twelve columns">
				<textarea name="contenido" class="ckeditor">
<?
if ($modo==nuevo or $modo==reseteo)
{
?>
					<div class="capa justificado">
						<p>En <? echo $r2['lugar'];?> del Distrito de <? echo $r2['distrito'];?>, Provincia de <? echo $r2['provincia'];?>, Departamento de <? echo $r2['departamento'];?>, siendo las 8.00 horas del día <? echo traducefecha($r2['f_evento']);?>, se inició el ENCUENTRO DE INTERCAMBIO DE EXPERIENCIAS Y VALORACION DE CONOCIMIENTOS LOCALES – INTERCON denominado “<? echo $r2['nombre'];?>”, en el marco de este evento se realizó la .............. sesión del <? echo date(Y);?>  del CLAR – Comité Local de Asignación de Recursos – de la Oficina Local <? echo $r2['oficina'];?> del Proyecto de Desarrollo Sierra Sur II, el cual se desarrolló conforme a lo siguiente:</p>
						<p><strong>1.- PARTICIPANTES</strong>
							<ol type="a">
							<li><h4>Miembros del CLAR</h4>
								<ul>
								<?
								$sql="SELECT clar_bd_miembro.nombre, 
								clar_bd_miembro.paterno, 
								clar_bd_miembro.materno, 
								sys_bd_cargo_jurado_clar.descripcion AS cargo
								FROM clar_bd_miembro INNER JOIN clar_bd_jurado_evento_clar ON clar_bd_miembro.cod_tipo_doc = clar_bd_jurado_evento_clar.cod_tipo_doc AND clar_bd_miembro.n_documento = clar_bd_jurado_evento_clar.n_documento
									 INNER JOIN sys_bd_cargo_jurado_clar ON sys_bd_cargo_jurado_clar.cod_cargo_jurado = clar_bd_jurado_evento_clar.cod_cargo_miembro
								WHERE clar_bd_jurado_evento_clar.cod_clar='$id'
								ORDER BY clar_bd_miembro.nombre ASC";
								$result=mysql_query($sql) or die (mysql_error());
								while ($f1=mysql_fetch_array($result)) 
								{
									echo "<li>".$f1['nombre']." ".$f1['paterno']." ".$f1['materno']." - ".$f1['cargo']."</li>";
								}
								?>
								</ul>
							</li>
							<li><h4>Personal del Proyecto de Desarrollo Sierra Sur II</h4>
								<ul>
									<li>Campo 1</li>
									<li>Campo 2</li>
									<li>Campo 3</li>
								</ul>
							</li>
							<li><h4>Alcaldes</h4>
								<ul>
									<li>Campo 1</li>
									<li>Campo 2</li>
									<li>Campo 3</li>
								</ul>
							</li>
							<li><h4>Invitados</h4>
								<ul>
									<li>Campo 1</li>
									<li>Campo 2</li>
									<li>Campo 3</li>
								</ul>
							</li>
							</ol>
						</p>
						<p><strong>2.- AGENDA DE LA REUNIÓN</strong>
							<ul>
								<li>Campo 1</li>
								<li>Campo 2</li>
								<li>Campo 3</li>
							</ul>
						</p>
						<p><strong>3.- DESARROLLO DE LA AGENDA</strong>
							<ol type="a">
								<li>El Sr(a). .............., Alcalde de la Municipalidad Distrital de .............., dio la bienvenida a las autoridades, a los participantes del Concurso de Iniciativas Rurales, a los representantes del Proyecto de Desarrollo Sierra Sur II, así como también a las diferentes delegaciones de visitantes y asistentes al evento, dando por inaugurado el evento con la expectativa de que las organizaciones participantes expongan satisfactoriamente sus propuestas.</li>
								<li>El Sr(a). .............., Director Ejecutivo del NEC PROYECTO SIERRA SUR II, manifestó su agradecimiento al Sr Alcalde y autoridades locales por su esfuerzo colaborativo en la organización de este INTERCON y expresó el reconocimiento a las organizaciones participantes por su interés y compromiso asumido en el desarrollo de sus iniciativas orientadas a mejorar sus condiciones de vida, capitalizando de la mejor manera las oportunidades de acceder a fondos públicos que les brinda el Proyecto Sierra Sur II de AGRORURAL – MINAG, que se ejecuta con apoyo financiero del FIDA – Fondo Internacional de Desarrollo Agrícola.</li>
								<li>El Sr(a). .............., en su condición de Jefe de la Oficina Local de .............. del Proyecto SIERRA SUR II, presentó un Informe Técnico respecto del proceso de preparación de las diferentes organizaciones locales que participan del Concurso, destacando los siguientes aspectos:
									<ul>
										<li><strong>EN LOS INTERCON.-</strong> Se evalúan y seleccionan propuestas de emprendimientos campesinos, enmarcados en un plan de Inversión Territorial - PIT con intervención del Comité Local de Asignación de Recursos - CLAR, se evalúa el avance y aprobación por el CLAR de segundos desembolsos de los emprendimientos de los PIT en ejecución; concurso de resultado de los diferentes emprendimientos que conforman los PIT; concurso de valoración de los activos culturales de los diferentes territorios del área de acción del Proyecto; suscripción de contratos de donación con cargo y transferencia de fondos públicos a organizaciones seleccionadas por el CLAR y ganadoras de concursos y aprendizajes de actores locales de diversos territorios del proyecto, a través de visitas denominadas giras motivacionales entre otras actividades.</li>
										<li>Finalmente el INTERCON ES UNA FIESTA, que permite (el día de hoy) a las poblaciones campesinas mostrar a través de actividades lúdicas y artísticas su creatividad, conocimiento y visión de su propio desarrollo.</li>
										<li>Manifestó que las organizaciones participantes en CLAR tienen 02 fases de evaluación.
											<ol type="i">
												<li>Evaluación de campo: Se realizó desde el 20 de febrero de 2014 a 25 de febrero de 2014, se evaluó al 100% de las organizaciones postulantes.</li>
												<li>Evaluación pública: La que se lleva a cabo en el presente evento; para lo cual, el sr(a) .............. presenta a cada uno de los miembros del CLAR y a las diferentes organizaciones postulantes que muestran sus diferentes iniciativas de acuerdo al siguiente detalle:
<p align="center"><strong>ORGANIZACIONES TERRITORIALES (PIT) QUE SE PRESENTAN PARA  PRIMER DESEMBOLSO CON PGRN Y/O PDN</strong></p>
						<p>
							<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
								<thead>
									<tr>
										<td>Nº</td>
										<td>ORGANIZACION TERRITORIAL</td>
										<td>ORGANIZACIÓN RESPONSABLE DEL PLAN DE GESTION DE RECURSOS NATURALES</td>
										<td>ORGANIZACIÓN RESPONSABLE DEL PLAN DE NEGOCIO</td>
										<td>DENOMINACION DEL PLAN DE NEGOCIO</td>
									</tr>
								</thead>
								<tbody>
								<?
								$na=0;
								$sql="SELECT org_ficha_taz.nombre AS pit,
								org2.nombre AS mrn,
								org1.nombre AS pdn,
								pit_bd_ficha_pdn.denominacion
								FROM
								pit_bd_ficha_pit
								LEFT JOIN clar_bd_ficha_pit ON clar_bd_ficha_pit.cod_pit = pit_bd_ficha_pit.cod_pit
								LEFT JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
								LEFT JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pit = pit_bd_ficha_pit.cod_pit
								LEFT JOIN org_ficha_organizacion AS org1 ON org1.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org1.n_documento = pit_bd_ficha_pdn.n_documento_org
								LEFT JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_pit = pit_bd_ficha_pit.cod_pit
								LEFT JOIN org_ficha_organizacion AS org2 ON org2.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org2.n_documento = pit_bd_ficha_mrn.n_documento_org
								WHERE
								clar_bd_ficha_pit.cod_clar = '$id'
								ORDER BY
								org_ficha_taz.nombre ASC,
								org2.nombre ASC,
								org1.nombre ASC";
								$result=mysql_query($sql) or die (mysql_error());
								while($f2=mysql_fetch_array($result))
								{
									$na++
								?>
									<tr>
										<td><? echo $na;?></td>
										<td><? echo $f2['pit'];?></td>
										<td><? echo $f2['mrn'];?></td>
										<td><? echo $f2['pdn'];?></td>
										<td><? echo $f2['denominacion'];?></td>
									</tr>
								<?
								}
								$nb=$na;
								$sql="SELECT pit_bd_ficha_pdn.denominacion, 
								org_ficha_organizacion.nombre
								FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
									 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
								WHERE pit_bd_ficha_pdn.tipo<>0 AND 
								clar_bd_ficha_pdn_suelto.cod_clar='$id'";
								$result=mysql_query($sql) or die (mysql_error());
								while($f3=mysql_fetch_array($result))
								{
									$nb++
								?>
									<tr>
										<td><? echo $nb;?></td>
										<td></td>
										<td></td>
										<td><? echo $f3['nombre'];?></td>
										<td><? echo $f3['denominacion'];?></td>
									</tr>
								<?	
								}
								?>	
								</tbody>
							</table>
						</p>
						<p align="center"><strong>ORGANIZACIONES TERRITORIALES (PIT) QUE SE PRESENTAN PARA SEGUNDO DESEMBOLSO </strong></p>
						<p>
							<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
								<thead>
									<tr class="txt_titulo centrado">
										<td colspan="3">PLANES DE INVERSION TERRITORIAL</td>
									</tr>
									<tr class="txt_titulo centrado">
										<td>Nº</td>
										<td>Nº DE DOCUMENTO</td>
										<td>NOMBRE DE LA ORGANIZACION</td>
									</tr>
								</thead>
								<tbody>
								<?
								$nc=0;
								$sql="SELECT
								org_ficha_taz.n_documento,
								org_ficha_taz.nombre
								FROM
								clar_bd_ficha_pit_2
								INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
								INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
								WHERE
								clar_bd_ficha_pit_2.cod_clar = '$id'";
								$result=mysql_query($sql) or die (mysql_error());
								while($f4=mysql_fetch_array($result))
								{
									$nc++
								?>
									<tr>
										<td><? echo $nc;?></td>
										<td><? echo $f4['n_documento'];?></td>
										<td><? echo $f4['nombre'];?></td>
									</tr>
								<?
								}
								?>	
								</tbody>
							</table>
						</p>
						<p><br/></p>
						<p>
							<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
								<thead>
									<tr class="txt_titulo centrado">
										<td colspan="3">PLANES DE GESTIÓN DE RECURSOS NATURALES</td>
									</tr>
									<tr class="txt_titulo centrado">
										<td>Nº</td>
										<td>Nº DE DOCUMENTO</td>
										<td>NOMBRE DE LA ORGANIZACION</td>
									</tr>
								</thead>
								<tbody>
								<?
								$nd=0;
								$sql="SELECT
								org_ficha_organizacion.n_documento,
								org_ficha_organizacion.nombre
								FROM
								clar_bd_ficha_mrn_2
								INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
								INNER JOIN org_ficha_organizacion ON pit_bd_ficha_mrn.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND pit_bd_ficha_mrn.n_documento_org = org_ficha_organizacion.n_documento
								WHERE
								clar_bd_ficha_mrn_2.cod_clar ='$id'";
								$result=mysql_query($sql) or die (mysql_error());
								while($f5=mysql_fetch_array($result))
								{
									$nd++
								?>
									<tr>
										<td><? echo $nd;?></td>
										<td><? echo $f5['n_documento'];?></td>
										<td><? echo $f5['nombre'];?></td>
									</tr>
								<?
								}
								?>	
								</tbody>
							</table>
						</p>
						<p><br/></p>
						<p>
							<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
								<thead>
									<tr class="txt_titulo centrado">
										<td colspan="4">PLANES DE NEGOCIO</td>
									</tr>
									<tr class="txt_titulo centrado">
										<td>Nº</td>
										<td>Nº DE DOCUMENTO</td>
										<td>NOMBRE DE LA ORGANIZACION</td>
										<td>DENOMINACION DEL PLAN DE NEGOCIO</td>
									</tr>
								</thead>
								<tbody>
								<?
								$ne=0;
								$sql="SELECT
								clar_bd_ficha_pdn_2.cod_ficha_pdn_clar,
								org_ficha_organizacion.n_documento,
								org_ficha_organizacion.nombre,
								pit_bd_ficha_pdn.denominacion
								FROM
								clar_bd_ficha_pdn_2
								INNER JOIN pit_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_2.cod_pdn
								INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
								WHERE
								clar_bd_ficha_pdn_2.cod_clar = '$id'";
								$result=mysql_query($sql) or die (mysql_error());
								while ($f6=mysql_fetch_array($result)) 
								{
									$ne++
								?>
									<tr>
										<td><? echo $ne;?></td>
										<td><? echo $f6['n_documento'];?></td>
										<td><? echo $f6['nombre'];?></td>
										<td><? echo $f6['denominacion'];?></td>
									</tr>
								<?
								}
								?>	
								</tbody>
							</table>
						</p>			
						<p><br/></p>
												</li>		
											</ol>
										</li>
									</ul>
								</li>						
								<li><p>La Secretaria Técnica del CLAR, hizo entrega de los resultados de la Evaluación de campo de cada miembro del CLAR, aclarando que el puntaje máximo en la 1ra fase de campo es de 50 puntos y que en la 2da fase de presentación pública que se realiza el día  de hoy .............., el puntaje máximo es también de 50 puntos, acumulable.</p><p>Seguidamente se da cuenta de los resultados, el puntaje total de calificación de cada una de las iniciativas de PIT, PGRN y PDN, según los cuadros siguientes:</p>
									<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>PLANES DE INVERSION TERRITORIAL - PRIMER DESEMBOLSO</strong></td>
										</tr>
										
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
									<?
										$nf=0;
										$sql="SELECT clar_bd_ficha_pit.cod_pit, 
										org_ficha_taz.n_documento, 
										org_ficha_taz.nombre, 
										pit_bd_ficha_pit.calificacion, 
										sys_bd_dependencia.nombre AS oficina, 
										((pit_bd_ficha_pit.aporte_pdss*0.70)+ 
										(pit_bd_ficha_pit.aporte_org*0.50)) AS aporte_total, 
										(pit_bd_ficha_pit.aporte_pdss*0.70) AS aporte_pdss, 
										(pit_bd_ficha_pit.aporte_org*0.50) AS aporte_org
										FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
										INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
										INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
										WHERE clar_bd_ficha_pit.cod_clar='$id' AND
										pit_bd_ficha_pit.mancomunidad=0
										ORDER BY pit_bd_ficha_pit.calificacion DESC";
										$result=mysql_query($sql) or die (mysql_error());
										while($f7=mysql_fetch_array($result))
										{
											$nf++
									?>
										<tr>
											<td align="center"><? echo $nf;?></td>
											<td align="center"><? echo $f7['n_documento'];?></td>
											<td><? echo $f7['nombre'];?></td>
											<td align="center"><? echo number_format($f7['calificacion'],2);?></td>
											<td align="center"><? echo $f7['oficina'];?></td>
											<td><? if ($f7['calificacion']>=70) echo number_format($f7['aporte_pdss'],2);?></td>
											<td><? if ($f7['calificacion']>=70) echo number_format($f7['aporte_org'],2);?></td>
											<td><? if ($f7['calificacion']>=70) echo number_format($f7['aporte_total'],2);?></td>
											<td><? if ($f7['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
									<?
									}
									$sql="SELECT SUM((pit_bd_ficha_pit.aporte_pdss*0.70)) AS aporte_pdss, 
									SUM((pit_bd_ficha_pit.aporte_org*0.50)) AS aporte_org, 
									SUM((pit_bd_ficha_pit.aporte_pdss*0.70)+ 
									(pit_bd_ficha_pit.aporte_org*0.50)) AS aporte_total
									FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
									WHERE clar_bd_ficha_pit.cod_clar='$id' AND
									pit_bd_ficha_pit.mancomunidad='0' AND
									pit_bd_ficha_pit.calificacion>='70'";
									$result=mysql_query($sql) or die (mysql_error());
									$f8=mysql_fetch_array($result);
									?>	
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f8['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f8['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f8['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
								</p>
								<p><strong>S/. <? echo number_format($f8['aporte_pdss'],2);?></strong>, que corresponde solamente al aporte del Proyecto de Desarrollo Sierra Sur II.</p>
								<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>PLANES DE INVERSION TERRITORIAL - SEGUNDO DESEMBOLSO</strong></td>
										</tr>
										
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
									<?
									$ng=0;
									$sql="SELECT clar_bd_ficha_pit_2.cod_pit, 
									pit_bd_ficha_pit.calificacion_2 AS calificacion, 
									org_ficha_taz.n_documento, 
									org_ficha_taz.nombre, 
									sys_bd_dependencia.nombre AS oficina, 
									((pit_bd_ficha_pit.aporte_pdss*0.30)+ 
									(pit_bd_ficha_pit.aporte_org*0.50)) AS aporte_total, 
									(pit_bd_ficha_pit.aporte_pdss*0.30) AS aporte_pdss, 
									(pit_bd_ficha_pit.aporte_org*0.50) AS aporte_org
									FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit_2 ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
									INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
									INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
									WHERE clar_bd_ficha_pit_2.cod_clar='$id'
									ORDER BY calificacion DESC";
									$result=mysql_query($sql) or die (mysql_error());
									while($f9=mysql_fetch_array($result))
									{
										$ng++
									?>
										<tr>
											<td align="center"><? echo $ng;?></td>
											<td align="center"><? echo $f9['n_documento'];?></td>
											<td><? echo $f9['nombre'];?></td>
											<td align="center"><? echo number_format($f9['calificacion'],2);?></td>
											<td align="center"><? echo $f9['oficina'];?></td>
											<td><? if ($f9['calificacion']>=70) echo number_format($f9['aporte_pdss'],2);?></td>
											<td><? if ($f9['calificacion']>=70) echo number_format($f9['aporte_org'],2);?></td>
											<td><? if ($f9['calificacion']>=70) echo number_format($f9['aporte_total'],2);?></td>
											<td align="center"><? if ($f9['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
									<?
									}
									$sql="SELECT SUM((pit_bd_ficha_pit.aporte_pdss*0.30)) AS aporte_pdss, 
									SUM((pit_bd_ficha_pit.aporte_org*0.50)) AS aporte_org, 
									SUM((pit_bd_ficha_pit.aporte_pdss*0.30)+ 
									(pit_bd_ficha_pit.aporte_org*0.50)) AS aporte_total
									FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit_2 ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit_2.cod_pit
									WHERE clar_bd_ficha_pit_2.cod_clar='$id' AND
									pit_bd_ficha_pit.calificacion_2>=70";
									$result=mysql_query($sql) or die (mysql_error());
									$f10=mysql_fetch_array($result);
									?>	
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f10['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f10['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f10['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
								</p>
								<p><strong>S/. <? echo number_format($f10['aporte_pdss'],2);?></strong>, que corresponde solamente al aporte del Proyecto de Desarrollo Sierra Sur II.</p>
								<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>PLANES DE GESTION DE RECURSOS NATURALES - PRIMER DESEMBOLSO</strong></td>
										</tr>
										
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
									<?
									$nj=0;
									$sql="SELECT org_ficha_organizacion.n_documento, 
									org_ficha_organizacion.nombre, 
									pit_bd_ficha_mrn.calificacion, 
									sys_bd_dependencia.nombre AS oficina, 
									((pit_bd_ficha_mrn.cif_pdss+ 
									pit_bd_ficha_mrn.at_pdss+ 
									pit_bd_ficha_mrn.vg_pdss+ 
									pit_bd_ficha_mrn.ag_pdss)*0.70) AS aporte_pdss, 
									((pit_bd_ficha_mrn.at_org+ 
									pit_bd_ficha_mrn.vg_org)*0.50) AS aporte_org, 
									((pit_bd_ficha_mrn.cif_pdss+ 
									pit_bd_ficha_mrn.at_pdss+ 
									pit_bd_ficha_mrn.vg_pdss+ 
									pit_bd_ficha_mrn.ag_pdss)*0.70)+ 
									((pit_bd_ficha_mrn.at_org+ 
									pit_bd_ficha_mrn.vg_org)*0.50) AS aporte_total
								FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
									 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
									 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
								WHERE clar_bd_ficha_mrn.cod_clar='$id'
								ORDER BY pit_bd_ficha_mrn.calificacion DESC";
									$result=mysql_query($sql) or die (mysql_error());
									while($f11=mysql_fetch_array($result))
									{
										$nj++
									?>
										<tr>
											<td align="center"><? echo $nj;?></td>
											<td align="center"><? echo $f11['n_documento'];?></td>
											<td><? echo $f11['nombre'];?></td>
											<td align="center"><? echo number_format($f11['calificacion'],2);?></td>
											<td align="center"><? echo $f11['oficina'];?></td>
											<td><? if ($f11['calificacion']>=70) echo number_format($f11['aporte_pdss'],2);?></td>
											<td><? if ($f11['calificacion']>=70) echo number_format($f11['aporte_org'],2);?></td>
											<td><? if ($f11['calificacion']>=70) echo number_format($f11['aporte_total'],2);?></td>
											<td align="center"><? if ($f11['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
									<?
									}
									$sql="SELECT SUM((pit_bd_ficha_mrn.cif_pdss+ 
									pit_bd_ficha_mrn.at_pdss+ 
									pit_bd_ficha_mrn.vg_pdss+ 
									pit_bd_ficha_mrn.ag_pdss)*0.70) AS aporte_pdss, 
									SUM((pit_bd_ficha_mrn.at_org+ 
									pit_bd_ficha_mrn.vg_org)*0.50) AS aporte_org, 
									SUM(((pit_bd_ficha_mrn.cif_pdss+ 
									pit_bd_ficha_mrn.at_pdss+ 
									pit_bd_ficha_mrn.vg_pdss+ 
									pit_bd_ficha_mrn.ag_pdss)*0.70)+ 
									((pit_bd_ficha_mrn.at_org+ 
									pit_bd_ficha_mrn.vg_org)*0.50)) AS aporte_total
									FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
									WHERE clar_bd_ficha_mrn.cod_clar='$id' AND
									pit_bd_ficha_mrn.calificacion>=70";
									$result=mysql_query($sql) or die (mysql_error());
									$f12=mysql_fetch_array($result);
										?>	
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f12['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f12['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f12['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
									</p>
									<p><strong>S/. <? echo number_format($f12['aporte_total'],2);?></strong>, monto total, del cual S/. <? echo number_format($f12['aporte_pdss'],2);?> corresponde al Proyecto de Desarrollo Sierra Sur II y S/. <? echo number_format($f12['aporte_org'],2);?> al aporte de la organización.</p>
									<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>PLANES DE GESTION DE RECURSOS NATURALES - SEGUNDO DESEMBOLSO</strong></td>
										</tr>
										
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
									<?
									$nk=0;
									$sql="SELECT org_ficha_organizacion.n_documento, 
									org_ficha_organizacion.nombre, 
									pit_bd_ficha_mrn.calificacion_2 AS calificacion, 
									sys_bd_dependencia.nombre AS oficina, 
									((pit_bd_ficha_mrn.cif_pdss+ 
									pit_bd_ficha_mrn.at_pdss+ 
									pit_bd_ficha_mrn.vg_pdss+ 
									pit_bd_ficha_mrn.ag_pdss)*0.30) AS aporte_pdss, 
									((pit_bd_ficha_mrn.at_org+ 
									pit_bd_ficha_mrn.vg_org)*0.50) AS aporte_org, 
									((pit_bd_ficha_mrn.cif_pdss+ 
									pit_bd_ficha_mrn.at_pdss+ 
									pit_bd_ficha_mrn.vg_pdss+ 
									pit_bd_ficha_mrn.ag_pdss)*0.30)+
									((pit_bd_ficha_mrn.at_org+ 
									pit_bd_ficha_mrn.vg_org)*0.50) AS aporte_total
									FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn_2 ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn_2.cod_mrn
									INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
									INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
									WHERE clar_bd_ficha_mrn_2.cod_clar='$id'
									ORDER BY calificacion DESC";
									$result=mysql_query($sql) or die (mysql_error());
									while($f13=mysql_fetch_array($result))
									{
										$nk++
									?>
										<tr>
											<td align="center"><? echo $nk;?></td>
											<td align="center"><? echo $f13['n_documento'];?></td>
											<td><? echo $f13['nombre'];?></td>
											<td align="center"><? echo number_format($f13['calificacion'],2);?></td>
											<td align="center"><? echo $f13['oficina'];?></td>
											<td><? if ($f13['calificacion']>=70) echo number_format($f13['aporte_pdss'],2);?></td>
											<td><? if ($f13['calificacion']>=70) echo number_format($f13['aporte_org'],2);?></td>
											<td><? if ($f13['calificacion']>=70) echo number_format($f13['aporte_total'],2);?></td>
											<td align="center"><? if ($f13['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
									<?
									}
									$sql="SELECT SUM((pit_bd_ficha_mrn.cif_pdss+ 
									pit_bd_ficha_mrn.at_pdss+ 
									pit_bd_ficha_mrn.vg_pdss+ 
									pit_bd_ficha_mrn.ag_pdss)*0.30) AS aporte_pdss, 
									SUM((pit_bd_ficha_mrn.at_org+ 
									pit_bd_ficha_mrn.vg_org)*0.50) AS aporte_org, 
									SUM(((pit_bd_ficha_mrn.cif_pdss+ 
									pit_bd_ficha_mrn.at_pdss+ 
									pit_bd_ficha_mrn.vg_pdss+ 
									pit_bd_ficha_mrn.ag_pdss)*0.30)+ 
									((pit_bd_ficha_mrn.at_org+ 
									pit_bd_ficha_mrn.vg_org)*0.50)) AS aporte_total
									FROM clar_bd_ficha_mrn_2 INNER JOIN pit_bd_ficha_mrn ON clar_bd_ficha_mrn_2.cod_mrn = pit_bd_ficha_mrn.cod_mrn
									WHERE clar_bd_ficha_mrn_2.cod_clar='$id' AND
									pit_bd_ficha_mrn.calificacion_2>=70";
									$result=mysql_query($sql) or die (mysql_error());
									$f14=mysql_fetch_array($result);
									?>	
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f14['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f14['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f14['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
									</p>
									<p><strong>S/. <? echo number_format($f14['aporte_total'],2);?></strong>, monto total, del cual S/. <? echo number_format($f14['aporte_pdss'],2);?> corresponde al Proyecto de Desarrollo Sierra Sur II y S/. <? echo number_format($f14['aporte_org'],2);?> al aporte de la organización.</p>
									<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>PLANES DE NEGOCIO - PRIMER DESEMBOLSO</strong></td>
										</tr>
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
									<?
									$nl=0;
									$sql="SELECT org_ficha_organizacion.n_documento, 
									org_ficha_organizacion.nombre, 
									pit_bd_ficha_pdn.calificacion, 
									sys_bd_dependencia.nombre AS oficina, 
									((pit_bd_ficha_pdn.total_apoyo+ 
									pit_bd_ficha_pdn.at_pdss+ 
									pit_bd_ficha_pdn.vg_pdss+ 
									pit_bd_ficha_pdn.fer_pdss)*0.70) AS aporte_pdss, 
									((pit_bd_ficha_pdn.at_org+ 
									pit_bd_ficha_pdn.vg_org+ 
									pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org, 
									((pit_bd_ficha_pdn.total_apoyo+ 
									pit_bd_ficha_pdn.at_pdss+ 
									pit_bd_ficha_pdn.vg_pdss+ 
									pit_bd_ficha_pdn.fer_pdss)*0.70)+
									((pit_bd_ficha_pdn.at_org+ 
									pit_bd_ficha_pdn.vg_org+ 
									pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_total
									FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
									INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
									INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
									WHERE clar_bd_ficha_pdn.cod_clar='$id'
									ORDER BY pit_bd_ficha_pdn.calificacion DESC";
									$result=mysql_query($sql) or die (mysql_error());
									while($f15=mysql_fetch_array($result))
									{
										$nl++
									?>
										<tr>
											<td align="center"><? echo $nl;?></td>
											<td align="center"><? echo $f15['n_documento'];?></td>
											<td><? echo $f15['nombre'];?></td>
											<td align="center"><? echo number_format($f15['calificacion'],2);?></td>
											<td align="center"><? echo $f15['oficina'];?></td>
											<td><? if ($f15['calificacion']>=70) echo number_format($f15['aporte_pdss'],2);?></td>
											<td><? if ($f15['calificacion']>=70) echo number_format($f15['aporte_org'],2);?></td>
											<td><? if ($f15['calificacion']>=70) echo number_format($f15['aporte_total'],2);?></td>
											<td align="center"><? if ($f15['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
									<?
									}
									$sql="SELECT SUM((pit_bd_ficha_pdn.total_apoyo+
									pit_bd_ficha_pdn.at_pdss+ 
									pit_bd_ficha_pdn.vg_pdss+ 
									pit_bd_ficha_pdn.fer_pdss)*0.70) AS aporte_pdss, 
									SUM((pit_bd_ficha_pdn.at_org+ 
									pit_bd_ficha_pdn.vg_org+ 
									pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org, 
									SUM(((pit_bd_ficha_pdn.total_apoyo+ 
									pit_bd_ficha_pdn.at_pdss+ 
									pit_bd_ficha_pdn.vg_pdss+ 
									pit_bd_ficha_pdn.fer_pdss)*0.70)+ 
									((pit_bd_ficha_pdn.at_org+ 
									pit_bd_ficha_pdn.vg_org+ 
									pit_bd_ficha_pdn.fer_org)*0.50)) AS aporte_total
									FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
									WHERE clar_bd_ficha_pdn.cod_clar='$id' AND 
									pit_bd_ficha_pdn.calificacion>=70";
									$result=mysql_query($sql) or die (mysql_error());
									$f16=mysql_fetch_array($result);
									?>	
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f16['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f16['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f16['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
									</p>
									<p><strong>S/. <? echo number_format($f16['aporte_total'],2);?></strong>, monto total, del cual S/. <? echo number_format($f16['aporte_pdss'],2);?> corresponde al Proyecto de Desarrollo Sierra Sur II y S/. <? echo number_format($f16['aporte_org'],2);?> al aporte de la organización.</p>
									<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>PLANES DE NEGOCIO INDEPENDIENTES - PRIMER DESEMBOLSO</strong></td>
										</tr>
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
									<?
									$nm=0;
									$sql="SELECT org_ficha_organizacion.n_documento, 
									org_ficha_organizacion.nombre, 
									pit_bd_ficha_pdn.calificacion, 
									sys_bd_dependencia.nombre AS oficina, 
									((pit_bd_ficha_pdn.total_apoyo+ 
									pit_bd_ficha_pdn.at_pdss+ 
									pit_bd_ficha_pdn.vg_pdss+ 
									pit_bd_ficha_pdn.fer_pdss)*0.70) AS aporte_pdss, 
									((pit_bd_ficha_pdn.at_org+ 
									pit_bd_ficha_pdn.vg_org+ 
									pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org, 
									((pit_bd_ficha_pdn.total_apoyo+ 
									pit_bd_ficha_pdn.at_pdss+ 
									pit_bd_ficha_pdn.vg_pdss+ 
									pit_bd_ficha_pdn.fer_pdss)*0.70)+
									((pit_bd_ficha_pdn.at_org+ 
									pit_bd_ficha_pdn.vg_org+ 
									pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_total
								FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
									 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
									 INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
								WHERE clar_bd_ficha_pdn_suelto.cod_clar='$id'
								ORDER BY pit_bd_ficha_pdn.calificacion DESC";
									$result=mysql_query($sql) or die (mysql_error());
									while($f17=mysql_fetch_array($result))
									{
										$nm++
									?>
										<tr>
											<td align="center"><? echo $nm;?></td>
											<td align="center"><? echo $f17['n_documento'];?></td>
											<td><? echo $f17['nombre'];?></td>
											<td align="center"><? echo number_format($f17['calificacion'],2);?></td>
											<td align="center"><? echo $f17['oficina'];?></td>
											<td><? if ($f17['calificacion']>=70) echo number_format($f17['aporte_pdss'],2);?></td>
											<td><? if ($f17['calificacion']>=70) echo number_format($f17['aporte_org'],2);?></td>
											<td><? if ($f17['calificacion']>=70) echo number_format($f17['aporte_total'],2);?></td>
											<td align="center"><? if ($f17['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
									<?
									}
									$sql="SELECT SUM((pit_bd_ficha_pdn.total_apoyo+
									pit_bd_ficha_pdn.at_pdss+ 
									pit_bd_ficha_pdn.vg_pdss+ 
									pit_bd_ficha_pdn.fer_pdss)*0.70) AS aporte_pdss, 
									SUM((pit_bd_ficha_pdn.at_org+ 
									pit_bd_ficha_pdn.vg_org+ 
									pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org, 
									SUM(((pit_bd_ficha_pdn.total_apoyo+ 
									pit_bd_ficha_pdn.at_pdss+ 
									pit_bd_ficha_pdn.vg_pdss+ 
									pit_bd_ficha_pdn.fer_pdss)*0.70)+ 
									((pit_bd_ficha_pdn.at_org+ 
									pit_bd_ficha_pdn.vg_org+ 
									pit_bd_ficha_pdn.fer_org)*0.50)) AS aporte_total
									FROM clar_bd_ficha_pdn_suelto INNER JOIN pit_bd_ficha_pdn ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
									WHERE clar_bd_ficha_pdn_suelto.cod_clar='$id' AND
									pit_bd_ficha_pdn.calificacion>=70";
									$result=mysql_query($sql) or die (mysql_error());
									$f18=mysql_fetch_array($result);
									?>	
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f18['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f18['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f18['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
									</p>
									<p><strong>S/. <? echo number_format($f18['aporte_total'],2);?></strong>, monto total, del cual S/. <? echo number_format($f18['aporte_pdss'],2);?> corresponde al Proyecto de Desarrollo Sierra Sur II y S/. <? echo number_format($f18['aporte_org'],2);?> al aporte de la organización.</p>
									<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>PLANES DE NEGOCIO - SEGUNDO DESEMBOLSO</strong></td>
										</tr>
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
										<?
										$nn=0;
										$sql="SELECT org_ficha_organizacion.n_documento, 
										org_ficha_organizacion.nombre, 
										sys_bd_dependencia.nombre AS oficina, 
										((pit_bd_ficha_pdn.total_apoyo+ 
										pit_bd_ficha_pdn.at_pdss+ 
										pit_bd_ficha_pdn.vg_pdss+ 
										pit_bd_ficha_pdn.fer_pdss)*0.30) AS aporte_pdss, 
										((pit_bd_ficha_pdn.at_org+ 
										pit_bd_ficha_pdn.vg_org+ 
										pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org, 
										((pit_bd_ficha_pdn.total_apoyo+ 
										pit_bd_ficha_pdn.at_pdss+ 
										pit_bd_ficha_pdn.vg_pdss+ 
										pit_bd_ficha_pdn.fer_pdss)*0.30)+
										((pit_bd_ficha_pdn.at_org+ 
										pit_bd_ficha_pdn.vg_org+ 
										pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_total, 
										pit_bd_ficha_pdn.calificacion_2 AS calificacion
										FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
										INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
										INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
										WHERE clar_bd_ficha_pdn_2.cod_clar='$id'
										ORDER BY calificacion DESC";
										$result=mysql_query($sql) or die (mysql_error());
										while($f19=mysql_fetch_array($result))
										{
											$nn++
										?>
										<tr>
											<td align="center"><? echo $nn;?></td>
											<td align="center"><? echo $f19['n_documento'];?></td>
											<td><? echo $f19['nombre'];?></td>
											<td align="center"><? echo number_format($f19['calificacion'],2);?></td>
											<td align="center"><? echo $f19['oficina'];?></td>
											<td><? if ($f19['calificacion']>=70) echo number_format($f19['aporte_pdss'],2);?></td>
											<td><? if ($f19['calificacion']>=70) echo number_format($f19['aporte_org'],2);?></td>
											<td><? if ($f19['calificacion']>=70) echo number_format($f19['aporte_total'],2);?></td>
											<td align="center"><? if ($f19['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
										<?
										}
										$sql="SELECT SUM((pit_bd_ficha_pdn.total_apoyo+
										pit_bd_ficha_pdn.at_pdss+ 
										pit_bd_ficha_pdn.vg_pdss+ 
										pit_bd_ficha_pdn.fer_pdss)*0.30) AS aporte_pdss, 
										SUM((pit_bd_ficha_pdn.at_org+ 
										pit_bd_ficha_pdn.vg_org+ 
										pit_bd_ficha_pdn.fer_org)*0.50) AS aporte_org, 
										SUM(((pit_bd_ficha_pdn.total_apoyo+ 
										pit_bd_ficha_pdn.at_pdss+ 
										pit_bd_ficha_pdn.vg_pdss+ 
										pit_bd_ficha_pdn.fer_pdss)*0.30)+ 
										((pit_bd_ficha_pdn.at_org+ 
										pit_bd_ficha_pdn.vg_org+ 
										pit_bd_ficha_pdn.fer_org)*0.50)) AS aporte_total
										FROM clar_bd_ficha_pdn_2 INNER JOIN pit_bd_ficha_pdn ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
										WHERE clar_bd_ficha_pdn_2.cod_clar='$id' AND
										pit_bd_ficha_pdn.calificacion_2>=70";
										$result=mysql_query($sql) or die (mysql_error());
										$f20=mysql_fetch_array($result);
										?>
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f20['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f20['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f20['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
									</p>
									<p><strong>S/. <? echo number_format($f20['aporte_total'],2);?></strong>, monto total, del cual S/. <? echo number_format($f20['aporte_pdss'],2);?> corresponde al Proyecto de Desarrollo Sierra Sur II y S/. <? echo number_format($f20['aporte_org'],2);?> al aporte de la organización.</p>
									<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>INVERSIONES PARA EL DESARROLLO LOCAL - PRIMER DESEMBOLSO</strong></td>
										</tr>
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
									<?
									$no=0;
									$sql="SELECT org_ficha_organizacion.n_documento, 
									org_ficha_organizacion.nombre, 
									pit_bd_ficha_idl.denominacion, 
									pit_bd_ficha_idl.calificacion, 
									(pit_bd_ficha_idl.aporte_org) AS aporte_org, 
									(pit_bd_ficha_idl.aporte_pdss) AS aporte_pdss, 
									sys_bd_dependencia.nombre AS oficina, 
									((pit_bd_ficha_idl.aporte_pdss)+ 
									(pit_bd_ficha_idl.aporte_org)) AS aporte_total
									FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl.cod_idl
									INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
									INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
									WHERE clar_bd_ficha_idl.cod_clar='$id'
									ORDER BY pit_bd_ficha_idl.calificacion DESC";
									$result=mysql_query($sql) or die(mysql_error());
									while($f21=mysql_fetch_array($result))
									{
										$no++
									?>
										<tr>
											<td align="center"><? echo $no;?></td>
											<td align="center"><? echo $f21['n_documento'];?></td>
											<td><? echo $f21['nombre'];?></td>
											<td align="center"><? echo number_format($f21['calificacion'],2);?></td>
											<td align="center"><? echo $f21['oficina'];?></td>
											<td><? if ($f21['calificacion']>=70) echo number_format($f21['aporte_pdss'],2);?></td>
											<td><? if ($f21['calificacion']>=70) echo number_format($f21['aporte_org'],2);?></td>
											<td><? if ($f21['calificacion']>=70) echo number_format($f21['aporte_total'],2);?></td>
											<td align="center"><? if ($f21['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
									<?
									}
									$sql="SELECT SUM((pit_bd_ficha_idl.aporte_pdss)) AS aporte_pdss, 
									SUM(pit_bd_ficha_idl.aporte_org) AS aporte_org, 
									SUM((pit_bd_ficha_idl.aporte_pdss)+ 
									(pit_bd_ficha_idl.aporte_org)) AS aporte_total
									FROM pit_bd_ficha_idl INNER JOIN clar_bd_ficha_idl ON pit_bd_ficha_idl.cod_ficha_idl = clar_bd_ficha_idl.cod_idl
									WHERE clar_bd_ficha_idl.cod_clar='$id'";
									$result=mysql_query($sql) or die (mysql_error());
									$f22=mysql_fetch_array($result);
									?>	
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f22['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f22['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f22['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
									</p>
									<p><strong>S/. <? echo number_format($f22['aporte_total'],2);?></strong>, monto total, del cual S/. <? echo number_format($f22['aporte_pdss'],2);?> corresponde al Proyecto de Desarrollo Sierra Sur II y S/. <? echo number_format($f22['aporte_org'],2);?> al aporte de la organización.</p>
									<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
									<thead>
										<tr>
											<td colspan="9" align="center"><strong>INVERSIONES PARA EL DESARROLLO LOCAL - SEGUNDO DESEMBOLSO</strong></td>
										</tr>
										<tr>
											<td rowspan="2" align="center"><strong>N°</strong></td>
											<td rowspan="2" align="center"><strong>DOCUMENTO</strong></td>
											<td rowspan="2"><strong>NOMBRE DE LA ORGANIZACION</strong></td>
											<td rowspan="2" align="center"><strong>PUNTAJE TOTAL</strong></td>
											<td rowspan="2" align="center"><strong>OFICINA</strong></td>
											<td colspan="3" align="center"><strong>MONTO A DESEMBOLSAR (S/.)</strong></td>
											<td rowspan="2" align="center"><strong>ESTADO</strong></td>
										</tr>
										<tr>
											<td align="center"><strong>PDSS</strong></td>
											<td align="center"><strong>ORG</strong></td>
											<td align="center"><strong>TOTAL</strong></td>
										</tr>
									</thead>  
									<tbody>
									<?
									$np=0;
									$sql="SELECT org_ficha_organizacion.n_documento, 
									org_ficha_organizacion.nombre, 
									pit_bd_ficha_idl.denominacion, 
									(pit_bd_ficha_idl.aporte_org*0.50) AS aporte_org, 
									(pit_bd_ficha_idl.aporte_pdss* 
									pit_bd_ficha_idl.segundo_pago/100) AS aporte_pdss, 
									sys_bd_dependencia.nombre AS oficina, 
									((pit_bd_ficha_idl.aporte_pdss* 
									pit_bd_ficha_idl.segundo_pago/100)+ 
									(pit_bd_ficha_idl.aporte_org*0.50)) AS aporte_total, 
									pit_bd_ficha_idl.calificacion_2 AS calificacion
									FROM pit_bd_ficha_idl INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
									INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
									INNER JOIN clar_bd_ficha_idl_2 ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
									WHERE clar_bd_ficha_idl_2.cod_clar='$id'
									ORDER BY pit_bd_ficha_idl.calificacion_2 DESC";
									$result=mysql_query($sql) or die (mysql_error());
									while($f23=mysql_fetch_array($result))
									{
										$np++
									?>
										<tr>
											<td align="center"><? echo $np;?></td>
											<td align="center"><? echo $f23['n_documento'];?></td>
											<td><? echo $f23['nombre']."/".$f23['denominacion'];?></td>
											<td align="center"><? echo number_format($f23['calificacion'],2);?></td>
											<td align="center"><? echo $f23['oficina'];?></td>
											<td><? if ($f23['calificacion']>=70) echo number_format($f23['aporte_pdss'],2);?></td>
											<td><? if ($f23['calificacion']>=70) echo number_format($f23['aporte_org'],2);?></td>
											<td><? if ($f23['calificacion']>=70) echo number_format($f23['aporte_total'],2);?></td>
											<td align="center"><? if ($f23['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></td>
										</tr>
									<?
									}
									$sql="SELECT SUM((pit_bd_ficha_idl.aporte_pdss* 
									pit_bd_ficha_idl.segundo_pago/100)) AS aporte_pdss, 
									SUM(pit_bd_ficha_idl.aporte_org*0.50) AS aporte_org, 
									SUM((pit_bd_ficha_idl.aporte_pdss* 
									pit_bd_ficha_idl.segundo_pago/100)+ 
									(pit_bd_ficha_idl.aporte_org*0.50)) AS aporte_total
									FROM clar_bd_ficha_idl_2 INNER JOIN pit_bd_ficha_idl ON clar_bd_ficha_idl_2.cod_idl = pit_bd_ficha_idl.cod_ficha_idl
									WHERE clar_bd_ficha_idl_2.cod_clar='$id' AND
									pit_bd_ficha_idl.calificacion_2>=70";
									$result=mysql_query($sql) or die (mysql_error());
									$f24=mysql_fetch_array($result);
									?>	
										<tr>
											<td colspan="5"><strong>TOTAL MONTOS A DESEMBOLSAR EN SOLES</strong></td>
											<td><strong><? echo number_format($f24['aporte_pdss'],2);?></strong></td>
											<td><strong><? echo number_format($f24['aporte_org'],2);?></td>
											<td><strong><? echo number_format($f24['aporte_total'],2);?></td>
											<td align="center">-</td>
										</tr>
									</tbody>
									</table>
									</p>
									<p><strong>S/. <? echo number_format($f24['aporte_total'],2);?></strong>, monto total, del cual S/. <? echo number_format($f24['aporte_pdss'],2);?> corresponde al Proyecto de Desarrollo Sierra Sur II y S/. <? echo number_format($f24['aporte_org'],2);?> al aporte de la organización.</p>
									<p>Se deja constancia que los documentos de calificación de los jurados, de cada una de las propuestas tanto en la Fase de Evaluación de Campo como en la Fase de Evaluación Pública, obran en poder de la Oficina Local de <? echo $r2['oficina'];?>, para los fines a que hubiera lugar.</p>
									<p>Se aclara que, en el caso de los emprendimientos que no alcanzaron calificación aprobatoria, éstos podrán presentarse hasta en dos oportunidades más, sea en un CLAR de la Oficina Local <? echo $r2['oficina'];?> o de cualquiera otra Oficina Local del Proyecto Sierra Sur II, para cuyo efecto coordinará las acciones necesarias con el Jefe de La Oficina Local.</p>
								</li>
								<li>Así mismo las 9.00 am se dio inicio al concurso de: 
								<p><strong>CONCURSO DE AVANCES Y RESULTADOS DE PLANES DE INVERSION TERRITORIAL</strong>,  participaron .............. Organizaciones de .............. oficinas locales y los resultados fueron:</p>
								<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
										<thead>
											<tr>
												<td align="center"><strong>N.</strong></td>
												<td align="center"><strong>OFICINA LOCAL</strong></td>
												<td align="center"><strong>ORGANIZACION PIT</strong></td>
												<td align="center"><strong>PUNTAJE</strong></td>
												<td align="center"><strong>PREMIO (S/.)</strong></td>
												<td align="center"><strong>LUGAR</strong></td>
											</tr>
										</thead>
										<tbody>
										<?
										$nq=0;
										$sql="SELECT sys_bd_dependencia.nombre AS oficina, 
										org_ficha_taz.nombre, 
										gcac_pit_participante_concurso.puntaje_total, 
										gcac_pit_participante_concurso.premio, 
										gcac_pit_participante_concurso.puesto
										FROM gcac_pit_participante_concurso INNER JOIN gcac_concurso_clar ON gcac_pit_participante_concurso.cod_concurso = gcac_concurso_clar.cod_concurso
										 INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = gcac_pit_participante_concurso.cod_pit
										 INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
										 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
										WHERE gcac_concurso_clar.f_concurso='".$r2['f_evento']."'
										ORDER BY gcac_pit_participante_concurso.puntaje_total DESC";
										$result=mysql_query($sql) or die (mysql_error());
										while($f25=mysql_fetch_array($result))
										{
												$premio_pit=$f25['premio'];
												$total_premio_pit=$total_premio_pit+$premio_pit;
												$nq++
										?>
											<tr>
												<td align="center"><? echo $nq;?></td>
												<td align="center"><? echo $f25['oficina'];?></td>
												<td><? echo $f25['nombre'];?></td>
												<td align="center"><? echo number_format($f25['puntaje_total'],2);?></td>
												<td align="right"><? echo number_format($f25['premio'],2);?></td>
												<td align="center"><? echo numeracion($f25['puesto']);?></td>
											</tr>
										<?
										}
										?>	
											<tr>
												<td colspan="4"><strong>TOTAL PREMIOS EN SOLES</strong></td>
												<td align="right"><strong><? echo number_format($total_premio_pit,2);?></strong></td>
												<td align="center">-</td>
											</tr>
										</tbody>
									</table>
								</p>
								<p>El monto total a premiar es <strong>S/. <? echo number_format($total_premio_pit,2);?></strong>, el 100% es aporte del Proyecto de Desarrollo Sierra Sur II.</p>

								<p><strong>CONCURSO DE COMIDAS TIPICAS.</strong> Participaron .............. Organizaciones de .............. oficinas locales y los resultados fueron:</p>

								<p>
									<table cellpadding="1" cellspacing="1" border="1" width="99%" align="center" class="mini">
										<thead>
											<tr>
												<td align="center"><strong>N.</strong></td>
												<td align="center"><strong>OFICINA LOCAL</strong></td>
												<td align="center"><strong>ORGANIZACION</strong></td>
												<td align="center"><strong>PARTICIPA CON</strong></td>
												<td align="center"><strong>PUNTAJE</strong></td>
												<td align="center"><strong>PREMIO (S/.)</strong></td>
												<td align="center"><strong>LUGAR</strong></td>
											</tr>
										</thead>
										<tbody>
										<?
										$nr=0;
										$sql="SELECT sys_bd_dependencia.nombre AS oficina, 
											org_ficha_organizacion.nombre, 
											gcac_participante_concurso.descripcion, 
											gcac_participante_concurso.puntaje, 
											gcac_participante_concurso.puesto, 
											gcac_participante_concurso.premio
										FROM gcac_participante_concurso INNER JOIN gcac_concurso_clar ON gcac_participante_concurso.cod_concurso = gcac_concurso_clar.cod_concurso
											 INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
											 INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
										WHERE gcac_concurso_clar.f_concurso='".$r2['f_evento']."' AND
										gcac_concurso_clar.cod_tipo_concurso=4
										ORDER BY gcac_participante_concurso.puntaje DESC";
										$result=mysql_query($sql) or die (mysql_error());
										while($f26=mysql_fetch_array($result))
										{
												$premio_comida=$f26['premio'];
												$total_premio_comida=$total_premio_comida+$premio_comida;
												$nr++
										?>
											<tr>
												<td align="center"><? echo $nr;?></td>
												<td align="center"><? echo $f26['oficina'];?></td>
												<td><? echo $f26['nombre'];?></td>
												<td><? echo $f26['descripcion'];?></td>
												<td align="center"><? echo number_format($f26['puntaje'],2);?></td>
												<td align="right"><? echo number_format($f26['premio'],2);?></td>
												<td align="center"><? echo numeracion($f26['puesto']);?></td>
											</tr>
										<?
										}
										?>	
											<tr>
												<td colspan="5"><strong>TOTAL PREMIOS EN SOLES</strong></td>
												<td align="right"><strong><? echo number_format($total_premio_comida,2);?></strong></td>
												<td align="center">-</td>
											</tr>
										</tbody>
										</table>
								</p>
								<p>El monto total a premiar es <strong>S/. <? echo number_format($total_premio_comida,2);?></strong>, el 100% es aporte del Proyecto de Desarrollo Sierra Sur II.</p>

								
								<?
								$total_aporte_pdss=$f8['aporte_pdss']+$f10['aporte_pdss']+$f12['aporte_pdss']+$f14['aporte_pdss']+$f16['aporte_pdss']+$f18['aporte_pdss']+$f20['aporte_pdss']+$f22['aporte_pdss']+$f24['aporte_pdss'];
								$total_aporte_org=$f8['aporte_org']+$f10['aporte_org']+$f12['aporte_org']+$f14['aporte_org']+$f16['aporte_org']+$f18['aporte_org']+$f20['aporte_org']+$f22['aporte_org']+$f24['aporte_org'];
								$total_aporte_total=$f8['aporte_total']+$f10['aporte_total']+$f12['aporte_total']+$f14['aporte_total']+$f16['aporte_total']+$f18['aporte_total']+$f20['aporte_total']+$f22['aporte_total']+$f24['aporte_total'];
								?>
								<p>El monto total aprobado en <? echo $r2['nombre'];?> <? echo date(Y);?> es de <strong>S/. <? echo number_format($total_aporte_total,2);?></strong>;  del cual <strong>S/. <? echo number_format($total_aporte_pdss,2);?></strong> corresponde al Proyecto de Desarrollo Sierra Sur II y <strong>S/. <? echo number_format($total_aporte_org,2);?></strong> al aporte de las organizaciones.</p>
								<p>No Habiendo otros asuntos más que tratar, siendo las 16.00 horas, se dio por concluida la reunión, firmando los jurados participantes, en señal de conformidad.</p>
								</li>
							</ol>
						</p>
						

						
					</div>
<?
}
else
{
	echo $r3['contenido'];
}	
?>				
				</textarea>
				</div>

				<div class="row">
					<div class="twelve columns"><br/></div>
					<div class="twelve columns">
						<button type="submit" class="primary button">Guardar cambios</button>
						<a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar operación</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>
</div>

<!-- Footer -->
<? include("../footer.php");?>

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
