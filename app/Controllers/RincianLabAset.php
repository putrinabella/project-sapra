<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianLabAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasLabModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class RincianLabAset extends ResourceController
{
    function __construct() {
        $this->rincianLabAsetModel = new RincianLabAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasLabModel = new IdentitasLabModels();
        $this->db = \Config\Database::connect();
    }

    public function generateAndSetKodeRincianLabAset() {

        $dataRincianLabAset = $this->rincianLabAsetModel->getAll();

        foreach ($dataRincianLabAset as $data) {
            $newKodeRincianLabAset = $this->generateKodeRincianLabAset(
                $data->idKategoriManajemen,
                $data->idIdentitasLab,
                $data->idSumberDana,
                $data->idIdentitasSarana,
                $data->tahunPengadaan,
                $data->nomorBarang
            );

            $this->rincianLabAsetModel->updateKodeRincianLabAset($data->idRincianLabAset, $newKodeRincianLabAset);
        }
        return redirect()->to(site_url('rincianLabAset'))->with('success', 'Berhasil generate kode aset');
    }

    public function generateKodeRincianLabAset($idKategoriManajemen, $idIdentitasLab, $idSumberDana, $idIdentitasSarana, $tahunPengadaan, $nomorBarang) {
        $kodeKategoriManajemen = $this->kategoriManajemenModel->getKodeKategoriManajemenById($idKategoriManajemen);
        $kodeLab = $this->identitasLabModel->getKodeLabById($idIdentitasLab);
        $kodeSumberDana = $this->sumberDanaModel->getKodeSumberDanaById($idSumberDana);
        $kodeSarana = $this->identitasSaranaModel->getKodeSaranaById($idIdentitasSarana);

        if ($tahunPengadaan === '0000') {
            $tahunPengadaan = 'xx';
        } else {
            $tahunPengadaan = substr($tahunPengadaan, -2);
        }

        $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);
        // $kodeLab = substr($kodeLab, -2);

        $kodeRincianLabAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodeLab . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;

        return $kodeRincianLabAset;
    }

    public function generateKode() {
        $idKategoriManajemen = $this->request->getPost('idKategoriManajemen');
        $idIdentitasLab = $this->request->getPost('idIdentitasLab');
        $idSumberDana = $this->request->getPost('idSumberDana');
        $tahunPengadaan = $this->request->getPost('tahunPengadaan');
        $idIdentitasSarana = $this->request->getPost('idIdentitasSarana');
        $nomorBarang = $this->request->getPost('nomorBarang');
        
        $kodeKategoriManajemen = $this->kategoriManajemenModel->getKodeKategoriManajemenById($idKategoriManajemen);
        $kodeLab = $this->identitasLabModel->getKodeLabById($idIdentitasLab);
        $kodeSumberDana = $this->sumberDanaModel->getKodeSumberDanaById($idSumberDana);
        $kodeSarana = $this->identitasSaranaModel->getKodeSaranaById($idIdentitasSarana);

        if ($tahunPengadaan === '0000') {
            $tahunPengadaan = 'xx';
        } else {
            $tahunPengadaan = substr($tahunPengadaan, -2);
        }
        // $kodeLab = substr($kodeLab, -2);
        $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);

        
        $kodeRincianLabAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodeLab . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;
        echo json_encode($kodeRincianLabAset);
    }

    public function checkDuplicate() {
        $kodeRincianLabAset = $this->request->getPost('kodeRincianLabAset');
    
        $isDuplicate = $this->rincianLabAsetModel->isDuplicate($kodeRincianLabAset);
    
        echo json_encode(['isDuplicate' => $isDuplicate]);
    }
    

    public function index() {
        $data['dataRincianLabAset'] = $this->rincianLabAsetModel->getAll();
        return view('labView/rincianLabAset/index', $data);
    }

    public function dataSarana() {
        $dataGeneral = $this->rincianLabAsetModel->getDataBySarana();

        $jumlahTotal = 0;
        foreach ($dataGeneral as $value) {
            $jumlahTotal += $value->jumlahAset;
        }
    
        $data['dataGeneral'] = $dataGeneral;
        $data['jumlahTotal'] = $jumlahTotal;
        
        return view('labView/rincianLabAset/dataSarana', $data);
    }

    public function pemusnahanLabAset() {
        $data['dataRincianLabAset'] = $this->rincianLabAsetModel->getDestroy();
        return view('labView/rincianLabAset/dataPemusnahanLabAset', $data);
    }

    public function pemusnahanLabAsetDelete($idRincianLabAset) {
        if ($this->request->getMethod(true) === 'POST') {
            $newSectionAset = $this->request->getPost('sectionAset');
            $namaAkun = $this->request->getPost('namaAkun'); 
            $kodeAkun = $this->request->getPost('kodeAkun'); 
    
            if ($this->rincianLabAsetModel->updateSectionAset($idRincianLabAset, $newSectionAset, $namaAkun, $kodeAkun)) {
                if ($newSectionAset === 'Dimusnahkan') {
                    return redirect()->to(site_url('pemusnahanLabAset'))->with('success', 'Aset berhasil dimusnahkan');
                } elseif ($newSectionAset === 'None') {
                    return redirect()->to(site_url('rincianLabAset'))->with('success', 'Aset berhasil dikembalikan');
                }
            } else {
                return redirect()->to(site_url('rincianLabAset'))->with('error', 'Aset batal dimusnahkan');
            }
        }
    }
    
    public function dataSaranaDetail($id = null) {
        $data['dataSarana'] = $this->rincianLabAsetModel->getDataBySaranaDetail($id);
        return view('labView/rincianLabAset/dataSaranaDetail', $data);
    }


    public function show($id = null) {
        if ($id != null) {
            $dataRincianLabAset = $this->rincianLabAsetModel->find($id);
        
            if (is_object($dataRincianLabAset)) {
                $spesifikasiMarkup = $dataRincianLabAset->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataRincianLabAset->bukti);
                $data = [
                    'dataRincianLabAset'           => $dataRincianLabAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('labView/rincianLabAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data = [
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataSumberDana' => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasLab' => $this->identitasLabModel->findAll(),
        ];
        
        return view('labView/rincianLabAset/new', $data);        
    }
    
    public function create() {
        $data = $this->request->getPost();
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['idIdentitasLab'])) {
            
            $insertedID = $this->rincianLabAsetModel->insert($data);
            $this->rincianLabAsetModel->setKodeAset($insertedID);
            
            return redirect()->to(site_url('rincianLabAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('rincianLabAset'))->with('error', 'Semua field harus terisi');
        }
    }
    

    private function uploadFile($fieldName) {
        $file = $this->request->getFile($fieldName);
        if ($file !== null) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move(ROOTPATH . 'public/uploads', $newName);
                return 'uploads/' . $newName;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    private function generateFileId($url) {
        preg_match('/\/file\/d\/(.*?)\//', $url, $matches);
        
        if (isset($matches[1])) {
            $fileId = $matches[1];
            return "https://drive.google.com/uc?export=view&id=" . $fileId;
        } else {
            return "Invalid Google Drive URL";
        }
    }
    
    public function update($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
    
                $this->rincianLabAsetModel->update($id, $data);
                
                $this->rincianLabAsetModel->updateKodeAset($id);
                
                return redirect()->to(site_url('rincianLabAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    

    public function edit($id = null) {
        if ($id != null) {
            $dataRincianLabAset = $this->rincianLabAsetModel->find($id);
    
            if (is_object($dataRincianLabAset)) {
                $data = [
                    'dataRincianLabAset' => $dataRincianLabAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                ];
                return view('labView/rincianLabAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function editPemusnahanLab($id = null) {
        if ($id != null) {
            $dataRincianLabAset = $this->rincianLabAsetModel->find($id);
    
            if (is_object($dataRincianLabAset)) {
                $data = [
                    'dataRincianLabAset' => $dataRincianLabAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasLab' => $this->identitasLabModel->findAll(),
                ];
                return view('labView/rincianLabAset/editPemusnahanLab', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function updatePemusnahanLab($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
            $this->rincianLabAsetModel->update($id, $data);
            return redirect()->to(site_url('pemusnahanLabAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    

    public function delete($id = null) {
        $this->rincianLabAsetModel->delete($id);
        return redirect()->to(site_url('rincianLabAset'));
    }

    public function trash() {
        $data['dataRincianLabAset'] = $this->rincianLabAsetModel->onlyDeleted()->getRecycle();
        return view('labView/rincianLabAset/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblRincianLabAset')
                ->set('deleted_at', null, true)
                ->where(['idRincianLabAset' => $id])
                ->update();
        } else {
            $this->db->table('tblRincianLabAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('rincianLabAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('rincianLabAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->rincianLabAsetModel->delete($id, true);
        return redirect()->to(site_url('rincianLabAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->rincianLabAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->rincianLabAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('rincianLabAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('rincianLabAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }
    
    public function export() {
        $data = $this->rincianLabAsetModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Kode Aset', 'Lokasi', 'Kategori Barang','Spesifikasi Barang', 'Status', 'Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
            
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeRincianLabAset);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->status);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->tahunPengadaan);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->hargaBeli);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->merk);
            $activeWorksheet->setCellValue('K'.($index + 2), $value->noSeri);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->warna);
            $activeWorksheet->setCellValue('M'.($index + 2), $spesifikasiText);
            $linkCell = 'N' . ($index + 2);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 

            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $activeWorksheet->setCellValue($linkCell, $hyperlinkFormula);
        
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'N'];

            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                $alignment->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            
                if ($column === 'M') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }

        
        $activeWorksheet->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:N1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:N'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:N')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'N') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Rincian Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->rincianLabAsetModel->getAll();
        $keyLab = $this->identitasLabModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $keyKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $keySarana = $this->identitasSaranaModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode Rincian Aset', 'ID Identitas Lab', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status (Bagus, Rusak, Hilang)', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerLabID = ['ID Identitas Lab', 'Nama Lab', 'Kode'];
        $activeWorksheet->fromArray([$headerLabID], NULL, 'Q1');
        $activeWorksheet->getStyle('Q1:S1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerKategoriManajemenID = ['ID Kategori Barang', 'Kategori Barang', 'Kode'];
        $activeWorksheet->fromArray([$headerKategoriManajemenID], NULL, 'U1');
        $activeWorksheet->getStyle('U1:W1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerSaranaID = ['ID Identitas Sarana', 'Nama Aset', 'Kode'];
        $activeWorksheet->fromArray([$headerSaranaID], NULL, 'Y1');
        $activeWorksheet->getStyle('Y1:AA1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headerSumberDanaID = ['ID Sumber Dana', 'Sumber Dana', 'Kode'];
        $activeWorksheet->fromArray([$headerSumberDanaID], NULL, 'AC1');
        $activeWorksheet->getStyle('AC1:AE1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            if ($index >= 1) {
                break;
            }

            $activeWorksheet->setCellValue('G' . ($index + 2), 'Bagus'); 

            $dataValidation = $activeWorksheet->getCell('G' . ($index + 2))->getDataValidation();
            $dataValidation->setType(DataValidation::TYPE_LIST);
            $dataValidation->setErrorStyle(DataValidation::STYLE_STOP);
            $dataValidation->setShowDropDown(true);
            $dataValidation->setErrorTitle('Input error');
            $dataValidation->setError('Value is not in list.');
            $dataValidation->setFormula1('"Bagus,Rusak,Hilang"'); 
            // work
            // $generateID = '=CONCAT("TS-BJB ", IF(D' . ($index + 2) . ' = U' . ($index + 2) . ', W' . ($index + 2) . ', D' . ($index + 2) . '), " ", IF(C' . ($index + 2) . ' = Q' . ($index + 2) . ', S' . ($index + 2) . ', C' . ($index + 2) . '), " ", IF(H' . ($index + 2) . ' = AC' . ($index + 2) . ', AE' . ($index + 2) . ', H' . ($index + 2) . '), " ",  IF(I' . ($index + 2) . ' = 0, "XX", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2)), " ", IF(E' . ($index + 2) . ' = Y' . ($index + 2) . ', AA' . ($index + 2) . ', E' . ($index + 2) . '), " ", TEXT(F' . ($index + 2) . ', "000"))';
            
            $kategoriBarangKode = '=IFERROR(INDEX($W$2:$W$' . (count($keyKategoriManajemen) + 1) . ', MATCH(D' . ($index + 2) . ', $U$2:$U$' . (count($keyKategoriManajemen) + 1) . ', 0)), D' . ($index + 2) . ')';
            $ruanganKode        = '=IFERROR(INDEX($S$2:$S$' . (count($keyLab) + 1) . ', MATCH(C' . ($index + 2) . ', $Q$2:$Q$' . (count($keyLab) + 1) . ', 0)), C' . ($index + 2) . ')';
            $sumberDanaKode     = '=IFERROR(INDEX($AE$2:$AE$' . (count($keySumberDana) + 1) . ', MATCH(H' . ($index + 2) . ', $AC$2:$AC$' . (count($keySumberDana) + 1) . ', 0)), H' . ($index + 2) . ')';
            $tahunKode          = '=IF(I' . ($index + 2) . ' = 0, "XX", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2))';
            $spesifikasiKode    = '=IFERROR(INDEX($AA$2:$AA$' . (count($keySarana) + 1) . ', MATCH(E' . ($index + 2) . ', $Y$2:$Y$' . (count($keySarana) + 1) . ', 0)), E' . ($index + 2) . ')';
            $nomorBarangKode    = '=TEXT(F' . ($index + 2) . ', "000")';

            $generateID = '=CONCAT("TS-BJB ", IFERROR(INDEX($W$2:$W$' . (count($keyKategoriManajemen) + 1) . ', MATCH(D' . ($index + 2) . ', $U$2:$U$' . (count($keyKategoriManajemen) + 1) . ', 0)), D' . ($index + 2) . '), " ", IFERROR(INDEX($S$2:$S$' . (count($keyLab) + 1) . ', MATCH(C' . ($index + 2) . ', $Q$2:$Q$' . (count($keyLab) + 1) . ', 0)), C' . ($index + 2) . '), " ", IFERROR(INDEX($AE$2:$AE$' . (count($keySumberDana) + 1) . ', MATCH(H' . ($index + 2) . ', $AC$2:$AC$' . (count($keySumberDana) + 1) . ', 0)), H' . ($index + 2) . '), " ", IF(I' . ($index + 2) . ' = 0, "XX", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2)), " ", IFERROR(INDEX($AA$2:$AA$' . (count($keySarana) + 1) . ', MATCH(E' . ($index + 2) . ', $Y$2:$Y$' . (count($keySarana) + 1) . ', 0)), E' . ($index + 2) . '), " ", TEXT(F' . ($index + 2) . ', "000"))';
            $generateStatus = '=IF(OR(ISBLANK(C' . ($index + 2) . '), ISBLANK(D' . ($index + 2) . '), ISBLANK(E' . ($index + 2) . '), ISBLANK(F' . ($index + 2) . '), ISBLANK(H' . ($index + 2) . '), ISBLANK(I' . ($index + 2) . '), ISBLANK(J' . ($index + 2) . '), ISBLANK(K' . ($index + 2) . '), ISBLANK(L' . ($index + 2) . '), ISBLANK(M' . ($index + 2) . '), ISBLANK(N' . ($index + 2) . '), ISBLANK(O' . ($index + 2) . ')), "ERROR: empty data", "CORRECT: fill up")';
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $generateID);
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');
            $activeWorksheet->setCellValue('F'.($index + 2), '');
            $activeWorksheet->setCellValue('H'.($index + 2), '');
            $activeWorksheet->setCellValue('I'.($index + 2), '');
            $activeWorksheet->setCellValue('J'.($index + 2), '');
            $activeWorksheet->setCellValue('K'.($index + 2), '');
            $activeWorksheet->setCellValue('L'.($index + 2), '');
            $activeWorksheet->setCellValue('M'.($index + 2), '');
            $activeWorksheet->setCellValue('N'.($index + 2), '');
            $activeWorksheet->setCellValue('O'.($index + 2), '');
            $activeWorksheet->setCellValue('P'.($index + 2), $generateStatus);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('A1:O1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $activeWorksheet->getStyle('A1:O'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:O')->getAlignment()->setWrapText(true);
        

        foreach (range('A', 'O') as $column) {
            if ($column === 'B') {
                $activeWorksheet->getColumnDimension($column)->setWidth(35);
            } else if ($column === 'N') {
                $activeWorksheet->getColumnDimension($column)->setWidth(50);
            }
            else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
        }

        $activeWorksheet->getColumnDimension('P')->setWidth(15); 
                
        foreach ($keyLab as $index => $value) {
            $activeWorksheet->setCellValue('Q'.($index + 2), $value->idIdentitasLab);
            $activeWorksheet->setCellValue('R'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('S'.($index + 2), $value->kodeLab);
        
            $columns = ['Q', 'R', 'S'];
            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                
                if ($column === 'R') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        
        $activeWorksheet->getStyle('Q1:S1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('Q1:S1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('Q1:S'.(count($keyLab) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('Q:S')->getAlignment()->setWrapText(true);
        
        foreach (range('Q', 'S') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyKategoriManajemen as $index => $value) {
            $activeWorksheet->setCellValue('U'.($index + 2), $value->idKategoriManajemen);
            $activeWorksheet->setCellValue('V'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('W'.($index + 2), $value->kodeKategoriManajemen);
        
            $columns = ['U', 'V', 'W'];
            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                
                if ($column === 'V') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        
        $activeWorksheet->getStyle('U1:W1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('U1:W1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('U1:W'.(count($keyKategoriManajemen) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('U:W')->getAlignment()->setWrapText(true);
        
        foreach (range('U', 'V') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keySarana as $index => $value) {
            $activeWorksheet->setCellValue('Y'.($index + 2), $value->idIdentitasSarana);
            $activeWorksheet->setCellValue('Z'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('AA'.($index + 2), $value->kodeSarana);
        
            $columns = ['Y', 'Z', 'AA'];
            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                
                if ($column === 'Z') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        
        $activeWorksheet->getStyle('Y1:AA1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('Y1:AA1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('Y1:AA'.(count($keySarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('Y:AA')->getAlignment()->setWrapText(true);
        
        foreach (range('Y', 'Z') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keySumberDana as $index => $value) {
            $activeWorksheet->setCellValue('AC'.($index + 2), $value->idSumberDana);
            $activeWorksheet->setCellValue('AD'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('AE'.($index + 2), $value->kodeSumberDana);
        
            $columns = ['AC', 'AD', 'AE'];
            foreach ($columns as $column) {
                $alignment = $activeWorksheet->getStyle($column . ($index + 2))->getAlignment();
                
                if ($column === 'AD') {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                } else {
                    $alignment->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                }
            }
        }
        
        $activeWorksheet->getStyle('AC1:AE1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('AC1:AE1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('AC1:AE'.(count($keySumberDana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('AC:AE')->getAlignment()->setWrapText(true);
        
        $activeWorksheet->getColumnDimension('AC')->setWidth(20);
        $activeWorksheet->getColumnDimension('AD')->setWidth(30); 
        $activeWorksheet->getColumnDimension('AE')->setWidth(20); 

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');
        $headerExampleTable =  ['No.', 'Kode Rincian Aset', 'ID Identitas Lab', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
        $exampleSheet->fromArray([$headerExampleTable], NULL, 'A1');
        $exampleSheet->getStyle('A1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);    

        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);

            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeRincianLabAset);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idIdentitasLab);
            $exampleSheet->setCellValue('D'.($index + 2), $value->idKategoriManajemen);
            $exampleSheet->setCellValue('E'.($index + 2), $value->idIdentitasSarana);
            $exampleSheet->setCellValue('F'.($index + 2), $value->nomorBarang);
            $exampleSheet->setCellValue('G'.($index + 2), $value->status);
            $exampleSheet->setCellValue('H'.($index + 2), $value->idSumberDana);
            $exampleSheet->setCellValue('I'.($index + 2), $value->tahunPengadaan);
            $exampleSheet->setCellValue('J'.($index + 2), $value->hargaBeli);
            $exampleSheet->setCellValue('K'.($index + 2), $value->merk);
            $exampleSheet->setCellValue('L'.($index + 2), $value->noSeri);
            $exampleSheet->setCellValue('M'.($index + 2), $value->warna);
            $exampleSheet->setCellValue('N'.($index + 2), $spesifikasiText);
            $exampleSheet->setCellValue('O'.($index + 2), $value->bukti);
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $exampleSheet->getStyle('A1:O1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:O1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('5D9C59');
        $exampleSheet->getStyle('A1:O'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:O')->getAlignment()->setWrapText(true);
        
        foreach (range('A', 'O') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Rincian Aset Template.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function import() {
        $file = $this->request->getFile('formExcel');
        $extension = $file->getClientExtension();
        if($extension == 'xlsx' || $extension == 'xls') {
            if($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            
            $spreadsheet = $reader->load($file);
            $theFile = $spreadsheet->getActiveSheet()->toArray();

            foreach ($theFile as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                
                $kodeRincianLabAset        = $value[1] ?? null;
                $idIdentitasLab   = $value[2] ?? null;
                $idKategoriManajemen    = $value[3] ?? null;
                $idIdentitasSarana      = $value[4] ?? null;
                $nomorBarang            = $value[5] ?? null;
                $status                 = $value[6] ?? null;
                $idSumberDana           = $value[7] ?? null;
                $tahunPengadaan         = $value[8] ?? null;
                $hargaBeli              = $value[9] ?? null;
                $merk                   = $value[10] ?? null;
                $noSeri                 = $value[11] ?? null;
                $warna                  = $value[12] ?? null;
                $spesifikasi            = $value[13] ?? null;
                $bukti                  = $value[14] ?? null;
                $statusData                 = $value[15] ?? null;

                $data = [
                    'kodeRincianLabAset' => $kodeRincianLabAset,
                    'idIdentitasLab' => $idIdentitasLab,
                    'idKategoriManajemen' => $idKategoriManajemen,
                    'idIdentitasSarana' => $idIdentitasSarana,
                    'nomorBarang' => $nomorBarang,
                    'status' => $status,
                    'idSumberDana' => $idSumberDana,
                    'tahunPengadaan' => $tahunPengadaan,
                    'hargaBeli' => $hargaBeli,
                    'merk' => $merk,
                    'noSeri' => $noSeri,
                    'warna' => $warna,
                    'spesifikasi' => $spesifikasi,
                    'bukti' => $bukti,
                    'statusData' => $statusData,
                ];
                
                if ($statusData == "ERROR: empty data") {
                    return redirect()->to(site_url('rincianLabAset'))->with('error', 'Pastika semua data sudah terisi');
                } else if ($statusData == "CORRECT: fill up") {
                    $this->rincianLabAsetModel->insert($data);
                }

            }
            return redirect()->to(site_url('rincianLabAset'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('rincianLabAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/labView/rincianLabAset/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataRincianLabAset'] = $this->rincianLabAsetModel->getAll();

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
        $filename = 'Laboratorium - Rincian Aset Report.pdf';
        $dompdf->stream($filename);
    }


    public function print($id = null) {
        $dataRincianLabAset = $this->rincianLabAsetModel->find($id);
        
        if (!is_object($dataRincianLabAset)) {
            return view('error/404');
        }

        $spesifikasiMarkup = $dataRincianLabAset->spesifikasi; 
        $parsedown = new Parsedown();
        $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
        $buktiUrl = $this->generateFileId($dataRincianLabAset->bukti);

        $data = [
            'dataRincianLabAset'           => $dataRincianLabAset,
            'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
            'dataSumberDana'            => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasLab'    => $this->identitasLabModel->findAll(),
            'buktiUrl'                  => $buktiUrl,
            'spesifikasiHtml'           => $spesifikasiHtml,
        ];

        $filePath = APPPATH . 'Views/labView/rincianLabAset/printInfo.php';

        if (!file_exists($filePath)) {
            return view('error/404');
        }

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };

        $includeFile($filePath, $data);

        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $namaSarana = $data['dataRincianLabAset']->namaSarana;
        $filename = "Laboratorium - Detail Rincian $namaSarana.pdf";
        $dompdf->stream($filename);
    }

    public function dataSaranaGeneratePDF() {
        $filePath = APPPATH . 'Views/labView/rincianLabAset/printGeneral.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataSarana'] = $this->rincianLabAsetModel->getDataBySarana();

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
        $filename = 'Laboratorium - Rincian Aset General Report.pdf';
        $dompdf->stream($filename);
    }

    public function dataSaranaExport() {
        $data = $this->rincianLabAsetModel->getDataBySarana();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Nama Aset', 'Total', 'Aset Bagus','Aset Rusak', 'Aset Hilang', 'Aset Dipinjam'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
        
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->jumlahAset);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->jumlahBagus);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->jumlahRusak);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->jumlahHilang);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->jumlahDipinjam);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        
        $activeWorksheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:G1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:G'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:G')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'G') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Laboratorium - Rincian Aset General.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function exportDestroyFile() {
        $data = $this->rincianLabAsetModel->getDestroy();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Tanggal Pemusnahan',  'Nama Akun',  'Kode Akun', 'Kode Aset', 'Lokasi', 'Kategori Barang','Spesifikasi Barang', 'Status', 'Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:N1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $spesifikasiMarkup = $value->spesifikasi; 
            $parsedown = new Parsedown();
            $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
            $spesifikasiText = $this->htmlConverter($spesifikasiHtml);

            $pengadaan = $value->tahunPengadaan;
            if ($pengadaan == 0 || 0000) {
                $pengadaan = "Tidak Diketahui";
            } else {
                $pengadaan = $value->tahunPengadaan;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->tanggalPemusnahan);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaAkun);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->kodeAkun);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->kodeRincianLabAset);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaLab);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaKategoriManajemen);
            $activeWorksheet->setCellValue('H'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('I'.($index + 2), $value->status);
            $activeWorksheet->setCellValue('J'.($index + 2), $value->namaSumberDana);
            $activeWorksheet->setCellValue('K'.($index + 2), $pengadaan);
            $activeWorksheet->setCellValue('L'.($index + 2), $value->hargaBeli);
            $activeWorksheet->setCellValue('M'.($index + 2), $value->merk);
            $activeWorksheet->setCellValue('N'.($index + 2), $value->noSeri);
            $activeWorksheet->setCellValue('O'.($index + 2), $value->warna);
            $activeWorksheet->setCellValue('P'.($index + 2), $spesifikasiText);
            $linkCell = 'Q' . ($index + 2);
            $linkValue = $value->bukti; 
            $linkTitle = 'Click here'; 

            $hyperlinkFormula = '=HYPERLINK("' . $linkValue . '", "' . $linkTitle . '")';
            $activeWorksheet->setCellValue($linkCell, $hyperlinkFormula);
        
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q' ];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }

        $activeWorksheet->getStyle('A1:Q1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:Q1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:Q'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:Q')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'Q') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Aset Laboratorium -  Pemusnahan Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function dataDestroyLabGeneratePDF() {
        $filePath = APPPATH . 'Views/labView/rincianLabAset/printPemusnahaLab.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataPemusnahan'] = $this->rincianLabAsetModel->getDestroy();

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
        $filename = 'Aset Laboratorium -  Data Pemusnahan Report.pdf';
        $dompdf->stream($filename);
    }

    public function generateSelectedLabQR($selectedRows) {
        $selectedRows = explode(',', $selectedRows); 
        $dataRincianLabAset = $this->rincianLabAsetModel->getSelectedRows($selectedRows);
    
        if (empty($selectedRows)) {
            return redirect()->to('rincianLabAset')->with('error', 'No rows selected for QR code generation.');
        }
    
        $data = [
            'dataRincianLabAset' => $dataRincianLabAset,
        ];

        foreach ($data['dataRincianLabAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianLabAset);
            $data['dataRincianLabAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/labView/rincianLabAset/printQrCode.php';

        if (!file_exists($filePath)) {
            return view('error/404');
        }

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };

        $includeFile($filePath, $data);

        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = 'Laboratorium - Selected QR Code Rincian Aset .pdf';
        $dompdf->stream($filename);
    }

    
    public function generateQRCode($kodeRincianLabAset)    {
        $writer = new PngWriter();
        $qrCode = QrCode::create($kodeRincianLabAset)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
            ->setSize(300)
            ->setMargin(10)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $result = $writer->write($qrCode);

        $dataUrl = $result->getDataUri();

        return $dataUrl;
    }


    public function generateLabQRDoc() {
        $dataRincianLabAset = $this->rincianLabAsetModel->getAll();
    
        $data = [
            'dataRincianLabAset' => $dataRincianLabAset,
        ];

        foreach ($data['dataRincianLabAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianLabAset);
            $data['dataRincianLabAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/labView/rincianLabAset/printQrCode.php';

        if (!file_exists($filePath)) {
            return view('error/404');
        }
        
        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };
    
        $includeFile($filePath, $data);
    
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = 'Laboratorium - QR Code Rincian Aset.pdf';
        $dompdf->stream($filename);
    }
}