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
	      <a href="">Evento CLAR</a>
	      <ul class="dropdown">
		      <li><a href="jurado_clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Jurado para eventos CLAR</a></li>
		      <li class="divider"></li>
		      <li><a href="clar.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Eventos CLAR</a></li>
		      <li class="divider"></li>
		      <li><a href="intercon.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Contrato para la realizacion de un INTERCON</a></li>
		      <li class="divider"></li>
		      <li><a href="calif_clar_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Calificacion de Iniciativas - Primer Desembolso</a></li>
		      <li class="divider"></li>
		      <li><a href="calif_clar_segundo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Calificacion de Iniciativas - Segundo Desembolso</a></li>
	      </ul>
      </li>
      	
      	
      	
      	<li class="divider"></li>
      	
      	<li class="has-dropdown">
	      	<a href="#">Concursos INTERCON</a>
	      	<ul class="dropdown">
	      		<li><a href="concurso.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Realización de Concursos</a></li>
	      		<li class="divider"></li>
	      		<li><a href="calif_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica">Calificacion - Concurso de Planes de Negocio</a></li>
	      		<li class="divider"></li>
	      		<li><a href="calif_map.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica">Calificación - Concurso de Mapas Territoriales</a></li>
	      		<li class="divider"></li>
	      		<li><a href="calif_danza.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica">Calificación - Concurso de Danzas Tipicas</a></li>
	      		<li class="divider"></li>
	      		<li><a href="calif_gastro.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica">Calificacion - Concurso de Gastronomía</a></li>
	      		<li class="divider"></li>
	      		<li><a href="calif_joven.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica">Calificacion - Concurso de PDN Jovenes</a></li>
	      		<li class="divider"></li>
	      		<li><a href="calif_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=califica">Calificacion - Concurso de Territorios</a></li>
	      		
	      	</ul>
      	</li>

      	<li class="divider"></li>

      	<li class="has-dropdown">
      		<a href="#">Concursos de resultados finales</a>
      		<ul class="dropdown">
      			<li><a href="jurado_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Jurado para el concurso</a></li>
      			<li class="divider"></li>
      			<li><a href="cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Concursos finales</a></li>
      			<li class="divider"></li>
            <li><a href="cal_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Calificaciones</a></li>
            <li class="divider"></li>
            <li><a href="premia_cf.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=acta">Premiación de ganadores</a></li> 
      		</ul>
      	</li>
      
      </ul>  
    </section>
  </nav>
  <!-- Termino del Menú del SIIR -->