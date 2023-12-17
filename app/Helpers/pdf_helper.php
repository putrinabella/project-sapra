<?php 
class MYPDF extends TCPDF {

    public function Header() {
        $image_path = FCPATH . '/assets/images/header-letter.jpg'; 
    
        $a4_width = 210;
        $image_width = $a4_width;
    
        list($original_width, $original_height) = getimagesize($image_path);
        $image_height = ($image_width / $original_width) * $original_height;
    
        $this->Image($image_path, 0, 0, $image_width, $image_height);
    }
}

if (!function_exists('generateFileId')) {
    function generateFileId($url) {
        preg_match('/\/file\/d\/(.*?)\//', $url, $matches);
        
        if (isset($matches[1])) {
            $fileId = $matches[1];
            return "https://drive.google.com/uc?export=view&id=" . $fileId;
        } else {
            return "Invalid Google Drive URL";
        }
    }
}

if (!function_exists('pdfSuratPeminjaman')) {
    function pdfSuratPeminjaman($dataDataPeminjaman, $dataRincianLabAset) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Histori Peminjaman');
        $pdf->SetSubject('Histori Peminjaman');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $dayNamesIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $monthNamesIndonesian = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $tanggalFormatted = $dayNamesIndonesian[date('w', strtotime($dataDataPeminjaman->tanggal))];
        $tanggalFormatted .= ', ' . date('j', strtotime($dataDataPeminjaman->tanggal));
        $tanggalFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($dataDataPeminjaman->tanggal))];
        $tanggalFormatted .= ' ' . date('Y', strtotime($dataDataPeminjaman->tanggal));
        $letterDate = date('j', strtotime($dataDataPeminjaman->tanggal)) . ' ' . $monthNamesIndonesian[date('n', strtotime($dataDataPeminjaman->tanggal))] . ' ' . date('Y', strtotime($dataDataPeminjaman->tanggal));

        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));
        $html = <<<EOD
        <p style="text-align: right;">No Peminjaman: $dataDataPeminjaman->idManajemenPeminjaman </p>
        <h3 style="text-align: center;">SURAT PERMOHONAN PEMINJAMAN ALAT LAB $yearNow/$yearNext</h3>
        <p style="padding-top: 10px;">Saya yang bertanda tangan di bawah ini: </p>
        <table style="padding-top: 10px;  width: 100%;">
            <tr>
                <th style="width: 30%;">Nama</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataPeminjaman->namaSiswa</th>
            </tr>
            <tr>
                <th style="width: 30%;">NIS/NIK</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataPeminjaman->nis</th>
            </tr>
            <tr>
                <th style="width: 30%;">Kelas/Karyawan</th>
                <th style="width: 5%;">:</th>
                <th>$dataDataPeminjaman->namaKelas</th>
            </tr>
            <tr>
                <th style="width: 30%;">Keperluan Alat</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataPeminjaman->keperluanAlat</th>
            </tr>
            <tr>
                <th style="width: 30%;">Hari, Tanggal Pinjam</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$tanggalFormatted</th>
            </tr>
            <tr>
                <th style="width: 30%;">Lama Pinjam</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataPeminjaman->lamaPinjam Hari</th>
            </tr>
        </table>

        <p style="padding-top: 10px;">Dengan memohon untuk dipinjamkan alat sebagai berikut:</p>

        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No.</b></th>
                    <th style="width: 45%;"><b>Nama Alat</b></th>
                    <th style="width: 10%;"><b>Jumlah</b></th>
                    <th style="width: 35%;"><b>Keadaan Alat Saat Dipinjam</b></th>
                </tr>
            </thead>
        <tbody>
    EOD;
    
    foreach ($dataRincianLabAset as $key => $value) {

        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 45%; text-align: left;">' . $value->namaSarana . '</td>';
        $html .= '<td style="width: 10%;">' . $value->totalAset . '</td>';
        $html .= '<td style="width: 35%;">Baik</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    <p style="padding-top: 10px; text-align: justify;">Dan bertanggungjawab atas alat tersebut di atas, bila terjadi sesuatu yang menyebabkan alat tersebut dikembalikan dalam keadaan tidak seperti sebelumnya, dan bersedia menggantinya.</p>

    <table style="padding-top: 10px;">
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;">Banjarbaru, $letterDate</th>
        </tr>
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;">Peminjam</th>
        </tr>
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;"></th>
        </tr>
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;"></th>
        </tr>
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;"> ($dataDataPeminjaman->namaSiswa)</th>
        </tr>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfSuratAsetPeminjaman')) {
    function pdfSuratAsetPeminjaman($dataDataAsetPeminjaman, $dataRincianAset) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Histori Peminjaman');
        $pdf->SetSubject('Histori Peminjaman');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $dayNamesIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $monthNamesIndonesian = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $tanggalFormatted = $dayNamesIndonesian[date('w', strtotime($dataDataAsetPeminjaman->tanggal))];
        $tanggalFormatted .= ', ' . date('j', strtotime($dataDataAsetPeminjaman->tanggal));
        $tanggalFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($dataDataAsetPeminjaman->tanggal))];
        $tanggalFormatted .= ' ' . date('Y', strtotime($dataDataAsetPeminjaman->tanggal));
        $letterDate = date('j', strtotime($dataDataAsetPeminjaman->tanggal)) . ' ' . $monthNamesIndonesian[date('n', strtotime($dataDataAsetPeminjaman->tanggal))] . ' ' . date('Y', strtotime($dataDataAsetPeminjaman->tanggal));


        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));
        $html = <<<EOD
        <p style="text-align: right;">No Peminjaman: $dataDataAsetPeminjaman->idManajemenAsetPeminjaman </p>
        <h3 style="text-align: center;">SURAT PERMOHONAN PEMINJAMAN ALAT $yearNow/$yearNext</h3>
        <p style="padding-top: 10px;">Saya yang bertanda tangan di bawah ini: </p>
        <table style="padding-top: 10px;  width: 100%;" >
            <tr>
                <th style="width: 30%;">Nama</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataAsetPeminjaman->namaSiswa</th>
            </tr>
            <tr>
                <th style="width: 30%;">NIS/NIK</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataAsetPeminjaman->nis</th>
            </tr>
            <tr>
                <th style="width: 30%;">Kelas/Karyawan</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataAsetPeminjaman->namaKelas</th>
            </tr>
            <tr>
                <th style="width: 30%;">Keperluan Alat</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataAsetPeminjaman->keperluanAlat</th>
            </tr>
            <tr>
                <th style="width: 30%;">Hari, Tanggal Pinjam</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$tanggalFormatted</th>
            </tr>
            <tr>
                <th style="width: 30%;">Lama Pinjam</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$dataDataAsetPeminjaman->lamaPinjam Hari</th>
            </tr>
        </table>

        <p style="padding-top: 10px;">Dengan memohon untuk dipinjamkan alat sebagai berikut:</p>

        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No.</b></th>
                    <th style="width: 45%;"><b>Nama Alat</b></th>
                    <th style="width: 10%;"><b>Jumlah</b></th>
                    <th style="width: 35%;"><b>Keadaan Alat Saat Dipinjam</b></th>
                </tr>
            </thead>
        <tbody>
    EOD;
    
    foreach ($dataRincianAset as $key => $value) {
        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 45%; text-align: left;">' . $value->namaSarana . '</td>';
        $html .= '<td style="width: 10%;">' . $value->totalAset . '</td>';
        $html .= '<td style="width: 35%;">Baik</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    <p style="padding-top: 10px; text-align: justify;">Dan bertanggungjawab atas alat tersebut di atas, bila terjadi sesuatu yang menyebabkan alat tersebut dikembalikan dalam keadaan tidak seperti sebelumnya, dan bersedia menggantinya.</p>

    <table style="padding-top: 10px;">
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;">Banjarbaru, $letterDate</th>
        </tr>
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;">Peminjam</th>
        </tr>
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;"></th>
        </tr>
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;"></th>
        </tr>
        <tr>
            <th style="width: 60%;"></th>
            <th style="width: 40%;"> ($dataDataAsetPeminjaman->namaSiswa)</th>
        </tr>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfProfilSekolah')) {
    function pdfProfilSekolah($data, $dataDokumenSekolah, $title) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Profil Sekolah');
        $pdf->SetSubject('Profil Sekolah');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $dayNamesIndonesian = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $monthNamesIndonesian = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $tanggalPendirianFormatted = $dayNamesIndonesian[date('w', strtotime($data->tanggalSkPendirian))];
        $tanggalPendirianFormatted .= ', ' . date('j', strtotime($data->tanggalSkPendirian));
        $tanggalPendirianFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($data->tanggalSkPendirian))];
        $tanggalPendirianFormatted .= ' ' . date('Y', strtotime($data->tanggalSkPendirian));

        $tanggalOperasionalFormatted = $dayNamesIndonesian[date('w', strtotime($data->tanggalSkIzinOperasional))];
        $tanggalOperasionalFormatted .= ', ' . date('j', strtotime($data->tanggalSkIzinOperasional));
        $tanggalOperasionalFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($data->tanggalSkIzinOperasional))];
        $tanggalOperasionalFormatted .= ' ' . date('Y', strtotime($data->tanggalSkIzinOperasional));


        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));
        $html = <<<EOD
        <h3 style="text-align: center;"> $title</h3>

        <h4>Identitas Sekolah</h4>
        <table style="padding-top: 10px; width: 100%">
            <tr>
                <th style="width: 30%;">NPSN</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%">$data->npsn</th>
            </tr>
            <tr>
                <th>Status</th>
                <th>:</th>
                <th>$data->status</th>
            </tr>
            <tr>
                <th>Bentuk Pendidikan</th>
                <th>:</th>
                <th>$data->bentukPendidikan</th>
            </tr>
            <tr>
                <th>Status Kepemilikan</th>
                <th>:</th>
                <th>$data->statusKepemilikan</th>
            </tr>
            <tr>
                <th>SK Pendirian</th>
                <th>:</th>
                <th>$data->skPendirian</th>
            </tr>
            <tr>
                <th>Tanggal SK Pendirian</th>
                <th>:</th>
                <th>$tanggalPendirianFormatted</th>
            </tr>
            <tr>
                <th>SK Izin Operasional</th>
                <th>:</th>
                <th>$data->skIzinOperasional</th>
            </tr>
            <tr>
                <th>Tanggal SK Izin Operasional</th>
                <th>:</th>
                <th>$tanggalOperasionalFormatted</th>
            </tr>
        </table>

        <br>

        <h4>Data Rinci</h4>
        <table style="padding-top: 10px; width: 100%">
            <tr>
                <th style="width: 30%;">Status BOS</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%">$data->statusBos</th>
            </tr>
            <tr>
                <th>Waktu Penyelenggaraan</th>
                <th>:</th>
                <th>$data->waktuPenyelenggaraan</th>
            </tr>
            <tr>
                <th>Sertifikasi ISO</th>
                <th>:</th>
                <th>$data->sertifikasiIso</th>
            </tr>
            <tr>
                <th>Sumber Listrik</th>
                <th>:</th>
                <th>$data->sumberListrik</th>
            </tr>
            <tr>
                <th>Kecepatan Internet</th>
                <th>:</th>
                <th>$data->kecepatanInternet</th>
            </tr>
        </table>

        <br>

        <h4>Data Pelengkap</h4>
        <table style="padding-top: 10px; width: 100%">
            <tr>
                <th style="width: 30%;">Siswa Berkebutuhan Khusus</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%">$data->siswaKebutuhanKhusus</th>
            </tr>
            <tr>
                <th>Nama Bank</th>
                <th>:</th>
                <th>$data->namaBank</th>
            </tr>
            <tr>
                <th>Cabang KCP</th>
                <th>:</th>
                <th>$data->cabangKcp</th>
            </tr>
            <tr>
                <th>Atas Nama Rekening</th>
                <th>:</th>
                <th>$data->atasNamaRekening</th>
            </tr>
        </table>

        <br>
        <br>
        <br>
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
        <thead>
            <tr>
                <th style="width: 10%;"><b>No.</b></th>
                <th style="width: 35%;"><b>Nama Dokumen</b></th>
                <th style="width: 55%;"><b>Link</b></th>
            </tr>
        </thead>
    <tbody>
    EOD;

    foreach ($dataDokumenSekolah as $key => $value) {

        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 35%; text-align: left;">' . $value->namaDokumenSekolah . '</td>';
        $html .= '<td style="width: 55%; text-align: left;">' . $value->linkDokumenSekolah . '</td>';
        $html .= '</tr>';
    }

    $html .= <<<EOD
        </tbody>
    </table>
    EOD;

    
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfLayananAset')) {
    function pdfLayananAset($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Sarana - Layanan Aset');
        $pdf->SetSubject('Sarana - Layanan Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 60%; text-align: left;">' .
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 30%;">Lokasi</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 65%;">' . $value->namaPrasarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kategori</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaKategoriManajemen . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Aset</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Layanan</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaStatusLayanan . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Sumber Dana</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSumberDana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Biaya</td>' .
                            '<td>:</td>' .
                            '<td>Rp' . number_format($value->biaya, 0, ',', '.') . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Keterangan</td>' .
                            '<td>:</td>' .
                            '<td style="text-align: justify;">' . $value->keterangan . '</td>' .
                        '</tr>' .
                    '</table>' .
                '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfLayananNonAset')) {
    function pdfLayananNonAset($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Sarana - Layanan Non Aset');
        $pdf->SetSubject('Sarana - Layanan Non Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 60%; text-align: left;">' .
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 30%;">Lokasi</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 65%;">' . $value->namaPrasarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Layanan</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaStatusLayanan . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kategori MEP</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaKategoriMep . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Sumber Dana</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSumberDana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Biaya</td>' .
                            '<td>:</td>' .
                            '<td>Rp' . number_format($value->biaya, 0, ',', '.') . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Keterangan</td>' .
                            '<td>:</td>' .
                            '<td style="text-align: justify;">' . $value->spesifikasi . '</td>' .
                        '</tr>' .
                    '</table>' .
                '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfLayananAsetIt')) {
    function pdfLayananAsetIt($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('IT - Layanan Aset');
        $pdf->SetSubject('IT - Layanan Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 60%; text-align: left;">' .
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 30%;">Lokasi</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 65%;">' . $value->namaPrasarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kategori</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaKategoriManajemen . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Aset</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Layanan</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaStatusLayanan . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Sumber Dana</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSumberDana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Biaya</td>' .
                            '<td>:</td>' .
                            '<td>Rp' . number_format($value->biaya, 0, ',', '.') . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Keterangan</td>' .
                            '<td>:</td>' .
                            '<td style="text-align: justify;">' . $value->keterangan . '</td>' .
                        '</tr>' .
                    '</table>' .
                '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfLayananAsetLab')) {
    function pdfLayananAsetLab($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Laboratorium - Layanan Aset');
        $pdf->SetSubject('Laboratorium - Layanan Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 60%; text-align: left;">' .
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 30%;">Lokasi</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 65%;">' . $value->namaLab . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kategori</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaKategoriManajemen . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Aset</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Layanan</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaStatusLayanan . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Sumber Dana</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSumberDana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Biaya</td>' .
                            '<td>:</td>' .
                            '<td>Rp' . number_format($value->biaya, 0, ',', '.') . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Keterangan</td>' .
                            '<td>:</td>' .
                            '<td style="text-align: justify;">' . $value->keterangan . '</td>' .
                        '</tr>' .
                    '</table>' .
                '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfLayananNonAsetLab')) {
    function pdfLayananNonAsetLab($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Laboratorium - Layanan Non Aset');
        $pdf->SetSubject('Laboratorium - Layanan Non Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 60%; text-align: left;">' .
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 30%;">Lokasi</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 65%;">' . $value->namaLab . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Layanan</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaStatusLayanan . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kategori MEP</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaKategoriMep . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Sumber Dana</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSumberDana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Biaya</td>' .
                            '<td>:</td>' .
                            '<td>Rp' . number_format($value->biaya, 0, ',', '.') . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Keterangan</td>' .
                            '<td>:</td>' .
                            '<td style="text-align: justify;">' . $value->spesifikasi . '</td>' .
                        '</tr>' .
                    '</table>' .
                '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfPemusnahanAset')) {
    function pdfPemusnahanAset($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->tanggalPemusnahan) - strtotime($b->tanggalPemusnahan);
        });

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Sarana - Pemusnahan Aset');
        $pdf->SetSubject('Sarana - Pemusnahan Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD
        <style>
        img {
            width: 380px;
            max-height: 500px;
        }
        </style>
        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $imageUrl = generateFileId($value->bukti);
        $tanggalTimestamp = strtotime($value->tanggalPemusnahan);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 60%; text-align: left;">' .
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 40%;">Nama Akun</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 55%;">' . $value->namaAkun . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kode Akun</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->kodeAkun . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kode</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->kodeRincianAset . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Lokasi</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaPrasarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kategori</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaKategoriManajemen . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Aset</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                        '<td>Merek</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->merk . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Status</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->status . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Sumber Dana</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSumberDana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Tahun Pengadaan</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->tahunPengadaan . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Harga Beli</td>' .
                            '<td>:</td>' .
                            '<td>Rp' . number_format($value->hargaBeli, 0, ',', '.') . '</td>' .
                        '</tr>' ;     
                        $html .= '</table>' .
                '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfPemusnahanItAset')) {
    function pdfPemusnahanItAset($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->tanggalPemusnahan) - strtotime($b->tanggalPemusnahan);
        });

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('IT - Pemusnahan Aset');
        $pdf->SetSubject('IT - Pemusnahan Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD
        <style>
        img {
            width: 380px;
            max-height: 500px;
        }
        </style>
        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $imageUrl = generateFileId($value->bukti);
        $tanggalTimestamp = strtotime($value->tanggalPemusnahan);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 60%; text-align: left;">' .
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 40%;">Nama Akun</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 55%;">' . $value->namaAkun . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kode Akun</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->kodeAkun . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kode</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->kodeRincianAset . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Lokasi</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaPrasarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kategori</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaKategoriManajemen . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Aset</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                        '<td>Merek</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->merk . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Status</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->status . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Sumber Dana</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSumberDana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Tahun Pengadaan</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->tahunPengadaan . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Harga Beli</td>' .
                            '<td>:</td>' .
                            '<td>Rp' . number_format($value->hargaBeli, 0, ',', '.') . '</td>' .
                        '</tr>' ;     
                        $html .= '</table>' .
                '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfPemusnahanLabAset')) {
    function pdfPemusnahanLabAset($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->tanggalPemusnahan) - strtotime($b->tanggalPemusnahan);
        });

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Laboratorium - Pemusnahan Aset');
        $pdf->SetSubject('Laboratorium - Pemusnahan Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD
        <style>
        img {
            width: 380px;
            max-height: 500px;
        }
        </style>
        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $imageUrl = generateFileId($value->bukti);
        $tanggalTimestamp = strtotime($value->tanggalPemusnahan);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 60%; text-align: left;">' .
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 40%;">Nama Akun</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 55%;">' . $value->namaAkun . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kode Akun</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->kodeAkun . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kode</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->kodeRincianLabAset . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Lokasi</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaLab . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Kategori</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaKategoriManajemen . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Aset</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSarana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                        '<td>Merek</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->merk . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Status</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->status . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Sumber Dana</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->namaSumberDana . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Tahun Pengadaan</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->tahunPengadaan . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Harga Beli</td>' .
                            '<td>:</td>' .
                            '<td>Rp' . number_format($value->hargaBeli, 0, ',', '.') . '</td>' .
                        '</tr>' ;     
                        $html .= '</table>' .
                '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfNonInventaris')) {
    function pdfNonInventaris($dataPemasukan, $dataPengeluaran, $title,  $startDate = null, $endDate = null) {
        usort($dataPemasukan, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });
        usort($dataPengeluaran, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Sarana - Non Inventaris');
        $pdf->SetSubject('Sarana - Non Inventaris');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD
        <style>
        img {
            width: 380px;
            max-height: 500px;
        }
        </style>
        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <h4>Data Pemasukan</h4>
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 20%;"><b>Nama</b></th>
                    <th style="width: 20%;"><b>Satuan</b></th>
                    <th style="width: 20%;"><b>Jumlah</b></th>

                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($dataPemasukan as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->nama. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->satuan. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->jumlah. '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;

    $html .= <<<EOD
        <br>    
        <h4>Data Pengeluaran</h4>
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 20%;"><b>Nama</b></th>
                    <th style="width: 20%;"><b>Satuan</b></th>
                    <th style="width: 20%;"><b>Jumlah</b></th>

                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($dataPengeluaran as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->nama. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->satuan. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->jumlah. '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfAsetGeneral')) {
    function pdfAsetGeneral($data, $title) {

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Sarana - Data General');
        $pdf->SetSubject('Sarana - Data General');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();
    

        $totalAset = 0;
        $asetBagus = 0;
        $asetRusak = 0;
        $asetHilang = 0;
        foreach ($data as $value) {
            $totalAset += $value->jumlahAset;
            $asetBagus += $value->jumlahBagus;
            $asetRusak += $value->jumlahRusak;
            $asetHilang += $value->jumlahHilang;
        }

        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        $html .= <<<EOD
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Nama</b></th>
                    <th style="width: 15%;"><b>Aset Bagus</b></th>
                    <th style="width: 15%;"><b>Aset Rusak</b></th>
                    <th style="width: 15%;"><b>Aset Hilang</b></th>
                    <th style="width: 15%;"><b>Total Aset</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $value->namaSarana. '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahBagus != 0 ? $value->jumlahBagus : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahRusak != 0 ? $value->jumlahRusak : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahHilang != 0 ? $value->jumlahHilang : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahAset != 0 ? $value->jumlahAset : '-') . '</td>';
        
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        <tr>
            <td colspan=2; style="width: 40%;"><b>Total</b></td>
            <td style="width: 15%;"><b>$asetBagus buah</b></td>
            <td style="width: 15%;"><b>$asetRusak buah</b></td>
            <td style="width: 15%;"><b>$asetHilang buah</b></td>
            <td style="width: 15%;"><b>$totalAset buah</b></td>
        </tr>
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfAsetItGeneral')) {
    function pdfAsetItGeneral($data, $title) {

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('IT - Data General');
        $pdf->SetSubject('IT - Data General');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();
    

        $totalAset = 0;
        $asetBagus = 0;
        $asetRusak = 0;
        $asetHilang = 0;
        foreach ($data as $value) {
            $totalAset += $value->jumlahAset;
            $asetBagus += $value->jumlahBagus;
            $asetRusak += $value->jumlahRusak;
            $asetHilang += $value->jumlahHilang;
        }

        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        $html .= <<<EOD
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Nama</b></th>
                    <th style="width: 15%;"><b>Aset Bagus</b></th>
                    <th style="width: 15%;"><b>Aset Rusak</b></th>
                    <th style="width: 15%;"><b>Aset Hilang</b></th>
                    <th style="width: 15%;"><b>Total Aset</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $value->namaSarana. '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahBagus != 0 ? $value->jumlahBagus : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahRusak != 0 ? $value->jumlahRusak : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahHilang != 0 ? $value->jumlahHilang : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahAset != 0 ? $value->jumlahAset : '-') . '</td>';
        
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        <tr>
            <td colspan=2; style="width: 40%;"><b>Total</b></td>
            <td style="width: 15%;"><b>$asetBagus buah</b></td>
            <td style="width: 15%;"><b>$asetRusak buah</b></td>
            <td style="width: 15%;"><b>$asetHilang buah</b></td>
            <td style="width: 15%;"><b>$totalAset buah</b></td>
        </tr>
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfAsetLabGeneral')) {
    function pdfAsetLabGeneral($data, $title) {

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Lab - Data General');
        $pdf->SetSubject('Lab - Data General');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();
    

        $totalAset = 0;
        $asetBagus = 0;
        $asetRusak = 0;
        $asetHilang = 0;
        foreach ($data as $value) {
            $totalAset += $value->jumlahAset;
            $asetBagus += $value->jumlahBagus;
            $asetRusak += $value->jumlahRusak;
            $asetHilang += $value->jumlahHilang;
        }

        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        $html .= <<<EOD
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Nama</b></th>
                    <th style="width: 15%;"><b>Aset Bagus</b></th>
                    <th style="width: 15%;"><b>Aset Rusak</b></th>
                    <th style="width: 15%;"><b>Aset Hilang</b></th>
                    <th style="width: 15%;"><b>Total Aset</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $value->namaSarana. '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahBagus != 0 ? $value->jumlahBagus : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahRusak != 0 ? $value->jumlahRusak : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahHilang != 0 ? $value->jumlahHilang : '-') . '</td>';
        $html .= '<td style="width: 15%; text-align: center;">' . ($value->jumlahAset != 0 ? $value->jumlahAset : '-') . '</td>';
        
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        <tr>
            <td colspan=2; style="width: 40%;"><b>Total</b></td>
            <td style="width: 15%;"><b>$asetBagus buah</b></td>
            <td style="width: 15%;"><b>$asetRusak buah</b></td>
            <td style="width: 15%;"><b>$asetHilang buah</b></td>
            <td style="width: 15%;"><b>$totalAset buah</b></td>
        </tr>
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfRincianAset')) {
    function pdfRincianAset($dataAsetBagus, $dataAsetRusak, $dataAsetHilang, $title) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Sarana - Rincian Aset');
        $pdf->SetSubject('Sarana - Rincian Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));

        $html = <<<EOD
        <h3 style="text-align: center;"> $title $yearNow/$yearNext</h3>
        EOD;

        // Include table for Status Aset: Bagus only if $dataAsetBagus is not empty
        if (!empty($dataAsetBagus)) {
            $html .= generateDataAset($dataAsetBagus, "Status Aset: Bagus");
        }

        // Include table for Status Aset: Rusak only if $dataAsetRusak is not empty
        if (!empty($dataAsetRusak)) {
            $html .= generateDataAset($dataAsetRusak, "Status Aset: Rusak");
        }

        // Include table for Status Aset: Hilang only if $dataAsetHilang is not empty
        if (!empty($dataAsetHilang)) {
            $html .= generateDataAset($dataAsetHilang, "Status Aset: Hilang");
        }

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

        return $pdfData;
    }

    // Function to generate a table based on the given data array and title
    function generateDataAset($data, $status) {
        $table = <<<EOD
        <br>
        <h4>$status</h4>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Kode</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
            <tbody>
        EOD;

        foreach ($data as $key => $value) {
            $table .= '<tr>';
            $table .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
            $table .= '<td style="width: 30%; text-align: left;">' . $value->kodeRincianAset . '</td>';
            $table .= '<td style="width: 60%; text-align: left;">' .
                            '<table style="width: 100%; padding:5px;">' .
                                '<tr>' .
                                    '<td style="width: 40%;">Lokasi</td>' .
                                    '<td style="width: 5%;">:</td>' .
                                    '<td style="width: 55%;">' . $value->namaPrasarana . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Kategori</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaKategoriManajemen . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Aset</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaSarana . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Sumber Dana</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaSumberDana . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Harga Beli</td>' .
                                    '<td>:</td>' .
                                    '<td>Rp' . number_format($value->hargaBeli, 0, ',', '.') . '</td>' .
                                '</tr>' .  
                                '<tr>' .
                                    '<td>Tahun Pengadaan</td>' .
                                    '<td>:</td>' .
                                    '<td>' . ($value->tahunPengadaan != 0 || 0000 ? $value->tahunPengadaan : 'Tidak diketahui') . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Merek</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->merk . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Warna</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->warna . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Keterangan</td>' .
                                    '<td>:</td>' .
                                    '<td style="text-align: justify;">' . $value->spesifikasi . '</td>' .
                                '</tr>' .
                            '</table>' .
                        '</td>' .
                    '</tr>';
        }

        $table .= <<<EOD
        </tbody>
    </table>
    EOD;

        return $table;
    }
}

if (!function_exists('pdfRincianItAset')) {
    function pdfRincianItAset($dataAsetItBagus, $dataAsetItRusak, $dataAsetItHilang, $title) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('IT - Rincian Aset');
        $pdf->SetSubject('IT - Rincian Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));

        $html = <<<EOD
        <h3 style="text-align: center;"> $title $yearNow/$yearNext</h3>
        EOD;

        // Include table for Status Aset: Bagus only if $dataAsetBagus is not empty
        if (!empty($dataAsetItBagus)) {
            $html .= generateDataAsetIt($dataAsetItBagus, "Status Aset: Bagus");
        }

        // Include table for Status Aset: Rusak only if $dataAsetRusak is not empty
        if (!empty($dataAsetItRusak)) {
            $html .= generateDataAsetIt($dataAsetItRusak, "Status Aset: Rusak");
        }

        // Include table for Status Aset: Hilang only if $dataAsetHilang is not empty
        if (!empty($dataAsetItHilang)) {
            $html .= generateDataAsetIt($dataAsetItHilang, "Status Aset: Hilang");
        }

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

        return $pdfData;
    }

    // Function to generate a table based on the given data array and title
    function generateDataAsetIt($data, $status) {
        $table = <<<EOD
        <br>
        <h4>$status</h4>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Kode</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
            <tbody>
        EOD;

        foreach ($data as $key => $value) {
            $table .= '<tr>';
            $table .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
            $table .= '<td style="width: 30%; text-align: left;">' . $value->kodeRincianAset . '</td>';
            $table .= '<td style="width: 60%; text-align: left;">' .
                            '<table style="width: 100%; padding:5px;">' .
                                '<tr>' .
                                    '<td style="width: 40%;">Lokasi</td>' .
                                    '<td style="width: 5%;">:</td>' .
                                    '<td style="width: 55%;">' . $value->namaPrasarana . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Kategori</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaKategoriManajemen . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Aset</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaSarana . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Sumber Dana</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaSumberDana . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Harga Beli</td>' .
                                    '<td>:</td>' .
                                    '<td>Rp' . number_format($value->hargaBeli, 0, ',', '.') . '</td>' .
                                '</tr>' .  
                                '<tr>' .
                                    '<td>Tahun Pengadaan</td>' .
                                    '<td>:</td>' .
                                    '<td>' . ($value->tahunPengadaan != 0 || 0000 ? $value->tahunPengadaan : 'Tidak diketahui') . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Merek</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->merk . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Warna</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->warna . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Keterangan</td>' .
                                    '<td>:</td>' .
                                    '<td style="text-align: justify;">' . $value->spesifikasi . '</td>' .
                                '</tr>' .
                            '</table>' .
                        '</td>' .
                    '</tr>';
        }

        $table .= <<<EOD
        </tbody>
    </table>
    EOD;

        return $table;
    }
}

if (!function_exists('pdfRincianLabAset')) {
    function pdfRincianLabAset($dataAsetBagus, $dataAsetRusak, $dataAsetHilang, $title) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Laboratorium - Rincian Aset');
        $pdf->SetSubject('Laboratorium - Rincian Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));

        $html = <<<EOD
        <h3 style="text-align: center;"> $title $yearNow/$yearNext</h3>
        EOD;

        // Include table for Status Aset: Bagus only if $dataAsetBagus is not empty
        if (!empty($dataAsetBagus)) {
            $html .= generateDataLabAset($dataAsetBagus, "Status Aset: Bagus");
        }

        // Include table for Status Aset: Rusak only if $dataAsetRusak is not empty
        if (!empty($dataAsetRusak)) {
            $html .= generateDataLabAset($dataAsetRusak, "Status Aset: Rusak");
        }

        // Include table for Status Aset: Hilang only if $dataAsetHilang is not empty
        if (!empty($dataAsetHilang)) {
            $html .= generateDataLabAset($dataAsetHilang, "Status Aset: Hilang");
        }

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

        return $pdfData;
    }

    // Function to generate a table based on the given data array and title
    function generateDataLabAset($data, $status) {
        $table = <<<EOD
        <br>
        <h4>$status</h4>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Kode</b></th>
                    <th style="width: 60%;"><b>Penjelasan</b></th>
                </tr>
            </thead>
            <tbody>
        EOD;

        foreach ($data as $key => $value) {
            $table .= '<tr>';
            $table .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
            $table .= '<td style="width: 30%; text-align: left;">' . $value->kodeRincianLabAset . '</td>';
            $table .= '<td style="width: 60%; text-align: left;">' .
                            '<table style="width: 100%; padding:5px;">' .
                                '<tr>' .
                                    '<td style="width: 40%;">Lokasi</td>' .
                                    '<td style="width: 5%;">:</td>' .
                                    '<td style="width: 55%;">' . $value->namaLab . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Kategori</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaKategoriManajemen . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Aset</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaSarana . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Sumber Dana</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->namaSumberDana . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Harga Beli</td>' .
                                    '<td>:</td>' .
                                    '<td>Rp' . number_format($value->hargaBeli, 0, ',', '.') . '</td>' .
                                '</tr>' .  
                                '<tr>' .
                                    '<td>Tahun Pengadaan</td>' .
                                    '<td>:</td>' .
                                    '<td>' . ($value->tahunPengadaan != 0 || 0000 ? $value->tahunPengadaan : 'Tidak diketahui') . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Merek</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->merk . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Warna</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->warna . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Keterangan</td>' .
                                    '<td>:</td>' .
                                    '<td style="text-align: justify;">' . $value->spesifikasi . '</td>' .
                                '</tr>' .
                            '</table>' .
                        '</td>' .
                    '</tr>';
        }

        $table .= <<<EOD
        </tbody>
    </table>
    EOD;

        return $table;
    }
}

if (!function_exists('pdfTagihanAir')) {
    function pdfTagihanAir($data, $title, $startYear, $endYear) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Tagihan Air');
        $pdf->SetSubject('Tagihan Air');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $dateRange = "";
        if ($startYear !== null && $endYear !== null) {
            $startYear = $startYear;
            $endYear = $endYear;
            $dateRange = ' - ';
        }
    
        $html = <<<EOD
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        if ($startYear !== '' && $endYear !== '') {
            $html .= '<h4 style="text-align: center;">' . $startYear . $dateRange . $endYear . '</h4>';
        }
        
        $html .= <<<EOD
            <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No.</b></th>
                    <th style="width: 30%;"><b>Bulan, Tahun</b></th>
                    <th style="width: 30%;"><b>Pemakaian</b></th>
                    <th style="width: 30%;"><b>Biaya</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;

        foreach ($data as $key => $value) {
            $numericMonth = (int)$value->bulanPemakaianAir;
            $monthName = $monthNamesIndonesian[$numericMonth];
        
            // Format the biaya with Rupiah
            $formattedBiaya = 'Rp ' . number_format($value->biaya, 0, ',', '.');
        
            $html .= '<tr>';
            $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $monthName . ', ' . $value->tahunPemakaianAir . '</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $value->pemakaianAir . ' kubik</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $formattedBiaya . '</td>';
            $html .= '</tr>';
        }

        $html .= <<<EOD
            </tbody>
        </table>
        EOD;

        
            
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

        return $pdfData;
    }
}

if (!function_exists('pdfTagihanInternet')) {
    function pdfTagihanInternet($data, $title, $startYear, $endYear) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Tagihan Air');
        $pdf->SetSubject('Tagihan Air');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $dateRange = "";
        if ($startYear !== null && $endYear !== null) {
            $startYear = $startYear;
            $endYear = $endYear;
            $dateRange = ' - ';
        }
    
        $html = <<<EOD
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        if ($startYear !== '' && $endYear !== '') {
            $html .= '<h4 style="text-align: center;">' . $startYear . $dateRange . $endYear . '</h4>';
        }
        
        $html .= <<<EOD
            <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No.</b></th>
                    <th style="width: 30%;"><b>Bulan, Tahun</b></th>
                    <th style="width: 30%;"><b>Pemakaian</b></th>
                    <th style="width: 30%;"><b>Biaya</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;

        foreach ($data as $key => $value) {
            $numericMonth = (int)$value->bulanPemakaianInternet;
            $monthName = $monthNamesIndonesian[$numericMonth];
        
            // Format the biaya with Rupiah
            $formattedBiaya = 'Rp ' . number_format($value->biaya, 0, ',', '.');
        
            $html .= '<tr>';
            $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $monthName . ', ' . $value->tahunPemakaianInternet . '</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $value->pemakaianInternet . ' GB</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $formattedBiaya . '</td>';
            $html .= '</tr>';
        }

        $html .= <<<EOD
            </tbody>
        </table>
        EOD;

        
            
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

        return $pdfData;
    }
}

if (!function_exists('pdfTagihanListrik')) {
    function pdfTagihanListrik($data, $title, $startYear, $endYear) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Tagihan Listrik');
        $pdf->SetSubject('Tagihan Listrik');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $dateRange = "";
        if ($startYear !== null && $endYear !== null) {
            $startYear = $startYear;
            $endYear = $endYear;
            $dateRange = ' - ';
        }
    
        $html = <<<EOD
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        if ($startYear !== '' && $endYear !== '') {
            $html .= '<h4 style="text-align: center;">' . $startYear . $dateRange . $endYear . '</h4>';
        }
        
        $html .= <<<EOD
            <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No.</b></th>
                    <th style="width: 30%;"><b>Bulan, Tahun</b></th>
                    <th style="width: 30%;"><b>Pemakaian</b></th>
                    <th style="width: 30%;"><b>Biaya</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;

        foreach ($data as $key => $value) {
            $numericMonth = (int)$value->bulanPemakaianListrik;
            $monthName = $monthNamesIndonesian[$numericMonth];
        
            // Format the biaya with Rupiah
            $formattedBiaya = 'Rp ' . number_format($value->biaya, 0, ',', '.');
        
            $html .= '<tr>';
            $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $monthName . ', ' . $value->tahunPemakaianListrik . '</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $value->pemakaianListrik . ' kWh</td>';
            $html .= '<td style="width: 30%; text-align: left;">' . $formattedBiaya . '</td>';
            $html .= '</tr>';
        }

        $html .= <<<EOD
            </tbody>
        </table>
        EOD;

        
            
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

        return $pdfData;
    }
}

if (!function_exists('pdfUserAction')) {
    function pdfUserAction($dataRestore, $dataDelete, $dataSoftDelete, $title, $startDate, $endDate) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Action Logs');
        $pdf->SetSubject('Action Logs');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        
        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }


    
        $html = <<<EOD
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        if ($startDate !== '' && $endDate !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }

        if (!empty($dataSoftDelete)) {
            $html .= generateUserAction($dataSoftDelete, "Action Type: Soft Delete");
        }

        if (!empty($dataRestore)) {
            $html .= generateUserAction($dataRestore, "Action Type: Restore");
        }

        if (!empty($dataDelete)) {
            $html .= generateUserAction($dataDelete, "Action Type: Delete");
        }

        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

        return $pdfData;
    }

    function generateUserAction($data, $status) {
        $table = <<<EOD
        <br>
        <h4>$status</h4>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 20%;"><b>Tanggal</b></th>
                    <th style="width: 35%;"><b>Identitas</b></th>
                    <th style="width: 35%;"><b>Action Type</b></th>
                </tr>
            </thead>
            <tbody>
        EOD;

        foreach ($data as $key => $value) {
            $table .= '<tr>';
            $table .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
            $table .= '<td style="width: 20%; text-align: left;">' . date('d F Y, H:i:s', strtotime($value->actionTime)) . '</td>';
            $table .= '<td style="width: 35%; text-align: left;">' . 
                            '<table style="width: 100%; padding:5px;">' .
                                '<tr>' .
                                    '<td style="width: 40%;">Username</td>' .
                                    '<td style="width: 10%;">:</td>' .
                                    '<td style="width: 50%;">' . $value->username . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Nama</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->nama . '</td>' .
                                '</tr>' .
                                '<tr>' .
                                    '<td>Role</td>' .
                                    '<td>:</td>' .
                                    '<td>' . $value->role . '</td>' .
                                '</tr>' .
                            '</table>' 
                    . '</td>';
            $table .= '<td style="width: 35%; text-align: left;">' . 
                            '<table style="width: 100%; padding:5px;">' .
                            '<tr>' .
                                '<td style="width: 30%;">Action</td>' .
                                '<td style="width: 10%;">:</td>' .
                                '<td style="width: 50%;">' . $value->actionType . '</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td>Detail</td>' .
                                '<td>:</td>' .
                            '</tr>' .
                            '<tr>' .
                                '<td colspan="3"; style="text-align: justify;" >' . $value->actionDetails . '</td>' .
                            '</tr>' .
                        '</table>' 
                    . '</td>' .
                    '</tr>';
        }

        $table .= <<<EOD
        </tbody>
    </table>
    EOD;

        return $table;
    }
}

if (!function_exists('pdfUserLogs')) {
    function pdfUserLogs($data, $title,  $startDate = null, $endDate = null) {
        usort($data, function($a, $b) {
            return strtotime($a->loginTime) - strtotime($b->loginTime);
        });
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Action Logs');
        $pdf->SetSubject('Action Logs');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 30%;"><b>Identitas</b></th>
                    <th style="width: 30%;"><b>Action Type</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $tanggalTimestamp = strtotime($value->loginTime);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $value->username.  ' (' . $value->role . ')' .'</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $value->actionType. '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfAsetRuangan')) {
    function pdfAsetRuangan($dataPrasaranaRuangan, $dataInfoPrasarana, $dataGeneral) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Aset Ruangan');
        $pdf->SetSubject('Aset Ruangan');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));
        $currentDate = date('d F Y');

        $dateFormatted = '';
        $dateFormatted .= date('d', strtotime($currentDate)) . ' '; 
        $dateFormatted .= $monthNamesIndonesian[date('n', strtotime($currentDate))];
        $dateFormatted .= ' ' . date('Y', strtotime($currentDate));

        $html = <<<EOD
        <p style="padding-top: 10px; text-align: center;"><b>
        DAFTAR INVENTARIS RUANGAN SMK TELKOM BANJARBARU TAHUN $yearNow/$yearNext
        </b></p>
        <h3 style="text-align: center; text-transform: uppercase; text-decoration: underline">$dataPrasaranaRuangan->kodePrasarana
        <br>$dataPrasaranaRuangan->namaPrasarana</h3>
        <table style="padding-top: 10px; width: 100%;">
            <tr>
                <th style="width: 15%;">Gedung</th>
                <th style="width: 2%;">:</th>
                <th>$dataInfoPrasarana->namaGedung</th>
            </tr>
            <tr>
                <th style="width: 15%;">Lantai</th>
                <th style="width: 2%;">:</th>
                <th>$dataInfoPrasarana->namaLantai</th>
            </tr>
            <tr>
                <th style="width: 15%;">Luas</th>
                <th style="width: 2%;">:</th>
                <th>$dataPrasaranaRuangan->luas&nbsp;m&sup2;</th>
            </tr>
        </table>
        <br>
        <br>
        <table border="1" style="width: 100%; padding:5px; text-align: center; border-collapse: collapse;" >
            <thead>
                <tr>
                <th rowspan="2" style="width: 8%;"><b>No.</b></th>
                <th rowspan="2" style="width: 41%;"><b>Nama Barang</b></th>
                <th rowspan="2" style="width: 15%;"><b>Jumlah</b></th>
                <th style="width: 16%; "><b>Kondisi</b></th>
                <th rowspan="2" style="width: 20%;"><b>Keterangan</b></th>
                </tr>
                <tr>
                    <th style="width: 8%;"><b>Baik</b></th>
                    <th style="width: 8%;"><b>Rusak</b></th>
                </tr>
            </thead>
        <tbody>
    EOD;
    
    foreach ($dataGeneral as $key => $value) {
        $asetRusak = $value->jumlahRusak + $value->jumlahHilang;
        $html .= '<tr>';
        $html .= '<td style="width: 8%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 41%; text-align: left;">' . $value->namaSarana . '</td>';
        $html .= '<td style="width: 15%;">' . $value->jumlahTotal . '</td>';
        $html .= '<td style="width: 8%;">' . $value->jumlahBagus . '</td>';
        $html .= '<td style="width: 8%;">' . $asetRusak. '</td>';
        $html .= '<td style="width: 20%;"></td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>
    <br>
    <br>
    <table style="padding-top: 5%;">
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;">Banjarbaru, $dateFormatted</th>
        </tr>
        <tr>
            <th style="width: 66%;"></th>
            <th style="width: 35%;"></th>
        </tr>
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;"></th>
        </tr>
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;"></th>
        </tr>
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;"></th>
        </tr>
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;">(Nama ketua sarpra)</th>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <b>Catatan:</b>
    <ol>
        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
        <li>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
        <li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</li>
        <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</li>
        <li>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit.</li>
        <li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</li>
        <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</li>
        <li>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit.</li>
        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
        <li>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
    </ol>
    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfAsetLaboratorium')) {
    function pdfAsetLaboratorium($dataLabRuangan, $dataInfoLab, $dataGeneral) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Aset Laboratorium');
        $pdf->SetSubject('Aset Laboratorium');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));
        $currentDate = date('d F Y');

        $dateFormatted = '';
        $dateFormatted .= date('d', strtotime($currentDate)) . ' '; 
        $dateFormatted .= $monthNamesIndonesian[date('n', strtotime($currentDate))];
        $dateFormatted .= ' ' . date('Y', strtotime($currentDate));

        $html = <<<EOD
        <p style="padding-top: 10px; text-align: center;"><b>
        DAFTAR INVENTARIS RUANGAN SMK TELKOM BANJARBARU TAHUN $yearNow/$yearNext
        </b></p>
        <h3 style="text-align: center; text-transform: uppercase; text-decoration: underline">$dataLabRuangan->kodeLab
        <br>$dataLabRuangan->namaLab</h3>
        <table style="padding-top: 10px; width: 100%;">
            <tr>
                <th style="width: 15%;">Gedung</th>
                <th style="width: 2%;">:</th>
                <th>$dataInfoLab->namaGedung</th>
            </tr>
            <tr>
                <th style="width: 15%;">Lantai</th>
                <th style="width: 2%;">:</th>
                <th>$dataInfoLab->namaLantai</th>
            </tr>
            <tr>
                <th style="width: 15%;">Luas</th>
                <th style="width: 2%;">:</th>
                <th>$dataLabRuangan->luas&nbsp;m&sup2;</th>
            </tr>
        </table>
        <br>
        <br>
        <table border="1" style="width: 100%; padding:5px; text-align: center; border-collapse: collapse;" >
            <thead>
                <tr>
                <th rowspan="2" style="width: 8%;"><b>No.</b></th>
                <th rowspan="2" style="width: 41%;"><b>Nama Barang</b></th>
                <th rowspan="2" style="width: 15%;"><b>Jumlah</b></th>
                <th style="width: 16%; "><b>Kondisi</b></th>
                <th rowspan="2" style="width: 20%;"><b>Keterangan</b></th>
                </tr>
                <tr>
                    <th style="width: 8%;"><b>Baik</b></th>
                    <th style="width: 8%;"><b>Rusak</b></th>
                </tr>
            </thead>
        <tbody>
    EOD;
    
    foreach ($dataGeneral as $key => $value) {
        $asetRusak = $value->jumlahRusak + $value->jumlahHilang;
        $html .= '<tr>';
        $html .= '<td style="width: 8%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 41%; text-align: left;">' . $value->namaSarana . '</td>';
        $html .= '<td style="width: 15%;">' . $value->jumlahTotal . '</td>';
        $html .= '<td style="width: 8%;">' . $value->jumlahBagus . '</td>';
        $html .= '<td style="width: 8%;">' . $asetRusak. '</td>';
        $html .= '<td style="width: 20%;"></td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>
    <br>
    <br>

    <table style="padding-top: 5%;">
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;">Banjarbaru, $dateFormatted</th>
        </tr>
        <tr>
            <th style="width: 66%;"></th>
            <th style="width: 35%;"></th>
        </tr>
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;"></th>
        </tr>
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;"></th>
        </tr>
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;"></th>
        </tr>
        <tr>
            <th style="width: 65%;"></th>
            <th style="width: 35%;">(Nama ketua sarpra)</th>
        </tr>
    </table>
    <br>
    <br>
    <br>
    <b>Catatan:</b>
    <ol>
        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
        <li>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
        <li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</li>
        <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</li>
        <li>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit.</li>
        <li>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</li>
        <li>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.</li>
        <li>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit.</li>
        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
        <li>Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</li>
    </ol>
    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfPlatformDigitalAplikasi')) {
    function pdfPlatformDigitalAplikasi($data, $title) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Platfrom Digital - Aplikasi');
        $pdf->SetSubject('Platfrom Digital - Aplikasi');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $html = <<<EOD
        <h3 style="text-align: center;"> $title</h3>
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 90%;"><b>Detail Aplikasi</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 90%; text-align: left;">' . 
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 20%;">Nama Aplikasi</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 75%;">' . $value->namaAplikasi . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Nama PIC</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->picAplikasi . '</td>' .
                        '</tr>' .
                    '</table>' 
            . '</td>';
        $html .= '</tr>';
    }
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfPlatformDigitalSosialMedia')) {
    function pdfPlatformDigitalSosialMedia($data, $title) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Platfrom Digital - Sosial Media');
        $pdf->SetSubject('Platfrom Digital - Sosial Media');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $html = <<<EOD
        <h3 style="text-align: center;"> $title</h3>
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 90%;"><b>Detail Sosial Media</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 90%; text-align: left;">' . 
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 20%;">Sosial Media</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 75%;">' . $value->namaSosialMedia . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Username</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->usernameSosialMedia . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Link</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->linkSosialMedia . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Nama PIC</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->picSosialMedia . '</td>' .
                        '</tr>' .
                    '</table>' 
            . '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdfPlatformDigitalWebsite')) {
    function pdfPlatformDigitalWebsite($data, $title) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Platfrom Digital - Website');
        $pdf->SetSubject('Platfrom Digital - Website');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $html = <<<EOD
        <h3 style="text-align: center;"> $title</h3>
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 90%;"><b>Detail Website</b></th>
                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($data as $key => $value) {
        $html .= '<tr>';
        $html .= '<td style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 90%; text-align: left;">' . 
                    '<table style="width: 100%; padding:5px;">' .
                        '<tr>' .
                            '<td style="width: 20%;">Website</td>' .
                            '<td style="width: 5%;">:</td>' .
                            '<td style="width: 75%;">' . $value->namaWebsite . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Fungsi</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->fungsiWebsite . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Link</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->linkWebsite . '</td>' .
                        '</tr>' .
                        '<tr>' .
                            '<td>Nama PIC</td>' .
                            '<td>:</td>' .
                            '<td>' . $value->picWebsite . '</td>' .
                        '</tr>' .
                    '</table>' 
            . '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}



// Not use 
if (!function_exists('pdfDetailPemusnahanAset')) {
    function pdfDetailPemusnahanAset($data, $title) {
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Sarana - Detail Pemusnahan Aset');
        $pdf->SetSubject('Sarana - Detail Pemusnahan Aset');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];
        $tanggalFormatted = number_format($data->hargaBeli, 0, ',', '.');
        
        $imageUrl = generateFileId($data->bukti);
        $html = <<<EOD

        
        <h3 style="text-align: center;"> $title</h3>
        <br>
        <table style="padding-top: 10px;  width: 100%;">
            <tr>
                <th style="width: 30%;">Kode</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$data->kodeRincianAset</th>
            </tr>
            <tr>
                <th style="width: 30%;">Lokasi</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$data->namaPrasarana</th>
            </tr>
            <tr>
                <th style="width: 30%;">Kategori Barang</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$data->namaKategoriManajemen</th>
            </tr>
            <tr>
                <th style="width: 30%;">Nama Aset</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$data->namaSarana</th>
            </tr>
            <tr>
                <th style="width: 30%;">Merek</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$data->merk</th>
            </tr>
            <tr>
                <th style="width: 30%;">Status Terakhir</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$data->status</th>
            </tr>
            <tr>
                <th style="width: 30%;">Sumber Dana</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$data->namaSumberDana</th>
            </tr>
            <tr>
                <th style="width: 30%;">Tahun Pengadaan</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">$data->tahunPengadaan</th>
            </tr>
            <tr>
                <th style="width: 30%;">Harga Beli</th>
                <th style="width: 5%;">:</th>
                <th style="width: 65%;">Rp$tanggalFormatted</th>
            </tr>
            <tr>
                <th><img src="$imageUrl" style="max-width: 800px; height: auto;" alt="Bukti"></th>
            </tr>
        </table>
        EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

// Not priority, maybe i'll do it letter
if (!function_exists('pdfRequestPeminjaman')) {
    function pdfRequestPeminjaman($dataRequest, $dataApprove, $dataReject, $title,  $startDate = null, $endDate = null) {
        usort($dataRequest, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });
        usort($dataApprove, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });
        usort($dataReject, function($a, $b) {
            return strtotime($a->tanggal) - strtotime($b->tanggal);
        });

        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Laboratorium - Non Inventaris');
        $pdf->SetSubject('Laboratorium - Non Inventaris');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');

        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(10, 54, 10);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);
        $pdf->AddPage();

        $monthNamesIndonesian = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $dayNamesIndonesian = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ];

        $startDateFormatted = '';
        $endDateFormatted = '';
        $dateRange = '';

        if ($startDate !== null && $endDate !== null) {
            $startDateFormatted .= date('j', strtotime($startDate));
            $startDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($startDate))];
            $startDateFormatted .= ' ' . date('Y', strtotime($startDate));

            $endDateFormatted .= date('j', strtotime($endDate));
            $endDateFormatted .= ' ' . $monthNamesIndonesian[date('n', strtotime($endDate))];
            $endDateFormatted .= ' ' . date('Y', strtotime($endDate));

            $dateRange = ' - ';
        }
    
        $html = <<<EOD
        <style>
        img {
            width: 380px;
            max-height: 500px;
        }
        </style>
        
        <h3 style="text-align: center;"> $title</h3>
        EOD;
        
        // Include date range only when both $startDate and $endDate are not null
        if ($startDateFormatted !== '' && $endDateFormatted !== '') {
            $html .= '<h4 style="text-align: center;">' . $startDateFormatted . $dateRange . $endDateFormatted . '</h4>';
        }
        
        $html .= <<<EOD
        <h4>Data Request</h4>
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 20%;"><b>Nama</b></th>
                    <th style="width: 20%;"><b>Satuan</b></th>
                    <th style="width: 20%;"><b>Jumlah</b></th>

                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($dataRequest as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->nama. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->satuan. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->jumlah. '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;

    $html .= <<<EOD
        <br>    
        <h4>Data Pengeluaran</h4>
        <br>
        <table border="1" style="text-align: center; width: 100%; padding:5px;">
            <thead>
                <tr>
                    <th style="width: 10%;"><b>No</b></th>
                    <th style="width: 30%;"><b>Tanggal</b></th>
                    <th style="width: 20%;"><b>Nama</b></th>
                    <th style="width: 20%;"><b>Satuan</b></th>
                    <th style="width: 20%;"><b>Jumlah</b></th>

                </tr>
            </thead>
        <tbody>
        EOD;
        
    
    foreach ($dataPengeluaran as $key => $value) {
        $tanggalTimestamp = strtotime($value->tanggal);
        $formattedTanggal = $dayNamesIndonesian[date('w', $tanggalTimestamp)] . ', ' . date('d', $tanggalTimestamp) . ' ' . $monthNamesIndonesian[date('n', $tanggalTimestamp)] . ' ' . date('Y', $tanggalTimestamp);
        $html .= '<tr>';
        $html .= '<td  style="width: 10%;">' . ($key + 1) . '</td>';
        $html .= '<td style="width: 30%; text-align: left;">' . $formattedTanggal. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->nama. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->satuan. '</td>';
        $html .= '<td style="width: 20%; text-align: left;">' . $value->jumlah. '</td>';
        $html .= '</tr>';
    }
    
    $html .= <<<EOD
        </tbody>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Generated PDF.pdf', 'S');

    return $pdfData;
    }
}

?>