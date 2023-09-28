<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\IdentitasGedungModels; 
use App\Models\IdentitasLantaiModels; 
use App\Models\IdentitasPrasaranaModels; 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class IdentitasPrasarana extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

     function __construct() {
        $this->identitasGedungModel = new IdentitasGedungModels();
        $this->identitasLantaiModel = new IdentitasLantaiModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->db = \Config\Database::connect();
    }

    // DATATABLES
    public function index()
    {
        $data['dataIdentitasPrasarana'] = $this->identitasPrasaranaModel->getAll();
        return view('informasi/identitasPrasaranaView/index', $data);
    }

    // Manual Pagination
    // public function index()
    // {
    //     $data = $this->identitasPrasaranaModel->getPaginated(10);
    //     return view('informasi/identitasPrasaranaView/index', $data);
    // }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        $data = [
            'dataIdentitasGedung' => $this->identitasGedungModel->findAll(),
            'dataIdentitasLantai' => $this->identitasLantaiModel->findAll(),
        ];
        
        return view('informasi/identitasPrasaranaView/new', $data);        
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */

    public function create() {
        $data = $this->request->getPost();
        if (!empty($data['idIdentitasGedung']) && !empty($data['idIdentitasLantai'])) {
            unset($data['idIdentitasPrasarana']);
            $this->identitasPrasaranaModel->insert($data);
            $query = "UPDATE tblIdentitasPrasarana SET kodePrasarana = CONCAT('P', LPAD(idIdentitasPrasarana, 3, '0'), '/G', LPAD(idIdentitasGedung, 3, '0'), '/L', LPAD(idIdentitasLantai, 3, '0'))";
            $this->db->query($query);
            return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('identitasPrasarana'))->with('error', 'Id Gedung dan Lantai harus diisi.');
        }
    }


    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id != null) {
            $dataIdentitasPrasarana = $this->identitasPrasaranaModel->find($id);
    
            if (is_object($dataIdentitasPrasarana)) {
                $data = [
                    'dataIdentitasPrasarana' => $dataIdentitasPrasarana,
                    'dataIdentitasGedung' => $this->identitasGedungModel->findAll(),
                    'dataIdentitasLantai' => $this->identitasLantaiModel->findAll(),
                ];
                return view('informasi/identitasPrasaranaView/edit', $data);
            } else {
                return view('error/404');
            }
        } else {
            return view('error/404');
        }
    }
    

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */

    public function update($id = null)
    {
        if ($id != null) {
            $data = $this->request->getPost();

            // Check if idIdentitasGedung and idIdentitasLantai exist and are not empty
            if (!empty($data['idIdentitasGedung']) && !empty($data['idIdentitasLantai'])) {
                $this->identitasPrasaranaModel->update($id, $data);

                // Update kodePrasarana using an SQL query
                $query = "UPDATE tblIdentitasPrasarana SET kodePrasarana = CONCAT('P', LPAD(idIdentitasPrasarana, 3, '0'), '/G', LPAD(idIdentitasGedung, 3, '0'), '/L', LPAD(idIdentitasLantai, 3, '0')) WHERE idIdentitasPrasarana = ?";
                $this->db->query($query, [$id]);

                return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('identitasPrasarana/edit/'.$id))->with('error', 'Id Gedung dan Lantai harus diisi.');
            }
        } else {
            return view('error/404');
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->identitasPrasaranaModel->delete($id);
        return redirect()->to(site_url('identitasPrasarana'));
    }

    public function trash() {
        $data['dataIdentitasPrasarana'] = $this->identitasPrasaranaModel->onlyDeleted()->getRecycle();
        return view('informasi/identitasPrasaranaView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblIdentitasPrasarana')
                ->set('deleted_at', null, true)
                ->where(['idIdentitasPrasarana' => $id])
                ->update();
        } else {
            $this->db->table('tblIdentitasPrasarana')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('identitasPrasarana/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->identitasPrasaranaModel->delete($id, true);
        return redirect()->to(site_url('identitasPrasarana/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->identitasPrasaranaModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->identitasPrasaranaModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('identitasPrasarana/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('identitasPrasarana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  

    public function export() {
        $data = $this->identitasPrasaranaModel->getAll();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
    
        $headers = ['No.', 'Kode', 'Nama Prasarana', 'Luas', 'Lokasi Gedung', 'Lokasi Lantai'];
        $activeWorksheet->fromArray([$headers], NULL, 'A1');
        $activeWorksheet->getStyle('A1:F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        foreach ($data as $index => $value) {
            $activeWorksheet->setCellValue('A'.($index + 2), $index + 1);
            $activeWorksheet->setCellValue('B'.($index + 2), $value->kodePrasarana);
            $activeWorksheet->setCellValue('C'.($index + 2), $value->namaPrasarana);
            $activeWorksheet->setCellValue('D'.($index + 2), $value->luas . ' m²');
            $activeWorksheet->setCellValue('E'.($index + 2), $value->namaGedung);
            $activeWorksheet->setCellValue('F'.($index + 2), $value->namaLantai);
    
            $columns = ['A', 'B', 'C', 'D', 'E', 'F'];

            foreach ($columns as $column) {
                $activeWorksheet->getStyle($column . ($index + 2))
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }            
        }
    
        $activeWorksheet->getStyle('A1:F1')->getFont()->setBold(true);
        $activeWorksheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        $activeWorksheet->getStyle('A1:F'.$activeWorksheet->getHighestRow())->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $activeWorksheet->getStyle('A:F')->getAlignment()->setWrapText(true);
    
        foreach (range('A', 'F') as $column) {
            $activeWorksheet->getColumnDimension($column)->setAutoSize(true);
        }
    
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Identitas Prasarana.xlsx');
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
                $namaPrasarana  = $value[1] ?? null;
                $luas           = $value[2] ?? null;
                $namaGedung     = $value[3] ?? null;
                $namaLantai     = $value[4] ?? null;
            
                if ($namaPrasarana !== null) {
                    $data = [
                        'namaPrasarana' => $namaPrasarana,
                        'luas' => $luas,
                        'idIdentitasGedung' => 99,
                        'idIdentitasLantai' => 99,
                    ];
                    
                    $this->identitasPrasaranaModel->insert($data);
                }
            }
            return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil diimport');
        } else {
            return redirect()->to(site_url('identitasPrasarana'))->with('error', 'Masukkan file excel dengan extensi xlsx atau xls');
        }
    }

    public function generatePDF()
    {
        $filePath = APPPATH . 'Views/informasi/identitasPrasaranaView/print.php';
    
        if (!file_exists($filePath)) {
            die('HTML file not found');
        }

        $data['dataidentitasPrasarana'] = $this->identitasPrasaranaModel->getAll();

        ob_start();

        $includeFile = function ($filePath, $data) {
            include $filePath;
        };
    
        $includeFile($filePath, $data);
    
        $html = ob_get_clean();
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $filename = 'Identitas Prasarana Report.pdf';
        $dompdf->stream($filename);
    }
}

