<?php

// La petición está usando el verbo POST
  //ruta para creación de usuario
  if($uri = "/users"){
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
    if(!key_exists('password',$userData)){
      http_response_code(400);
      echo '{"message": "Es necesario que especifiques la contraseña de usuario"}';
      return;
    }

    //creamos el usuario
    $result = insertUser($conn, $userData);
    //aquí deberíamos devolver un error si $result es null

    //obtenemos la info del usuario anteriormente creado y guardamos en $user
    $user = getUser($conn, $result);

    //quitamos la clave password para evitar filtraciones de la contraseña
    unset($user['password']);

    //devolvemos la respuesta con código 201 + json con datos del usuario
    http_response_code(201);
    echo json_encode($user);
    return;
  }