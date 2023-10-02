<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'user') {
    $username = $_SESSION['username'];
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Proposal - User</title>
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
                            $ambilsemuadataproposal = mysqli_query($conn, "select * from proposal where status = 2");
                            while ($data = mysqli_fetch_array($ambilsemuadataproposal)) {
                            ?>
                                <br>
                                <div class="alert alert-warning ml-3" role="alert" style="max-width: 500px;">
                                    <i class="fas fa-info-circle"></i> Mohon Perbaiki Proposal yang Gagal Terverifikasi
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php
                            };
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

                                            <?php $ambilsemuadataproposal = mysqli_query($conn, "select * from proposal where username = '$username'");
                                            $i = 1;
                                            while ($data = mysqli_fetch_array($ambilsemuadataproposal)) {
                                                $id = $data['id'];
                                                $namakegiatan = $data['nama_kegiatan'];
                                                $status = $data['status'];
                                                $username = $data['username'];
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
                                                            echo '<button type="button" class="btn btn-info" disabled>Belum Terverifikasi</button>';
                                                        } else if ($status == 1) {
                                                            echo '<button type="button" class="btn btn-success" disabled>Terverifikasi</button>';
                                                        } else {
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