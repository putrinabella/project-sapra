<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataSiswaModels; 
use App\Models\IdentitasKelasModels; 
use App\Models\ManajemenUserModels; 
use App\Models\UserActionLogsModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Parsedown;

class DataPegawai extends ResourceController
{
    
     function __construct() {
        $this->dataSiswaModel = new DataSiswaModels();
        $this->identitasKelasModel = new IdentitasKelasModels();
        $this->manajemenUserModel = new ManajemenUserModels();
        $this->userActionLogsModel = new UserActionLogsModels();
        $this->db = \Config\Database::connect();
        helper(['pdf', 'custom']);
    }

    public function index() {
        $data['dataDataPegawai'] = $this->dataSiswaModel->getAllPegawai();
        return view('master/dataPegawaiView/index', $data);
    }

    public function show($id = null) {
        if ($id != null) {
            $dataDataPegawai = $this->dataSiswaModel->find($id);
        
            if (is_object($dataDataPegawai)) {
                $spesifikasiMarkup = $dataDataPegawai->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataDataPegawai->bukti);
                $data = [
                    'dataDataPegawai'           => $dataDataPegawai,
                    'dataIdentitasKelas'       => $this->identitasKelasModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('master/dataPegawaiView/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data = [
            'dataDataPegawai' =>  $this->dataSiswaModel->findAll(),
            'dataIdentitasKelas' => $this->identitasKelasModel->findAll(),
        ];
        
        return view('master/dataPegawaiView/new', $data);       
    }

    public function create() {
        $data = $this->request->getPost();
        $nis = $data['nis'];
        $hashedPassword = password_hash($data['nis'], PASSWORD_BCRYPT);
    
        if ($this->dataSiswaModel->isDuplicate($nis)) {
            return redirect()->to(site_url('dataPegawai'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            $this->dataSiswaModel->insert($data);
            $userData = [
                'username' => $data['nis'],
                'nama' => $data['namaSiswa'],
                'role' => 'User',
                'password' => $hashedPassword,
            ];
            $this->manajemenUserModel->insert($userData);
            return redirect()->to(site_url('dataPegawai'))->with('success', 'Data berhasil disimpan');
        }
    }
    
    public function edit($id = null) {
        if ($id != null) {
            $dataDataPegawai = $this->dataSiswaModel->find($id);
    
            if (is_object($dataDataPegawai)) {
                $data = [
                    'dataDataPegawai' => $dataDataPegawai,
                    'dataIdentitasKelas' => $this->identitasKelasModel->findAll(),
                ];
                return view('master/dataPegawaiView/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function update($id = null) {
        if ($id != null) {
            $data = $this->request->getPost();
            $nis = $data['nis'];
    
            $existingData = $this->dataSiswaModel->find($id);
            if ($existingData->nis != $nis) {
                if ($this->dataSiswaModel->isDuplicate($nis)) {
                    return redirect()->to(site_url('dataPegawai'))->with('error', 'Gagal update karena ditemukan duplikat data!');
                }
            }           
            $username = $existingData->nis;
            $idUser = $this->manajemenUserModel->getIdByUsername($username);
            $hashedPassword = password_hash($data['nis'], PASSWORD_BCRYPT);
            if ($idUser !== null) {
                $userData = [
                    'username' => $data['nis'],
                    'nama' => $data['namaSiswa'],
                    'role' => 'User',
                    'password' => $hashedPassword,
                ];
                $this->manajemenUserModel->update($idUser, $userData);
            }
            $this->dataSiswaModel->update($id, $data);

            return redirect()->to(site_url('dataPegawai'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    


    public function delete($id = null) {
        $this->dataSiswaModel->delete($id);
        activityLogs($this->userActionLogsModel, "Soft Delete", "Melakukan soft delete data Master - Data Pegawai dengan id $id");
        return redirect()->to(site_url('dataPegawai'));
    }

    public function trash() {
        $data['dataDataPegawai'] = $this->dataSiswaModel->onlyDeleted()->getRecycle();
        
        return view('master/dataPegawaiView/trash', $data);
    } 

    public function restore($id = null) {
        $affectedRows = restoreData('tblDataSiswa', 'idDataSiswa', $id, $this->userActionLogsModel, 'Master - Data Pegawai');
    
        if ($affectedRows > 0) {
            return redirect()->to(site_url('dataPegawai'))->with('success', 'Data berhasil direstore');
        }
    
        return redirect()->to(site_url('dataPegawai/trash'))->with('error', 'Tidak ada data untuk direstore');
    }
    
    public function deletePermanent($id = null) {
        if($id != null) {
            $existingData = $this->dataSiswaModel->withDeleted()->find($id);

            if ($existingData) {
                // Get the related idUser
                $username = $existingData->nis;
                $idUser = $this->manajemenUserModel->getIdByUsername($username);
    
                if ($idUser !== null) {
                    $this->manajemenUserModel->delete($idUser);
                }
                
                activityLogs($this->userActionLogsModel, "Delete", "Melakukan soft delete data Master - Data Pegawai dengan id $id");
                $this->dataSiswaModel->delete($id, true);
    
                return redirect()->to(site_url('dataPegawai/trash'))->with('success', 'Data berhasil dihapus permanen');
            } else {
                return view('error/404');
            }
        } else {
            $countInTrash = $this->dataSiswaModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                // $this->dataSiswaModel->onlyDeleted()->purgeDeleted();
                activityLogs($this->userActionLogsModel, "Delete All", "Mengosongkan tempat sampah Master - Data Pegawai");
                $this->dataSiswaModel->purgeDeletedWithUser();
                return redirect()->to(site_url('dataPegawai/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('dataPegawai/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function export() {
        $data = $this->dataSiswaModel->getAllPegawai();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Pegawai');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'NIP', 'Nama'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->nis);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSiswa);

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:C1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:C'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Master - Data Pegawai.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->dataSiswaModel->getAllPegawai();
        $keyKelas = $this->identitasKelasModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Pegawai');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'NIP', 'Nama'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:C1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:C'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'NIP', 'Nama'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->nis);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaSiswa);

            $exampleSheet->getStyle('A'.($index + 2))
                            ->getAlignment()
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C'];
            
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $exampleSheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:C1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:C'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'C') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Master - Data Pegawai Example.xlsx');
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
                $nis                    = $value[1] ?? null;
                $namaSiswa              = $value[2] ?? null;
                if ($nis === null || $nis === '') {
                    continue; 
                }
                $data = [
                    'nis'   => $nis,
                    'namaSiswa' => $namaSiswa,
                    'idIdentitasKelas'   => 0,
                ];

                if (!empty($data['nis']) && !empty($data['namaSiswa'])) {
                    $this->dataSiswaModel->insert($data);
                    $hashedPassword = password_hash($nis, PASSWORD_BCRYPT);
                    $userData = [
                        'username' => $nis,
                        'nama' => $namaSiswa,
                        'password' => $hashedPassword,
                        'role' => 'User',
                    ];
                    $this->manajemenUserModel->insert($userData);
                } else {
                    return redirect()->to(site_url('dataPegawai'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('dataPegawai'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('dataPegawai'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $dataPegawai = $this->dataSiswaModel->getAllPegawai();

        $title = "MASTER - DATA PEGAWAI";
        if (!$dataPegawai) {
            return view('error/404');
        }
    
        $data = [
            'dataPegawai' => $dataPegawai,
        ];
    
        $pdfData = pdfMasterDataPegawai($dataPegawai, $title);
    
        
        $filename = 'Master - Data Pegawai' . ".pdf";
        
        $response = $this->response;
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        $response->setBody($pdfData);
        $response->send();
    }
}