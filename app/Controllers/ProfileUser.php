<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataSiswaModels; 
use App\Models\UserModels; 

class ProfileUser extends ResourceController
{
    
    function __construct() {
        $this->userModel = new UserModels();
        $this->dataSiswaModel = new DataSiswaModels();
        $this->db = \Config\Database::connect();
    }

    public function index() {
        $dataProfile = $this->userModel->getProfileUser(session('username'));
        $data['dataProfileUser'] = $dataProfile;
        return view('profileUser/index', $data);
    }

    public function show($id = null) {

        if ($id != null) {
            $dataProfileUser = $this->userModel->find($id);
            if (!empty($dataProfileUser)) {
                $data['dataProfileUser'] = $dataProfileUser;
                return view('profileUser/index', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }

    public function new() {
        $data['dataProfileUser'] = $this->userModel->findAll();
        
        return view('profileUser/new', $data);        
    }

    
    public function create() {
        $data = $this->request->getPost(); 
        if (!empty($data['namaProfileUser']) && !empty($data['fungsiProfileUser']) && !empty($data['linkProfileUser']) && !empty($data['picProfileUser'])) {
            $this->userModel->insert($data);
            return redirect()->to(site_url('website'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('website'))->with('error', 'Semua field harus terisi');
        }
    }

    public function edit($id = null) {
        if ($id != null) {
            $dataProfileUser = $this->userModel->find($id);
    
            if (is_object($dataProfileUser)) {
                $data = [
                    'dataProfileUser' => $dataProfileUser,
                ];
                return view('profileUser/edit', $data);
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
            if (!empty($data['namaProfileUser']) && !empty($data['fungsiProfileUser']) && !empty($data['linkProfileUser']) && !empty($data['picProfileUser'])) {
                $this->userModel->update($id, $data);
                return redirect()->to(site_url('website'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to
                (site_url('website'))->with('error', 'Semua data harus diisi');
            }
        } else {
            return view('error/404');
        }
    }

    public function delete($id = null) {
        $this->userModel->delete($id);
        return redirect()->to(site_url('website'));
    }

    public function trash() {
        $data['dataProfileUser'] = $this->userModel->onlyDeleted()->getRecycle();
        return view('profileUser/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblProfileUser')
                ->set('deleted_at', null, true)
                ->where(['idProfileUser' => $id])
                ->update();
        } else {
            $this->db->table('tblProfileUser')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('website'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('website/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->userModel->delete($id, true);
        return redirect()->to(site_url('website/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->userModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->userModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('website/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('website/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    } 
    
    public function export() {
        $data = $this->userModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('ProfileUser');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Nama', 'Fungsi', 'Link','PIC'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->namaProfileUser);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->fungsiProfileUser);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->linkProfileUser);
            $activeWorksheet->setCellValue('E'.($index + 2), $value->picProfileUser);

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $activeWorksheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:E1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:E'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Profil - ProfileUser.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    public function createTemplate() {
        $data = $this->userModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle('ProfileUser');
        $activeWorksheet->getTabColor()->setRGB('DF2E38');
    
        $headers = ['No.', 'Nama', 'Fungsi', 'Link','PIC'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), '');
            $activeWorksheet->setCellValue('C'.($index + 2), '');
            $activeWorksheet->setCellValue('D'.($index + 2), '');
            $activeWorksheet->setCellValue('E'.($index + 2), '');

            $activeWorksheet->getStyle('A'.($index + 2))
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            $columns = ['B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }           
        }
        
        $activeWorksheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $activeWorksheet->getStyle('A1:E1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:E'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            if ($column === 'A') {
                $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
            }
            $activeWorksheet->getColumnDimension($column)->setWidth(30);
        }

        $exampleSheet = $spreadsheet->createSheet();
        $exampleSheet->setTitle('Example Sheet');
        $exampleSheet->getTabColor()->setRGB('767870');

        $headers = ['No.', 'Nama', 'Fungsi', 'Link','PIC'];
        $exampleSheet->fromArray([$headers], NULL, 'A1');
        $exampleSheet->getStyle('A1:E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        foreach ($data as $index => $value) {
            if ($index >= 3) {
                break;
            }
            $exampleSheet->setCellValue('A'.($index + 2), $index + 1);
            $exampleSheet->setCellValue('B'.($index + 2), $value->namaProfileUser);
            $exampleSheet->setCellValue('C'.($index + 2), $value->fungsiProfileUser);
            $exampleSheet->setCellValue('D'.($index + 2), $value->linkProfileUser);
            $exampleSheet->setCellValue('E'.($index + 2), $value->picProfileUser);

            $exampleSheet->getStyle('A'.($index + 2))
                            ->getAlignment()
                            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $columns = ['B', 'C', 'D', 'E'];
            
            foreach ($columns as $column) {
                $exampleSheet->getStyle($column . ($index + 2))
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            }            
        }
        
        $exampleSheet->getStyle('A1:E1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('C7E8CA');
        $exampleSheet->getStyle('A1:E1')->getFont()->setBold(true);
        $exampleSheet->getStyle('A1:E'.$exampleSheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $exampleSheet->getStyle('A:E')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'E') as $column) {
            $exampleSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Profil - ProfileUser Example.xlsx');
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
                $namaProfileUser            = $value[1] ?? null;
                $fungsiProfileUser          = $value[2] ?? null;
                $linkProfileUser            = $value[3] ?? null;
                $picProfileUser             = $value[4] ?? null;

                $data = [
                    'namaProfileUser'   => $namaProfileUser,
                    'fungsiProfileUser' => $fungsiProfileUser,
                    'linkProfileUser'   => $linkProfileUser,
                    'picProfileUser'    => $picProfileUser,
                ];

                if (!empty($data['namaProfileUser']) && !empty($data['fungsiProfileUser'])
                    && !empty($data['linkProfileUser']) && !empty($data['picProfileUser'])) {
                        $this->userModel->insert($data);
                } else {
                    return redirect()->to(site_url('website'))->with('error', 'Pastikan semua data telah diisi!');
                }
            }
            return redirect()->to(site_url('website'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('website'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }


    public function generatePDF() {
        $filePath = APPPATH . 'Views/profileUser/print.php';
    
        if (!file_exists($filePath)) {
            return view('error/404');
        }

        $data['dataProfileUser'] = $this->userModel->findAll();

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
        $filename = 'Profil - ProfileUser Report.pdf';
        $dompdf->stream($filename);
    }
}