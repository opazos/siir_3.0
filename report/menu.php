  <!-- Inicio del menu del SIIR -->
<?
$sql="SELECT sys_bd_dependencia.nombre
FROM sys_bd_dependencia
WHERE sys_bd_dependencia.cod_dependencia='".$row['cod_dependencia']."'";
$result=mysql_query($sql) or die (mysql_error());
$i1=mysql_fetch_array($result);
?>
  <nav class="top-bar fixed">
    <ul>
      <li class="name"><h1><a href="../principal.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">SIIR - OLP. <? echo $i1['nombre'];?></a></h1></li>
      <li class="toggle-topbar"><a href="#"></a></li>
    </ul>

    <section>
 

      <ul class="right">
      
      <li class="has-dropdown">
	      <a href="">Seguimiento y Evaluacion</a>
	      <ul class="dropdown">
		      <li><a href="report_ml.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Reporte Marco Logico</a></li>	
		      <li class="divider"></li>
		      <li><a href="report_oferente.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Reporte de Oferentes Contratados</a></li>     
	      </ul>
      </li>
      
      
        <li class="has-dropdown">
        <a href="">Iniciativas del NEC PDSS II</a>
        <ul class="dropdown">
	        <li><a href="report_pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Iniciativas y familias atendidas por Oficina Local</a></li>
	        <li class="divider"></li>
	        <li><a href="report_distrito.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Iniciativas atendidas a nivel de distrito</a></li>
	        <li class="divider"></li>
	        <li><a href="report_iniciativa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Iniciativas Territoriales por Oficina Local</a></li>
	        <li class="divider"></li>
	        <li><a href="report_ejec_iniciativa.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Ejecucion Financiera Iniciativas - Planes de Negocio</a></li>
	        <li class="divider"></li>
	        <li><a href="report_participantes.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Participantes en Iniciativas de PDN y PGRN</a></li>
        </ul>
        </li>
        <li class="divider"></li>
        <li class="has-dropdown">
	    <a href="">Administracion</a>
	    <ul class="dropdown">
			<li><a href="report_contratos.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Contratos firmados con Iniciativas</a></li>
			<li class="divider"></li>
			<li><a href="report_iniciativas.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Estado situacional de iniciativas</a></li>
			<li class="divider"></li>
			<li><a href="report_aporte.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Aporte de terceros</a></li>
			<li class="divider"></li>
			<li><a href="report_liquida.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Reporte de Liquidaciones</a></li>

	    </ul>
        </li>
        
        
      </ul>  
    </section>
  </nav>
  <!-- Termino del Menú del SIIR -->