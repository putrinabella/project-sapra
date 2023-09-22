<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\IdentitasLantaiModels;

class IdentitasLantai extends ResourcePresenter
{
    function __construct() {
        $this->identitasLantaiModel = new IdentitasLantaiModels();
    }
    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $data['dataIdentitasLantai'] = $this->identitasLantaiModel->findAll();
        return view('informasi/identitasLantaiView/index', $data);
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
        return view('informasi/identitasLantaiView/new');
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
        $this->identitasLantaiModel->insert($data);
        return redirect()->to(site_url('identitasLantai'))->with('success', 'Data berhasil disimpan');
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
            $dataIdentitasLantai = $this->identitasLantaiModel->where('idIdentitasLantai', $id)->first();
    
            if (is_object($dataIdentitasLantai)) {
                $data['dataIdentitasLantai'] = $dataIdentitasLantai;
                return view('informasi/identitasLantaiView/edit', $data);
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
        $this->identitasLantaiModel->update($id, $data);
        return redirect()->to(site_url('identitasLantai'))->with('success', 'Data berhasil update');
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
        $this->identitasLantaiModel->where('idIdentitasLantai', $id)->delete();
        return redirect()->to(site_url('identitasLantai'))->with('success', 'Data berhasil dihapus');
    }

    public function trash() {
        $data['dataIdentitasLantai'] = $this->identitasLantaiModel->onlyDeleted()->findAll();
        return view('informasi/identitasLantaiView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblIdentitasLantai')
                ->set('deleted_at', null, true)
                ->where(['idIdentitasLantai' => $id])
                ->update();
        } else {
            $this->db->table('tblIdentitasLantai')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }

        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('identitasLantai'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('identitasLantai/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->identitasLantaiModel->delete($id, true);
        return redirect()->to(site_url('identitasLantai/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->identitasLantaiModel->onlyDeleted()->countAllResults();
            
            if ($countInTrash > 0) {
                $this->identitasLantaiModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('identitasLantai/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('identitasLantai/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
        
    }  
}
