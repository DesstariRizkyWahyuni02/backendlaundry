<?php
include_once '../koneksi.php';
try{

    $statement = $connection->prepare("select * from pelanggan limit 50");
    $isOk = $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    $reply['Pelanggan'] = $results;
}catch (Exception $exception){
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}

if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}

/*
 * Query OK
 * set status == true
 * Output JSON
 */
$reply['status'] = true;
echo json_encode($reply);
// public function auto(){ 
//      $query=$this->db->prepare("SELECT MAX(id_pelanggan) as terakhir from pelanggan");   $query->execute();  
//       $hasil=$query->get_result();
//        $data=$hasil->fetch_assoc(); 
//      $lastNoUrut=$data['terakhir'];
//       $nextID=$lastNoUrut + 1;
//       return $nextID; 
//     } 
//       public function autoinvoice(){
//         $query = $this->db->prepare("SELECT MAX(id_pelanggan) as terakhir from pelanggan");   
//         $query->execute();
//         $hasil =$query->get_result();
//          $data = $hasil->fetch_assoc();
//          $lastID = $data['terakhir'];
//          $lastNoUrut = substr($lastID,3); 
//          $nextNoUrut = $lastNoUrut +1; 
//          $nextID = "KD".sprintf("%03s", $nextNoUrut); 
//          return $nextID; 
//         }