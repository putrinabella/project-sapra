<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\IdentitasSaranaModels;

class IdentitasSarana extends ResourcePresenter
{
    function __construct() {
        $this->identitasSaranaModel = new IdentitasSaranaModels();
    }
    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $data['dataIdentitasSarana'] = $this->identitasSaranaModel->findAll();
        return view('informasi/identitasSaranaView/index', $data);
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
        return view('informasi/identitasSaranaView/new');
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
        $this->identitasSaranaModel->insert($data);
        return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil disimpan');
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
            $dataIdentitasSarana = $this->identitasSaranaModel->where('idIdentitasSarana', $id)->first();
    
            if (is_object($dataIdentitasSarana)) {
                $data['dataIdentitasSarana'] = $dataIdentitasSarana;
                return view('informasi/identitasSaranaView/edit', $data);
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
        $this->identitasSaranaModel->update($id, $data);
        return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil update');
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
        $this->identitasSaranaModel->where('idIdentitasSarana', $id)->delete();
        return redirect()->to(site_url('identitasSarana'));
    }

    public function trash() {
        $data['dataIdentitasSarana'] = $this->identitasSaranaModel->onlyDeleted()->findAll();
        return view('informasi/identitasSaranaView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblIdentitasSarana')
                ->set('deleted_at', null, true)
                ->where(['idIdentitasSarana' => $id])
                ->update();
        } else {
            $this->db->table('tblIdentitasSarana')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }

        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('identitasSarana/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->identitasSaranaModel->delete($id, true);
        return redirect()->to(site_url('identitasSarana/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->identitasSaranaModel->onlyDeleted()->countAllResults();
            
            if ($countInTrash > 0) {
                $this->identitasSaranaModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('identitasSarana/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('identitasSarana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
        
    }  
}
