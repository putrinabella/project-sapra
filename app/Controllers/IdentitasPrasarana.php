<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\IdentitasGedungModels; 
use App\Models\IdentitasLantaiModels; 
use App\Models\IdentitasPrasaranaModels; 

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
    }

    public function index()
    {
        $data['dataIdentitasPrasarana'] = $this->identitasPrasaranaModel->getAll();
        return view('informasi/identitasPrasaranaView/index', $data);
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
    public function create()
    {
        $data = $this->request->getPost();
        $this->identitasPrasaranaModel->insert($data);
        return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil disimpan');
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
        $data = $this->request->getPost();
        $this->identitasPrasaranaModel->update($id, $data);
        return redirect()->to(site_url('identitasPrasarana'))->with('success', 'Data berhasil update'); 
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
        $data['dataIdentitasPrasarana'] = $this->identitasPrasaranaModel->onlyDeleted()->findAll();
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
}

