<?php
require "../function.php";

if (isset($_SESSION['log']) && $_SESSION['role'] === 'suplier') {
    // Konten halaman user
    $idproduk = $_GET['idproduk'];

    $result_produk = mysqli_query($conn, "SELECT namaproduk, idtipe FROM produk WHERE idproduk = '$idproduk'");
    $data_produk = mysqli_fetch_assoc($result_produk);

    if ($data_produk) {
        $idtipe = $data_produk['idtipe'];
        $namaproduk = $data_produk['namaproduk'];

        $result_tipe = mysqli_query(
            $conn,
            "SELECT namatipe, idtipe FROM tipe WHERE idtipe = '$idtipe'"
        );
        $data_tipe = mysqli_fetch_assoc($result_tipe);

        if ($data_tipe) {
            $idtipe = $data_tipe['idtipe'];
            $namatipe = $data_tipe['namatipe'];
        }
    }

    $namabarang = isset($_GET['namabarang']) ? $_GET['namabarang'] : '';
    $vendor = isset($_GET['vendor']) ? $_GET['vendor'] : '';
    $merk = isset($_GET['merk']) ? $_GET['merk'] : '';
    $minharga = isset($_GET['minharga']) ? $_GET['minharga'] : '';
    $maxharga = isset($_GET['maxharga']) ? $_GET['maxharga'] : '';

    $filter_query = "SELECT * FROM katalog WHERE idproduk = '$idproduk'";

    if ($namabarang !== '') {
        $filter_query .= " AND namabarang LIKE '%$namabarang%'";
    }

    if ($vendor !== '') {
        $filter_query .= " AND vendor = '$vendor'";
    }

    if ($merk !== '') {
        $filter_query .= " AND merk = '$merk'";
    }

    if ($minharga !== '') {
        $filter_query .= " AND harga >= $minharga";
    }

    if ($maxharga !== '') {
        $filter_query .= " AND harga <= $maxharga";
    }

    $ambilsemuadatakatalog = mysqli_query($conn, $filter_query);

?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SIEKA | Barang E-Katalog - Vendor</title>
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
                height: 230px;
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
                transition: all 0.5s ease;
            }

            .card-title {
                font-size: 1rem;
                /* Menyesuaikan ukuran font */
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
                /* Mengurangi jarak bawah */
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

            .card-text {
                color: black;
            }

            .vendor,
            .merk {
                margin-top: 0.5em;
                /* Menambahkan jarak atas */
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
                        <?php if (isset($namaproduk) && isset($idproduk)) { ?>
                            <h1 class="header">
                                Daftar <?= $namaproduk; ?> E-Katalog
                                <div class="btn-group" role="group" aria-label="Basic example" style="float: right;">
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                                </div>
                                <button class="btn btn-secondary icon mr-1" style="float: right;" data-toggle="modal" data-target="#filter<?= $idproduk; ?>"><i class="fas fa-filter"></i></button>
                            </h1>
                        <?php } else {
                            echo '<h1 class="header">Daftar Barang E-Katalog</h1>';
                        } ?>
                        <div class="navigation">
                            <a href="tipe.php">Kategori E-katalog</a>
                            <span style="font-size: 12px;"><i class="fas fa-chevron-right"></i></span>
                            <?php
                            if (isset($namatipe) && isset($idtipe)) {
                                echo '<a href="produk.php?idtipe=' . $idtipe . '">' . $namatipe . '</a>';
                            } else {
                                echo 'Tidak ada data';
                            }
                            ?>
                            <span style="font-size: 12px;"><i class="fas fa-chevron-right"></i></span>
                            <?php
                            if (isset($namaproduk) && isset($idproduk)) {
                                echo '<a href="katalog.php?idproduk=' . $idproduk . '">' . $namaproduk . '</a>';
                            } else {
                            }

                            ?>
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
                        if (mysqli_num_rows($ambilsemuadatakatalog) > 0) {
                        ?>
                            <div class="card-body">
                                <div class="row">
                                    <?php
                                    while ($data = mysqli_fetch_array($ambilsemuadatakatalog)) {
                                        $idproduk = $data['idproduk'];
                                        $namabarang = $data['namabarang'];
                                        $foto = $data['foto'];
                                        $id = $data['id'];
                                        $vendor = $data['vendor'];
                                        $harga = number_format($data['harga'], 0, ',', '.');
                                        $jumlah = $data['jumlah'];
                                        $deskripsi = $data['deskripsi'];
                                        $merk = $data['merk'];
                                    ?>
                                        <div class="col-md-2 mb-2">
                                            <a href="detailcard.php?id=<?= $id; ?>" class="card-link">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <img src="../assets/katalog/<?= $foto; ?>" class="card-img-top">
                                                    </div>
                                                    <div class="card-content">
                                                        <p class="card-title mb-1"><?= $namabarang; ?></p>
                                                        <p class="card-text mb-1 vendor " style="display:none;"><?= $vendor; ?></p>
                                                        <p class="card-text mb-1 merk"><?= $merk; ?></p>
                                                        <p class="card-text mb-1 harga">Rp. <?= $harga; ?></p>
                                                    </div>
                                                </div>
                                            </a>
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
        <!-- Filter Modal -->
        <div class="modal fade" id="filter<?= $idproduk; ?>">
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
                            <input type="hidden" name="idproduk" value="<?= $idproduk; ?>">
                            <div class="form-group">
                                <label for="barang">Cari berdasarkan nama barang:</label>
                                <input type="text" name="namabarang" id="barang" class="form-control" placeholder="Nama Barang" value="<?= isset($_GET['namabarang']) ? $_GET['namabarang'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="merk">Pilih Merk:</label>
                                <select name="merk" id="merk" class="form-control">
                                    <option value="">Pilih Merk</option>
                                    <?php
                                    $selected_merk = isset($_GET['merk']) ? $_GET['merk'] : '';
                                    $result = mysqli_query($conn, "SELECT DISTINCT merk FROM katalog WHERE idproduk = '$idproduk'");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $merk_name = $row['merk'];
                                        $selected = ($selected_merk == $merk_name) ? 'selected' : '';
                                        echo '<option value="' . $merk_name . '" ' . $selected . '>' . $merk_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="vendor">Pilih Vendor:</label>
                                <select name="vendor" id="vendor" class="form-control">
                                    <option value="">Pilih Vendor</option>
                                    <?php
                                    $selected_vendor = isset($_GET['vendor']) ? $_GET['vendor'] : '';
                                    $result = mysqli_query($conn, "SELECT DISTINCT vendor FROM katalog WHERE idproduk = '$idproduk'");
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
    header("location:../loginkatalog.php");
    exit();
}
?>