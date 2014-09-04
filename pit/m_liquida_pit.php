<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

$sql="SELECT * FROM pit_bd_pit_liquida WHERE cod_ficha_liq='$cod'";
$result=mysql_query($sql) or die (mysql_error());
$f1=mysql_fetch_array($result);

//1.- obtengo los montos ejecutados
$sql="SELECT SUM(ficha_animador.aporte_pdss) AS aporte_pdss, 
  SUM(ficha_animador.aporte_org) AS aporte_org, 
  SUM(ficha_animador.aporte_otro) AS aporte_otro
FROM pit_bd_ficha_pit INNER JOIN ficha_animador ON pit_bd_ficha_pit.cod_pit = ficha_animador.cod_iniciativa AND pit_bd_ficha_pit.cod_tipo_iniciativa = ficha_animador.cod_tipo_iniciativa
WHERE pit_bd_ficha_pit.cod_pit='".$f1['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r2=mysql_fetch_array($result);

//3.- Obtengo la addenda
//****** Busco Informacion de addendas
$sql="SELECT pit_bd_ficha_adenda.n_adenda, 
  pit_bd_ficha_adenda.f_adenda, 
  pit_bd_ficha_adenda.f_inicio, 
  pit_bd_ficha_adenda.meses, 
  pit_bd_ficha_adenda.f_termino
FROM pit_bd_ficha_adenda
WHERE pit_bd_ficha_adenda.cod_pit='".$f1['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r3=mysql_fetch_array($result);
$total_adenda=mysql_num_rows($result);

//******* Busco Información de montos
$sql="SELECT pit_adenda_pit.aporte_pdss, 
  pit_adenda_pit.aporte_org
FROM pit_adenda_pit
WHERE pit_adenda_pit.cod_pit='".$f1['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r5=mysql_fetch_array($result);


//4.- Obtengo el monto programado
$sql="SELECT pit_bd_ficha_pit.aporte_pdss, 
  pit_bd_ficha_pit.aporte_org
FROM pit_bd_ficha_pit
WHERE pit_bd_ficha_pit.cod_pit='".$f1['cod_pit']."'";
$result=mysql_query($sql) or die (mysql_error());
$r4=mysql_fetch_array($result);

$total_programado=$r5['aporte_pdss']+$r4['aporte_pdss'];

$saldo=$total_programado-$r2['aporte_pdss'];
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
<dd  class="active"><a href="">Liquidación de Contratos PIT</a></dd>
</dl>
<!-- Termino contenedores -->
<ul class="tabs-content">
<li class="active" id="simple1Tab">
<div class="row collapse">
<form name="form5" method="post" class="custom" action="gestor_liquida_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&action=UPDATE" onsubmit="return checkSubmit();">

<div class="row">
  <div class="twelve columns"><h6>I.- Datos de la iniciativa a liquidar</h6></div>
  <div class="two columns">Número de contrato</div>
  <div class="four columns">
  <input type="hidden" name="codigo" value="<? echo $f1['cod_ficha_liq'];?>">

    <select name="pit" class="medium" disabled="disabled">
      <option value="" selected="selected">Seleccionar</option>
    <?
    $sql="SELECT pit_bd_ficha_pit.cod_pit, 
    pit_bd_ficha_pit.n_contrato, 
    pit_bd_ficha_pit.f_contrato, 
    sys_bd_tipo_iniciativa.codigo_iniciativa, 
    sys_bd_dependencia.nombre AS oficina
    FROM sys_bd_tipo_iniciativa INNER JOIN pit_bd_ficha_pit ON sys_bd_tipo_iniciativa.cod_tipo_iniciativa = pit_bd_ficha_pit.cod_tipo_iniciativa
    INNER JOIN org_ficha_taz ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
    INNER JOIN sys_bd_dependencia ON sys_bd_dependencia.cod_dependencia = org_ficha_taz.cod_dependencia
    WHERE pit_bd_ficha_pit.n_contrato<>0 AND
    org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."'
    ORDER BY org_ficha_taz.cod_dependencia ASC, pit_bd_ficha_pit.f_contrato ASC, pit_bd_ficha_pit.n_contrato ASC";
    $result=mysql_query($sql) or die (mysql_error());
    while($r1=mysql_fetch_array($result))
    {
    ?>
    <option value="<? echo $r1['cod_pit'];?>" <? if($r1['cod_pit']==$f1['cod_pit']) echo "selected";?>><? echo numeracion($r1['n_contrato'])."-".periodo($r1['f_contrato'])."-".$r1['codigo_iniciativa']."-".$r1['oficina'];?></option>
    <?
    }
    ?>

    </select>
  </div>
  <div class="two columns">Fecha de liquidación</div>
  <div class="four columns"><input type="date" name="f_liquidacion" class="seven date" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $f1['f_liquidacion'];?>"></div>
  <div class="two columns">Tipo de liquidación</div>
  <div class="ten columns">
    <select name="tipo_liquidacion" class="large">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1" <? if ($f1['cod_tipo']==1) echo "selected";?>>Liquidación Total - Se liquida el monto total del PIT</option>
      <option value="2" <? if ($f1['cod_tipo']==2) echo "selected";?>>Liquidación Parcial - Se liquida el PIT parcialmente debido a que no se a ejecutado el total del presupuesto</option>
    </select>
  </div>
  <div class="twelve columns"><h6>1.1 Si el PIT se esta liquidando de forma parcial, indicar los motivos</h6></div>
  <div class="twelve columns"><textarea name="comentario" rows="10" cols="80" style="width: 100%"><? echo $f1['comentario'];?></textarea></div>
  <div class="twelve columns"><h6>1.2 Si el PIT recibió un segundo desembolso:</h6></div>
  <div class="two columns">Fecha de desembolso del monto de animador territorial</div>
  <div class="four columns"><input type="date" name="f_desembolso" class="date seven" placeholder="aaaa-mm-dd" maxlength="10" value="<? echo $f1['f_desembolso'];?>"></div>
  <div class="two columns">Nº de cheque con el que se desembolso</div>
  <div class="four columns"><input type="text" name="n_cheque" class="seven" value="<? echo $f1['n_cheque'];?>"></div> 
  <div class="twelve columns"><br/></div> 
  <div class="two columns">Hubo cambios en la junta directiva</div>
  <div class="four columns">
    <select name="hc_dir" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1" <? if ($f1['hc_dir']==1) echo "selected";?>>SI</option>
      <option value="0" <? if ($f1['hc_dir']==0) echo "selected";?>>NO</option>
    </select>
  </div>
  <div class="two columns">Si hubo cambios indicar los motivos</div>
  <div class="four columns"><textarea name="just_dir"><? echo $f1['just_dir'];?></textarea></div>

  <div class="twelve columns"><h6>II.- Resultados obtenidos en el Territorio (Resumir resultados de los Animadores Territoriales)</h6></div>
  <div class="twelve columns"><textarea name="resultado" rows="10" cols="80" style="width: 100%"><? echo $f1['resultado'];?></textarea></div>

  <div class="twelve columns"><h6>III.- Sobre el mapa territorial</h6></div>
  <div class="two columns">Se aplicó y utilizó el mapa territorial en el territorio?</div>
  <div class="four columns">
    <select name="mapa" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1" <? if ($f1['aplicacion']==1) echo "selected";?>>SI</option>
      <option value="0" <? if ($f1['aplicacion']==0) echo "selected";?>>NO</option>
    </select>
  </div>
  <div class="two columns">Si la respuesta es afirmativa. Que uso se le dió al mapa?</div>
  <div class="four columns">
    <select name="uso_mapa" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1" <? if ($f1['uso']==1) echo "selected";?>>Para presentar avances</option>
      <option value="2" <? if ($f1['uso']==2) echo "selected";?>>Evaluar resultados</option>
      <option value="3" <? if ($f1['uso']==3) echo "selected";?>>Presentaciones diversas</option>
      <option value="4" <? if ($f1['uso']==4) echo "selected";?>>Otros usos</option>
    </select>
  </div>
  <div class="twelve columns"><br/></div>
  <div class="two columns">Ha ganado algún concurso de mapas territoriales?</div>
  <div class="ten columns">
    <select name="concurso" class="medium">
      <option value="" selected="selected">Seleccionar</option>
      <option value="1" <? if ($f1['concurso']==1) echo "selected";?>>SI</option>
      <option value="0" <? if ($f1['concurso']==0) echo "selected";?>>NO</option>
    </select>
  </div>
<div class="twelve columns"><h6>Si la respuesta es afirmativa...</h6></div>
<div class="two columns">Puesto ocupado</div>
<div class="four columns"><input type="text" name="puesto" class="seven digits" value="<? echo $f1['puesto'];?>"></div>
<div class="two columns">Monto del premio obtenido</div>
<div class="four columns"><input type="text" name="premio" class="seven number" value="<? echo $f1['premio'];?>"></div>



  <div class="twelve columns"><h6>IV.- Ejecución Presupuestal</h6></div>

  <div class="two columns">Número de addenda</div>
  <div class="four columns"><input type="text" name="n_adenda" class="seven" value="<? echo $r3['n_adenda'];?>" disabled="disabled"></div>
  <div class="two columns">Fecha de addenda</div>
  <div class="four columns"><input type="date" name="f_adenda" class="seven" disabled="disabled" value="<? echo $r3['f_adenda'];?>"></div>

  <div class="two columns">Presupuesto programado (S/.)</div>
  <div class="four columns"><input type="text" name="monto_programado" class="seven number" disabled="disabled" value="<? echo $total_programado;?>"></div>
  <div class="two columns">Presupuesto ejecutado</div>
  <div class="four columns"><input type="text" name="monto_ejecutado" class="seven number" readonly="readonly" value="<? echo $r2['aporte_pdss'];?>"> <input type="hidden" name="saldo" value="<? echo $saldo;?>"></div>

</div>

<br/>

<div class="twelve columns">
<button type="submit" class="success button">Guardar cambios</button>
<a href="pit_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime" class="secondary button">Finalizar</a>
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
