<?php

function getRequestBody(){
  $json = file_get_contents('php://input');
  
  return json_decode($json, true);
}

function insertUser($conn, $userData){
  $insertUser = [
    ':role_id' => $userData['role_id'],
    ':email' => $userData['email'],
    ':firstname' => $userData['firstname'],
    ':lastname' => $userData['lastname'],
    ':password' => hash("sha512", $userData['password']),
    ':confirmed' => 0,
    ':enabled' => 1,
    ':created_at' => (new DateTime())->format('Y-m-d H:i:s')
  ];
  
  $insertSQL = "INSERT INTO usuarios (role_id, email, firstname, lastname, password, confirmed, enabled, created_at) VALUES (:role_id, :email, :firstname, :lastname, :password, :confirmed, :enabled, :created_at)";
  //le decimos a PDO que prepare la consulta de $insertSQL para su uso posterior
  $query = $conn->prepare($insertSQL);
  
  try{
    // Vincula y executa
    if($query->execute($insertUser)) {
        return $conn->lastInsertId();
    }
  }catch(Exception $e){
    return $e->getMessage();
  }
}

function getUser($conn, $id){
  $userSQL = "SELECT * FROM usuarios WHERE id=:id";
  $query = $conn->prepare($userSQL);
  // Especificamos el fetch mode antes de llamar a fetch()
  $query->setFetchMode(PDO::FETCH_ASSOC);
  // Ejecutamos
  $query->execute([':id' => $id]);
  // Mostramos los resultados
  $users = $query->fetchAll();

  if(count($users) === 0){
    return null;
  }

  return $users[0];
}

function getUserList($conn){
  $usersSQL = "SELECT * FROM usuarios ORDER BY firstname ASC, lastname ASC";
  $query = $conn->prepare($usersSQL);
  // Especificamos el fetch mode antes de llamar a fetch()
  $query->setFetchMode(PDO::FETCH_ASSOC);
  // Ejecutamos
  $query->execute();
  // retornamos los resultados de la base de datos según la consulta SQL y los devolvemos directamente
  return $query->fetchAll();
}
  
function editUser($conn, $userData, $id){
  $updateUser = [
    ':role_id' => $userData['role_id'],
    ':email' => $userData['email'],
    ':firstname' => $userData['firstname'],
    ':lastname' => $userData['lastname'],
    ':confirmed' => $userData['confirmed'],
    ':enabled' => $userData['enabled'],
    ':id' => $id
  ];
  
  $updateSQL = "UPDATE usuarios SET role_id=:role_id, email=:email, firstname=:firstname, lastname=:lastname, confirmed=:confirmed, enabled=:enabled WHERE id=:id";
  //le decimos a PDO que prepare la consulta de $insertSQL para su uso posterior
  $query = $conn->prepare($updateSQL);
  
  try{
    // Vincula y executa
    if($query->execute($updateUser)) {
        return $id;
    }
  }catch(Exception $e){
    return null;
  }
}

function deleteUser($conn, $id){
  $deleteUser = [
    ':id' => $id
  ];
  
  $deleteSQL = "DELETE FROM usuarios WHERE id=:id";
  //le decimos a PDO que prepare la consulta de $insertSQL para su uso posterior
  $query = $conn->prepare($deleteSQL);
  
  try{
    // Vincula y executa
    if($query->execute($deleteUser)) {
      return $query->rowCount();
    }
  }catch(Exception $e){
    return 0;
  }
}