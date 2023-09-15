<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'user') {
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Standar - User</title>
        <link rel="icon" href="../assets/img/logosiska.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/logosiska.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>

    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-2" href="index.php"><img src="../assets/img/siskaa.png" alt="" width="150px" class="ml-3" href="index.php"></a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <ul class="navbar-nav ml-auto ml-md-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="logout.php">Logout</a>
                        </div>
                    </li>
                </ul>
            </form>
            <!-- Navbar-->

        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
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
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:
                            <?php
                            if (isset($_SESSION['log']) && $_SESSION['log'] === "True" && isset($_SESSION['username'])) {
                                echo $_SESSION['username'];
                            } else {
                            }
                            ?>
                        </div>

                    </div>

                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Daftar Standar </h1>
                        <div class="card mb-4">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Standar</th>
                                                <th>Deskripsi</th>
                                                <th>File </th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $ambilsemuadatastandar = mysqli_query($conn, "select * from standar where status = 1");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadatastandar)) {

                                                $namastandar = $data['namastandar'];
                                                $deskripsi = $data['deskripsi'];
                                                $file = $data['file'];
                                                $ids = $data['idstandar'];
                                                $tanggal = $data['tanggal'];
                                            ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $namastandar; ?></td>
                                                    <td><?= $deskripsi; ?></td>
                                                    <td><a href="../assets/standar/pdf/<?= $file; ?>" target="_blank"><?= $file; ?></a></td>
                                                    <td><?= $tanggal; ?></td>
                                                </tr>
                                            <?php
                                            };

                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </main>
                <footer class="py-5 bg-dark text-white">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Hubungi Kami</h5>
                                <p>Alamat: Jl. Diponegoro No. 52-60, Salatiga, Jawa Tengah</p>
                                <p>Email: lpm@uksw.edu</p>

                            </div>
                            <div class="col-md-4">
                                <h5>Tentang Kami</h5>
                                <ul class="list-unstyled">
                                    <li><a href="#">Beranda</a></li>
                                    <li><a href="#">Tentang Kami</a></li>
                                    <li><a href="#">Layanan</a></li>
                                    <li><a href="#">Kontak</a></li>
                                </ul>
                            </div>
                            <div class="col-md-4">
                                <h5>Ikuti Kami</h5>
                                <a href="#" target="_blank"><i class="fab fa-facebook"></i></a>
                                <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
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