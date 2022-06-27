<?php
include_once '../koneksi.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}

$id_transaksi = $_POST['id_transaksi'] ?? '';
$id_user = $_POST['id_user'] ?? '';
$id_pelanggan = $_POST['id_pelanggan'] ?? '';
$tgl_bayar = $_POST['tgl_bayar'] ?? date('Y-m-d') ;
$total_harga = $_POST['total_harga'] ?? '';
$berat_pakaian = $_POST['berat_pakaian'] ?? '';
$katalog = $_POST['katalog'] ?? '';
$status = $_POST['status'] ?? '';
$dibayar = $_POST['dibayar'] ?? '';

$isValidated = true;

if(empty($id_transaksi)){
    $reply['error'] = 'id_transaksi harus diisi';
    $isValidated = false;
}
if(empty($id_user)){
    $reply['error'] = 'id_user harus diisi';
    $isValidated = false;
}
if(empty($id_pelanggan)){
    $reply['error'] = 'id_pelanggan harus diisi';
    $isValidated = false;
}
if(empty($total_harga)){
    $reply['error'] = 'total harga harus diisi';
    $isValidated = false;
}
if(empty($berat_pakaian)){
    $reply['error'] = 'berat pakaian harus diisi';
    $isValidated = false;
}
if(empty($katalog)){
    $reply['error'] = 'katalog harus diisi';
    $isValidated = false;
}
if(empty($status)){
    $reply['error'] = 'status harus diisi';
    $isValidated = false;
}
if(empty($dibayar)){
    $reply['error'] = 'dibayar harus diisi';
    $isValidated = false;
}

if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

try{
    $query = "INSERT INTO transaksi (id_transaksi, id_user, id_pelanggan, tgl_bayar, total_harga, berat_pakaian, katalog, status, dibayar) 
VALUES (:id_transaksi, :id_user, :id_pelanggan, :tgl_bayar, :total_harga, :berat_pakaian, :katalog, :status, :dibayar)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":id_transaksi", $id_transaksi);
    $statement->bindValue(":id_user", $id_user);
    $statement->bindValue(":id_pelanggan", $id_pelanggan);
    $statement->bindValue(":tgl_bayar", $tgl_bayar);
    $statement->bindValue(":total_harga", $total_harga);
    $statement->bindValue(":berat_pakaian", $berat_pakaian);
    $statement->bindValue(":katalog", $katalog);
    $statement->bindValue(":status", $status);
    $statement->bindValue(":dibayar", $dibayar);
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
$getResult = "SELECT * FROM transaksi WHERE id_transaksi = :id_transaksi";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_transaksi', $id_transaksi);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

/**
 * Show output to client
 * Set status info true
 */
$reply['transaksi'] = $result;
$reply['status'] = $isOk;
echo json_encode($reply);