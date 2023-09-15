<?php
session_start();


//koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "siska");

//cek login 
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //cocokan dengan database
    $cekdatabase = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' AND password = '$password'");

    //hitung jumlah data
    $hitung = mysqli_num_rows($cekdatabase);

    if ($hitung > 0) {
        $user = mysqli_fetch_assoc($cekdatabase); // Ambil data pengguna

        $_SESSION['log'] = "True";
        $_SESSION['role'] = $user['role'];

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
            'message' => ' Username atau password salah !'
        ];
        header('location:index.php');
        exit();
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
    } else {
        header('location:superadmin/home.php');
        exit();
    }
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
    <title>SISKA | LOGIN </title>
    <link rel="icon" href="assets/img/logosiska.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/img/logosiska.png" type="image/x-icon">
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4">
                            <div class="card shadow-lg border-0 rounded-lg mt-5 w-100">
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <img src="assets/img/siskae.png" alt="" width="250px">
                                    </div>
                                    <?php
                                    if (isset($_SESSION['notification'])) {
                                        echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show" role="alert">
                                        <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>';
                                        unset($_SESSION['notification']);
                                    }
                                    ?>
                                    <form method="post">
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputUsername">Username</label>
                                            <input class="form-control py-4" name="username" id="inputUsername" type="text" placeholder="Username" />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Password" />
                                        </div>
                                        <div class="form-group d-flex justify-content-center mt-4 mb-0">
                                            <button class="btn btn-primary" name="login">Login</button>
                                        </div>
                                        <input type="hidden" name="role" value="<?= $role; ?>">
                                    </form>
                                    <div class="mt-4 text-center">
                                        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>

</html>