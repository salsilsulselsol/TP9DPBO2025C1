<?php
include_once("model/DB.class.php");


/******************************************
 Asisten Pemrogaman 13 & 14
******************************************/

// Kelas yang berisikan tabel dari mahasiswa
class TabelMahasiswa extends DB
{
	// Mengambil semua data mahasiswa
	function getMahasiswa()
	{
		// Query mysql select data mahasiswa
		$query = "SELECT * FROM mahasiswa";
		// Mengeksekusi query
		return $this->execute($query);
	}

	// Mengambil data mahasiswa berdasarkan ID
	function getMahasiswaById($id)
	{
		// Query mysql select data mahasiswa berdasarkan id
		$query = "SELECT * FROM mahasiswa WHERE id=$id";
		// Mengeksekusi query
		return $this->execute($query);
	}

	// Menambahkan data mahasiswa baru
	function addMahasiswa($data)
	{
		// Escape string untuk keamanan
		$nim = $this->db_link->real_escape_string($data['nim']);
		$nama = $this->db_link->real_escape_string($data['nama']);
		$tempat = $this->db_link->real_escape_string($data['tempat']);
		$tl = $this->db_link->real_escape_string($data['tl']);
		$gender = $this->db_link->real_escape_string($data['gender']);
		$email = $this->db_link->real_escape_string($data['email']);
		$telp = $this->db_link->real_escape_string($data['telp']);

		// Query mysql insert data mahasiswa
		$query = "INSERT INTO mahasiswa (nim, nama, tempat, tl, gender, email, telp) 
                  VALUES ('$nim', '$nama', '$tempat', '$tl', '$gender', '$email', '$telp')";
		
		// Mengeksekusi query
		return $this->execute($query);
	}

	// Mengupdate data mahasiswa berdasarkan ID
	function updateMahasiswa($id, $data)
	{
		// Escape string untuk keamanan
		$nim = $this->db_link->real_escape_string($data['nim']);
		$nama = $this->db_link->real_escape_string($data['nama']);
		$tempat = $this->db_link->real_escape_string($data['tempat']);
		$tl = $this->db_link->real_escape_string($data['tl']);
		$gender = $this->db_link->real_escape_string($data['gender']);
		$email = $this->db_link->real_escape_string($data['email']);
		$telp = $this->db_link->real_escape_string($data['telp']);
        $id_clean = $this->db_link->real_escape_string($id);

		// Query mysql update data mahasiswa
		$query = "UPDATE mahasiswa 
                  SET nim='$nim', nama='$nama', tempat='$tempat', tl='$tl', gender='$gender', email='$email', telp='$telp' 
                  WHERE id='$id_clean'";
		
		// Mengeksekusi query
		return $this->execute($query);
	}

	// Menghapus data mahasiswa berdasarkan ID
	function deleteMahasiswa($id)
	{
        $id_clean = $this->db_link->real_escape_string($id);
		// Query mysql delete data mahasiswa
		$query = "DELETE FROM mahasiswa WHERE id='$id_clean'";
		
		// Mengeksekusi query
		return $this->execute($query);
	}
}
?>