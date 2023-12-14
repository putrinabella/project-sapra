<?php

namespace App\Models;

use CodeIgniter\Model;

class FormFeedbackModels extends Model
{
    protected $table            = 'tblFormFeedback';
    protected $primaryKey       = 'idFormFeedback';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idFormFeedback', 'idDataSiswa', 'tanggal', 'statusFeedback'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    // Models for admin view =============================================================================================== //
    function getAll($startDate = null, $endDate = null) {
        $builder = $this->db->table('tblFormFeedback');
        $builder->join('tblDetailFormFeedback', 'tblDetailFormFeedback.idFormFeedback = tblFormFeedback.idFormFeedback');
        $builder->join('tblPertanyaanFeedback', 'tblPertanyaanFeedback.idPertanyaanFeedback = tblDetailFormFeedback.idPertanyaanFeedback');  
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblFormFeedback.idDataSiswa');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblFormFeedback.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblFormFeedback.statusFeedback = 'empty' THEN 1
                    WHEN tblFormFeedback.statusFeedback = 'done' THEN 2
                    WHEN tblFormFeedback.statusFeedback = 'process' THEN 3
                    WHEN tblFormFeedback.statusFeedback = 'done' THEN 4
                    ELSE 3 END", 'asc');     
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblFormFeedback.tanggal >=', $startDate);
            $builder->where('tblFormFeedback.tanggal <=', $endDate);
        }
        $builder->groupBy('tblFormFeedback.idFormFeedback');
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdentitas($id = null) {
        $builder = $this->db->table('tblFormFeedback');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblFormFeedback.idDataSiswa');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblFormFeedback.idFormFeedback', $id);
        $query = $builder->get();
        return $query->getRow();
    }
    // End of models for admin view ======================================================================================== //

    // Models for user view =============================================================================================== //
    function getData($startDate = null, $endDate = null, $idUser) {
        $builder = $this->db->table('tblFormFeedback');
        $builder->join('tblDetailFormFeedback', 'tblDetailFormFeedback.idFormFeedback = tblFormFeedback.idFormFeedback');
        $builder->join('tblPertanyaanFeedback', 'tblPertanyaanFeedback.idPertanyaanFeedback = tblDetailFormFeedback.idPertanyaanFeedback');  
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblFormFeedback.idDataSiswa');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblFormFeedback.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblFormFeedback.statusFeedback = 'empty' THEN 1
                    WHEN tblFormFeedback.statusFeedback = 'done' THEN 2
                    WHEN tblFormFeedback.statusFeedback = 'process' THEN 3
                    WHEN tblFormFeedback.statusFeedback = 'done' THEN 4
                    ELSE 3 END", 'asc');     
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblFormFeedback.tanggal >=', $startDate);
            $builder->where('tblFormFeedback.tanggal <=', $endDate);
        }
    
        $builder->where('tblFormFeedback.idDataSiswa', $idUser);
        $builder->groupBy('tblFormFeedback.idFormFeedback');
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdentitasUser($id) {
        $builder = $this->db->table('tblFormFeedback');
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblFormFeedback.idDataSiswa');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblFormFeedback.idFormFeedback', $id);
        $query = $builder->get();
        return $query->getRow();
    }

    function getIdDetailFeedback($id) {
        $builder = $this->db->table('tblDetailFormFeedback');
        $builder->where('tblDetailFormFeedback.idFormFeedback', $id);
        $query = $builder->get();
        return $query->getResult();
    }

    // End of models for user view ======================================================================================== //

    // Models for general use =============================================================================================== //
    function getDetailDataFeedback($idFormFeedback) {
        $builder = $this->db->table('tblFormFeedback');
        $builder->join('tblDetailFormFeedback', 'tblDetailFormFeedback.idFormFeedback = tblFormFeedback.idFormFeedback');
        $builder->join('tblPertanyaanFeedback', 'tblPertanyaanFeedback.idPertanyaanFeedback = tblDetailFormFeedback.idPertanyaanFeedback');  
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblFormFeedback.idDataSiswa');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblFormFeedback.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblFormFeedback.statusFeedback = 'empty' THEN 1
                    WHEN tblFormFeedback.statusFeedback = 'done' THEN 2
                    ELSE 3 END", 'asc');     
        $builder->where('tblFormFeedback.idFormFeedback', $idFormFeedback);
        $query = $builder->get();
        return $query->getResult();
    }
    // End of models for general use ======================================================================================== //
}
