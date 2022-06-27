<?php
include_once '../koneksi.php';

/***
 * @var $connection PDO
 */

$id_user = $_POST['id_user'];
try {
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "Delete FROM user WHERE `id_user`= '$id_user'";

    $connection->exec($sql);
    echo "Data berhasil di hapus";
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$connection = null;