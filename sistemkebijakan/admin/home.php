<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'admin') {
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
        <title>SISKA | Home - Admin</title>
        <link rel="icon" href="../assets/img/logosiska.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/logosiska.png" type="image/x-icon">

        <!-- Stylesheets -->
        <link href="../css/styles.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous">

        <!-- FontAwesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>

    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-2" href="index.php">
                <img src="../assets/img/siskaa.png" alt="" width="150px" class="ml-3">
            </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navbar Search -->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0"></form>

            <!-- Navbar Dropdown Menu -->
            <ul class="navbar-nav ml-auto ml-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">
            <!-- Sidebar Navigation -->
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <!-- Sidebar Menu Heading -->
                            <div class="sb-sidenav-menu-heading">Menu</div>

                            <!-- Sidebar Links -->
                            <a class="nav-link" href="home.php">
                                <i class="fas fa-home"></i>&nbsp; Home
                            </a>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-folder"></i></div>
                                Daftar Dokumen
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="index.php">
                                        <i class="fas fa-balance-scale"></i>&nbsp; Kebijakan
                                    </a>
                                    <a class="nav-link" href="standar.php">
                                        <i class="fas fa-book"></i>&nbsp; Standar
                                    </a>
                                    <a class="nav-link" href="prosedur.php">
                                        <i class="fas fa-cogs"></i>&nbsp; Prosedur
                                    </a>
                                    <a class="nav-link" href="institusi.php">
                                        <i class="fas fa-building"></i>&nbsp; Institusi Kerja
                                    </a>
                                </nav>
                            </div>
                            <a class="nav-link" href="admin.php">
                                <i class="fas fa-user-cog"></i>&nbsp; Kelola Admin
                            </a>
                            <a class="nav-link" href="user.php">
                                <i class="fas fa-user"></i>&nbsp; Kelola User
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <!-- Logged in User Info -->
                        <div class="small">
                            Logged in as:
                            <?php
                            if (isset($_SESSION['log']) && $_SESSION['log'] === "True" && isset($_SESSION['username'])) {
                                echo $_SESSION['username'];
                                if (isset($_SESSION['role'])) {
                                    if ($_SESSION['role'] === "superadmin") {
                                        echo " (Super Admin)";
                                    } else if ($_SESSION['role'] === "admin") {
                                        echo " (Admin)";
                                    } else {
                                        echo " (User)";
                                    }
                                }
                            } else {
                                // Handle case where user is not logged in
                            }
                            ?>
                        </div>
                    </div>

                </nav>
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
                                <img class="d-block w-100" src="../assets/img/1.jpg" alt="First slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Sistem kebijakan Kurikulum </h5>
                                    <p>Kebijakan kurikulum merujuk pada seperangkat prinsip, aturan, dan panduan yang digunakan untuk merancang, mengembangkan, dan mengelola program pendidikan</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="../assets/img/5.jpg" alt="second slide">
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>Sistem Kebijakan Pekerjaan</h5>
                                    <p>Sistem kebijakan pekerjaan adalah seperangkat aturan, prosedur, pedoman, dan praktik yang diterapkan oleh suatu organisasi untuk mengatur hubungan antara perusahaan dan karyawannya</p>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="../assets/img/3.jpg" alt="second slide">
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
                    <h3 class="text-center mt-5">Dokumen Terbaru</h3>
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
                            <div class="card ml-5 mt-3 mb-3" style="width: 20rem;">
                                <img src="../assets/img/4.jpg" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $namakebijakan; ?></h5>
                                    <p class="card-text"><?= $deskripsi; ?></p>
                                    <a href="../assets/kebijakan/pdf/<?= $file; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
                                </div>
                            </div>
                        <?php
                        }

                        $ambilsemuadatainstitusi = mysqli_query($conn, "SELECT * FROM institusi WHERE status = 1 ORDER BY tanggal DESC LIMIT 1");
                        $data = mysqli_fetch_array($ambilsemuadatainstitusi);

                        if ($data) { // Cek apakah ada data dengan status 1
                            $namainstitusi = $data['namainstitusi'];
                            $deskripsi = $data['deskripsi'];
                            $file = $data['file'];
                            $idi = $data['idinstitusi'];
                            $tanggal = $data['tanggal'];
                        ?>
                            <!-- Card selanjutnya -->
                            <div class="card ml-5 mt-3 mb-3" style="width: 20rem;">
                                <img src="../assets/img/2.jpg" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $namainstitusi; ?></h5>
                                    <p class="card-text"><?= $deskripsi; ?></p>
                                    <a href="../assets/institusi/pdf/<?= $file; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
                                </div>
                            </div>
                        <?php
                        }

                        $ambilsemuadataprosedur = mysqli_query($conn, "SELECT * FROM prosedur WHERE status = 1 ORDER BY tanggal DESC LIMIT 1");
                        $data = mysqli_fetch_array($ambilsemuadataprosedur);

                        if ($data) { // Cek apakah ada data dengan status 1
                            $namaprosedur = $data['namaprosedur'];
                            $deskripsi_prosedur = $data['deskripsi'];
                            $file_prosedur = $data['file'];
                            $tanggal_prosedur = $data['tanggal'];
                        ?>
                            <div class="card ml-5 mt-3 mb-3" style="width: 20rem;">
                                <img src="../assets/img/6.jpg" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $namaprosedur; ?></h5>
                                    <p class="card-text"><?= $deskripsi_prosedur; ?></p>
                                    <a href="../assets/prosedur/pdf/<?= $file_prosedur; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>


                </main>
                <footer class="py-5 bg-dark text-white">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="font-weight-bold">Hubungi Kami</h5>
                                <p>Alamat: Jl. Diponegoro No. 52-60, Salatiga, Jawa Tengah</p>
                                <p>Email: lpm@uksw.edu</p>

                            </div>
                            <div class="col-md-4">
                                <h5 class="font-weight-bold">Tentang Kami</h5>
                                <ul class="list-unstyled">
                                    <li><a href="#">Beranda</a></li>
                                    <li><a href="#">Tentang Kami</a></li>
                                    <li><a href="#">Layanan</a></li>
                                    <li><a href="#">Kontak</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h5 class="font-weight-bold">Ikuti Kami</h5>
                                <a href="https://www.facebook.com/people/lpm_uksw/100064567248871/" target="_blank"><i class="fab fa-facebook"></i></a>
                                <a href="https://twitter.com/lpm_uksw?lang=en" target="_blank"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.instagram.com/lpm_uksw/?hl=en" target="_blank"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <p class="text-center small">Copyright &copy; 2023 LPM UKSW. All Rights Reserved.</p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="../assets/demo/chart-area-demo.js"></script>
        <script src="../assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="../assets/demo/datatables-demo.js"></script>
    </body>

    </html>
<?php
} else {
    header("location:../index.php");
    exit();
}
?>