<?php

// La petición está usando el verbo GET
if($uri === '/users'){
    //obtiene el listado de usuarios y guarda en users
    $users = getUserList($conn);

    //devolvemos la respuesta con código 200 + json con datos de los usuarios
    http_response_code(200);
    echo json_encode($users);
    return;
}

//partimos la ruta para extraer recurso + id del usuario que se quiere acceder
$uriParts = explode('/',substr($uri,1));

//si la ruta no tiene 2 framentos (recurso + id) no ejecuta el código dentro del if
if($uriParts[0] === 'users' && count($uriParts) === 2){
    //obtiene la información del usuario y guarda en $user
    $user = getUser($conn, $uriParts[1]);

    //si no se encuentra el usuario devuelve error 404
    if(!$user){
        //devolvemos un error
        http_response_code(404);
        echo '{"message": "El usuario no existe"}';
        return;
    }

    //le quitamos el password para que no se pueda ver
    unset($user['password']);
    //devolvemos la respuesta con código 200 + json con datos del usuario
    http_response_code(200);
    echo json_encode($user);
    return;
}