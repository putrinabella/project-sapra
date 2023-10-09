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
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.kodePrasarana = tblRincianAset.kodePrasarana');
        $builder->where('tblIdentitasSarana.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblRincianAset.deleted_at', null); 
        $query = $builder->get();
    
        return $query->getResult();
    }

    function getTotalSarana($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->where('tblRincianAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->selectSum('totalSarana');
        $query = $builder->get();
        return $query->getRow()->totalSarana;
    }
    
    function getSaranaLayak($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->where('tblRincianAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->selectSum('saranaLayak');
        $query = $builder->get();
        return $query->getRow()->saranaLayak;
    }

    function getSaranaRusak($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->where('tblRincianAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->selectSum('saranaRusak');
        $query = $builder->get();
        return $query->getRow()->saranaRusak;
    }
}
