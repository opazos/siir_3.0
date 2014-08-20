<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM ml_bd_contrato_vg WHERE cod_contrato='$id'";
$result=mysql_query($sql) or die (mysql_error());
$r1=mysql_fetch_array($result);

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
<dl class="tabs"><dd  class="active"><a href="">Modificar contrato</a></dd></dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_contrato_vg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">
  <div class="row">
    <div class="twelve columns"><h6>I.- Información del evento</h6></div>
    <div class="two columns">Inicio</div>
    <div class="four columns"><input type="date" name="f_inicio" placeholder="0000-00-00" maxlength="10" class="date required seven" value="<? echo $r1['f_inicio'];?>"></div>
    <div class="two columns">Término</div>
    <div class="four columns"><input type="date" name="f_termino" placeholder="0000-00-00" maxlength="10" class="date required seven" value="<? echo $r1['f_termino'];?>"></div>   
    <div class="twelve columns">Objeto de la visita:</div>
    <div class="twelve columns"><textarea name="objeto" rows="5"><? echo $r1['objeto'];?></textarea></div> 
    <div class="two columns">Número de participantes</div>
    <div class="four columns"><input type="text" name="n_participantes" class="required digits seven" value="<? echo $r1['n_participantes'];?>"></div>
    <div class="two columns">Fecha de presentación de la propuesta</div>
    <div class="four columns"><input type="date" name="f_presentacion" placeholder="0000-00-00" maxlength="10" class="date required seven" value="<? echo $r1['f_presentacion'];?>"></div>
  </div>

  <div class="row">
    <div class="twelve columns"><h6>II.- Itinerarios</h6></div>
    <table>
      <thead>
        <tr>
          <th>N.</th>
          <th class="five">Organización/Entidad</th>
          <th>Departamento</th>
          <th>Provincia</th>
          <th>Distrito</th>
          <th><br/></th>
        </tr>
      </thead>
      <tbody>
<?php
  $num=0;
  $sql="SELECT * FROM ml_bd_itinerario_vg WHERE cod_contrato_vg='$id'";
  $result=mysql_query($sql) or die (mysql_error());
  while($f5=mysql_fetch_array($result))
  {
    $cad=$f5['cod_itinerario'];
    $num++
?>
        <tr>
          <td><? echo $num;?></td>
          <td><input type="text" name="entidads[<? echo $cad;?>]" value="<? echo $f5['entidad'];?>"></td>
          <td><input type="text" name="deps[<? echo $cad;?>]" value="<? echo $f5['dep'];?>"></td>
          <td><input type="text" name="provs[<? echo $cad;?>]" value="<? echo $f5['prov'];?>"></td>
          <td><input type="text" name="dists[<? echo $cad;?>]" value="<? echo $f5['dist'];?>"></td>
          <td><a href="gestor_contrato_vg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $id;?>&cod=<? echo $f5['cod_itinerario'];?>&action=DELETE_ITINERARIO"><img src="../images/Delete.png" border="0" width="25" height="25"></a></td>
        </tr>
<?
}
?>


      <?php
      $num=$num;
      for ($i=1; $i<=5;$i++)
      {
        $num++
      ?>
        <tr>
          <td><? echo $num;?></td>
          <td><input type="text" name="entidad[]"></td>
          <td><input type="text" name="dep[]"></td>
          <td><input type="text" name="prov[]"></td>
          <td><input type="text" name="dist[]"></td>
          <td><br/></td>
        </tr>
      <?
      }
      ?>
      </tbody>
    </table>    
  </div>
  <div class="row">
    <div class="twelve columns"><h6>III.- Información del contrato</h6></div>
    <div class="two columns">Organización con la que se contrata</div>
    <div class="ten columns">
      <select name="org" class="large" required>
        <option value="" selected="selected">Seleccionar</option>
        <?php
        $sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
        org_ficha_organizacion.n_documento, 
        org_ficha_organizacion.nombre
        FROM org_ficha_organizacion
        WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
        ORDER BY org_ficha_organizacion.nombre ASC";
        $result=mysql_query($sql) or die (mysql_error());
        while($f1=mysql_fetch_array($result))
        {
        ?>
        <option value="<? echo $f1['cod_tipo_doc'].",".$f1['n_documento'];?>" <? if ($f1['cod_tipo_doc']==$r1['cod_tipo_doc'] and $f1['n_documento']==$r1['n_documento']) echo "selected";?>><? echo $f1['nombre'];?></option>
        <?
        }
        ?>
      </select>
    </div>
    <div class="two columns">Fecha de aprobación de propuesta</div>
    <div class="four columns"><input type="date" name="f_aprobacion" placeholder="0000-00-00" maxlength="10" class="date required seven" value="<? echo $r1['f_aprobacion'];?>"></div>
    <div class="two columns">Fecha de contrato</div>
    <div class="four columns"><input type="date" name="f_contrato" placeholder="0000-00-00" maxlength="10" class="date required seven" value="<? echo $r1['f_contrato']?>"></div> 
    <div class="two columns">Afectación presupuestal</div>
    <div class="ten columns">
      <select name="poa" class="large" required>
        <option value="" selected="selected">Seleccionar</option>
        <?php
        $sql="SELECT sys_bd_subactividad_poa.cod, 
        sys_bd_subactividad_poa.codigo, 
        sys_bd_subactividad_poa.nombre
        FROM sys_bd_subactividad_poa
        WHERE sys_bd_subactividad_poa.periodo='$anio'AND
        sys_bd_subactividad_poa.cod_categoria_poa=2
        ORDER BY sys_bd_subactividad_poa.codigo ASC";
        $result=mysql_query($sql) or die (mysql_error());
        while($f2=mysql_fetch_array($result))
        {
        ?>
        <option value="<? echo $f2['cod'];?>" <? if ($f2['cod']==$r1['cod_poa']) echo "selected";?>><? echo $f2['codigo']."-".$f2['nombre'];?></option>
        <?php
        }
        ?>
      </select>
    </div>   

    <div class="two columns">Fuente de Financiamiento</div>
    <div class="ten columns">
      <select name="fte_fto" class="medium">
        <option value="" selected="selected">Seleccionar</option>
        <?php
          $sql="SELECT sys_bd_fuente_fto.cod, 
          sys_bd_fuente_fto.descripcion
          FROM sys_bd_fuente_fto";
          $result=mysql_query($sql) or die (mysql_error());
          while($f4=mysql_fetch_array($result))
          {
        ?>
          <option value="<? echo $f4['cod'];?>" <? if ($f4['cod']==$r1['fte_fto']) echo "selected";?>><? echo $f4['descripcion'];?></option>
        <? 
          }
        ?>
      </select>
    </div>

  </div>

  <div class="row">
    <div class="twelve columns"><h6>IV.- Presupuesto del contrato</h6></div>
    <div class="two columns">Entidad bancaria</div>
    <div class="four columns">
      <select name="ifi" class="medium">
        <option value="" selected="selected">Seleccionar</option>
        <?php
        $sql="SELECT sys_bd_ifi.cod_ifi, 
        sys_bd_ifi.descripcion
        FROM sys_bd_ifi
        ORDER BY sys_bd_ifi.descripcion ASC";
        $result=mysql_query($sql) or die (mysql_error());
        while($f3=mysql_fetch_array($result))
        {
        ?>
        <option value="<? echo $f3['cod_ifi'];?>" <? if ($f3['cod_ifi']==$r1['cod_ifi']) echo "selected";?>><? echo $f3['descripcion'];?></option>
        <?php  
        }
        ?>
      </select>
    </div>
    <div class="two columns">Número de cuenta</div>
    <div class="four columns"><input type="text" name="n_cuenta" class="required seven" value="<? echo $r1['n_cuenta'];?>"></div> 
    <div class="two columns">Existe valorización de aportes?</div>
    <div class="four columns">
      <select name="valorizacion" class="mini">
        <option value="" selected="selected">Seleccionar</option>
        <option value="1" <? if ($r1['valorizacion']==1) echo "selected";?>>Si</option>
        <option value="0" <? if ($r1['valorizacion']==0) echo "selected";?>>No</option>
      </select>
    </div>
    <div class="two columns">Si la respuesta es <strong>SI</strong>, indicar el valor del aporte</div>
    <div class="four columns"><input type="text" name="valor_aporte" class="number seven" value="<? echo $r1['aporte_valorizacion'];?>"></div>


    <div class="twelve columns"><hr/></div>
    <div class="two columns">Aporte NEC PDSS II (S/.)</div>
    <div class="ten columns"><input type="text" name="aporte_pdss" class="required number three" value="<? echo $r1['aporte_pdss'];?>"></div>
    <div class="two columns">Aporte Organización (S/.)</div>
    <div class="ten columns"><input type="text" name="aporte_org" class="required number three" value="<? echo $r1['aporte_org'];?>"></div>
    <div class="two columns">Aporte Otros (S/.)</div>
    <div class="ten columns"><input type="text" name="aporte_otro" class="required number three" value="<? echo $r1['aporte_otro'];?>"></div>   
    <div class="twelve columns"><input type="hidden" name="codigo" value="<? echo $r1['cod_contrato'];?>"><br/></div>     
  </div>

  

  <div class="row">
    <div class="twelve columns">
      <button type="submit" class="primary button">Guardar cambios</button>
      <a href="contrato_vg.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Cancelar</a>
    </div>
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
