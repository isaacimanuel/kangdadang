<?php
session_start();

//koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "siska");

$usernameValue = '';
$namaValue = '';
$emailValue = '';
$passwordValue = '';
$alamatValue = '';
$deskripsiValue = '';
$hargaValue = '';
$merkValue = '';
$vendorValue = '';
$jumlahValue = '';
$fotoValue = '';
$fakultasValue = '';


//konfirmasi pesanan vendor
if (isset($_POST['konfirmasipesan'])) {
    $kode_pesan = $_POST['kode_pesan'];
    $idvendor = $_POST['idvendor'];
    $iduser = $_POST['iduser'];

    $updateQuery = "UPDATE detailpesan SET status = 1 WHERE kode_pesan = '$kode_pesan' AND idvendor = '$idvendor' AND status = 0";
    mysqli_query($conn, $updateQuery);
    if ($updateQuery) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Pesanan Terkonfirmasi!'
        ];
    }
    header("Location: detailpesan.php?iduser=$iduser");
}
//konfirmasi pesanan superadmin
if (isset($_POST['konfirmasipesansuperadmin'])) {
    $kode_pesan = $_POST['kode_pesan'];
    $iduser = $_POST['iduser'];

    $updateQuery = "UPDATE detailpesan SET status = 1 WHERE kode_pesan = '$kode_pesan' AND status = 0";
    mysqli_query($conn, $updateQuery);
    if ($updateQuery) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Pesanan Terkonfirmasi!'
        ];
    }
    header("Location: detailpesan.php?iduser=$iduser");
}
//download pesan tambahkan ke riwayat
if (isset($_POST['downloadpesan'])) {
    $kode_pesan = $_POST['kode_pesan'];

    // Pindahkan data ke tabel riwayat
    $query = "INSERT INTO riwayat (kode_pesan, idbarang,iduser, idvendor, namabarang, merk, vendor, harga_awal, harga, jumlah,tanggal_pesan,status_admin,status_user,status_superadmin,status_vendor) 
              SELECT kode_pesan, idbarang,iduser, idvendor, namabarang, merk, vendor, harga_awal, harga, jumlah,tanggal_pesan,1,1,1,1
              FROM detailpesan 
              WHERE kode_pesan = '$kode_pesan'";

    mysqli_query($conn, $query);

    // Jika berhasil, hapus data dari tabel detailpesan
    if (mysqli_affected_rows($conn) > 0) {
        $delete_query = "DELETE FROM detailpesan WHERE kode_pesan = '$kode_pesan'";
        mysqli_query($conn, $delete_query);
    }

    // Redirect pengguna ke file PDF atau lakukan proses PDF di sini
    echo '<script>window.open("export.php?kode_pesan=' . $kode_pesan . '", "_blank");</script>';
}
//hapus riwayat
if (isset($_POST['hapusriwayat'])) {
    $iduser = $_POST['iduser'];
    $role = $_SESSION['role'];
    $idvendor = $_POST['idvendor'];

    if ($role == 'admin_katalog') {
        // Jika role adalah admin_katalog, update status_admin menjadi 0
        $update_status = mysqli_query($conn, "UPDATE riwayat SET status_admin = 0 WHERE iduser = '$iduser'");
    } elseif ($role == 'superadmin_katalog') {
        // Jika role adalah superadmin_katalog, update status_superadmin menjadi 0
        $update_status = mysqli_query($conn, "UPDATE riwayat SET status_superadmin = 0 WHERE iduser = '$iduser'");
    } elseif ($role == 'user_katalog') {
        // Jika role adalah superadmin_katalog, update status_superadmin menjadi 0
        $update_status = mysqli_query($conn, "UPDATE riwayat SET status_user = 0 WHERE iduser = '$iduser'");
    } elseif ($role == 'suplier') {
        // Jika role adalah superadmin_katalog, update status_superadmin menjadi 0
        $update_status = mysqli_query($conn, "UPDATE riwayat SET status_vendor = 0 WHERE iduser = '$iduser' AND idvendor = '$idvendor'");
    }

    if ($update_status) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil Hapus Riwayat!'
        ];
        header("location:riwayat.php?iduser=$iduser");
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menghapus Riwayat!'
        ];
    }

    // Periksa apakah semua status menjadi 0
    $cek_status = mysqli_query($conn, "SELECT * FROM riwayat WHERE iduser = '$iduser' AND status_admin = 0 AND status_superadmin = 0 AND status_user = 0 AND status_vendor = 0");

    if (mysqli_num_rows($cek_status) > 0) {
        // Jika semua status menjadi 0, hapus dari database
        $hapus_riwayat = mysqli_query($conn, "DELETE FROM riwayat WHERE iduser = '$iduser'");
    }
}


// Tambah produk
if (isset($_POST['addnewproduk'])) {
    $namaproduk = $_POST['namaproduk'];
    $idtipe = $_POST['idtipe'];

    $namaValue = $namaproduk;

    // Periksa apakah nama produk sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM produk WHERE namaproduk='$namaproduk'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Nama produk sudah ada, silakan pilih yang lain!'
        ];
    } else {

        // Membuat nama file unik dengan timestamp
        $uniqueFileName = time() . '_' . $_FILES['foto']['name'];
        $foto = $uniqueFileName;

        // Validasi foto
        $maxFileSize = 307200; // 300kb dalam byte
        if ($_FILES['foto']['size'] <= $maxFileSize) {
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (in_array($_FILES['foto']['type'], $allowedFileTypes)) {
                // Direktori untuk menyimpan file
                $uploadDir = "../assets/produk/";
                $uploadedFile = $uploadDir . $uniqueFileName;

                // Upload file
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadedFile)) {
                    // Simpan data ke database
                    $tambahproduk = mysqli_query($conn, "INSERT INTO produk (idtipe, namaproduk, foto) VALUES ('$idtipe','$namaproduk','$foto')");

                    if ($tambahproduk) {
                        $_SESSION['notification'] = [
                            'type' => 'success',
                            'message' => 'Berhasil menambah Produk!'
                        ];
                        $namaValue = '';
                    } else {
                        $_SESSION['notification'] = [
                            'type' => 'danger',
                            'message' => 'Gagal menambah Data!'
                        ];
                    }
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal mengunggah file!'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Hanya file JPEG atau PNG dengan ukuran maksimum 300kb yang diterima!'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal menambah Data, pastikan Anda memilih file foto dan maks 300kb !'
            ];
        }
    }
}
// Edit produk
if (isset($_POST['editproduk'])) {
    $idproduk = $_POST['idproduk'];
    $namaproduk = $_POST['namaproduk'];

    // Mengecek apakah ada file gambar yang diunggah
    if (!empty($_FILES['foto']['name'])) {
        // Membuat nama file unik dengan timestamp
        $uniqueFileName = time() . '_' . $_FILES['foto']['name'];
        $foto = $uniqueFileName;

        // Validasi foto
        $maxFileSize = 307200; // 300kb dalam byte
        if ($_FILES['foto']['size'] <= $maxFileSize) {
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (in_array($_FILES['foto']['type'], $allowedFileTypes)) {
                // Direktori untuk menyimpan file
                $uploadDir = "../assets/produk/";
                $uploadedFile = $uploadDir . $uniqueFileName;

                // Upload file
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadedFile)) {
                    // Hapus foto lama
                    $queryGetOldFoto = mysqli_query($conn, "SELECT foto FROM produk WHERE idproduk='$idproduk'");
                    $data = mysqli_fetch_assoc($queryGetOldFoto);
                    $oldFoto = $data['foto'];
                    $oldFilePath = $uploadDir . $oldFoto;
                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                        unlink($oldFilePath); // Hapus file lama jika ada
                    }

                    // Update data di database
                    $updateproduk = mysqli_query($conn, "UPDATE produk SET namaproduk='$namaproduk', foto='$foto' WHERE idproduk='$idproduk'");

                    if ($updateproduk) {
                        $_SESSION['notification'] = [
                            'type' => 'success',
                            'message' => 'Berhasil mengedit Data!'
                        ];
                    } else {
                        $_SESSION['notification'] = [
                            'type' => 'danger',
                            'message' => 'Gagal mengedit Data!'
                        ];
                    }
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal mengunggah file!'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Hanya file JPEG atau PNG dengan ukuran maksimum 300kb yang diterima!'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal mengedit Data, pastikan Anda memilih file foto dan maks 300kb !'
            ];
        }
    } else {
        // Jika tidak ada file gambar yang diunggah, hanya mengupdate nama produk
        $updateproduk = mysqli_query($conn, "UPDATE produk SET namaproduk='$namaproduk' WHERE idproduk='$idproduk'");

        if ($updateproduk) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil mengedit Data!'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal mengedit Data!'
            ];
        }
    }
}
// Hapus produk
if (isset($_POST['hapusproduk'])) {
    $idproduk = $_POST['idproduk'];

    // Ambil nama file gambar dari database
    $query = mysqli_query($conn, "SELECT foto FROM produk WHERE idproduk='$idproduk'");
    if ($query) {
        $data = mysqli_fetch_assoc($query);

        if ($data) {
            $foto = $data['foto'];

            // Hapus data dari database
            $hapusproduk = mysqli_query($conn, "DELETE FROM produk WHERE idproduk='$idproduk'");

            if ($hapusproduk) {
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Berhasil menghapus Data!'
                ];

                // Hapus file gambar dari direktori setelah berhasil menghapus data
                $uploadDir = "../assets/produk/";
                $filePath = $uploadDir . $foto;
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath); // Hapus file jika ada
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal menghapus Data dari Database!'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Data tidak ditemukan di Database!'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal mengakses Database!'
        ];
    }
}

//tambah tipe 
if (isset($_POST['addnewtipe'])) {
    $namatipe = $_POST['namatipe'];

    $namaValue = $namatipe;

    // Periksa apakah tipe sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM tipe WHERE namatipe='$namatipe'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Tipe sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Membuat nama file unik dengan timestamp
        $uniqueFileName = time() . '_' . $_FILES['foto']['name'];
        $foto = $uniqueFileName;

        // Validasi foto
        $maxFileSize = 307200; // 300kb dalam byte
        if ($_FILES['foto']['size'] <= $maxFileSize) {
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (in_array($_FILES['foto']['type'], $allowedFileTypes)) {
                // Direktori untuk menyimpan file
                $uploadDir = "../assets/kategori/";
                $uploadedFile = $uploadDir . $uniqueFileName;

                // Upload file
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadedFile)) {
                    // Simpan data ke database
                    $tambahtipe = mysqli_query($conn, "INSERT INTO tipe (namatipe, foto) VALUES ('$namatipe','$foto')");

                    if ($tambahtipe) {
                        $_SESSION['notification'] = [
                            'type' => 'success',
                            'message' => 'Berhasil menambah Data!'
                        ];
                        $namaValue = '';
                    } else {
                        $_SESSION['notification'] = [
                            'type' => 'danger',
                            'message' => 'Gagal menambah Data!'
                        ];
                    }
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal mengunggah file!'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Hanya file JPEG atau PNG dengan ukuran maksimum 300kb yang diterima!'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal menambah Data, pastikan Anda memilih file foto dan maks 300kb !'
            ];
        }
    }
}
//edit tipe
if (isset($_POST['edittipe'])) {
    $idtipe = $_POST['idtipe'];
    $namatipe = $_POST['namatipe'];

    // Mengecek apakah ada file gambar yang diunggah
    if (!empty($_FILES['foto']['name'])) {
        // Membuat nama file unik dengan timestamp
        $uniqueFileName = time() . '_' . $_FILES['foto']['name'];
        $foto = $uniqueFileName;

        // Validasi foto
        $maxFileSize = 307200; // 300kb dalam byte
        if ($_FILES['foto']['size'] <= $maxFileSize) {
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (in_array($_FILES['foto']['type'], $allowedFileTypes)) {
                // Direktori untuk menyimpan file
                $uploadDir = "../assets/kategori/";
                $uploadedFile = $uploadDir . $uniqueFileName;

                // Upload file
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadedFile)) {
                    // Hapus foto lama
                    $queryGetOldFoto = mysqli_query($conn, "SELECT foto FROM tipe WHERE idtipe='$idtipe'");
                    $data = mysqli_fetch_assoc($queryGetOldFoto);
                    $oldFoto = $data['foto'];
                    $oldFilePath = $uploadDir . $oldFoto;
                    if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                        unlink($oldFilePath); // Hapus file lama jika ada
                    }

                    // Update data di database
                    $updatetipe = mysqli_query($conn, "UPDATE tipe SET namatipe='$namatipe', foto='$foto' WHERE idtipe='$idtipe'");

                    if ($updatetipe) {
                        $_SESSION['notification'] = [
                            'type' => 'success',
                            'message' => 'Berhasil mengedit Data!'
                        ];
                    } else {
                        $_SESSION['notification'] = [
                            'type' => 'danger',
                            'message' => 'Gagal mengedit Data!'
                        ];
                    }
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal mengunggah file!'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Hanya file JPEG atau PNG dengan ukuran maksimum 2MB yang diterima!'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal mengedit Data, pastikan Anda memilih file foto dan maks 300kb !'
            ];
        }
    } else {
        // Jika tidak ada file gambar yang diunggah, hanya mengupdate nama tipe
        $updatetipe = mysqli_query($conn, "UPDATE tipe SET namatipe='$namatipe' WHERE idtipe='$idtipe'");

        if ($updatetipe) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil mengedit Data!'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal mengedit Data!'
            ];
        }
    }
}
//hapus tipe 
if (isset($_POST['hapustipe'])) {
    $idtipe = $_POST['idtipe'];

    // Ambil nama file gambar dari database
    $query = mysqli_query($conn, "SELECT foto FROM tipe WHERE idtipe='$idtipe'");
    if ($query) {
        $data = mysqli_fetch_assoc($query);

        if ($data) {
            $foto = $data['foto'];

            // Hapus data dari database
            $hapustipe = mysqli_query($conn, "DELETE FROM tipe WHERE idtipe='$idtipe'");

            if ($hapustipe) {
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Berhasil menghapus Data!'
                ];

                // Hapus file gambar dari direktori setelah berhasil menghapus data
                $uploadDir = "../assets/kategori/";
                $filePath = $uploadDir . $foto;
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath); // Hapus file jika ada
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal menghapus Data dari Database!'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Data tidak ditemukan di Database!'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal mengakses Database!'
        ];
    }
}

//tambah data barang
if (isset($_POST['addnewdatabarang'])) {
    $namabarang = $_POST['namabarang'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];
    $vendor = $_POST['vendor'];
    $idproduk = $_POST['idproduk'];
    $idtipe = $_POST['idtipe'];
    $deskripsi = $_POST['deskripsi'];
    $idvendor = $_POST['idvendor'];
    $merk = $_POST['merk'];

    $namaValue = $namabarang;
    $hargaValue = $harga;
    $jumlahValue = $jumlah;
    $deskripsiValue = $deskripsi;
    $merkValue = $merk;
    $vendorValue = $vendor;

    // Membuat nama file unik dengan timestamp
    $uniqueFileName = time() . '_' . $_FILES['foto']['name'];
    $foto = $uniqueFileName;

    if ($harga >= 0 && $jumlah >= 0) {
        // Validasi foto
        $maxFileSize = 307200; // 300kb dalam byte
        if ($_FILES['foto']['size'] <= $maxFileSize) {
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (in_array($_FILES['foto']['type'], $allowedFileTypes)) {
                // Direktori untuk menyimpan file
                $uploadDir = "../assets/katalog/";
                $uploadedFile = $uploadDir . $uniqueFileName;

                // Upload file
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadedFile)) {
                    // Simpan data ke database
                    $tambahdatabarang = mysqli_query($conn, "INSERT INTO katalog (namabarang,merk,deskripsi, vendor,foto, harga,jumlah,idproduk,idtipe,idvendor,tanggal_tambah) VALUES ('$namabarang','$merk','$deskripsi','$vendor','$foto', '$harga','$jumlah','$idproduk','$idtipe','$idvendor',now())");

                    if ($tambahdatabarang) {
                        $_SESSION['notification'] = [
                            'type' => 'success',
                            'message' => 'Berhasil menambah Data!'
                        ];
                        $namaValue = '';
                        $hargaValue = '';
                        $jumlahValue = '';
                        $deskripsiValue = '';
                        $merkValue = '';
                        $vendorValue = '';
                    } else {
                        $_SESSION['notification'] = [
                            'type' => 'danger',
                            'message' => 'Gagal menambah Data!'
                        ];
                    }
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal mengunggah file!'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Hanya file JPEG atau PNG dengan ukuran maksimum 300kb yang diterima!'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal menambah Data, pastikan Anda memilih file foto dan maks 300kb !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal menambah Data, Jumlah atau harga tidak valid !'
        ];
    }
}
//hapus data barang vendor
if (isset($_POST['hapusdatabarangvendor'])) {
    $id = $_POST['id'];
    $idproduk = $_POST['idproduk'];

    // Cek apakah ada relasi ke tabel lain
    $cek_relasi = mysqli_query($conn, "SELECT * FROM keranjang WHERE idbarang = '$id'");
    if (mysqli_num_rows($cek_relasi) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Hapus Barang!'
        ];
    } else {
        // Ambil nama file foto dari database
        $query = mysqli_query($conn, "SELECT foto FROM katalog WHERE id = '$id'");
        $data = mysqli_fetch_assoc($query);
        $fileToDelete = $data['foto'];

        // Hapus data katalog dari database
        $hapus = mysqli_query($conn, "DELETE FROM katalog WHERE id = '$id'");

        if ($hapus) {
            // Hapus foto jika data berhasil dihapus
            $filePath = "../assets/katalog/" . $fileToDelete;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

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
    }
}
//hapus data barang
if (isset($_POST['hapusdatabarang'])) {
    $id = $_POST['id'];
    $idproduk = $_POST['idproduk'];

    // Cek apakah ada relasi ke tabel lain
    $cek_relasi = mysqli_query($conn, "SELECT * FROM keranjang WHERE idbarang = '$id'");
    if (mysqli_num_rows($cek_relasi) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Hapus Barang!'
        ];
    } else {
        // Ambil nama file foto dari database
        $query = mysqli_query($conn, "SELECT foto FROM katalog WHERE id = '$id'");
        $data = mysqli_fetch_assoc($query);
        $fileToDelete = $data['foto'];

        // Hapus data katalog dari database
        $hapus = mysqli_query($conn, "DELETE FROM katalog WHERE id = '$id'");

        if ($hapus) {
            // Hapus foto jika data berhasil dihapus
            $filePath = "../assets/katalog/" . $fileToDelete;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menghapus Data !'
            ];
            header("location:katalog.php?idproduk=$idproduk");
            exit();
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menghapus Data !'
            ];
            header("location:katalog.php?idproduk=$idproduk");
            exit();
        }
    }
}
//edit data barang
if (isset($_POST['editdatabarang'])) {
    $id = $_POST['id'];
    $namabarang = $_POST['namabarang'];
    $harga = $_POST['harga'];
    $vendor = $_POST['vendor'];
    $jumlah = $_POST['jumlah'];
    $deskripsi = $_POST['deskripsi'];
    $foto = $_FILES['foto']['name'];
    $merk = $_POST['merk'];

    $uniqueFileName = time() . '_' . $_FILES['foto']['name'];
    $foto = $uniqueFileName;

    // Cek apakah barang sudah dipesan
    $cek_pesanan = mysqli_query($conn, "SELECT * FROM keranjang WHERE idbarang='$id'");
    $data_pesanan = mysqli_fetch_assoc($cek_pesanan);

    if ($data_pesanan) {
        // Jika barang sudah dipesan, perbarui nama di pesanan
        $update_pesanan = mysqli_query($conn, "UPDATE keranjang SET namabarang = '$namabarang', merk = '$merk',foto = '$foto' WHERE idbarang='$id'");
        if (!$update_pesanan) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Update Pesanan!'
            ];
        }
    }

    // Cek apakah file gambar diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $maxFileSize = 307200; // 2MB dalam byte
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        // Validasi ukuran dan tipe file
        if ($_FILES['foto']['size'] <= $maxFileSize && in_array($_FILES['foto']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/katalog/";
            $uploadedFile = $uploadDir . $uniqueFileName;

            // Ambil file lama
            $ambilFileLama = mysqli_query($conn, "SELECT foto FROM katalog WHERE id = '$id'");
            $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
            $fileLama = $dataFileLama['foto'];
            $fileLamaPath = $uploadDir . $fileLama;

            // Hapus file lama jika ada
            if (file_exists($fileLamaPath)) {
                unlink($fileLamaPath); // Hapus file foto lama
            }

            if ($harga >= 0 && $jumlah >= 0) {
                // Upload file baru
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadedFile)) {
                    // Update data barang dengan file foto baru
                    $update = mysqli_query($conn, "UPDATE katalog SET namabarang = '$namabarang', merk = '$merk', vendor = '$vendor',deskripsi = '$deskripsi', foto = '$foto', harga = '$harga', jumlah = '$jumlah' WHERE id = '$id'");

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
                        'message' => 'Gagal mengunggah file maks 300kb !'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Jumlah atau harga tidak Valid !'
                ];
            }
        }
    } else {
        // Jika tidak ada file yang diunggah, gunakan file lama
        $ambilFileLama = mysqli_query($conn, "SELECT foto FROM katalog WHERE id = '$id'");
        $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
        $foto = $dataFileLama['foto'];

        if ($harga >= 0 && $jumlah >= 0) {
            $update = mysqli_query($conn, "UPDATE katalog SET namabarang = '$namabarang', merk = '$merk',deskripsi ='$deskripsi',vendor = '$vendor', foto = '$foto', harga = '$harga', jumlah = '$jumlah' WHERE id = '$id'");
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
                'message' => 'Jumlah atau Harga tidak Valid!'
            ];
        }
    }
    // Perbarui nama barang dan merk di tabel detailpesan jika ada
    $update_detailpesan = mysqli_query($conn, "UPDATE detailpesan SET namabarang = '$namabarang', merk = '$merk' WHERE idbarang='$id'");
    if (!$update_detailpesan) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Update Detail Pesanan!'
        ];
    }
}

//pesan 1 barang keranjang
if (isset($_POST['pesandatabarang'])) {
    $id = $_POST['id'];
    $namabarang = $_POST['namabarang'];
    $jumlah_pesan = $_POST['jumlah'];
    $idproduk = $_POST['idproduk'];
    $vendor = $_POST['vendor'];
    $idvendor = $_POST['idvendor'];
    $merk = $_POST['merk'];
    $iduser = $_SESSION['id'];

    $ambildata = mysqli_query($conn, "SELECT * FROM katalog WHERE id='$id'");
    $data = mysqli_fetch_assoc($ambildata);
    $stok = $data['jumlah'];

    $cek_pesanan_di_keranjang = mysqli_query($conn, "SELECT SUM(jumlah) AS jumlah_pesanan FROM keranjang WHERE idbarang='$id' AND iduser = '$iduser'");
    $data_pesanan_di_keranjang = mysqli_fetch_assoc($cek_pesanan_di_keranjang);
    $jumlah_pesanan_di_keranjang = $data_pesanan_di_keranjang['jumlah_pesanan'];

    if ($jumlah_pesan <= $stok - $jumlah_pesanan_di_keranjang && $jumlah_pesan > 0) {
        $harga_awal = $data['harga'];
        $total_harga = $jumlah_pesan * $data['harga'];

        $cek_pesanan = mysqli_query($conn, "SELECT * FROM keranjang WHERE idbarang='$id' AND iduser = '$iduser'");
        $data_pesanan = mysqli_fetch_assoc($cek_pesanan);

        if ($data_pesanan) {
            $update_pesanan = mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + '$jumlah_pesan',  harga = jumlah * harga_awal WHERE idbarang='$id' AND iduser = '$iduser'");
            if (!$update_pesanan) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Update Pesanan!'
                ];
            }
        } else {
            $simpan_pesanan = mysqli_query($conn, "INSERT INTO keranjang (idbarang,iduser,namabarang,merk,vendor, foto, jumlah, harga,harga_awal,idproduk,idvendor) VALUES ('$id','$iduser', '$namabarang','$merk','$vendor', '{$data['foto']}', '$jumlah_pesan', '$total_harga','$harga_awal','$idproduk','$idvendor')");
            if (!$simpan_pesanan) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Menambah Keranjang !'
                ];
            }
        }

        if (!isset($_SESSION['notification'])) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah Keranjang !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Pesanan Barang Melebihi Stok atau Sudah ada di keranjang Anda !'
        ];
    }
}


// Edit keranjang barang SUPERADMIN
if (isset($_POST['editpesan'])) {
    $id = $_POST['id'];
    $jumlah_pesanan = $_POST['jumlah'];

    // Ambil data pesanan dari database
    $ambildata = mysqli_query($conn, "SELECT * FROM keranjang WHERE id='$id'");
    $data = mysqli_fetch_assoc($ambildata);

    if ($data) {
        $idbarang = $data['idbarang'];
        $id = $data['id'];
        $jumlah_sebelumnya = $data['jumlah'];

        // Periksa apakah stok mencukupi untuk edit pesanan
        $ambildata_barang = mysqli_query($conn, "SELECT * FROM katalog WHERE id='$idbarang'");
        $data_barang = mysqli_fetch_assoc($ambildata_barang);
        $stok = $data_barang['jumlah'] - 1;

        if ($jumlah_pesanan >= 0 && $jumlah_pesanan <= $stok + $jumlah_sebelumnya) {
            $total_harga = $jumlah_pesanan * $data_barang['harga'];

            // Update jumlah pesanan
            $update_pesanan = mysqli_query($conn, "UPDATE keranjang SET jumlah='$jumlah_pesanan' WHERE id='$id'");

            if ($jumlah_pesanan == 0) {
                // Hapus pesanan jika jumlah menjadi 0
                $delete_pesanan = mysqli_query($conn, "DELETE FROM keranjang WHERE id='$id'");
            }

            if ($update_pesanan) {
                // Update total harga
                $update_total_harga = mysqli_query($conn, "UPDATE keranjang SET harga='$total_harga' WHERE id='$id'");
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Edit Pesanan !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Jumlah Pesanan Tidak Valid atau Melebihi Stok !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Pesanan tidak ditemukan untuk diedit!'
        ];
    }
}

//edit keranjang barang user
if (isset($_POST['editpesanan'])) {
    $namabarang = $_POST['namabarang'];
    $jumlah_pesanan = $_POST['jumlah'];
    $iduser = $_SESSION['id'];

    // Ambil data pesanan dari database
    $ambildata = mysqli_query($conn, "SELECT * FROM keranjang WHERE namabarang='$namabarang' AND iduser='$iduser'");
    $data = mysqli_fetch_assoc($ambildata);
    if ($data) {
        $id = $data['idbarang'];
        $jumlah_sebelumnya = $data['jumlah'];

        // Periksa apakah stok mencukupi untuk edit pesanan
        $ambildata_barang = mysqli_query($conn, "SELECT * FROM katalog WHERE id='$id'");
        $data_barang = mysqli_fetch_assoc($ambildata_barang);
        $stok = $data_barang['jumlah'];

        if ($jumlah_pesanan >= 0 && $jumlah_pesanan <= $stok + $jumlah_sebelumnya) {
            $total_harga = $jumlah_pesanan * $data_barang['harga'];

            // Update jumlah pesanan
            $update_pesanan = mysqli_query($conn, "UPDATE keranjang SET jumlah='$jumlah_pesanan' WHERE idbarang='$id' AND iduser='$iduser'");

            if ($jumlah_pesanan == 0) {
                // Hapus pesanan jika jumlah menjadi 0
                $delete_pesanan = mysqli_query($conn, "DELETE FROM keranjang WHERE idbarang='$id' AND iduser='$iduser'");
            }

            if ($update_pesanan) {
                // Update total harga
                $update_total_harga = mysqli_query($conn, "UPDATE keranjang SET harga='$total_harga' WHERE idbarang='$id' AND iduser='$iduser'");
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Edit Pesanan !'
                ];
            }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Jumlah Pesanan Tidak Valid atau Melebihi Stok !'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Pesanan tidak ditemukan untuk diedit!'
        ];
    }
}
//hapus keranjang barang
if (isset($_POST['hapuspesan'])) {
    $id = $_POST['id'];

    // Ambil data pesanan dari database
    $ambildata = mysqli_query($conn, "SELECT * FROM keranjang WHERE id='$id'");
    $data = mysqli_fetch_assoc($ambildata);

    if ($data) {
        $idbarang = $data['idbarang'];
        $jumlah_pesanan = $data['jumlah'];

        // Hapus pesanan dari database
        $query = mysqli_query($conn, "DELETE FROM keranjang WHERE id = $id");

        if ($query) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Pesanan berhasil dihapus!'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal menghapus Pesanan'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Pesanan tidak ditemukan untuk dihapus!'
        ];
    }
}
//pesan barang
if (isset($_POST['pesan'])) {
    $iduser = $_POST['iduser'];
    $kode_pesan = str_pad(rand(0, 9999), 5, '0', STR_PAD_LEFT);
    // Ambil semua barang dari keranjang
    $ambil_keranjang = mysqli_query($conn, "SELECT * FROM keranjang WHERE iduser='$iduser'");
    $pesan_sukses = true; // Set default pesan sukses ke true

    while ($data_keranjang = mysqli_fetch_assoc($ambil_keranjang)) {
        $idbarang = $data_keranjang['idbarang'];
        $jumlah = $data_keranjang['jumlah'];

        // Ambil stok dari katalog
        $ambil_stok = mysqli_query($conn, "SELECT jumlah FROM katalog WHERE id='$idbarang'");
        $data_stok = mysqli_fetch_assoc($ambil_stok);
        $stok = $data_stok['jumlah'];

        // Cek apakah stok mencukupi
        if ($stok >= $jumlah) {
            // Jika stok mencukupi, proses pesanan
            $namabarang = $data_keranjang['namabarang'];
            $iduser = $_POST['iduser'];
            $merk = $data_keranjang['merk'];
            $idvendor = $data_keranjang['idvendor'];
            $vendor = $data_keranjang['vendor'];
            $harga_awal = $data_keranjang['harga_awal'];
            $harga = $data_keranjang['harga'];

            // Masukkan barang ke detail pesanan
            $insert_detailpesan = mysqli_query($conn, "INSERT INTO detailpesan (tanggal_pesan,kode_pesan,iduser, namabarang, merk, idbarang, idvendor, vendor, harga_awal, harga, jumlah,status) 
            VALUES (now(),'$kode_pesan','$iduser', '$namabarang', '$merk', '$idbarang', '$idvendor', '$vendor', '$harga_awal', '$harga', '$jumlah',0)");

            $update_stok = mysqli_query($conn, "UPDATE katalog SET jumlah=jumlah-'$jumlah' WHERE id='$idbarang'");
            if (!$update_stok) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Update Stok!'
                ];
                $pesan_sukses = false; // Set pesan sukses ke false jika ada kesalahan
                break; // Hentikan loop jika ada kesalahan
            }

            if ($insert_detailpesan) {
                // Hapus barang dari keranjang
                $hapus_keranjang = mysqli_query($conn, "DELETE FROM keranjang WHERE iduser = '$iduser'");
            }
        } else {
            // Jika stok tidak mencukupi, set pesan kesalahan
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Stok barang tidak mencukupi'
            ];
            $pesan_sukses = false; // Set pesan sukses ke false
            break; // Hentikan loop jika ada kesalahan
        }
    }

    if ($pesan_sukses) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil, Pesanan dipindahkan ke Detail Pesan !'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Memesan Barang Mohon Periksa Masing Masing stok barang yang dipesan'
        ];
    }
}

//batal pesan
if (isset($_POST['batalpesan'])) {
    $id = $_POST['id'];

    // Dapatkan idbarang dari pesanan yang dibatalkan
    $ambil_idbarang = mysqli_query($conn, "SELECT idbarang, jumlah FROM detailpesan WHERE id='$id'");
    $data_idbarang = mysqli_fetch_assoc($ambil_idbarang);
    $idbarang = $data_idbarang['idbarang'];
    $jumlah_batal = $data_idbarang['jumlah'];

    // Hapus pesanan dari tabel detailpesan
    $hapus_pesanan = mysqli_query($conn, "DELETE FROM detailpesan WHERE id='$id'");
    if ($hapus_pesanan) {
        // Dapatkan stok saat ini dari katalog
        $ambil_stok = mysqli_query($conn, "SELECT jumlah FROM katalog WHERE id='$idbarang'");
        $data_stok = mysqli_fetch_assoc($ambil_stok);
        $stok_sekarang = $data_stok['jumlah'];

        // Tambahkan jumlah pesanan yang dibatalkan ke stok
        $stok_update = $stok_sekarang + $jumlah_batal;

        // Update stok di tabel katalog
        $update_stok = mysqli_query($conn, "UPDATE katalog SET jumlah='$stok_update' WHERE id='$idbarang'");

        if ($update_stok) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil membatalkan pesanan!'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal membatalkan pesanan!'
            ];
        }
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal membatalkan pesanan!'
        ];
    }
}

//batal pesan vendor
if (isset($_POST['batalpesanvendor'])) {
    $id = $_POST['id'];
    $deskripsi = $_POST['deskripsi'];

    // Update status pesanan di tabel detailpesan menjadi 3 dan tambahkan deskripsi kesalahan
    $update_status = mysqli_query($conn, "UPDATE detailpesan SET status = 3, deskripsi= '$deskripsi' WHERE id = '$id'");

    if ($update_status) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil membatalkan pesanan!'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal membatalkan pesanan!'
        ];
    }
}




//tambah data Kebijakan Direktorat 
if (isset($_POST['addnewdatakebijakan'])) {
    $namakebijakan = $_POST['namakebijakan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $namaValue = $namakebijakan;
    $deskripsiValue = $deskripsi;

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/kebijakan/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahkebijakan = mysqli_query($conn, "INSERT INTO kebijakan (namakebijakan, deskripsi, file, tanggal, status) VALUES ('$namakebijakan', '$deskripsi', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahkebijakan) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                    $namaValue = '';
                    $deskripsiValue = '';
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
//hapus data kebijakan Direktorat
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
//update info kebijakan Direktorat
if (isset($_POST['updatedatakebijakan'])) {
    $idk = $_POST['idk'];
    $namakebijakan = $_POST['namakebijakan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    if ($_FILES['file']['size'] > 0) {
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Validasi ukuran file
        $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte

        if (
            $fileSize <= $maxFileSize
        ) {
            // Validasi tipe file
            $allowedFileTypes = ['application/pdf'];
            if (in_array($fileType, $allowedFileTypes)) {
                $uploadDir = "../assets/kebijakan/pdf/";
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueFileName = uniqid() . '.' . $fileExtension;

                $uploadedFile = $uploadDir . $uniqueFileName;

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
                    $update = mysqli_query($conn, "UPDATE kebijakan SET namakebijakan = '$namakebijakan', deskripsi = '$deskripsi', file = '$uniqueFileName', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE idkebijakan = '$idk'");

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
                        'message' => 'Gagal Mengunggah File Baru !'
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
    } else {
        // Jika tidak ada file baru diunggah
        $update = mysqli_query(
            $conn,
            "UPDATE kebijakan SET namakebijakan = '$namakebijakan', deskripsi = '$deskripsi', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE idkebijakan = '$idk'"
        );

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
    }
}

//tambah data Kebijakan fakultas
if (isset($_POST['addnewdatafakultas'])) {
    $namakebijakan = $_POST['namakebijakan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $fakultas = $_POST['fakultas'];

    $namaValue = $namakebijakan;
    $deskripsiValue = $deskripsi;
    $fakultasValue = $fakultas;

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/fakultas/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahfakultas = mysqli_query($conn, "INSERT INTO fakultas (namakebijakan, deskripsi,fakultas, file, tanggal, status) VALUES ('$namakebijakan', '$deskripsi','$fakultas', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahfakultas) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                    $namaValue = '';
                    $deskripsiValue = '';
                    $fakultasValue = '';
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
//hapus data kebijakan fakultas
if (isset($_POST['hapusdatakebijakanfakultas'])) {
    $id = $_POST['id'];

    // Ambil nama file PDF dari database
    $query = mysqli_query($conn, "SELECT file FROM fakultas WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);
    $fileToDelete = $data['file'];

    // Hapus file PDF dari direktori
    $filePath = "../assets/fakultas/pdf/" . $fileToDelete;
    if (unlink($filePath)) {
        // Hapus data kebijakan dari database
        $hapus = mysqli_query($conn, "DELETE FROM fakultas WHERE id = '$id'");

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
//update info kebijakan fakultas
if (isset($_POST['updatedatafakultas'])) {
    $id = $_POST['id'];
    $namakebijakan = $_POST['namakebijakan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];
    $fakultas = $_POST['fakultas'];

    if ($_FILES['file']['size'] > 0) {
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Validasi ukuran file
        $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte

        if (
            $fileSize <= $maxFileSize
        ) {
            // Validasi tipe file
            $allowedFileTypes = ['application/pdf'];
            if (in_array(
                $fileType,
                $allowedFileTypes
            )) {
                $uploadDir = "../assets/fakultas/pdf/";
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueFileName = uniqid() . '.' . $fileExtension;

                $uploadedFile = $uploadDir . $uniqueFileName;

                // Upload file
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                    // Hapus file PDF lama jika perlu
                    $ambilFileLama = mysqli_query($conn, "SELECT file FROM fakultas WHERE id = '$id'");
                    $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
                    $fileLama = $dataFileLama['file'];
                    $fileLamaPath = $uploadDir . $fileLama;

                    if (file_exists($fileLamaPath)) {
                        unlink($fileLamaPath); // Hapus file PDF lama
                    }

                    // Update data fakultas dengan file PDF baru dan tanggal_update
                    $update = mysqli_query($conn, "UPDATE fakultas SET namakebijakan = '$namakebijakan', deskripsi = '$deskripsi', file = '$uniqueFileName', tanggal = '$tanggal',fakultas = '$fakultas', kesalahan = NULL, status = 0 WHERE id = '$id'");

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
                        'message' => 'Gagal Mengunggah File Baru !'
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
    } else {
        // Jika tidak ada file baru diunggah
        $update = mysqli_query($conn, "UPDATE fakultas SET namakebijakan = '$namakebijakan', deskripsi = '$deskripsi', tanggal = '$tanggal',fakultas = '$fakultas', kesalahan = NULL, status = 0 WHERE id = '$id'");

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
    }
}

//tambah data standar
if (isset($_POST['addnewdatastandar'])) {
    $namastandar = $_POST['namastandar'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $namaValue = $namastandar;
    $deskripsiValue = $deskripsi;


    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/standar/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahstandar = mysqli_query($conn, "INSERT INTO standar (namastandar, deskripsi, file, tanggal, status) VALUES ('$namastandar', '$deskripsi', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahstandar) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                    $namaValue = '';
                    $deskripsiValue = '';
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
    $query = mysqli_query($conn, "SELECT * FROM standar WHERE idstandar = '$ids'");
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
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal Menghapus File !'
        ];
    }
}
//update info standar
if (isset($_POST['updatedatastandar'])) {
    $ids = $_POST['ids'];
    $namastandar = $_POST['namastandar'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    if ($_FILES['file']['size'] > 0) {
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Validasi ukuran file
        $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte

        if (
            $fileSize <= $maxFileSize
        ) {
            // Validasi tipe file
            $allowedFileTypes = ['application/pdf'];
            if (in_array($fileType, $allowedFileTypes)) {
                $uploadDir = "../assets/standar/pdf/";
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueFileName = uniqid() . '.' . $fileExtension;

                $uploadedFile = $uploadDir . $uniqueFileName;

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
                    $update = mysqli_query($conn, "UPDATE standar SET namastandar = '$namastandar', deskripsi = '$deskripsi', file = '$uniqueFileName', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE idstandar = '$ids'");

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
                        'message' => 'Gagal Mengunggah File Baru !'
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
    } else {
        // Jika tidak ada file baru diunggah
        $update = mysqli_query($conn, "UPDATE standar SET namastandar = '$namastandar', deskripsi = '$deskripsi', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE idstandar = '$ids'");

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
    }
}

//tambah data prosedur
if (isset($_POST['addnewdataprosedur'])) {
    $namaprosedur = $_POST['namaprosedur'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $namaValue = $namaprosedur;
    $deskripsiValue = $deskripsi;

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/prosedur/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahprosedur = mysqli_query($conn, "INSERT INTO prosedur (namaprosedur, deskripsi, file, tanggal, status) VALUES ('$namaprosedur', '$deskripsi', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahprosedur) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                    $namaValue = '';
                    $deskripsiValue = '';
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

    if ($_FILES['file']['size'] > 0) {
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Validasi ukuran file
        $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte

        if (
            $fileSize <= $maxFileSize
        ) {
            // Validasi tipe file
            $allowedFileTypes = ['application/pdf'];
            if (in_array($fileType, $allowedFileTypes)) {
                $uploadDir = "../assets/prosedur/pdf/";
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueFileName = uniqid() . '.' . $fileExtension;

                $uploadedFile = $uploadDir . $uniqueFileName;

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
                    $update = mysqli_query($conn, "UPDATE prosedur SET namaprosedur = '$namaprosedur', deskripsi = '$deskripsi', file = '$uniqueFileName', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE idprosedur = '$idp'");

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
                        'message' => 'Gagal Mengunggah File Baru !'
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
    } else {
        // Jika tidak ada file baru diunggah
        $update = mysqli_query($conn, "UPDATE prosedur SET namaprosedur = '$namaprosedur', deskripsi = '$deskripsi', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE idprosedur = '$idp'");

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
    }
}

//tambah data instruksi
if (isset($_POST['addnewdatainstruksi'])) {
    $namainstruksi = $_POST['namainstruksi'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $namaValue = $namainstruksi;
    $deskripsiValue = $deskripsi;

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/instruksi/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahinstruksi = mysqli_query($conn, "INSERT INTO instruksi (namainstruksi, deskripsi, file, tanggal, status) VALUES ('$namainstruksi', '$deskripsi', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahinstruksi) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                    $namaValue = '';
                    $deskripsiValue = '';
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
//hapus data instruksi
if (isset($_POST['hapusdatainstruksi'])) {
    $idi = $_POST['idi'];

    // Ambil nama file PDF dari database
    $query = mysqli_query($conn, "SELECT file FROM instruksi WHERE idinstruksi = '$idi'");
    $data = mysqli_fetch_assoc($query);
    $fileToDelete = $data['file'];

    // Hapus file PDF dari direktori
    $filePath = "../assets/instruksi/pdf/" . $fileToDelete;
    if (unlink($filePath)) {
        // Hapus data instruksi dari database
        $hapus = mysqli_query($conn, "DELETE FROM instruksi WHERE idinstruksi = '$idi'");

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
//update info instruksi
if (isset($_POST['updatedatainstruksi'])) {
    $idi = $_POST['idi'];
    $namainstruksi = $_POST['namainstruksi'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    if ($_FILES['file']['size'] > 0) {
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Validasi ukuran file
        $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte

        if (
            $fileSize <= $maxFileSize
        ) {
            // Validasi tipe file
            $allowedFileTypes = ['application/pdf'];
            if (in_array($fileType, $allowedFileTypes)) {
                $uploadDir = "../assets/instruksi/pdf/";
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueFileName = uniqid() . '.' . $fileExtension;

                $uploadedFile = $uploadDir . $uniqueFileName;

                // Upload file
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                    // Hapus file PDF lama jika perlu
                    $ambilFileLama = mysqli_query($conn, "SELECT file FROM instruksi WHERE idinstruksi = '$idi'");
                    $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
                    $fileLama = $dataFileLama['file'];
                    $fileLamaPath = $uploadDir . $fileLama;

                    if (file_exists($fileLamaPath)) {
                        unlink($fileLamaPath); // Hapus file PDF lama
                    }

                    // Update data instruksi dengan file PDF baru dan tanggal_update
                    $update = mysqli_query($conn, "UPDATE instruksi SET namainstruksi = '$namainstruksi', deskripsi = '$deskripsi', file = '$uniqueFileName', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE idinstruksi = '$idi'");

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
                        'message' => 'Gagal Mengunggah File Baru !'
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
    } else {
        // Jika tidak ada file baru diunggah
        $update = mysqli_query($conn, "UPDATE instruksi SET namainstruksi = '$namainstruksi', deskripsi = '$deskripsi', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE idinstruksi = '$idi'");

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
    }
}

//menambah admin katalog
if (isset($_POST['addadminkatalog'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    $usernameValue = $username;
    $passwordValue = $password;
    $namaValue = $nama;
    $emailValue = $email;
    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user_katalog WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {

        $queryinsert = mysqli_query($conn, "insert into user_katalog (username, password,nama,email, role) values('$username', '$password','$nama','$email','admin_katalog')");

        if ($queryinsert) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah Admin !'
            ];
            $usernameValue = '';
            $passwordValue = '';
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah Admin !'
            ];
        }
    }
}
//nambah user katalog
if (isset($_POST['adduserkatalog'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];

    $usernameValue = $username;
    $namaValue = $nama;
    $emailValue = $email;
    $passwordValue = $password;
    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user_katalog WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Jika username belum ada, maka lakukan INSERT
        $queryinsert = mysqli_query($conn, "INSERT INTO user_katalog (username, password,nama,email, role) VALUES ('$username','$password','$nama','$email','user_katalog')");

        if ($queryinsert) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah User !'
            ];

            $usernameValue = '';
            $namaValue = '';
            $emailValue = '';
            $passwordValue = '';
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah user  ! '
            ];
        }
    }
}
//nambah vendor
if (isset($_POST['addvendor'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $nama = isset($_POST['nama']) ? $_POST['nama'] : null;
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : null;
    $deskripsi = isset($_POST['deskripsi']) ? $_POST['deskripsi'] : null;

    $usernameValue = $username;
    $namaValue = $nama;
    $emailValue = $email;
    $passwordValue = $password;
    $deskripsiValue = $deskripsi;
    $alamatValue = $alamat;

    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user_katalog WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Jika username belum ada, maka lakukan INSERT
        $queryinsert = mysqli_query($conn, "INSERT INTO user_katalog (username, password,nama,alamat, role, deskripsi,email) VALUES ('$username', '$password','$nama','$alamat', 'suplier','$deskripsi','$email')");

        if ($queryinsert) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah vendor !'
            ];

            $usernameValue = '';
            $namaValue = '';
            $emailValue = '';
            $passwordValue = '';
            $deskripsiValue = '';
            $alamatValue = '';
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah vendor  ! '
            ];
        }
    }
}
//edit vendor
if (isset($_POST['updatevendor'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $deskripsi = $_POST['deskripsi'];

    function updateVendorInKatalog($conn, $id, $nama)
    {
        $updateVendorQuery = mysqli_query($conn, "UPDATE katalog SET vendor = '$nama' WHERE idvendor = '$id'");
        return $updateVendorQuery;
    }
    function updateKeranjang($conn, $id, $nama)
    {
        $updateKeranjangQuery = mysqli_query($conn, "UPDATE keranjang SET vendor = '$nama' WHERE idvendor = '$id' ");
        return $updateKeranjangQuery;
    }

    function updateVendorInDetailPesan($conn, $id, $nama)
    {
        $updateDetailPesanQuery = mysqli_query($conn, "UPDATE detailpesan SET vendor = '$nama' WHERE idvendor = '$id'");
        return $updateDetailPesanQuery;
    }

    function updateVendorInRiwayat($conn, $id, $nama)
    {
        $updateRiwayatVendorQuery = mysqli_query($conn, "UPDATE riwayat SET vendor = '$nama' WHERE idvendor = '$id'");
        return $updateRiwayatVendorQuery;
    }

    $check_query = mysqli_query($conn, "SELECT * FROM user_katalog WHERE username='$username' AND id <> '$id'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        $queryupdate = mysqli_query($conn, "UPDATE user_katalog SET username = '$username', password = '$password', alamat = '$alamat', nama = '$nama', deskripsi = '$deskripsi', email = '$email' WHERE id = '$id'");

        if ($queryupdate) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Mengedit Data User !'
            ];

            $updateVendorQueryResult = updateVendorInKatalog($conn, $id, $nama);

            if ($updateVendorQueryResult) {
                $_SESSION['notification'] = [
                    'type' => 'success',
                    'message' => 'Berhasil Memperbarui Vendor!'
                ];

                $updatekeranjangQueryResult = updateKeranjang($conn, $id, $nama);

                if ($updateVendorQueryResult) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Memperbarui Vendor!'
                    ];

                    $updateDetailPesanQueryResult = updateVendorInDetailPesan($conn, $id, $nama);

                    if ($updateDetailPesanQueryResult) {
                        $_SESSION['notification'] = [
                            'type' => 'success',
                            'message' => 'Berhasil Memperbarui Vendor!'
                        ];

                        $updateRiwayatQueryResult = updateVendorInRiwayat($conn, $id, $nama);

                        if ($updateRiwayatQueryResult) {
                            $_SESSION['notification'] = [
                                'type' => 'success',
                                'message' => 'Berhasil Memperbarui Vendor!'
                            ];
                        } else {
                            $_SESSION['notification'] = [
                                'type' => 'danger',
                                'message' => 'Gagal Memperbarui Vendor!'
                            ];
                        }
                    } else {
                        $_SESSION['notification'] = [
                            'type' => 'danger',
                            'message' => 'Gagal Memperbarui Vendor di Detail Pesan!'
                        ];
                    }
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal Memperbarui Vendor di Katalog!'
                    ];
                }
            } else {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Mengedit Data User !'
                ];
            }
        }
    }
}
//edit user katalog
if (isset($_POST['updateuserkatalog'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $id = $_POST['id'];

    // Periksa apakah username baru sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user_katalog WHERE username='$username' AND id <> '$id'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Jika username belum ada, maka lakukan UPDATE
        $queryupdate = mysqli_query($conn, "UPDATE user_katalog SET username = '$username', password = '$password', nama = '$nama', email = '$email' WHERE id = '$id'");

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
//hapus user katalog
if (isset($_POST['hapususerkatalog'])) {
    $id = $_POST['id'];

    $querydelete = mysqli_query($conn, "delete from user_katalog where id = '$id'");

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

//menambah admin baru
if (isset($_POST['addadmin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $nama = $_POST['nama'];

    $usernameValue = $username;
    $namaValue = $nama;
    $emailValue = $email;
    $passwordValue = $password;
    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {

        $queryinsert = mysqli_query($conn, "insert into user (username,nama, password,email, role) values('$username','$nama', '$password','$email', 'admin')");

        if ($queryinsert) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah Admin !'
            ];
            $usernameValue = '';
            $emailValue = '';
            $passwordValue = '';
            $namaValue = '';
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah Admin !'
            ];
        }
    }
}
//nambah user
if (isset($_POST['adduser'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $nama = $_POST['nama'];

    $usernameValue = $username;
    $namaValue = $nama;
    $emailValue = $email;
    $passwordValue = $password;

    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Jika username belum ada, maka lakukan INSERT
        $queryinsert = mysqli_query($conn, "INSERT INTO user (username,nama, password, email, role) VALUES ('$username','$nama', '$password', '$email', 'user')");

        if ($queryinsert) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah User !'
            ];
            $usernameValue = '';
            $emailValue = '';
            $passwordValue = '';
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambah user  ! '
            ];
        }
    }
}
//edit user
if (isset($_POST['updateuser'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $id = $_POST['id'];
    $nama = $_POST['nama'];

    // Periksa apakah username baru sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND id <> '$id'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Jika username belum ada, maka lakukan UPDATE
        $queryupdate = mysqli_query($conn, "UPDATE user SET username ='$username', password = '$password', email = '$email',nama = '$nama' WHERE id = '$id'");

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


//verifikasi data kebijakan direktorat
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
//batal verifikasi data kebijakan direktorat
if (isset($_POST['tidakverifdatakebijakan'])) {
    $idk = $_POST['idk'];
    $kesalahan = $_POST['kesalahan'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update kebijakan set status = 2, kesalahan = '$kesalahan' WHERE idkebijakan = $idk");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Tidak Terverifikasi !'
        ];
    } else {
        header('location:index.php');
    }
}
//verifikasi data kebijakan fakultas
if (isset($_POST['verifikasifakultas'])) {
    $id = $_POST['id'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update fakultas set status = 1 WHERE id = $id");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil Verifikasi !'
        ];
    } else {
        header('location:index.php');
    }
}
//batal verifikasi data kebijakan fakultas
if (isset($_POST['tidakverifikasifakultas'])) {
    $id = $_POST['id'];
    $kesalahan = $_POST['kesalahan'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update fakultas set status = 2, kesalahan = '$kesalahan'  WHERE id = $id");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Tidak Terverifikasi !'
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
//batal verifikasi data prosedur
if (isset($_POST['tidakverifdataprosedur'])) {
    $idp = $_POST['idp'];
    $kesalahan = $_POST['kesalahan'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update prosedur set status = 2, kesalahan = '$kesalahan' WHERE idprosedur = $idp");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Tidak Terverifikasi !'
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
//batal verifikasi data standar
if (isset($_POST['tidakverifdatastandar'])) {
    $ids = $_POST['ids'];
    $kesalahan = $_POST['kesalahan'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update standar set status = 2, kesalahan = '$kesalahan' WHERE idstandar = $ids");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Tidak Terverifikasi !'
        ];
    } else {
        header('location:standar.php');
    }
}
//verifikasi data instruksi
if (isset($_POST['verifikasidatainstruksi'])) {
    $idi = $_POST['idi'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update instruksi set status = 1 WHERE idinstruksi = $idi");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil Verifikasi !'
        ];
    } else {
        header('location:instruksi.php');
    }
}
//batal verifikasi data instruksi
if (isset($_POST['tidakverifdatainstruksi'])) {
    $idi = $_POST['idi'];
    $kesalahan = $_POST['kesalahan'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "UPDATE instruksi set status = 2, kesalahan = '$kesalahan' WHERE idinstruksi = $idi");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Tidak Terverifikasi !'
        ];
    } else {
        header('location:instruksi.php');
    }
}
