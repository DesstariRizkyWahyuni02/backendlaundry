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
$id_laporan = $_POST['id_laporan'] ?? '';
$id_transaksi = $_POST['id_transaksi'] ?? '';
$total_pendapatan = $_POST['total_pendapatan'] ?? '';
$jumlah_transaksi = $_POST['jumlah_transaksi'] ?? '';
$keterangan = $_POST['keterangan'] ?? '';



/**
 * Validation empty fields
 */
$isValidated = true;
if(empty($id_laporan)){
    $reply['error'] = 'id laporan harus diisi';
    $isValidated = false;
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
    $queryCheck = "SELECT * FROM laporan where id_laporan = :id_laporan";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_laporan', $id_laporan);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'Data tidak ditemukan id_laporan '.$id_laporan;
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
    $query = "UPDATE laporan SET id_transaksi = :id_transaksi,total_pendapatan = :total_pendapatan, jumlah_transaksi = :jumlah_transaksi, keterangan = :keterangan
WHERE id_laporan = :id_laporan";
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

/**
 * Show output to client
 */
$reply['status'] = $isOk;
echo json_encode($reply);