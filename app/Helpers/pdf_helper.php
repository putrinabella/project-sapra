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
        $namaPeminjam = ($dataDataPeminjaman->kategoriPeminjam == 'siswa') ? $dataDataPeminjaman->namaSiswa : $dataDataPeminjaman->namaPegawai;
        $idPeminjam = ($dataDataPeminjaman->kategoriPeminjam == 'siswa') ? $dataDataPeminjaman->nis : $dataDataPeminjaman->nip;
        $asalPeminjam = ($dataDataPeminjaman->kategoriPeminjam == 'siswa') ? $dataDataPeminjaman->namaKelas : $dataDataPeminjaman->namaKategoriPegawai;
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
                <th>$namaPeminjam</th>
            </tr>
            <tr>
                <th style="width: 200px;">NIS/NIK</th>
                <th style="width: 20px;">:</th>
                <th>$idPeminjam</th>
            </tr>
            <tr>
                <th style="width: 200px;">Kelas/Karyawan</th>
                <th style="width: 20px;">:</th>
                <th>$asalPeminjam</th>
            </tr>
            <tr>
                <th style="width: 200px;">Keperluan Alat</th>
                <th style="width: 20px;">:</th>
                <th>$dataDataPeminjaman->keperluanAlat</th>
            </tr>
            <tr>
                <th style="width: 200px;">Hari, Tanggal Pinjam</th>
                <th style="width: 20px;">:</th>
                <th>$dataDataPeminjaman->tanggal</th>
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
            <th style="width: 40%;"> ($namaPeminjam)</th>
        </tr>
    </table>

    EOD;
        
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    
    $pdfData = $pdf->Output('Formulir Peminjaman Aset.pdf', 'S');

    return $pdfData;
    }
}

?>