<?php
session_start();

//koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "siska");

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cocokan dengan database
    $cekdatabase = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' AND password = '$password'");

    // Hitung jumlah data
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        $user = mysqli_fetch_assoc($cekdatabase); // Ambil data pengguna
        $_SESSION['log'] = "True";
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['id'] = $user['id'];

        if ($user['role'] === 'admin') {
            header('location:admin/home.php'); // Arahkan ke halaman admin jika rolenya adalah admin
            exit();
        } else if ($user['role'] === 'user') {
            header('location:user/home.php'); // Arahkan ke halaman user jika rolenya adalah user
            exit();
        } else {
            header('location:superadmin/home.php');
            exit();
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username atau password salah.'
        ];
    }
}

// Cek apakah pengguna sudah login
if (isset($_SESSION['log'])) {
    // Jika sudah, arahkan sesuai role
    if ($_SESSION['role'] === 'admin') {
        header('location:admin/home.php');
        exit();
    } else if ($_SESSION['role'] === 'user') {
        header('location:user/home.php');
        exit();
    } else if ($_SESSION['role'] === 'superadmin') {
        header('location:superadmin/home.php');
        exit();
    }
}

if (isset($_POST['loginkatalog'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cocokan dengan database
    $cekdatabase = mysqli_query($conn, "SELECT * FROM user_katalog WHERE username = '$username' AND password = '$password'");

    // Hitung jumlah data
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        $user = mysqli_fetch_assoc($cekdatabase); // Ambil data pengguna
        $_SESSION['log'] = "True";
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['id'] = $user['id'];

        if ($user['role'] === 'admin_katalog') {
            header('location:admin/homekatalog.php'); // Arahkan ke halaman admin jika rolenya adalah admin
            exit();
        } else if ($user['role'] === 'user_katalog') {
            header('location:user/homekatalog.php'); // Arahkan ke halaman user jika rolenya adalah user
            exit();
        } else if ($user['role'] === 'suplier') {
            header('location:suplier/homekatalog.php'); // Arahkan ke halaman user jika rolenya adalah user
            exit();
        } else if ($user['role'] === 'superadmin_katalog') {
            header('location:superadmin/homekatalog.php');
            exit();
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username atau password salah.'
        ];
    }
}

// Cek apakah pengguna sudah login
if (isset($_SESSION['log'])) {
    // Jika sudah, arahkan sesuai role
    if ($_SESSION['role'] === 'admin_katalog') {
        header('location:admin/homekatalog.php');
        exit();
    } else if ($_SESSION['role'] === 'user_katalog') {
        header('location:user/homekatalog.php');
        exit();
    } else if ($_SESSION['role'] === 'suplier') {
        header('location:suplier/homekatalog.php');
        exit();
    } else {
        header('location:superadmin/homekatalog.php');
        exit();
    }
}
