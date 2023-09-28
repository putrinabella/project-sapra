<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\SumberDanaModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SumberDana extends ResourcePresenter
{
    function __construct() {
        $this->sumberDanaModel = new SumberDanaModels();
    }
    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $data['dataSumberDana'] = $this->sumberDanaModel->findAll();
        return view('informasi/sumberDanaView/index', $data);
    }

    /**
     * Present a view to present a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Present a view to present a new single resource object
     *
     * @return mixed
     */
    public function new()
    {
        return view('informasi/sumberDanaView/new');
    }

    /**
     * Process the creation/insertion of a new resource object.
     * This should be a POST.
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->sumberDanaModel->insert($data);
        return redirect()->to(site_url('sumberDana'))->with('success', 'Data berhasil disimpan');
    }

    /**
     * Present a view to edit the properties of a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id != null) {
            $dataSumberDana = $this->sumberDanaModel->where('idSumberDana', $id)->first();
    
            if (is_object($dataSumberDana)) {
                $data['dataSumberDana'] = $dataSumberDana;
                return view('informasi/sumberDanaView/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }
    

    /**
     * Process the updating, full or partial, of a specific resource object.
     * This should be a POST.
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $data = $this->request->getPost();
        $this->sumberDanaModel->update($id, $data);
        return redirect()->to(site_url('sumberDana'))->with('success', 'Data berhasil update');
    }

    /**
     * Present a view to confirm the deletion of a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function remove($id = null)
    {
        //
    }

    /**
     * Process the deletion of a specific resource object
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->sumberDanaModel->delete($id);
        return redirect()->to(site_url('sumberDana'));
    }

    public function trash() {
        $data['dataSumberDana'] = $this->sumberDanaModel->onlyDeleted()->findAll();
        return view('informasi/sumberDanaView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblSumberDana')
                ->set('deleted_at', null, true)
                ->where(['idSumberDana' => $id])
                ->update();
        } else {
            $this->db->table('tblSumberDana')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('sumberDana'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('sumberDana/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->sumberDanaModel->delete($id, true);
        return redirect()->to(site_url('sumberDana/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->sumberDanaModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->sumberDanaModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('sumberDana/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('sumberDana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    // public function export() {
    //     $data = $this->sumberDanaModel->findAll();
    //     $spreadsheet = new Spreadsheet();
    //     $activeWorksheet = $spreadsheet->getActiveSheet();
    //     $activeWorksheet->setCellValue('A1', 'No.');
    //     $activeWorksheet->setCellValue('B1', 'ID Sumber Dana');
    //     $activeWorksheet->setCellValue('C1', 'Nama Sumber Dana');
    
    //     $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
    //     $column = 2;
    //     foreach ($data as $key => $value) {
    //         $idSumberDana = str_pad($value->idSumberDana, 3, '0', STR_PAD_LEFT); // Ensure ID is always 3 digits
    //         $activeWorksheet->setCellValue('A'.$column, ($column-1));
    //         $activeWorksheet->setCellValue('B'.$column, $idSumberDana);
    //         $activeWorksheet->setCellValue('C'.$column, $value->namaSumberDana);
    
    //         $activeWorksheet->getStyle('A'.$column)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //         $activeWorksheet->getStyle('B'.$column)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //         $activeWorksheet->getStyle('C'.$column)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

    //         $column++;
    //     }
    
    //     $activeWorksheet->getStyle('A1:C1')->getFont()->setBold(true);
    //     $activeWorksheet->getStyle('A1:C1')->getFill()
    //         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    //         ->getStartColor()->setARGB('FFFFFF00');
        
    //     $styleArray = [
    //         'borders'=> [
    //             'allBorders' => [
    //                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
    //                 'color' => ['ARGB' => 'FF000000'],
    //             ],
    //         ],
    //     ];
    
    //     $activeWorksheet->getStyle('A1:C'.($column-1))->applyFromArray($styleArray);
    
    //     $activeWorksheet->getColumnDimension('A')->setAutoSize(true);
    //     $activeWorksheet->getColumnDimension('B')->setAutoSize(true);
    //     $activeWorksheet->getColumnDimension('C')->setAutoSize(true);
    
    //     $writer = new Xlsx($spreadsheet);
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment;filename=Sumber Dana.xlsx');
    //     header('Cache-Control: max-age=0');
    //     $writer->save('php://output');
    //     exit();
    // }

    public function export() {
        $data = $this->sumberDanaModel->findAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        // Set column headers
        $headers = ['No.', 'ID Sumber Dana', 'Nama Sumber Dana'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:C1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        // Fill data
        foreach ($data as $index => $value) {
            $idSumberDana = str_pad($value->idSumberDana, 3, '0', STR_PAD_LEFT);
            $activeWorksheet->setCellValue('A'.($index + 2), $index);
            $activeWorksheet->setCellValue('B'.($index + 2), $idSumberDana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaSumberDana);
    
            $activeWorksheet->getStyle('A'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $activeWorksheet->getStyle('B'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $activeWorksheet->getStyle('C'.($index + 2))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        }
    
        // Apply styles
        $activeWorksheet->getStyle('A1:C1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:C1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $activeWorksheet->getStyle('A1:C'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:C')->getAlignment()->setWrapText(true);
    
        // Auto-size columns
        foreach (range('A', 'C') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        // Output the file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Sumber Dana.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
}
