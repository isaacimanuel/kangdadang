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
        <title>SISKA | Instruksi Kerja - Admin</title>
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
                            <?php
                            $ambilsemuadatainstruksi = mysqli_query($conn, "select * from instruksi where status = 2");
                            while ($data = mysqli_fetch_array($ambilsemuadatainstruksi)) {
                            ?>
                                <div class="alert alert-warning ml-3" role="alert" style="max-width: 500px;">
                                    <i class="fas fa-info-circle"></i> Mohon Perbaiki Data yang Gagal Terverifikasi
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php
                            };
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
                                                $status = $data['status'];
                                                $kesalahan = $data['kesalahan'];
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
                                                        if ($status == 0) {
                                                        ?>
                                                            <button type="button" class="btn btn-info" disabled><i class="fas fa-clock"></i></button>
                                                        <?php
                                                        } else if ($status == 1) {
                                                        ?>
                                                            <button type="button" class="btn btn-success" disabled><i class="fas fa-check"></i></button>

                                                        <?php
                                                        } else {
                                                        ?>
                                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#kesalahan<?= $idi; ?>">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        <?php
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
                                                                    <input type="text" name="namainstruksi" value="<?= $namainstruksi; ?>" class="form-control" required>
                                                                    <br>
                                                                    <input type="text" name="deskripsi" value="<?= $deskripsi; ?>" class="form-control" required>
                                                                    <br>
                                                                    <small class="text-muted">File pdf Maksimal 2MB.</small>
                                                                    <input type="file" name="file" accept=".pdf" value="<?= $file; ?>" class="form-control">
                                                                    <br>
                                                                    <small class="text-muted">Tanggal SK</small>
                                                                    <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                                                                    <br>
                                                                    <input type="hidden" name="idi" value="<?= $idi; ?>">
                                                                    <button type="submit" class="btn btn-primary" name="updatedatainstruksi" style="width: 100%;">Submit</button>
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
                                                                    Apakah Anda Yakin ingin menghapus data <?= $namainstruksi ?>?
                                                                    <input type="hidden" name="idi" value="<?= $idi; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="hapusdatainstruksi">Delete</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Lihat Kesalahan Modal -->
                                                <div class="modal fade" id="kesalahan<?= $idi; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Catatan Data Instruksi Kerja</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Mohon perbaiki data <?= $namainstruksi ?>
                                                                    <br>
                                                                    <br>
                                                                    <small>Deskripsi Kesalahan</small>
                                                                    <textarea class="form-control" name="kesalahan" rows="3" readonly><?php echo $kesalahan; ?></textarea>
                                                                    <br>
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
                        <input type="text" name="namainstruksi" placeholder="Nama instruksi" class="form-control" required>
                        <br>
                        <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control" required>
                        <br>
                        <small class="text-muted">File pdf Maksimal 2MB.</small>
                        <input type="file" name="file" accept=".pdf" class="form-control" placeholder="file" required>
                        <br>
                        <small class="text-muted">Tanggal SK</small>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="addnewdatainstruksi" style="width: 100%;">Submit</button>
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