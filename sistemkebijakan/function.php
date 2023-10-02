<?php
session_start();


//koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "siska");

//tambah proposal
if (isset($_POST['tambahproposal'])) {
    $namakegiatan = $_POST['namakegiatan'];
    $tujuankegiatan = $_POST['tujuankegiatan'];
    $dasarpelaksanaan = $_POST['dasarpelaksanaan'];
    $waktupelaksanaan = $_POST['waktupelaksanaan'];
    $waktuselesai = $_POST['waktuselesai'];
    $tempatpelaksanaan = $_POST['tempatpelaksanaan'];
    $pesertakegiatan = $_POST['pesertakegiatan'];
    $targetluaran = $_POST['targetluaran'];
    $totalanggaran = $_POST['totalanggaran'];
    $unitpelaksana = $_POST['unitpelaksana'];
    $sumberdana = $_POST['sumberdana'];

    // Lampiran
    $lampiran = $_FILES['lampiran']['name'];
    $lampiran_temp = $_FILES['lampiran']['tmp_name'];
    $lampiran_path = "../assets/pdf/$lampiran"; // Sesuaikan dengan path Anda

    move_uploaded_file($lampiran_temp, $lampiran_path);

    // penutup
    $penutup = $_FILES['penutup']['name'];
    $penutup_temp = $_FILES['penutup']['tmp_name'];
    $penutup_path = "../assets/png/$penutup"; // Sesuaikan dengan path Anda

    move_uploaded_file($penutup_temp, $penutup_path);

    $username = $_SESSION['username'];

    // Simpan data ke tabel proposal
    $query = "INSERT INTO proposal (nama_kegiatan, tujuan_kegiatan, dasar_pelaksanaan, waktu_pelaksanaan, waktu_selesai, tempat_pelaksanaan,  peserta_kegiatan, target_luaran, total_anggaran, unit_pelaksana, sumber_dana, penutup, lampiran, status, verif_daku,verif_lpm,username)
              VALUES ('$namakegiatan', '$tujuankegiatan', '$dasarpelaksanaan', '$waktupelaksanaan','$waktuselesai', '$tempatpelaksanaan', '$pesertakegiatan', '$targetluaran','$totalanggaran','$unitpelaksana','$sumberdana','$penutup', '$lampiran', 0, 0, 0,'$username')";

    mysqli_query($conn, $query);

    $proposal_id = mysqli_insert_id($conn); // Dapatkan ID proposal yang baru saja dimasukkan

    // Simpan data ke tabel indikator_kinerja
    if (isset($_POST['kodeiks']) && isset($_POST['iks']) && isset($_POST['rasional'])) {
        $kode_iks = $_POST['kodeiks'];
        $iks = $_POST['iks'];
        $rasional = $_POST['rasional'];

        // Simpan data ke tabel indikator_kinerja
        $query = "INSERT INTO indikator_kinerja (proposal_id, kode_iks, iks, rasional)
                  VALUES ('$proposal_id', '$kode_iks', '$iks', '$rasional')";

        mysqli_query($conn, $query);
    }
    // Simpan data ke tabel rencana_anggaran
    foreach ($_POST['jenis'] as $key => $value) {
        $jenis_pengeluaran = $_POST['jenis'][$key];
        $satuan = $_POST['satuan'][$key];
        $jumlah = $_POST['jumlah'][$key];
        $harga_satuan = $_POST['harga'][$key];
        $kurs = $_POST['kurs'][$key];
        $kode_anggaran = $_POST['kode'][$key];
        $subtotal = $_POST['sub'][$key];

        $queryRencana = "INSERT INTO rencana_anggaran (proposal_id, jenis_pengeluaran, satuan, jumlah, harga_satuan, kurs, kode_anggaran, subtotal) 
        VALUES ('$proposal_id', '$jenis_pengeluaran', '$satuan', '$jumlah', '$harga_satuan', '$kurs','$kode_anggaran','$subtotal')";
        mysqli_query($conn, $queryRencana);
    }
    // Redirect atau berikan notifikasi sesuai kebutuhan
    header("location: proposal.php"); // Ganti dengan halaman yang sesuai

}

//edit proposal 
if (isset($_POST['editproposal'])) {
    $proposal_id = $_GET['id'];
    $namakegiatan = $_POST['namakegiatan'];
    $tujuankegiatan = $_POST['tujuankegiatan'];
    $dasarpelaksanaan = $_POST['dasarpelaksanaan'];
    $waktupelaksanaan = $_POST['waktupelaksanaan'];
    $waktuselesai = $_POST['waktuselesai'];
    $tempatpelaksanaan = $_POST['tempatpelaksanaan'];
    $pesertakegiatan = $_POST['pesertakegiatan'];
    $targetluaran = $_POST['targetluaran'];
    $totalanggaran = $_POST['totalanggaran'];
    $unitpelaksana = $_POST['unitpelaksana'];
    $sumberdana = $_POST['sumberdana'];
    // Lakukan pembaruan ke tabel proposal
    $query = "UPDATE proposal SET
              nama_kegiatan = '$namakegiatan',
              tujuan_kegiatan = '$tujuankegiatan',
              dasar_pelaksanaan = '$dasarpelaksanaan',
              waktu_pelaksanaan = '$waktupelaksanaan',
              waktu_selesai = '$waktuselesai',
              tempat_pelaksanaan = '$tempatpelaksanaan',
              peserta_kegiatan = '$pesertakegiatan',
              target_luaran = '$targetluaran',
              total_anggaran = '$totalanggaran',
              unit_pelaksana = '$unitpelaksana',
              sumber_dana = '$sumberdana',
              status = 0,
              verif_daku = 0,
              verif_lpm = 0
              WHERE id = '$proposal_id'";

    mysqli_query($conn, $query);

    // Lakukan pembaruan ke tabel indikator_kinerja
    if (isset($_POST['kodeiks']) && isset($_POST['iks']) && isset($_POST['rasional'])) {
        $kode_iks = $_POST['kodeiks'];
        $iks = $_POST['iks'];
        $rasional = $_POST['rasional'];

        $query = "UPDATE indikator_kinerja SET
                  kode_iks = '$kode_iks',
                  iks = '$iks',
                  rasional = '$rasional'
                  WHERE proposal_id = '$proposal_id'";

        mysqli_query($conn, $query);
    }
    // Hapus rencana anggaran yang terkait
    $deleteQuery = "DELETE FROM rencana_anggaran WHERE proposal_id = '$proposal_id'";
    mysqli_query($conn, $deleteQuery);


    // Lakukan pembaruan atau penambahan data rencana anggaran
    foreach ($_POST['jenis'] as $key => $value) {
        $jenis_pengeluaran = $_POST['jenis'][$key];
        $satuan = $_POST['satuan'][$key];
        $jumlah = $_POST['jumlah'][$key];
        $harga_satuan = $_POST['harga'][$key];
        $kurs = $_POST['kurs'][$key];
        $kode_anggaran = $_POST['kode'][$key];
        $subtotal = $_POST['sub'][$key];

        $queryRencana = "INSERT INTO rencana_anggaran (proposal_id, jenis_pengeluaran, satuan, jumlah, harga_satuan, kurs, kode_anggaran, subtotal) 
        VALUES ('$proposal_id', '$jenis_pengeluaran', '$satuan', '$jumlah', '$harga_satuan', '$kurs','$kode_anggaran','$subtotal')";
        mysqli_query($conn, $queryRencana);
    }

    // Redirect atau berikan notifikasi sesuai kebutuhan
    header("location: proposal.php");
}
//hapus data proposal
if (isset($_POST['hapusdataproposal'])) {
    $id = $_POST['id'];

    // Query untuk menghapus indikator_kinerja
    $query = "DELETE FROM indikator_kinerja WHERE proposal_id = $id";
    mysqli_query($conn, $query);

    // Query untuk menghapus rencana_anggaran
    $query = "DELETE FROM rencana_anggaran WHERE proposal_id = $id";
    mysqli_query($conn, $query);

    // Query untuk menghapus proposal
    $query = "DELETE FROM proposal WHERE id = $id";
    mysqli_query($conn, $query);

    // Redirect atau berikan notifikasi sesuai kebutuhan
    header("location: proposal.php"); // Ganti dengan halaman yang sesuai
}

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

//tambah data instruksi
if (isset($_POST['addnewdatainstruksi'])) {
    $namainstruksi = $_POST['namainstruksi'];
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
            $uploadDir = "../assets/instruksi/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

            // Upload file
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
                // Simpan data ke database
                $tambahinstruksi = mysqli_query($conn, "INSERT INTO instruksi (namainstruksi, deskripsi, file, tanggal, status) VALUES ('$namainstruksi', '$deskripsi', '$file', '$tanggal',0)");

                if ($tambahinstruksi) {
                    $_SESSION['notification'] = [
                        'type' => 'success',
                        'message' => 'Berhasil Menambah Data !'
                    ];
                } else {
                    header('location:instruksi.php');
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
    $file = $_FILES['file']['name']; // Gunakan 'newfile' sebagai nama input file

    // Validasi ukuran file
    $maxFileSize = 2 * 1024 * 1024; // 2MB dalam byte
    if ($_FILES['file']['size'] <= $maxFileSize) {
        // Validasi tipe file
        $allowedFileTypes = ['application/pdf'];
        if (in_array($_FILES['file']['type'], $allowedFileTypes)) {
            // Direktori untuk menyimpan file
            $uploadDir = "../assets/instruksi/pdf/";
            $uploadedFile = $uploadDir . $_FILES['file']['name'];

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
                $update = mysqli_query($conn, "UPDATE instruksi SET namainstruksi = '$namainstruksi', deskripsi = '$deskripsi', file = '$file', tanggal = '$tanggal', status = 0 WHERE idinstruksi = '$idi'");

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

//batal verifikasi data kebijakan
if (isset($_POST['tidakverifdatakebijakan'])) {
    $idk = $_POST['idk'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update kebijakan set status = 2 WHERE idkebijakan = $idk");

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

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update prosedur set status = 2 WHERE idprosedur = $idp");

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

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update standar set status = 2 WHERE idstandar = $ids");

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

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update instruksi set status = 2 WHERE idinstruksi = $idi");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Tidak Terverifikasi !'
        ];
    } else {
        header('location:instruksi.php');
    }
}

//verifikasi data proposal
if (isset($_POST['verifikasiproposallpm'])) {
    $id = $_POST['id'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update proposal set verif_lpm = 1 WHERE id = $id");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'LPM Berhasil Verfikasi'
        ];
    } else {
        header('location:proposal.php');
    }

    $cek = mysqli_query($conn, "SELECT * FROM proposal WHERE id = $id AND verif_lpm = 1 AND verif_daku = 1");

    if (mysqli_num_rows($cek) > 0) {
        // Kedua pihak sudah melakukan verifikasi, set status = 1
        mysqli_query($conn, "UPDATE proposal SET status = 1 WHERE id = $id");
    }
}

//verifikasi data proposal
if (isset($_POST['verifikasiproposaldaku'])) {
    $id = $_POST['id'];

    // Set status_verifikasi = 1
    $verif = mysqli_query($conn, "update proposal set verif_daku = 1 WHERE id = $id");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'success',
            'message' => 'DAKU Berhasil Verfikasi !'
        ];
    } else {
        header('location:proposal.php');
    }

    $cek = mysqli_query($conn, "SELECT * FROM proposal WHERE id = $id AND verif_lpm = 1 AND verif_daku = 1");

    if (mysqli_num_rows($cek) > 0) {
        // Kedua pihak sudah melakukan verifikasi, set status = 1
        mysqli_query($conn, "UPDATE proposal SET status = 1 WHERE id = $id");
    }
}

//batal verifikasi data proposal
if (isset($_POST['tolakproposallpm']) || isset($_POST['tolakproposaldaku'])) {
    $id = $_POST['id'];

    // Set status_verifikasi = 2
    $verif = mysqli_query($conn, "update proposal set status = 2, verif_daku = 0, verif_lpm = 0 WHERE id = $id");

    if ($verif) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Tidak Terverifikasi !'
        ];
    } else {
        header('location:proposal.php');
    }
}
