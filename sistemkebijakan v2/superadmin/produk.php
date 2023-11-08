<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'superadmin_katalog') {
    $idtipe = $_GET['idtipe'];
    $result = mysqli_query($conn, "SELECT namatipe, idtipe FROM tipe WHERE idtipe = '$idtipe'");
    $data = mysqli_fetch_assoc($result);

    // Cek apakah data ditemukan
    if ($data) {
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
        <title>SIEKA | Daftar Produk E-Katalog - SuperAdmin</title>
        <link rel="icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .card {
                background-color: #DFE0E2;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                transition: transform 0.2s;
                margin-bottom: 20px;
                overflow: hidden;
                height: 200px;
                /* Ubah sesuai dengan kebutuhan Anda */
                width: 200px;
                /* Ubah sesuai dengan kebutuhan Anda */
                display: flex;
                flex-direction: column;
            }


            .card:hover {
                transform: scale(1.01);
            }

            .card-header img {
                width: 100%;
                /* Atur lebar gambar agar mengisi seluruh area card-header */
                height: auto;
                /* Biarkan tinggi gambar mengikuti proporsi aslinya */
                max-height: 60px;
                /* Atur tinggi maksimum gambar */
                object-fit: contain;
                /* Sesuaikan proporsi gambar */
            }

            .card-content {
                padding: 15px;
                text-align: center;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                overflow: auto;
            }

            .card-title {
                font-size: 1rem;
                /* Menyesuaikan ukuran font */
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
                /* Mengurangi jarak bawah */
            }

            .card-buttons {
                display: flex;
                justify-content: center;
                gap: 5px;
                /* Mengurangi jarak antara tombol */
            }

            .btn-edit,
            .btn-delete {
                font-size: 12px;
                /* Mengurangi ukuran font tombol */
            }

            .topButton {
                font-size: 14px;
                /* Mengurangi ukuran font tombol top */
            }

            .row {
                gap: 45px;
                margin-left: -20px;
                margin-right: -20px;
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
                        if (isset($namatipe) && isset($idtipe)) {
                            echo '<h1>
                            Daftar ' . $namatipe . ' E-Katalog 
                            <div class="btn-group" role="group" aria-label="Basic example" style="float: right; margin-right: 5px;">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                            </div>
                            </h1>';
                        } else {
                            echo '<h1> Daftar Produk E-Katalog </h1>';
                        }
                        ?>

                        <?php
                        if (isset($namatipe) && isset($idtipe)) {
                        ?>
                            <div class="navigation">
                                <a href="tipe.php">Kategori E-katalog</a>
                                <span style="font-size: 12px;"><i class="fas fa-chevron-right"></i></span>
                                <a href="produk.php?idtipe=<?= $idtipe; ?>"><?= $namatipe; ?></a>
                            </div><br>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Tambah Data Produk</button>
                            <br>
                        <?php } else { ?>
                            <div class="navigation">
                                <a href="tipe.php">Kategori E-katalog</a>
                                <span style="font-size: 12px;"><i class="fas fa-chevron-right"></i></span>
                                Tidak ada data
                            </div><br>
                        <?php  } ?>
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
                        $ambilsemuadatakatalog = mysqli_query($conn, "SELECT * FROM produk where idtipe = '$idtipe'");
                        if (mysqli_num_rows($ambilsemuadatakatalog) > 0) {
                        ?>
                            <div class="card-body">
                                <div class="row">
                                    <?php

                                    while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
                                        $namaproduk = $data['namaproduk'];
                                        $idproduk = $data['idproduk'];
                                        $foto = $data['foto'];
                                    ?>
                                        <div class="col-md-2 mb-2">
                                            <a href="katalog.php?idproduk=<?= $idproduk; ?>" class="card-link">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <img src="../assets/produk/<?= $foto; ?>" class="card-img-top">
                                                    </div>
                                                    <div class="card-content">
                                                        <h6 class="card-title"><?= $namaproduk; ?></h6>
                                                        <div class="card-buttons">
                                                            <a type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $idproduk; ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <a type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idproduk; ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- edit Modal -->
                                        <div class="modal fade" id="edit<?= $idproduk; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit produk Katalog</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                                                            <small class="text-muted">Nama Produk</small>
                                                            <input type="text" name="namaproduk" value="<?= $namaproduk; ?>" placeholder="produk" class="form-control mb-3" required>
                                                            <small class="text-muted">Foto Produk</small>
                                                            <input type="file" name="foto" accept=".jpg,.png,.jpeg," class="form-control">
                                                            <br>
                                                            <button type="submit" class="btn btn-warning" name="editproduk" style="width: 100%;">Edit</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>


                                        <!-- delete Modal -->
                                        <div class="modal fade" id="delete<?= $idproduk; ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus produk</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <form method="post" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            Apakah Anda Yakin ingin menghapus produk <?= $namaproduk ?>?
                                                            <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                                                            <br>
                                                            <br>
                                                            <button type="submit" class="btn btn-danger" name="hapusproduk" style="width: 100%;">Hapus</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo '<div style="text-align: center; opacity:0.8;">Tidak ada data</div>';
                                }
                                ?>

                                </div>
                            </div>

                            <div id="noDataMessage" class="text-center mt-3" style="display: none; opacity: 0.8;">
                                <p>Tidak ada data</p>
                            </div>
                            <button onclick="topFunction()" id="myBtn" title="Go to top" class="topButton"><i class="fas fa-arrow-up"></i></button>
                    </div>
                </main>
                <?php include('../footer.php'); ?>
            </div>
        </div>
        <?php include('../script.php') ?>
    </body>

    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data produk</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <small class="text-muted">Produk</small>
                        <input type="text" name="namaproduk" placeholder="Nama produk" class="form-control mb-3" required value="<?= $namaValue; ?>">

                        <input type="hidden" name="idtipe" value="<?= $idtipe; ?>">
                        <small class="text-muted">Foto Produk</small>
                        <input type="file" name="foto" accept=".jpg,.png,.jpeg," class="form-control" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="addnewproduk" style="width: 100%;">Kirim</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    </html>

<?php
} else {
    header("location:../loginkatalog.php");
    exit();
}
?>