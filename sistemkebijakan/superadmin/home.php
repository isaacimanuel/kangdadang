<?php
require "../function.php";
require "../cek.php";
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
        <title>SISKA | Home - Superadmin</title>
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
                transform: scale(1.05);
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
                    <?php include('../carousel.php'); ?>
                    <!-- Tentang SISKA -->
                    <?php include('../tentang.php'); ?>
                    <h3 class="text-center mt-5">Dokumen Terbaru</h3>
                    <div class="d-flex justify-content-center">
                        <?php include('../card.php'); ?>

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
    header("location:../index.php");
    exit();
}
?>