<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'user') {
    // Konten halaman user
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Katalog Sarana Prasarana - User</title>
        <link rel="icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .deskripsi {
                color: black;
                opacity: 0.7;
                text-align: justify;
            }
        </style>
    </head>

    <body class="sb-nav-fixed">
        <?php include('../navbar.php'); ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php include('sidebar.php'); ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <?php
                        $id = $_GET['id'];
                        $ambilsemuadatakatalog = mysqli_query($conn, "SELECT * FROM katalog where id = $id");
                        while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {

                            $idtipe = $data['idtipe'];
                            $namabarang = $data['namabarang'];
                            $vendor = $data['vendor'];
                            $jumlah = $data['jumlah'];
                            $harga = number_format($data['harga'], 0, ',', '.');
                            $foto = $data['foto'];
                            $deskripsi = $data['deskripsi'];

                        ?>
                            <h1> Detail Katalog <?= $namabarang; ?> <a href="katalog.php?idtipe=<?= $idtipe; ?>" type="button" class="btn btn-info" style="float: right;"><i class="fas fa-arrow-left"></i></a></h1>
                            <?php
                            if (isset($_SESSION['notification'])) {
                                echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show ml-3 mt-3" role="alert" style="max-width: 500px;">
                            <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                                unset($_SESSION['notification']);
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card">
                                        <!-- Foto Barang -->
                                        <a href="../assets/img/<?= $foto; ?>" target="_blank"> <!-- Tambahkan tautan di sini -->
                                            <img src="../assets/img/<?= $foto; ?>" class="card-img-top" alt="<?= $data['foto']; ?>" style="max-width: 100%; height: auto;">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card">
                                        <!-- Informasi Barang -->
                                        <div class="card-body">
                                            <h3 class="card-title mb-4"><?= $namabarang; ?></h3>
                                            <p class="card-text mb-1">Vendor : <?= $vendor; ?></p>
                                            <p class="card-text mb-1">Harga : Rp. <?= $harga; ?></p>
                                            <p class="card-text">Jumlah : <?= $jumlah; ?></p>
                                            <div class="deskripsi">
                                                <p class="card-text"><?= $deskripsi; ?></p>
                                            </div>
                                            <br>
                                            <button class="btn btn-success" data-toggle="modal" data-target="#pesan<?= $id; ?>"><i class="fas fa-credit-card"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pesan Modal -->
                            <div class="modal fade" id="pesan<?= $id; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Pesan Data Barang</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <form method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                Apakah Anda Yakin ingin Pesan <?= $namabarang ?>?
                                                <input type="hidden" name="id" value="<?= $id; ?>">
                                                <input type="hidden" name="idtipe" value="<?= $idtipe; ?>">
                                                <input type="hidden" name="namabarang" value="<?= $namabarang; ?>">
                                                <input type="hidden" name="vendor" value="<?= $vendor; ?>">
                                                <br>
                                                <br>
                                                <input type="number" name="jumlah" placeholder="Jumlah Barang" class="form-control" required>
                                                <br>
                                                <button type="submit" class="btn btn-success" name="pesandatabarang" style="width: 100%;">Pesan</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                    </div>
                <?php }; ?>
                </main>
                <?php include('../footer.php'); ?>
            </div>
        </div>
        <?php include('../script.php') ?>
    </body>



    </html>

<?php
} else {
    header("location:../index.php");
    exit();
}
?>