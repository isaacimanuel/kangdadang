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

            <!-- Icon "Kelola Akun" seharusnya di dalam link utama -->
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-lock"></i></div>
                Kelola Akun
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>

            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="adminkatalog.php">
                        <i class="fas fa-user-cog"></i>&nbsp; Kelola Admin
                    </a>
                    <a class="nav-link" href="userkatalog.php">
                        <i class="fas fa-user"></i>&nbsp; Kelola User
                    </a>
                    <a class="nav-link" href="vendor.php">
                        <i class="fas fa-user"></i>&nbsp; Kelola Vendor
                    </a>
                </nav>
            </div>
        </div>
    </div>
</nav>