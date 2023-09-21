<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\IdentitasGedungModels;

class IdentitasGedung extends ResourcePresenter
{
    function __construct() {
        $this->identitasGedungModel = new IdentitasGedungModels();
    }
    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $data['dataIdentitasGedung'] = $this->identitasGedungModel->findAll();
        return view('informasi/identitasGedungView/index', $data);
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
        return view('informasi/identitasGedungView/new');
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
        $this->identitasGedungModel->insert($data);
        return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil disimpan');
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
            $dataIdentitasGedung = $this->identitasGedungModel->where('idIdentitasGedung', $id)->first();
    
            if (is_object($dataIdentitasGedung)) {
                $data['dataIdentitasGedung'] = $dataIdentitasGedung;
                return view('informasi/identitasGedungView/edit', $data);
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
        $this->identitasGedungModel->update($id, $data);
        return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil update');
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
        $this->identitasGedungModel->where('idIdentitasGedung', $id)->delete();
        return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil dihapus');
    }

    public function trash() {
        $data['dataIdentitasGedung'] = $this->identitasGedungModel->onlyDeleted()->findAll();
        return view('informasi/identitasGedungView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblIdentitasGedung')
                ->set('deleted_at', null, true)
                ->where(['idIdentitasGedung' => $id])
                ->update();
        } else {
            $this->db->table('tblIdentitasGedung')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('identitasGedung'))->with('success', 'Data berhasil direstore');
        } 
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->identitasGedungModel->delete($id, true);
        return redirect()->to(site_url('identitasGedung/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $this->identitasGedungModel->purgeDeleted($id);
            return redirect()->to(site_url('identitasGedung/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
        }
    }  
}
