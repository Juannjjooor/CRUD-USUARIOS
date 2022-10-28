<?php
//obtenemos el body de la petición (función creada por nosotros)
$userData = getRequestBody();
//Si no es un array lo que llega en el body cortamos ejecución, no han llegado datos en el body
if(!is_array($userData)){
//devolvemos un error
http_response_code(400);
echo '{"message": "Petición mal formada"}';
return;
}

//validamos que exista el parámetro
if(!key_exists('role_id',$userData)){
http_response_code(400);
echo '{"message": "Es necesario especificar el role_id del usuario"}';
return;
}

//validamos que exista el parámetro
if(!key_exists('email',$userData)){
http_response_code(400);
echo '{"message": "Es necesario que especifiques un email de usuario"}';
return;
}

//validamos que exista el parámetro
if(!key_exists('firstname',$userData)){
http_response_code(400);
echo '{"message": "Es necesario que especifiques un nombre de usuario"}';
return;
}

//validamos que exista el parámetro
if(!key_exists('lastname',$userData)){
http_response_code(400);
echo '{"message": "Es necesario que especifiques un apellido de usuario"}';
return;
}

//validamos que exista el parámetro
if(!key_exists('confirmed',$userData)){
http_response_code(400);
echo '{"message": "Es necesario que especifiques un valor para confirmado"}';
return;
}

//validamos que exista el parámetro
if(!key_exists('enabled',$userData)){
http_response_code(400);
echo '{"message": "Es necesario que especifiques un valor para activo"}';
return;
}

//partimos la ruta para extraer recurso + id del usuario que se quiere acceder
$uriParts = explode('/',substr($uri,1));

//si la ruta no tiene 2 framentos (recurso + id) no ejecuta el código dentro del if
if($uriParts[0] === 'users' && count($uriParts) === 2){
//editamos el usuario, si todo va bien tenemos el id del usuario, en caso contrario un null
$id = editUser($conn, $userData, $uriParts[1]);

//si no se ha editado el usuario devolvemos un error
if(!$id){
    //devolvemos un error
    http_response_code(400);
    echo '{"message": "No se ha podido actualizar el usuario, revisa los datos enviados"}';
    return;
}

//obtenemos los datos del usuario
$user = getUser($conn, $id);
//eliminamos el campo contraseña para evitar filtrados
unset($user['password']);
//devolvemos la respuesta con código 200 + json con datos del usuario
http_response_code(200);
echo json_encode($user);
}