<?php
require 'function.php';

$id = $_SESSION['id'];
$query = mysqli_query($conn, "SELECT * FROM user_katalog WHERE id = $id");
$data = mysqli_fetch_array($query);
$username = $data['username'];
$password = $data['password'];
$nama = $data['nama'];
$email = $data['email'];
$role = $data['role'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="assets/img/siekalogo.png" type="image/x-icon">
    <link rel="shortcut icon" href="assets/img/siekalogo.png" type="image/x-icon">
    <title>Profil - <?= $nama; ?> </title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Profil Anda</h3>
                                </div>
                                <div class="card-body">
                                    <?php
                                    if (isset($_SESSION['notification'])) {
                                        echo '<div class="alert alert-' . $_SESSION['notification']['type'] . ' alert-dismissible fade show mt-3" role="alert" style="width: 100%;">
                            <i class="fas fa-info-circle"></i> ' . $_SESSION['notification']['message'] . '
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>';
                                        unset($_SESSION['notification']);
                                    }
                                    ?>
                                    <form method="post">
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="inputFirstName">Username</label>
                                                    <input class="form-control py-4" type="text" value="<?= $username; ?>" name="username" placeholder="Username" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="small mb-1" for="inputLastName">Nama</label>
                                                    <input class="form-control py-4" type="text" value="<?= $nama; ?>" name="nama" placeholder="Nama" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="inputEmailAddress">Email</label>
                                            <input class="form-control py-4" type="email" value="<?= $email; ?>" name="email" placeholder="Email" />
                                        </div>
                                        <div class="form-group">
                                            <label class="small mb-1" for="password">Password</label>
                                            <div class="input-group">
                                                <input class="form-control py-4" type="password" name="password" value="<?= $password; ?>" placeholder="Password" />
                                            </div>
                                        </div>
                                        <?php if ($role == 'admin_katalog' || $role == 'superadmin_katalog') { ?>
                                            <div class="form-group">
                                                <label class="small mb-1" for="role">role</label>
                                                <div class="input-group">
                                                    <input class="form-control py-4" type="text" name="role" value="<?= $role; ?>" placeholder="Password" readonly />
                                                </div>
                                            </div>
                                        <?php }  ?>
                                        <input type="hidden" name="id" value="<?= $id; ?>">
                                        <div class="form-group mt-4 mb-0"><button class="btn btn-primary btn-block" type="submit" name="updateuserkatalog">Simpan</button></div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="loginkatalog.php">Kembali</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php include('script.php') ?>
</body>

</html>