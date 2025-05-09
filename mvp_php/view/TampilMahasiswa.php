<?php

/******************************************
 Asisten Pemrogaman 13 & 14
******************************************/

include_once("KontrakView.php"); // Gunakan include_once
include_once("presenter/ProsesMahasiswa.php"); // Gunakan include_once

class TampilMahasiswa implements KontrakView
{
	private $prosesmahasiswa; // Presenter yang dapat berinteraksi langsung dengan view
	private $tpl;

	function __construct()
	{
		//konstruktor
		$this->prosesmahasiswa = new ProsesMahasiswa();
	}

    // Metode helper untuk menghasilkan HTML tabel secara keseluruhan
	private function _generateTableHtmlContent()
	{
		$this->prosesmahasiswa->prosesDataMahasiswa(); // Pastikan data terbaru dimuat
		$data_rows = null; // Untuk baris-baris data tabel

		// Mengambil semua data mahasiswa untuk ditampilkan di tabel utama
		for ($i = 0; $i < $this->prosesmahasiswa->getSize(); $i++) {
			$no = $i + 1;
            // Menggunakan htmlspecialchars untuk mencegah XSS
			$data_rows .= "<tr>
			<td>" . $no . "</td>
			<td>" . htmlspecialchars($this->prosesmahasiswa->getNim($i)) . "</td>
			<td>" . htmlspecialchars($this->prosesmahasiswa->getNama($i)) . "</td>
			<td>" . htmlspecialchars($this->prosesmahasiswa->getTempat($i)) . "</td>
			<td>" . htmlspecialchars($this->prosesmahasiswa->getTl($i)) . "</td>
			<td>" . htmlspecialchars($this->prosesmahasiswa->getGender($i)) . "</td>
			<td>" . htmlspecialchars($this->prosesmahasiswa->getEmail($i)) . "</td>
			<td>" . htmlspecialchars($this->prosesmahasiswa->getTelp($i)) . "</td>
			<td class='action-buttons'>
				<a href='index.php?action=edit&id=" . $this->prosesmahasiswa->getId($i) . "' class='btn btn-sm btn-edit'>Edit</a>
				<a href='index.php?action=delete&id=" . $this->prosesmahasiswa->getId($i) . "' class='btn btn-sm btn-danger' onclick='return confirmDelete();'>Delete</a>
			</td>
			</tr>";
		}

        if ($this->prosesmahasiswa->getSize() == 0) {
            $data_rows = "<tr><td colspan='9'>Tidak ada data mahasiswa.</td></tr>";
        }

        // Membangun HTML lengkap untuk bagian tabel
        $table_section_html = '
        <div class="row">
          <div class="col-12">
            <h3 class="mb-4 text-center">TABEL MAHASISWA</h3>
            <table class="table table-bordered table-hover" style="text-align: center;">
              <thead class="thead-light">
                <tr>
                  <th scope="col">NO</th>
                  <th scope="col">NIM</th>
                  <th scope="col">NAMA</th>
                  <th scope="col">TEMPAT</th>
                  <th scope="col">TANGGAL LAHIR</th>
                  <th scope="col">GENDER</th>
                  <th scope="col">EMAIL</th>
                  <th scope="col">TELEPON</th>
                  <th scope="col">AKSI</th>
                </tr>
              </thead>
              <tbody>
                ' . $data_rows . '
              </tbody>
            </table>
          </div>
        </div>';
        
        return $table_section_html;
	}

    // Metode helper untuk menghasilkan HTML pesan status
    private function _generateStatusMessageHtml($status_data) {
        if (!$status_data || !isset($status_data['message']) || !isset($status_data['type'])) {
            return "";
        }
        $alert_type = ($status_data['type'] == 'success') ? 'alert-success' : 'alert-danger';
        return "<div class='alert $alert_type alert-dismissible fade show' role='alert'>
                  " . htmlspecialchars($status_data['message']) . "
                  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                  </button>
                </div>";
    }

	function tampil()
	{
        $table_html = $this->_generateTableHtmlContent();

		// Membaca template skin.html
		$this->tpl = new Template("templates/skin.html");

		// Mengganti placeholder dengan data yang sudah diproses
		$this->tpl->replace("DATA_TABLE_SECTION", $table_html);
        $this->tpl->replace("DATA_FORM_MAHASISWA", ""); // Kosongkan form jika hanya menampilkan tabel
        $this->tpl->replace("DATA_STATUS_MESSAGE", ""); // Kosongkan pesan status

		// Menampilkan ke layar
		$this->tpl->write();
	}

    function tampilForm($action, $id = null, $status = null) {
        $form_title = ($action == 'add') ? "Tambah Data Mahasiswa" : "Edit Data Mahasiswa";
        $submit_button_text = ($action == 'add') ? "Tambah" : "Update";
        $form_action_url = ($action == 'add') ? "index.php?action=submit_add" : "index.php?action=submit_edit&id=" . $id;

        $nim_val = '';
        $nama_val = '';
        $tempat_val = '';
        $tl_val = '';
        $gender_val = '';
        $email_val = '';
        $telp_val = '';

        // Jika ada data dari POST (misalnya setelah validasi gagal pada 'add')
        // atau jika $status ada (menandakan ada pesan, mungkin dari validasi gagal)
        // dan methodnya POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nim_val = htmlspecialchars($_POST['nim'] ?? '');
            $nama_val = htmlspecialchars($_POST['nama'] ?? '');
            $tempat_val = htmlspecialchars($_POST['tempat'] ?? '');
            $tl_val = htmlspecialchars($_POST['tl'] ?? '');
            $gender_val = htmlspecialchars($_POST['gender'] ?? '');
            $email_val = htmlspecialchars($_POST['email'] ?? '');
            $telp_val = htmlspecialchars($_POST['telp'] ?? '');
        }


        if ($action == 'edit' && $id !== null) {
            // Jika bukan dari POST request (artinya baru masuk halaman edit)
            // atau jika POST tapi tidak ada data $_POST (seharusnya tidak terjadi jika form disubmit)
            // maka ambil data dari DB.
            // Jika ada data POST, nilai di atas sudah diisi dari POST.
            if ($_SERVER['REQUEST_METHOD'] != 'POST' || empty($_POST)) {
                $mhs = $this->prosesmahasiswa->getDataMahasiswaById($id);
                if ($mhs) {
                    $nim_val = htmlspecialchars($mhs->getNim());
                    $nama_val = htmlspecialchars($mhs->getNama());
                    $tempat_val = htmlspecialchars($mhs->getTempat());
                    $tl_val = htmlspecialchars($mhs->getTl());
                    $gender_val = htmlspecialchars($mhs->getGender());
                    $email_val = htmlspecialchars($mhs->getEmail());
                    $telp_val = htmlspecialchars($mhs->getTelp());
                } else {
                    // Handle jika data tidak ditemukan
                    $this->tampilStatus("Data mahasiswa dengan ID $id tidak ditemukan.", "danger");
                    return; // Hentikan eksekusi jika data tidak ada
                }
            }
        }


        $data_form = "
        <div class='form-container'>
            <h4 class='mb-3'>$form_title</h4>
            <form action='$form_action_url' method='POST'>
                <div class='form-group'>
                    <label for='nim'>NIM</label>
                    <input type='text' class='form-control' id='nim' name='nim' value='$nim_val' required>
                </div>
                <div class='form-group'>
                    <label for='nama'>Nama</label>
                    <input type='text' class='form-control' id='nama' name='nama' value='$nama_val' required>
                </div>
                <div class='form-group'>
                    <label for='tempat'>Tempat Lahir</label>
                    <input type='text' class='form-control' id='tempat' name='tempat' value='$tempat_val' required>
                </div>
                <div class='form-group'>
                    <label for='tl'>Tanggal Lahir</label>
                    <input type='date' class='form-control' id='tl' name='tl' value='$tl_val' required>
                </div>
                <div class='form-group'>
                    <label for='gender'>Gender</label>
                    <select class='form-control' id='gender' name='gender' required>
                        <option value='Laki-laki'" . ($gender_val == 'Laki-laki' ? ' selected' : '') . ">Laki-laki</option>
                        <option value='Perempuan'" . ($gender_val == 'Perempuan' ? ' selected' : '') . ">Perempuan</option>
                    </select>
                </div>
                <div class='form-group'>
                    <label for='email'>Email</label>
                    <input type='email' class='form-control' id='email' name='email' value='$email_val' required>
                </div>
                <div class='form-group'>
                    <label for='telp'>Telepon</label>
                    <input type='text' class='form-control' id='telp' name='telp' value='$telp_val' required>
                </div>
                <button type='submit' class='btn btn-primary'>$submit_button_text</button>
                <a href='index.php' class='btn btn-secondary'>Batal</a>
            </form>
        </div>";

        $status_message_html = $this->_generateStatusMessageHtml($status);

        // Membaca template skin.html
		$this->tpl = new Template("templates/skin.html");
        $this->tpl->replace("DATA_FORM_MAHASISWA", $data_form);
        $this->tpl->replace("DATA_TABLE_SECTION", ""); // Kosongkan tabel jika menampilkan form
        $this->tpl->replace("DATA_STATUS_MESSAGE", $status_message_html);
        $this->tpl->write();
    }

    function tampilStatus($message, $type = "success") {
        $table_html = $this->_generateTableHtmlContent();
        $status_message_html = $this->_generateStatusMessageHtml(['message' => $message, 'type' => $type]);

        $this->tpl = new Template("templates/skin.html");
        $this->tpl->replace("DATA_TABLE_SECTION", $table_html);
        $this->tpl->replace("DATA_FORM_MAHASISWA", ""); // Kosongkan form
        $this->tpl->replace("DATA_STATUS_MESSAGE", $status_message_html);
        $this->tpl->write();
    }
}
?>