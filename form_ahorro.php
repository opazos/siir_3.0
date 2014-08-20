<?
require("funciones/sesion.php");
include("funciones/funciones.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

if($tipo==SOL)
{
  $accion=ADD;
}
elseif($tipo==EDIT_SOL)
{
  $accion=UPDATE;
}
elseif($tipo==UPDATE)
{
  $accion=UPDATE_MODULO;
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
   <link type="image/x-icon" href="images/favicon.ico" rel="shortcut icon" />
   
   
  <link rel="stylesheet" href="stylesheets/foundation.css">
  <link rel="stylesheet" href="stylesheets/general_foundicons.css">
 
  <link rel="stylesheet" href="stylesheets/app.css">
  <link rel="stylesheet" href="rtables/responsive-tables.css">
  
  <script src="javascripts/modernizr.foundation.js"></script>
  <script src="javascripts/btn_envia.js"></script>
  <script src="rtables/javascripts/jquery.min.js"></script>
  <script src="rtables/responsive-tables.js"></script>
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

<!-- Termino contenedores -->
      <div class="row collapse">
        <form name="form5" method="post" action="gestor_ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&tipo=<? echo $tipo;?>&action=<? echo $accion;?>" onsubmit="return checkSubmit();">
        	<?php
          if($tipo==SOL)
          {
            //1.- obtengo el numero que corresponde
            $sql="SELECT sys_bd_numera_dependencia.cod, 
            sys_bd_numera_dependencia.n_solicitud_iniciativa,
            sys_bd_numera_dependencia.n_atf_iniciativa
            FROM sys_bd_numera_dependencia
            WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
            sys_bd_numera_dependencia.periodo='$anio'";
            $result=mysql_query($sql) or die (mysql_error());
            $r1=mysql_fetch_array($result);

            $n_solicitud=$r1['n_solicitud_iniciativa']+1;
          ?>
          <fieldset>
          <legend>Solicitud de desembolso - Ahorros</legend>
          <div class="row">
          <div class="twelve columns"><h6>I.- Información de Pago</h6></div>
          <div class="two columns">N. solicitud</div>
          <div class="four columns"><input type="text" name="n_solicitud" class="required number seven" value="<? echo $n_solicitud;?>" readonly> <input type="hidden" name="cod" value="<? echo $r1['cod'];?>"></div>
          <div class="two columns">Fecha de solicitud</div>
          <div class="four columns"><input type="date" name="f_solicitud" placeholder="aaaa-mm-dd" maxlength="10" class="required date seven" value="<? echo $fecha_hoy;?>"></div>
          <div class="two columns">Afectación presupuestal</div>
          <div class="four columns">
            <select name="poa" class="hyjack">
                <option value="" selected="selected">Seleccionar</option>
                <?php
                $sql="SELECT sys_bd_subactividad_poa.cod, 
                  sys_bd_subactividad_poa.codigo, 
                  sys_bd_subactividad_poa.nombre
                FROM sys_bd_subactividad_poa
                WHERE sys_bd_subactividad_poa.periodo='$anio'
                ORDER BY sys_bd_subactividad_poa.codigo ASC";
                $result=mysql_query($sql) or die (mysql_error());
                while($f1=mysql_fetch_array($result))
                {
                  echo "<option value='".$f1['cod']."'>".$f1['codigo']."-".$f1['nombre']."</option>";
                }
                ?>
            </select>
          </div>
          <div class="two columns">Fuente de Financiamiento</div>
          <div class="four columns">
            <select name="fte_fto" class="medium">
              <option value="" selected="selected">Seleccionar</option>
              <option value="1">Fuente FIDA</option>
              <option value="2">Fuente RO</option>
              <option value="3">Fuente FIDA+RO</option>
            </select>
          </div>

          <div class="twelve columns"><br/></div>

          <div class="two columns">Tipo de Incentivo</div>
          <div class="ten columns">
            <select name="incentivo" class="large">
              <option value="" selected="selected">Seleccionar</option>
              <option value="1">INCENTIVO AL DEPOSITO DE APERTURA</option>
              <option value="2">INCENTIVO AL MANTENIMIENTO Y AL CRECIMIENTO</option>
              <option value="3">INCENTIVO AL RETIRO</option>
            </select>
          </div>


          </div>
          <div class="row">
            <div class="twelve columns"><h6>II.- Grupos de ahorro</h6></div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Organizacion</th>
                <th>IFI</th>
                <th>N. Ahorristas</th>
                <th>MONTO PDSS (S/.)</th>
                <th>MONTO ORG (S/.)</th>
                <th>N. ATF</th>
              </tr>
            </thead>

            <tbody>
            <?php
            for ($i=0; $i<=30 ; $i++) 
            { 
            ?>
              <tr>
                <td>
                <select name="grupo[]" required class="hyjack">
                <option value="" selected="selected">Seleccionar</option>
                  <?php
                  $sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
                    org_ficha_organizacion.n_documento, 
                    org_ficha_organizacion.nombre
                  FROM org_ficha_organizacion
                  WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
                  ORDER BY org_ficha_organizacion.nombre ASC";
                  $result1=mysql_query($sql) or die (mysql_error());
                  while($f2=mysql_fetch_array($result1))
                  {
                    echo "<option value='".$f2['cod_tipo_doc'].",".$f2['n_documento']."'>".$f2['nombre']."</option>";
                  }
                  ?>
                  </select>
                </td>
                <td>
                  <select name="ifi[]" required class="hyjack">
                    <option value="" selected="selected">Seleccionar</option>
                    <?php
                    $sql="SELECT sys_bd_ifi.cod_ifi, 
                      sys_bd_ifi.descripcion
                    FROM sys_bd_ifi
                    ORDER BY sys_bd_ifi.descripcion ASC";
                    $result2=mysql_query($sql) or die (mysql_error());
                    while($f3=mysql_fetch_array($result2))
                    {
                      echo "<option value='".$f3['cod_ifi']."'>".$f3['descripcion']."</option>";
                    }
                    ?>
                  </select>
                </td>
                <td><input type="text" name="n_ahorrista[]" class="number"></td>
                <td><input type="text" name="monto[]" class="number"></td>
                <td><input type="text" name="contrapartida[]" class="number"></td>
                <td><input type="text" name="n_atf[]" class="digits" disabled="disabled"></td>
              </tr>
              <?php
              }
              ?>
            </tbody>
          </table>

          </fieldset>
          <?php
          }
          elseif($tipo==EDIT_SOL)
          {
            $sql="SELECT * FROM mh_bd_desembolso WHERE cod='$cod'";
            $result=mysql_query($sql) or die (mysql_error());
            $r1=mysql_fetch_array($result);


            /*Obtengo el numero de atf*/
            $sql="SELECT sys_bd_numera_dependencia.cod, 
            sys_bd_numera_dependencia.n_atf_iniciativa
            FROM sys_bd_numera_dependencia
            WHERE sys_bd_numera_dependencia.cod_dependencia='".$row['cod_dependencia']."' AND
            sys_bd_numera_dependencia.periodo='$anio'";
            $result=mysql_query($sql) or die (mysql_error());
            $r3=mysql_fetch_array($result);

          ?>
           <fieldset>
          <legend>VERIFIQUE SU INFORMACION Y DE CLICK EN GUARDAR CAMBIOS</legend>
          <div class="row">
          <div class="twelve columns"><h6>I.- Información de Pago</h6></div>
          <div class="two columns">N. solicitud</div>
          <div class="four columns"><input type="text" name="n_solicitud" class="required number seven" value="<? echo $r1['n_solicitud'];?>" readonly> <input type="hidden" name="codigo" value="<? echo $cod;?>"> <input type="hidden" name="cod" value="<? echo $r3['cod'];?>"></div>
          <div class="two columns">Fecha de solicitud</div>
          <div class="four columns"><input type="date" name="f_solicitud" placeholder="aaaa-mm-dd" maxlength="10" class="required date seven" value="<? echo $r1['f_solicitud'];?>"></div>
          <div class="two columns">Afectación presupuestal</div>
          <div class="four columns">
            <select name="poa" required class="medium">
                <option value="" selected="selected">Seleccionar</option>
                <?php
                $sql="SELECT sys_bd_subactividad_poa.cod, 
                  sys_bd_subactividad_poa.codigo, 
                  sys_bd_subactividad_poa.nombre
                FROM sys_bd_subactividad_poa
                WHERE sys_bd_subactividad_poa.periodo='$anio'
                ORDER BY sys_bd_subactividad_poa.codigo ASC";
                $result=mysql_query($sql) or die (mysql_error());
                while($f1=mysql_fetch_array($result))
                {
                ?>
                <option value="<? echo $f1['cod'];?>" <? if ($f1['cod']==$r1['cod_poa']) echo "selected";?>><? echo $f1['codigo']."-".$f1['nombre'];?></option>
                <?
                }
                ?>
            </select>
          </div>
          <div class="two columns">Fuente de Financiamiento</div>
          <div class="four columns">
            <select name="fte_fto" class="medium">
              <option value="" selected="selected">Seleccionar</option>
              <option value="1" <? if ($r1['fte_fto']==1) echo "selected";?>>Fuente FIDA</option>
              <option value="2" <? if ($r1['fte_fto']==2) echo "selected";?>>Fuente RO</option>
              <option value="3" <? if ($r1['fte_fto']==3) echo "selected";?>>Fuente FIDA+RO</option>
            </select>
          </div>


          <div class="two columns">Tipo de Incentivo</div>
          <div class="ten columns">
            <select name="incentivo" class="large">
              <option value="" selected="selected">Seleccionar</option>
              <option value="1" <? if ($r1['cod_tipo_incentivo']==1) echo "selected";?>>INCENTIVO AL DEPOSITO DE APERTURA</option>
              <option value="2" <? if ($r1['cod_tipo_incentivo']==2) echo "selected";?>>INCENTIVO AL MANTENIMIENTO Y AL CRECIMIENTO</option>
              <option value="3" <? if ($r1['cod_tipo_incentivo']==3) echo "selected";?>>INCENTIVO AL RETIRO</option>
            </select>
          </div>

          </div>
          <div class="row">
            <div class="twelve columns"><h6>II.- Grupos de ahorro</h6></div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Organizacion</th>
                <th>IFI</th>
                <th>N. Ahorristas</th>
                <th>MONTO PDSS (S/.)</th>
                <th>MONTO ORG (S/.)</th>
                <th>N. ATF</th>
              </tr>
            </thead>

            <tbody>
            <?php
            $num=$r3['n_atf_iniciativa'];
            $sql="SELECT * FROM mh_bd_grupo WHERE cod_desembolso='$cod'";
            $result=mysql_query($sql) or die (mysql_error());
            while($r2=mysql_fetch_array($result))
            {
              $cad=$r2['cod'];
              $num++
            ?>
              <tr>
                <td>
                <select name="grupo[<? echo $cad;?>]" required class="medium required">
                <option value="" selected="selected">Seleccionar</option>
                  <?php
                  $sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
                    org_ficha_organizacion.n_documento, 
                    org_ficha_organizacion.nombre
                  FROM org_ficha_organizacion
                  WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
                  ORDER BY org_ficha_organizacion.nombre ASC";
                  $result1=mysql_query($sql) or die (mysql_error());
                  while($f2=mysql_fetch_array($result1))
                  {
                  ?>
                  <option value="<? echo $f2['cod_tipo_doc'].",".$f2['n_documento'];?>" <? if ($f2['cod_tipo_doc']==$r2['cod_tipo_doc'] and $f2['n_documento']==$r2['n_documento']) echo "selected";?>><? echo $f2['nombre'];?></option>
                  <?php
                  }
                  ?>
                  </select>
                </td>
                <td>
                  <select name="ifi[<? echo $cad;?>]" required class="medium">
                    <option value="" selected="selected">Seleccionar</option>
                    <?php
                    $sql="SELECT sys_bd_ifi.cod_ifi, 
                      sys_bd_ifi.descripcion
                    FROM sys_bd_ifi
                    ORDER BY sys_bd_ifi.descripcion ASC";
                    $result2=mysql_query($sql) or die (mysql_error());
                    while($f3=mysql_fetch_array($result2))
                    {
                    ?>
                    <option value="<? echo $f3['cod_ifi'];?>" <? if ($f3['cod_ifi']==$r2['cod_ifi']) echo "selected";?>><? echo $f3['descripcion'];?></option>
                    <?
                    }
                    ?>
                  </select>
                </td>
                <td><input type="text" name="n_ahorrista[<? echo $cad;?>]" class="number" value="<? echo $r2['n_ahorristas'];?>"></td>
                <td><input type="text" name="monto[<? echo $cad;?>]" class="number" value="<? echo $r2['monto'];?>"></td>
                <td><input type="text" name="contrapartida[<? echo $cad;?>]" class="number" value="<? echo $r2['contrapartida'];?>"></td>
                <td><input type="text" name="n_at[<? echo $cad;?>]" class="digits error" value="<? echo $num;?>" readonly></td>
              </tr>
              <?php
              }
              ?>
              <input type="hidden" name="n_atf" value="<? echo $num;?>">
            </tbody>
          </table>
          </fieldset>         
          <?php
          }
          elseif($tipo==UPDATE)
          {
            $sql="SELECT * FROM mh_bd_desembolso WHERE cod='$cod'";
            $result=mysql_query($sql) or die (mysql_error());
            $r1=mysql_fetch_array($result);            
          ?>
            <fieldset>
          <legend>VERIFIQUE SU INFORMACION Y DE CLICK EN GUARDAR CAMBIOS</legend>
          <div class="row">
          <div class="twelve columns"><h6>I.- Información de Pago</h6></div>
          <div class="two columns">N. solicitud</div>
          <div class="four columns"><input type="text" name="n_solicitud" class="required number seven" value="<? echo $r1['n_solicitud'];?>" readonly> <input type="hidden" name="codigo" value="<? echo $cod;?>"> <input type="hidden" name="cod" value="<? echo $r3['cod'];?>"></div>
          <div class="two columns">Fecha de solicitud</div>
          <div class="four columns"><input type="date" name="f_solicitud" placeholder="aaaa-mm-dd" maxlength="10" class="required date seven" value="<? echo $r1['f_solicitud'];?>"></div>
          <div class="two columns">Afectación presupuestal</div>
          <div class="four columns">
            <select name="poa" required class="hyjack">
                <option value="" selected="selected">Seleccionar</option>
                <?php
                $sql="SELECT sys_bd_subactividad_poa.cod, 
                  sys_bd_subactividad_poa.codigo, 
                  sys_bd_subactividad_poa.nombre
                FROM sys_bd_subactividad_poa
                WHERE sys_bd_subactividad_poa.periodo='$anio'
                ORDER BY sys_bd_subactividad_poa.codigo ASC";
                $result=mysql_query($sql) or die (mysql_error());
                while($f1=mysql_fetch_array($result))
                {
                ?>
                <option value="<? echo $f1['cod'];?>" <? if ($f1['cod']==$r1['cod_poa']) echo "selected";?>><? echo $f1['codigo']."-".$f1['nombre'];?></option>
                <?
                }
                ?>
            </select>
          </div>
          <div class="two columns">Fuente de Financiamiento</div>
          <div class="four columns">
            <select name="fte_fto" class="medium">
              <option value="" selected="selected">Seleccionar</option>
              <option value="1" <? if ($r1['fte_fto']==1) echo "selected";?>>Fuente FIDA</option>
              <option value="2" <? if ($r1['fte_fto']==2) echo "selected";?>>Fuente RO</option>
              <option value="3" <? if ($r1['fte_fto']==3) echo "selected";?>>Fuente FIDA+RO</option>
            </select>
          </div>
          </div>
          <div class="two columns">Tipo de Incentivo</div>
          <div class="ten columns">
            <select name="incentivo" class="large">
              <option value="" selected="selected">Seleccionar</option>
              <option value="1" <? if ($r1['cod_tipo_incentivo']==1) echo "selected";?>>INCENTIVO AL DEPOSITO DE APERTURA</option>
              <option value="2" <? if ($r1['cod_tipo_incentivo']==2) echo "selected";?>>INCENTIVO AL MANTENIMIENTO Y AL CRECIMIENTO</option>
              <option value="3" <? if ($r1['cod_tipo_incentivo']==3) echo "selected";?>>INCENTIVO AL RETIRO</option>
            </select>
          </div>

          </div>

          <div class="row">
            <div class="twelve columns"><h6>II.- Grupos de ahorro</h6></div>
          </div>
          <table>
            <thead>
              <tr>
                <th>Organizacion</th>
                <th>IFI</th>
                <th>N. Ahorristas</th>
                <th>MONTO A TRANSFERIR (S/.)</th>
                <th>N. ATF</th>
              </tr>
            </thead>

            <tbody>
            <?php
            $sql="SELECT * FROM mh_bd_grupo WHERE cod_desembolso='$cod'";
            $result=mysql_query($sql) or die (mysql_error());
            while($r2=mysql_fetch_array($result))
            {
              $cad=$r2['cod'];
            ?>
              <tr>
                <td>
                <select name="grupo[<? echo $cad;?>]" required class="hyjack">
                <option value="" selected="selected">Seleccionar</option>
                  <?php
                  $sql="SELECT org_ficha_organizacion.cod_tipo_doc, 
                    org_ficha_organizacion.n_documento, 
                    org_ficha_organizacion.nombre
                  FROM org_ficha_organizacion
                  WHERE org_ficha_organizacion.cod_dependencia='".$row['cod_dependencia']."'
                  ORDER BY org_ficha_organizacion.nombre ASC";
                  $result1=mysql_query($sql) or die (mysql_error());
                  while($f2=mysql_fetch_array($result1))
                  {
                  ?>
                  <option value="<? echo $f2['cod_tipo_doc'].",".$f2['n_documento'];?>" <? if ($f2['cod_tipo_doc']==$r2['cod_tipo_doc'] and $f2['n_documento']==$r2['n_documento']) echo "selected";?>><? echo $f2['nombre'];?></option>
                  <?php
                  }
                  ?>
                  </select>
                </td>
                <td>
                  <select name="ifi[<? echo $cad;?>]" required class="hyjack">
                    <option value="" selected="selected">Seleccionar</option>
                    <?php
                    $sql="SELECT sys_bd_ifi.cod_ifi, 
                      sys_bd_ifi.descripcion
                    FROM sys_bd_ifi
                    ORDER BY sys_bd_ifi.descripcion ASC";
                    $result2=mysql_query($sql) or die (mysql_error());
                    while($f3=mysql_fetch_array($result2))
                    {
                    ?>
                    <option value="<? echo $f3['cod_ifi'];?>" <? if ($f3['cod_ifi']==$r2['cod_ifi']) echo "selected";?>><? echo $f3['descripcion'];?></option>
                    <?
                    }
                    ?>
                  </select>
                </td>
                <td><input type="text" name="n_ahorrista[<? echo $cad;?>]" class="number" value="<? echo $r2['n_ahorristas'];?>"></td>
                <td><input type="text" name="monto[<? echo $cad;?>]" class="number" value="<? echo $r2['monto'];?>"></td>
                <td><input type="text" name="n_at[<? echo $cad;?>]" class="digits" value="<? echo $r2['n_atf'];?>" readonly></td>
              </tr>
              <?php
              }
              ?>
              <input type="hidden" name="n_atf" value="<? echo $num;?>">
            </tbody>
          </table>
          </fieldset>         
          <?php
          }
          ?>
          <div class="twelve columns">
            <button type="submit" class="success button">Guardar cambios</button>
            <a href="ahorro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Cancelar</a>
          </div>
        </form>
      </div>

</div>
</div>


  <!-- Footer -->
<? include("footer.php");?>
  
  <!-- Included JS Files (Compressed) -->
  <script src="javascripts/jquery.js"></script>
  <script src="javascripts/foundation.min.js"></script>
  
  <!-- Initialize JS Plugins -->
  <script src="javascripts/app.js"></script>
  <script type="text/javascript" src="plugins/jquery.js"></script>


  <!-- VALIDADOR DE FORMULARIOS -->
  <script src="plugins/validation/jquery.validate.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" media="screen" href="plugins/validation/stylesheet.css" />
  <script type="text/javascript" src="plugins/validation/jquery.maskedinput.js"></script>
  <script type="text/javascript" src="plugins/validation/mktSignup.js"></script> 

  <!-- Combo Buscador -->
<link href="plugins/combo_buscador/hyjack.css" rel="stylesheet" type="text/css" />
<script src="plugins/combo_buscador/hyjack_externo.js" type="text/javascript"></script>
<script src="plugins/combo_buscador/configuracion.js" type="text/javascript"></script>
  
</body>
</html>
