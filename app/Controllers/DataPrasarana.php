<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DataPrasaranaModels; 
use App\Models\IdentitasPrasaranaModels; 

class DataPrasarana extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */

     function __construct() {
        $this->dataPrasaranaModel = new DataPrasaranaModels();
        $this->identitasPrasaranaModel = new IdentitasPrasaranaModels();
    }

    // DATATABLES
    public function index()
    {
        $data['dataPrasarana'] = $this->dataPrasaranaModel->getAll();
        return view('prasarana/RuanganView', $data);
    }

    // Manual Pagination
    // public function index()
    // {
    //     $data = $this->dataPrasaranaModel->getPaginated(10);
    //     return view('master/dataPrasaranaView/index', $data);
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
            'dataIdentitasGedung' => $this->dataPrasaranaModel->findAll(),
            'dataIdentitasLantai' => $this->identitasLantaiModel->findAll(),
        ];
        
        return view('master/dataPrasaranaView/new', $data);        
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $data = $this->request->getPost();
        $this->dataPrasaranaModel->insert($data);
        return redirect()->to(site_url('dataPrasarana'))->with('success', 'Data berhasil disimpan');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        if ($id != null) {
            $dataPrasaranaModel = $this->dataPrasaranaModel->find($id);
    
            if (is_object($dataPrasaranaModel)) {
                $data = [
                    'dataPrasaranaModel' => $dataPrasaranaModel,
                    'dataIdentitasGedung' => $this->dataPrasaranaModel->findAll(),
                    'dataIdentitasLantai' => $this->identitasLantaiModel->findAll(),
                ];
                return view('master/dataPrasaranaView/edit', $data);
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
        $data = $this->request->getPost();
        $this->dataPrasaranaModel->update($id, $data);
        return redirect()->to(site_url('dataPrasarana'))->with('success', 'Data berhasil update'); 
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $this->dataPrasaranaModel->delete($id);
        return redirect()->to(site_url('dataPrasarana'));
    }

    public function trash() {
        $data['dataPrasaranaModel'] = $this->dataPrasaranaModel->onlyDeleted()->findAll();
        return view('master/dataPrasaranaView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblPrasaranaModel')
                ->set('deleted_at', null, true)
                ->where(['idPrasaranaModel' => $id])
                ->update();
        } else {
            $this->db->table('tblPrasaranaModel')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('dataPrasarana'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('dataPrasarana/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->dataPrasaranaModel->delete($id, true);
        return redirect()->to(site_url('dataPrasarana/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->dataPrasaranaModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->dataPrasaranaModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('dataPrasarana/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('dataPrasarana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  
}

