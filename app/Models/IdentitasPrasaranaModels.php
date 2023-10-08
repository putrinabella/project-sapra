<?php

namespace App\Models;

use CodeIgniter\Model;

class IdentitasPrasaranaModels extends Model
{
    protected $table            = 'tblIdentitasPrasarana';
    protected $primaryKey       = 'idIdentitasPrasarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaPrasarana', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'kodePrasarana' ,'tipe'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung', 'left');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai', 'left');

        // $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        // $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->where('tblIdentitasPrasarana.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->where('tblIdentitasPrasarana.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    // function getPaginated($num, $keyword = null) {
    //     $builder = $this->builder();
    //     $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
    //     $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
    //     $builder->where('tblIdentitasPrasarana.deleted_at', null);
    //     $data = [
    //         'dataIdentitasPrasarana' => $this->paginate($num),
    //         'pager' => $this->pager,
    //     ];
    //     return $data;
    // }

    function updateKodePrasarana($id) {
        $builder = $this->db->table($this->table);
        $builder->set('kodePrasarana', 'CONCAT("P", LPAD(idIdentitasPrasarana, 3, "0"), 
                        "/G", LPAD(idIdentitasGedung, 2, "0"), 
                        "/L", LPAD(idIdentitasLantai, 2, "0"))', false);
        $builder->where('idIdentitasPrasarana', $id);
        $builder->update();
    }

    function setKodePrasarana() {
        $builder = $this->db->table($this->table);
        $builder->set('kodePrasarana', 'CONCAT("P", LPAD(idIdentitasPrasarana, 3, "0"), 
                        "/G", LPAD(idIdentitasGedung, 2, "0"), 
                        "/L", LPAD(idIdentitasLantai, 2, "0"))', false);
        $builder->update();
    }
}
