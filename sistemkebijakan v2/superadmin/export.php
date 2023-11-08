<?php
require_once __DIR__ . '/../vendor/autoload.php';
require "../function.php";

$kode_pesan = $_GET['kode_pesan'];
$ambilsemuadatakatalog = mysqli_query($conn, "SELECT r.*, u.*
    FROM riwayat r 
    JOIN user_katalog u ON r.iduser = u.id
    WHERE kode_pesan = '$kode_pesan'");

$mpdf = new \Mpdf\Mpdf();

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/siskalogo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/img/siskalogo.png" type="image/x-icon">
    <title>Catatan Pesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }

        .invoice {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: 0 auto;
            max-width: 800px;
            position: relative;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #333; /* Warna judul */
            text-align: center;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details div {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            background-color: #f7f7f7;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
            text-align: right;
        }

        .total span {
            display: inline-block;
            width: 150px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="invoice-header">
            <div class="invoice-title">Catatan Pesanan</div><br>
            <div>Kode Pesanan: ' . $kode_pesan . '</div>
        </div>
        
        <div class="invoice-details">';
// Inisialisasi variabel nama
$nama = '';
$email = '';
while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
    $nama = $data['nama'];
    $email = $data['email'];
    break;
}

$html .= '<div>Nama Pemesan : ' . $nama . '</div>';
$html .= '<div>Email        : ' . $email . '</div>';

mysqli_data_seek($ambilsemuadatakatalog, 0); // Reset kursor data
// Inisialisasi tanggal
$tanggal = '';
while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
    $tanggal = $data['tanggal_pesan'];
    break;
}

$html .= '<div>Tanggal Pesan : ' . $tanggal . '</div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Nama Vendor</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>';

$i = 1;
mysqli_data_seek($ambilsemuadatakatalog, 0); // Reset kursor data
while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
    $namabarang = $data['namabarang'];
    $merk = $data['merk'];
    $vendor = $data['vendor'];
    $totalJumlah = $data['jumlah'];
    $harga_awal = number_format($data['harga_awal'], 0, ',', '.');
    $totalHarga = number_format($data['harga'], 0, ',', '.');

    $html .= "<tr>
                <td>{$i}</td>
                <td>{$namabarang}</td>
                <td>{$merk}</td>
                <td>{$vendor}</td>
                <td>{$totalJumlah}</td>
                <td>Rp.{$harga_awal}</td>
                <td>Rp.{$totalHarga}</td>
            </tr>";

    $i++;
}

$html .= '</tbody>
    </table>
</div>
<div class="total">
    <span>Total Harga :</span> ';

$result = mysqli_query($conn, "SELECT SUM(harga) as total FROM riwayat WHERE kode_pesan = '$kode_pesan'");
$row = mysqli_fetch_assoc($result);
$totalHarga = number_format($row['total'], 0, ',', '.');

$html .= 'Rp. ' . $totalHarga . '
    </div>
    <br>
</div>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output("CatatanPembayaran_{$kode_pesan}.pdf", 'I');
