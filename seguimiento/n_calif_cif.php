<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);

//1.- Buscamos los datos del concurso
$sql="SELECT org_ficha_organizacion.nombre, 
  cif_bd_concurso.n_concurso, 
  cif_bd_concurso.f_concurso, 
  cif_bd_concurso.actividad_1, 
  cif_bd_concurso.actividad_2, 
  cif_bd_concurso.actividad_3, 
  act1.descripcion AS describe1, 
  act1.unidad AS unidad1, 
  act2.descripcion AS describe2, 
  act2.unidad AS unidad2, 
  act3.descripcion AS describe3, 
  act3.unidad AS unidad3
FROM pit_bd_ficha_mrn INNER JOIN cif_bd_concurso ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn
   LEFT JOIN sys_bd_actividad_mrn act1 ON act1.cod = cif_bd_concurso.actividad_1
   LEFT JOIN sys_bd_actividad_mrn act2 ON act2.cod = cif_bd_concurso.actividad_2
   LEFT JOIN sys_bd_actividad_mrn act3 ON act3.cod = cif_bd_concurso.actividad_3
   INNER JOIN org_ficha_organizacion ON org_ficha_organizacion.cod_tipo_doc = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_organizacion.n_documento = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_concurso.cod_concurso_cif='$cod'";
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
       
  <div class="row collapse">

   <div class="row"> 
    <div class="twelve columns" align="center"><h6>FICHA DE REGISTRO Y VALORIZACIÓN DE ACTIVOS CONCURSO INTERFAMILIAR N. <? echo numeracion($r1['n_concurso']);?> - <? echo periodo($r1['f_concurso']);?><br/> DEL PGRN: <? echo $r1['nombre'];?></h6></div>
    <div class="twelve columns"><br/></div>
   </div> 

  <? include("../plugins/buscar/buscador.html");?>

  <form name="form5" method="POST" class="custom" action="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&action=ADD_CALIF" onsubmit="return checkSubmit();">

  <div class="row">
    <!-- Inicio la informacion del formulario -->
    <div class="two columns">Fecha del concurso</div>
    <div class="four columns"><? echo traducefecha($r1['f_concurso']);?></div>
    <div class="two columns">Actividad en la que concursará</div>
    <div class="four columns">
      <select name="actividad" class="medium" required>
        <option value="" selected="selected">Seleccionar</option>
        <?
        if($actividad<>NULL)
        {
        ?>
        <option value="<? echo $r1['actividad_1'];?>" <? if ($actividad==$r1['actividad_1']) echo "selected";?>><? echo $r1['describe1']."-".$r1['unidad1'];?></option>
        <option value="<? echo $r1['actividad_2'];?>" <? if ($actividad==$r1['actividad_2']) echo "selected";?>><? echo $r1['describe2']."-".$r1['unidad2'];?></option>
        <option value="<? echo $r1['actividad_3'];?>" <? if ($actividad==$r1['actividad_3']) echo "selected";?>><? echo $r1['describe3']."-".$r1['unidad3'];?></option>
        <?
        }
        ?>
        <!-- aca seguimos el proceso normal -->
        <?
        if($r1['actividad_1']<>NULL)
        {
          echo "<option value='".$r1['actividad_1']."'>".$r1['describe1']."-".$r1['unidad1']."</option>";
        }
        if($r1['actividad_2']<>NULL)
        {
          echo "<option value='".$r1['actividad_2']."'>".$r1['describe2']."-".$r1['unidad2']."</option>";
        } 
        if($r1['actividad_3']<>NULL)
        {
          echo "<option value='".$r1['actividad_3']."'>".$r1['describe3']."-".$r1['unidad3']."</option>";
        }             
        ?>
      </select>
    </div>
    <div class="twelve columns"><hr/></div>
<table id="lista">
<thead>
    <tr>
      <td rowspan="2"><small>N.</small></td>
      <td rowspan="2"><small>Nombre del participante</small></td>
      <td colspan="2"><small>Activo Físico antes del CIF</small></td>
      <td colspan="2"><small>Activo Físico logrado con el CIF</small></td>
      <td rowspan="2"><small>Puntaje</small></td>
      <td rowspan="2"><small>Puesto ocupado</small></td>
      <td colspan="2"><small>Premio recibido (S/.)</small></td>
      <td rowspan="2"><br/></td>
    </tr>
    <tr>
      <td><small>Cantidad</small></td>
      <td><small>Valor del activo (S/.)</small></td>
      <td><small>Cantidad</small></td>
      <td><small>Valor del activo (S/.)</small></td> 
      <td><small>PDSS II</small></td>
      <td><small>Otros</small></td>     
    </tr>
</thead>

<tbody>

<!--- Registros antiguos -->
<?
  $numa=0;
  $sql="SELECT org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno, 
  cif_bd_ficha_cif.meta_1, 
  cif_bd_ficha_cif.valor_1, 
  cif_bd_ficha_cif.meta_2, 
  cif_bd_ficha_cif.valor_2, 
  cif_bd_ficha_cif.puntaje, 
  cif_bd_ficha_cif.puesto, 
  cif_bd_ficha_cif.premio_pdss, 
  cif_bd_ficha_cif.premio_otro, 
  cif_bd_ficha_cif.cod_ficha_cif
  FROM cif_bd_concurso INNER JOIN cif_bd_ficha_cif ON cif_bd_concurso.cod_concurso_cif = cif_bd_ficha_cif.cod_concurso_cif
  INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_ficha_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_ficha_cif.n_documento
  INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
  WHERE cif_bd_ficha_cif.cod_concurso_cif='$cod'  AND
  cif_bd_ficha_cif.cod_actividad='$actividad'
  ORDER BY cif_bd_ficha_cif.puntaje DESC, cif_bd_ficha_cif.puesto ASC, org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $cad=$f1['cod_ficha_cif'];
    $numa++
?>
   <tr>
    <td><small><? echo $numa;?></small></td>
    <td><small><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></small></td>
    <td><input type="text" name="cantidad1a[<? echo $cad;?>]" value="<? echo $f1['meta_1'];?>"></td>
    <td><input type="text" name="valor1a[<? echo $cad;?>]" value="<? echo $f1['valor_1'];?>"></td>
    <td><input type="text" name="cantidad2a[<? echo $cad;?>]" value="<? echo $f1['meta_2'];?>"></td>
    <td><input type="text" name="valor2a[<? echo $cad;?>]" value="<? echo $f1['valor_2'];?>"></td>
    <td><input type="text" name="puntajea[<? echo $cad;?>]" value="<? echo $f1['puntaje'];?>"></td>
    <td><input type="text" name="puestoa[<? echo $cad;?>]" value="<? echo $f1['puesto'];?>"></td>
    <td><input type="text" name="premio_pdssa[<? echo $cad;?>]" value="<? echo $f1['premio_pdss'];?>"></td>
    <td><input type="text" name="premio_otroa[<? echo $cad;?>]" value="<? echo $f1['premio_otro'];?>"></td>
    <td><a href="gestor_cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $cod;?>&actividad=<? echo $actividad;?>&id=<? echo $f1['cod_ficha_cif'];?>&action=DELETE_CALIF"><img src="../images/Delete.png" border="0" width="48" height="48"></a></td>
  </tr>
<? 
  }
?>
<tr><td colspan="11"><hr/></td></tr>
<? 
  $num=0;
  if($actividad==NULL)
  {
  $sql="SELECT cif_bd_participante_cif.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno
FROM cif_bd_ficha_cif RIGHT OUTER JOIN cif_bd_participante_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
   INNER JOIN cif_bd_concurso ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
   INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
   INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
WHERE cif_bd_participante_cif.cod_concurso_cif='$cod'
ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  }
  else
  {
  $sql="SELECT cif_bd_participante_cif.n_documento, 
  org_ficha_usuario.nombre, 
  org_ficha_usuario.paterno, 
  org_ficha_usuario.materno
  FROM cif_bd_ficha_cif RIGHT OUTER JOIN cif_bd_participante_cif ON cif_bd_ficha_cif.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND cif_bd_ficha_cif.n_documento = cif_bd_participante_cif.n_documento AND cif_bd_ficha_cif.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
  INNER JOIN cif_bd_concurso ON cif_bd_concurso.cod_concurso_cif = cif_bd_participante_cif.cod_concurso_cif
  INNER JOIN org_ficha_usuario ON org_ficha_usuario.cod_tipo_doc = cif_bd_participante_cif.cod_tipo_doc AND org_ficha_usuario.n_documento = cif_bd_participante_cif.n_documento
  INNER JOIN pit_bd_ficha_mrn ON pit_bd_ficha_mrn.cod_mrn = cif_bd_concurso.cod_mrn AND org_ficha_usuario.cod_tipo_doc_org = pit_bd_ficha_mrn.cod_tipo_doc_org AND org_ficha_usuario.n_documento_org = pit_bd_ficha_mrn.n_documento_org
  WHERE cif_bd_participante_cif.cod_concurso_cif='$cod' AND
  cif_bd_ficha_cif.cod_ficha_cif IS NULL
  ORDER BY org_ficha_usuario.nombre ASC, org_ficha_usuario.paterno ASC, org_ficha_usuario.materno ASC";
  }
  $result=mysql_query($sql) or die (mysql_error());
  while($f1=mysql_fetch_array($result))
  {
    $num++
?>
  <tr>
    <td><small><? echo $num;?></small></td>
    <td><small><? echo $f1['nombre']." ".$f1['paterno']." ".$f1['materno'];?></small><input type="hidden" name="dni[]" value="<? echo $f1['n_documento'];?>"></td>
    <td><input type="text" name="cantidad_inicial[]"></td>
    <td><input type="text" name="valor_inicial[]"></td>
    <td><input type="text" name="cantidad_final[]"></td>
    <td><input type="text" name="valor_final[]"></td>
    <td><input type="text" name="puntaje[]"></td>
    <td><input type="text" name="puesto[]"></td>
    <td><input type="text" name="premio_pdss[]"></td>
    <td><input type="text" name="premio_otro[]"></td>
    <td><br/></td>
  </tr>
<?
  }
?>  
</tbody>
</table>
</div>

<div class="twelve columns">
<!-- Campos Ocultos -->
<input type="hidden" name="numeracion" value="<? echo $num;?>">
<button type="submit" class="primary button">Guardar cambios</button>
<a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=calif" class="secondary button">Finalizar</a>
</div> 

  	
  </form>
  </div>

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
  <script type="text/javascript" src="../plugins/buscar/js/buscar-en-tabla.js"></script>

  <!-- VALIDADOR DE FORMULARIOS -->
<script src="../plugins/validation/jquery.validate.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" media="screen" href="../plugins/validation/stylesheet.css" />
<script type="text/javascript" src="../plugins/validation/jquery.maskedinput.js"></script>
<script type="text/javascript" src="../plugins/validation/mktSignup.js"></script>    
</body>
</html>
