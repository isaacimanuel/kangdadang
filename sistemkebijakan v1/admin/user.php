<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'admin') {
    // Konten halaman admin
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Kelola User - Admin</title>
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
                        <h1>Kelola User</h1>

                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah User
                                </button>
                            </div>
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
                                                <th>No </th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Aksi</th>

                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                            $ambilsemuadatauser = mysqli_query($conn, "select * from user where role = 'user'");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadatauser)) {

                                                $username = $data['username'];
                                                $id = $data['id'];
                                                $password = $data['password'];
                                                $email = $data['email'];
                                            ?>

                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $username; ?></td>
                                                    <td><?= $email; ?></td>
                                                    <td><?= $password; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $id; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $id; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                <!-- edit Modal -->
                                                <div class="modal fade" id="edit<?= $id; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Update User</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    <input type="text" name="usernameuser" value="<?= $username; ?>" class="form-control" placeholder="Username" required>
                                                                    <br>
                                                                    <input type="password" name="passwordbaru" class="form-control" value="<?= $password; ?>" placeholder="Password">
                                                                    <br>
                                                                    <input type="email" name="email" placeholder="email" value="<?= $email; ?>" class="form-control" required>
                                                                    <br>
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <button type="submit" class="btn btn-primary" name="updateuser" style="width: 100%;">Submit</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- delete Modal -->
                                                <div class="modal fade" id="delete<?= $id; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Hapus User</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin menghapus <?= $username ?>?
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="hapususer">Hapus</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                            <?php
                                            };

                                            ?>
                                        </tbody>
                                    </table>
                                </div>
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

    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah User</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <input type="text" name="username" placeholder="Username" class="form-control" required>
                        <br>
                        <input type="password" name="password" placeholder="Password" class="form-control" required>
                        <br>
                        <input type="email" name="email" placeholder="Email" class="form-control" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="adduser" style="width: 100%;">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    </html>
<?php
} else {
    header("location:../login.php");
    exit();
}
?>