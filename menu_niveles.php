  <!-- Inicio del menu del SIIR -->

  <nav class="top-bar fixed">
    <ul>
      <li class="name"><h1><a href="../principal.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">SIIR</a></h1></li>
      <li class="toggle-topbar"><a href="#"></a></li>
    </ul>

    <section>


      <ul class="right">
        <li class="has-dropdown">
          <a href="#">Organizaciones</a>

          <ul class="dropdown">
          	<li><label>Territorios</label></li>
          	<li><a href="../n_territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Nuevo territorio</a></li>
          	<li><label>Mantenimiento de la informacion</label></li>
          	<li><a href="../territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
          	<li><a href="../territorio.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar</a></li>
          	<li><label>Organizaciones y familias</label></li>
            <li><a href="../n_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=pit">Nueva organización vinculada a un territorio</a></li>
            <li><a href="../n_organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Nueva organizacion independiente</a></li>
            <li><label>Mantenimiento de la informacion</label></li>
            <li><a href="../organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=edit">Modificar</a></li>
            <li><a href="../organizacion.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>&modo=delete">Eliminar</a></li>
          </ul></li>
         <li class="divider"></li>
         <li class="has-dropdown"><a href="">Iniciativas</a>
         <ul class="dropdown">
         <li><a href="../inic_primero.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Iniciativas que participan en un CLAR de Primer Desembolso</a></li>
         <li><a href="../inic_segundo.php?SES=<? echo $SES;?>&anio=<? echo $anio;?>">Iniciativas que participan en un CLAR de Segundo Desembolso</a></li>
	      </ul>
         </li>
         <li class="divider"></li>
        <li class="has-dropdown"><a href="">Evento CLAR</a>
        <ul class="dropdown">
	        <li><a href="">Registro de evento CLAR</a></li>
	        <li><a href="">Asignacion de iniciativas a un CLAR</a></li>
	        <li><a href="">Calificación de iniciativas</a></li>
	        <li><a href="">Premiacion de mapas culturales</a></li>
	        <li><a href="">Registro e impresion de contratos - Primer desembolso</a></li>
	        <li><a href="">Registro e impresión de ATF's - Segundo desembolso</a></li>
        </ul>
        </li>
        <li class="divider"></li>
        <li class="has-dropdown"><a href="">Recursos naturales</a>
        <ul class="dropdown">
	        <li><a href="">Planes de Inversión Territorial - Mantenimiento y monitoreo</a></li>
	        <li><a href="">Planes de Gestión de Recursos Naturales - Mantenimiento y monitoreo</a></li>
	        <li><a href="">Concursos Interfamiliares</a></li>
        </ul>
        </li>
        <li class="divider"></li>
        <li class="has-dropdown"><a href="">Mercados locales</a>
        <ul class="dropdown">
	        <li><a href="">Planes de Negocio - Mantenimiento y monitoreo</a></li>
	        <li><a href="">Inversiones para el desarrollo local - Mantenimiento y monitoreo</a></li>
	        <li class="divider"></li>
	        <li><a href="">Eventos de Promocion comercial</a></li>
	        <li><a href="">Eventos de Visita guiada</a></li>
	        <li><a href="">Eventos de Participacion en ferias</a></li>
        </ul>
        </li>
        <li class="divider"></li>
        <li class="has-dropdown"><a href="">Gestión del conocimiento</a>
        <ul class="dropdown">
        <li><label>Promoción y difusion</label></li>
	        <li><a href="">Eventos de promoción y difusion</a></li>
	        <li><a href="">Giras de aprendizaje e intercambio de conocimientos</a></li>
	        <li class="divider"></li>
	     <li><label>Convenios Interistitucionales</label></li> 
	     <li><a href="">Registrar convenio</a></li>  
	     <li><a href="">Modificar</a></li>
	     <li><a href="">Imprimir</a></li>
	     <li class="divider"></li>
	     <li><label>Eventos de Gestión del Conocimiento</label></li>
	     <li><a href="">Concursos de valorizacion del conocimiento</a></li>
	     <li><a href="">Rutas de aprendizaje e intercambio de conocimientos</a></li>
	     <li><a href="">Participación en diplomados</a></li>
	        
        </ul>
        </li>
      </ul>
    </section>
  </nav>

  <!-- Termino del Menú del SIIR -->