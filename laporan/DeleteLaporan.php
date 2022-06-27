<?php
require_once '../koneksi.php';

/***
 * @var $connection PDO
 */

$id_laporan = $_POST['id_laporan'];
try {
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "Delete FROM laporan WHERE `id_laporan`= '$id_laporan'";

    $connection->exec($sql);
    echo "Data berhasil di hapus";
} catch(PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$connection = null;