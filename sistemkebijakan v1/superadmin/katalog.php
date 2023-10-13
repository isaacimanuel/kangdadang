<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'superadmin') {
    // Konten halaman superadmin
    $idtipe = $_GET['idtipe'];

    // Dapatkan nama tipe dari database
    $result = mysqli_query($conn, "SELECT namatipe FROM tipe WHERE idtipe = '$idtipe'");
    $data = mysqli_fetch_assoc($result);

    // Cek apakah data ditemukan
    if ($data) {
        $namatipe = $data['namatipe'];
    } else {
        $namatipe = "Tipe tidak ditemukan"; // Jika tipe tidak ditemukan
    }
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Katalog Sarana Prasarana - SuperAdmin</title>
        <link rel="icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .icon {
                margin-right: 5px;
                /* Menambahkan margin kanan sebesar 10 piksel */
            }

            .card-columns {
                column-count: 5;
                column-gap: 0.5rem;
                justify-content: space-between;
                /* Tambahkan jarak antar card */
            }

            .card {
                border: none;
                border-radius: 10px;
                overflow: hidden;
                margin-bottom: 1rem;
                transition: transform 0.3s;
            }

            .card:hover {
                transform: scale(1.02);
            }

            .card-img-top {
                object-fit: cover;
                height: 200px;
                /* Atur tinggi gambar */
            }

            .card-title {
                font-size: 1rem;
                font-weight: bold;
                margin: 0;
            }

            .card-text {
                font-size: 1rem;
            }
        </style>
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
                        <h1>Katalog <?= $namatipe; ?>
                            <a type="button" href="pesanan.php" class="btn btn-info icon" style="float: right;"><i class="fas fa-shopping-cart"></i></a>
                            <a href="tipe.php" type="button" class="btn btn-success icon" style="float: right;"><i class="fas fa-tag"></i></a>

                            <button class="btn btn-secondary icon" data-toggle="modal" data-target="#filter<?= $idtipe; ?>" style="float: right;"><i class="fas fa-filter"></i></button>

                            <div class="btn-group" style="float: right; margin-right: 5px;" role="group" aria-label="Basic example">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                            </div>

                        </h1>


                        <!-- Button to Open the Modal -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                            Tambah Data Katalog
                        </button>

                        <?php
                        if (isset($_SESSION['notification'])) {
                            echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show mt-3" role="alert" style="max-width: 500px;">
                            <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                            unset($_SESSION['notification']);
                        }
                        ?>
                        <br><br>
                        <div class="card-columns">
                            <?php
                            $counter = 1;
                            $filterError = false; // Tambahkan ini untuk melacak kesalahan filter
                            $searchError = false; // Tambahkan ini untuk melacak kesalahan pencarian
                            if (isset($_GET['caribarang'])) {
                                $idtipe = $_GET['idtipe'];
                                $namabarang = $_GET['namabarang'];
                                $vendor = $_GET['vendor'];
                                $minharga = $_GET['minharga'];
                                $maxharga = $_GET['maxharga'];

                                $filter_query = "SELECT * FROM katalog WHERE idtipe = '$idtipe'";

                                if (!empty($namabarang)) {
                                    $filter_query .= " AND namabarang LIKE '%$namabarang%'";
                                }

                                if (!empty($vendor)) {
                                    $filter_query .= " AND vendor = '$vendor'";
                                }

                                if (!empty($minharga)) {
                                    $filter_query .= " AND harga >= $minharga";
                                }

                                if (!empty($maxharga)) {
                                    $filter_query .= " AND harga <= $maxharga";
                                }

                                $ambilsemuadatakatalog = mysqli_query($conn, $filter_query);
                            } else {
                                $ambilsemuadatakatalog = mysqli_query($conn, "SELECT * FROM katalog WHERE idtipe = '$idtipe'");
                            }
                            if (mysqli_num_rows($ambilsemuadatakatalog) > 0) {
                                while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
                                    $idkatalog = $data['idkatalog'];
                                    $idtipe = $data['idtipe'];
                                    $namabarang = $data['namabarang'];
                                    $foto = $data['foto'];
                                    $id = $data['id'];
                                    $vendor = $data['vendor'];
                                    $harga = number_format($data['harga'], 0, ',', '.');
                                    $jumlah = $data['jumlah'];
                                    $deskripsi = $data['deskripsi'];
                            ?>

                                    <div class="card">
                                        <a href="detailcard.php?id=<?= $id; ?>">
                                            <img src="../assets/img/<?= $foto; ?>" class="card-img-top" alt="<?= $foto; ?>">
                                        </a>
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $namabarang; ?></h5>
                                            <p class="card-text">Harga: Rp. <?= $harga; ?></p>
                                            <button class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $id; ?>"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $id; ?>"><i class="fas fa-trash"></i></button>
                                            <button class="btn btn-success" data-toggle="modal" data-target="#pesan<?= $id; ?>"><i class="fas fa-credit-card"></i></button>
                                        </div>
                                    </div>
                                    <!-- Pesan Modal -->
                                    <div class="modal fade" id="pesan<?= $id; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Pesan Data Barang</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        Apakah Anda Yakin ingin Pesan <?= $namabarang ?>?
                                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                                        <input type="hidden" name="idtipe" value="<?= $idtipe; ?>">
                                                        <input type="hidden" name="namabarang" value="<?= $namabarang; ?>">
                                                        <input type="hidden" name="vendor" value="<?= $vendor; ?>">
                                                        <br>
                                                        <br>
                                                        <input type="number" name="jumlah" placeholder="Jumlah Barang" class="form-control" required>
                                                        <br>
                                                        <button type="submit" class="btn btn-success" name="pesandatabarang" style="width: 100%;">Pesan</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- edit Modal -->
                                    <div class="modal fade" id="edit<?= $id; ?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Data Barang</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        <small><label for="foto" class="form-label text-muted">Gambar Barang</label></small>
                                                        <input type="file" name="foto" accept=".jpg,.png,.jpeg" class="form-control">
                                                        <small class="text-muted">Nama Barang</small>
                                                        <input type="text" name="namabarang" value="<?= $namabarang; ?>" placeholder="Nama Barang" class="form-control" required>
                                                        <small class="text-muted">Vendor</small>
                                                        <input type="text" name="vendor" placeholder="Nama Vendor" value="<?= $vendor; ?>" class="form-control" required>
                                                        <small class="text-muted">Deskripsi Barang</small>
                                                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi Barang" required><?php echo $deskripsi; ?></textarea>
                                                        <br>
                                                        <small class="text-muted">Harga Barang</small>
                                                        <input type="number" name="harga" placeholder="Harga Barang" value="<?= $harga; ?>" class="form-control" required>
                                                        <small class="text-muted">Jumlah Barang</small>
                                                        <input type="number" name="jumlah" placeholder="Jumlah Barang" value="<?= $jumlah; ?>" class="form-control" required>
                                                        <br>
                                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                                        <button type="submit" class="btn btn-warning" name="editdatabarang" style="width: 100%;">Edit</button>
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
                                                    <h4 class="modal-title">Hapus Data Barang</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <form method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        Apakah Anda Yakin ingin menghapus data <?= $namabarang ?>?
                                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                                        <input type="hidden" name="idtipe" value="<?= $idtipe; ?>">
                                                        <br>
                                                        <br>
                                                        <button type="submit" class="btn btn-danger" name="hapusdatabarang" style="width: 100%;">Hapus</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                            <?php
                                    if ($counter % 5 == 0) {
                                        echo '</div><div class="w-100"></div><div class="card-columns">';
                                    }

                                    $counter++;
                                }
                            } else {
                                echo ' <div id="noDataMessage" style="display: none; text-align: center; opacity:0.8;">Tidak ada data</div>';
                            }
                            ?>
                        </div>
                        <div id="noDataMessage" style="display: none; text-align: center; opacity:0.8;">Tidak ada data</div>
                        <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
                </main>
                <?php include('../footer.php'); ?>
            </div>
        </div>


        <!-- The Modal -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Tambah Data Barang</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <small class="text-muted">Gambar Barang</small>
                            <input type="hidden" name="idtipe" value="<?= $idtipe; ?>">
                            <input type="hidden" name="idkatalog" value="<?= $idkatalog; ?>">
                            <input type="file" name="foto" accept=".jpg,.png,.jpeg," class="form-control" required>
                            <br>
                            <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required>
                            <br>
                            <input type="text" name="vendor" placeholder="Nama Vendor" class="form-control" required>
                            <br>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi Barang" required></textarea>
                            <br>
                            <input type="number" name="harga" placeholder="Harga Barang" class="form-control" required>
                            <br>
                            <input type="number" name="jumlah" placeholder="Jumlah Barang" class="form-control" required>
                            <br>
                            <button type="submit" class="btn btn-primary" name="addnewdatabarang" style="width: 100%;">Kirim</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- Filter Modal -->
        <div class="modal fade" id="filter<?= $idtipe; ?>">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Filter Barang</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <form method="get">
                        <div class="modal-body">
                            <input type="hidden" name="idtipe" value="<?= $idtipe; ?>">
                            <div class="form-group">
                                <label for="barang">Cari berdasarkan nama barang:</label>
                                <input type="text" name="namabarang" id="barang" class="form-control" placeholder="Nama Barang" value="<?= isset($_GET['namabarang']) ? $_GET['namabarang'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="vendor">Pilih Vendor:</label>
                                <select name="vendor" id="vendor" class="form-control">
                                    <option value="">Pilih Vendor</option>
                                    <?php
                                    $selected_vendor = isset($_GET['vendor']) ? $_GET['vendor'] : '';
                                    $result = mysqli_query($conn, "SELECT DISTINCT vendor FROM katalog WHERE idtipe = '$idtipe'");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $vendor_name = $row['vendor'];
                                        $selected = ($selected_vendor == $vendor_name) ? 'selected' : '';
                                        echo '<option value="' . $vendor_name . '" ' . $selected . '>' . $vendor_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="min_harga">Harga Minimum:</label>
                                    <input type="number" name="minharga" id="min_harga" class="form-control" placeholder="Minimum" value="<?= isset($_GET['minharga']) ? $_GET['minharga'] : ''; ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="max_harga">Harga Maksimum:</label>
                                    <input type="number" name="maxharga" id="max_harga" class="form-control" placeholder="Maksimum" value="<?= isset($_GET['maxharga']) ? $_GET['maxharga'] : ''; ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-danger btn-block" id="resetFilter" name="resetfilter">Reset Filter</button>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-secondary btn-block" name="caribarang">Cari</button>
                                </div>
                            </div>
                        </div>
                    </form>


                </div>
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