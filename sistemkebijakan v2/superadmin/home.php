<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'superadmin') {
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
        <title>SISKA | Beranda - Superadmin</title>
        <link rel="icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siskalogo.png" type="image/x-icon">

        <!-- Stylesheets -->
        <link href="../css/styles.css" rel="stylesheet">
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">

        <!-- FontAwesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .card:hover {
                transform: scale(1.01);
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>

    <body class="sb-nav-fixed">
        <?php include('../navbar.php'); ?>

        <div id="layoutSidenav">
            <!-- Sidebar Navigation -->
            <div id="layoutSidenav_nav">
                <?php include('sidebar.php'); ?>
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
                                <img class="d-block mw-100 " src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="First slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Sistem kebijakan Kurikulum </h5>
                                    <p>Kebijakan kurikulum merujuk pada seperangkat prinsip, aturan, dan panduan yang digunakan untuk merancang, mengembangkan, dan mengelola program pendidikan</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block mw-100" src="https://images.unsplash.com/photo-1568992687947-868a62a9f521?q=80&w=1932&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Kebijakan Instuksi Kerja</h5>
                                    <p>Instuksi Kerja adalah seperangkat aturan, prosedur, pedoman, dan praktik yang diterapkan oleh suatu organisasi untuk mengatur hubungan antara Universitas dan Staffnya</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block mw-100" src="https://images.unsplash.com/photo-1553877522-43269d4ea984?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Sistem Kebijakan Akademik</h5>
                                    <p>Sistem kebijakan akademik adalah seperangkat aturan dan pedoman yang diterapkan oleh lembaga pendidikan untuk mengatur aspek-aspek akademik dari proses belajar-mengajar dan kehidupan mahasiswa</p>
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
                                <h2 class="section-heading">Tentang SISKA</h2>
                                <br>
                                <p class="text-muted mb-5">
                                    SISKA adalah Sistem Informasi Sosialisasi Kebijakan yang dirancang untuk memudahkan manajemen dan akses kebijakan di Universitas Kristen Satya Wacana.
                                    Dengan SISKA, Anda dapat dengan mudah menelusuri dan melihat berbagai dokumen kebijakan.
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
                    <h3 class="text-center mt-5">Dokumen Terbaru</h3>
                    <!-- card -->
                    <div class="d-flex justify-content-center">
                        <?php
                        $ambilsemuadatakebijakan = mysqli_query($conn, "SELECT * FROM kebijakan WHERE status = 1 ORDER BY tanggal DESC LIMIT 1");
                        $data = mysqli_fetch_array($ambilsemuadatakebijakan);

                        if ($data) { // Cek apakah ada data dengan status 1
                            $namakebijakan = $data['namakebijakan'];
                            $deskripsi = $data['deskripsi'];
                            $file = $data['file'];
                            $tanggal = $data['tanggal'];
                        ?>
                            <!-- Card pertama dengan hasil dari query -->

                            <div class="card ml-5 mt-3 mb-3" style="width: 20rem;height: 27rem;box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
                                <img src="https://images.unsplash.com/photo-1506377872008-6645d9d29ef7?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $namakebijakan; ?></h5>
                                    <p class="card-text"><?= $deskripsi; ?></p>
                                    <div class="baca">
                                        <a href="../assets/kebijakan/pdf/<?= $file; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }

                        $ambilsemuadatainstruksi = mysqli_query($conn, "SELECT * FROM instruksi WHERE status = 1 ORDER BY tanggal DESC LIMIT 1");
                        $data = mysqli_fetch_array($ambilsemuadatainstruksi);

                        if ($data) { // Cek apakah ada data dengan status 1
                            $namainstruksi = $data['namainstruksi'];
                            $deskripsi = $data['deskripsi'];
                            $file = $data['file'];
                            $tanggal = $data['tanggal'];
                        ?>
                            <!-- Card selanjutnya -->
                            <div class="card ml-5 mt-3 mb-3" style="width: 20rem;height: 27rem;box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
                                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D.jpg" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $namainstruksi; ?></h5>
                                    <p class="card-text"><?= $deskripsi; ?></p>
                                    <div class="baca">
                                        <a href="../assets/instruksi/pdf/<?= $file; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }

                        $ambilsemuadataprosedur = mysqli_query($conn, "SELECT * FROM prosedur WHERE status = 1 ORDER BY tanggal DESC LIMIT 1");
                        $data = mysqli_fetch_array($ambilsemuadataprosedur);

                        if ($data) { // Cek apakah ada data dengan status 1
                            $namaprosedur = $data['namaprosedur'];
                            $deskripsi = $data['deskripsi'];
                            $file = $data['file'];
                            $tanggal = $data['tanggal'];
                        ?>
                            <div class="card ml-5 mt-3 mb-3" style="width: 20rem;height: 27rem;box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
                                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $namaprosedur; ?></h5>
                                    <p class="card-text"><?= $deskripsi; ?></p>
                                    <div class="baca">
                                        <a href="../assets/prosedur/pdf/<?= $file; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                    </div>
                    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
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