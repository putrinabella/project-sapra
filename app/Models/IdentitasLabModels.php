<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasLabModels extends Model
{
    protected $table            = 'tblIdentitasLab';
    protected $primaryKey       = 'idIdentitasLab';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaLab', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'kodeLab'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasLab.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasLab.idIdentitasLantai');
        $builder->where('tblIdentitasLab.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasLab.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasLab.idIdentitasLantai');
        $builder->where('tblIdentitasLab.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function updateKodeLab($id) {
        $builder = $this->db->table($this->table);
        $builder->set('kodeLab', 'CONCAT("LAB", LPAD(idIdentitasLab, 3, "0"), 
                        "/G", LPAD(idIdentitasGedung, 2, "0"), 
                        "/L", LPAD(idIdentitasLantai, 2, "0"))', false);
        $builder->where('idIdentitasLab', $id);
        $builder->update();
    }

    function setKodeLab() {
        $builder = $this->db->table($this->table);
        $builder->set('kodeLab', 'CONCAT("LAB", LPAD(idIdentitasLab, 3, "0"), 
                        "/G", LPAD(idIdentitasGedung, 2, "0"), 
                        "/L", LPAD(idIdentitasLantai, 2, "0"))', false);
        $builder->update();
    }
}
