<?php
session_start();

//koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "siska");

//tambah tipe 
if (isset($_POST['addnewtipe'])) {
    $namatipe = $_POST['namatipe'];

    if (empty($namatipe)) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'nama tipe tidak boleh kosong!'
        ];
    }

    $query = mysqli_query($conn, "INSERT INTO tipe (namatipe) VALUES ('$namatipe')");

    if ($query) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Berhasil menambah tipe!'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal menambah tipe!'
        ];
    }
}
//edit tipe
if (isset($_POST['edittipe'])) {
    $idtipe = $_POST['idtipe'];
    $namatipe = $_POST['namatipe'];

    // Query SQL untuk mengupdate tipe
    $query = mysqli_query($conn, "UPDATE tipe SET namatipe = '$namatipe' WHERE idtipe = $idtipe");

    if ($query) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Tipe berhasil diubah!'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal mengubah tipe.'
        ];
    }
}
//hapus tipe 
if (isset($_POST['hapustipe'])) {
    $idtipe = $_POST['id'];

    // Query SQL untuk menghapus tipe
    $query = mysqli_query($conn, "DELETE FROM tipe WHERE idtipe = $idtipe");

    if ($query) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'Tipe berhasil dihapus!'
        ];
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Gagal menghapus tipe, Masih ada data didalamnya.'
        ];
    }
}

//tambah data barang
if (isset($_POST['addnewdatabarang'])) {
    $namabarang = $_POST['namabarang'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];
    $vendor = $_POST['vendor'];
    $idtipe = $_POST['idtipe'];
    $idkatalog = $_POST['idkatalog'];
    $deskripsi = $_POST['deskripsi'];

    // Membuat nama file unik dengan timestamp
    $uniqueFileName = time() . '_' . $_FILES['foto']['name'];
    $foto = $uniqueFileName;

    if ($jumlah > 0) {
        // Validasi foto
        $maxFileSize = 307200; // 300kb dalam byte
        if ($_FILES['foto']['size'] <= $maxFileSize) {
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

            if (in_array($_FILES['foto']['type'], $allowedFileTypes)) {
                // Direktori untuk menyimpan file
                $uploadDir = "../assets/img/";
                $uploadedFile = $uploadDir . $uniqueFileName;

                // Upload file
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadedFile)) {
                    // Simpan data ke database
                    $tambahdatabarang = mysqli_query($conn, "INSERT INTO katalog (idkatalog, namabarang,deskripsi, vendor,foto, harga,jumlah,idtipe) VALUES (38, '$namabarang','$deskripsi','$vendor','$foto', '$harga','$jumlah','$idtipe')");

                    if ($tambahdatabarang) {
                        $_SESSION['notification'] = [
                            'type' => 'success',
                            'message' => 'Berhasil menambah Data!'
                        ];
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
                    'message' => 'Hanya file JPEG atau PNG dengan ukuran maksimum 2MB yang diterima!'
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
            'message' => 'Gagal menambah Data, Jumlah tidak valid !'
        ];
    }
}
//hapus data barang
if (isset($_POST['hapusdatabarang'])) {
    $id = $_POST['id'];
    $idtipe = $_POST['idtipe'];

    // Cek apakah ada relasi ke tabel lain
    $cek_relasi = mysqli_query($conn, "SELECT * FROM pesanan WHERE idbarang = '$id'");
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
            $filePath = "../assets/img/" . $fileToDelete;
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menghapus Data !'
            ];
            header("location:katalog.php?idtipe=$idtipe");
            exit();
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menghapus Data !'
            ];
            header("location:katalog.php?idtipe=$idtipe");
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

    $uniqueFileName = time() . '_' . $_FILES['foto']['name'];
    $foto = $uniqueFileName;

    // Cek apakah file gambar diunggah
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $maxFileSize = 307200; // 2MB dalam byte
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        // Validasi ukuran dan tipe file
        if ($_FILES['foto']['size'] <= $maxFileSize && in_array($_FILES['foto']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/img/";
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

            // Upload file baru
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadedFile)) {
                // Update data barang dengan file foto baru
                $update = mysqli_query($conn, "UPDATE katalog SET namabarang = '$namabarang', vendor = '$vendor',deskripsi = '$deskripsi', foto = '$foto', harga = '$harga', jumlah = '$jumlah' WHERE id = '$id'");

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
                'message' => 'Hanya file JPEG atau PNG dengan ukuran maksimum 2MB yang diterima !'
            ];
        }
    } else {
        // Jika tidak ada file yang diunggah, gunakan file lama
        $ambilFileLama = mysqli_query($conn, "SELECT foto FROM katalog WHERE id = '$id'");
        $dataFileLama = mysqli_fetch_assoc($ambilFileLama);
        $foto = $dataFileLama['foto'];

        $update = mysqli_query($conn, "UPDATE katalog SET namabarang = '$namabarang',deskripsi ='$deskripsi',vendor = '$vendor', foto = '$foto', harga = '$harga', jumlah = '$jumlah' WHERE id = '$id'");

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
//pesan barang
if (isset($_POST['pesandatabarang'])) {
    $id = $_POST['id'];
    $namabarang = $_POST['namabarang'];
    $jumlah_pesan = $_POST['jumlah'];
    $idtipe = $_POST['idtipe'];
    $vendor = $_POST['vendor'];

    // Ambil username dari sesi
    $username = $_SESSION['username'];

    $ambildata = mysqli_query($conn, "SELECT * FROM katalog WHERE id='$id'");
    $data = mysqli_fetch_assoc($ambildata);
    $stok = $data['jumlah'];

    if ($jumlah_pesan <= $stok && $jumlah_pesan > 0) {
        $harga_awal = $data['harga'];
        $total_harga = $jumlah_pesan * $data['harga'];

        // Cek apakah barang sudah pernah dipesan oleh pengguna ini
        $cek_pesanan = mysqli_query($conn, "SELECT * FROM pesanan WHERE idbarang='$id' AND username='$username'");
        $data_pesanan = mysqli_fetch_assoc($cek_pesanan);

        if ($data_pesanan) {
            // Jika sudah ada pesanan, update jumlahnya
            $update_pesanan = mysqli_query($conn, "UPDATE pesanan SET jumlah= jumlah + '$jumlah_pesan' WHERE idbarang='$id' AND username='$username'");
            if (!$update_pesanan) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Update Pesanan!'
                ];
            }
        } else {
            // Jika belum ada pesanan, buat pesanan baru
            $simpan_pesanan = mysqli_query($conn, "INSERT INTO pesanan (idbarang, namabarang,vendor, foto, jumlah, harga,harga_awal, username, tanggal,idtipe) VALUES ('$id', '$namabarang','$vendor', '{$data['foto']}', '$jumlah_pesan', '$total_harga','$harga_awal', '$username', now(),'$idtipe')");

            if (!$simpan_pesanan) {
                $_SESSION['notification'] = [
                    'type' => 'danger',
                    'message' => 'Gagal Pesan Barang !'
                ];
            }
        }

        // Update stok
        $update_stok = mysqli_query($conn, "UPDATE katalog SET jumlah=jumlah-'$jumlah_pesan' WHERE id='$id'");
        if (!$update_stok) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Update Stok!'
            ];
        }

        // Cek apakah ada notifikasi, jika tidak ada maka berhasil
        if (!isset($_SESSION['notification'])) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Pesan Barang !'
            ];
        }

        header("location:katalog.php?idtipe=$idtipe");
        exit();
    } else {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Jumlah Pesanan Tidak Valid atau Melebihi Stok !'
        ];
        header("location:katalog.php?idtipe=$idtipe");
        exit();
    }
}

// Edit pesanan barang SUPERADMIN
if (isset($_POST['editpesan'])) {
    $id = $_POST['id'];
    $jumlah_pesanan = $_POST['jumlah'];

    // Ambil username dari sesi
    $username = $_SESSION['username'];

    // Ambil data pesanan dari database
    $ambildata = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id'");
    $data = mysqli_fetch_assoc($ambildata);

    if ($data) {
        $idbarang = $data['idbarang'];
        $id = $data['id'];
        $jumlah_sebelumnya = $data['jumlah'];

        // Periksa apakah stok mencukupi untuk edit pesanan
        $ambildata_barang = mysqli_query($conn, "SELECT * FROM katalog WHERE id='$idbarang'");
        $data_barang = mysqli_fetch_assoc($ambildata_barang);
        $stok = $data_barang['jumlah'];

        if ($jumlah_pesanan >= 0 && $jumlah_pesanan <= $stok + $jumlah_sebelumnya) {
            $total_harga = $jumlah_pesanan * $data_barang['harga'];

            // Update jumlah pesanan
            $update_pesanan = mysqli_query($conn, "UPDATE pesanan SET jumlah='$jumlah_pesanan' WHERE id='$id'");

            if ($jumlah_pesanan == 0) {
                // Hapus pesanan jika jumlah menjadi 0
                $delete_pesanan = mysqli_query($conn, "DELETE FROM pesanan WHERE id='$id'");
            }

            if ($update_pesanan) {
                // Update total harga
                $update_total_harga = mysqli_query($conn, "UPDATE pesanan SET harga='$total_harga' WHERE id='$id'");

                if ($update_total_harga) {
                    // Update stok di katalog
                    $jumlah_kurang = $jumlah_sebelumnya - $jumlah_pesanan;
                    $update_stok_katalog = mysqli_query($conn, "UPDATE katalog SET jumlah=jumlah+'$jumlah_kurang' WHERE id='$idbarang'");

                    if (!$update_stok_katalog) {
                        $_SESSION['notification'] = [
                            'type' => 'danger',
                            'message' => 'Gagal Update Stok di Katalog!'
                        ];
                    }
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal Edit Pesanan !'
                    ];
                }
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

//edit pesanan barang user
if (isset($_POST['editpesanan'])) {
    $namabarang = $_POST['namabarang'];
    $jumlah_pesanan = $_POST['jumlah'];

    // Ambil username dari sesi
    $username = $_SESSION['username'];

    // Ambil data pesanan dari database
    $ambildata = mysqli_query($conn, "SELECT * FROM pesanan WHERE namabarang='$namabarang' AND username='$username'");
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
            $update_pesanan = mysqli_query($conn, "UPDATE pesanan SET jumlah='$jumlah_pesanan' WHERE idbarang='$id' AND username='$username'");

            if ($jumlah_pesanan == 0) {
                // Hapus pesanan jika jumlah menjadi 0
                $delete_pesanan = mysqli_query($conn, "DELETE FROM pesanan WHERE idbarang='$id' AND username='$username'");
            }

            if ($update_pesanan) {
                // Update total harga
                $update_total_harga = mysqli_query($conn, "UPDATE pesanan SET harga='$total_harga' WHERE idbarang='$id' AND username='$username'");

                if ($update_total_harga) {
                    // Update stok di katalog
                    $jumlah_kurang = $jumlah_sebelumnya - $jumlah_pesanan;
                    $update_stok_katalog = mysqli_query($conn, "UPDATE katalog SET jumlah=jumlah+'$jumlah_kurang' WHERE id='$id'");

                    if (!$update_stok_katalog) {
                        $_SESSION['notification'] = [
                            'type' => 'danger',
                            'message' => 'Gagal Update Stok di Katalog!'
                        ];
                    }
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Gagal Edit Pesanan !'
                    ];
                }
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
//hapus pesan barang
if (isset($_POST['hapuspesan'])) {
    $id = $_POST['id'];

    // Ambil data pesanan dari database
    $ambildata = mysqli_query($conn, "SELECT * FROM pesanan WHERE id='$id'");
    $data = mysqli_fetch_assoc($ambildata);

    if ($data) {
        $idbarang = $data['idbarang'];
        $jumlah_pesanan = $data['jumlah'];

        // Hapus pesanan dari database
        $query = mysqli_query($conn, "DELETE FROM pesanan WHERE id = $id");

        if ($query) {
            // Perbarui jumlah di katalog
            $update_stok_katalog = mysqli_query($conn, "UPDATE katalog SET jumlah=jumlah+'$jumlah_pesanan' WHERE id='$idbarang'");

            if ($update_stok_katalog) {
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

//tambah data Kebijakan Direktorat 
if (isset($_POST['addnewdatakebijakan'])) {
    $namakebijakan = $_POST['namakebijakan'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/kebijakan/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahkebijakan = mysqli_query($conn, "INSERT INTO kebijakan (namakebijakan, deskripsi, file, tanggal, status) VALUES ('$namakebijakan', '$deskripsi', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahkebijakan) {
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
                $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

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

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/fakultas/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahfakultas = mysqli_query($conn, "INSERT INTO fakultas (namakebijakan, deskripsi,fakultas, file, tanggal, status) VALUES ('$namakebijakan', '$deskripsi','$fakultas', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahfakultas) {
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

    if (
        $_FILES['file']['size'] > 0
    ) {
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
                $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

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
                    $update = mysqli_query($conn, "UPDATE fakultas SET namakebijakan = '$namakebijakan', deskripsi = '$deskripsi', file = '$uniqueFileName', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE id = '$id'");

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
            "UPDATE fakultas SET namakebijakan = '$namakebijakan', deskripsi = '$deskripsi', tanggal = '$tanggal', kesalahan = NULL, status = 0 WHERE id = '$id'"
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

//tambah data standar
if (isset($_POST['addnewdatastandar'])) {
    $namastandar = $_POST['namastandar'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/standar/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahstandar = mysqli_query($conn, "INSERT INTO standar (namastandar, deskripsi, file, tanggal, status) VALUES ('$namastandar', '$deskripsi', '$uniqueFileName', '$tanggal', 0)");

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
    $idk = $_POST['ids'];

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
                $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

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

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/prosedur/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahprosedur = mysqli_query($conn, "INSERT INTO prosedur (namaprosedur, deskripsi, file, tanggal, status) VALUES ('$namaprosedur', '$deskripsi', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahprosedur) {
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
                $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

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

    $fileName = $_FILES['file']['name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = $_FILES['file']['type'];

    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($fileSize <= $maxFileSize) {
        $allowedFileTypes = ['application/pdf'];
        if (in_array($fileType, $allowedFileTypes)) {
            $uploadDir = "../assets/instruksi/pdf/";
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

            $uploadedFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                $tambahinstruksi = mysqli_query($conn, "INSERT INTO instruksi (namainstruksi, deskripsi, file, tanggal, status) VALUES ('$namainstruksi', '$deskripsi', '$uniqueFileName', '$tanggal', 0)");

                if ($tambahinstruksi) {
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
                $uniqueFileName = time() . '_' . uniqid() . '.' . $fileExtension;

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

//menambah admin baru
if (isset($_POST['addadmin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {

        $queryinsert = mysqli_query($conn, "insert into user (username, password,email, role) values('$username', '$password','$email', 'admin')");

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
    $emailbaru = $_POST['emailbaru'];
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

        $queryupdate = mysqli_query($conn, "update user set username = '$usernamebaru', email = '$emailbaru', password = '$passwordbaru' where id = '$idnya'");

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
//nambah user
if (isset($_POST['adduser'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Periksa apakah username sudah ada
    $check_query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username sudah ada, silakan pilih yang lain!'
        ];
    } else {
        // Jika username belum ada, maka lakukan INSERT
        $queryinsert = mysqli_query($conn, "INSERT INTO user (username, password, email, role) VALUES ('$username', '$password', '$email', 'user')");

        if ($queryinsert) {
            $_SESSION['notification'] = [
                'type' => 'success',
                'message' => 'Berhasil Menambah User !'
            ];
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
    $usernamebaru = $_POST['usernameuser'];
    $emailbaru = $_POST['emailbaru'];
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
        $queryupdate = mysqli_query($conn, "UPDATE user SET username = '$usernamebaru', password = '$passwordbaru', email = '$emailbaru' WHERE id = '$id'");

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
if (isset($_POST['tolakfakultas'])) {
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
    $idi = $_POST['idp'];

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
