<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'superadmin') {
    // Konten halaman superadmin
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Daftar Pesanan - SuperAdmin</title>
        <link rel="icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
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
                        <h1>Daftar Pesanan
                            <a href="tipe.php" class="btn btn-success" style="float: right;">
                                <i class="fas fa-tag"></i>
                            </a>
                        </h1>
                        <div class="card mb-4">
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
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Username Pemesan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $ambilsemuadatakatalog = mysqli_query($conn, "SELECT username,idtipe, COUNT(*) as total_pesanan FROM pesanan GROUP BY username");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
                                                $username = $data['username'];
                                                $idtipe = $data['idtipe'];
                                            ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $username; ?></td>
                                                    <td>
                                                        <a href="detailpesan.php?username=<?= $username; ?>" type="button" class="btn btn-secondary">
                                                            <i class="fas fa-info-circle"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php
                                            };

                                            ?>
                                        </tbody>
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
    header("location:../index.php");
    exit();
}
?>