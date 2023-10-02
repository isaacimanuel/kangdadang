<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'daku') {

?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Proposal - DAKU</title>
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
                        <h1>Daftar Proposal </h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <a href="form.php" class="btn btn-primary">Buat Proposal</a>
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
                                                <th>No</th>
                                                <th>Nama Kegiatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $ambilsemuadataproposal = mysqli_query($conn, "select * from proposal");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadataproposal)) {
                                                $id = $data['id'];
                                                $namakegiatan = $data['nama_kegiatan'];
                                                $status = $data['status'];
                                                $verif_lpm = $data['verif_lpm'];
                                                $verif_daku = $data['verif_daku'];
                                            ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td><strong><a href="detail.php?id=<?= $id; ?>"><?= $namakegiatan; ?></a></strong></td>
                                                    <td>
                                                        <a href="edit.php?id=<?= $id; ?>" class=" btn btn-warning">Edit</a>
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $id; ?>">
                                                            Delete
                                                        </button>
                                                        <?php
                                                        if ($status == 0) {
                                                            if ($verif_lpm == 0 && $verif_daku == 0) {
                                                                // Jika belum ada verifikasi dari kedua pihak
                                                                echo '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#verifikasi' . $id . '">
                                                                    Verifikasi
                                                                    </button>';
                                                            } else if ($verif_lpm == 1 && $verif_daku == 0) {
                                                                // Jika sudah ada verifikasi dari 'lpm', tetapi belum dari 'daku'
                                                                echo
                                                                '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#verifikasi' . $id . '">
                                                                    Verifikasi
                                                                    </button>';
                                                            } else if ($verif_lpm == 0 && $verif_daku == 1) {
                                                                // Jika sudah ada verifikasi dari 'daku', tetapi belum dari 'lpm'
                                                                echo '<button type="button" class="btn btn-info" disabled>Proses</button>';
                                                            }
                                                        } else if ($status == 1) {
                                                            echo '<button type="button" class="btn btn-success" disabled>Terverifikasi</button>';
                                                        } else {
                                                            // Jika sudah ada verifikasi dari kedua pihak
                                                            echo '<button type="button" class="btn btn-danger" disabled>Gagal Terverifikasi</button>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                                <!-- delete Modal -->
                                                <div class="modal fade" id="delete<?= $id; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Hapus Proposal</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin menghapus data <?= $namakegiatan ?>?
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-danger" name="hapusdataproposal">Delete</button>
                                                                </div>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- verifikasi Modals -->
                                                <div class="modal fade" id="verifikasi<?= $id; ?>">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">

                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Verfikasi Proposal</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>

                                                            <!-- Modal body -->
                                                            <form method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    Apakah Anda Yakin ingin Memverifikasi Proposal <?= $namakegiatan ?>?
                                                                    <input type="hidden" name="id" value="<?= $id; ?>">
                                                                    <br>
                                                                    <br>
                                                                    <button type="submit" class="btn btn-success" name="verifikasiproposaldaku">Verifikasi</button>
                                                                    <button type="submit" class="btn btn-danger" name="tolakproposaldaku">Tolak</button>
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

    </html>

<?php
} else {
    header("location:../index.php");
    exit();
}
?>