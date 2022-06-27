<?php
include_once '../koneksi.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}

$id_user = $_POST['id_user'] ?? '';
$username = $_POST['username'] ?? '';
$password = password_hash($_POST['password'], PASSWORD_DEFAULT) ?? '';
$role = $_POST['role'] ??  '' ;




$isValidated = true;

if(empty($id_user)){
    $reply['error'] = 'id_user harus diisi';
    $isValidated = false;
}
if(empty($username)){
    $reply['error'] = 'username harus diisi';
    $isValidated = false;
}
if(empty($password)){
    $reply['error'] = 'password harus diisi';
    $isValidated = false;
}
if(empty($role)){
    $reply['error'] = 'role harus diisi';
    $isValidated = false;
}

if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

try{
    $query = "INSERT INTO user (id_user, username, password, role) 
VALUES (:id_user, :username, :password, :role)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":id_user", $id_user);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":password", $password);
    $statement->bindValue(":role", $role);

    /**
     * Execute query
     */
    $isOk = $statement->execute();
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/**
 * If not OK, add error info
 * HTTP Status code 400: Bad request
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#client_error_responses
 */
if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}

/*
 * Get last data
 */
$getResult = "SELECT * FROM user WHERE id_user = :id_user";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_user', $id_user);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

/**
 * Show output to client
 * Set status info true
 */
$reply['user'] = $result;
$reply['status'] = $isOk;
echo json_encode($reply);