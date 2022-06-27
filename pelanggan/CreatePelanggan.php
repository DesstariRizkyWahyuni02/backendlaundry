<?php
include_once '../koneksi.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
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

$isValidated = true;

if(empty($id_pelanggan)){
    $reply['error'] = 'id_pelanggan harus diisi';
    $isValidated = false;
}

if(empty($nama)){
    $reply['error'] = 'nama harus diisi';
    $isValidated = false;
}if(empty($no_tlp)){
    $reply['error'] = 'total harga harus diisi';
    $isValidated = false;
}
if(empty($jenis_kelamin)){
    $reply['error'] = 'berat pakaian harus diisi';
    $isValidated = false;
}
if(empty($alamat)){
    $reply['error'] = 'alamat harus diisi';
    $isValidated = false;
}
if(empty($kelurahan)){
    $reply['error'] = 'keluarahan harus diisi';
    $isValidated = false;
}

try{
    $query = "INSERT INTO pelanggan (id_pelanggan, nama, no_tlp, jenis_kelamin, alamat, kelurahan) 
VALUES (:id_pelanggan, :nama, :no_tlp, :jenis_kelamin, :alamat, :kelurahan)";
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

/*
 * Get last data
 */
$getResult = "SELECT * FROM pelanggan WHERE id_pelanggan = :id_pelanggan";
$stm = $connection->prepare($getResult);
$stm->bindValue(':id_pelanggan', $id_pelanggan);
$stm->execute();
$result = $stm->fetch(PDO::FETCH_ASSOC);

/**
 * Show output to client
 * Set status info true
 */
$reply['pelanggan'] = $result;
$reply['status'] = $isOk;
echo json_encode($reply);