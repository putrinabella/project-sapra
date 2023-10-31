<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ManajemenUserModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use Parsedown;

class ManajemenUser extends ResourceController
{
    
    function __construct() {
        $this->manajemenUserModel = new ManajemenUserModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $data['dataManajemenUser'] = $this->manajemenUserModel->findAll();
        return view('master/manajemenUserView/index', $data);
    }

    public function show($id = null) {
        if ($id != null) {
            $dataManajemenUser = $this->manajemenUserModel->find($id);
        
            if (is_object($dataManajemenUser)) {
                $spesifikasiMarkup = $dataManajemenUser->spesifikasi;
                $parsedown = new Parsedown();
                $spesifikasiHtml = $parsedown->text($spesifikasiMarkup);
                $spesifikasiText = $this->htmlConverter($spesifikasiHtml);
                
                $buktiUrl = $this->generateFileId($dataManajemenUser->bukti);
                $data = [
                    'dataManajemenUser'           => $dataManajemenUser,
                    'dataIdentitasSarana'       => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana'            => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen'     => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana'    => $this->identitasPrasaranaModel->findAll(),
                    'buktiUrl'                  => $buktiUrl,
                    'spesifikasiHtml'           => $spesifikasiHtml,
                ];
                return view('master/manajemenUserView/show', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data['dataManajemenUser'] = $this->manajemenUserModel->findAll();
        
        return view('master/manajemenUserView/new', $data);        
    }

    
    // public function create() {
    //     $data = $this->request->getPost(); 
    //     if (!empty($data['password'])) {
    //         $this->manajemenUserModel->insert($data);
    //         return redirect()->to(site_url('manajemenUser'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('manajemenUser'))->with('error', 'Semua field harus terisi');
    //     }
    // }

    public function create() {
        $data = $this->request->getPost();
        $username = $data['username'];
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        if ($this->manajemenUserModel->isDuplicate($username)) {
            return redirect()->to(site_url('manajemenUser'))->with('error', 'Ditemukan duplikat data! Masukkan data yang berbeda.');
        } else {
            $data = [
                'username' => $data['username'],
                'nama' => $data['nama'],
                'role' => $data['role'],
                'password' => $hashedPassword,
            ];

            $this->manajemenUserModel->insert($data);
            return redirect()->to(site_url('manajemenUser'))->with('success', 'Data berhasil disimpan');
        }
    }
    

    public function edit($id = null) {
        if ($id != null) {
            $dataManajemenUser = $this->manajemenUserModel->find($id);
    
            if (is_object($dataManajemenUser)) {
                $data = [
                    'dataManajemenUser' => $dataManajemenUser,
                ];
                return view('master/manajemenUserView/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function update($id = null) {
        if ($id !== null) {
            $data = $this->request->getPost();
            $username = $data['username'];

            if (isset($data['password']) && isset($data['username']) && isset($data['nama']) && isset($data['role'])) {
                $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
                if ($this->manajemenUserModel->isDuplicate($username)) {
                    return redirect()->to(site_url('manajemenUser'))->with('error', 'Gagal update karena ditemukan duplikat data!');
                } else {
                    $updateData = [
                        'username' => $data['username'],
                        'nama' => $data['nama'],
                        'role' => $data['role'],
                        'password' => $hashedPassword,
                    ];
        
                    $this->manajemenUserModel->update($id, $updateData);
                    return redirect()->to(site_url('manajemenUser'))->with('success', 'Data berhasil diupdate');
                }                
                return redirect()->to(site_url('manajemenUser'))->with('success', 'Data berhasil diperbarui');
            } else {
                return redirect()->to(site_url('manajemenUser'))->with('error', 'Silahkan isi semua kolom!');
            }

            
        } else {
            return view('error/404');
        }
    }
    
    public function delete($id = null) {
        $this->manajemenUserModel->delete($id);
        return redirect()->to(site_url('manajemenUser'));
    }

    public function trash() {
        $data['dataManajemenUser'] = $this->manajemenUserModel->onlyDeleted()->getRecycle();
        return view('master/manajemenUserView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblManajemenUser')
                ->set('deleted_at', null, true)
                ->where(['idUser' => $id])
                ->update();
        } else {
            $this->db->table('tblManajemenUser')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('manajemenUser'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('manajemenUserView/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->manajemenUserModel->delete($id, true);
        return redirect()->to(site_url('manajemenUserView/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->manajemenUserModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->manajemenUserModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('manajemenUserView/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('manajemenUserView/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function export() {
        $data = $this->manajemenUserModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('ManajemenUser');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Nama ManajemenUser',  'PIC'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaManajemenUser);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->picManajemenUser);

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
        header('Content-Disposition: attachment;filename=Profil - ManajemenUser.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->manajemenUserModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('Input Sheet');
        $activeWorksheet->getTabColor()->setRGB('ED1C24');
    
        $headers = ['No.', 'Nama ManajemenUser',  'PIC'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
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
            if ($column === 'A') {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
            $activeWorksheet->getColumnDimension($column)->setWidth(30);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'Nama ManajemenUser', 'PIC'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->namaManajemenUser);
            $exampleSheet->setCellValue('C'.($index + 2), $value->picManajemenUser);

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
        header('Content-Disposition: attachment;filename=Profil - ManajemenUser Example.xlsx');
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
                $namaManajemenUser            = $value[1] ?? null;
                $picManajemenUser             = $value[2] ?? null;

                $data = [
                    'namaManajemenUser'       => $namaManajemenUser,
                    'picManajemenUser'        => $picManajemenUser,

                ];

                if (!empty($data['namaManajemenUser']) && !empty($data['picManajemenUser'])) {
                        $this->manajemenUserModel->insert($data);
                } else {
                    return redirect()->to(site_url('manajemenUser'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('manajemenUser'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('manajemenUser'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/master/manajemenUserView/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataManajemenUser'] = $this->manajemenUserModel->findAll();

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
        $filename = 'Profil - ManajemenUser Report.pdf';
        $dompdf->stream($filename);
    }
}