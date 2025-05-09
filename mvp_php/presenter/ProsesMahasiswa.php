<?php

include_once("KontrakPresenter.php"); // Gunakan include_once untuk menghindari error redeclare

/******************************************
 Asisten Pemrogaman 13 & 14
******************************************/

class ProsesMahasiswa implements KontrakPresenter
{
	private $tabelmahasiswa;
	private $data = []; // Untuk list mahasiswa
    private $dataMahasiswaSingle = null; // Untuk data mahasiswa tunggal (saat edit)

	function __construct()
	{
		// Konstruktor
		try {
			$db_host = "localhost"; // host 
			$db_user = "root";      // user
			$db_password = "";      // password
			$db_name = "mvp_php";   // nama basis data
			$this->tabelmahasiswa = new TabelMahasiswa($db_host, $db_user, $db_password, $db_name); // instansi TabelMahasiswa
			$this->data = array();      // instansi list untuk data Mahasiswa
		} catch (Exception $e) {
			echo "Error di Konstruktor ProsesMahasiswa: " . $e->getMessage();
		}
	}

	function prosesDataMahasiswa()
	{
		try {
			// mengambil data di tabel Mahasiswa
			$this->tabelmahasiswa->open();
			$this->tabelmahasiswa->getMahasiswa();
            $this->data = []; // Kosongkan array data sebelum diisi ulang
			while ($row = $this->tabelmahasiswa->getResult()) {
				// ambil hasil query
				$mahasiswa = new Mahasiswa(); // instansiasi objek mahasiswa untuk setiap data mahasiswa
				$mahasiswa->setId($row['id']);       // mengisi id
				$mahasiswa->setNim($row['nim']);     // mengisi nim
				$mahasiswa->setNama($row['nama']);   // mengisi nama
				$mahasiswa->setTempat($row['tempat']); // mengisi tempat
				$mahasiswa->setTl($row['tl']);       // mengisi tl
				$mahasiswa->setGender($row['gender']); // mengisi gender
				$mahasiswa->setEmail($row['email']);   // mengisi email
				$mahasiswa->setTelp($row['telp']);     // mengisi telp

				$this->data[] = $mahasiswa; // tambahkan data mahasiswa ke dalam list
			}
			// Tutup koneksi
			$this->tabelmahasiswa->close();
		} catch (Exception $e) {
			// memproses error
			echo "Error di prosesDataMahasiswa: " . $e->getMessage();
		}
	}

    function getDataMahasiswaById($id)
    {
        try {
            $this->tabelmahasiswa->open();
            $this->tabelmahasiswa->getMahasiswaById($id);
            $row = $this->tabelmahasiswa->getResult();
            if ($row) {
                $mahasiswa = new Mahasiswa();
                $mahasiswa->setId($row['id']);
                $mahasiswa->setNim($row['nim']);
                $mahasiswa->setNama($row['nama']);
                $mahasiswa->setTempat($row['tempat']);
                $mahasiswa->setTl($row['tl']);
                $mahasiswa->setGender($row['gender']);
                $mahasiswa->setEmail($row['email']);
                $mahasiswa->setTelp($row['telp']);
                $this->dataMahasiswaSingle = $mahasiswa;
            } else {
                $this->dataMahasiswaSingle = null;
            }
            $this->tabelmahasiswa->close();
        } catch (Exception $e) {
            echo "Error di getDataMahasiswaById: " . $e->getMessage();
            $this->dataMahasiswaSingle = null;
        }
        return $this->dataMahasiswaSingle;
    }


	function tambahMahasiswa($data)
	{
		try {
			$this->tabelmahasiswa->open();
			$this->tabelmahasiswa->addMahasiswa($data);
			$this->tabelmahasiswa->close();
			return true; // Berhasil
		} catch (Exception $e) {
			echo "Error di tambahMahasiswa: " . $e->getMessage();
			return false; // Gagal
		}
	}

	function ubahMahasiswa($id, $data)
	{
		try {
			$this->tabelmahasiswa->open();
			$this->tabelmahasiswa->updateMahasiswa($id, $data);
			$this->tabelmahasiswa->close();
			return true; // Berhasil
		} catch (Exception $e) {
			echo "Error di ubahMahasiswa: " . $e->getMessage();
			return false; // Gagal
		}
	}

	function hapusMahasiswa($id)
	{
		try {
			$this->tabelmahasiswa->open();
			$this->tabelmahasiswa->deleteMahasiswa($id);
			$this->tabelmahasiswa->close();
			return true; // Berhasil
		} catch (Exception $e) {
			echo "Error di hapusMahasiswa: " . $e->getMessage();
			return false; // Gagal
		}
	}

	// Getter methods
	function getId($i)
	{
		return ($i < count($this->data)) ? $this->data[$i]->getId() : null;
	}
	function getNim($i)
	{
		return ($i < count($this->data)) ? $this->data[$i]->getNim() : null;
	}
	function getNama($i)
	{
		return ($i < count($this->data)) ? $this->data[$i]->getNama() : null;
	}
	function getTempat($i)
	{
		return ($i < count($this->data)) ? $this->data[$i]->getTempat() : null;
	}
	function getTl($i)
	{
		return ($i < count($this->data)) ? $this->data[$i]->getTl() : null;
	}
	function getGender($i)
	{
		return ($i < count($this->data)) ? $this->data[$i]->getGender() : null;
	}
	function getEmail($i)
	{
		return ($i < count($this->data)) ? $this->data[$i]->getEmail() : null;
	}
	function getTelp($i)
	{
		return ($i < count($this->data)) ? $this->data[$i]->getTelp() : null;
	}
	function getSize()
	{
		return count($this->data);
	}
}
?>