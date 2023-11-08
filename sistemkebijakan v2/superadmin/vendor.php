<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'superadmin_katalog') {
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
        <title>SIEKA | Kelola User - SuperAdmin</title>
        <link rel="icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
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
                        <h1>Kelola Vendor</h1>
                        <div class="navigation">
                            <a href="vendor.php">Kelola Vendor</a>
                        </div>
                        <?php
                        if (isset($_SESSION['notification'])) {
                            echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show mt-3" role="alert" style="max-width: 100%;">
                            <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                            unset($_SESSION['notification']);
                        }
                        ?>
                        <div class="card mt-3 mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Vendor
                                </button>
                            </div>


                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No </th>
                                                <th>Nama Vendor</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Password</th>
                                                <th>Aksi</th>

                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                            $ambilsemuadatauser = mysqli_query($conn, "select * from user_katalog where role = 'suplier'");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadatauser)) {

                                                $username = $data['username'];
                                                $id = $data['id'];
                                                $password = $data['password'];
                                                $nama = $data['nama'];
                                                $alamat = $data['alamat'];
                                                $deskripsi = $data['deskripsi'];
                                                $email = $data['email'];
                                            ?>

                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><a href="profilvendor.php?id=<?= $id; ?>"><?= $nama; ?></a></td>
                                                    <td><?= $username; ?></td>
                                                    <td><?= $email; ?></td>
                                                    <td><?= $password; ?></td>
                                                    <td>
                                                        <button type=" button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $id; ?>">
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
                                                                <h4 class="modal-title">Update Vendor</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    <small class="text-muted">Username</small>
                                                                    <input type="text" name="username" value="<?= $username; ?>" class="form-control mb-3" placeholder="Username" required>
                                                                    <small class="text-muted">Password</small>
                                                                    <input type="password" name="password" class="form-control mb-3" value="<?= $password; ?>" placeholder="Password">
                                                                    <small class="text-muted">Email</small>
                                                                    <input type="email" name="email" class="form-control mb-3" value="<?= $email; ?>" placeholder="Email">
                                                                    <small class="text-muted">Nama Vendor</small>
                                                                    <input type="text" name="nama" placeholder="Nama" value="<?= $nama; ?>" class="form-control mb-3">
                                                                    <small class="text-muted">Alamat</small>
                                                                    <input type="text" name="alamat" placeholder="Alamat" value="<?= $alamat; ?>" class="form-control mb-3">
                                                                    <small class="text-muted">Deskripsi</small>
                                                                    <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi Vendor" rows="3"><?php echo $deskripsi; ?></textarea><br>
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <button type="submit" class="btn btn-warning" name="updatevendor" style="width: 100%;">Edit</button>
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
                                                                <h4 class="modal-title">Hapus Vendor</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post">
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin menghapus <?= $username ?>?
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="hapususerkatalog" style="width: 100%;">Hapus</button>
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
                    <h4 class="modal-title">Tambah Vendor</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <small class="text-muted">Username</small>
                        <input type="text" name="username" placeholder="Username" class="form-control mb-3" required value="<?= $usernameValue; ?>">
                        <small class="text-muted">Password</small>
                        <input type="password" name="password" placeholder="Password" class="form-control  mb-2" required value="<?= $passwordValue; ?>">
                        <small class="text-muted">Email</small>
                        <input type="email" name="email" placeholder="Email" class="form-control  mb-2" required value="<?= $emailValue; ?>">
                        <small class="text-muted">Nama Vendor</small>
                        <input type="text" name="nama" placeholder="Nama" class="form-control  mb-2" required value="<?= $namaValue; ?>">
                        <small class="text-muted">Alamat</small>
                        <input type="text" name="alamat" placeholder="Alamat" class="form-control  mb-2" value="<?= $alamatValue; ?>">
                        <small class="text-muted">Deskripsi</small>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi Vendor" rows="3"><?php echo $deskripsiValue ?></textarea><br>
                        <button type="submit" class="btn btn-primary" name="addvendor" style="width: 100%;">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    </html>
<?php
} else {
    header("location:../loginkatalog.php");
    exit();
}
?>