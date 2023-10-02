<?php
require "../function.php";
require "../cek.php";
if (isset($_SESSION['log']) && $_SESSION['role'] === 'admin') {
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SISKA | Form - User</title>
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
                        <h1>Buat Proposal </h1>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="namakegiatan">Nama Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" id="namaKegiatan" name="namakegiatan" placeholder="Masukkan Nama Kegiatan">
                                        </div>
                                        <div class=" form-group">
                                            <label for="tujuan">Tujuan Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" id="tujuan" name="tujuankegiatan" placeholder="Tujuan Kegiatan">
                                        </div>
                                        <div class="form-group">
                                            <label for="dasarPelaksanaan">Dasar Pelaksanaan Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" id="dasarPelaksanaan" name="dasarpelaksanaan" placeholder="Dasar Pelaksanaan Kegiatan">
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
                                                        <td><input type="text" class="form-control small-placeholder" name="kodeiks" placeholder="Kode IKS" style="font-size: 14px;"></td>
                                                        <td>
                                                            <input type="text" class="form-control small-placeholder" name="iks" placeholder="Deskripsi Indikator" style="font-size: 14px;">
                                                        </td>
                                                        <td><input type="text" class="form-control small-placeholder" name="rasional" placeholder="Rasional" style="font-size: 14px;"></td>
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
                                                    <input type="datetime-local" class="form-control small-placeholder" name="waktupelaksanaan" id="waktupelaksanaan" placeholder="Waktu Pelaksanaan">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="waktuselesai" class="col-sm-2 col-form-label small-text">Waktu Selesai</label>
                                                <div class="col-sm-10">
                                                    <input type="datetime-local" class="form-control small-placeholder" name="waktuselesai" id="waktupelaksanaan" placeholder="Waktu Pelaksanaan">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tempatpelaksanaan" class="col-sm-2 col-form-label small-text">Tempat Pelaksanaan</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control small-placeholder" name="tempatpelaksanaan" id="tempatpelaksanaan" placeholder="Tempat Pelaksanaan">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="peserta" class="col-sm-2 col-form-label small-text">Peserta Kegiatan</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control small-placeholder" name="pesertakegiatan" id="peserta" placeholder="Peserta Kegiatan">
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="targetLuaran">Target Luaran Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" id="targetLuaran" name="targetluaran" placeholder="Target Luaran Kegiatan">
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
                                                    <tr>
                                                        <td><input type="number" class="form-control small-placeholder" name="no[]" value="1"></td>
                                                        <td><input type="text" class="form-control small-placeholder" name="jenis[]" placeholder="Jenis Pengeluaran"></td>
                                                        <td><input type="text" class="form-control small-placeholder" name="satuan[]" placeholder="Satuan"></td>
                                                        <td><input type="number" class="form-control small-placeholder" name="jumlah[]" placeholder="Jumlah"></td>
                                                        <td><input type="number" class="form-control small-placeholder" name="harga[]" placeholder="Harga Satuan (USD)"></td>
                                                        <td><input type="number" class="form-control small-placeholder" name="kurs[]" placeholder="Kurs (Rp)"></td>
                                                        <td><input type="text" class="form-control small-placeholder" name="kode[]" placeholder="Kode Anggaran"></td>
                                                        <td><input type="text" class="form-control small-placeholder" name="sub[]" placeholder="Sub Total"></td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger form-control" onclick="deleteRow(this)">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-primary ml-3" style="width: 40px;height: 40px; padding: 0;" onclick="addRow()">
                                                <i class="fas fa-plus" style="margin: auto;"></i>
                                            </button>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <label for="totalanggaran">Total Anggaran Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" name="totalanggaran" placeholder="Total Anggaran Kegiatan">
                                        </div>
                                        <div class="form-group">
                                            <label for="unitPelaksana">Unit Pelaksana Kegiatan</label>
                                            <input type="text" class="form-control small-placeholder" name="unitpelaksana" placeholder="Unit Pelaksana Kegiatan">
                                        </div>
                                        <div class="form-group">
                                            <label for="sumberDana">Sumber Dana</label>
                                            <input type="text" class="form-control small-placeholder" name="sumberdana" placeholder="Sumber Dana">
                                        </div>
                                        <div class="form-group">
                                            <label for="penutup">Penutup</label>
                                            <input type="file" accept=".png" class="form-control" name="penutup" id="penutup">
                                        </div>
                                        <div class="form-group">
                                            <label for="lampiran">Lampiran</label>
                                            <input type="file" accept=".pdf" class="form-control" name="lampiran" id="lampiran">
                                        </div>
                                        <br>
                                        <button type="submit" class="btn btn-primary" name="tambahproposal">Submit</button>
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