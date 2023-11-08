<?php
require "../function.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'suplier') {
    $idbarang = $_GET['id'];
    // Dapatkan nama produk dari database
    $result = mysqli_query($conn, "SELECT namabarang, idproduk,idvendor FROM katalog WHERE id = '$idbarang'");
    $data = mysqli_fetch_assoc($result);

    // Cek apakah data ditemukan
    if ($data) {
        $idproduk = $data['idproduk'];
        $namabarang = $data['namabarang'];
        $idvendor = $data['idvendor'];
    } else {
        $namabarang = "produk tidak ditemukan";
        $idproduk = "produk tidak ditemukan"; // Jika produk tidak ditemukan
    }

    // Dapatkan nama produk dari database
    $result = mysqli_query($conn, "SELECT namaproduk, idtipe FROM produk WHERE idproduk = '$idproduk'");
    $data = mysqli_fetch_assoc($result);

    // Cek apakah data ditemukan
    if ($data) {
        $idtipe = $data['idtipe'];
        $namaproduk = $data['namaproduk'];
    } else {
        $namaproduk = "produk tidak ditemukan";
        $idtipe = "produk tidak ditemukan"; // Jika produk tidak ditemukan
    }

    $result = mysqli_query(
        $conn,
        "SELECT namatipe, idtipe FROM tipe WHERE idtipe = '$idtipe'"
    );
    $data = mysqli_fetch_assoc($result);

    // Cek apakah data ditemukan
    if ($data) {
        $idtipe = $data['idtipe'];
        $namatipe = $data['namatipe'];
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
        <title>SIEKA | Detail E-Katalog - Vendor</title>
        <link rel="icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siekalogo.png" type="image/x-icon">
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
                        <?php
                        $ambilsemuadatakatalog = mysqli_query($conn, "SELECT * FROM katalog where id = $idbarang");
                        if (mysqli_num_rows($ambilsemuadatakatalog) > 0) {
                            while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {

                                $idproduk = $data['idproduk'];
                                $namabarang = $data['namabarang'];
                                $vendor = $data['vendor'];
                                $jumlah = $data['jumlah'];
                                $harga = $data['harga'];
                                $foto = $data['foto'];
                                $deskripsi = $data['deskripsi'];
                                $merk = $data['merk'];
                                $idvendor = $data['idvendor'];

                        ?>

                                <h1> Detail E-Katalog <?= $namabarang; ?></h1>
                                <div class="navigation">
                                    <a href="tipe.php">Kategori E-katalog</a>
                                    <span style="font-size: 12px;"><i class="fas fa-chevron-right"></i></span>
                                    <a href="produk.php?idtipe=<?= $idtipe; ?>"><?= $namatipe; ?></a>
                                    <span style="font-size: 12px;"><i class="fas fa-chevron-right"></i></span>
                                    <a href="katalog.php?idproduk=<?= $idproduk; ?>"><?= $namaproduk; ?></a>
                                    <span style="font-size: 12px;"><i class="fas fa-chevron-right"></i></span>
                                    <a href="detailcard.php?id=<?= $idbarang; ?>"><?= $merk; ?></a>
                                </div>

                                <?php
                                if (isset($_SESSION['notification'])) {
                                    echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show ml-3 mt-3" role="alert" style="max-width: 100%;">
                            <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                                    unset($_SESSION['notification']);
                                }
                                ?>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <!-- Foto Barang -->
                                            <a href="../assets/katalog/<?= $foto; ?>" target="_blank"> <!-- Tambahkan tautan di sini -->
                                                <img src="../assets/katalog/<?= $foto; ?>" class="card-img-top" alt="<?= $data['foto']; ?>" style="max-width: 100%; height: auto;">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card">
                                            <!-- Informasi Barang -->
                                            <div class="card-body">
                                                <h3 class="card-title mb-4"><?= $namabarang; ?></h3>
                                                <p class="card-text mb-1"><?= $merk; ?></p>
                                                <p class="card-text mb-1">
                                                    <a href="profilvendor.php?id=<?= $idvendor; ?>">
                                                        <?= $vendor; ?>
                                                    </a>
                                                </p>
                                                <p class="card-text mb-1">Rp. <?= number_format($data['harga'], 0, ',', '.');; ?></p>
                                                <p class="card-text">Stok : <?= $jumlah; ?></p>
                                                <div class="deskripsi">
                                                    <p class="card-text"><?= $deskripsi; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php };
                        } else {
                            echo '<div style="text-align: center; opacity:0.8;">Tidak ada data</div>';
                        } ?>
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