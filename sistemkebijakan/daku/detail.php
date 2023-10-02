<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'daku') {
    // Mendapatkan ID dari URL
    $id = $_GET['id'];

    // Mengambil data proposal berdasarkan ID
    $get = mysqli_query($conn, "SELECT * FROM proposal WHERE id ='$id'");
    $fetch = mysqli_fetch_assoc($get);
    $get1 = mysqli_query($conn, "SELECT * from indikator_kinerja where proposal_id = $id");
    $fetch1 = mysqli_fetch_assoc($get1);
    $get2 = mysqli_query($conn, "SELECT * from rencana_anggaran where proposal_id = $id");

    // Simpan data ke dalam array
    $rencana_anggaran = [];
    while ($row = mysqli_fetch_assoc($get2)) {
        $rencana_anggaran[] = $row;
    }

    if ($fetch && $fetch1) {
        // Menyimpan nilai-nilai dari database ke variabel
        $namakegiatan = $fetch['nama_kegiatan'];
        $tujuankegiatan = $fetch['tujuan_kegiatan'];
        $dasarpelaksanaan = $fetch['dasar_pelaksanaan'];
        $waktupelaksanaan = $fetch['waktu_pelaksanaan'];
        $waktuselesai = $fetch['waktu_selesai'];
        $tempatpelaksanaan = $fetch['tempat_pelaksanaan'];
        $pesertakegiatan = $fetch['peserta_kegiatan'];
        $targetluaran = $fetch['target_luaran'];
        $totalanggaran = $fetch['total_anggaran'];
        $unitpelaksana = $fetch['unit_pelaksana'];
        $sumberdana = $fetch['sumber_dana'];
        $penutup = $fetch['penutup'];
        $lampiran = $fetch['lampiran'];

        $kodeiks = $fetch1['kode_iks'];
        $iks = $fetch1['iks'];
        $rasional = $fetch1['rasional'];
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
        <title>SISKA | Detail Proposal - DAKU</title>
        <link rel="icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link rel="shortcut icon" href="../assets/img/siskalogo.png" type="image/x-icon">
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../css/tambah.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <style>
            .small-placeholder::placeholder {
                font-size: 14px;
            }

            .small-text {
                font-size: 15px;
                /* Atur ukuran teks sesuai kebutuhan */
            }

            .edit .btn {
                width: 100px;
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
                        <h1>Detail Proposal </h1>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="namakegiatan">Nama Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" id="namaKegiatan" name="namakegiatan" placeholder="Masukkan Nama Kegiatan" value="<?= $namakegiatan; ?>">
                                        </div>
                                        <div class=" form-group">
                                            <label for="tujuan">Tujuan Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" id="tujuan" name="tujuankegiatan" placeholder="Tujuan Kegiatan" value="<?= $tujuankegiatan; ?>">
                                        </div>
                                        <div class=" form-group">
                                            <label for="dasarPelaksanaan">Dasar Pelaksanaan Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" id="dasarPelaksanaan" name="dasarpelaksanaan" placeholder="Dasar Pelaksanaan Kegiatan" value="<?= $dasarpelaksanaan; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="indikator">Indikator Kinerja Strategis Rujukan</label>
                                            <table class="table" id="indikator">
                                                <thead>
                                                    <tr>
                                                        <th>Kode IKS</th>
                                                        <th>IKS</th>
                                                        <th>Rasional</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input type="text" class="form-control small-placeholder" name="kodeiks" placeholder="Kode IKS" style="font-size: 14px;" value="<?= $kodeiks; ?>"></td>
                                                        <td>
                                                            <input type="text" class="form-control small-placeholder" name="iks" placeholder="Deskripsi Indikator" style="font-size: 14px;" value="<?= $iks; ?>">
                                                        </td>
                                                        <td><input type="text" class="form-control small-placeholder" name="rasional" placeholder="Rasional" style="font-size: 14px;" value="<?= $rasional; ?>"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="gambaranUmum">Gambaran Umum Kegiatan</label>
                                            <div class="form-group row">
                                                <label for="waktupelaksanaan" class="col-sm-2 col-form-label small-text">Waktu Pelaksanaan</label>
                                                <div class="col-sm-10">
                                                    <input type="datetime-local" class="form-control small-placeholder" name="waktupelaksanaan" id="waktupelaksanaan" placeholder="Waktu Pelaksanaan" value="<?= $waktupelaksanaan; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="waktuselesai" class="col-sm-2 col-form-label small-text">Waktu Selesai</label>
                                                <div class="col-sm-10">
                                                    <input type="datetime-local" class="form-control small-placeholder" name="waktuselesai" id="waktupelaksanaan" placeholder="Waktu Pelaksanaan" value="<?= $waktuselesai; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tempatpelaksanaan" class="col-sm-2 col-form-label small-text">Tempat Pelaksanaan</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control small-placeholder" name="tempatpelaksanaan" id="tempatpelaksanaan" placeholder="Tempat Pelaksanaan" value="<?= $tempatpelaksanaan; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="peserta" class="col-sm-2 col-form-label small-text">Peserta Kegiatan</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control small-placeholder" name="pesertakegiatan" id="peserta" placeholder="Peserta Kegiatan" value="<?= $pesertakegiatan; ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="targetLuaran">Target Luaran Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" id="targetLuaran" name="targetluaran" placeholder="Target Luaran Kegiatan" value="<?= $targetluaran; ?>">
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="rencanaAnggaran">Rencana anggaran</label>
                                            <table class="table" id="rencana">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Jenis Pengeluaran</th>
                                                        <th>Satuan</th>
                                                        <th>Jumlah</th>
                                                        <th>Harga Satuan (USD)</th>
                                                        <th>Kurs (Rp)</th>
                                                        <th>Kode Anggaran</th>
                                                        <th>Subtotal</th>
                                                        <th>Aksi</th> <!-- Tambah kolom aksi untuk tombol Hapus -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($rencana_anggaran as $key => $row) : ?>
                                                        <tr>
                                                            <td><input type="number" class="form-control small-placeholder" name="no[]" value="<?= $key + 1; ?>"></td>
                                                            <td><input type="text" class="form-control small-placeholder" name="jenis[]" placeholder="Jenis Pengeluaran" value="<?= $row['jenis_pengeluaran']; ?>"></td>
                                                            <td><input type="text" class="form-control small-placeholder" name="satuan[]" placeholder="Satuan" value="<?= $row['satuan']; ?>"></td>
                                                            <td><input type="number" class="form-control small-placeholder" name="jumlah[]" placeholder="Jumlah" value="<?= $row['jumlah']; ?>"></td>
                                                            <td><input type="number" class="form-control small-placeholder" name="harga[]" placeholder="Harga Satuan (USD)" value="<?= $row['harga_satuan']; ?>"></td>
                                                            <td><input type="number" class="form-control small-placeholder" name="kurs[]" placeholder="Kurs (Rp)" value="<?= $row['kurs']; ?>"></td>
                                                            <td><input type="text" class="form-control small-placeholder" name="kode[]" placeholder="Kode Anggaran" value="<?= $row['kode_anggaran']; ?>"></td>
                                                            <td><input type="text" class="form-control small-placeholder" name="sub[]" placeholder="Sub Total" value="<?= $row['subtotal']; ?>"></td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger form-control" onclick="deleteRow(this)">
                                                                    <i class="fas fa-minus"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-primary ml-3" style="width: 40px;height: 40px; padding: 0;" onclick="addRow()">
                                                <i class="fas fa-plus" style="margin: auto;"></i>
                                            </button>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="totalanggaran">Total Anggaran Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" name="totalanggaran" placeholder="Total Anggaran Kegiatan" value="<?= $totalanggaran; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="unitPelaksana">Unit Pelaksana Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" name="unitpelaksana" placeholder="Unit Pelaksana Kegiatan" value="<?= $unitpelaksana; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="sumberDana">Sumber Dana</label>
                                            <input type="text" class="form-control small-placeholder" name="sumberdana" placeholder="Sumber Dana" value="<?= $sumberdana; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="penutup">Penutup</label>
                                            <a href="../assets/png/<?= $penutup; ?>" class="form-control" target="_blank"><?= $penutup; ?></a>
                                        </div>
                                        <div class="form-group">
                                            <label for="lampiran">Lampiran</label>
                                            <a href="../assets/pdf/<?= $lampiran; ?>" class="form-control" target="_blank"><?= $lampiran; ?></a>
                                        </div>
                                        <br>
                                        <div class="edit">
                                            <a href="proposal.php" class="btn btn-danger">Kembali</a>
                                        </div>
                                    </form>
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

    </html>

<?php
} else {
    header("location:../index.php");
    exit();
}
?>