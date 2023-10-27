<?php

namespace App\Models;

use CodeIgniter\Model;

class PerangkatItModels extends Model
{
    protected $table            = 'tblIdentitasSarana';
    protected $primaryKey       = 'idIdentitasSarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaSarana', 'perangkatIT'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;
    
    function getPerangkatIT() {
        $builder = $this->db->table($this->table);
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getData($idIdentitasSarana) {
        $builder = $this->db->table($this->table);
        $builder->join('tblRincianAset', 'tblRincianAset.idIdentitasSarana = tblIdentitasSarana.idIdentitasSarana');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->where('tblIdentitasSarana.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianAset.deleted_at', null); 
        $query = $builder->get();
    
        return $query->getResult();
    }

    // function getTotalSarana($idIdentitasSarana) {
    //     $builder = $this->db->table('tblRincianAset');
    //     $builder->selectSum('idIdentitasSarana', 'totalSarana');
    //     $builder->where('idIdentitasSarana', $idIdentitasSarana);
    //     $query = $builder->get();
    //     return $query->getRow()->totalSarana;
    // }
    
    function getTotalSarana($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('COUNT(*) as totalSarana');
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianAset.deleted_at', null); 
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $query = $builder->get();
        $result = $query->getRow();
    
        if ($result) {
            return $result->totalSarana;
        } else {
            return 0;
        }
    }
    
    function getSaranaLayak($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $builder->where('status', 'Bagus');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }

    function getSaranaRusak($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $builder->where('status', 'Rusak');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }

    function getSaranaHilang($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $builder->where('status', 'Hilang');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }
}
