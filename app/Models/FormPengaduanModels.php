<?php

namespace App\Models;

use CodeIgniter\Model;

class FormPengaduanModels extends Model
{
    protected $table            = 'tblFormPengaduan';
    protected $primaryKey       = 'idFormPengaduan';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idFormPengaduan', 'idDataSiswa', 'tanggal', 'statusPengaduan'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getData($startDate = null, $endDate = null, $idUser) {
        $builder = $this->db->table('tblFormPengaduan');
        $builder->join('tblDetailFormPengaduan', 'tblDetailFormPengaduan.idFormPengaduan = tblFormPengaduan.idFormPengaduan');
        $builder->join('tblPertanyaanPengaduan', 'tblPertanyaanPengaduan.idPertanyaanPengaduan = tblDetailFormPengaduan.idPertanyaanPengaduan');  
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblFormPengaduan.idDataSiswa');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblFormPengaduan.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblFormPengaduan.statusPengaduan = 'needFeedback' THEN 1
                    WHEN tblFormPengaduan.statusPengaduan = 'request' THEN 2
                    WHEN tblFormPengaduan.statusPengaduan = 'process' THEN 3
                    WHEN tblFormPengaduan.statusPengaduan = 'done' THEN 4
                    ELSE 5 END", 'asc');     
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblFormPengaduan.tanggal >=', $startDate);
            $builder->where('tblFormPengaduan.tanggal <=', $endDate);
        }
    
        $builder->where('tblFormPengaduan.idDataSiswa', $idUser);
        $builder->groupBy('tblFormPengaduan.idFormPengaduan');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDetailDataPengaduan($idFormPengaduan) {
        $builder = $this->db->table('tblFormPengaduan');
        $builder->join('tblDetailFormPengaduan', 'tblDetailFormPengaduan.idFormPengaduan = tblFormPengaduan.idFormPengaduan');
        $builder->join('tblPertanyaanPengaduan', 'tblPertanyaanPengaduan.idPertanyaanPengaduan = tblDetailFormPengaduan.idPertanyaanPengaduan');  
        $builder->join('tblDataSiswa', 'tblDataSiswa.idDataSiswa = tblFormPengaduan.idDataSiswa');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblFormPengaduan.deleted_at', null);
        $builder->orderBy("CASE 
                    WHEN tblFormPengaduan.statusPengaduan = 'needFeedback' THEN 1
                    WHEN tblFormPengaduan.statusPengaduan = 'request' THEN 2
                    WHEN tblFormPengaduan.statusPengaduan = 'process' THEN 3
                    WHEN tblFormPengaduan.statusPengaduan = 'done' THEN 4
                    ELSE 5 END", 'asc');     
        $builder->where('tblFormPengaduan.idFormPengaduan', $idFormPengaduan);
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdentitasUser($idUser) {
        $builder = $this->db->table('tblDataSiswa');
        $builder->join('tblFormPengaduan', 'tblFormPengaduan.idDataSiswa = tblDataSiswa.idDataSiswa');
        $builder->join('tblIdentitasKelas', 'tblIdentitasKelas.idIdentitasKelas = tblDataSiswa.idIdentitasKelas');
        $builder->where('tblFormPengaduan.idDataSiswa', $idUser);
        $query = $builder->get();
        return $query->getRow();
    }
    
}
