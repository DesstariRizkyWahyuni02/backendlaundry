<?php
include_once '../koneksi.php';
/*
 * Validate http method
 */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Content-Type: application/json');
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


/**
 * Validation empty fields
 */
$isValidated = true;
if(empty($id_transaksi)){
    $reply['error'] = 'id_transaksi harus diisi';
    $isValidated = false;
}


if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/*
 * Jika filter gagal
 */
if(!$isValidated){
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/**
 * METHOD OK
 * Validation OK
 * Check if data is exist
 */
try{
    $queryCheck = "SELECT * FROM transaksi where id_transaksi = :id_transaksi";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_transaksi', $id_transaksi);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'Data tidak ditemukan id_user '.$id_transaksi;
        echo json_encode($reply);
        http_response_code(400);
        exit(0);
    }
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

/**
 * Prepare query
 */
try{
    $fields = [];
    $query = "UPDATE transaksi SET  id_user = :id_user, id_pelanggan = :id_pelanggan, tgl_bayar = :tgl_bayar, total_harga = :total_harga, berat_pakaian = :berat_pakaian, katalog = :katalog, status = :status, dibayar = :dibayar 
WHERE id_transaksi = :id_transaksi";
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

/**
 * Show output to client
 */
$reply['status'] = $isOk;
echo json_encode($reply);