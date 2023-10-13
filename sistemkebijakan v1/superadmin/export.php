<?php

require_once __DIR__ . '/../vendor/autoload.php';
require "../function.php";
$username = $_GET['username'];
$ambilsemuadatakatalog = mysqli_query($conn, "SELECT id,namabarang, foto,vendor,harga_awal,SUM(harga) as total_harga, SUM(jumlah) as total_jumlah FROM pesanan WHERE username = '$username' GROUP BY namabarang, foto");

$mpdf = new \Mpdf\Mpdf();

$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/img/siskalogo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../assets/img/siskalogo.png" type="image/x-icon">
    <title>Invoice</title>
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

        .thank-you {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="invoice-header">
            <div class="invoice-title">Invoice</div>
            <br>
            <div>Tanggal : ' . date('d/m/Y') . '</div>
            <div>Nomor Faktur: INV12345</div>
        </div>
        <div class="invoice-details">
            <div>Nama Pemesan: ' . $username . '</div>
            <div>Alamat Pengiriman: [ALAMAT PENGIRIMAN]</div>
            <div>Nomor Telepon: [NOMOR TELEPON]</div>
        </div>
    </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Nama Vendor</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>';

$i = 1;
while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
    $namabarang = $data['namabarang'];
    $vendor = $data['vendor'];
    $totalJumlah = $data['total_jumlah'];
    $harga_awal = number_format($data['harga_awal'], 0, ',', '.');
    $totalHarga = number_format($data['total_harga'], 0, ',', '.');

    $html .= "<tr>
                <td>{$i}</td>
                <td>{$namabarang}</td>
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

$result = mysqli_query($conn, "SELECT SUM(harga) as total FROM pesanan WHERE username = '$username'");
$row = mysqli_fetch_assoc($result);
$totalHarga = number_format($row['total'], 0, ',', '.');

$html .=        'Rp. ' . $totalHarga . '
        </div>
        <br>
        <div class="thank-you">
            Terimakasih atas pesanan Anda!
        </div>
    </div>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output("invoice_{$username}.pdf", 'I');
