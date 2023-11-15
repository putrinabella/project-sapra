<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataPeminjamanModels;
use App\Models\IdentitasSaranaModels;
use App\Models\SumberDanaModels;
use App\Models\KategoriManajemenModels;
use App\Models\IdentitasLabModels;
use App\Models\ManajemenPeminjamanModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;
use TCPDF;

class DataPeminjaman extends ResourceController
{

    function __construct()
    {
        $this->manajemenPeminjamanModel = new ManajemenPeminjamanModels();
        $this->dataPeminjamanModel = new DataPeminjamanModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }

        $data['tableHeading'] = $tableHeading;
        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->getData($startDate, $endDate);
        return view('labView/dataPeminjaman/index', $data);
    }
    
    public function user()
    {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }

        $data['tableHeading'] = $tableHeading;
        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->getData($startDate, $endDate);
        return view('labView/dataPeminjaman/user', $data);
    }

    public function index2() {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $formattedStartDate = !empty($startDate) ? date('d F Y', strtotime($startDate)) : '';
        $formattedEndDate = !empty($endDate) ? date('d F Y', strtotime($endDate)) : '';

        $tableHeading = "";
        if (!empty($formattedStartDate) && !empty($formattedEndDate)) {
            $tableHeading = " $formattedStartDate - $formattedEndDate";
        }

        $dataDataPeminjaman = $this->dataPeminjamanModel->getData($startDate, $endDate);

        $idManajemenPeminjamanArray = [];

        foreach ($dataDataPeminjaman as $peminjaman) {
            $idManajemenPeminjamanArray[] = $this->dataPeminjamanModel->getIdManajemenPeminjaman($peminjaman->namaPeminjam);
        }

        $data['idManajemenPeminjamanArray'] = $idManajemenPeminjamanArray;
        $data['tableHeading'] = $tableHeading;
        $data['dataDataPeminjaman'] = $dataDataPeminjaman;

        return view('labView/dataPeminjaman/index', $data);
    }

    public function edit($id = null)
    {
        if ($id != null) {
            $dataDataPeminjaman = $this->dataPeminjamanModel->find($id);
            $dataItemDipinjam = $this->dataPeminjamanModel->getBorrowItems($dataDataPeminjaman->idManajemenPeminjaman);
            if (is_object($dataDataPeminjaman)) {
                $data = [
                    'dataDataPeminjaman' => $dataDataPeminjaman,
                    // 'dataIdentitasSarana' => $this->dataPeminjamanModel->getPerangkatIT(),
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataItemDipinjam' => $dataItemDipinjam,
                ];
                return view('labView/dataPeminjaman/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    // public function getLoanHistory($id = null)
    // {
    //     if ($id != null) {
    //         $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($id);
    //         $dataItemDipinjam = $this->dataPeminjamanModel->getReturnItem($dataDataPeminjaman->idManajemenPeminjaman);
    //         if (is_object($dataDataPeminjaman)) {
    //             $data = [
    //                 'dataDataPeminjaman' => $dataDataPeminjaman,
    //                 'dataIdentitasLab' => $this->identitasLabModel->findAll(),
    //                 'dataItemDipinjam' => $dataItemDipinjam,
    //             ];
    //             return view('labView/dataPeminjaman/show', $data);
    //         } else {
    //             return view('error/404');
    //         }
    //     } else {
    //         return view('error/404');
    //     }
    // }

    public function getLoanHistory($id = null) {
        if ($id != null) {
            $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($id);
            $dataRincianLabAset = $this->dataPeminjamanModel->getRincianLabAset($id);
            if (is_object($dataDataPeminjaman)) {
                $data = [
                    'dataDataPeminjaman' => $dataDataPeminjaman,
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                    'dataRincianLabAset' => $dataRincianLabAset,
                ];
                return view('labView/dataPeminjaman/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    // public function print($id = null) {
    //     $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($id);
    //     $dataRincianLabAset = $this->dataPeminjamanModel->getRincianLabAset($id);
    
    //     if (!$dataDataPeminjaman || empty($dataRincianLabAset)) {
    //         return view('error/404');
    //     }
    
    //     $data = [
    //         'dataDataPeminjaman' => $dataDataPeminjaman,
    //         'dataIdentitasLab' => $this->identitasLabModel->findAll(),
    //         'dataRincianLabAset' => $dataRincianLabAset,
    //     ];
    
    //     $pdf = new TCPDF();
    
    //     $pdf->SetCreator(PDF_CREATOR);
    //     $pdf->SetAuthor('Putri Nabella');
    //     $pdf->SetTitle('Histori Peminjaman');
    //     $pdf->SetSubject('Histori Peminjaman');
    //     $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');
    
    //     $pdf->AddPage();
    
    //     $pdf->SetFont('times', '', 12);
    
    //     $pdf->Cell(0, 10, 'Histori Peminjaman', 0, 1, 'C');
    
    //     $pdf->Cell(0, 10, 'Tanggal Peminjaman: ' . date('d F Y', strtotime($dataDataPeminjaman->tanggal)), 0, 1, 'L');
    
    //     $pdf->Ln(); 
    //     $pdf->SetFont('times', 'B', 12);
    //     $pdf->Cell(5, 10, 'No.', 1);
    //     $pdf->Cell(0, 10, 'Kode', 1);
    //     $pdf->Cell(0, 10, 'Nama', 1);
    //     $pdf->Cell(0, 10, 'Kategori', 1);
    //     $pdf->Cell(0, 10, 'Merk', 1);
    //     $pdf->Cell(0, 10, 'Warna', 1);
    //     $pdf->Cell(0, 10, 'Kondisi Awal', 1);
    //     $pdf->Cell(0, 10, 'Kondisi Saat Dikembalikan', 1);
    //     $pdf->Ln(); 
    
    //     foreach ($dataRincianLabAset as $key => $value) {
    //         $pdf->Cell(5, 10, $key, 1);
    //         $pdf->Cell(0, 10, $value->kodeRincianLabAset, 1);
    //         $pdf->Cell(0, 10, $value->namaSarana, 1);
    //         $pdf->Cell(0, 10, $value->namaKategoriManajemen, 1);
    //         $pdf->Cell(0, 10, $value->merk, 1);
    //         $pdf->Cell(0, 10, $value->warna, 1);
    //         $pdf->Cell(0, 10, 'Bagus', 1);
    //         $pdf->Cell(0, 10, $value->statusSetelahPengembalian, 1);
    //         $pdf->Ln(); 
    //     }
    
    //     $pdf->Output('histori_peminjaman.pdf', 'I');
    
    //     // Add the following lines to set appropriate headers
    //     $response = $this->response;
    //     $response->setHeader('Content-Type', 'application/pdf');
    //     $response->setHeader('Content-Disposition', 'inline; filename="histori_peminjaman.pdf"');
    //     $response->send();
    // }
    

    // public function print($id) {
    //     // create new PDF document
    //     $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    //     // set document information
    //     $pdf->SetCreator(PDF_CREATOR);
    //     $pdf->SetAuthor('Nicola Asuni');
    //     $pdf->SetTitle('TCPDF Example 001');
    //     $pdf->SetSubject('TCPDF Tutorial');
    //     $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

    //     // set default header data
    //     $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
    //     $pdf->setFooterData(array(0,64,0), array(0,64,128));

    //     // set header and footer fonts
    //     $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    //     $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    //     // set default monospaced font
    //     $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    //     // set margins
    //     $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    //     $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //     $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    //     // set auto page breaks
    //     $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    //     // set image scale factor
    //     $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    //     // set some language-dependent strings (optional)
    //     if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    //         require_once(dirname(__FILE__).'/lang/eng.php');
    //         $pdf->setLanguageArray($l);
    //     }

    //     // ---------------------------------------------------------

    //     // set default font subsetting mode
    //     $pdf->setFontSubsetting(true);

    //     // Set font
    //     // dejavusans is a UTF-8 Unicode font, if you only need to
    //     // print standard ASCII chars, you can use core fonts like
    //     // helvetica or times to reduce file size.
    //     $pdf->SetFont('dejavusans', '', 14, '', true);

    //     // Add a page
    //     // This method has several options, check the source code documentation for more information.
    //     $pdf->AddPage();

    //     // set text shadow effect
    //     $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

    //     // Set some content to print
    //     $html = <<<EOD
    //     <h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
    //     <i>This is the first example of TCPDF library.</i>
    //     <p>This text is printed using the <i>writeHTMLCell()</i> method but you can also use: <i>Multicell(), writeHTML(), Write(), Cell() and Text()</i>.</p>
    //     <p>Please check the source code documentation and other examples for further information.</p>
    //     <p style="color:#CC0000;">TO IMPROVE AND EXPAND TCPDF I NEED YOUR SUPPORT, PLEASE <a href="http://sourceforge.net/donate/index.php?group_id=128076">MAKE A DONATION!</a></p>
    //     EOD;

    //     // Print text using writeHTMLCell()
    //     $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

    //     // ---------------------------------------------------------

    //     // Close and output PDF document
    //     // This method has several options, check the source code documentation for more information.
    //     $pdf->Output('example_001.pdf', 'I');

    // }

    // public function print($id = null) {
    //     $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($id);
    //     $dataRincianLabAset = $this->dataPeminjamanModel->getRincianLabAset($id);
    
    //     if (!$dataDataPeminjaman || empty($dataRincianLabAset)) {
    //         return view('error/404');
    //     }
    
    //     $data = [
    //         'dataDataPeminjaman' => $dataDataPeminjaman,
    //         'dataIdentitasLab' => $this->identitasLabModel->findAll(),
    //         'dataRincianLabAset' => $dataRincianLabAset,
    //     ];
    
    //     $pdf = new TCPDF();

    //     $headerImage = base_url('assets/images/favicon.png');
    
    //     $pdf->SetHeaderData($headerImage, 30);
        
    //     // $pdf->SetHeaderData($headerImage, 30, 'Histori Peminjaman', 'Tanggal Peminjaman: ' . date('d F Y', strtotime($dataDataPeminjaman->tanggal)));
    //     $pdf->SetCreator(PDF_CREATOR);
    //     $pdf->SetAuthor('Putri Nabella');
    //     $pdf->SetTitle('Histori Peminjaman');
    //     $pdf->SetSubject('Histori Peminjaman');
    //     $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');
    
    //     $pdf->AddPage();
    
    //     $pdf->SetFont('times', '', 12);
    
    //     $pdf->Cell(0, 10, 'Histori Peminjaman', 0, 1, 'C');
    
    //     $pdf->Cell(0, 10, 'Tanggal Peminjaman: ' . date('d F Y', strtotime($dataDataPeminjaman->tanggal)), 0, 1, 'L');
    //     $pdf->Cell(0, 10, 'Tanggal Pengembalian: ' . date('d F Y', strtotime($dataDataPeminjaman->tanggalPengembalian)), 0, 1, 'L');
    
        
    //     $pdf->Ln(); 
    //     $pdf->SetFont('times', 'B', 12);
    //     $pdf->Cell(5, 10, 'No.', 1);
    //     $pdf->Cell(0, 10, 'Kode', 1);
    //     $pdf->Cell(0, 10, 'Nama', 1);
    //     $pdf->Cell(0, 10, 'Kategori', 1);
    //     $pdf->Cell(0, 10, 'Merk', 1);
    //     $pdf->Cell(0, 10, 'Warna', 1);
    //     $pdf->Cell(0, 10, 'Kondisi Awal', 1);
    //     $pdf->Cell(0, 10, 'Kondisi Saat Dikembalikan', 1);
    //     $pdf->Ln(); 
    
    //     foreach ($dataRincianLabAset as $key => $value) {
    //         $pdf->Cell(5, 10, $key, 1);
    //         $pdf->Cell(0, 10, $value->kodeRincianLabAset, 1);
    //         $pdf->Cell(0, 10, $value->namaSarana, 1);
    //         $pdf->Cell(0, 10, $value->namaKategoriManajemen, 1);
    //         $pdf->Cell(0, 10, $value->merk, 1);
    //         $pdf->Cell(0, 10, $value->warna, 1);
    //         $pdf->Cell(0, 10, 'Bagus', 1);
    //         $pdf->Cell(0, 10, $value->statusSetelahPengembalian, 1);
    //         $pdf->Ln(); 
    //     }
    
    //     $pdfData = $pdf->Output('histori_peminjaman.pdf', 'S');
    
    //     // Set appropriate headers
    //     $response = $this->response;
    //     $response->setHeader('Content-Type', 'application/pdf');
    //     $response->setHeader('Content-Disposition', 'inline; filename="histori_peminjaman.pdf"');
    //     $response->setBody($pdfData);
    //     $response->send();
    // }

    public function print($id = null) {
        $dataDataPeminjaman = $this->dataPeminjamanModel->findHistory($id);
        $dataRincianLabAset = $this->dataPeminjamanModel->getRincianLabAset($id);
    
        if (!$dataDataPeminjaman || empty($dataRincianLabAset)) {
            return view('error/404');
        }
    
        $data = [
            'dataDataPeminjaman' => $dataDataPeminjaman,
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
            'dataRincianLabAset' => $dataRincianLabAset,
        ];
    
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Putri Nabella');
        $pdf->SetTitle('Histori Peminjaman');
        $pdf->SetSubject('Histori Peminjaman');
        $pdf->SetKeywords('TCPDF, PDF, CodeIgniter 4');
        // set default header data
        
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->setFooterData(array(0,64,0), array(0,64,128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        $pdf->SetFont('times', '', 12, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        // set text shadow effect
        // $pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

        // Set some content to print
        $html = <<<EOD
        <p style="text-align: right";>No Peminjaman: 41</p>
        <h3 style="text-align: center";>SURAT PERMOHONAN PEMINJAMAN ALAT LAB 2023/2024</h3>
        <p style="padding-top: 10px;">Saya yang bertanda tangan di bawah ini: </p>
        <table>
            <tr>
                <th style="width: 100px";>Nama</th>
                <th>:</th>
                <th>$dataDataPeminjaman->namaPeminjam</th>
            </tr>
        </table>
        <p style="padding-top: 10px;">Saya yang bertanda tangan di bawah ini: $dataDataPeminjaman->namaPeminjam</p>




        EOD;
        
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        
        $pdfData = $pdf->Output('histori_peminjaman.pdf', 'S');
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="histori_peminjaman.pdf"');
        $response->setBody($pdfData);
        $response->send();
    }
    

    



    public function update($id = null)
    {
        if ($id != null) {
            $data = $this->request->getPost();
            $statuses = $data['status'];
            $idRincianLabAsets = $data['idRincianLabAset'];
            $updateData = [
                'loanStatus' => 'Pengembalian',
                'namaPenerima' => $data['namaPenerima'],
                'tanggalPengembalian' => $data['tanggalPengembalian'],
            ];

            $getIdManajemenPeminjaman = [
                'idManajemenPeminjaman' => $data['idManajemenPeminjaman'],
            ];
            // $this->dataPeminjamanModel->update($id, $updateData);

            foreach ($statuses as $index => $status) {
                $idRincianLabAset = $idRincianLabAsets[$index];

                $this->dataPeminjamanModel->updateReturnStatus($idRincianLabAset, $status);
                $this->dataPeminjamanModel->updateReturnSectionAset($idRincianLabAset);
                $this->dataPeminjamanModel->updateDetailReturnStatus($idRincianLabAset, $getIdManajemenPeminjaman, $status);
            }
            $this->dataPeminjamanModel->update($id, $updateData);
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Aset berhasil dikembalikan');
        } else {
            return view('error/404');
        }
    }

    // public function update($id = null) {
    //     $data = $this->request->getPost();
    //     $idRincianLabAset = $data['idRincianLabAset'];
    //     $idManajemenPeminjaman = $data['idManajemenPeminjaman'];
    //     $idIdentitasSarana = $data['idIdentitasSarana'];
    //     $idIdentitasLab = $data['idIdentitasLab'];
    //     $sectionAsetValue = 'None';
    //     $jumlah = $data['jumlahPeminjaman'];


    //     if ($data['loanStatus'] === 'Pengembalian') {
    //         $jumlahBarangDikembalikan = $data['jumlahBarangDikembalikan'];
    //         $jumlahBarangRusak = $data['jumlahBarangRusak'];
    //         $jumlahBarangHilang = $data['jumlahBarangHilang'];

    //         $assetsToBorrow = $this->manajemenPeminjamanModel->getBorrowedItems($idIdentitasSarana, $jumlah, $idIdentitasLab);

    //         $updateRusak = 0;
    //         $updateHilang = 0;

    //         foreach ($assetsToBorrow as $asset) {
    //             if ($jumlahBarangRusak > $updateRusak) {
    //                 $this->manajemenPeminjamanModel->updateReturnStatus($asset['idIdentitasSarana'], 'Rusak', 1, $asset['idIdentitasLab'], $asset['idManajemenPeminjaman']);
    //                 $this->manajemenPeminjamanModel->updateReturnSectionAsetRusak($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman, "Rusak");
    //                 $updateRusak++;
    //             } 

    //             if ($jumlahBarangHilang > $updateHilang) {
    //                 $this->manajemenPeminjamanModel->updateReturnStatus($asset['idIdentitasSarana'], 'Hilang', 1, $asset['idIdentitasLab'], $asset['idManajemenPeminjaman']);
    //                 $this->manajemenPeminjamanModel->updateReturnSectionAsetHilang($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman, "Hilang");
    //                 $updateHilang++;
    //             }
    //         }
    //     } 

    //     $this->manajemenPeminjamanModel->updateReturnSectionAset($idIdentitasSarana, $sectionAsetValue, $idIdentitasLab, $jumlah, $idManajemenPeminjaman);
    //     $this->dataPeminjamanModel->update($id, $data);

    //     return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Return data berhasil disimpan');
    // }

    public function delete($id = null)
    {
        $this->dataPeminjamanModel->delete($id);
        return redirect()->to(site_url('dataPeminjaman'));
    }

    public function trash()
    {
        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->onlyDeleted()->getRecycle();
        return view('labView/dataPeminjaman/trash', $data);
    }

    public function restore($id = null)
    {
        $this->db = \Config\Database::connect();
        if ($id != null) {
            $this->db->table('tblManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where(['idManajemenPeminjaman' => $id])
                ->update();
        } else {
            $this->db->table('tblManajemenPeminjaman')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }
        if ($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('dataPeminjaman'))->with('success', 'Data berhasil direstore');
        }
        return redirect()->to(site_url('dataPeminjaman/trash'))->with('error', 'Tidak ada data untuk direstore');
    }

    public function deletePermanent($id = null)
    {
        if ($id != null) {
            $this->dataPeminjamanModel->delete($id, true);
            return redirect()->to(site_url('dataPeminjaman/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->dataPeminjamanModel->onlyDeleted()->countAllResults();

            if ($countInTrash > 0) {
                $this->dataPeminjamanModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('dataPeminjaman/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('dataPeminjaman/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }


    public function export()
    {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');

        $data = $this->dataPeminjamanModel->getDataExcel($startDate, $endDate);

        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Pengembalian');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');

        $headers = ['No.', 'Tanggal', 'Nama', 'Asal', 'Barang yang dipinjam', 'Lokasi', 'Status', 'Kondisi Awal', 'Kondisi Pengembalian', 'Tanggal Pengembalian'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:J1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A' . ($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B' . ($index + 2), $value->tanggal);
            $activeWorksheet->setCellValue('C' . ($index + 2), $value->namaPeminjam);
            $activeWorksheet->setCellValue('D' . ($index + 2), $value->asalPeminjam);
            $activeWorksheet->setCellValue('E' . ($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('F' . ($index + 2), $value->namaLab);
            if ($value->loanStatus == "Peminjaman") {
                $activeWorksheet->setCellValue('G' . ($index + 2), 'Sedang Dipinjam');
            } else {
                $activeWorksheet->setCellValue('G' . ($index + 2), 'Sudah Dikembalikan');
            }
            $activeWorksheet->setCellValue('H' . ($index + 2), "Bagus");
            $activeWorksheet->setCellValue('I' . ($index + 2), $value->statusSetelahPengembalian);
            $activeWorksheet->setCellValue('J' . ($index + 2), $value->tanggalPengembalian);


            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }
        $activeWorksheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:J1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:J' . $activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:J')->getAlignment()->setWrapText(true);

        foreach (range('A', 'J') as $column) {
            if ($column === 'K') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'L') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40);
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $dataPeminjaman = $this->dataPeminjamanModel->getDataExcelPeminjaman($startDate, $endDate);
        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Data Peminjaman');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headerExampleTable = ['No.', 'Tanggal', 'Nama', 'Asal', 'Barang yang dipinjam', 'Lokasi', 'Status', 'Kondisi Awal'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($dataPeminjaman as $index => $value) {
            $exampleSheet->setCellValue('A' . ($index + 2), $index + 1);
            $exampleSheet->setCellValue('B' . ($index + 2), $value->tanggal);
            $exampleSheet->setCellValue('C' . ($index + 2), $value->namaPeminjam);
            $exampleSheet->setCellValue('D' . ($index + 2), $value->asalPeminjam);
            $exampleSheet->setCellValue('E' . ($index + 2), $value->namaSarana);
            $exampleSheet->setCellValue('F' . ($index + 2), $value->namaLab);
            if ($value->loanStatus == "Peminjaman") {
                $exampleSheet->setCellValue('G' . ($index + 2), 'Sedang Dipinjam');
            } else {
                $exampleSheet->setCellValue('G' . ($index + 2), 'Sudah Dikembalikan');
            }
            $exampleSheet->setCellValue('H' . ($index + 2), "Bagus");

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }
        $exampleSheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:H1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:H' . $exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:H')->getAlignment()->setWrapText(true);

        foreach (range('A', 'H') as $column) {
            if ($column === 'K') {
                $exampleSheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'L') {
                $exampleSheet->getColumnDimension($column)->setWidth(40);
            } else {
                $exampleSheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Data Peminjaman.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function generatePDF()
    {
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $filePath = APPPATH . 'Views/labView/dataPeminjaman/print.php';

        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataDataPeminjaman'] = $this->dataPeminjamanModel->getDataExport($startDate, $endDate);
        foreach ($data['dataDataPeminjaman'] as $key => $value) {
            $idManajemenPeminjaman = $value->idManajemenPeminjaman;
            $data['dataRincianLabAset'] =$this->dataPeminjamanModel->getRincianLabAset($idManajemenPeminjaman);
        }
        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };

        $includeFile($filePath, $data);

        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $filename = "Laboratorium - Data Peminjaman.pdf";
        $dompdf->stream($filename);
    }

    public function changeStatus($idRicianLabAset)
    {
        $newSectionAset = $this->request->getPost('sectionAset');
        $namaAkun = $this->request->getPost('namaAkun');
        $kodeAkun = $this->request->getPost('kodeAkun');

        if ($this->rincianAsetModel->updateSectionAset($idRicianLabAset, $newSectionAset, $namaAkun, $kodeAkun)) {
            if ($newSectionAset === 'Dimusnahkan') {
                return redirect()->to(site_url('rincianAset'))->with('success', 'Aset berhasil dimusnahkan');
            } elseif ($newSectionAset === 'None') {
                return redirect()->to(site_url('rincianAset'))->with('success', 'Sarana berhasil dikembalikan');
            }
        } else {
            return redirect()->to(site_url('rincianAset'))->with('error', 'Aset batal dimusnahkan');
        }
    }
}