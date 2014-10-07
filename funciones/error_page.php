<?
/*archivo que nos devuelve los mensajes de error de acuerdo a lo que salga*/
extract($_POST);
extract($_GET);


//Manejo de  Errores

$error=$_GET['error'];

switch($error)
{
case 'loginfailed':
	echo "<div class='alert-box alert'>Los datos de acceso son incorrectos, por favor vuelva ingresar los datos correctamente.<a href='' class='close'>&times;</a></div>";
	break;
case 'not_period':
	echo "<div class='alert-box alert'>Seleccione un periodo  para poder empezar a trabajar.<a href='' class='close'>&times;</a></div>";
	break;
case 'incorrect_password':
	echo "<div class='alert-box alert'>El password ingresado no es correcto.<a href='' class='close'>&times;</a></div>";
	break;
case 'vacio':
	echo "<div class='alert-box alert'>Usted olvido ingresar informacion que es necesaria para proceder.<a href='' class='close'>&times;</a></div>";
	break;
case 'pit_null':
	echo "<div class='alert-box alert'>El territorio de esta organizacion aun no a generado una demanda para participar en un CLAR, genere la demanda y vuelva a realizar la operacion.<a href='' class='close'>&times;</a></div>";
	break;
case 'no_dni':
	echo "<div class='alert-box alert'>El sistema no encontro este DNI en la base de datos.<a href='' class='close'>&times;</a></div>";			

}

//Manejo de avisos y alertas
$alert=$_GET['alert'];

switch ($alert) 
{
case 'success_insert':
	echo "<div class='alert-box success'>Los datos han sido ingresados correctamente.<a href='' class='close'>&times;</a></div>";
	break;
case 'success_change':
	echo "<div class='alert-box success'>Los datos han sido actualizados correctamente.<a href='' class='close'>&times;</a></div>";
	break;
case 'success_delete':
	echo "<div class='alert-box success'>El registro a sido eliminado correctamente.<a href='' class='close'>&times;</a></div>";
	break;
}

?>


