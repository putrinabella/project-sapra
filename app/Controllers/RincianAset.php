<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\RincianAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 

class RincianAset extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

     function __construct() {
        $this->rincianAsetModel = new RincianAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['dataRincianAset'] = $this->rincianAsetModel->getAll();
        return view('saranaView/rincianAset/index', $data);
    }

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
            'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
            'dataSumberDana' => $this->sumberDanaModel->findAll(),
            'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
            'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
        ];
        
        return view('saranaView/rincianAset/new', $data);        
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */

    public function create() {
        $data = $this->request->getPost();
        $saranaLayak = intval($data['saranaLayak']);
        $saranaRusak = intval($data['saranaRusak']);
        $totalSarana = $saranaLayak + $saranaRusak;

        $data['totalSarana'] = $totalSarana;
        if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['kodePrasarana'])) {
            $this->rincianAsetModel->insert($data);
            $query = "UPDATE tblRincianAset SET kodeRincianAset = CONCAT('A', LPAD(idIdentitasSarana, 3, '0'), '/', tahunPengadaan, '/', 'SD', LPAD(idSumberDana, 2, '0'), '/', kodePrasarana)";
            $this->db->query($query);
            return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('rincianAset'))->with('error', 'Semua field harus terisi');
        }
    }

    // public function create() {
    //     $data = $this->request->getPost();
        
    //     if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['kodePrasarana'])) {
    //         // Calculate totalSarana
    //         $saranaLayak = intval($data['saranaLayak']);
    //         $saranaRusak = intval($data['saranaRusak']);
    //         $totalSarana = $saranaLayak + $saranaRusak;
            
    //         // Add totalSarana to the data array
    //         $data['totalSarana'] = $totalSarana;
    
    //         // Insert the data into the database
    //         $this->rincianAsetModel->insert($data);
    
    //         return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil disimpan');
    //     } else {
    //         return redirect()->to(site_url('rincianAset'))->with('error', 'Semua field harus terisi');
    //     }
    // }
    

    

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
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
    

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    // public function update($id = null)
    // {
    //     $data = $this->request->getPost();
    //     $this->rincianAsetModel->update($id, $data);
    //     return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil update'); 
    // }
    public function update($id = null)
    {
        if ($id != null) {
            $data = $this->request->getPost();

            // Check if idIdentitasSarana and idIdentitasLantai exist and are not empty
            if (!empty($data['idIdentitasSarana']) && !empty($data['idIdentitasLantai'])) {
                $this->rincianAsetModel->update($id, $data);

                // Update kodePrasarana using an SQL query
                $query = "UPDATE tblRincianAset SET kodePrasarana = CONCAT('P', LPAD(idRincianAset, 3, '0'), '/G', LPAD(idIdentitasSarana, 3, '0'), '/L', LPAD(idIdentitasLantai, 3, '0')) WHERE idRincianAset = ?";
                $this->db->query($query, [$id]);

                return redirect()->to(site_url('rincianAset'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('rincianAset/edit/'.$id))->with('error', 'Id Sarana dan Lantai harus diisi.');
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
        $this->rincianAsetModel->delete($id);
        return redirect()->to(site_url('rincianAset'));
    }

    public function trash() {
        $data['dataRincianAset'] = $this->rincianAsetModel->onlyDeleted()->findAll();
        return view('informasi/rincianAsetView/trash', $data);
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
}

