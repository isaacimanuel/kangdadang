<?php
session_start();


//koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "siska");

//tambah data Kebijakan
if (isset($_POST['addnewdatakebijakan'])) {
    $namakebijakan = $_POST['namakebijakan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $file = $_FILES['file']['name'];


    // Validasi ukuran file
    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        // Validasi tipe file
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/kebijakan/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                // Simpan data ke database
                $tambahkebijakan = mysqli_query($conn, "INSERT INTO kebijakan (namakebijakan, deskripsi, file, tanggal, status) VALUES ('$namakebijakan', '$deskripsi', '$file', '$tanggal',0)");

                if ($tambahkebijakan) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil menambah Data !'
                    ];
                } else {
                    header('location:index.php');
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal mengunggah file !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Hanya file PDF yang diterima !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menambah Data, File pdf maks 2MB !'
        ];
    }
}

//hapus data kebijakan

if (isset($_POST['hapusdatakebijakan'])) {
    $idk = $_POST['idk'];

    // Ambil nama file PDF dari database
    $query = mysqli_query($conn, "SELECT file FROM kebijakan WHERE idkebijakan = '$idk'");
    $data = mysqli_fetch_assoc($query);
    $fileToDelete = $data['file'];

    // Hapus file PDF dari direktori
    $filePath = "../assets/kebijakan/pdf/" . $fileToDelete;
    if (unlink($filePath)) {
        // Hapus data kebijakan dari database
        $hapus = mysqli_query($conn, "DELETE FROM kebijakan WHERE idkebijakan = '$idk'");

        if ($hapus) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menghapus Data !'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menghapus Data !'
            ];
        }
    } else {
        echo 'Gagal menghapus file PDF.';
        header('location:index.php');
    }
}
//update info kebijakan
if (isset($_POST['updatedatakebijakan'])) {
    $idk = $_POST['idk'];
    $namakebijakan = $_POST['namakebijakan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $file = $_FILES['file']['name']; // Gunakan 'newfile' sebagai nama input file

    // Validasi ukuran file
    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        // Validasi tipe file
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/kebijakan/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                // Hapus file PDF lama jika perlu
                $ambilFileLama = mysqli_query($conn, "SELECT file FROM kebijakan WHERE idkebijakan = '$idk'");
                $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
                $fileLama = $dataFileLama['file'];
                $fileLamaPath = $uploadDir . $fileLama;

                if (file_exists($fileLamaPath)) {
                    unlink($fileLamaPath); // Hapus file PDF lama
                }

                // Update data kebijakan dengan file PDF baru dan tanggal_update
                $update = mysqli_query($conn, "UPDATE kebijakan SET namakebijakan = '$namakebijakan', deskripsi = '$deskripsi', file = '$file', tanggal = '$tanggal', status = 0 WHERE idkebijakan = '$idk'");

                if ($update) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Mengedit Data !'
                    ];
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal Mengedit Data !'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal mengunggah file !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Mengedit Data, Hanya file pdf yang diterima !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Mengedit Data, File pdf maks 2MB !'
        ];
    }
}
//tambah data standar
if (isset($_POST['addnewdatastandar'])) {
    $namastandar = $_POST['namastandar'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $file = $_FILES['file']['name'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            $uploadDir = "../assets/standar/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahstandar = mysqli_query($conn, "INSERT INTO standar (namastandar, deskripsi, file, tanggal, status) VALUES ('$namastandar', '$deskripsi', '$file', '$tanggal',0)");

                if ($tambahstandar) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal Menambah Data !'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Mengunggah File !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah Data, Hanya file pdf yang diterima !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menambah Data, File pdf maks 2MB !'
        ];
    }
}


//hapus data standar

if (isset($_POST['hapusdatastandar'])) {
    $ids = $_POST['ids'];

    // Ambil nama file PDF dari database
    $query = mysqli_query($conn, "SELECT file FROM standar WHERE idstandar = '$ids'");
    $data = mysqli_fetch_assoc($query);
    $fileToDelete = $data['file'];

    // Hapus file PDF dari direktori
    $filePath = "../assets/standar/pdf/" . $fileToDelete;
    if (unlink($filePath)) {
        // Hapus data standar dari database
        $hapus = mysqli_query($conn, "DELETE FROM standar WHERE idstandar = '$ids'");

        if ($hapus) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil menghapus Data !'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menghapus Data !'
            ];
        }
    } else {
        echo 'Gagal menghapus file PDF.';
        header('location:standar.php');
    }
}
//update info standar
if (isset($_POST['updatedatastandar'])) {
    $ids = $_POST['ids'];
    $namastandar = $_POST['namastandar'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $file = $_FILES['file']['name']; // Gunakan 'newfile' sebagai nama input file

    // Validasi ukuran file
    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        // Validasi tipe file
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/standar/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                // Hapus file PDF lama jika perlu
                $ambilFileLama = mysqli_query($conn, "SELECT file FROM standar WHERE idstandar = '$ids'");
                $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
                $fileLama = $dataFileLama['file'];
                $fileLamaPath = $uploadDir . $fileLama;

                if (file_exists($fileLamaPath)) {
                    unlink($fileLamaPath); // Hapus file PDF lama
                }

                // Update data standar dengan file PDF baru dan tanggal_update
                $update = mysqli_query($conn, "UPDATE standar SET namastandar = '$namastandar', deskripsi = '$deskripsi', file = '$file', tanggal = '$tanggal', status = 0 WHERE idstandar = '$ids'");

                if ($update) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Mengedit Data !'
                    ];
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal Mengedit Data !'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Mengunggah File !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Mengedit Data, Hanya file pdf yang diterima !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Mengedit Data, File pdf maks 2MB !'
        ];
    }
}

//tambah data prosedur
if (isset($_POST['addnewdataprosedur'])) {
    $namaprosedur = $_POST['namaprosedur'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $file = $_FILES['file']['name'];


    // Validasi ukuran file
    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        // Validasi tipe file
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/prosedur/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                // Simpan data ke database
                $tambahprosedur = mysqli_query($conn, "INSERT INTO prosedur (namaprosedur, deskripsi, file, tanggal, status) VALUES ('$namaprosedur', '$deskripsi', '$file', '$tanggal',0)");

                if ($tambahprosedur) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                } else {
                    header('location:prosedur.php');
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Mengunggah file !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah Data, hanya file pdf yang diterima !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menambah Data, File pdf maks 2MB !'
        ];
    }
}

//hapus data prosedur

if (isset($_POST['hapusdataprosedur'])) {
    $idp = $_POST['idp'];

    // Ambil nama file PDF dari database
    $query = mysqli_query($conn, "SELECT file FROM prosedur WHERE idprosedur = '$idp'");
    $data = mysqli_fetch_assoc($query);
    $fileToDelete = $data['file'];

    // Hapus file PDF dari direktori
    $filePath = "../assets/prosedur/pdf/" . $fileToDelete;
    if (unlink($filePath)) {
        // Hapus data prosedur dari database
        $hapus = mysqli_query($conn, "DELETE FROM prosedur WHERE idprosedur = '$idp'");

        if ($hapus) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil menghapus Data !'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menghapus Data !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menghapus Data !'
        ];
    }
}
//update info prosedur
if (isset($_POST['updatedataprosedur'])) {
    $idp = $_POST['idp'];
    $namaprosedur = $_POST['namaprosedur'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $file = $_FILES['file']['name']; // Gunakan 'newfile' sebagai nama input file

    // Validasi ukuran file
    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        // Validasi tipe file
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/prosedur/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                // Hapus file PDF lama jika perlu
                $ambilFileLama = mysqli_query($conn, "SELECT file FROM prosedur WHERE idprosedur = '$idp'");
                $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
                $fileLama = $dataFileLama['file'];
                $fileLamaPath = $uploadDir . $fileLama;

                if (file_exists($fileLamaPath)) {
                    unlink($fileLamaPath); // Hapus file PDF lama
                }

                // Update data prosedur dengan file PDF baru dan tanggal_update
                $update = mysqli_query($conn, "UPDATE prosedur SET namaprosedur = '$namaprosedur', deskripsi = '$deskripsi', file = '$file', tanggal = '$tanggal', status = 0 WHERE idprosedur = '$idp'");

                if ($update) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Mengedit Data !'
                    ];
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal Mengedit Data !'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Mengunggah file !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Mengedit Data, hanya file pdf yang diterima !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Mengedit Data, file pdf maks 2 MB !'
        ];
    }
}

//tambah data institusi
if (isset($_POST['addnewdatainstitusi'])) {
    $namainstitusi = $_POST['namainstitusi'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $file = $_FILES['file']['name'];


    // Validasi ukuran file
    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        // Validasi tipe file
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/institusi/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                // Simpan data ke database
                $tambahinstitusi = mysqli_query($conn, "INSERT INTO institusi (namainstitusi, deskripsi, file, tanggal, status) VALUES ('$namainstitusi', '$deskripsi', '$file', '$tanggal',0)");

                if ($tambahinstitusi) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                } else {
                    header('location:institusi.php');
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Mengunggah File !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Mengedit Data, hanya file pdf yang diterima !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menambah Data, file pdf maks 2 MB !'
        ];
    }
}

//hapus data institusi

if (isset($_POST['hapusdatainstitusi'])) {
    $idi = $_POST['idi'];

    // Ambil nama file PDF dari database
    $query = mysqli_query($conn, "SELECT file FROM institusi WHERE idinstitusi = '$idi'");
    $data = mysqli_fetch_assoc($query);
    $fileToDelete = $data['file'];

    // Hapus file PDF dari direktori
    $filePath = "../assets/institusi/pdf/" . $fileToDelete;
    if (unlink($filePath)) {
        // Hapus data institusi dari database
        $hapus = mysqli_query($conn, "DELETE FROM institusi WHERE idinstitusi = '$idi'");

        if ($hapus) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menghapus Data !'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menghapus Data !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menghapus Data !'
        ];
    }
}
//update info institusi
if (isset($_POST['updatedatainstitusi'])) {
    $idi = $_POST['idi'];
    $namainstitusi = $_POST['namainstitusi'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $file = $_FILES['file']['name']; // Gunakan 'newfile' sebagai nama input file

    // Validasi ukuran file
    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        // Validasi tipe file
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/institusi/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                // Hapus file PDF lama jika perlu
                $ambilFileLama = mysqli_query($conn, "SELECT file FROM institusi WHERE idinstitusi = '$idi'");
                $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
                $fileLama = $dataFileLama['file'];
                $fileLamaPath = $uploadDir . $fileLama;

                if (file_exists($fileLamaPath)) {
                    unlink($fileLamaPath); // Hapus file PDF lama
                }

                // Update data institusi dengan file PDF baru dan tanggal_update
                $update = mysqli_query($conn, "UPDATE institusi SET namainstitusi = '$namainstitusi', deskripsi = '$deskripsi', file = '$file', tanggal = '$tanggal', status = 0 WHERE idinstitusi = '$idi'");

                if ($update) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Mengedit Data !'
                    ];
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal Mengedit Data !'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Mengunggah file !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Mengedit Data, hanya file pdf yang diterima !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Mengedit Data, file pdf maks 2 MB !'
        ];
    }
}

//menambah admin baru
if (isset($_POST['addadmin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {

        $queryinsert = mysqli_query($conn, "insert into user (username, password, role) values('$username', '$password', 'admin')");

        if ($queryinsert) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah Admin !'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah Admin !'
            ];
        }
    }
}

//edit admin 
if (isset($_POST['updateadmin'])) {
    $usernamebaru = $_POST['usernameadmin'];
    $passwordbaru = $_POST['passwordbaru'];
    $idnya = $_POST['id'];

    // Periksa apakah username baru sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$usernamebaru' AND id <> '$id'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {

        $queryupdate = mysqli_query($conn, "update user set username = '$usernamebaru', password = '$passwordbaru' where id = '$idnya'");

        if ($queryupdate) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Mengedit Data Admin !'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Mengedit Data Admin !'
            ];
        }
    }
}

//hapus admin
if (isset($_POST['hapusadmin'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "delete from user where id = '$id'");

    if ($querydelete) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Menghapus Data Admin !'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menghapus Data Admin !'
        ];
    }
}
//menambah user
if (isset($_POST['adduser'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Jika username belum ada, maka lakukan INSERT
        $queryinsert = mysqli_query($conn, "INSERT INTO user (username, password, role) VALUES ('$username', '$password', 'user')");

        if ($queryinsert) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah User !'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah user  !'
            ];
        }
    }
}

//edit user
if (isset($_POST['updateuser'])) {
    $usernamebaru = $_POST['usernameuser'];
    $passwordbaru = $_POST['passwordbaru'];
    $id = $_POST['id'];

    // Periksa apakah username baru sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$usernamebaru' AND id <> '$id'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Jika username belum ada, maka lakukan UPDATE
        $queryupdate = mysqli_query($conn, "UPDATE user SET username = '$usernamebaru', password = '$passwordbaru' WHERE id = '$id'");

        if ($queryupdate) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Mengedit Data User !'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Mengedit Data User !'
            ];
        }
    }
}


//hapus user
if (isset($_POST['hapususer'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "delete from user where id = '$id'");

    if ($querydelete) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil Menghapus Data User !'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menghapus Data User'
        ];
    }
}

//verifikasi data kebijakan
if (isset($_POST['verifikasidatakebijakan'])) {
    $idk = $_POST['idk'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update kebijakan set status = 1 WHERE idkebijakan = $idk");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil Verifikasi !'
        ];
    } else {
        header('location:index.php');
    }
}
//verifikasi data prosedur
if (isset($_POST['verifikasidataprosedur'])) {
    $idp = $_POST['idp'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update prosedur set status = 1 WHERE idprosedur = $idp");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil Verifikasi !'
        ];
    } else {
        header('location:prosedur.php');
    }
}

//verifikasi data standar
if (isset($_POST['verifikasidatastandar'])) {
    $ids = $_POST['ids'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update standar set status = 1 WHERE idstandar = $ids");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil Verifikasi !'
        ];
    } else {
        header('location:standar.php');
    }
}
//verifikasi data institusi
if (isset($_POST['verifikasidatainstitusi'])) {
    $idi = $_POST['idi'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update institusi set status = 1 WHERE idinstitusi = $idi");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil Verifikasi !'
        ];
    } else {
        header('location:institusi.php');
    }
}
