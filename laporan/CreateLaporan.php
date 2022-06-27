<?php
include_once '../koneksi.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}

$id_laporan = $_POST['id_laporan'] ?? '';
$id_transaksi = $_POST['id_transaksi'] ?? '';
$total_pendapatan = $_POST['total_pendapatan'] ?? '';
$jumlah_transaksi = $_POST['jumlah_transaksi'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';

$isValidated = true;

if(empty($id_laporan)){
    $reply['error'] = 'id_laporan harus diisi';
    $isValidated = false;
}
if(empty($id_transaksi)){
    $reply['error'] = 'id_transaksi harus diisi';
    $isValidated = false;
}
if(empty($total_pendapatan)){
    $reply['error'] = 'total_pendapatan harus diisi';
    $isValidated = false;
}if(empty($jumlah_transaksi)){
    $reply['error'] = 'Jumlah Transaksi harus diisi';
    $isValidated = false;
}



try{
    $query = "INSERT INTO laporan (id_laporan, id_transaksi,total_pendapatan, jumlah_transaksi, keterangan) 
VALUES (:id_laporan, :id_transaksi,:total_pendapatan, :jumlah_transaksi, :keterangan)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    
    
    $statement->bindValue(":id_laporan", $id_laporan);
    $statement->bindValue(":id_transaksi", $id_transaksi);
    $statement->bindValue(":total_pendapatan", $total_pendapatan);
    $statement->bindValue(":jumlah_transaksi", $jumlah_transaksi);
    $statement->bindValue(":keterangan", $keterangan);
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
$getResult = "SELECT * FROM laporan WHERE id_laporan = :id_laporan";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_laporan', $id_laporan);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

/**
 * Show output to client
 * Set status info true
 */
$reply['laporan'] = $result;
$reply['status'] = $isOk;
echo json_encode($reply);