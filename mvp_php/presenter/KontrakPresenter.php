<?php

// Include model files (pastikan path sesuai dengan struktur folder Anda)
include("model/Template.class.php");
/******************************************
 Asisten Pemrogaman 13 & 14
******************************************/

// Interface atau gambaran dari presenter akan seperti apa
interface KontrakPresenter
{
	public function prosesDataMahasiswa(); // Untuk Read
	public function tambahMahasiswa($data);    // Untuk Create
	public function ubahMahasiswa($id, $data); // Untuk Update
	public function hapusMahasiswa($id);   // Untuk Delete
	public function getDataMahasiswaById($id); // Untuk mendapatkan data spesifik untuk form edit

	// Getter untuk data mahasiswa
	public function getId($i);
	public function getNim($i);
	public function getNama($i);
	public function getTempat($i);
	public function getTl($i);
	public function getGender($i);
	public function getEmail($i); // Tambahan
	public function getTelp($i);  // Tambahan
	public function getSize();
}
?>
