<?php

namespace App\Controllers;

class InformasiController extends BaseController
{
    public function getIdentitasSarana() {
        $builder = $this->db->table('tblIdentitasSarana');
        $query = $builder->get()->getResult();
        $data['dataIdentitasSarana'] = $query;
        return view('informasi/identitasSaranaView/show', $data);
    }

    public function addIdentitasSarana() {
        return view('informasi/identitasSaranaView/add');
    }

    public function saveIdentitasSarana() {
        $data = $this->request->getPost();
        // short way
        $this->db->table('tblIdentitasSarana')->insert($data);

        if($this->db->affectedRows() >0) {
            return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil disimpan');
        } 
    }

    public function editIdentitasSarana($id = null) {
        if ($id !== null) {
            $query = $this->db->table('tblIdentitasSarana')->getWhere(['idIdentitasSarana' => $id]);
            if($query->resultID->num_rows > 0) {
                $data['dataIdentitasSarana'] = $query->getRow();
                return view('informasi/identitasSaranaView/edit', $data);
            } else {
                return view('error/404');
                // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        } else {
            // throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            return view('error/404');
        }
    }

    public function updateIdentitasSarana($id) {
        $data = $this->request->getPost();
        
        // short way
        // unset($data['_method']);
        
        // specifict way
        $data = [
            'namaSarana' => $this->request->getVar('namaSarana'),
        ];
        
        $this->db->table('tblIdentitasSarana')->where(['idIdentitasSarana' => $id])->update($data);
        return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil diupdate');
    }

    public function deleteIdentitasSarana($id) {
        $this->db->table('tblIdentitasSarana')->where(['idIdentitasSarana' => $id])->delete();
        return redirect()->to(site_url('identitasSarana'))->with('success', 'Data berhasil dihapus');
  
    }
    

    public function getIdentitasPrasarana() {
        return view('informasi/identitasPrasaranaView');
    }

    public function getIdentitasGedung() {
        return view('informasi/identitasGedungView');
    }

    public function getIdentitasLantai() {
        return view('informasi/identitasLantaiView');
    }

    public function getSumberDana() {
        return view('informasi/sumberDanaView');
    }

    public function getStatusManajemen() {
        return view('informasi/statusManajemenView');
    }

    public function getKategoriManajemen() {
        return view('informasi/kategoriManajemenView');
    }

    public function getProfilSekolah() {
        return view('informasi/profilSekolahView');
    }
}
