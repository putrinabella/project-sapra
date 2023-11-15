<?php

namespace App\Models;

use CodeIgniter\Model;

class DataSiswaModels extends Model
{
    protected $table            = 'tblDataSiswa';
    protected $primaryKey       = 'idDataSiswa';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idDataSiswa', 'namaSiswa', 'nis', 'idIdentitasKelas'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getData() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblDataSiswa.deleted_at', NULL);
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->where('tblDataSiswa.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }
}
