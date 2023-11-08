<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'superadmin_katalog') {
    // Konten halaman admin
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>SIEKA | Beranda - Superadmin</title>
        <link rel="icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siekalogo.png" type="image/x-icon">

        <!-- Stylesheets -->
        <link href="../css/styles.css" rel="stylesheet">
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">

        <!-- FontAwesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .card:hover {
                transform: scale(1.01);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .card {
                font-size: 14px;
                background-color: whitesmoke;
                display: flex;
            }

            .card-title {
                font-size: 1rem;
                margin-bottom: 0;
                color: black;
                text-decoration-line: none;
            }

            .card-img-top {
                object-fit: contain;
                height: 70%;
                width: auto;
            }

            .card-body {
                padding: 10px;
            }
        </style>
    </head>

    <body class="sb-nav-fixed">
        <?php include('../navbarkatalog.php'); ?>

        <div id="layoutSidenav">
            <!-- Sidebar Navigation -->
            <div id="layoutSidenav_nav">
                <?php include('sidebarkatalog.php'); ?>
            </div>

            <div id="layoutSidenav_content">
                <main>
                    <!-- Carousel -->
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block mw-100 " src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?q=80&w=2020&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="First slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Laptop</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block mw-100" src="https://images.unsplash.com/photo-1584628804763-8233dfc0996c?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Alat Tulis</h5>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block mw-100" src="https://images.unsplash.com/photo-1622126807280-9b5b32b28e77?q=80&w=2060&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" style="opacity: 0.9;" alt="second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Kursi Kantor</h5>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <!-- Tentang SISKA -->
                    <div class="container mt-5">
                        <div class="row">
                            <div class="col-lg-8 mx-auto text-center">
                                <h2 class="section-heading">Tentang SIEKA</h2>
                                <br>
                                <p class="text-muted mb-5">
                                    SIEKA adalah Sistem Informasi E-Katalog yang dirancang untuk memudahkan Manajemen Pemesanan E-katalog di Universitas Kristen Satya Wacana.
                                    Dengan SIEKA, Anda dapat dengan mudah menelusuri dan melihat berbagai daftar produk E-katalog.
                                </p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-check-circle fa-3x mb-2 text-muted"></i>
                                            <p class="text-muted">Efisien</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-cogs fa-3x mb-2 text-muted"></i>
                                            <p class="text-muted">Mudah Dikelola</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <i class="fas fa-users fa-3x mb-2 text-muted"></i>
                                            <p class="text-muted">Akses Mudah</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-center mt-5">Kategori E-Katalog</h3>
                    <br>
                    <div class="d-flex justify-content-center">
                        <?php
                        $ambilsemuadata = mysqli_query($conn, "SELECT * FROM tipe limit 6");

                        while ($data = mysqli_fetch_array($ambilsemuadata)) {
                            $idtipe = $data['idtipe'];
                            $namatipe = $data['namatipe'];
                            $foto = $data['foto'];
                        ?>
                            <a href="produk.php?idtipe=<?= $idtipe; ?>">
                                <div class="card ml-4 mt-3 mb-3" style="width: 10rem;height: 10rem;box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
                                    <img src="../assets/kategori/<?= $foto; ?>" class="card-img-top" alt="...">

                                    <div class="card-body text-center">
                                        <p style="color: black;text-decoration-line:none;"><?= $namatipe; ?></p>
                                    </div>
                                </div>
                            </a>


                        <?php
                        }
                        ?>
                    </div>
                    <button onclick=" topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
                </main>
                <br><br>
                <?php include('../footer.php'); ?>
            </div>
        </div>

        <?php include('../script.php') ?>
    </body>

    </html>
<?php
} else {
    header("location:../loginkebijakan.php");
    exit();
}
?>