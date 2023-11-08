<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'suplier') {
    // Konten halaman superadmin
    $idvendor = $_GET['id'];
    $query_profil = "SELECT * FROM user_katalog WHERE role = 'suplier' AND id = '$idvendor'";
    $ambilsemuadataprofil = mysqli_query($conn, $query_profil);
    $data = mysqli_fetch_array($ambilsemuadataprofil);
    if ($data) {
        $namavendor = $data['nama'];
        $alamat = $data['alamat'];
        $username = $data['username'];
        $password = $data['password'];
        $email = $data['email'];
        $deskripsivendor = $data['deskripsi'];
    }

    $query = "SELECT * FROM katalog WHERE idvendor = '$idvendor'";

    $ambilsemuadatakatalog = mysqli_query($conn, $query);

?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SIEKA | Profil Vendor - Vendor</title>
        <link rel="icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siekalogo.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .card {
                background-color: #DFE0E2;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
                transition: transform 0.2s;
                margin-bottom: 20px;
                overflow: hidden;
                height: 260px;
                /* Ubah sesuai dengan kebutuhan Anda */
                width: 230px;
                /* Ubah sesuai dengan kebutuhan Anda */
                display: flex;
                flex-direction: column;
            }


            .card:hover {
                transform: scale(1.01);
            }

            .card-header img {
                width: 100%;
                /* Atur lebar gambar agar mengisi seluruh area card-header */
                height: auto;
                /* Biarkan tinggi gambar mengikuti proporsi aslinya */
                height: 80px;
                /* Atur tinggi maksimum gambar */
                object-fit: contain;
                /* Sesuaikan proporsi gambar */
            }

            .card-content {
                padding: 15px;
                text-align: center;
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                overflow: auto;
            }

            .card-title {
                font-size: 1rem;
                /* Menyesuaikan ukuran font */
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
                /* Mengurangi jarak bawah */
            }

            .card-buttons {
                display: flex;
                justify-content: center;
                gap: 5px;
                /* Mengurangi jarak antara tombol */
            }

            .btn-edit,
            .btn-delete {
                font-size: 12px;
                /* Mengurangi ukuran font tombol */
            }

            .topButton {
                font-size: 14px;
                /* Mengurangi ukuran font tombol top */
            }

            .row {
                gap: 55px;
                margin-left: -20px;
                margin-right: -20px;
            }

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

            .header .icon {
                margin-left: 5px;
                /* Jarak antara ikon */
                float: right;
            }

            .card-text {
                color: black;
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
                        <?php
                        if (isset($namavendor) && isset($idvendor)) {
                            echo '<h1> ' . $namavendor . '
                            <div class="btn-group" role="group" aria-label="Basic example" style="float: right;">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                            </div>
                            <button type="button" class="btn btn-info mr-1" data-toggle="modal" data-target="#info" style="float: right;">
                                Info Vendor
                            </button>
                            </h1>';
                        } else {
                            echo '<h1> Profil Vendor </h1>';
                        } ?>
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
                        <?php if ($_SESSION['id'] == $_GET['id']) { ?>
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                Tambah Data Katalog
                            </button>
                        <?php  }
                        if (mysqli_num_rows($ambilsemuadatakatalog) > 0) { ?>


                            <div class="card-body mt-3">
                                <div class="row">
                                    <?php

                                    while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
                                        $idproduk = $data['idproduk'];
                                        $namabarang = $data['namabarang'];
                                        $foto = $data['foto'];
                                        $id = $data['id'];
                                        $vendor = $data['vendor'];
                                        $harga = $data['harga'];
                                        $jumlah = $data['jumlah'];
                                        $deskripsi = $data['deskripsi'];
                                        $merk = $data['merk'];

                                    ?>
                                        <div class="col-md-2 mb-2">
                                            <a href="detailcard.php?id=<?= $id; ?>" class="card-link">
                                                <?php if ($_SESSION['id'] == $_GET['id']) { ?>
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <img src="../assets/katalog/<?= $foto; ?>" class="card-img-top">
                                                        </div>
                                                        <div class="card-content">
                                                            <p class="card-title mb-1"><?= $namabarang; ?></p>
                                                            <p class="card-text mb-1 vendor" style="display:none;"><?= $vendor; ?></p>
                                                            <p class="card-text mb-1 merk"><?= $merk; ?></p>
                                                            <p class="card-text mb-1 harga">Rp. <?= number_format($data['harga'], 0, ',', '.'); ?></p>

                                                            <div class="card-buttons">
                                                                <a type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?= $id; ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?= $id; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        <?php  } else { ?>
                                                            <div class="card" style="height: 220px;">
                                                                <div class="card-header">
                                                                    <img src="../assets/katalog/<?= $foto; ?>" class="card-img-top">
                                                                </div>
                                                                <div class="card-content">
                                                                    <p class="card-title mb-1"><?= $namabarang; ?></p>
                                                                    <p class="card-text mb-1 vendor" style="display:none;"><?= $vendor; ?></p>
                                                                    <p class="card-text mb-1 merk"><?= $merk; ?></p>
                                                                    <p class="card-text harga">Rp. <?= number_format($data['harga'], 0, ',', '.'); ?></p>
                                                                <?php } ?>
                                                                </div>
                                                            </div>
                                            </a>
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
                                                            <input type="file" name="foto" accept=".jpg,.png,.jpeg" class="form-control mb-2">
                                                            <small class="text-muted">Nama Barang</small>
                                                            <input type="text" name="namabarang" value="<?= $namabarang; ?>" placeholder="Nama Barang" class="form-control mb-2" required>
                                                            <small class="text-muted">Merk</small>
                                                            <input type="text" class="form-control mb-2" name="merk" placeholder="Merk" value="<?= $merk; ?>" required>
                                                            <small class="text-muted">Deskripsi Barang</small>
                                                            <textarea class="form-control mb-2" id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi Barang" required><?php echo $deskripsi; ?></textarea>
                                                            <div class="form-row mb-2">
                                                                <div class="form-group col-md-6">
                                                                    <small class="text-muted">Harga Barang</small>
                                                                    <input type="number" name="harga" value="<?= $harga; ?>" placeholder="Harga Barang" class="form-control" required>
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <small class="text-muted">Stok Barang</small>
                                                                    <input type="number" name="jumlah" placeholder="Jumlah Barang" value="<?= $jumlah; ?>" class="form-control" required>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="id" value="<?= $id; ?>">
                                                            <input type="hidden" name="vendor" value="<?= $vendor; ?>">
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
                                                            <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                                                            <br>
                                                            <br>
                                                            <button type="submit" class="btn btn-danger" name="hapusdatabarangvendor" style="width: 100%;">Hapus</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                <?php
                                    }
                                } else {
                                    echo '<div style="text-align: center; opacity:0.8;">Tidak ada data</div>';
                                }
                                ?>
                                <div id="noDataMessage" style="display: none; text-align: center; opacity:0.8;">Tidak ada data</div>
                                <button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fas fa-arrow-up"></i></button>
                </main>
                <?php include('../footer.php'); ?>
            </div>
        </div>
        <?php include('../script.php') ?>

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
                            <input type="hidden" name="idvendor" value="<?= $idvendor; ?>">
                            <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                            <input type="hidden" name="idtipe" value="<?= $idtipe; ?>">
                            <input type="hidden" name="vendor" value="<?= $namavendor; ?>">
                            <small class="text-muted">Gambar Barang</small>
                            <input type="file" name="foto" accept=".jpg,.png,.jpeg," class="form-control mb-2" required>
                            <small class="text-muted">Nama Barang</small>
                            <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control mb-2" required value="<?= $namaValue; ?>">
                            <small class="text-muted">Pilih Kategori Produk</small>
                            <select name="idtipe" id="idtipe" class="form-control" required>
                                <option value="" disabled selected>Pilih kategori</option>
                                <?php
                                // Ambil daftar kategori dari database
                                $result = mysqli_query($conn, "SELECT * FROM tipe");
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['idtipe'] . '">' . $row['namatipe'] . '</option>';
                                }
                                ?>
                            </select>
                            <small class="text-muted">Pilih Produk</small>
                            <select name="idproduk" id="idproduk" class="form-control mb-2" required>
                            </select>
                            <small class="text-muted">Merk</small>
                            <input type="text" class="form-control mb-2" name="merk" placeholder="Merk" required value="<?= $merkValue; ?>">
                            <small class="text-muted">Deskripsi</small>
                            <textarea class="form-control mb-2" id="deskripsi " name="deskripsi" rows="3" placeholder="Deskripsi Barang" required><?php echo $deskripsiValue; ?></textarea>
                            <div class="form-row mb-2">
                                <div class="form-group col-md-6">
                                    <small class="text-muted">Harga Barang</small>
                                    <input type="number" name="harga" placeholder="Harga Barang" class="form-control" required value="<?= $hargaValue; ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <small class="text-muted">Stok Barang</small>
                                    <input type="number" name="jumlah" placeholder="Jumlah Barang" class="form-control" required value="<?= $jumlahValue; ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" name="addnewdatabarang" style="width: 100%;">Kirim</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- info vendor Modal -->
        <div class="modal fade" id="info">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><?= $namavendor; ?></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <small>Alamat Vendor</small>
                            <input type="text" class="form-control mb-3" value="<?= $alamat; ?>" readonly>
                            <small>Email Vendor</small>
                            <input type="email" class="form-control mb-3" value="<?= $email; ?>" readonly>
                            <small>Deskripsi Vendor</small>
                            <textarea class="form-control" rows="5" readonly><?= $deskripsivendor; ?></textarea>
                    </form>

                </div>
            </div>
        </div>


    </body>


    </html>


<?php
} else {
    header("location:../loginkatalog.php");
    exit();
}
?>