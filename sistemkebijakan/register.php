<?php
session_start();

//koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "siska");

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];

    // Validasi apakah ada field yang kosong
    if (empty($username) || empty($password) || empty($verify_password)) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => ' Harap isi data dahulu.'
        ];
        header('location: register.php');
        exit();
    }

    // Validasi apakah password cocok dengan verifikasi password
    if ($password !== $verify_password) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => ' Password tidak sesuai, mohon isi password dengan benar.'
        ];
        header('location: register.php');
        exit();
    }

    // Lakukan validasi apakah username sudah ada
    $periksausername = "SELECT * FROM user WHERE username = '$username'";
    $check_result = mysqli_query($conn, $periksausername);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => ' Mohon maaf username sudah dipakai.'
        ];
        header('location: register.php');
        exit();
    }

    // Jika validasi berhasil, masukkan data ke database
    $insert_query = "INSERT INTO user (username, password, role) VALUES ('$username', '$password', 'user')";
    $result = mysqli_query($conn, $insert_query);

    if ($result) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => ' Berhasil Membuat Akun ! Silahkan ke halaman login'
        ];
    } else {
        // Redirect ke halaman registrasi dengan notifikasi
        header('location: register.php');
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
    <title>SISKA | Register</title>
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
                        <div class="col-lg-6">
                            <div class="card shadow-lg border-0 rounded-lg mt-5 w-100 mx-auto">
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <img src="assets/img/siskae.png" alt="" width="300px">
                                    </div>
                                    <?php
                                    if (isset($_SESSION['notification'])) {
                                        echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show" role="alert">
                                              <i class="fas fa-info-circle"></i>' . $_SESSION['notification']['message'] . '
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
                                        <div class="form-group row">
                                            <div class="col-md-6"> <!-- Baris 1 -->
                                                <label class="small mb-1" for="inputPassword">Password</label>
                                                <input class="form-control py-4" name="password" id="inputPassword" type="password" placeholder="Password" />
                                            </div>
                                            <div class="col-md-6"> <!-- Baris 2 -->
                                                <label class="small mb-1" for="inputVerifyPassword">Verifikasi Password</label>
                                                <input class="form-control py-4" name="verify_password" id="inputVerifyPassword" type="password" placeholder="Verifikasi password" />
                                            </div>
                                        </div>
                                        <div class="form-group d-flex justify-content-center mt-4 mb-0">
                                            <button class="btn btn-primary" name="register">Register</button>
                                        </div>
                                    </form>
                                    <div class="mt-4 text-center">
                                        <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
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