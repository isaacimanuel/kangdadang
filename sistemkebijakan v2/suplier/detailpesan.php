<?php
require "../function.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'suplier') {
    $iduser = $_GET['iduser'];
    $idvendor = $_SESSION['id'];

?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SIEKA | Detail Pesanan - Vendor</title>
        <link rel="icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .navigation {
                font-size: 16px;
            }

            .navigation a {
                text-decoration: none;
                color: #333;
                margin-right: 5px;
            }

            .navigation span {
                margin-right: 5px;
            }

            .navigation a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body class="sb-nav-fixed">
        <?php include('../navbarkatalog.php'); ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include('sidebarkatalog.php'); ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1>Detail Pesanan</h1>
                        <div class="navigation">
                            <a href="userdetailpesan.php">Daftar Pesanan</a>
                            <span style="font-size: 12px;"><i class="fas fa-chevron-right"></i></span>
                            <a href="detailpesan.php?iduser=<?= $iduser; ?>">Detail Pesanan</a>
                        </div>

                        <?php
                        if (isset($_SESSION['notification'])) {
                            echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show mt-3" role="alert" style="max-width: 100%;">
                            <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                            unset($_SESSION['notification']);
                        }
                        ?>
                        <div class=" card mb-4 mt-3">
                            <div class="card-header">
                                <button data-toggle="modal" data-target="#konfirmasi<?= $iduser; ?>" type="button" class="btn btn-info">
                                    Konfirmasi Pesanan
                                </button>
                            </div>
                            <!-- konfirmasi pesanan Modal -->
                            <div class="modal fade" id="konfirmasi<?= $iduser; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Konfirmasi Pesanan</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                Konfirmasi Pembelian
                                                <br>
                                                <input type="hidden" name="idvendor" value="<?= $idvendor; ?>">
                                                <input type="hidden" name="iduser" value="<?= $iduser; ?>">
                                                <br>
                                                <select name="kode_pesan" class="form-control" required>
                                                    <option value="">Pilih Kode Pesan</option>
                                                    <?php
                                                    $result = mysqli_query($conn, "SELECT DISTINCT kode_pesan FROM detailpesan WHERE iduser = '$iduser' AND idvendor = '$idvendor' AND status = 0");
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                        $kode_pesan = $row['kode_pesan'];
                                                        echo "<option value='$kode_pesan'>$kode_pesan</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <br>
                                                <button type="submit" class="btn btn-info" name="konfirmasipesan" style="width: 100%;">Konfirmasi Pesanan</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                            <?php
                            $result = mysqli_query($conn, "SELECT DISTINCT kode_pesan FROM detailpesan WHERE iduser = '$iduser'");
                            $dataExists = false;
                            while ($row = mysqli_fetch_assoc($result)) {
                                $kode_pesan = $row['kode_pesan'];
                                $ambilsemuadatakatalog = mysqli_query($conn, "SELECT * FROM detailpesan WHERE iduser = '$iduser' AND kode_pesan = '$kode_pesan' AND idvendor = '$idvendor'");
                                if (mysqli_num_rows($ambilsemuadatakatalog) > 0) {
                                    $dataExists = true;
                            ?>


                                    <div class="card-body">

                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Kode Pesan</th>
                                                        <th>Nama Barang</th>
                                                        <th>Merk</th>
                                                        <th>Nama Vendor</th>
                                                        <th>Tanggal</th>
                                                        <th>jumlah</th>
                                                        <th>Harga Awal</th>
                                                        <th>Harga</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php

                                                    $i = 1;
                                                    while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
                                                        $id = $data['id'];
                                                        $namabarang = $data['namabarang'];
                                                        $merk = $data['merk'];
                                                        $vendor = $data['vendor'];
                                                        $totalJumlah = $data['jumlah'];
                                                        $harga_awal = number_format($data['harga_awal'], 0, ',', '.');
                                                        $totalHarga = number_format($data['harga'], 0, ',', '.'); // Gunakan 'harga' bukan 'total_harga'
                                                        $idbarang = $data['idbarang'];
                                                        $idvendor = $data['idvendor'];
                                                        $tanggal = $data['tanggal_pesan'];
                                                        $kode_pesan = $data['kode_pesan'];
                                                        $status = $data['status'];
                                                    ?>

                                                        <tr>
                                                            <td><?= $i++; ?></td>
                                                            <td><?= $kode_pesan; ?></td>
                                                            <td><a href="detailcard.php?id=<?= $idbarang; ?>"><?= $namabarang; ?></a></td>
                                                            <td><?= $merk; ?></td>
                                                            <td><a href="profilvendor.php?id=<?= $idvendor; ?>"><?= $vendor; ?></a></td> <!-- Perlu tambahkan tag penutup <a> -->
                                                            <td><?= $tanggal; ?></td>
                                                            <td><?= $totalJumlah; ?></td>
                                                            <td>Rp.<?= $harga_awal; ?></td>
                                                            <td>Rp.<?= $totalHarga; ?></td>
                                                            <td>
                                                                <?php if ($status == 0) { ?>
                                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $id; ?>">
                                                                        <i class="fas fa-ban"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-info" disabled><i class="fas fa-clock"></i></button>

                                                                <?php } else if ($status == 1) { ?>

                                                                    <button type="button" class="btn btn-info" disabled><i class="fas fa-check"></i></button>

                                                                <?php } else if ($status == 3) { ?>
                                                                    <button type="button" class="btn btn-info" disabled><i class="fas fa-clock"></i></button>
                                                                <?php } ?>
                                                            </td>
                                                            <!-- Batal Modal -->
                                                            <div class="modal fade" id="delete<?= $id; ?>">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">

                                                                        <!-- Modal Header -->
                                                                        <div class="modal-header">
                                                                            <h4 class="modal-title">Batalkan Pesanan</h4>
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                        </div>

                                                                        <!-- Modal body -->
                                                                        <form method="post" enctype="multipart/form-data">
                                                                            <div class="modal-body">
                                                                                Apakah Anda Yakin ingin membatalkan pesanan <?= $namabarang ?>?
                                                                                <input type="hidden" name="id" value="<?= $id; ?>">
                                                                                <br>
                                                                                <textarea name="deskripsi" class="form-control mt-3" rows="3" placeholder="Deskripsi Pembatalan"></textarea>
                                                                                <br>
                                                                                <button type="submit" class="btn btn-danger" name="batalpesanvendor" style="width: 100%;">Batalkan Pesanan</button>
                                                                            </div>
                                                                        </form>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php
                                                    }
                                                        ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="7"></th>
                                                        <th>Total :</th>
                                                        <th colspan="5" class="text-right">
                                                            <?php
                                                            $resultTotal = mysqli_query($conn, "SELECT SUM(harga) as total FROM detailpesan WHERE kode_pesan = '$kode_pesan' AND idvendor = '$idvendor'");
                                                            $rowTotal = mysqli_fetch_assoc($resultTotal);
                                                            $totalHarga = number_format($rowTotal['total'], 0, ',', '.');
                                                            echo "Rp." . $totalHarga;
                                                            ?>
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            if (!$dataExists) {
                                echo '<div style="text-align: center; opacity:0.8;">Tidak ada detail pesan</div>';
                            }
                            ?>
                            <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
                        </div>
                </main>
                <?php include('../footer.php'); ?>
            </div>
        </div>
        <?php include('../script.php') ?>
    </body>

    </html>

<?php
} else {
    header("location:../loginkatalog.php");
    exit();
}
?>