<?php
include_once '../koneksi.php';
header("Content-Type: application/json; charset=UTF-8");

try{

    $statement = $connection->prepare("select * from user limit 50");
    $isOk = $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $reply['transaksi'] = $results;
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

if ($isOk) {
   $reply['status']='succcess';
   $reply['message']='User ditemukan';
   $reply['data']=$isOk;
}
else {
   $reply['status']='failed';
   $reply['message']='User tidak ditemukan';
   $reply['data']=$isOk;

}
/*
 * Query OK
 * set status == true
 * Output JSON
 */
$reply['status'] = true;
echo json_encode($reply);
