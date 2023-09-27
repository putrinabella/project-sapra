<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\SaranaLayananAsetModels; 
use App\Models\IdentitasSaranaModels; 
use App\Models\SumberDanaModels; 
use App\Models\KategoriManajemenModels; 
use App\Models\IdentitasPrasaranaModels; 

class SaranaLayananAset extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

     function __construct() {
        $this->saranaLayananAsetModel = new SaranaLayananAsetModels();
        $this->identitasSaranaModel = new IdentitasSaranaModels();
        $this->sumberDanaModel = new SumberDanaModels();
        $this->kategoriManajemenModel = new KategoriManajemenModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data['dataSaranaLayananAset'] = $this->saranaLayananAsetModel->getAll();
        return view('saranaView/layananAset/index', $data);
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
        
        return view('saranaView/saranaLayananAset/new', $data);        
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
            $this->saranaLayananAsetModel->insert($data);
            $query = "UPDATE tblSaranaLayananAset SET kodeSaranaLayananAset = CONCAT('A', LPAD(idIdentitasSarana, 3, '0'), '/', tahunPengadaan, '/', 'SD', LPAD(idSumberDana, 2, '0'), '/', kodePrasarana)";
            $this->db->query($query);
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil disimpan');
        } else {
            return redirect()->to(site_url('saranaLayananAset'))->with('error', 'Semua field harus terisi');
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
            $dataSaranaLayananAset = $this->saranaLayananAsetModel->find($id);
    
            if (is_object($dataSaranaLayananAset)) {
                $data = [
                    'dataSaranaLayananAset' => $dataSaranaLayananAset,
                    'dataIdentitasSarana' => $this->identitasSaranaModel->findAll(),
                    'dataSumberDana' => $this->sumberDanaModel->findAll(),
                    'dataKategoriManajemen' => $this->kategoriManajemenModel->findAll(),
                    'dataIdentitasPrasarana' => $this->identitasPrasaranaModel->findAll(),
                ];
                return view('saranaView/saranaLayananAset/edit', $data);
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
            if (!empty($data['idIdentitasSarana']) && !empty($data['tahunPengadaan']) && !empty($data['idSumberDana']) && !empty($data['kodePrasarana'])) {
                $this->saranaLayananAsetModel->update($id, $data);
                $query = "UPDATE tblSaranaLayananAset SET kodeSaranaLayananAset = CONCAT('A', LPAD(idIdentitasSarana, 3, '0'), '/', tahunPengadaan, '/', 'SD', LPAD(idSumberDana, 2, '0'), '/', kodePrasarana)  WHERE idSaranaLayananAset = ?";
                $this->db->query($query, [$id]);

                return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil diupdate');
            } else {
                return redirect()->to(site_url('saranaLayananAset/edit/'.$id))->with('error', 'Id Sarana dan Lantai harus diisi.');
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
        $this->saranaLayananAsetModel->delete($id);
        return redirect()->to(site_url('saranaLayananAset'));
    }

    public function trash() {
        $data['dataSaranaLayananAset'] = $this->saranaLayananAsetModel->onlyDeleted()->getRecycle();
        return view('saranaView/saranaLayananAset/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblSaranaLayananAset')
                ->set('deleted_at', null, true)
                ->where(['idSaranaLayananAset' => $id])
                ->update();
        } else {
            $this->db->table('tblSaranaLayananAset')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('saranaLayananAset'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('saranaLayananAset/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->saranaLayananAsetModel->delete($id, true);
        return redirect()->to(site_url('saranaLayananAset/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->saranaLayananAsetModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->saranaLayananAsetModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('saranaLayananAset/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('saranaLayananAset/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  
}
