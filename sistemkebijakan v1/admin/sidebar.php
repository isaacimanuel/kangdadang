<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <!-- Sidebar Menu Heading -->
            <div class="sb-sidenav-menu-heading">Menu</div>

            <!-- Sidebar Links -->
            <a class="nav-link" href="home.php">
                <i class="fas fa-home"></i>&nbsp; Home
            </a>
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                Daftar Dokumen
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                        <i class="fas fa-balance-scale"></i>&nbsp; Kebijakan
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="index.php"><i class="fas fa-building"></i>&nbsp; Direktorat</a>
                            <a class="nav-link" href="fakultas.php"><i class="fas fa-university"></i>&nbsp; Fakultas</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="standar.php">
                        <i class="fas fa-book"></i>&nbsp; Standar
                    </a>
                    <a class="nav-link" href="prosedur.php">
                        <i class="fas fa-cogs"></i>&nbsp; Prosedur
                    </a>
                    <a class="nav-link" href="instruksi.php">
                        <i class="fas fa-building"></i>&nbsp; Instruksi Kerja
                    </a>
                </nav>
            </div>
            <a class="nav-link" href="user.php">
                <i class="fas fa-user"></i>&nbsp; Kelola User
            </a>
        </div>
    </div>
    <div class="sb-sidenav-footer" style="background-color: transparent;">
        <a class="nav-link" href="tipe.php">
            <i class="fas fa-shopping-bag"></i>&nbsp; Katalog
        </a>
    </div>
</nav>