<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Menu</div>
            <a class="nav-link" href="homekatalog.php">
                <i class="fas fa-home"></i>&nbsp; Beranda
            </a>
            <a class="nav-link" href="tipe.php">
                <i class="fas fa-file-signature"></i>&nbsp; E - Katalog
            </a>
            <a class="nav-link" href="userdetailpesan.php">
                <i class="fas fa-info-circle"></i>&nbsp; Detail Pesan
            </a>
            <a class="nav-link" href="daftarriwayat.php">
                <i class="fas fa-history"></i>&nbsp; Riwayat Pesanan
            </a>
            <?php $idvendor = $_SESSION['id']; ?>
            <a class="nav-link" href="profilvendor.php?id=<?= $idvendor; ?>">
                <i class="fas fa-user"></i>&nbsp; Profil Vendor
            </a>
        </div>
    </div>
</nav>