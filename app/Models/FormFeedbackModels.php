<?php

namespace App\Models;

use CodeIgniter\Model;

class FormFeedbackModels extends Model
{
    protected $table            = 'tblFormFeedback';
    protected $primaryKey       = 'idFormFeedback';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idFormFeedback', 'idDataSiswa', 'tanggal', 'statusFeedback', 'idFormPengaduan'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    // Models for admin view =============================================================================================== //
    function getAll($startDate = null, $endDate = null) {
        $builder = $this->db->table('tblFormFeedback');
        $builder->join('tblDetailFormFeedback', 'tblDetailFormFeedback.idFormFeedback = tblFormFeedback.idFormFeedback');
        $builder->join('tblFormPengaduan', 'tblFormPengaduan.idFormPengaduan = tblFormFeedback.idFormPengaduan');
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
        $builder->orderBy('tblFormFeedback.tanggal', 'asc');
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
        $builder->join('tblFormPengaduan', 'tblFormPengaduan.idFormPengaduan = tblFormFeedback.idFormPengaduan');
        $builder->where('tblFormFeedback.idFormFeedback', $id);
        $query = $builder->get();
        return $query->getRow();
    }
    
    public function getFeedbackPercentages() {
        $builder = $this->db->table('tblDetailFormFeedback');
        $builder->select('idFormFeedback, SUM(isiFeedback) as totalFeedback, COUNT(*) as feedbackCount');
        // $builder->where('tblDetailFormFeedback.idFormFeedback', $id);
        $builder->groupBy('idFormFeedback');
        $query = $builder->get();
    
        $result = $query->getResult();
    
        $points = [];
    
        foreach ($result as $row) {
            $idFormFeedback = $row->idFormFeedback;
            $totalFeedback = $row->totalFeedback;
            $feedbackCount = $row->feedbackCount;
    
            $mean = ($feedbackCount > 0) ? $totalFeedback / $feedbackCount : 0;
            $percentage = 100 / $feedbackCount;
            $point = $mean * $percentage;
    
            $points[$idFormFeedback] = $point;
        }
    
        return $points;
    }

    public function getAverageFeedbackPercentages() {
        $builder = $this->db->table('tblDetailFormFeedback');
        $builder->select('idFormFeedback, SUM(isiFeedback) as totalFeedback, COUNT(*) as feedbackCount');
        $builder->where('tblDetailFormFeedback.isiFeedback!=', null);
        $builder->groupBy('idFormFeedback');
        $query = $builder->get();
    
        $result = $query->getResult();
    
        $points = [];
    
        foreach ($result as $row) {
            $idFormFeedback = $row->idFormFeedback;
            $totalFeedback = $row->totalFeedback;
            $feedbackCount = $row->feedbackCount;
    
            $mean = ($feedbackCount > 0) ? $totalFeedback / $feedbackCount : 0;
            $percentage = ($feedbackCount > 0) ? (100 / $feedbackCount) : 0;
            $point = $mean * $percentage;
    
            $points[$idFormFeedback] = (float) $point;
        }
    
        $overallAverage = count($points) > 0 ? array_sum($points) / count($points) : 0;
    
        return $overallAverage;
    }
    
    
    // End of models for admin view ======================================================================================== //

    // Models for user view =============================================================================================== //
    function getData($startDate = null, $endDate = null, $idUser) {
        $builder = $this->db->table('tblFormFeedback');
        $builder->join('tblDetailFormFeedback', 'tblDetailFormFeedback.idFormFeedback = tblFormFeedback.idFormFeedback');
        $builder->join('tblFormPengaduan', 'tblFormPengaduan.idFormPengaduan = tblFormFeedback.idFormPengaduan');
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
        $builder->orderBy('tblFormFeedback.tanggal', 'asc');
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
        $builder->join('tblFormPengaduan', 'tblFormPengaduan.idFormPengaduan = tblFormFeedback.idFormPengaduan');
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

    public function getFeedbackPercentagesUser($id) {
        $builder = $this->db->table('tblDetailFormFeedback');
        $builder->select('idFormFeedback, SUM(isiFeedback) as totalFeedback, COUNT(*) as feedbackCount');
        $builder->where('tblDetailFormFeedback.idFormFeedback', $id);
        $builder->groupBy('idFormFeedback');
        $query = $builder->get();
    
        $result = $query->getResult();
    
        $points = [];
    
        foreach ($result as $row) {
            $idFormFeedback = $row->idFormFeedback;
            $totalFeedback = $row->totalFeedback;
            $feedbackCount = $row->feedbackCount;
    
            $mean = ($feedbackCount > 0) ? $totalFeedback / $feedbackCount : 0;
            $percentage = 100 / $feedbackCount;
            $point = $mean * $percentage;
    
            $points[$idFormFeedback] = $point;
        }
    
        return $points;
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
