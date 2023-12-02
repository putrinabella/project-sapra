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

if (!function_exists('pdf_suratpeminjaman')) {
    function pdf_suratpeminjaman($dataDataPeminjaman, $dataRincianLabAset) {
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

        $pdf->SetMargins(PDF_MARGIN_LEFT, 54, PDF_MARGIN_RIGHT);
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


        $yearNow = date('Y');
        $yearNext = date('Y', strtotime('+1 year'));
        $html = <<<EOD
        <style>

        </style>
        
        <p style="text-align: right;">No Peminjaman: $dataDataPeminjaman->idManajemenPeminjaman </p>
        <h3 style="text-align: center;">SURAT PERMOHONAN PEMINJAMAN ALAT LAB $yearNow/$yearNext</h3>
        <p style="padding-top: 10px;">Saya yang bertanda tangan di bawah ini: </p>
        <table style="padding-top: 10px;">
            <tr>
                <th style="width: 200px;">Nama</th>
                <th style="width: 20px;">:</th>
                <th>$dataDataPeminjaman->namaSiswa</th>
            </tr>
            <tr>
                <th style="width: 200px;">NIS/NIK</th>
                <th style="width: 20px;">:</th>
                <th>$dataDataPeminjaman->nis</th>
            </tr>
            <tr>
                <th style="width: 200px;">Kelas/Karyawan</th>
                <th style="width: 20px;">:</th>
                <th>$dataDataPeminjaman->namaKelas</th>
            </tr>
            <tr>
                <th style="width: 200px;">Keperluan Alat</th>
                <th style="width: 20px;">:</th>
                <th>$dataDataPeminjaman->keperluanAlat</th>
            </tr>
            <tr>
                <th style="width: 200px;">Hari, Tanggal Pinjam</th>
                <th style="width: 20px;">:</th>
                <th>$tanggalFormatted</th>
            </tr>
            <tr>
                <th style="width: 200px;">Lama Pinjam</th>
                <th style="width: 20px;">:</th>
                <th>$dataDataPeminjaman->lamaPinjam Hari</th>
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
            <th style="width: 40%;">Banjarbaru, $dataDataPeminjaman->tanggal</th>
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
    
    $pdfData = $pdf->Output('Formulir Peminjaman Aset.pdf', 'S');

    return $pdfData;
    }
}

if (!function_exists('pdf_layananaset')) {
    function pdf_layananaset($dataSaranaLayananAset, $title,  $startDate = null, $endDate = null) {
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

        $pdf->SetMargins(PDF_MARGIN_LEFT, 54, PDF_MARGIN_RIGHT);
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
        
    
    foreach ($dataSaranaLayananAset as $key => $value) {
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
    
    $pdfData = $pdf->Output('Formulir Peminjaman Aset.pdf', 'S');

    return $pdfData;
    }
}

?>