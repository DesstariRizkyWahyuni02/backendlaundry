<?php
include_once '../koneksi.php';

/***
 * @var $connection PDO
 */

$id_transaksi = $_POST['id_transaksi'];
try {
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "Delete FROM transaksi WHERE `id_transaksi`= '$id_transaksi'";

    $connection->exec($sql);
    echo "Data berhasil di hapus";
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$connection = null;