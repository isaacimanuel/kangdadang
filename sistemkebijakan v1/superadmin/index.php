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
        <title>SISKA | Kebijakan Direktorat - SuperAdmin</title>
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
                        <h1>Daftar Kebijakan Direktorat</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Daftar Kebijakan
                                </button>
                            </div>
                            <br>
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
                                                <th>Nama Kebijakan</th>
                                                <th>Deskripsi</th>
                                                <th>File (Maks 2MB)</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $ambilsemuadatakebijakan = mysqli_query($conn, "select * from kebijakan");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadatakebijakan)) {

                                                $namakebijakan = $data['namakebijakan'];
                                                $deskripsi = $data['deskripsi'];
                                                $file = $data['file'];
                                                $idk = $data['idkebijakan'];
                                                $tanggal = $data['tanggal'];
                                            ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <?php if ($idk == 38) { ?>
                                                        <td><a href="tipe.php"><?= $namakebijakan; ?></a></td>
                                                    <?php  } else { ?>
                                                        <td><?= $namakebijakan; ?></td>
                                                    <?php } ?>
                                                    <td><?= $deskripsi; ?></td>
                                                    <td><a href="../assets/kebijakan/pdf/<?= $file; ?>" target="_blank"><?= $file; ?></a></td>
                                                    <td><?= $tanggal; ?></td>
                                                    <td>

                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $idk; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idk; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <?php
                                                        if ($data['status'] == 1) {
                                                            echo '<button type="button" class="btn btn-success" disabled><i class="fas fa-check"></i></button>';
                                                        } else if ($data['status'] == 2) {
                                                            echo '<button type="button" class="btn btn-danger" disabled><i class="fas fa-times"></i></button>';
                                                        } else {
                                                            echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#verifikasi' . $idk . '"><i class="fas fa-check"></i></button>&nbsp;';
                                                            echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolakverifikasi' . $idk . '"><i class="fas fa-times"></i></button>';
                                                        }
                                                        ?>

                                                    </td>
                                                </tr>

                                                <!-- edit Modal -->
                                                <div class="modal fade" id="edit<?= $idk; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Update Data Kebijakan</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <input type="text" name="namakebijakan" value="<?= $namakebijakan; ?>" class="form-control" required>
                                                                    <br>
                                                                    <input type="text" name="deskripsi" value="<?= $deskripsi; ?>" class="form-control" required>
                                                                    <br>
                                                                    <small class="text-muted">File pdf Maksimal 2MB.</small>
                                                                    <input type="file" name="file" accept=".pdf" value="<?= $file; ?>" class="form-control">
                                                                    <br>
                                                                    <small class="text-muted">Tanggal SK</small>
                                                                    <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                                                                    <br>
                                                                    <input type="hidden" name="idk" value="<?= $idk; ?>">
                                                                    <button type="submit" class="btn btn-warning" name="updatedatakebijakan" style="width: 100%;">Edit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- delete Modal -->
                                                <div class="modal fade" id="delete<?= $idk; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Hapus Data Kebijakan</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin menghapus data <?= $namakebijakan ?>?
                                                                    <input type="hidden" name="idk" value="<?= $idk; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="hapusdatakebijakan" style="width: 100%;">Hapus</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Verifikasi Modal -->
                                                <div class="modal fade" id="verifikasi<?= $idk; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Verifikasi Data Kebijakan</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin verifikasi data <?= $namakebijakan ?>?
                                                                    <input type="hidden" name="idk" value="<?= $idk; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-success" name="verifikasidatakebijakan" style="width: 100%;">Verifikasi</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tolak Verifikasi Modal -->
                                                <div class="modal fade" id="tolakverifikasi<?= $idk; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Tolak Data Kebijakan</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin Tolak verifikasi data <?= $namakebijakan ?>?
                                                                    <input type="hidden" name="idk" value="<?= $idk; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <small>Deskripsi Kesalahan</small>
                                                                    <textarea class="form-control" id="kesalahan" name="kesalahan" rows="3" required></textarea>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="tidakverifdatakebijakan" style="width: 100%;">Tolak</button>
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
                    <h4 class="modal-title">Tambah Data Kebijakan</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                    <div class="modal-body">
                        <input type="text" name="namakebijakan" placeholder="Nama Kebijakan" class="form-control" required>
                        <br>
                        <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control" required>
                        <br>
                        <small class="text-muted">File pdf Maksimal 2MB.</small>
                        <input type="file" name="file" accept=".pdf" class="form-control" placeholder="file" required>
                        <br>
                        <small class="text-muted">Tanggal SK</small>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="addnewdatakebijakan" style="width: 100%;">Kirim</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    </html>
<?php
} else {
    header("location:../index.php");
    exit();
}
?>