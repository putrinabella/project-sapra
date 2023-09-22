<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\SumberDanaModels;

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
        $this->sumberDanaModel->where('idSumberDana', $id)->delete();
        return redirect()->to(site_url('sumberDana'))->with('success', 'Data berhasil dihapus');
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
                return redirect()->to(site_url('sumberDana'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('sumberDana/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
    }  
}
