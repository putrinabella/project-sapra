<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use App\Models\KategoriManajemenModels;

class KategoriManajemen extends ResourcePresenter
{
    function __construct() {
        $this->kategoriManajemenModel = new KategoriManajemenModels();
    }
    /**
     * Present a view of resource objects
     *
     * @return mixed
     */
    public function index()
    {
        $data['dataKategoriManajemen'] = $this->kategoriManajemenModel->findAll();
        return view('informasi/kategoriManajemenView/index', $data);
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
        return view('informasi/kategoriManajemenView/new');
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
        $this->kategoriManajemenModel->insert($data);
        return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil disimpan');
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
            $dataKategoriManajemen = $this->kategoriManajemenModel->where('idKategoriManajemen', $id)->first();
    
            if (is_object($dataKategoriManajemen)) {
                $data['dataKategoriManajemen'] = $dataKategoriManajemen;
                return view('informasi/kategoriManajemenView/edit', $data);
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
        $this->kategoriManajemenModel->update($id, $data);
        return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil update');
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
        $this->kategoriManajemenModel->where('idKategoriManajemen', $id)->delete();
        return redirect()->to(site_url('kategoriManajemen'));
    }

    public function trash() {
        $data['dataKategoriManajemen'] = $this->kategoriManajemenModel->onlyDeleted()->findAll();
        return view('informasi/kategoriManajemenView/trash', $data);
    } 

    public function restore($id = null) {
        $this->db = \Config\Database::connect();
        if($id != null) {
            $this->db->table('tblKategoriManajemen')
                ->set('deleted_at', null, true)
                ->where(['idKategoriManajemen' => $id])
                ->update();
        } else {
            $this->db->table('tblKategoriManajemen')
                ->set('deleted_at', null, true)
                ->where('deleted_at is NOT NULL', NULL, FALSE)
                ->update();
        }

        if($this->db->affectedRows() > 0) {
            return redirect()->to(site_url('kategoriManajemen'))->with('success', 'Data berhasil direstore');
        } 
        return redirect()->to(site_url('kategoriManajemen/trash'))->with('error', 'Tidak ada data untuk direstore');
    } 

    public function deletePermanent($id = null) {
        if($id != null) {
        $this->kategoriManajemenModel->delete($id, true);
        return redirect()->to(site_url('kategoriManajemen/trash'))->with('success', 'Data berhasil dihapus permanen');
        } else {
            $countInTrash = $this->kategoriManajemenModel->onlyDeleted()->countAllResults();
            
            if ($countInTrash > 0) {
                $this->kategoriManajemenModel->onlyDeleted()->purgeDeleted();
                return redirect()->to(site_url('kategoriManajemen/trash'))->with('success', 'Semua data trash berhasil dihapus permanen');
            } else {
                return redirect()->to(site_url('kategoriManajemen/trash'))->with('error', 'Tempat sampah sudah kosong!');
            }
        }
        
    }  
}
