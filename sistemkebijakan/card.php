<?php
$ambilsemuadatakebijakan = mysqli_query($conn, "SELECT * FROM kebijakan WHERE status = 1 ORDER BY tanggal DESC LIMIT 1");
$data = mysqli_fetch_array($ambilsemuadatakebijakan);

if ($data) { // Cek apakah ada data dengan status 1
    $namakebijakan = $data['namakebijakan'];
    $deskripsi = $data['deskripsi'];
    $file = $data['file'];
    $tanggal = $data['tanggal'];
?>
    <!-- Card pertama dengan hasil dari query -->

    <div class="card ml-5 mt-3 mb-3" style="width: 20rem;height: 27rem;box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
        <img src="../assets/img/4.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title"><?= $namakebijakan; ?></h5>
            <p class="card-text"><?= $deskripsi; ?></p>
            <div class="baca">
                <a href="../assets/kebijakan/pdf/<?= $file; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
            </div>
        </div>
    </div>
<?php
}

$ambilsemuadatainstruksi = mysqli_query($conn, "SELECT * FROM instruksi WHERE status = 1 ORDER BY tanggal DESC LIMIT 1");
$data = mysqli_fetch_array($ambilsemuadatainstruksi);

if ($data) { // Cek apakah ada data dengan status 1
    $namainstruksi = $data['namainstruksi'];
    $deskripsi = $data['deskripsi'];
    $file = $data['file'];
    $tanggal = $data['tanggal'];
?>
    <!-- Card selanjutnya -->
    <div class="card ml-5 mt-3 mb-3" style="width: 20rem;height: 27rem;box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
        <img src="../assets/img/2.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title"><?= $namainstruksi; ?></h5>
            <p class="card-text"><?= $deskripsi; ?></p>
            <div class="baca">
                <a href="../assets/instruksi/pdf/<?= $file; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
            </div>
        </div>
    </div>
<?php
}

$ambilsemuadataprosedur = mysqli_query($conn, "SELECT * FROM prosedur WHERE status = 1 ORDER BY tanggal DESC LIMIT 1");
$data = mysqli_fetch_array($ambilsemuadataprosedur);

if ($data) { // Cek apakah ada data dengan status 1
    $namaprosedur = $data['namaprosedur'];
    $deskripsi = $data['deskripsi'];
    $file = $data['file'];
    $tanggal = $data['tanggal'];
?>
    <div class="card ml-5 mt-3 mb-3" style="width: 20rem;height: 27rem;box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);">
        <img src="../assets/img/6.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title"><?= $namaprosedur; ?></h5>
            <p class="card-text"><?= $deskripsi; ?></p>
            <div class="baca">
                <a href="../assets/prosedur/pdf/<?= $file; ?>" class="btn btn-primary" target="_blank">Baca Selengkapnya</a>
            </div>
        </div>
    </div>
<?php
}
?>