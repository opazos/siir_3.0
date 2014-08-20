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
      	<li class="has-dropdown"><a href="">Planes de Inversion Territorial</a>
      	<ul class="dropdown">
      	<li><a href="pit.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Mantenimiento y regularizacion de información</a></li>
      	<li class="divider"></li>
      	<li><a href="animador.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Animadores territoriales</a></li>
      	</ul>
      	</li>
        
        <li class="has-dropdown"><a href="">Manejo de Recursos Naturales</a>
        <ul class="dropdown">
        <li><a href="mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Mantenimiento y regularizacion de información</a></li>
        <li class="divider"></li>
        <li><a href="cif.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=imprime">Concursos Interfamiliares</a></li>
        <li class="divider"></li>
        <li><a href="at_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Asistentes Tecnicos</a></li>
        <li class="divider"></li>
        <li><a href="ag_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Apoyo a la Gestión</a></li>
        <li class="divider"></li>
        <li><a href="vg_mrn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Visitas Guiadas</a></li>    
        </ul>
        </li>
        
        
        
        <li class="divider"></li>
        <li class="has-dropdown"><a href="">Planes de Negocio</a>
        <ul class="dropdown">
	        <li><a href="pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Mantenimiento y regularizacion de información</a></li>
          <li class="divider"></li>  
	        <li><a href="at_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Asistentes Tecnicos</a></li>
	        <li class="divider"></li>
	        <li><a href="ag_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Apoyo a la Gestión</a></li>
	        <li class="divider"></li>
	        <li><a href="vg_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Visitas Guiadas</a></li>
	        <li class="divider"></li>
	        <li><a href="pf_pdn.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Participacion en Ferias</a></li>

        </ul>
        </li>
        <li class="divider"></li>
        <li class="has-dropdown"><a href="">Inversiones para el desarrollo local</a>
        <ul class="dropdown">
	        <li><a href="idl.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Mantenimiento y regularizacion de información</a></li>
        </ul>
        </li>
        

      </ul>
    </section>
  </nav>

  <!-- Termino del Menú del SIIR -->