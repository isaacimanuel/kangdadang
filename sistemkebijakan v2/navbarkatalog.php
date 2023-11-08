<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand ps-2" href="homekatalog.php">
        <img src="../assets/img/siekalogo.png" alt="" width="40px">&nbsp; S I E K A
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0"></form>

    <!-- Navbar Dropdown Menu -->
    <ul class="navbar-nav ml-auto ml-md-0">
        <?php
        $id = $_SESSION['id'];
        $query = mysqli_query($conn, "SELECT * from user_katalog WHERE id = '$id'");
        $data = mysqli_fetch_array($query);
        $namauser = $data['nama'];
        $role = $_SESSION['role'];
        if ($role == 'user_katalog' || $role == 'superadmin_katalog') {
        ?>
            <li class="nav-item">
                <a class="nav-link" id="userDropdown" href="daftarkeranjang.php" role="button">
                    <i class="fas fa-shopping-cart fa-fw"></i>
                </a>
            </li>
        <?php } ?>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
                <?= $namauser; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <?php if ($role == 'suplier') { ?>
                    <a class="dropdown-item" href="profil.php">Profil Anda</a>
                <?php } else { ?>
                    <a class="dropdown-item" href="../profil.php">Profil Anda</a>
                <?php } ?>
                <a class="dropdown-item" href="../logout.php">Logout</a>
            </div>
        </li>
    </ul>
</nav>