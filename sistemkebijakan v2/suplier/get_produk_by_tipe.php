<?php
require_once '../function.php';

if (isset($_GET['idtipe'])) {
    $idtipe = $_GET['idtipe'];

    $query = "SELECT * FROM produk WHERE idtipe = '$idtipe'";
    $result = mysqli_query($conn, $query);

    $produk = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $produk[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($produk);
}
