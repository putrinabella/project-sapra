<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilSekolahModels extends Model
{
    protected $table            = 'tblProfilSekolah';
    protected $primaryKey       = 'idProfilSekolah';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idProfilSekolah', 'npsn', 'status', 'bentukPendidikan' ,'statusKepemilikan' ,'skPendirian', 'tanggalSkPendirian', 'skIzinOperasional', 'tanggalSkIzinOperasional', 'statusBos', 'waktuPenyelenggaraan', 'sertifikasiIso', 'sumberListrik', 'kecepatanInternet', 'siswaKebutuhanKhusus', 'namaBank', 'cabangKcp', 'atasNamaRekening'];

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    function getCount() {
        return $this->countAll();
    }
}
