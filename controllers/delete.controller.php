<?php

// La petición está usando el verbo DELETE
//partimos la ruta para extraer recurso + id del usuario que se quiere acceder
$uriParts = explode('/',substr($uri,1));

//si la ruta no tiene 2 framentos (recurso + id) no ejecuta el código dentro del if
if($uriParts[0] === 'users' && count($uriParts) === 2){
//borramos el usuario y devuelve las filas borradas (las opciones son 0 ó 1 ya que se borra por la clave primaria)
$affectedRows = deleteUser($conn, $uriParts[1]);

//si no hay filas borradas el elemento no existía (devolvemos un 404)
if($affectedRows === 0){
    //devolvemos un error
    http_response_code(404);
    echo '{"message": "El usuario no existe"}';
    return;
}

//devolvemos la respuesta con código 204 + cuerpo vacío
//204 = todo ha ido bien pero el cuerpo de la respuesta está vacío
http_response_code(204);
echo '';
return;
}