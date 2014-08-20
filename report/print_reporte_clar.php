<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT clar_bd_evento_clar.nombre, 
  clar_bd_evento_clar.f_campo1, 
  clar_bd_evento_clar.f_campo2, 
  clar_bd_evento_clar.f_evento, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  clar_bd_evento_clar.lugar, 
  sys_bd_dependencia.nombre AS oficina, 
  sys_bd_tipo_clar.descripcion AS tipo_evento, 
  clar_bd_evento_clar.objetivo, 
  clar_bd_evento_clar.resultado, 
  clar_bd_evento_clar.cod_clar, 
  sys_bd_distrito.latitud, 
  sys_bd_distrito.longitud, 
  sys_bd_distrito.altitud, 
  sys_bd_distrito.ubigeo, 
  sys_bd_distrito.nivel_pobreza, 
  sys_bd_distrito.poblacion, 
  clar_bd_evento_clar.concurso_danza, 
  clar_bd_evento_clar.concurso_comida, 
  clar_bd_evento_clar.concurso_pdn, 
  clar_bd_evento_clar.concurso_mapa
FROM sys_bd_departamento INNER JOIN clar_bd_evento_clar ON sys_bd_departamento.cod = clar_bd_evento_clar.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = clar_bd_evento_clar.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = clar_bd_evento_clar.cod_dist
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = clar_bd_evento_clar.cod_dependencia
   INNER JOIN sys_bd_tipo_clar ON sys_bd_tipo_clar.cod = clar_bd_evento_clar.cod_tipo_clar
WHERE clar_bd_evento_clar.cod_clar='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$latitud=$r1['latitud'];
$longitud=$r1['longitud'];

//hago calculos para desembolso
$sql="SELECT SUM(((pit_bd_ficha_mrn.cif_pdss+ 
  pit_bd_ficha_mrn.at_pdss+ 
  pit_bd_ficha_mrn.vg_pdss+ 
  pit_bd_ficha_mrn.ag_pdss)*0.70)) AS deposito
FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
WHERE clar_bd_ficha_mrn.cod_clar='$cod' AND
pit_bd_ficha_mrn.calificacion>=70";
$result=mysql_query($sql) or die (mysql_error());
$f6=mysql_fetch_array($result);

$sql="SELECT SUM(((pit_bd_ficha_mrn.cif_pdss+ 
  pit_bd_ficha_mrn.at_pdss+ 
  pit_bd_ficha_mrn.vg_pdss+ 
  pit_bd_ficha_mrn.ag_pdss)*0.30)) AS deposito
FROM clar_bd_ficha_mrn_2 INNER JOIN pit_bd_ficha_mrn ON clar_bd_ficha_mrn_2.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE clar_bd_ficha_mrn_2.cod_clar='$cod' AND
pit_bd_ficha_mrn.calificacion>=70";
$result=mysql_query($sql) or die (mysql_error());
$f7=mysql_fetch_array($result);

$sql="SELECT SUM(((pit_bd_ficha_pdn.total_apoyo+
  pit_bd_ficha_pdn.at_pdss+ 
  pit_bd_ficha_pdn.vg_pdss+ 
  pit_bd_ficha_pdn.fer_pdss)*0.70)) AS deposito
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
WHERE clar_bd_ficha_pdn.cod_clar='$cod' AND
pit_bd_ficha_pdn.calificacion>=70";
$result=mysql_query($sql) or die (mysql_error());
$f8=mysql_fetch_array($result);

$sql="SELECT SUM(((pit_bd_ficha_pdn.total_apoyo+
  pit_bd_ficha_pdn.at_pdss+ 
  pit_bd_ficha_pdn.vg_pdss+ 
  pit_bd_ficha_pdn.fer_pdss)*0.30)) AS deposito
FROM clar_bd_ficha_pdn_2 INNER JOIN pit_bd_ficha_pdn ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_bd_ficha_pdn_2.cod_clar='$cod' AND
pit_bd_ficha_pdn.calificacion>=70";
$result=mysql_query($sql) or die (mysql_error());
$f9=mysql_fetch_array($result);

$sql="SELECT SUM(((pit_bd_ficha_pdn.total_apoyo+
  pit_bd_ficha_pdn.at_pdss+ 
  pit_bd_ficha_pdn.vg_pdss+ 
  pit_bd_ficha_pdn.fer_pdss)*0.70)) AS deposito
FROM clar_bd_ficha_pdn_suelto INNER JOIN pit_bd_ficha_pdn ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_bd_ficha_pdn_suelto.cod_clar='$cod' AND
pit_bd_ficha_pdn.calificacion>=70";
$result=mysql_query($sql) or die (mysql_error());
$f10=mysql_fetch_array($result);

$sql="SELECT SUM((pit_bd_ficha_pit.aporte_pdss*0.70)) AS deposito
FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
WHERE clar_bd_ficha_pit.cod_clar='$cod' AND
pit_bd_ficha_pit.calificacion>=70";
$result=mysql_query($sql) or die (mysql_error());
$f11=mysql_fetch_array($result);

$sql="SELECT SUM((pit_bd_ficha_pit.aporte_pdss*0.30)) AS deposito
FROM clar_bd_ficha_pit_2 INNER JOIN pit_bd_ficha_pit ON clar_bd_ficha_pit_2.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE clar_bd_ficha_pit_2.cod_clar='$cod' AND
pit_bd_ficha_pit.calificacion>=70";
$result=mysql_query($sql) or die (mysql_error());
$f12=mysql_fetch_array($result);

$total_deposito=$f6['deposito']+$f7['deposito']+$f8['deposito']+$f9['deposito']+$f10['deposito']+$f11['deposito']+$f12['deposito'];

//Hago calculo para las familias
$sql="SELECT COUNT(org_ficha_usuario.n_documento) AS familia
FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE clar_bd_ficha_mrn.cod_clar='$cod' AND
org_ficha_usuario.titular=1";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);

$sql="SELECT COUNT(org_ficha_usuario.n_documento) AS familia
FROM pit_bd_ficha_mrn INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
   INNER JOIN clar_bd_ficha_mrn_2 ON clar_bd_ficha_mrn_2.cod_mrn = pit_bd_ficha_mrn.cod_mrn
WHERE clar_bd_ficha_mrn_2.cod_clar='$cod' AND
org_ficha_usuario.titular=1";
$result=mysql_query($sql) or die (mysql_error());
$i2=mysql_fetch_array($result);

$sql="SELECT COUNT(org_ficha_usuario.n_documento) AS familia
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
WHERE clar_bd_ficha_pdn.cod_clar='$cod' AND
org_ficha_usuario.titular=1";
$result=mysql_query($sql) or die (mysql_error());
$i3=mysql_fetch_array($result);

$sql="SELECT COUNT(org_ficha_usuario.n_documento) AS familia
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_bd_ficha_pdn_2.cod_clar='$cod' AND
org_ficha_usuario.titular=1";
$result=mysql_query($sql) or die (mysql_error());
$i4=mysql_fetch_array($result);

$sql="SELECT COUNT(org_ficha_usuario.n_documento) AS familia
FROM pit_bd_ficha_pdn INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN clar_bd_ficha_pdn_suelto ON clar_bd_ficha_pdn_suelto.cod_pdn = pit_bd_ficha_pdn.cod_pdn
WHERE clar_bd_ficha_pdn_suelto.cod_clar='$cod' AND
org_ficha_usuario.titular=1";
$result=mysql_query($sql) or die (mysql_error());
$i5=mysql_fetch_array($result);

$total_familia=$i1['familia']+$i2['familia']+$i3['familia']+$i4['familia']+$i5['familia'];
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
   
  <link href="../stylesheets/printer.css" rel="stylesheet" type="text/css" media="print">
  <link rel="stylesheet" href="../stylesheets/foundation.css">
  <link rel="stylesheet" href="../stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="../stylesheets/app.css">
  <link rel="stylesheet" href="../rtables/responsive-tables.css">
  
  <script src="../javascripts/modernizr.foundation.js"></script>
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
    <style>
      html, body, #map-canvas {
        margin: 0;
        padding: 0;
        height: 92%;
      }
    </style>
    
  <style type="text/css">
      @media print 
      {
        .oculto {display:none;background-color: #333;color:#FFF; font: bold 84% 'trebuchet ms',helvetica,sans-serif;  }
      }
      @page { size: A4; }
  </style>
  
  <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAjU0EJWnWPMv7oQ-jjS7dYxSPW5CJgpdgO_s4yyMovOaVh_KvvhSfpvagV18eOyDWu7VytS6Bi1CWxw"
      type="text/javascript"></script>  

  <script type="text/javascript">
    //<![CDATA[
    var map;

    function load() {
      map = new GMap(document.getElementById("map"));
      map.setCenter(new GLatLng(-<? echo $latitud;?>, -<? echo $longitud;?>), 13);
      //map.addControl(new GSmallMapControl());
      map.addControl(new GMapTypeControl());
      map.addOverlay(new GLayer("com.panoramio.all"));
      map.addOverlay(new GLayer("org.wikipedia.en"));
      map.addOverlay(new GLayer("com.google.webcams"));
    }

    //]]>
    </script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<!-- Grafico 1 -->
<script type="text/javascript">
var chart;
$(document).ready(function() 
{
//GRAFICO 1 
chart = new Highcharts.Chart({
chart: {
renderTo: 'container_1',
defaultSeriesType: 'bar'
},
title: {
text: 'Nº de Familias presentadas por iniciativa'
},
subtitle: {
text: 'Fuente : NEC PDSS II'
},
xAxis: {
categories: ['PDN Primer Desembolso', 'PGRN Primer Desembolso', 'PDN Segundo Desembolso', 'PGRN Segundo Desembolso'],
title: {
text: null
}
},
yAxis: {
min: 0,
title: {
text: 'Nº de Familias',
align: 'high'
}
},
tooltip: {
formatter: function() {
return ''+
  this.series.name +': '+ this.y +' Familias';
}
},
plotOptions: {
bar: {
dataLabels: {
enabled: true
            }
      }
            },
            
legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
         },           

credits: {
enabled: false
},
series: 
[ 
{ 
name: 'Familias presentadas',
data: [<? echo $i3['familia']+$i5['familia'];?>, <? echo $i1['familia'];?>, <? echo $i4['familia'];?>, <? echo $i2['familia'];?>]
} 
]
});
});
</script>

</head>
<body onload="load()" onunload="GUnload()">
<!-- Cargo los plugins para la grafica -->
<script type="text/javascript" src="../plugins/grafica/js/highcharts.js"></script>
<script type="text/javascript" src="../plugins/grafica/js/modules/exporting.js"></script>

<!-- Iniciamos el contenido -->
<div class="twelve columns">
  <!-- Titulos -->
    <div class="row">
      <div class="twelve columns centrado"><h6>RESUMEN EVENTO CLAR DENOMINADO: <? echo $r1['nombre'];?></h6></div>
      <hr/>
      <div class="twelve columns"><h6>I.- DATOS SOBRE LA UBICACION DEL EVENTO</h6></div>
      <div class="two columns"><h6>DEPARTAMENTO</h6></div>
      <div class="two columns"><h6>PROVINCIA</h6></div>
      <div class="two columns"><h6>DISTRITO</h6></div>
      <div class="six columns"><h6>LUGAR DE REALIZACIÓN</h6></div>
      <div class="two columns"><p><? echo $r1['departamento'];?></p></div>
      <div class="two columns"><p><? echo $r1['provincia'];?></p></div>
      <div class="two columns"><p><? echo $r1['distrito'];?></p></div>
      <div class="six columns"><p><? echo $r1['lugar'];?></p></div>
    </div>

    <div class="row">
     <div class="six columns">
       <div class="row">
        <div class="twelve columns"><h6>1.1. DATOS SOBRE EL DISTRITO</h6></div>
         <div class="twelve columns"><h6>OFICINA LOCAL</h6></div>
         <div class="twelve columns"><p><? echo $r1['oficina'];?></p></div>
         <div class="four columns"><h6>LATITUD</h6></div>
         <div class="four columns"><h6>LONGITUD</h6></div>
         <div class="four columns"><h6>ALTITUD</h6></div>
         <div class="four columns"><p><? echo $r1['latitud'];?></p></div>
         <div class="four columns"><p><? echo $r1['longitud'];?></p></div>
         <div class="four columns"><p><? echo $r1['altitud'];?> m.s.n.m</p></div>
         <div class="four columns"><h6>UBIGEO</h6></div>
         <div class="four columns"><h6>NIVEL DE POBREZA</h6></div>
         <div class="four columns"><h6>POBLACION</h6></div>
         <div class="four columns"><p><? echo $r1['ubigeo'];?></p></div>
         <div class="four columns"><p>QUINTIL <? echo $r1['nivel_pobreza'];?></p></div>
         <div class="four columns"><p><? echo number_format($r1['poblacion']);?> familias</p></div>
       </div>
     </div>
     <div class="six columns">  
       <p><h6>1.2. UBICACION EN EL MAPA</h6></p>
       <p><iframe src="http://www.panoramio.com/plugin/?lt=-<? echo $latitud;?>&amp;ln=-<? echo $longitud;?>&amp;z=3&amp;k=0" width="450px" height="250px"></iframe></p>
     </div> 
    </div>

    <div class="row">
      <div class="twelve columns"><h6>1.3. DATOS SOBRE EL EVENTO</h6></div>
      <div class="four columns"><h6>Fecha de inicio de evaluación de campo</h6></div>
      <div class="four columns"><h6>Fecha de termino de evaluación de campo</h6></div>
      <div class="four columns"><h6>Fecha de evaluación pública</h6></div>
      <div class="four columns"><p><? echo fecha_normal($r1['f_campo1']);?></p></div>
      <div class="four columns"><p><? echo fecha_normal($r1['f_campo2']);?></p></div>
      <div class="four columns"><p><? echo fecha_normal($r1['f_evento']);?></p></div>
      <div class="twelve columns"><h6>1.4. OBJETIVO DEL EVENTO</h6></div>
      <div class="twelve columns"><p><? echo $r1['objetivo'];?></p></div>
    </div>

<div class="row">
<div class="twelve columns"><h6>II.- CONCURSOS REALIZADOS EN EL EVENTO</h6></div>
</div>
<div class="row">
<div class="three columns"><h6>Se realizó concurso de PIT's?</h6></div>
<div class="three columns"><h6>Se realizó concurso de Planes de Negocio?</h6></div>
<div class="three columns"><h6>Se realizó concurso de Gastronomía?</h6></div>
<div class="three columns"><h6>Se realizó concurso de Danzas?</h6></div>
</div>
<div class="row">
<div class="three columns"><p><? if ($r1['concurso_mapa']==1) echo "SI"; else echo "NO";?></p></div>
<div class="three columns"><p><? if ($r1['concurso_pdn']==1) echo "SI"; else echo "NO";?></p></div>
<div class="three columns"><p><? if ($r1['concurso_comida']==1) echo "SI"; else echo "NO";?></p></div>
<div class="three columns"><p><? if ($r1['concurso_danza']==1) echo "SI"; else echo "NO";?></p></div>
<div class="twelve columns"><h6>2.1. CUADRO DE RESULTADOS </h6></div>
</div>    

<div class="row">
  <div class="twelve columns">
    <table>
      <thead>
        <tr>
          <th><small>TIPO CONCURSO</small></th>
          <th><small>NOMBRE DEL CONCURSO</small></th>
          <th><small>NOMBRE DEL PARTICIPANTE</small></th>
          <th><small>NOMBRE DEL PLATO / DANZA</small></th>
          <th><small>OFICINA</small></th>
          <th><small>PUNTAJE</small></th>
          <th><small>PUESTO</small></th>
          <th><small>PREMIO (S/.)</small></th>
        </tr>
      </thead>

      <tbody>
    <?
    $sql="SELECT sys_bd_tipo_concurso_clar.descripcion AS tipo_concurso, 
    gcac_concurso_clar.nombre AS concurso, 
    org_ficha_organizacion.nombre, 
    gcac_participante_concurso.descripcion, 
    sys_bd_dependencia.nombre AS oficina, 
    gcac_participante_concurso.puesto, 
    gcac_participante_concurso.puntaje, 
    gcac_participante_concurso.premio
    FROM sys_bd_tipo_concurso_clar INNER JOIN gcac_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
    INNER JOIN gcac_participante_concurso ON gcac_participante_concurso.cod_concurso = gcac_concurso_clar.cod_concurso
    INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
    INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
    WHERE gcac_concurso_clar.f_concurso='".$r1['f_evento']."' AND
    gcac_concurso_clar.cod_tipo_concurso=1
    ORDER BY gcac_concurso_clar.cod_concurso ASC, gcac_participante_concurso.puntaje DESC";
    $result=mysql_query($sql) or die (mysql_error());
    while($r2=mysql_fetch_array($result))
    {
    ?>
        <tr>
          <td><small><? echo $r2['tipo_concurso'];?></small></td>
          <td><small><? echo $r2['concurso'];?></small></td>
          <td><small><? echo $r2['nombre'];?></small></td>
          <td><small><? echo $r2['descripcion'];?></small></td>
          <td><small><? echo $r2['oficina'];?></small></td>
          <td><small><? echo number_format($r2['puntaje'],2);?></small></td>
          <td><small><? echo numeracion($r2['puesto']);?></small></td>
          <td><small><? echo number_format($r2['premio'],2);?></small></td>
        </tr>
     <?
     }
    $sql="SELECT sys_bd_tipo_concurso_clar.descripcion AS tipo_concurso, 
    gcac_concurso_clar.nombre AS concurso, 
    org_ficha_organizacion.nombre, 
    gcac_participante_concurso.descripcion, 
    sys_bd_dependencia.nombre AS oficina, 
    gcac_participante_concurso.puesto, 
    gcac_participante_concurso.puntaje, 
    gcac_participante_concurso.premio
    FROM sys_bd_tipo_concurso_clar INNER JOIN gcac_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
    INNER JOIN gcac_participante_concurso ON gcac_participante_concurso.cod_concurso = gcac_concurso_clar.cod_concurso
    INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
    INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
    WHERE gcac_concurso_clar.f_concurso='".$r1['f_evento']."' AND
    gcac_concurso_clar.cod_tipo_concurso=2
    ORDER BY gcac_concurso_clar.cod_concurso ASC, gcac_participante_concurso.puntaje DESC";
    $result=mysql_query($sql) or die (mysql_error());
    while($r3=mysql_fetch_array($result))
    {
     ?>   
        <tr>
          <td><small><? echo $r3['tipo_concurso'];?></small></td>
          <td><small><? echo $r3['concurso'];?></small></td>
          <td><small><? echo $r3['nombre'];?></small></td>
          <td><small><? echo $r3['descripcion'];?></small></td>
          <td><small><? echo $r3['oficina'];?></small></td>
          <td><small><? echo number_format($r3['puntaje'],2);?></small></td>
          <td><small><? echo numeracion($r3['puesto']);?></small></td>
          <td><small><? echo number_format($r3['premio'],2);?></small></td>
        </tr>
     <?
     }
    $sql="SELECT sys_bd_tipo_concurso_clar.descripcion AS tipo_concurso, 
    gcac_concurso_clar.nombre AS concurso, 
    org_ficha_organizacion.nombre, 
    gcac_participante_concurso.descripcion, 
    sys_bd_dependencia.nombre AS oficina, 
    gcac_participante_concurso.puesto, 
    gcac_participante_concurso.puntaje, 
    gcac_participante_concurso.premio
    FROM sys_bd_tipo_concurso_clar INNER JOIN gcac_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
    INNER JOIN gcac_participante_concurso ON gcac_participante_concurso.cod_concurso = gcac_concurso_clar.cod_concurso
    INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
    INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
    WHERE gcac_concurso_clar.f_concurso='".$r1['f_evento']."' AND
    gcac_concurso_clar.cod_tipo_concurso=3
    ORDER BY gcac_concurso_clar.cod_concurso ASC, gcac_participante_concurso.puntaje DESC";
    $result=mysql_query($sql) or die (mysql_error());
    while($r4=mysql_fetch_array($result))
    {     
     ?>   
        <tr>
          <td><small><? echo $r4['tipo_concurso'];?></small></td>
          <td><small><? echo $r4['concurso'];?></small></td>
          <td><small><? echo $r4['nombre'];?></small></td>
          <td><small><? echo $r4['descripcion'];?></small></td>
          <td><small><? echo $r4['oficina'];?></small></td>
          <td><small><? echo number_format($r4['puntaje'],2);?></small></td>
          <td><small><? echo numeracion($r4['puesto']);?></small></td>
          <td><small><? echo number_format($r4['premio'],2);?></small></td>
        </tr>
     <?
     }
    $sql="SELECT sys_bd_tipo_concurso_clar.descripcion AS tipo_concurso, 
    gcac_concurso_clar.nombre AS concurso, 
    org_ficha_organizacion.nombre, 
    gcac_participante_concurso.descripcion, 
    sys_bd_dependencia.nombre AS oficina, 
    gcac_participante_concurso.puesto, 
    gcac_participante_concurso.puntaje, 
    gcac_participante_concurso.premio
    FROM sys_bd_tipo_concurso_clar INNER JOIN gcac_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
    INNER JOIN gcac_participante_concurso ON gcac_participante_concurso.cod_concurso = gcac_concurso_clar.cod_concurso
    INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
    INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
    WHERE gcac_concurso_clar.f_concurso='".$r1['f_evento']."' AND
    gcac_concurso_clar.cod_tipo_concurso=4
    ORDER BY gcac_concurso_clar.cod_concurso ASC, gcac_participante_concurso.puntaje DESC";
    $result=mysql_query($sql) or die (mysql_error());
    while($r5=mysql_fetch_array($result))
    {     
     ?>   
        <tr>
          <td><small><? echo $r5['tipo_concurso'];?></small></td>
          <td><small><? echo $r5['concurso'];?></small></td>
          <td><small><? echo $r5['nombre'];?></small></td>
          <td><small><? echo $r5['descripcion'];?></small></td>
          <td><small><? echo $r5['oficina'];?></small></td>
          <td><small><? echo number_format($r5['puntaje'],2);?></small></td>
          <td><small><? echo numeracion($r5['puesto']);?></small></td>
          <td><small><? echo number_format($r5['premio'],2);?></small></td>
        </tr>
     <?
     }
    $sql="SELECT sys_bd_tipo_concurso_clar.descripcion AS tipo_concurso, 
    gcac_concurso_clar.nombre AS concurso, 
    org_ficha_organizacion.nombre, 
    gcac_participante_concurso.descripcion, 
    sys_bd_dependencia.nombre AS oficina, 
    gcac_participante_concurso.puesto, 
    gcac_participante_concurso.puntaje, 
    gcac_participante_concurso.premio
    FROM sys_bd_tipo_concurso_clar INNER JOIN gcac_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
    INNER JOIN gcac_participante_concurso ON gcac_participante_concurso.cod_concurso = gcac_concurso_clar.cod_concurso
    INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = gcac_participante_concurso.cod_tipo_doc AND org_ficha_organizacion.n_documento = gcac_participante_concurso.n_documento
    INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
    WHERE gcac_concurso_clar.f_concurso='".$r1['f_evento']."' AND
    gcac_concurso_clar.cod_tipo_concurso=5
    ORDER BY gcac_concurso_clar.cod_concurso ASC, gcac_participante_concurso.puntaje DESC";
    $result=mysql_query($sql) or die (mysql_error());
    while($r6=mysql_fetch_array($result))
    {     
     ?>   
        <tr>
          <td><small><? echo $r6['tipo_concurso'];?></small></td>
          <td><small><? echo $r6['concurso'];?></small></td>
          <td><small><? echo $r6['nombre'];?></small></td>
          <td><small><? echo $r6['descripcion'];?></small></td>
          <td><small><? echo $r6['oficina'];?></small></td>
          <td><small><? echo number_format($r6['puntaje'],2);?></small></td>
          <td><small><? echo numeracion($r6['puesto']);?></small></td>
          <td><small><? echo number_format($r6['premio'],2);?></small></td>
        </tr>
     <?
     }
     $sql="SELECT sys_bd_tipo_concurso_clar.descripcion AS tipo_concurso, 
      gcac_concurso_clar.nombre AS concurso, 
      org_ficha_taz.nombre, 
      sys_bd_dependencia.nombre AS oficina, 
      gcac_pit_participante_concurso.puntaje_total, 
      gcac_pit_participante_concurso.puesto, 
      gcac_pit_participante_concurso.premio, 
      gcac_pit_participante_concurso.plato, 
      gcac_pit_participante_concurso.danza
      FROM sys_bd_tipo_concurso_clar INNER JOIN gcac_concurso_clar ON sys_bd_tipo_concurso_clar.codigo = gcac_concurso_clar.cod_tipo_concurso
     INNER JOIN gcac_pit_participante_concurso ON gcac_pit_participante_concurso.cod_concurso = gcac_concurso_clar.cod_concurso
     INNER JOIN pit_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = gcac_pit_participante_concurso.cod_pit
     INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
     INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
    WHERE gcac_concurso_clar.f_concurso='".$r1['f_evento']."' AND
    gcac_concurso_clar.cod_tipo_concurso=6
    ORDER BY gcac_concurso_clar.cod_concurso ASC, gcac_pit_participante_concurso.puntaje_total DESC";
    $result=mysql_query($sql) or die (mysql_error());
    while($r7=mysql_fetch_array($result))
    {
     ?>
        <tr>
          <td><small><? echo $r7['tipo_concurso'];?></small></td>
          <td><small><? echo $r7['concurso'];?></small></td>
          <td><small><? echo $r7['nombre'];?></small></td>
          <td><small><? echo "Plato: ".$r7['plato']."<br/> Danza: ".$r7['danza'];?></small></td>
          <td><small><? echo $r7['oficina'];?></small></td>
          <td><small><? echo number_format($r7['puntaje_total'],2);?></small></td>
          <td><small><? echo numeracion($r7['puesto']);?></small></td>
          <td><small><? echo number_format($r7['premio'],2);?></small></td>
        </tr>
     <?
     }

    $sql="SELECT SUM(gcac_concurso_clar.premio) AS premio
    FROM gcac_concurso_clar
    WHERE gcac_concurso_clar.f_concurso='".$r1['f_evento']."'";
    $result=mysql_query($sql) or die (mysql_error());
    $r6=mysql_fetch_array($result);
     ?>   

     <tr>
       <td colspan="7"><h6><small>TOTALES</small></h6></td>
       <td><h6><small><? echo number_format($r6['premio'],2);?></small></h6></td>
     </tr>

      </tbody>

    </table>
  </div>
</div>



<div class="row">
<div class="twelve columns"><h6>III.- INICIATIVAS PARTICIPANTES</h6></div>
<div class="three columns"><h6></h6></div>
</div>

<div class="row">
<div class="twelve columns">
    <table class="responsive">
      <thead>
        <tr>
          <th><small>N. DOCUMENTO</small></th>
          <th><small>TIPO DE INICIATIVA</small></th>
          <th><small>NOMBRE</small></th>
          <th><small>TIPO DE ORGANIZACION</small></th>
          <th><small>DEPARTAMENTO</small></th>
          <th><small>PROVINCIA</small></th>
          <th><small>DISTRITO</small></th>
          <th><small>OFICINA</small></th>
          <th><small>FAMILIAS</small></th>
          <th><small>PUNTAJE</small></th>
          <th><small>ESTADO</small></th>
          <th><small>MONTO A DEPOSITAR</small></th>
        </tr>
      </thead>

      <tbody>
 <?
 $sql="SELECT org_ficha_taz.n_documento, 
  sys_bd_tipo_iniciativa.codigo_iniciativa AS iniciativa, 
  org_ficha_taz.nombre, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  sys_bd_dependencia.nombre AS oficina, 
  pit_bd_ficha_pit.calificacion, 
  (pit_bd_ficha_pit.aporte_pdss*0.70) AS deposito
FROM pit_bd_ficha_pit INNER JOIN clar_bd_ficha_pit ON pit_bd_ficha_pit.cod_pit = clar_bd_ficha_pit.cod_pit
   INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
WHERE clar_bd_ficha_pit.cod_clar='$cod' AND
pit_bd_ficha_pit.calificacion<>0";
  $result=mysql_query($sql) or die (mysql_error());
  while($i6=mysql_fetch_array($result))
  {
?>
        <tr>
          <td><small><? echo $i6['n_documento'];?></small></td>
          <td><small><? echo $i6['iniciativa'];?></small></td>
          <td><small><? echo $i6['nombre'];?></small></td>
          <td><small><? echo $i6['tipo_org'];?></small></td>
          <td><small><? echo $i6['departamento'];?></small></td>
          <td><small><? echo $i6['provincia'];?></small></td>
          <td><small><? echo $i6['distrito'];?></small></td>
          <td><small><? echo $i6['oficina'];?></small></td>
          <td><small>-</small></td>
          <td><small><? echo number_format($i6['calificacion'],2);?></small></td>
          <td><small><? if ($i6['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></small></td>
          <td><small><? echo number_format($i6['deposito'],2);?></small></td>
        </tr>
<?
  }
  $sql="SELECT org_ficha_taz.n_documento, 
  sys_bd_tipo_iniciativa.codigo_iniciativa AS iniciativa, 
  org_ficha_taz.nombre, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  sys_bd_dependencia.nombre AS oficina, 
  (pit_bd_ficha_pit.aporte_pdss*0.30) AS deposito, 
  pit_bd_ficha_pit.calificacion_2 AS calificacion
FROM pit_bd_ficha_pit INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_taz.cod_tipo_org
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_taz.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_taz.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_taz.cod_dist
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
   INNER JOIN clar_bd_ficha_pit_2 ON clar_bd_ficha_pit_2.cod_pit = pit_bd_ficha_pit.cod_pit
WHERE clar_bd_ficha_pit_2.cod_clar='$cod' AND
pit_bd_ficha_pit.calificacion_2<>0";
$result=mysql_query($sql) or die (mysql_error());
while($i7=mysql_fetch_array($result))
{
?>
        <tr>
          <td><small><? echo $i7['n_documento'];?></small></td>
          <td><small><? echo $i7['iniciativa'];?></small></td>
          <td><small><? echo $i7['nombre'];?></small></td>
          <td><small><? echo $i7['tipo_org'];?></small></td>
          <td><small><? echo $i7['departamento'];?></small></td>
          <td><small><? echo $i7['provincia'];?></small></td>
          <td><small><? echo $i7['distrito'];?></small></td>
          <td><small><? echo $i7['oficina'];?></small></td>
          <td><small>-</small></td>
          <td><small><? echo number_format($i7['calificacion'],2);?></small></td>
          <td><small><? if ($i7['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></small></td>
          <td><small><? echo number_format($i7['deposito'],2);?></small></td>
        </tr>
<?
}
 $sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  sys_bd_cp.nombre AS cp, 
  sys_bd_dependencia.nombre AS oficina, 
  COUNT(pit_bd_user_iniciativa.n_documento) AS familias, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  ((pit_bd_ficha_mrn.cif_pdss+ 
  pit_bd_ficha_mrn.at_pdss+ 
  pit_bd_ficha_mrn.vg_pdss+ 
  pit_bd_ficha_mrn.ag_pdss)*0.70) AS deposito, 
  pit_bd_ficha_mrn.calificacion, 
  pit_bd_ficha_mrn.cod_mrn, 
  sys_bd_tipo_iniciativa.codigo_iniciativa AS iniciativa
FROM pit_bd_ficha_mrn INNER JOIN clar_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = clar_bd_ficha_mrn.cod_mrn
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
   LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
WHERE clar_bd_ficha_mrn.cod_clar='$cod' AND
org_ficha_usuario.titular=1
GROUP BY pit_bd_ficha_mrn.cod_mrn";
$result=mysql_query($sql) or die (mysql_error());
while($f1=mysql_fetch_array($result))
{
 ?>     
        <tr>
          <td><small><? echo $f1['n_documento'];?></small></td>
          <td><small><? echo $f1['iniciativa'];?></small></td>
          <td><small><? echo $f1['nombre'];?></small></td>
          <td><small><? echo $f1['tipo_org'];?></small></td>
          <td><small><? echo $f1['departamento'];?></small></td>
          <td><small><? echo $f1['provincia'];?></small></td>
          <td><small><? echo $f1['distrito'];?></small></td>
          <td><small><? echo $f1['oficina'];?></small></td>
          <td><small><? echo number_format($f1['familias']);?></small></td>
          <td><small><? echo number_format($f1['calificacion'],2);?></small></td>
          <td><small><? if ($f1['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></small></td>
          <td><small><? echo number_format($f1['deposito'],2);?></small></td>
        </tr>
<?
}
$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  sys_bd_cp.nombre AS cp, 
  sys_bd_dependencia.nombre AS oficina, 
  COUNT(pit_bd_user_iniciativa.n_documento) AS familias, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  ((pit_bd_ficha_mrn.cif_pdss+ 
  pit_bd_ficha_mrn.at_pdss+ 
  pit_bd_ficha_mrn.vg_pdss+ 
  pit_bd_ficha_mrn.ag_pdss)*0.30) AS deposito, 
  pit_bd_ficha_mrn.cod_mrn, 
  pit_bd_ficha_mrn.calificacion_2 AS calificacion, 
  sys_bd_tipo_iniciativa.codigo_iniciativa AS iniciativa
FROM pit_bd_ficha_mrn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
   LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_mrn.cod_mrn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN clar_bd_ficha_mrn_2 ON clar_bd_ficha_mrn_2.cod_mrn = pit_bd_ficha_mrn.cod_mrn
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_mrn.cod_tipo_iniciativa
WHERE org_ficha_usuario.titular=1 AND
clar_bd_ficha_mrn_2.cod_clar='$cod'
GROUP BY pit_bd_ficha_mrn.cod_mrn";
$result=mysql_query($sql) or die (mysql_error());
while($f3=mysql_fetch_array($result))
{
?>
        <tr>
          <td><small><? echo $f3['n_documento'];?></small></td>
          <td><small><? echo $f3['iniciativa'];?></small></td>
          <td><small><? echo $f3['nombre'];?></small></td>
          <td><small><? echo $f3['tipo_org'];?></small></td>
          <td><small><? echo $f3['departamento'];?></small></td>
          <td><small><? echo $f3['provincia'];?></small></td>
          <td><small><? echo $f3['distrito'];?></small></td>
          <td><small><? echo $f3['oficina'];?></small></td>
          <td><small><? echo number_format($f3['familias']);?></small></td>
          <td><small><? echo number_format($f3['calificacion'],2);?></small></td>
          <td><small><? if ($f3['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></small></td>
          <td><small><? if ($f3['calificacion']>=70) echo number_format($f3['deposito'],2);?></small></td>
        </tr>


<?        
}
$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  sys_bd_cp.nombre AS cp, 
  sys_bd_dependencia.nombre AS oficina, 
  COUNT(org_ficha_usuario.n_documento) AS familias, 
  pit_bd_ficha_pdn.cod_pdn, 
  pit_bd_ficha_pdn.calificacion, 
  ((pit_bd_ficha_pdn.total_apoyo+ 
  pit_bd_ficha_pdn.at_pdss+ 
  pit_bd_ficha_pdn.vg_pdss+ 
  pit_bd_ficha_pdn.fer_pdss)*0.70) AS deposito, 
  sys_bd_tipo_iniciativa.codigo_iniciativa AS iniciativa
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn.cod_pdn
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
   LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
WHERE clar_bd_ficha_pdn.cod_clar='$cod' AND
org_ficha_usuario.titular=1
GROUP BY pit_bd_ficha_pdn.cod_pdn";
$result=mysql_query($sql) or die (mysql_error());
while($f2=mysql_fetch_array($result))
{
?>
        <tr>
          <td><small><? echo $f2['n_documento'];?></small></td>
          <td><small><? echo $f2['iniciativa'];?></small></td>
          <td><small><? echo $f2['nombre'];?></small></td>
          <td><small><? echo $f2['tipo_org'];?></small></td>
          <td><small><? echo $f2['departamento'];?></small></td>
          <td><small><? echo $f2['provincia'];?></small></td>
          <td><small><? echo $f2['distrito'];?></small></td>
          <td><small><? echo $f2['oficina'];?></small></td>
          <td><small><? echo number_format($f2['familias']);?></small></td>
          <td><small><? echo number_format($f2['calificacion'],2);?></small></td>
          <td><small><? if ($f2['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></small></td>
          <td><small><? if ($f2['calificacion']>=70) echo number_format($f2['deposito'],2);?></small></td>
        </tr>
<?
}
$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  sys_bd_cp.nombre AS cp, 
  sys_bd_dependencia.nombre AS oficina, 
  COUNT(org_ficha_usuario.n_documento) AS familias, 
  pit_bd_ficha_pdn.cod_pdn, 
  ((pit_bd_ficha_pdn.total_apoyo+ 
  pit_bd_ficha_pdn.at_pdss+ 
  pit_bd_ficha_pdn.vg_pdss+ 
  pit_bd_ficha_pdn.fer_pdss)*0.30) AS deposito, 
  pit_bd_ficha_pdn.calificacion_2 AS calificacion, 
  sys_bd_tipo_iniciativa.codigo_iniciativa AS iniciativa 
FROM pit_bd_ficha_pdn INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
   LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN clar_bd_ficha_pdn_2 ON clar_bd_ficha_pdn_2.cod_pdn = pit_bd_ficha_pdn.cod_pdn
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
WHERE org_ficha_usuario.titular=1 AND
clar_bd_ficha_pdn_2.cod_clar='$cod'
GROUP BY pit_bd_ficha_pdn.cod_pdn";
$result=mysql_query($sql) or die (mysql_error());
while($f4=mysql_fetch_array($result))
{
?>

        <tr>
          <td><small><? echo $f4['n_documento'];?></small></td>
          <td><small><? echo $f4['iniciativa'];?></small></td>
          <td><small><? echo $f4['nombre'];?></small></td>
          <td><small><? echo $f4['tipo_org'];?></small></td>
          <td><small><? echo $f4['departamento'];?></small></td>
          <td><small><? echo $f4['provincia'];?></small></td>
          <td><small><? echo $f4['distrito'];?></small></td>
          <td><small><? echo $f4['oficina'];?></small></td>
          <td><small><? echo number_format($f4['familias']);?></small></td>
          <td><small><? echo number_format($f4['calificacion'],2);?></small></td>
          <td><small><? if ($f4['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></small></td>
          <td><small><? if ($f4['calificacion']>=70) echo number_format($f4['deposito'],2);?></small></td>
        </tr>
<?
}
$sql="SELECT sys_bd_tipo_doc.descripcion AS tipo_doc, 
  org_ficha_organizacion.n_documento, 
  org_ficha_organizacion.nombre, 
  sys_bd_tipo_org.descripcion AS tipo_org, 
  sys_bd_departamento.nombre AS departamento, 
  sys_bd_provincia.nombre AS provincia, 
  sys_bd_distrito.nombre AS distrito, 
  sys_bd_cp.nombre AS cp, 
  sys_bd_dependencia.nombre AS oficina, 
  ((pit_bd_ficha_pdn.total_apoyo+ 
  pit_bd_ficha_pdn.at_pdss+ 
  pit_bd_ficha_pdn.vg_pdss+ 
  pit_bd_ficha_pdn.fer_pdss)*0.70) AS deposito, 
  pit_bd_ficha_pdn.calificacion, 
  COUNT(org_ficha_usuario.n_documento) AS familias, 
  pit_bd_ficha_pdn.cod_pdn, 
  sys_bd_tipo_iniciativa.codigo_iniciativa AS iniciativa
FROM pit_bd_ficha_pdn INNER JOIN clar_bd_ficha_pdn_suelto ON pit_bd_ficha_pdn.cod_pdn = clar_bd_ficha_pdn_suelto.cod_pdn
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
   LEFT OUTER JOIN sys_bd_cp ON sys_bd_cp.cod = org_ficha_organizacion.cod_cp
   INNER JOIN sys_bd_tipo_doc ON sys_bd_tipo_doc.cod_tipo_doc = org_ficha_organizacion.cod_tipo_doc
   INNER JOIN sys_bd_tipo_org ON sys_bd_tipo_org.cod_tipo_org = org_ficha_organizacion.cod_tipo_org
   INNER JOIN sys_bd_departamento ON sys_bd_departamento.cod = org_ficha_organizacion.cod_dep
   INNER JOIN sys_bd_provincia ON sys_bd_provincia.cod = org_ficha_organizacion.cod_prov
   INNER JOIN sys_bd_distrito ON sys_bd_distrito.cod = org_ficha_organizacion.cod_dist
   INNER JOIN pit_bd_user_iniciativa ON pit_bd_user_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa AND pit_bd_user_iniciativa.cod_iniciativa = pit_bd_ficha_pdn.cod_pdn
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = pit_bd_user_iniciativa.cod_tipo_doc AND org_ficha_usuario.n_documento = pit_bd_user_iniciativa.n_documento AND org_ficha_usuario.cod_tipo_doc_org = org_ficha_organizacion.cod_tipo_doc AND org_ficha_usuario.n_documento_org = org_ficha_organizacion.n_documento
   INNER JOIN sys_bd_tipo_iniciativa ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pdn.cod_tipo_iniciativa
WHERE clar_bd_ficha_pdn_suelto.cod_clar='$cod' AND
org_ficha_usuario.titular=1
GROUP BY pit_bd_ficha_pdn.cod_pdn";
$result=mysql_query($sql) or die (mysql_error());
while($f5=mysql_fetch_array($result))
{
?>
        <tr>
          <td><small><? echo $f5['n_documento'];?></small></td>
          <td><small><? echo $f5['iniciativa'];?></small></td>
          <td><small><? echo $f5['nombre'];?></small></td>
          <td><small><? echo $f5['tipo_org'];?></small></td>
          <td><small><? echo $f5['departamento'];?></small></td>
          <td><small><? echo $f5['provincia'];?></small></td>
          <td><small><? echo $f5['distrito'];?></small></td>
          <td><small><? echo $f5['oficina'];?></small></td>
          <td><small><? echo number_format($f5['familias']);?></small></td>
          <td><small><? echo number_format($f5['calificacion'],2);?></small></td>
          <td><small><? if ($f5['calificacion']>=70) echo "APROBADO"; else echo "DESAPROBADO";?></small></td>
          <td><small><? if ($f5['calificacion']>=70) echo number_format($f5['deposito'],2);?></small></td>
        </tr> 
   <?
   }
   ?>    

        <tr>
          <td colspan="8"><h6><small>TOTALES</small></h6></td>
          <td><h6><small><? echo number_format($total_familia);?></small></h6></td>
          <td><h6><small>-</small></h6></td>
          <td><h6><small>-</small></h6></td>
          <td><h6><small><? echo number_format($total_deposito,2);?></small></h6></td>
        </tr>        
      </tbody>
    </table>
  </div>
</div>

<div class="twelve columns">
  <button type="submit" class="secondary button oculto" onClick="window.print()">Imprimir</button>
  <a href="index.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button oculto">Finalizar</a>
</div>
</div>

<!-- Footer -->
<? include("footer.php");?>


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
</body>
</html>
