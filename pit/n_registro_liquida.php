<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if ($cod==NULL)
{
  $action=ADD;
}
else
{
  $action=UPDATE;
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
<dd  class="active">
<a href="">
  <?
  if ($cod==NULL)
  {
  echo "Parte 1 de 2";
  }
  else
  {
  echo "Parte 2 de 2";
  }
  ?>
</a>
</dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" class="custom" method="post" action="gestor_registro_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=<? echo $action;?>" onsubmit="return checkSubmit();">
<?
if ($cod==NULL)
{
?>
<div class="row">
  <div class="twelve columns"><h6>Seleccione el tipo de iniciativa</h6></div>
  <div class="twelve columns">
    <select name="tipo_iniciativa">
      <option value="" selected="selected">Seleccionar</option>
      <?
      $sql="SELECT sys_bd_tipo_iniciativa.cod_tipo_iniciativa, 
      sys_bd_tipo_iniciativa.codigo_iniciativa, 
      sys_bd_tipo_iniciativa.descripcion
      FROM sys_bd_tipo_iniciativa
      ORDER BY sys_bd_tipo_iniciativa.codigo_iniciativa ASC";
      $result=mysql_query($sql) or die (mysql_error());
      while($f1=mysql_fetch_array($result))
      {
        echo "<option value='".$f1['cod_tipo_iniciativa']."'>".$f1['codigo_iniciativa']." - ".$f1['descripcion']."</option>";
      }
      ?>
    </select>
  </div>

  <div class="twelve columns"><h6>Seleccione la Oficina local</h6></div>
  <div class="twelve columns">
    <select name="cod_dependencia">
      <option value="" selected="selected">Seleccionar</option>
      <?
        $sql="SELECT sys_bd_dependencia.cod_dependencia, 
        sys_bd_dependencia.nombre
        FROM sys_bd_dependencia
        ORDER BY sys_bd_dependencia.cod_dependencia ASC";
        $result=mysql_query($sql) or die (mysql_error());
        while($f2=mysql_fetch_array($result))
        {
          echo "<option value='".$f2['cod_dependencia']."'>".$f2['nombre']."</option>";
        }
      ?>
    </select>
  </div>
  <div class="twelve columns"><br/></div>
  <div class="twelve columns">
    <button type="submit" class="primary button">Guardar cambios</button>
    <a href="registro_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button">Finalizar</a>
  </div>
</div>
<?
}
else
{
  $sql="SELECT * FROM bd_registro_liquida WHERE cod='$cod'";
  $result=mysql_query($sql) or die (mysql_error());
  $r1=mysql_fetch_array($result);
?>
<div class="row">
  <div class="twelve columns"><h6>Seleccione el tipo de iniciativa</h6></div>
  <div class="twelve columns">
    <select name="tipo_iniciativa" disabled>
      <option value="" selected="selected">Seleccionar</option>
      <?
      $sql="SELECT sys_bd_tipo_iniciativa.cod_tipo_iniciativa, 
      sys_bd_tipo_iniciativa.codigo_iniciativa, 
      sys_bd_tipo_iniciativa.descripcion
      FROM sys_bd_tipo_iniciativa
      ORDER BY sys_bd_tipo_iniciativa.codigo_iniciativa ASC";
      $result=mysql_query($sql) or die (mysql_error());
      while($f1=mysql_fetch_array($result))
      {
      ?>
      <option value="<? echo $f1['cod_tipo_iniciativa'];?>" <? if ($f1['cod_tipo_iniciativa']==$r1['cod_tipo_iniciativa']) echo "selected";?>><? echo $f1['codigo_iniciativa']." - ".$f1['descripcion'];?></option>
      <?
      }
      ?>
    </select>
  </div>

  <div class="twelve columns"><h6>Seleccione la Oficina local</h6></div>
  <div class="twelve columns">
    <select name="cod_dependencia" disabled="">
      <option value="" selected="selected">Seleccionar</option>
      <?
        $sql="SELECT sys_bd_dependencia.cod_dependencia, 
        sys_bd_dependencia.nombre
        FROM sys_bd_dependencia
        ORDER BY sys_bd_dependencia.cod_dependencia ASC";
        $result=mysql_query($sql) or die (mysql_error());
        while($f2=mysql_fetch_array($result))
        {
        ?>
        <option value="<? echo $f2['cod_dependencia'];?>" <? if ($f2['cod_dependencia']==$r1['cod_dependencia']) echo "selected";?>><? echo $f2['nombre'];?></option>
        <?
        }
      ?>
    </select>
  </div>
</div>

<div class="row">
  <div class="three columns"><h6>Seleccione Iniciativa</h6></div>
  <div class="three columns"><h6>Fecha de liquidacion de la iniciativa</h6></div>
  <div class="three columns"><h6>Fecha de ingreso a UEP</h6></div>
  <div class="three columns"><h6>Fecha de salida a responsables</h6></div>
</div>
<div class="row">  
  <div class="three columns">
    <select name="cod_iniciativa">
      <option value="" selected="selected">Seleccionar</option>
      <?
        if ($row['cod_tipo_iniciativa']==1)
        {
          $sql="SELECT epd_bd_demanda.cod_evento AS codigo, 
          epd_bd_demanda.n_evento AS n_evento, 
          epd_bd_demanda.f_evento AS f_evento
          FROM epd_bd_demanda
          WHERE epd_bd_demanda.estado=1 AND
          epd_bd_demanda.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY epd_bd_demanda.f_evento ASC, epd_bd_demanda.n_evento ASC";
        }
        elseif($r1['cod_tipo_iniciativa']==2)
        {
          $sql="SELECT gm_ficha_evento.cod_ficha_gm AS codigo, 
          gm_ficha_evento.n_ficha_gm AS n_evento, 
          gm_ficha_evento.f_presentacion AS f_evento
          FROM gm_ficha_evento
          WHERE gm_ficha_evento.cod_estado_iniciativa='004' AND
          gm_ficha_evento.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY n_evento ASC, f_evento ASC";
        }
        elseif($r1['cod_tipo_iniciativa']==3)
        {
          $sql="SELECT pit_bd_ficha_pit.cod_pit AS codigo, 
          pit_bd_ficha_pit.n_contrato AS n_evento, 
          pit_bd_ficha_pit.f_contrato AS f_evento
          FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
          WHERE pit_bd_ficha_pit.cod_estado_iniciativa='004' AND
          org_ficha_taz.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY pit_bd_ficha_pit.n_contrato ASC, pit_bd_ficha_pit.f_contrato ASC";
        }
        elseif($r1['cod_tipo_iniciativa']==4)
        {
          $sql="SELECT pit_bd_ficha_pdn.cod_pdn AS codigo, 
          pit_bd_ficha_pdn.n_contrato AS n_evento, 
          pit_bd_ficha_pdn.f_contrato AS f_evento
          FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_pdn ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_pdn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_pdn.n_documento_org
          WHERE pit_bd_ficha_pdn.cod_estado_iniciativa='004' AND
          pit_bd_ficha_pdn.n_contrato<>0 AND
          org_ficha_organizacion.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY pit_bd_ficha_pdn.n_contrato ASC, pit_bd_ficha_pdn.f_contrato ASC";
        }
        elseif($r1['cod_tipo_iniciativa']==6)
        {
          $sql="SELECT pit_bd_ficha_idl.cod_ficha_idl AS codigo, 
          pit_bd_ficha_idl.n_contrato AS n_evento, 
          pit_bd_ficha_idl.f_contrato AS f_evento
          FROM org_ficha_organizacion INNER JOIN pit_bd_ficha_idl ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_idl.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_idl.n_documento_org
          WHERE pit_bd_ficha_idl.cod_estado_iniciativa='004' AND
          org_ficha_organizacion.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY pit_bd_ficha_idl.n_contrato ASC, pit_bd_ficha_idl.f_contrato ASC";
        }
        elseif($r1['cod_tipo_iniciativa']==7)
        {
          $sql="SELECT clar_bd_evento_clar.cod_clar AS codigo, 
          clar_bd_evento_clar.n_contrato AS n_evento, 
          clar_bd_evento_clar.f_evento AS f_evento
          FROM clar_bd_evento_clar
          WHERE clar_bd_evento_clar.n_contrato<>0 AND
          clar_bd_evento_clar.estado=1 AND
          clar_bd_evento_clar.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY clar_bd_evento_clar.n_contrato ASC, clar_bd_evento_clar.f_evento ASC";
        }
        elseif($r1['cod_tipo_iniciativa']==8)
        {
          $sql="SELECT ml_promocion_c.cod_evento AS codigo, 
          ml_promocion_c.n_contrato AS n_evento, 
          ml_promocion_c.f_contrato AS f_evento
          FROM org_ficha_organizacion INNER JOIN ml_promocion_c ON org_ficha_organizacion.cod_tipo_doc = ml_promocion_c.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_promocion_c.n_documento_org
          WHERE ml_promocion_c.cod_estado_iniciativa='004' AND
          org_ficha_organizacion.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY ml_promocion_c.n_contrato ASC, ml_promocion_c.f_contrato ASC";
          
        }
        elseif($r1['cod_tipo_iniciativa']==10)
        {
          $sql="SELECT ml_pf.cod_evento AS codigo, 
          ml_pf.n_contrato AS n_evento, 
          ml_pf.f_contrato AS f_evento
          FROM org_ficha_organizacion INNER JOIN ml_pf ON org_ficha_organizacion.cod_tipo_doc = ml_pf.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = ml_pf.n_documento_org
          WHERE ml_pf.cod_estado_iniciativa='004' AND
          org_ficha_organizacion.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY ml_pf.n_contrato ASC, ml_pf.f_contrato ASC";
        }
        elseif($r1['cod_tipo_iniciativa']==11)
        {
          $sql="SELECT gcac_bd_evento_gc.cod_evento_gc AS codigo, 
          gcac_bd_evento_gc.n_contrato AS n_evento, 
          gcac_bd_evento_gc.f_contrato AS f_evento
          FROM org_ficha_organizacion INNER JOIN gcac_bd_evento_gc ON org_ficha_organizacion.cod_tipo_doc = gcac_bd_evento_gc.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = gcac_bd_evento_gc.n_documento_org
          WHERE gcac_bd_evento_gc.cod_estado_iniciativa='004' AND
          org_ficha_organizacion.cod_dependencia='".$r1['cod_dependencia']."'
          ORDER BY gcac_bd_evento_gc.n_contrato ASC, gcac_bd_evento_gc.f_contrato ASC";
        }
        $result=mysql_query($sql) or die (mysql_error());
        while($f3=mysql_fetch_array($result))
        {
        ?>
        <option value="<? echo $f3['codigo'].",".$f3['n_evento'].",".$f3['f_evento'];?>" <? if ($f3['codigo']==$r1['cod_iniciativa']) echo "selected";?>><? echo numeracion($f3['n_evento'])."-".periodo($f3['f_evento']);?></option>
        <? 
        }
      ?>
    </select>
  </div>
  <div class="three columns"><input type="date" name="f_liquida" placeholder="aaaa-mm-dd" maxlength="10" required class="date nine" value="<? echo $r1['f_liquidacion'];?>"></div>
  <div class="three columns"><input type="date" name="f_ingreso" placeholder="aaaa-mm-dd" maxlength="10" required class="date nine" value="<? echo $r1['f_ingreso'];?>"></div>
  <div class="three columns"><input type="date" name="f_salida" placeholder="aaaa-mm-dd" maxlength="10" required class="date nine" value="<? echo $r1['f_salida'];?>"></div>
</div>

<div class="row">
  <div class="four columns"><h6>N. de Folios</h6></div>
  <div class="four columns"><h6>Fecha de ingreso para liquidación y perfeccionamiento</h6></div>
  <div class="four columns"><h6>Fecha de salida para baja contable</h6></div>
</div>

<div class="row">
<div class="four columns"><input type="text" name="n_folio" class="required five" value="<? echo $r1['n_folio'];?>"></div>
<div class="four columns"><input type="date" name="f_ingreso_2" placeholder="aaaa-mm-dd" maxlength="10" required class="date seven" value="<? echo $r1['f_ingreso_2'];?>"></div>
<div class="four columns"><input type="date" name="f_salida_2" placeholder="aaaa-mm-dd" maxlength="10" required class="date seven" value="<? echo $r1['f_salida_2'];?>"></div>
</div>


<div class="row">
  <div class="twelve columns"><h6>Comentarios / Observaciones</h6></div>
  <div class="twelve columns"><textarea name="comentario"><? echo $r1['comentario'];?></textarea><input type="hidden" name="codigo" value="<? echo $cod;?>"></div>
</div>


<div class="row">
  <div class="twelve columns"><br/></div>
  <div class="twelve columns">
    <button type="submit" class="primary button">Guardar cambios</button>
    <a href="registro_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>" class="secondary button">Finalizar</a>
  </div>  
</div>
<?
}
?>

	
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
