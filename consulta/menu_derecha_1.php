<?php

if ($tipo==1)
{
	echo "<ul class='nav-bar vertical'>
	<li><a href=''>Registrar nuevo</a></li>
	<li><a href='container.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&modo=edit'>Modificar datos</a></li>
	<li><a href='container.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&modo=delete'>Eliminar registro</a></li>
	</ul>";
}
elseif($tipo==2)
{
	echo "<ul class='nav-bar vertical'>
	<li class='has-flyout'><a href=''>Registrar nuevo</a>
		<ul class='flyout'>
			<li><a href=''>Nueva organizacion perteneciente a un territorio</a></li>
			<li><a href=''>Nueva organizacion independiente</a></li>
		</ul>
	</li>
	<li><a href='container.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&modo=edit'>Modificar datos</a></li>
	<li><a href='container.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&modo=delete'>Eliminar registro</a></a>
	</ul>";
}
elseif($tipo==3)
{
	echo "<ul class='nav-bar vertical'>
	<li><a href='container.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&modo=edit'>Realizar Consistencia</a></li>
	<li><a href='container.php?SES=".$SES."&anio=".$anio."&tipo=".$tipo."&modo=imprime'>Imprimir</a></li>
	</ul>";
}


?>