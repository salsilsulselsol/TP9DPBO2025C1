<?php

/******************************************
 Asisten Pemrogaman 13 & 14
******************************************/

// Memastikan semua file class di-include sekali saja
include_once("model/Mahasiswa.class.php");
include_once("model/TabelMahasiswa.class.php");
include_once("view/TampilMahasiswa.php");
include_once("presenter/ProsesMahasiswa.php"); // Meskipun ProsesMahasiswa di-include di TampilMahasiswa, lebih baik di sini juga untuk kejelasan

// Membuat instance dari View
$view = new TampilMahasiswa();
// Membuat instance dari Presenter (sebenarnya sudah dihandle di dalam TampilMahasiswa, tapi bisa juga di sini jika diperlukan untuk logika lain)
$presenter = new ProsesMahasiswa(); 

// Mengambil aksi dari URL (jika ada)
$action = $_GET['action'] ?? 'index'; // Default action adalah 'index' (menampilkan tabel)
$id = $_GET['id'] ?? null; // ID untuk operasi edit/delete

// Routing sederhana berdasarkan aksi
switch ($action) {
    case 'add':
        // Menampilkan form untuk menambah data
        $view->tampilForm('add');
        break;

    case 'submit_add':
        // Memproses data dari form tambah
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi sederhana (bisa ditambahkan validasi yang lebih lengkap)
            if (!empty($_POST['nim']) && !empty($_POST['nama']) && !empty($_POST['email']) && !empty($_POST['telp'])) {
                $data = [
                    'nim' => $_POST['nim'],
                    'nama' => $_POST['nama'],
                    'tempat' => $_POST['tempat'],
                    'tl' => $_POST['tl'],
                    'gender' => $_POST['gender'],
                    'email' => $_POST['email'],
                    'telp' => $_POST['telp']
                ];
                if ($presenter->tambahMahasiswa($data)) {
                    // Redirect kembali ke halaman utama dengan pesan sukses
                    header("Location: index.php?status=add_success");
                    exit();
                } else {
                    // Tampilkan form lagi dengan pesan error
                    $view->tampilForm('add', null, ['type' => 'danger', 'message' => 'Gagal menambahkan data.']);
                }
            } else {
                // Data tidak lengkap, tampilkan form lagi dengan pesan error
                 $view->tampilForm('add', null, ['type' => 'danger', 'message' => 'Semua field wajib diisi.']);
            }
        }
        break;

    case 'edit':
        // Menampilkan form untuk mengedit data
        if ($id !== null) {
            $view->tampilForm('edit', $id);
        } else {
            // Jika ID tidak ada, redirect ke halaman utama
            header("Location: index.php?status=invalid_id");
            exit();
        }
        break;

    case 'submit_edit':
        // Memproses data dari form edit
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $id !== null) {
             // Validasi sederhana
            if (!empty($_POST['nim']) && !empty($_POST['nama']) && !empty($_POST['email']) && !empty($_POST['telp'])) {
                $data = [
                    'nim' => $_POST['nim'],
                    'nama' => $_POST['nama'],
                    'tempat' => $_POST['tempat'],
                    'tl' => $_POST['tl'],
                    'gender' => $_POST['gender'],
                    'email' => $_POST['email'],
                    'telp' => $_POST['telp']
                ];
                if ($presenter->ubahMahasiswa($id, $data)) {
                    header("Location: index.php?status=edit_success");
                    exit();
                } else {
                    $view->tampilForm('edit', $id, ['type' => 'danger', 'message' => 'Gagal mengubah data.']);
                }
            } else {
                 $view->tampilForm('edit', $id, ['type' => 'danger', 'message' => 'Semua field wajib diisi.']);
            }
        } else {
            header("Location: index.php?status=invalid_action");
            exit();
        }
        break;

    case 'delete':
        // Menghapus data
        if ($id !== null) {
            if ($presenter->hapusMahasiswa($id)) {
                header("Location: index.php?status=delete_success");
                exit();
            } else {
                header("Location: index.php?status=delete_failed");
                exit();
            }
        } else {
            header("Location: index.php?status=invalid_id");
            exit();
        }
        break;

    case 'index':
    default:
        // Menampilkan tabel utama dan pesan status jika ada
        $status_message = "";
        if (isset($_GET['status'])) {
            switch ($_GET['status']) {
                case 'add_success':
                    $status_message = "Data mahasiswa berhasil ditambahkan!";
                    $view->tampilStatus($status_message, "success");
                    break;
                case 'edit_success':
                    $status_message = "Data mahasiswa berhasil diubah!";
                    $view->tampilStatus($status_message, "success");
                    break;
                case 'delete_success':
                    $status_message = "Data mahasiswa berhasil dihapus!";
                     $view->tampilStatus($status_message, "success");
                    break;
                case 'delete_failed':
                    $status_message = "Gagal menghapus data mahasiswa.";
                    $view->tampilStatus($status_message, "danger");
                    break;
                case 'invalid_id':
                    $status_message = "ID mahasiswa tidak valid.";
                    $view->tampilStatus($status_message, "danger");
                    break;
                case 'invalid_action':
                    $status_message = "Aksi tidak valid.";
                    $view->tampilStatus($status_message, "danger");
                    break;
                default:
                    $view->tampil(); // Tampilan default tanpa pesan khusus
                    break;
            }
        } else {
            $view->tampil(); // Tampilan default
        }
        break;
}

?>
