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
        <title>SISKA | Instruksi Kerja - SuperAdmin</title>
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
                        <h1>Daftar Instruksi Kerja </h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Daftar Instruksi Kerja
                                </button>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($_SESSION['notification'])) {
                                    echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show " role="alert" style="max-width: 100%;">
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
                                                <th>No</th>
                                                <th>Nama Instruksi Kerja</th>
                                                <th>Deskripsi</th>
                                                <th>File (Maks 2MB)</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $ambilsemuadatainstruksi = mysqli_query($conn, "select * from instruksi");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadatainstruksi)) {

                                                $namainstruksi = $data['namainstruksi'];
                                                $deskripsi = $data['deskripsi'];
                                                $file = $data['file'];
                                                $idi = $data['idinstruksi'];
                                                $tanggal = $data['tanggal'];
                                            ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $namainstruksi; ?></td>
                                                    <td><?= $deskripsi; ?></td>
                                                    <td><a href="../assets/instruksi/pdf/<?= $file; ?>" target="_blank"><?= $file; ?></a></td>
                                                    <td><?= $tanggal; ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $idi; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $idi; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <?php
                                                        if ($data['status'] == 1) {
                                                            echo '<button type="button" class="btn btn-success" disabled><i class="fas fa-check"></i></button>';
                                                        } else if ($data['status'] == 2) {
                                                            echo '<button type="button" class="btn btn-danger" disabled><i class="fas fa-times"></i></button>';
                                                        } else {
                                                            echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#verifikasi' . $idi . '"><i class="fas fa-check"></i></button>&nbsp;';
                                                            echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolakverifikasi' . $idi . '"><i class="fas fa-times"></i></button>';
                                                        }
                                                        ?>

                                                    </td>
                                                </tr>

                                                <!-- edit Modal -->
                                                <div class="modal fade" id="edit<?= $idi; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Update Data instruksi</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <small class="text-muted">Instruksi Kerja</small>
                                                                    <input type="text" name="namainstruksi" value="<?= $namainstruksi; ?>" class="form-control mb-2" required>
                                                                    <small class="text-muted">Deskripsi</small>
                                                                    <textarea class="form-control mb-2" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi" required><?php echo $deskripsi; ?></textarea>
                                                                    <small class="text-muted">File pdf Maksimal 2MB.</small>
                                                                    <input type="file" name="file" accept=".pdf" value="<?= $file; ?>" class="form-control mb-2">
                                                                    <small class="text-muted">Tanggal SK</small>
                                                                    <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                                                                    <br>
                                                                    <input type="hidden" name="idi" value="<?= $idi; ?>">
                                                                    <button type="submit" class="btn btn-warning" name="updatedatainstruksi" style="width: 100%;">Edit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- delete Modal -->
                                                <div class="modal fade" id="delete<?= $idi; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Hapus Data instruksi</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin menghapus <?= $namainstruksi ?>?
                                                                    <input type="hidden" name="idi" value="<?= $idi; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="hapusdatainstruksi" style="width: 100%;">Hapus</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Verifikasi Modal -->
                                                <div class="modal fade" id="verifikasi<?= $idi; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Verifikasi Data instruksi</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin verifikasi <?= $namainstruksi ?>?
                                                                    <input type="hidden" name="idi" value="<?= $idi; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-success" name="verifikasidatainstruksi" style="width: 100%;">Verifikasi</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tolak Verifikasi Modal -->
                                                <div class="modal fade" id="tolakverifikasi<?= $idi; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Tolak Data Instruksi</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda yakin ingin tolak verifikasi <?= $namainstruksi ?>?
                                                                    <input type="hidden" name="idi" value="<?= $idi; ?>">
                                                                    <br><br>
                                                                    <small>Deskripsi Kesalahan</small>
                                                                    <textarea class="form-control" id="kesalahan" name="kesalahan" rows="3" required></textarea>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="tidakverifdatainstruksi" style="width: 100%;">Tolak</button>
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
                    <h4 class="modal-title">Tambah Data instruksi</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <small class="text-muted">Instruksi Kerja</small>
                        <input type="text" name="namainstruksi" placeholder="Nama instruksi" class="form-control mb-2" required value="<?= $namaValue; ?>">
                        <small class="text-muted">Deskripsi</small>
                        <textarea class="form-control mb-2" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi" required><?php echo $deskripsiValue; ?></textarea>
                        <small class="text-muted">File pdf Maksimal 2MB.</small>
                        <input type="file" name="file" accept=".pdf" class="form-control mb-2" placeholder="file" required>
                        <small class="text-muted">Tanggal SK</small>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="addnewdatainstruksi" style="width: 100%;">Kirim</button>
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