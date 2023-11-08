<?php
require "loginfunc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>SISKA | SIEKA </title>
    <link rel="icon" href="assets/img/siskalogo.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/img/siskalogo.png" type="image/x-icon">
    <link href="css/styles.css" rel="stylesheet" />
    <link href="css/tambah.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        .card-header {
            border-bottom: 1px solid #dee2e6;
            /* Warna garis pemisah */
        }

        .card-container {
            display: flex;
            justify-content: space-between;
        }

        .card {
            width: 45%;
            /* Lebar card */
            margin: 10px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
            /* Sesuaikan dengan tinggi halaman jika perlu */
        }
    </style>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container d-flex justify-content-center align-items-center mt-5">
                    <div class="card-container">
                        <!-- Card 1 untuk Login Kebijakan -->
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header">
                                <div class="text-center mb-2">
                                    <img src="assets/img/siska.png" alt="" width="250px">
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post" action="loginkebijakan.php">
                                    <a href="loginkebijakan.php" class="btn btn-info" style="width: 100%;">Kebijakan</a>
                                </form>
                            </div>
                        </div>

                        <!-- Card 2 untuk Login Katalog -->
                        <div class="card shadow-lg border-0 rounded-lg mt-5">
                            <div class="card-header">
                                <div class="text-center mb-2">
                                    <img src="assets/img/sieka.png" alt="" width="254px">
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post" action="loginkatalog.php">
                                    <a href="loginkatalog.php" class="btn btn-info" style="width: 100%;">E-Katalog</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>