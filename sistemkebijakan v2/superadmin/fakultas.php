<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'superadmin') {
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Kebijakan Fakultas - SuperAdmin</title>
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
                        <h1>Daftar Kebijakan Fakultas </h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- Button to Open the Modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Daftar Kebijakan
                                </button>
                            </div>
                            <div class=" card-body">
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
                                                <th>No</th>
                                                <th>Nama Kebijakan</th>
                                                <th>Deskripsi</th>
                                                <th>Fakultas</th>
                                                <th>File (Maks 2MB)</th>
                                                <th>Tanggal</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $ambilsemuadatakebijakan = mysqli_query($conn, "select * from fakultas");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadatakebijakan)) {

                                                $namakebijakan = $data['namakebijakan'];
                                                $deskripsi = $data['deskripsi'];
                                                $file = $data['file'];
                                                $id = $data['id'];
                                                $fakultas = $data['fakultas'];
                                                $tanggal = $data['tanggal'];
                                                $status = $data['status'];
                                            ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><?= $namakebijakan; ?></td>
                                                    <td><?= $deskripsi; ?></td>
                                                    <td><?= $fakultas; ?></td>
                                                    <td><a href="../assets/fakultas/pdf/<?= $file; ?>" target="_blank"><?= $file; ?></a></td>
                                                    <td><?= $tanggal; ?></td>
                                                    <td>

                                                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $id; ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $id; ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        <?php
                                                        if ($data['status'] == 1) {
                                                            echo '<button type="button" class="btn btn-success" disabled><i class="fas fa-check"></i></button>';
                                                        } else if ($data['status'] == 2) {
                                                            echo '<button type="button" class="btn btn-danger" disabled><i class="fas fa-times"></i></button>';
                                                        } else {
                                                            echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#verifikasi' . $id . '"><i class="fas fa-check"></i></button>&nbsp;';
                                                            echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolakverifikasi' . $id . '"><i class="fas fa-times"></i></button>';
                                                        }
                                                        ?>

                                                    </td>
                                                </tr>

                                                <!-- edit Modal -->
                                                <div class="modal fade" id="edit<?= $id; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Edit Data Kebijakan</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>


                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <small class="text-muted">Nama Kebijakan</small>
                                                                    <input type="text" name="namakebijakan" value="<?= $namakebijakan; ?>" class="form-control mb-2" required>
                                                                    <small class="text-muted">Deskripsi</small>
                                                                    <textarea class="form-control mb-2" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi" required><?php echo $deskripsi; ?></textarea>
                                                                    <small class="text-muted">Fakultas</small>
                                                                    <select id="fakultas" name="fakultas" class="form-control mb-2" required>
                                                                        <option value="Fakultas Keguruan dan Ilmu Pendidikan">Fakultas Keguruan dan Ilmu Pendidikan</option>
                                                                        <option value="Fakultas Biologi">Fakultas Biologi</option>
                                                                        <option value="Fakultas Bahasa dan Seni">Fakultas Bahasa dan Seni</option>
                                                                        <option value="Fakultas Teknik Elektronika dan Komputer">Fakultas Teknik Elektronika dan Komputer</option>
                                                                        <option value="Fakultas Ekonomika dan Bisnis">Fakultas Ekonomika dan Bisnis</option>
                                                                        <option value="Fakultas Ilmu Sosial dan Ilmu Komunikasi">Fakultas Ilmu Sosial dan Ilmu Komunikasi</option>
                                                                        <option value="Fakultas Teknologi Informasi">Fakultas Teknologi Informasi</option>
                                                                        <option value="Fakultas Teologi">Fakultas Teologi</option>
                                                                        <option value="Fakultas Kedokteran dan Ilmu Kesehatan">Fakultas Kedokteran dan Ilmu Kesehatan</option>
                                                                        <option value="Fakultas Psikologi">Fakultas Psikologi</option>
                                                                        <option value="Fakultas Hukum">Fakultas Hukum</option>
                                                                        <option value="Fakultas Sains dan Matematika">Fakultas Sains dan Matematika</option>
                                                                        <option value="Fakultas Pertanian dan Bisnis">Fakultas Pertanian dan Bisnis</option>
                                                                        <option value="Pascasarjana">Pascasarjana</option>
                                                                    </select>
                                                                    <small class="text-muted">File pdf Maksimal 2MB.</small>
                                                                    <input type="file" name="file" accept=".pdf" value="<?= $file; ?>" class="form-control mb-2">
                                                                    <small class="text-muted">Tanggal SK</small>
                                                                    <input type="datetime-local" class="form-control mb-2" id="tanggal" name="tanggal" required>
                                                                    <br>
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <button type="submit" class="btn btn-warning" name="updatedatafakultas" style="width: 100%;">Edit</button>
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
                                                                <h4 class="modal-title">Hapus Data Kebijakan</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin menghapus data <?= $namakebijakan ?>?
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="hapusdatakebijakanfakultas" style="width: 100%;">Hapus</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Verifikasi Modal -->
                                                <div class="modal fade" id="verifikasi<?= $id; ?>">
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
                                                                    Apakah Anda yakin ingin verifikasi Kebijakan <?= $namakebijakan ?>?
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-success" name="verifikasifakultas" style="width: 100%;">Verifikasi</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tolak Verifikasi Modal -->
                                                <div class="modal fade" id="tolakverifikasi<?= $id; ?>">
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
                                                                    Apakah Anda Yakin ingin Tolak verifikasi data <?= $namakebijakan ?>?
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <small>Deskripsi Kesalahan</small>
                                                                    <textarea class="form-control" id="kesalahan" name="kesalahan" rows="3" required></textarea>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="tidakverifikasifakultas" style="width: 100%;">Tolak</button>
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
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <small class="text-muted">Nama Kebijakan</small>
                        <input type="text" name="namakebijakan" placeholder="Nama Kebijakan" class="form-control mb-2" required value="<?= $namaValue; ?>">
                        <small class="text-muted">Deskripsi</small>
                        <textarea class="form-control mb-2" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi" required><?php echo $deskripsiValue; ?></textarea>
                        <small class="text-muted">Fakultas</small>
                        <select id="fakultas" name="fakultas" class="form-control mb-2" required>
                            <option value="Fakultas Keguruan dan Ilmu Pendidikan">Fakultas Keguruan dan Ilmu Pendidikan</option>
                            <option value="Fakultas Biologi">Fakultas Biologi</option>
                            <option value="Fakultas Bahasa dan Seni">Fakultas Bahasa dan Seni</option>
                            <option value="Fakultas Teknik Elektronika dan Komputer">Fakultas Teknik Elektronika dan Komputer</option>
                            <option value="Fakultas Ekonomika dan Bisnis">Fakultas Ekonomika dan Bisnis</option>
                            <option value="Fakultas Ilmu Sosial dan Ilmu Komunikasi">Fakultas Ilmu Sosial dan Ilmu Komunikasi</option>
                            <option value="Fakultas Teknologi Informasi">Fakultas Teknologi Informasi</option>
                            <option value="Fakultas Teologi">Fakultas Teologi</option>
                            <option value="Fakultas Kedokteran dan Ilmu Kesehatan">Fakultas Kedokteran dan Ilmu Kesehatan</option>
                            <option value="Fakultas Psikologi">Fakultas Psikologi</option>
                            <option value="Fakultas Hukum">Fakultas Hukum</option>
                            <option value="Fakultas Sains dan Matematika">Fakultas Sains dan Matematika</option>
                            <option value="Fakultas Pertanian dan Bisnis">Fakultas Pertanian dan Bisnis</option>
                            <option value="Pascasarjana">Pascasarjana</option>
                        </select>
                        <small class="text-muted">File pdf Maksimal 2MB.</small>
                        <input type="file" name="file" accept=".pdf" class="form-control mb-2" placeholder="file" required>
                        <small class="text-muted">Tanggal SK</small>
                        <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="addnewdatafakultas" style="width: 100%;">Kirim</button>
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