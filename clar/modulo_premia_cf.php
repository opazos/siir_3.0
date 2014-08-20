<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//Obtengo los datos del concurso
$sql="SELECT * FROM bd_cfinal WHERE cod_concurso='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

$max_gan_a=$r1['max_gan_a'];
$max_gan_b=$r1['max_gan_b'];
$max_gan_c=$r1['max_gan_c'];

//Obtengo la numeracion de ATF
$sql="SELECT sys_bd_numera_dependencia.cod, 
  sys_bd_numera_dependencia.n_solicitud_iniciativa, 
  sys_bd_numera_dependencia.n_atf_iniciativa
FROM sys_bd_numera_dependencia
WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_numera_dependencia.periodo='$anio'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

$n_atf=$r2['n_atf_iniciativa']

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
<dd  class="active"><a href="">Cuadro de premiación</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&tipo=<? echo $tipo;?>&action=PREMIA_CF" onsubmit="return checkSubmit();">

  <div class="row">
    <div class="twelve columns"><h6>I.- Datos del concurso</h6></div>
    <div class="two columns">Nombre del evento</div>
    <div class="ten columns"><input type="text" name="nombre" class="required ten" value="<? echo $r1['nombre'];?>"> <input type="hidden" name="codigo" value="<? echo $r1['cod_concurso'];?>"></div>
  <div class="two columns">Nivel del concurso</div>
  <div class="four columns">
    <select name="nivel" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <?php
        $sql="SELECT * FROM sys_bd_nivel_cf";
        $result=mysql_query($sql) or die (mysql_error());
        while($f1=mysql_fetch_array($result))
        {
        ?>
        <option value="<? echo $f1['cod_nivel'];?>" <? if ($f1['cod_nivel']==$r1['cod_nivel']) echo "selected";?>><? echo $f1['descripcion'];?></option>
        <?
        }
        ?>
    </select>
  </div>
  <div class="two columns">Fecha del concurso</div>
  <div class="four columns"><input type="date" name="f_concurso" class="required date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $r1['f_concurso'];?>"></div>
  <hr/>
  <div class="twelve columns"><h6>II.- Premiación de iniciativas ganadoras: <? if ($tipo==1) echo "CATEGORIA A"; elseif($tipo==2) echo "CATEGORIA B"; else echo "CATEGORIA C";?></h6></div>
 
    <table>
      <thead>
        <tr>
          <th>N.</th>
          <th>Participante</th>
          <th class="two">Contrato</th>
          <th>Oficina</th>
          <th>N. ATF</th>
          <th>Puntaje</th>
          <th>Puesto</th>
          <th>Premio</th>
        </tr>
      </thead>

      <tbody>
<?php
$numa=$n_atf;
if ($tipo==1)
{
  $sql="SELECT bd_ficha_cfinal.cod_participante,
  bd_ficha_cfinal.puntaje, 
  bd_ficha_cfinal.puesto, 
  bd_ficha_cfinal.premio, 
  pit_bd_ficha_pit.n_contrato, 
  pit_bd_ficha_pit.f_contrato, 
  org_ficha_taz.nombre, 
  sys_bd_dependencia.nombre AS oficina, 
  bd_ficha_cfinal.n_atf
FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
   INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=1
ORDER BY bd_ficha_cfinal.puntaje DESC LIMIT 0,$max_gan_a";
}
elseif($tipo==2)
{
  $sql="SELECT bd_ficha_cfinal.cod_participante,
  bd_ficha_cfinal.puntaje, 
  bd_ficha_cfinal.puesto, 
  bd_ficha_cfinal.premio, 
  pit_bd_ficha_pit.n_contrato, 
  pit_bd_ficha_pit.f_contrato, 
  org_ficha_taz.nombre, 
  sys_bd_dependencia.nombre AS oficina, 
  bd_ficha_cfinal.n_atf
FROM pit_bd_ficha_pit INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pit.cod_pit = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
   INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=2
ORDER BY bd_ficha_cfinal.puntaje DESC LIMIT 0,$max_gan_b"; 
}
elseif($tipo==3)
{
  $sql="SELECT bd_ficha_cfinal.cod_participante, 
  bd_ficha_cfinal.puntaje, 
  bd_ficha_cfinal.puesto, 
  bd_ficha_cfinal.premio, 
  pit_bd_ficha_pdn.n_contrato, 
  pit_bd_ficha_pdn.f_contrato, 
  org_ficha_organizacion.nombre, 
  sys_bd_dependencia.nombre AS oficina, 
  bd_ficha_cfinal.n_atf
FROM pit_bd_ficha_pdn INNER JOIN bd_ficha_cfinal ON pit_bd_ficha_pdn.cod_pdn = bd_ficha_cfinal.cod_iniciativa AND pit_bd_ficha_pdn.cod_tipo_iniciativa = bd_ficha_cfinal.cod_tipo_iniciativa
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
   INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_organizacion.cod_dependencia
WHERE bd_ficha_cfinal.cod_concurso='$cod' AND
bd_ficha_cfinal.cod_categoria=3
ORDER BY bd_ficha_cfinal.puntaje DESC LIMIT 0,$max_gan_c";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
  $cad=$fila['cod_participante'];
  $na=1;
  $num=$num+$na;
  $numa++
?>      
        <tr>
          <td><? echo $num;?></td>
          <td><? echo $fila['nombre'];?></td>
          <td><? echo numeracion($fila['n_contrato'])."-".periodo($fila['f_contrato']);?></td>
          <td><? echo $fila['oficina'];?></td>
          <td><input type="text" name="atf[<? echo $cad;?>]" readonly="readonly" value="<? if($fila['n_atf']<>0) echo $fila['n_atf']; else echo $numa;?>"> <input type="hidden" name="tiene_atf" value="<? if($fila['n_atf']<>0) echo 1; else echo 0;?>"></td>
          <td><input type="text" name="puntaje" readonly="readonly" value="<? echo $fila['puntaje'];?>"></td>
          <td><input type="text" name="puesto[<? echo $cad;?>]" readonly="readonly" value="<? echo $num;?>"></td>
          <td><input type="text" name="premio[<? echo $cad;?>]" value="<? echo $fila['premio'];?>"></td>
        </tr>
<?php
}
?>       
      </tbody>
    </table>
    <input type="hidden" name="n_atf" value="<? echo $numa;?>">
  </div>
  <div class="twelve columns">
    <button type="submit" class="button" id="btn_envia">Actualizar</button>
    <a href="premia_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=acta" class="secondary button">Finalizar</a>
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
  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
