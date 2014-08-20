<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM bd_cfinal WHERE cod_concurso='$cod'";
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
  <!-- Con esto no podran dar click atras -->  
  <meta http-equiv="Expires" content="0" />
  <meta http-equiv="Pragma" content="no-cache" />

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
  
  <!-- Con este código deshabilito el click atras -->
  <script type="text/javascript">
  {
  if(history.forward(1))
  location.replace(history.forward(1))
  }
  </script> 
</head>
<body>
<? include("menu.php");?>


<!-- Iniciamos el contenido -->
<div class="row">
 <div class="twelve columns">
       
<!-- Contenedores -->
<dl class="tabs">
<dd  class="active"><a href="">Concursos finales - Jurado Calificador</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">

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
  <div class="twelve columns"><h6>1.1.- Ubicación del evento</h6></div>
  <div class="two columns">Departamento</div>
  <div class="four columns"><input type="text" name="departamento" class="required seven" value="<? echo $r1['departamento'];?>"></div>
  <div class="two columns">Provincia</div>
  <div class="four columns"><input type="text" name="provincia" class="required seven" value="<? echo $r1['provincia'];?>"></div>  
  <div class="two columns">Distrito</div>
  <div class="four columns"><input type="text" name="distrito" class="required seven" value="<? echo $r1['distrito'];?>"></div>
  <div class="six columns"><br/></div>
  <div class="twelve columns"><h6>1.2.- Total Iniciativas a premiar (Número máximo de ganadores)</h6></div>
  <div class="two columns">Categoría A</div>
  <div class="four columns"><input type="text" name="max_cat_a" class="required seven digits" value="<? echo $r1['max_gan_a'];?>"></div>
  <div class="two columns">Categoría B</div>
  <div class="four columns"><input type="text" name="max_cat_b" class="required seven digits" value="<? echo $r1['max_gan_b'];?>"></div>
  <div class="two columns">Categoría C</div>
  <div class="four columns"><input type="text" name="max_cat_c" class="required seven digits" value="<? echo $r1['max_gan_c'];?>"></div> 
  <div class="six columns"><br/></div>
  <div class="twelve columns"><h6>1.3.- Premios (S/.) y afectación presupuestal</h6></div>
  <table>
    <thead>
      <th class="ten">Rubro</th>
      <th>Monto (S/.)</th>
    </thead>
    <tbody>
      <tr>
        <td>Total monto Premio Plano (S/.)</td>
        <td><input type="text" name="premio_flat" class="required number" value="<? echo $r1['total_premio_flat'];?>"></td>
      </tr>
      <tr>
        <td>Total monto Premio Concurso (S/.)</td>
        <td><input type="text" name="premio_concurso" class="required number" value="<? echo $r1['total_premio_concurso'];?>"></td>
      </tr>   
      <tr>
        <td>Existe aporte de terceros para este concurso?</td>
        <td>
          <select name="aporte_otro" class="mini">
            <option selected="selected" value="">Seleccionar</option>
            <option value="1" <? if ($r1['aporte_otro']==1) echo "selected";?>>Si</option>
            <option value="0" <? if ($r1['aporte_otro']==0) echo "selected";?>>No</option>
          </select>
        </td>
      </tr>   
      <tr>
        <td>Total monto Aporte de terceros (S/.)</td>
        <td><input type="text" name="premio_otro" class="required number" value="<? echo $r1['total_premio_otro'];?>"></td>
      </tr>
    </tbody>
  </table>

  <div class="two columns">Afectación presupuestal</div>
  <div class="four columns">
    <select name="poa" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <?php
      $sql="SELECT sys_bd_subactividad_poa.cod, 
        sys_bd_subactividad_poa.codigo, 
        sys_bd_subactividad_poa.nombre
      FROM sys_bd_subactividad_poa
      WHERE sys_bd_subactividad_poa.periodo='$anio'
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
  <div class="two columns">Fte. Fto.</div>
  <div class="four columns">
    <select name="fte_fto" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <?
      $sql="SELECT sys_bd_fuente_fto.cod, 
      sys_bd_fuente_fto.descripcion
      FROM sys_bd_fuente_fto";
      $result=mysql_query($sql) or die (mysql_error());
      while($f3=mysql_fetch_array($result))
      {
      ?>
        <option value="<?php echo $f3['cod'];?>" <?php if ($f3['cod']==$r1['cod_fte_fto']) echo "selected";?>><?php echo $f3['descripcion'];?></option>
      <?php
      }
      ?>
    </select>
  </div>
</div>  

<div class="row">
  <div class="twelve columns"><h6>II.- Jurados del concurso</h6></div>

  <table>
    <thead>
      <tr>
        <th>N.</th>
        <th>Nombres y apellidos completos</th>
        <th>cargo que representará</th>
        <th><br/></th>
      </tr>
    </thead>

    <tbody>

      <?php
        $num=0;
        $sql="SELECT * FROM bd_jurado_cfinal WHERE cod_concurso='$cod'";
        $result=mysql_query($sql) or die (mysql_error());
        while($f6=mysql_fetch_array($result))
        {
          $cad=$f6['cod_jurado'];
          $num++
      ?>
      <tr>
        <td><?php echo $num;?></td>
        <td>
          <select name="nombrea[<? echo $cad;?>]">
            <option value="" selected="selected">Seleccionar</option>
            <?php
              $sql="SELECT * FROM clar_bd_miembro ORDER BY nombre ASC";
              $result1=mysql_query($sql) or die (mysql_error());
              while($f7=mysql_fetch_array($result1))
              {
            ?>
              <option value="<? echo $f7['n_documento'];?>" <? if ($f7['n_documento']==$f6['n_documento']) echo "selected";?>><? echo $f7['nombre']." ".$f7['paterno']." ".$f7['materno'];?></option>
            <?php
              }
            ?>
          </select>
        </td>
        <td>
          <select name="cargoa[<? echo $cad;?>]">
            <option value="" selected="selected">Seleccionar</option>
            <?php
              $sql="SELECT * FROM sys_bd_cargo_cf ORDER BY descripcion ASC";
              $result2=mysql_query($sql) or die (mysql_error());
              while($f8=mysql_fetch_array($result2))
              {
            ?>
              <option value="<? echo $f8['cod_cargo'];?>" <? if ($f8['cod_cargo']==$f6['cod_cargo_cf']) echo "selected";?>><? echo $f8['descripcion'];?></option>
            <?
              }
            ?>            
          </select>
        </td>
        <td>
          <a href="" class="tiny alert button">Eliminar</a>
        </td>
      </tr>
      <?
      }
      ?>
      <tr>
        <td colspan="3"><hr/></td>
      </tr> 
      <?php
      for ($i=1; $i<=5 ; $i++) 
      { 
      ?>
      <tr>
        <td><?php echo $i;?></td>
        <td>
          <select name="nombres[]">
            <option value="" selected="selected">Seleccionar</option>
            <?php
              $sql="SELECT * FROM clar_bd_miembro ORDER BY nombre ASC";
              $result=mysql_query($sql) or die (mysql_error());
              while($f4=mysql_fetch_array($result))
              {
                echo "<option value='".$f4['n_documento']."'>".$f4['nombre']." ".$f4['paterno']." ".$f4['materno']."</option>";
              }
            ?>
          </select>
        </td>
        <td>
          <select name="cargos[]">
            <option value="" selected="selected">Seleccionar</option>
            <?php
              $sql="SELECT * FROM sys_bd_cargo_cf ORDER BY descripcion ASC";
              $result=mysql_query($sql) or die (mysql_error());
              while($f5=mysql_fetch_array($result))
              {
                echo "<option value='".$f5['cod_cargo']."'>".$f5['descripcion']."</option>";
              }
            ?>            
          </select>
        </td>
        <td>-</td>
      </tr>
      <?php
      }
      ?>
    </tbody>
  </table>

</div>




  <div class="twelve columns">
    <hr/>
    <button type="submit" class="button" id="btn_envia">Guardar cambios</button>
    <a href="participante_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>" class="button success">Siguiente >></a>
    <a href="cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit" class="secondary button">Finalizar</a>
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
