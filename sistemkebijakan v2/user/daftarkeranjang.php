<?php
require "../function.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'user_katalog') {
    // Konten halaman user
    $username = $_SESSION['username'];

    $result = mysqli_query($conn, "SELECT id FROM user_katalog WHERE username = '$username'");

    // Periksa apakah kueri berhasil dieksekusi
    if ($result) {
        // Ambil ID dari hasil kueri
        $row = mysqli_fetch_assoc($result);
        $iduser = $row['id'];
    }
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SIEKA | Keranjang Pesanan - User</title>
        <link rel="icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siskalogo.png" type="image/x-icon">
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
                        <h1>Keranjang Pesanan</h1>
                        <?php
                        if (isset($_SESSION['notification'])) {
                            echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show mt-3" role="alert" style="max-width: 100%;">
                            <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                            unset($_SESSION['notification']);
                        } ?>
                        <div class=" card mb-4 mt-3">
                            <div class="card-header">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#pesan<?= $username; ?>">
                                    Pesan Keranjang
                                </button>
                            </div>
                            <div class="card-body">
                                <?php
                                $ambilsemuadatakatalog = mysqli_query($conn, "SELECT id,idvendor,idbarang,namabarang,merk, foto,vendor,harga_awal,SUM(harga) as total_harga, SUM(jumlah) as total_jumlah FROM keranjang WHERE iduser = '$iduser' GROUP BY namabarang, foto");
                                $i = 1;
                                if (mysqli_num_rows($ambilsemuadatakatalog) > 0) { ?>

                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>Merk</th>
                                                    <th>Nama Vendor</th>
                                                    <th>jumlah</th>
                                                    <th>Harga Awal</th>
                                                    <th>Harga</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php

                                                while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
                                                    $id = $data['id'];
                                                    $namabarang = $data['namabarang'];
                                                    $foto = $data['foto'];
                                                    $vendor = $data['vendor'];
                                                    $harga_awal = number_format($data['harga_awal'], 0, ',', '.');
                                                    $totalHarga = number_format($data['total_harga'], 0, ',', '.');
                                                    $totalJumlah = $data['total_jumlah'];
                                                    $idbarang = $data['idbarang'];
                                                    $idvendor = $data['idvendor'];
                                                    $merk = $data['merk'];
                                                ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><a href="detailcard.php?id=<?= $idbarang; ?>"><?= $namabarang; ?></a></td>
                                                        <td><?= $merk; ?></td>
                                                        <td><a href="profilvendor.php?id=<?= $idvendor; ?>"><?= $vendor; ?></td>
                                                        <td><?= $totalJumlah; ?></td>
                                                        <td>Rp.<?= $harga_awal; ?></td>
                                                        <td>Rp.<?= $totalHarga; ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editpesan<?= $id; ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $id; ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <!-- Edit Pesanan Modal -->
                                                    <div class="modal fade" id="editpesan<?= $id; ?>">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Edit Pesanan Barang</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button> <!-- Tombol close ini berfungsi untuk menutup modal. -->
                                                                </div>
                                                                <!-- Modal body -->
                                                                <form method="post" enctype="multipart/form-data">
                                                                    <div class="modal-body">
                                                                        Edit Pesanan <?= $namabarang; ?>
                                                                        <br>
                                                                        <br>
                                                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                                                        <input type="hidden" name="namabarang" value="<?= $namabarang; ?>">
                                                                        <input type="number" name="jumlah" placeholder="Jumlah Barang" value="<?= $totalJumlah; ?>" class="form-control" required>
                                                                        <br>
                                                                        <button type="submit" class="btn btn-warning" name="editpesan" style="width: 100%;">Edit Pesanan</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- delete Modal -->
                                                    <div class="modal fade" id="delete<?= $id; ?>">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">

                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Hapus Data Barang</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>

                                                                <!-- Modal body -->
                                                                <form method="post" enctype="multipart/form-data">
                                                                    <div class="modal-body">
                                                                        Apakah Anda Yakin ingin menghapus pesanan <?= $namabarang ?>?
                                                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                                                        <input type="hidden" name="idtipe" value="<?= $idtipe; ?>">
                                                                        <br>
                                                                        <br>
                                                                        <button type="submit" class="btn btn-danger" name="hapuspesan" style="width: 100%;">Hapus</button>
                                                                    </div>
                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Pesan Modal -->
                                                    <div class="modal fade" id="pesan<?= $username; ?>">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">

                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Pesan Barang</h4>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>

                                                                <!-- Modal body -->
                                                                <form method="post" enctype="multipart/form-data">
                                                                    <div class="modal-body">
                                                                        Apakah Anda Yakin ingin pesan?
                                                                        <input type="hidden" name="iduser" value="<?= $iduser; ?>">
                                                                        <input type="hidden" name="namabarang" value="<?= $namabarang; ?>">
                                                                        <input type="hidden" name="merk" value="<?= $merk; ?>">
                                                                        <input type="hidden" name="idbarang" value="<?= $idbarang; ?>">
                                                                        <input type="hidden" name="iduser" value="<?= $iduser; ?>">
                                                                        <input type="hidden" name="idvendor" value="<?= $idvendor; ?>">
                                                                        <input type="hidden" name="vendor" value="<?= $vendor; ?>">
                                                                        <input type="hidden" name="harga_awal" value="<?= $harga_awal; ?>">
                                                                        <input type="hidden" name="harga" value="<?= $totalHarga; ?>">
                                                                        <input type="hidden" name="jumlah" value="<?= $totalJumlah; ?>">
                                                                        <br>
                                                                        <br>
                                                                        <button type="submit" class="btn btn-success" name="pesan" style="width: 100%;">Pesan</button>
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
                                                    <th colspan="5"></th>
                                                    <th>Total Harga :</th>
                                                    <th colspan="3" class="text-right">
                                                        <?php
                                                        $result = mysqli_query($conn, "SELECT SUM(harga) as total FROM keranjang WHERE iduser = '$iduser'");
                                                        $row = mysqli_fetch_assoc($result);
                                                        $totalHarga = number_format($row['total'], 0, ',', '.');
                                                        echo "Rp." . $totalHarga;
                                                        ?>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        <?php } else {
                                        echo '<div style="text-align: center; opacity:0.8;">Tidak ada data, Silahkan Menuju E-Katalog untuk memilih barang</div>';
                                    } ?>
                                        </table>
                                    </div>
                            </div>
                        </div>
                        <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
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