<?php
include_once '../koneksi.php';

/***
 * @var $connection PDO
 */

$id_pelanggan = $_POST['id_pelanggan'];
try {
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "Delete FROM pelanggan WHERE `id_pelanggan`= '$id_pelanggan'";

    $connection->exec($sql);
    echo "Data berhasil di hapus";
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$connection = null;