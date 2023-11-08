<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'superadmin') {
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
        <title>SISKA | Kelola Admin - SuperAdmin</title>
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
                        <h1>Kelola Admin</h1>

                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Admin
                                </button>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['notification'])) {
                                    echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show" role="alert" style="max-width: 100%;">
                            <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                                    unset($_SESSION['notification']);
                                }
                                ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No </th>
                                                <th>Username</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Aksi</th>

                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                            $ambilsemuadataadmin = mysqli_query($conn, "select * from user where role = 'admin'");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadataadmin)) {

                                                $username = $data['username'];
                                                $id = $data['id'];
                                                $password = $data['password'];
                                                $email = $data['email'];
                                                $nama = $data['nama'];
                                            ?>

                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $username; ?></td>
                                                    <td><?= $nama; ?></td>
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
                                                                <h4 class="modal-title">Edit Admin</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    <small class="text-muted">Username</small>
                                                                    <input type="text" name="username" value="<?= $username; ?>" class="form-control mb-2" placeholder="Username" required>
                                                                    <small class="text-muted">Nama</small>
                                                                    <input type="text" name="nama" placeholder="nama" class="form-control mb-2" required value="<?= $nama; ?>">
                                                                    <small class="text-muted">Email</small>
                                                                    <input type="email" name="email" class="form-control mb-2" value="<?= $email; ?>" placeholder="Email">
                                                                    <small class="text-muted">Password</small>
                                                                    <input type="password" name="password" class="form-control " value="<?= $password; ?>" placeholder="Password">
                                                                    <br>
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <button type="submit" class="btn btn-warning" name="updateuser" style="width: 100%;">Edit</button>
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
                                                                <h4 class="modal-title">Hapus Admin</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin menghapus <?= $username ?>?
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="hapususer" style="width: 100%;">Hapus</button>
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
                    <h4 class="modal-title">Tambah Admin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <small class="text-muted">Username</small>
                        <input type="text" name="username" placeholder="Username" class="form-control mb-2" required value="<?= $usernameValue; ?>">
                        <small class="text-muted">Nama</small>
                        <input type="text" name="nama" placeholder="nama" class="form-control mb-2" required value="<?= $namaValue; ?>">
                        <small class="text-muted">Email</small>
                        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required value="<?= $emailValue; ?>">
                        <small class="text-muted">Password</small>
                        <input type="password" name="password" placeholder="Password" class="form-control mb-2" required value="<?= $passwordValue; ?>"><br>
                        <button type="submit" class="btn btn-primary" name="addadmin" style="width: 100%;">Kirim</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    </html>
<?php
} else {
    header("location:../loginkebijakan.php");
    exit();
}
?>