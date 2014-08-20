<?
require("../funciones/sesion.php");
include("../funciones/funciones.php");
include("../funciones/error_page.php");
conectarte();

$sql="SELECT * FROM sys_bd_personal WHERE md5(n_documento)='$SES'";
$result=mysql_query($sql) or die (mysql_error());
$row=mysql_fetch_array($result);
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
  <script src="../rtables/javascripts/jquery.min.js"></script>
  <script src="../rtables/responsive-tables.js"></script>
    <!-- Included CSS Files (Compressed) -->
  <!--
  <link rel="stylesheet" href="stylesheets/foundation.min.css">
-->  
</head>
<body>
<? include("menu.php");?>

<? echo $mensaje;?>

<!-- Iniciamos el contenido -->
<div class="row">
<div class="three panel columns">
<!-- Menu vertical -->
 <ul class="nav-bar vertical">
 <li class="has-flyout">
	 <a href="">PIT</a>
	 <ul class="flyout">
		 <li><a href="n_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=mrn">Registrar PIT de Plan de Gestión de Recursos Naturales</a></li>
		 <li><a href="n_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar PIT de Plan de Negocios</a></li>
		 <li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir</a></li>
		 <li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar</a></li>
	 </ul>
 </li>
 <li class="has-flyout">
	 <a href="">PGRN</a>
	 <ul class="flyout">
		 <li><a href="n_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=mrn">Registrar nuevo</a></li>
		 <li><a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir carpeta</a></li>
		 <li><a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_familia">Imprimir lista de participantes</a></li>
		 <li><a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar</a></li>
	 </ul>
 </li> 
 <li class="has-flyout">
	 <a href="">PDN</a>
	 <ul class="flyout">
		 <li><a href="n_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit">Registrar Plan de Negocio asociado a un PIT</a></li>
		 <li><a href="n_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=amp">Registrar Plan de Negocio asociado a una Ampliacion</a></li>
		 <li><a href="n_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=ind">Registrar Plan de Negocio Independiente</a></li>
		 <li><a href="n_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=jov">Registrar Plan de Negocio de Jovenes - Fondo Reembolsable</a></li>
		 <li><a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir carpeta</a></li>
		 <li><a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime_familia">Imprimir lista de participantes</a></li>
		 <li><a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar</a></li>
	 </ul>
 </li> 
 
  <li class="has-flyout">
	 <a href="">IDL</a>
	 <ul class="flyout">
		 <li><a href="n_idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Registrar nuevo</a></li>
		 <li><a href="idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
		 <li><a href="idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Imprimir</a></li>
		 <li><a href="idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar</a></li>
	 </ul>
 </li>
</ul>
<!-- fin del menu vertical -->
<hr>
</div>

    <div class="nine columns">
      <div class="panel">
        <div class="row">
        <? include("../plugins/buscar/buscador.html");?>
        
       <table class="responsive" id="lista">
	        <thead>
		        <tr>
			        <th>Nº</th>
			        <th>Nº Doc</th>
			        <th>Nombre de la Organizacion</th>
			        <th>Fecha de presentacion</th>
			        <th>Estado</th>
			        <th></th>
		        </tr>
	        </thead>  
<?
$num=0;
if($row['cod_dependencia']==001)
{
$sql="SELECT pit_bd_ficha_pit.cod_pit, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.f_presentacion, 
	sys_bd_estado_iniciativa.descripcion AS estado
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pit.cod_estado_iniciativa
WHERE
sys_bd_estado_iniciativa.cod_estado_iniciativa=001
ORDER BY pit_bd_ficha_pit.f_presentacion DESC";
}
else
{
$sql="SELECT pit_bd_ficha_pit.cod_pit, 
	org_ficha_taz.n_documento, 
	org_ficha_taz.nombre, 
	pit_bd_ficha_pit.f_presentacion, 
	sys_bd_estado_iniciativa.descripcion AS estado
FROM org_ficha_taz INNER JOIN pit_bd_ficha_pit ON org_ficha_taz.cod_tipo_doc = pit_bd_ficha_pit.cod_tipo_doc_taz AND org_ficha_taz.n_documento = pit_bd_ficha_pit.n_documento_taz
	 INNER JOIN sys_bd_estado_iniciativa ON sys_bd_estado_iniciativa.cod_estado_iniciativa = pit_bd_ficha_pit.cod_estado_iniciativa
WHERE org_ficha_taz.cod_dependencia='".$row['cod_dependencia']."' AND
sys_bd_estado_iniciativa.cod_estado_iniciativa=001
ORDER BY pit_bd_ficha_pit.f_presentacion DESC";
}
$result=mysql_query($sql) or die (mysql_error());
while($fila=mysql_fetch_array($result))
{
$num++
?>
		        <tr>
			        <td><? echo numeracion($num);?></td>
			        <td><? echo $fila['n_documento'];?></td>
			        <td><? echo $fila['nombre'];?></td>
			        <td><? echo fecha_normal($fila['f_presentacion']);?></td>
			        <td><? echo $fila['estado'];?></td>
			        <td>
			        <?
			        if ($modo==imprime)
			        {
			        ?>
			        <a href="../print/print_demanda_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&cod=<? echo $fila['cod_pit'];?>" class="small button success">Imprimir</a>
			        <?
			        }
			        elseif($modo==edit)
			        {
			        ?>
			        <a href="m_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_pit'];?>" class="small button primary">Editar</a>
			        <?
			        }
			        elseif($modo==delete)
			        {
			        ?>
			        <a href="gestor_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&id=<? echo $fila['cod_pit'];?>&action=DELETE" class="small button alert" onclick="return confirm('Va a eliminar permanentemente este registro, desea proceder ?')">Eliminar</a>
			        <?
			        }
			        ?>
			        </td>
		        </tr>
<?
}
?>
        </table>

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
</body>
</html>
