<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataSiswaModels; 
use App\Models\IdentitasKelasModels; 
use App\Models\ManajemenUserModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class DataSiswa extends ResourceController
{
    
     function __construct() {
        $this->dataSiswaModel = new DataSiswaModels();
        $this->identitasKelasModel = new IdentitasKelasModels();
        $this->manajemenUserModel = new ManajemenUserModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataDataSiswa'] = $this->dataSiswaModel->getAll();
        return view('master/dataSiswaView/index', $data);
    }

    public function show($id = null) {
        if ($id != null) {
            $dataDataSiswa = $this->dataSiswaModel->find($id);
        
            if (is_object($dataDataSiswa)) {
                $spesifikasiMarkup = $dataDataSiswa->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataDataSiswa->bukti);
                $data = [
                    'dataDataSiswa'           => $dataDataSiswa,
                    'dataIdentitasKelas'       => $this->identitasKelasModel->getAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('master/dataSiswaView/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data = [
            'dataDataSiswa' =>  $this->dataSiswaModel->findAll(),
            'dataIdentitasKelas' => $this->identitasKelasModel->getAll(),
        ];
        
        return view('master/dataSiswaView/new', $data);       
    }

    public function create() {
        $data = $this->request->getPost();
        $nis = $data['nis'];
        $hashedPassword = password_hash($data['nis'], PASSWORD_BCRYPT);
    
        if ($this->dataSiswaModel->isDuplicate($nis)) {
            return redirect()->to(site_url('dataSiswa'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            $this->dataSiswaModel->insert($data);
            $userData = [
                'username' => $data['nis'],
                'nama' => $data['namaSiswa'],
                'role' => 'User',
                'password' => $hashedPassword,
            ];
            $this->manajemenUserModel->insert($userData);
            return redirect()->to(site_url('dataSiswa'))->with('success', 'Data berhasil disimpan');
        }
    }
    
    public function edit($id = null) {
        if ($id != null) {
            $dataDataSiswa = $this->dataSiswaModel->find($id);
    
            if (is_object($dataDataSiswa)) {
                $data = [
                    'dataDataSiswa' => $dataDataSiswa,
                    'dataIdentitasKelas' => $this->identitasKelasModel->getAll(),
                ];
                return view('master/dataSiswaView/edit', $data);
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
                    return redirect()->to(site_url('dataSiswa'))->with('error', 'Gagal update karena ditemukan duplikat data!');
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

            return redirect()->to(site_url('dataSiswa'))->with('success', 'Data berhasil diupdate');
        } else {
            return view('error/404');
        }
    }
    


    public function delete($id = null) {
        $this->dataSiswaModel->delete($id);
        return redirect()->to(site_url('dataSiswa'));
    }

    public function trash() {
        $data['dataDataSiswa'] = $this->dataSiswaModel->onlyDeleted()->getRecycle();
        
        return view('master/dataSiswaView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblDataSiswa')
                ->set('deleted_at', null, true)
                ->where(['idDataSiswa' => $id])
                ->update();
        } else {
            $this->db->table('tblDataSiswa')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('dataSiswa'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('dataSiswa/trash'))->with('error', 'Tidak ada data untuk direstore');
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
    
                $this->dataSiswaModel->delete($id, true);
    
                return redirect()->to(site_url('dataSiswa/trash'))->with('success', 'Data berhasil dihapus permanen');
            } else {
                return view('error/404');
            }
        } else {
            $countInTrash = $this->dataSiswaModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                // $this->dataSiswaModel->onlyDeleted()->purgeDeleted();
                $this->dataSiswaModel->purgeDeletedWithUser();
                return redirect()->to(site_url('dataSiswa/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('dataSiswa/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function export() {
        $data = $this->dataSiswaModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('DataSiswa');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'NIS', 'Nama', 'Kelas'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->nis);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSiswa);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->namaKelas);

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C', 'D'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:D1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:D'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Profil - Data Siswa.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->dataSiswaModel->getAll();
        $keyKelas = $this->identitasKelasModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Data Siswa');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'NIS', 'Nama', 'Id Identitas Kelas'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        $headerKelasID = ['ID Identitas Kelas', 'Nama Kelas'];
        $activeWorksheet->fromArray([$headerKelasID], NULL, 'F1');
        $activeWorksheet->getStyle('F1:G1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C', 'D'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:D1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:D'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }

        foreach ($keyKelas as $index => $value) {
            $activeWorksheet->setCellValue('F'.($index + 2), $value->idIdentitasKelas);
            $activeWorksheet->setCellValue('G'.($index + 2), $value->namaKelas);
    
            $columns = ['F', 'G'];
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                    ->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }    
        }

        $activeWorksheet->getStyle('F1:G1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('F1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('F1:G'.(count($keyKelas) + 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('F:G')->getAlignment()->setWrapText(true);
    
        foreach (range('F', 'G') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }


        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'NIS', 'Nama', 'ID Identitas Kelas'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:D1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->nis);
            $exampleSheet->setCellValue('C'.($index + 2), $value->namaSiswa);
            $exampleSheet->setCellValue('D'.($index + 2), $value->idIdentitasKelas);

            $exampleSheet->getStyle('A'.($index + 2))
                            ->getAlignment()
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C', 'D'];
            
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $exampleSheet->getStyle('A1:D1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:D1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:D'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:D')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'D') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Profil - Data Siswa Example.xlsx');
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
                $idIdentitasKelas       = $value[3] ?? null;
                if ($nis === null || $nis === '') {
                    continue; 
                }
                $data = [
                    'nis'   => $nis,
                    'namaSiswa' => $namaSiswa,
                    'idIdentitasKelas'   => $idIdentitasKelas,
                ];

                if (!empty($data['nis']) && !empty($data['namaSiswa'])
                && !empty($data['idIdentitasKelas'])) {
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
                    return redirect()->to(site_url('dataSiswa'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('dataSiswa'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('dataSiswa'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/dataSiswaView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataDataSiswa'] = $this->dataSiswaModel->getAll();

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
        $filename = 'Profil - DataSiswa Report.pdf';
        $dompdf->stream($filename);
    }
}