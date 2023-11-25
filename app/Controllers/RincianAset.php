<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 
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

class RincianAset extends ResourceController
{
    
    function __construct() {
        $this->rincianAsetModel = new RincianAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->db = \Config\Database::connect();
    }

    
    public function generateAndSetKodeRincianAset() {

        $dataRincianAset = $this->rincianAsetModel->getAll();

        foreach ($dataRincianAset as $data) {
            $newKodeRincianAset = $this->generateKodeRincianAset(
                $data->idKategoriManajemen,
                $data->idIdentitasPrasarana,
                $data->idSumberDana,
                $data->idIdentitasSarana,
                $data->tahunPengadaan,
                $data->nomorBarang
            );

            $this->rincianAsetModel->updateKodeRincianAset($data->idRincianAset, $newKodeRincianAset);
        }
        return redirect()->to(site_url('rincianAset'))->with('success', 'Berhasil generate kode aset');
    }

    public function generateAndSetKodeRincianItAset() {

        $dataRincianAset = $this->rincianAsetModel->getItAll();

        foreach ($dataRincianAset as $data) {
            $newKodeRincianAset = $this->generateKodeRincianAset(
                $data->idKategoriManajemen,
                $data->idIdentitasPrasarana,
                $data->idSumberDana,
                $data->idIdentitasSarana,
                $data->tahunPengadaan,
                $data->nomorBarang
            );

            $this->rincianAsetModel->updateKodeRincianAset($data->idRincianAset, $newKodeRincianAset);
        }
        return redirect()->to(site_url('dataRincianItSarana'))->with('success', 'Berhasil generate kode aset');
    }

    public function generateKodeRincianAset($idKategoriManajemen, $idIdentitasPrasarana, $idSumberDana, $idIdentitasSarana, $tahunPengadaan, $nomorBarang) {
        $kodeKategoriManajemen = $this->kategoriManajemenModel->getKodeKategoriManajemenById($idKategoriManajemen);
        $kodePrasarana = $this->identitasPrasaranaModel->getKodePrasaranaById($idIdentitasPrasarana);
        $kodeSumberDana = $this->sumberDanaModel->getKodeSumberDanaById($idSumberDana);
        $kodeSarana = $this->identitasSaranaModel->getKodeSaranaById($idIdentitasSarana);

        if ($tahunPengadaan === '0000') {
            $tahunPengadaan = 'xx';
        } else {
            $tahunPengadaan = substr($tahunPengadaan, -2);
        }

        $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);
        $kodePrasarana = substr($kodePrasarana, -2);

        $kodeRincianAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodePrasarana . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;

        return $kodeRincianAset;
    }

    public function generateKode() {
        $idKategoriManajemen = $this->request->getPost('idKategoriManajemen');
        $idIdentitasPrasarana = $this->request->getPost('idIdentitasPrasarana');
        $idSumberDana = $this->request->getPost('idSumberDana');
        $tahunPengadaan = $this->request->getPost('tahunPengadaan');
        $idIdentitasSarana = $this->request->getPost('idIdentitasSarana');
        $nomorBarang = $this->request->getPost('nomorBarang');

        $kodeKategoriManajemen = $this->kategoriManajemenModel->getKodeKategoriManajemenById($idKategoriManajemen);
        $kodePrasarana = $this->identitasPrasaranaModel->getKodePrasaranaById($idIdentitasPrasarana);
        $kodeSumberDana = $this->sumberDanaModel->getKodeSumberDanaById($idSumberDana);
        $kodeSarana = $this->identitasSaranaModel->getKodeSaranaById($idIdentitasSarana);

        if ($tahunPengadaan === '0000') {
            $tahunPengadaan = 'xx';
        } else {
            $tahunPengadaan = substr($tahunPengadaan, -2);
        }
        $kodePrasarana = substr($kodePrasarana, -2);
        $nomorBarang = str_pad($nomorBarang, 3, '0', STR_PAD_LEFT);

        
        $kodeRincianAset = 'TS-BJB ' . $kodeKategoriManajemen . ' ' . $kodePrasarana . ' ' . $kodeSumberDana . ' ' . $tahunPengadaan . ' ' . $kodeSarana . ' ' . $nomorBarang;
        echo json_encode($kodeRincianAset);
    }

    public function checkDuplicate() {
        $kodeRincianAset = $this->request->getPost('kodeRincianAset');
    
        $isDuplicate = $this->rincianAsetModel->isDuplicate($kodeRincianAset);
    
        echo json_encode(['isDuplicate' => $isDuplicate]);
    }
    

    public function index() {
        $data['dataRincianAset'] = $this->rincianAsetModel->getAll();
        return view('saranaView/rincianAset/index', $data);
    }
    
    public function dataRincianItSarana() {
        $data['dataRincianAset'] = $this->rincianAsetModel->getItAll();
        return view('saranaView/rincianAset/dataAsetIt', $data);
    }

    public function dataSarana() {
        $dataGeneral = $this->rincianAsetModel->getDataBySarana();

        $jumlahTotal = 0;
        foreach ($dataGeneral as $value) {
            $jumlahTotal += $value->jumlahAset;
        }
    
        $data['dataGeneral'] = $dataGeneral;
        $data['jumlahTotal'] = $jumlahTotal;
        
        return view('saranaView/rincianAset/dataSarana', $data);
    }

    public function dataItSarana() {
        $dataGeneral = $this->rincianAsetModel->getDataItBySarana();

        $jumlahTotal = 0;
        foreach ($dataGeneral as $value) {
            $jumlahTotal += $value->jumlahAset;
        }
    
        $data['dataGeneral'] = $dataGeneral;
        $data['jumlahTotal'] = $jumlahTotal;
        
        return view('saranaView/rincianAset/dataSaranaIt', $data);
    }

    public function pemusnahanAset() {
        $data['dataRincianAset'] = $this->rincianAsetModel->getDestroy();
        return view('saranaView/rincianAset/dataPemusnahanAset', $data);
    }

    public function pemusnahanItAset() {
        $data['dataRincianAset'] = $this->rincianAsetModel->getDestroyIt();
        return view('saranaView/rincianAset/dataPemusnahanItAset', $data);
    }

    public function pemusnahanAsetDelete($idRincianAset) {
        if ($this->request->getMethod(true) === 'POST') {
            $newSectionAset = $this->request->getPost('sectionAset');
            $namaAkun = $this->request->getPost('namaAkun'); 
            $kodeAkun = $this->request->getPost('kodeAkun'); 
    
            if ($this->rincianAsetModel->updateSectionAset($idRincianAset, $newSectionAset, $namaAkun, $kodeAkun)) {
                if ($newSectionAset === 'Dimusnahkan') {
                    return redirect()->to(site_url('rincianAset'))->with('success', 'Aset berhasil dimusnahkan');
                } elseif ($newSectionAset === 'None') {
                    return redirect()->to(site_url('rincianAset'))->with('success', 'Aset berhasil dikembalikan');
                }
            } else {
                return redirect()->to(site_url('rincianAset'))->with('error', 'Aset batal dimusnahkan');
            }
        }
    }
    public function pemusnahanItAsetDelete($idRincianAset) {
        if ($this->request->getMethod(true) === 'POST') {
            $newSectionAset = $this->request->getPost('sectionAset');
            $namaAkun = $this->request->getPost('namaAkun'); 
            $kodeAkun = $this->request->getPost('kodeAkun'); 
    
            if ($this->rincianAsetModel->updateSectionAset($idRincianAset, $newSectionAset, $namaAkun, $kodeAkun)) {
                if ($newSectionAset === 'Dimusnahkan') {
                    return redirect()->to(site_url('dataRincianItSarana'))->with('success', 'Aset berhasil dimusnahkan');
                } elseif ($newSectionAset === 'None') {
                    return redirect()->to(site_url('dataRincianItSarana'))->with('success', 'Aset berhasil dikembalikan');
                }
            } else {
                return redirect()->to(site_url('dataRincianItSarana'))->with('error', 'Aset batal dimusnahkan');
            }
        }
    }
    
    public function dataSaranaDetail($id = null) {
        $data['dataSarana'] = $this->rincianAsetModel->getDataBySaranaDetail($id);
        return view('saranaView/rincianAset/dataSaranaDetail', $data);
    }

    public function dataItSaranaDetail($id = null) {
        $data['dataSarana'] = $this->rincianAsetModel->getDataItBySaranaDetail($id);
        return view('saranaView/rincianAset/dataSaranaItDetail', $data);
    }

    public function generateQRCode($kodeRincianAset)    {
        $writer = new PngWriter();
        $qrCode = QrCode::create($kodeRincianAset)
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

    public function show($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
        
            if (is_object($dataRincianAset)) {
                $spesifikasiMarkup = $dataRincianAset->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataRincianAset->bukti);
                $qrCodeData = $this->generateQRCode($dataRincianAset->kodeRincianAset);

                $data = [
                    'dataRincianAset'           => $dataRincianAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                    'qrCodeData'                => $qrCodeData
                ];
                return view('saranaView/rincianAset/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function showIt($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
        
            if (is_object($dataRincianAset)) {
                $spesifikasiMarkup = $dataRincianAset->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataRincianAset->bukti);
                $qrCodeData = $this->generateQRCode($dataRincianAset->kodeRincianAset);

                $data = [
                    'dataRincianAset'           => $dataRincianAset,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                    'qrCodeData'                => $qrCodeData
                ];
                return view('saranaView/rincianAset/showIt', $data);
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
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
        ];
        
        return view('saranaView/rincianAset/new', $data);        
    }
    
    public function newIt() {
        $data = [
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAsetIT(),
            'dataSumberDana' => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
        ];
        
        return view('saranaView/rincianAset/newIt', $data);        
    }
    
    public function create() {
        $data = $this->request->getPost();
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['idIdentitasPrasarana'])) {
            
            // $insertedID = $this->rincianAsetModel->insert($data);
            // $this->rincianAsetModel->setKodeAset($insertedID);
            $this->rincianAsetModel->insert($data);
            
            return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('rincianAset'))->with('error', 'Semua field harus terisi');
        }
    }

    public function createIt() {
        $data = $this->request->getPost();
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['idIdentitasPrasarana'])) {
            
            // $insertedID = $this->rincianAsetModel->insert($data);
            // $this->rincianAsetModel->setKodeAset($insertedID);
            $this->rincianAsetModel->insert($data);
            
            return redirect()->to(site_url('dataRincianItSarana'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('dataRincianItSarana'))->with('error', 'Semua field harus terisi');
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
                $this->rincianAsetModel->update($id, $data);
                return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    
    public function edit($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
    
            if (is_object($dataRincianAset)) {
                $data = [
                    'dataRincianAset' => $dataRincianAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                ];
                return view('saranaView/rincianAset/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }
    public function updateIt($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
                $this->rincianAsetModel->update($id, $data);
                return redirect()->to(site_url('dataRincianItSarana'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    
    public function editIt($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
    
            if (is_object($dataRincianAset)) {
                $data = [
                    'dataRincianAset' => $dataRincianAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                ];
                return view('saranaView/rincianAset/editIt', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function editPemusnahan($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
    
            if (is_object($dataRincianAset)) {
                $data = [
                    'dataRincianAset' => $dataRincianAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                ];
                return view('saranaView/rincianAset/editPemusnahanIt', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }
    public function editPemusnahanIt($id = null) {
        if ($id != null) {
            $dataRincianAset = $this->rincianAsetModel->find($id);
    
            if (is_object($dataRincianAset)) {
                $data = [
                    'dataRincianAset' => $dataRincianAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAsetIT(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                ];
                return view('saranaView/rincianAset/editPemusnahanIt', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function updatePemusnahan($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
            $this->rincianAsetModel->update($id, $data);
            return redirect()->to(site_url('pemusnahanAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    public function updateItPemusnahan($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
            $this->rincianAsetModel->update($id, $data);
            return redirect()->to(site_url('pemusnahanItAset'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    

    public function delete($id = null) {
        $this->rincianAsetModel->delete($id);
        return redirect()->to(site_url('rincianAset'));
    }

    public function deleteIt($id = null) {
        $this->rincianAsetModel->delete($id);
        return redirect()->to(site_url('dataRincianItSarana'));
    }

    public function trash() {
        $data['dataRincianAset'] = $this->rincianAsetModel->onlyDeleted()->getRecycle();
        return view('saranaView/rincianAset/trash', $data);
    } 

    public function trashIt() {
        $data['dataRincianAset'] = $this->rincianAsetModel->onlyDeleted()->getItRecycle();
        return view('saranaView/rincianAset/trashIt', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblRincianAset')
                ->set('deleted_at', null, true)
                ->where(['idRincianAset' => $id])
                ->update();
        } else {
            $this->db->table('tblRincianAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('rincianAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->rincianAsetModel->delete($id, true);
        return redirect()->to(site_url('rincianAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->rincianAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->rincianAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('rincianAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('rincianAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    public function restoreIt($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblRincianAset')
                ->set('deleted_at', null, true)
                ->where(['idRincianAset' => $id])
                ->update();
        } else {
            $this->db->table('tblRincianAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('dataRincianItSarana'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('dataItSarana/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanentIt($id = null) {
        if($id != null) {
        $this->rincianAsetModel->delete($id, true);
        return redirect()->to(site_url('dataItSarana/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->rincianAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->rincianAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('dataItSarana/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('dataItSarana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 

    private function htmlConverter($html) {
        $plainText = strip_tags(str_replace('<br />', "\n", $html));
        $plainText = preg_replace('/\n+/', "\n", $plainText);
        return $plainText;  
    }
    
    public function export() {
        $data = $this->rincianAsetModel->getAll();
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
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
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
        
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'N'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        $activeWorksheet->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $activeWorksheet->getStyle('M')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        
        $activeWorksheet->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:N1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:N'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:N')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'N') as $column) {
            if ($column === 'K') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'L') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }

        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Sarana - Rincian Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function exportIt() {
        $data = $this->rincianAsetModel->getItAll();
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
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
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
        
            
            $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I' ,'J', 'K', 'L', 'N'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        $activeWorksheet->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $activeWorksheet->getStyle('M')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        
        $activeWorksheet->getStyle('A1:N1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:N1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:N'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:N')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'N') as $column) {
            if ($column === 'K') {
                $activeWorksheet->getColumnDimension($column)->setWidth(20);
            } else if ($column === 'L') {
                $activeWorksheet->getColumnDimension($column)->setWidth(40); 
            } else {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }

        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Perangkat IT - Rincian Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->rincianAsetModel->getAll();
        $keyPrasarana = $this->identitasPrasaranaModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $keyKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $keySarana = $this->identitasSaranaModel->findAll();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode Rincian Aset', 'ID Identitas Prasarana', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status (Bagus, Rusak, Hilang)', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerPrasaranaID = ['ID Identitas Prasarana', 'Nama Prasarana', 'Kode'];
        $activeWorksheet->fromArray([$headerPrasaranaID], NULL, 'Q1');
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

            $generateID = '=CONCAT("TS-BJB ", IFERROR(INDEX($W$2:$W$' . (count($keyKategoriManajemen) + 1) . ', MATCH(D' . ($index + 2) . ', $U$2:$U$' . (count($keyKategoriManajemen) + 1) . ', 0)), D' . ($index + 2) . '), " ", IFERROR(INDEX($S$2:$S$' . (count($keyPrasarana) + 1) . ', MATCH(C' . ($index + 2) . ', $Q$2:$Q$' . (count($keyPrasarana) + 1) . ', 0)), C' . ($index + 2) . '), " ", IFERROR(INDEX($AE$2:$AE$' . (count($keySumberDana) + 1) . ', MATCH(H' . ($index + 2) . ', $AC$2:$AC$' . (count($keySumberDana) + 1) . ', 0)), H' . ($index + 2) . '), " ", IF(I' . ($index + 2) . ' = 0, "XX", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2)), " ", IFERROR(INDEX($AA$2:$AA$' . (count($keySarana) + 1) . ', MATCH(E' . ($index + 2) . ', $Y$2:$Y$' . (count($keySarana) + 1) . ', 0)), E' . ($index + 2) . '), " ", TEXT(F' . ($index + 2) . ', "000"))';
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
                
        foreach ($keyPrasarana as $index => $value) {
            $activeWorksheet->setCellValue('Q'.($index + 2), $value->idIdentitasPrasarana);
            $activeWorksheet->setCellValue('R'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('S'.($index + 2), $value->kodePrasarana);
        
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
        $activeWorksheet->getStyle('Q1:S'.(count($keyPrasarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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
        $headerExampleTable =  ['No.', 'Kode Rincian Aset', 'ID Identitas Prasarana', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
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
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idIdentitasPrasarana);
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
        header('Content-Disposition: attachment;filename=Rincian Aset Example.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function createItTemplate() {
        $data = $this->rincianAsetModel->getItAll();
        $keyPrasarana = $this->identitasPrasaranaModel->findAll();
        $keySumberDana = $this->sumberDanaModel->findAll();
        $keyKategoriManajemen = $this->kategoriManajemenModel->findAll();
        $keySarana = $this->identitasSaranaModel->findAsetIT();
        $spreadsheet = new Spreadsheet();
        
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
        
        $headerInputTable = ['No.', 'Kode Rincian Aset', 'ID Identitas Prasarana', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status (Bagus, Rusak, Hilang)', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
        $activeWorksheet->fromArray([$headerInputTable], NULL, 'A1');
        $activeWorksheet->getStyle('A1:O1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerPrasaranaID = ['ID Identitas Prasarana', 'Nama Prasarana', 'Kode'];
        $activeWorksheet->fromArray([$headerPrasaranaID], NULL, 'Q1');
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
            $ruanganKode        = '=IFERROR(INDEX($S$2:$S$' . (count($keyPrasarana) + 1) . ', MATCH(C' . ($index + 2) . ', $Q$2:$Q$' . (count($keyPrasarana) + 1) . ', 0)), C' . ($index + 2) . ')';
            $sumberDanaKode     = '=IFERROR(INDEX($AE$2:$AE$' . (count($keySumberDana) + 1) . ', MATCH(H' . ($index + 2) . ', $AC$2:$AC$' . (count($keySumberDana) + 1) . ', 0)), H' . ($index + 2) . ')';
            $tahunKode          = '=IF(I' . ($index + 2) . ' = 0, "XX", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2))';
            $spesifikasiKode    = '=IFERROR(INDEX($AA$2:$AA$' . (count($keySarana) + 1) . ', MATCH(E' . ($index + 2) . ', $Y$2:$Y$' . (count($keySarana) + 1) . ', 0)), E' . ($index + 2) . ')';
            $nomorBarangKode    = '=TEXT(F' . ($index + 2) . ', "000")';

            $generateID = '=CONCAT("TS-BJB ", IFERROR(INDEX($W$2:$W$' . (count($keyKategoriManajemen) + 1) . ', MATCH(D' . ($index + 2) . ', $U$2:$U$' . (count($keyKategoriManajemen) + 1) . ', 0)), D' . ($index + 2) . '), " ", IFERROR(INDEX($S$2:$S$' . (count($keyPrasarana) + 1) . ', MATCH(C' . ($index + 2) . ', $Q$2:$Q$' . (count($keyPrasarana) + 1) . ', 0)), C' . ($index + 2) . '), " ", IFERROR(INDEX($AE$2:$AE$' . (count($keySumberDana) + 1) . ', MATCH(H' . ($index + 2) . ', $AC$2:$AC$' . (count($keySumberDana) + 1) . ', 0)), H' . ($index + 2) . '), " ", IF(I' . ($index + 2) . ' = 0, "XX", RIGHT(TEXT(I' . ($index + 2) . ', "0000"), 2)), " ", IFERROR(INDEX($AA$2:$AA$' . (count($keySarana) + 1) . ', MATCH(E' . ($index + 2) . ', $Y$2:$Y$' . (count($keySarana) + 1) . ', 0)), E' . ($index + 2) . '), " ", TEXT(F' . ($index + 2) . ', "000"))';
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
                
        foreach ($keyPrasarana as $index => $value) {
            $activeWorksheet->setCellValue('Q'.($index + 2), $value->idIdentitasPrasarana);
            $activeWorksheet->setCellValue('R'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('S'.($index + 2), $value->kodePrasarana);
        
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
        $activeWorksheet->getStyle('Q1:S'.(count($keyPrasarana) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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
        $headerExampleTable =  ['No.', 'Kode Rincian Aset', 'ID Identitas Prasarana', 'ID Kategori Manajemen','ID Identitas Sarana', 'Nomor Barang', 'Status', 'ID Sumber Dana', 'Tahun Pengadaan', 'Harga Beli', 'Merek' , 'Nomor Seri', 'Warna', 'Spesifikasi', 'Bukti (GDRIVE LINK)'];
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
            $exampleSheet->setCellValue('B'.($index + 2), $value->kodeRincianAset);
            $exampleSheet->setCellValue('C'.($index + 2), $value->idIdentitasPrasarana);
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
        header('Content-Disposition: attachment;filename=Aset Perangkat IT Example.xlsx');
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
                
                $kodeRincianAset        = $value[1] ?? null;
                $idIdentitasPrasarana   = $value[2] ?? null;
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
                    'kodeRincianAset' => $kodeRincianAset,
                    'idIdentitasPrasarana' => $idIdentitasPrasarana,
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
                    return redirect()->to(site_url('rincianAset'))->with('error', 'Pastika semua data sudah terisi');
                } else if ($statusData == "CORRECT: fill up") {
                    $this->rincianAsetModel->insert($data);
                    return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil diimport');
                }
            }
            return redirect()->to(site_url('rincianAset'))->with('error', 'Pastikan excel yang dimasukan sudah sesuai!');
        } else {
            return redirect()->to(site_url('rincianAset'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }
    public function importIt() {
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
                
                $kodeRincianAset        = $value[1] ?? null;
                $idIdentitasPrasarana   = $value[2] ?? null;
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
                    'kodeRincianAset' => $kodeRincianAset,
                    'idIdentitasPrasarana' => $idIdentitasPrasarana,
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
                    return redirect()->to(site_url('dataRincianItSarana'))->with('error', 'Pastika semua data sudah terisi');
                } else if ($statusData == "CORRECT: fill up") {
                    $this->rincianAsetModel->insert($data);
                }

            }
            return redirect()->to(site_url('dataRincianItSarana'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('dataRincianItSarana'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generateSelectedItQR($selectedRows) {
        $selectedRows = explode(',', $selectedRows); 
        $dataRincianAset = $this->rincianAsetModel->getSelectedRows($selectedRows);
    
        if (empty($selectedRows)) {
            return redirect()->to('rincianAset')->with('error', 'No rows selected for QR code generation.');
        }
    
        $data = [
            'dataRincianAset' => $dataRincianAset,
        ];

        foreach ($data['dataRincianAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianAset);
            $data['dataRincianAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/saranaView/rincianAset/printQrCode.php';

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
        $filename = 'Sarana - Selected QR Code Rincian Aset .pdf';
        $dompdf->stream($filename);
    }

    public function generateSelectedQR($selectedRows) {
        $selectedRows = explode(',', $selectedRows); 
        $dataRincianAset = $this->rincianAsetModel->getSelectedRows($selectedRows);
    
        if (empty($selectedRows)) {
            return redirect()->to('rincianAset')->with('error', 'No rows selected for QR code generation.');
        }
    
        $data = [
            'dataRincianAset' => $dataRincianAset,
        ];

        foreach ($data['dataRincianAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianAset);
            $data['dataRincianAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/saranaView/rincianAset/printQrCode.php';

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
        $filename = 'Sarana - Selected QR Code Rincian Aset .pdf';
        $dompdf->stream($filename);
    }


    public function generateQRDoc() {
        $dataRincianAset = $this->rincianAsetModel->getAll();
    
        $data = [
            'dataRincianAset' => $dataRincianAset,
        ];

        foreach ($data['dataRincianAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianAset);
            $data['dataRincianAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/saranaView/rincianAset/printQrCode.php';

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
        $filename = 'Sarana - QR Code Rincian Aset.pdf';
        $dompdf->stream($filename);
    }

    public function generateItQRDoc() {
        $dataRincianAset = $this->rincianAsetModel->getItAll();
    
        $data = [
            'dataRincianAset' => $dataRincianAset,
        ];

        foreach ($data['dataRincianAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianAset);
            $data['dataRincianAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/saranaView/rincianAset/printQrCode.php';

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
        $filename = 'Sarana - QR Code Rincian Aset.pdf';
        $dompdf->stream($filename);
    }

    public function generatePDF() {
        $dataRincianAset = $this->rincianAsetModel->getAll();
    
        $data = [
            'dataRincianAset' => $dataRincianAset,
        ];

        foreach ($data['dataRincianAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianAset);
            $data['dataRincianAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/saranaView/rincianAset/print.php';

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
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $filename = 'Sarana - Rincian Aset Report.pdf';
        $dompdf->stream($filename);
    }

    public function generateItPDF() {
        $dataRincianAset = $this->rincianAsetModel->getItAll();
    
        $data = [
            'dataRincianAset' => $dataRincianAset,
        ];

        foreach ($data['dataRincianAset'] as $key => $value) {
            $qrCode = $this->generateQRCode($value->kodeRincianAset);
            $data['dataRincianAset'][$key]->qrCodeData = $qrCode;
        }

        $filePath = APPPATH . 'Views/saranaView/rincianAset/print.php';

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
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $filename = 'Sarana - Rincian Aset Report.pdf';
        $dompdf->stream($filename);
    }


    public function print($id = null) {
        $dataRincianAset = $this->rincianAsetModel->find($id);
        
        if (!is_object($dataRincianAset)) {
            return view('error/404');
        }

        $spesifikasiMarkup = $dataRincianAset->spesifikasi; 
        $parsedown = new Parsedown();
        $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
        $buktiUrl = $this->generateFileId($dataRincianAset->bukti);
        $qrCodeData = $this->generateQRCode($dataRincianAset->kodeRincianAset);
        // print_r($qrCodeData);
        // die;
        $data = [
            'dataRincianAset' => $dataRincianAset,
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataSumberDana' => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
            'buktiUrl' => $buktiUrl,
            'spesifikasiHtml' => $spesifikasiHtml,
            'qrCodeData' => $qrCodeData, 
        ];

        $filePath = APPPATH . 'Views/saranaView/rincianAset/printInfo.php';

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
        $filename = 'Sarana - Detail Rincian Aset.pdf';
        $namaSarana = $data['dataRincianAset']->namaSarana;
        $filename = "Sarana - Detail Rincian Aset $namaSarana.pdf";
        $dompdf->stream($filename);
    }

    public function dataSaranaGeneratePDF() {
        $filePath = APPPATH . 'Views/saranaView/rincianAset/printGeneral.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataSarana'] = $this->rincianAsetModel->getDataBySarana();

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
        $filename = 'Sarana - Rincian Aset General Report.pdf';
        $dompdf->stream($filename);
    }

    public function dataItSaranaGeneratePDF() {
        $filePath = APPPATH . 'Views/saranaView/rincianAset/printGeneral.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataSarana'] = $this->rincianAsetModel->getDataItBySarana();

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
        $filename = 'Perangkat IT - Rincian Aset General Report.pdf';
        $dompdf->stream($filename);
    }

    public function dataSaranaExport() {
        $data = $this->rincianAsetModel->getDataBySarana();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Nama Aset', 'Total', 'Aset Bagus','Aset Rusak', 'Aset Hilang'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
        
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->jumlahAset);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->jumlahBagus);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->jumlahRusak);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->jumlahHilang);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'F') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Sarana - Rincian Aset General.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function dataItSaranaExport() {
        $data = $this->rincianAsetModel->getDataItBySarana();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Rincian Aset');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Nama Aset', 'Total', 'Aset Bagus','Aset Rusak', 'Aset Hilang'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
        
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaSarana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->jumlahAset);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->jumlahBagus);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->jumlahRusak);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->jumlahHilang);

            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
        
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'F') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Perangkat IT - Rincian Aset General.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }

    public function exportDestroyFile() {
        $data = $this->rincianAsetModel->getDestroy();
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
            $activeWorksheet->setCellValue('E'.($index + 2), $value->kodeRincianAset);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaPrasarana);
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
        header('Content-Disposition: attachment;filename=Sarana - Pemusnahan Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function dataDestroyaGeneratePDF() {
        $filePath = APPPATH . 'Views/saranaView/rincianAset/printPemusnahan.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataPemusnahan'] = $this->rincianAsetModel->getDestroy();

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
        $filename = 'Sarana - Data Pemusnahan Report.pdf';
        $dompdf->stream($filename);
    }

    public function exportDestroyItFile() {
        $data = $this->rincianAsetModel->getDestroyIt();
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
            $activeWorksheet->setCellValue('E'.($index + 2), $value->kodeRincianAset);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaPrasarana);
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
        header('Content-Disposition: attachment;filename=Perangkat IT - Pemusnahan Aset.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function dataDestroyaGenerateItPDF() {
        $filePath = APPPATH . 'Views/saranaView/rincianAset/printPemusnahan.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataPemusnahan'] = $this->rincianAsetModel->getDestroyIt();

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
        $filename = 'Perangkat IT - Data Pemusnahan Report.pdf';
        $dompdf->stream($filename);
    }
}
