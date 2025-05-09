# Tugas Praktikum 9 DPBO 2025 C1
Tugasnya modifikasi kode yang udah ada supaya bisa mengelola data mahasiswa (CRUD - Create, Read, Update, Delete), program pake bahasa PHP dan menerapkan pola arsitektur Model-View-Presenter (MVP).

## Desain Program
Program ini dibagi jadi tiga bagian utama sesuai pola MVP:

1.  **Model**: Bagian ini urusannya sama data dan logika proses data.
    * `DB.class.php`: Kelas buat koneksi ke database MySQL dan eksekusi query dasar.
    * `Mahasiswa.class.php`: Kelas yang merepresentasikan satu objek mahasiswa. Isinya ya atribut-atribut mahasiswa kayak NIM, Nama, Email, dll.
    * `TabelMahasiswa.class.php`: Nah, kelas ini yang jadi jembatan ke tabel `mahasiswa` di database. Dia punya fungsi-fungsi buat ambil data (`SELECT`), nambah data (`INSERT`), ubah data (`UPDATE`), sama hapus data (`DELETE`). Kelas ini pakai `DB.class.php` buat konek sama database.

2.  **View**: Ini bagian yang tampil di layar pengguna (User Interface).
    * `KontrakView.php`: Ini semacam "kontrak" atau interface yang nentuin fungsi apa aja yang harus ada di View.
    * `TampilMahasiswa.php`: Kelas ini yang beneran nampilin data ke pengguna. Dia ngambil data dari Presenter terus diolah buat jadi HTML. Dia juga yang ngatur kapan nampilin tabel, kapan nampilin form tambah/edit.
    * `templates/skin.html`: Ini file template HTML-nya. Isinya kerangka halaman web, nanti bagian-bagian tertentu (kayak tabel data atau form) bakal diisi dinamis sama `TampilMahasiswa.php`.

3.  **Presenter**: Si Presenter ini jadi penghubung antara Model dan View.
    * `KontrakPresenter.php`: Sama kayak `KontrakView.php`, ini interface buat Presenter.
    * `ProsesMahasiswa.php`: Kelas ini yang jadi otaknya. Dia nerima perintah dari View (misalnya, "tampilin semua mahasiswa" atau "tambah mahasiswa baru"), terus dia minta data ke Model (`TabelMahasiswa.class.php`), olah datanya (kalau perlu), baru dikasih lagi ke View buat ditampilin. Dia juga yang nanggepin aksi dari pengguna, kayak pas nge-klik tombol simpan atau hapus.

## Penjelasan Alur Program

### 1. Nampilin Daftar Mahasiswa (Read)

1.  Kamu buka `index.php` di browser.
2.  `index.php` langsung nyiapin `TampilMahasiswa` (View) dan `ProsesMahasiswa` (Presenter).
3.  `index.php` bilang ke View, "Eh, tolong tampilin data mahasiswa dong!" (`$view->tampil()`).
4.  Si View (di `TampilMahasiswa.php`) nggak langsung ke database, tapi dia nanya ke Presenter (`ProsesMahasiswa.php`), "Presenter, minta data semua mahasiswa dong!" (`$this->prosesmahasiswa->prosesDataMahasiswa()`).
5.  Presenter lalu ngontak Model (`TabelMahasiswa.class.php`), "Model, tolong ambilin semua data dari tabel `mahasiswa` ya."
6.  Model eksekusi query `SELECT * FROM mahasiswa`, dapet datanya, terus dikasih balik ke Presenter.
7.  Presenter nerima data mentah dari Model, terus disiapin biar gampang dibaca sama View (misalnya, data per mahasiswa dijadiin objek).
8.  Presenter ngasih data yang udah rapi ke View.
9.  View seneng dapet data! Dia langsung nyiapin HTML buat nampilin tabel. Data NIM, Nama, Email, Telepon, dll. dimasukin ke tabel.
10. View ngambil template `skin.html`, terus bagian `DATA_TABLE_SECTION` diisi sama HTML tabel yang udah jadi. Bagian form dikosongin.
11. Taraa! Halaman web dengan tabel data mahasiswa muncul di browsermu.

### 2. Nambah Data Mahasiswa Baru (Create)

1.  **Nampilin Form Tambah:**
    * Kamu klik link "Tambah Mahasiswa". Browser manggil `index.php?action=add`.
    * `index.php` bilang ke View, "View, tolong tampilin form buat nambah data ya!" (`$view->tampilForm('add')`).
    * View nyiapin HTML buat form tambah data.
    * View ngambil `skin.html`, bagian `DATA_FORM_MAHASISWA` diisi HTML form, bagian tabel dikosongin.
    * Form tambah data muncul di browser.
2.  **Ngirim Data dari Form:**
    * Kamu isi semua kolom di form, terus klik tombol "Tambah".
    * Data dari form dikirim ke `index.php?action=submit_add` pake metode POST.
    * `index.php` nerima data ini, terus ngasih ke Presenter, "Presenter, ini ada data mahasiswa baru, tolong ditambahin ya!" (`$presenter->tambahMahasiswa($data)`).
    * Presenter ngontak Model, "Model, tolong masukin data ini ke tabel `mahasiswa`."
    * Model eksekusi query `INSERT INTO mahasiswa ...`.
    * Kalau berhasil, Model ngasih tau Presenter, Presenter ngasih tau `index.php`.
    * `index.php` ngarahin kamu balik ke halaman utama (`index.php?status=add_success`) sambil bawa pesan "Data berhasil ditambah!".
    * Kalau gagal (misalnya ada error pas query atau validasi nggak lolos), kamu bakal tetap di halaman form tambah, tapi ada pesan errornya, dan data yang udah kamu ketik nggak hilang.

### 3. Ngubah Data Mahasiswa (Update)

1.  **Nampilin Form Edit:**
    * Di tabel, kamu klik tombol "Edit" di salah satu baris data. Browser manggil `index.php?action=edit&id=ID_MAHASISWA_YANG_DIPILIH`.
    * `index.php` bilang ke View, "View, tampilin form edit buat mahasiswa dengan ID sekian dong!" (`$view->tampilForm('edit', $id)`).
    * View nanya ke Presenter, "Presenter, minta data mahasiswa dengan ID ini dong." (`$this->prosesmahasiswa->getDataMahasiswaById($id)`).
    * Presenter ngambil data spesifik itu dari Model.
    * Presenter ngasih data mahasiswa itu ke View.
    * View nyiapin HTML form edit, terus kolom-kolomnya diisi sama data mahasiswa yang mau diedit tadi.
    * Bagian `DATA_FORM_MAHASISWA` di `skin.html` diisi form edit, bagian tabel dikosongin.
    * Form edit dengan data yang udah ada muncul di browser.
2.  **Ngirim Data yang Sudah Diubah:**
    * Kamu ubah data di form, terus klik tombol "Update".
    * Data dikirim ke `index.php?action=submit_edit&id=ID_MAHASISWA_YANG_DIEDIT` pake metode POST.
    * `index.php` nerima data ini, terus ngasih ke Presenter, "Presenter, data mahasiswa ID sekian udah diubah nih, tolong di-update ya!" (`$presenter->ubahMahasiswa($id, $data)`).
    * Presenter ngontak Model, "Model, tolong update data mahasiswa ini di tabel."
    * Model eksekusi query `UPDATE mahasiswa SET ... WHERE id=...`.
    * Kalau berhasil, `index.php` ngarahin kamu balik ke halaman utama (`index.php?status=edit_success`) dengan pesan "Data berhasil diubah!".
    * Kalau gagal, balik lagi ke form edit dengan pesan error.

### 4. Hapus Data Mahasiswa (Delete)

1.  Di tabel, kamu klik tombol "Delete" di salah satu baris.
2.  Muncul kotak konfirmasi JavaScript, "Yakin mau hapus data ini?"
3.  Kalau kamu klik "OK", browser manggil `index.php?action=delete&id=ID_MAHASISWA_YANG_MAU_DIHAPUS`.
4.  `index.php` bilang ke Presenter, "Presenter, tolong hapus mahasiswa dengan ID ini ya." (`$presenter->hapusMahasiswa($id)`).
5.  Presenter ngontak Model, "Model, tolong hapus data mahasiswa ini dari tabel."
6.  Model eksekusi query `DELETE FROM mahasiswa WHERE id=...`.
7.  Kalau berhasil, `index.php` ngarahin kamu balik ke halaman utama (`index.php?status=delete_success`) dengan pesan "Data berhasil dihapus!".
8.  Kalau gagal, ada pesan error (`index.php?status=delete_failed`).

## Dokumentasi Saat Program Dijalankan

https://github.com/user-attachments/assets/e4fd7074-1abe-467a-8dae-89d518945f52


