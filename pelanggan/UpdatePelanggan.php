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

$id_pelanggan = $_POST['id_pelanggan'] ?? '';
$nama = $_POST['nama'] ?? '';
$no_tlp = $_POST['no_tlp'] ?? '';
$jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
$alamat = $_POST['alamat'] ?? '';
$kelurahan = $_POST['kelurahan'] ?? '';




/**
 * Validation empty fields
 */
$isValidated = true;
if(empty($id_pelanggan)){
    $reply['error'] = 'id_pelanggan harus diisi';
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
    $queryCheck = "SELECT * FROM pelanggan where id_pelanggan = :id_pelanggan";
    $statement = $connection->prepare($queryCheck);
    $statement->bindValue(':id_pelanggan', $id_pelanggan);
    $statement->execute();
    $row = $statement->rowCount();
    /**
     * Jika data tidak ditemukan
     * rowcount == 0
     */
    if($row === 0){
        $reply['error'] = 'Data tidak ditemukan id_pelanggan '.$id_pelanggan;
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
    $query = "UPDATE pelanggan SET nama = :nama, no_tlp = :no_tlp, jenis_kelamin = :jenis_kelamin, alamat = :alamat, kelurahan = :kelurahan
WHERE id_pelanggan = :id_pelanggan";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":id_pelanggan", $id_pelanggan);
    $statement->bindValue(":nama", $nama);
    $statement->bindValue(":no_tlp", $no_tlp);
    $statement->bindValue(":jenis_kelamin", $jenis_kelamin);
    $statement->bindValue(":alamat", $alamat);
    $statement->bindValue(":kelurahan", $kelurahan);
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