<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'user_katalog') {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SIEKA | Kategori E-Katalog - User</title>
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
                height: 150px;
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

            #noDataMessage {
                display: flex;
                justify-content: center;
                align-items: center;
                position: absolute;
                top: 30%;
                left: 50%;
                text-align: center;
                opacity: 0.8;
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
                        <h1>Kategori E-Katalog
                            <div class="btn-group" style="float: right; margin-right: 5px;" role="group" aria-label="Basic example">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                            </div>
                        </h1>
                        <div class="navigation ">
                            <a href="tipe.php">Kategori E-Katalog</a>
                        </div>
                        <div class="card-body" id="cardContainer">
                            <div class="row">
                                <?php
                                $ambilsemuadatakatalog = mysqli_query($conn, "SELECT * FROM tipe");

                                while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
                                    $namatipe = $data['namatipe'];
                                    $idtipe = $data['idtipe'];
                                    $foto = $data['foto'];
                                ?>
                                    <div class="col-md-2 mb-2">
                                        <a href="produk.php?idtipe=<?= $idtipe; ?>" class="card-link">
                                            <div class="card">
                                                <div class="card-header">
                                                    <img src="../assets/kategori/<?= $foto; ?>" class="card-img-top">
                                                </div>
                                                <div class="card-content">
                                                    <h6 class="card-title"><?= $namatipe; ?></h6>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                                    <div id="noDataMessage" class="text-center mt-3" style="display: none;">
                                        <p>Tidak ada data</p>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        <button onclick="topFunction()" id="myBtn" title="Go to top" class="topButton"><i class="fas fa-arrow-up"></i></button>
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