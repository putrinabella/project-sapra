<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\StatusLayananModels;

class StatusLayanan extends ResourcePresenter
{
    function __construct() {
        $this->statusLayananModel = new StatusLayananModels();
    }
    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $data['dataStatusLayanan'] = $this->statusLayananModel->findAll();
        return view('informasi/statusLayananView/index', $data);
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
        return view('informasi/statusLayananView/new');
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
        $this->statusLayananModel->insert($data);
        return redirect()->to(site_url('statusLayanan'))->with('success', 'Data berhasil disimpan');
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
            $dataStatusLayanan = $this->statusLayananModel->where('idStatusLayanan', $id)->first();
    
            if (is_object($dataStatusLayanan)) {
                $data['dataStatusLayanan'] = $dataStatusLayanan;
                return view('informasi/statusLayananView/edit', $data);
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
        $this->statusLayananModel->update($id, $data);
        return redirect()->to(site_url('statusLayanan'))->with('success', 'Data berhasil update');
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
        $this->statusLayananModel->delete($id);
        return redirect()->to(site_url('statusLayanan'));
    }

    public function trash() {
        $data['dataStatusLayanan'] = $this->statusLayananModel->onlyDeleted()->findAll();
        return view('informasi/statusLayananView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblStatusLayanan')
                ->set('deleted_at', null, true)
                ->where(['idStatusLayanan' => $id])
                ->update();
        } else {
            $this->db->table('tblStatusLayanan')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
            }
        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('statusLayanan'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('statusLayanan/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->statusLayananModel->delete($id, true);
        return redirect()->to(site_url('statusLayanan/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->statusLayananModel->onlyDeleted()->countAllResults();
        
            if ($countInTrash > 0) {
                $this->statusLayananModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('statusLayanan/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('statusLayanan/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  
}
