<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananAsetItModels extends Model
{
    protected $table            = 'tblSaranaLayananAset';
    protected $primaryKey       = 'idSaranaLayananAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idSaranaLayananAset', 'idRincianAset', 'idSumberDana','idStatusLayanan', 'biaya', 'bukti','tanggal' , 'keterangan'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    protected $tableRincianAset           = 'tblRincianAset';
    protected $primaryKeyRincianAset      = 'idRincianAset';
    protected $returnTypeRincianAset      = 'object';
    protected $allowedFieldsRincianAse    = ['idRincianAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'kodePrasarana', 'tahunPengadaan', 'saranaLayak', 'saranaRusak', 'spesifikasi', 'totalSarana', 'bukti', 'kodeRincianAset'];
    protected $useTimestampsRincianAse    = true;
    protected $useSoftDeletesRincianAse   = true;

    protected $tableSarana            = 'tblIdentitasSarana';
    protected $primaryKeySarana       = 'idIdentitasSarana';
    protected $returnTypeSarana       = 'object';


    function getAll() {
        $builder = $this->db->table('tblRincianAset');
        // $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblSaranaLayananAset.idRincianAset');
        $builder->join('tblSaranaLayananAset', 'tblSaranaLayananAset.idRincianAset = tblRincianAset.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at', null);
        $builder->where('tblIdentitasSarana.perangkatIt', 1);

        $query = $builder->get();
        return $query->getResult();
    }
    
    function getRincianIT() {
        $builder = $this->db->table($this->tableRincianAset);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblIdentitasSarana.perangkatIt', 1);
        $query = $builder->get();
        return $query->getResult();
    }

    function getSaranaIT() {
        $builder = $this->db->table($this->tableRincianAset);
        $builder->select('DISTINCT(tblRincianAset.idIdentitasSarana), tblIdentitasSarana.namaSarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblIdentitasSarana.perangkatIt', 1);
        $builder->where('tblRincianAset.sectionAset', "None");  
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana'); 
        $query = $builder->get();
        return $query->getResult();

        // $builder->where('tblIdentitasSarana.deleted_at', null);
        // $query = $builder->get();
        // return $query->getResult();
    }

    function updateKodeAset($id) {
        $builder = $this->db->table($this->tableRincianAset);
        $builder->set('kodeRincianAset', 
                        'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
                        "/", tahunPengadaan, 
                        "/", "SD", LPAD(idSumberDana, 2, "0"), 
                        "/", idIdentitasPrasarana)',
                        false
                        );
        $builder->where('idRincianAset', $id);
        $builder->update();
    }

    function setKodeAset() {
        $builder = $this->db->table($this->tableRincianAset);
        $builder->set('kodeRincianAset', 
                        'CONCAT("A", LPAD(idIdentitasSarana, 3, "0"), 
                        "/", tahunPengadaan, 
                        "/", "SD", LPAD(idSumberDana, 2, "0"), 
                        "/", idIdentitasPrasarana)',
                        false
                        );
        $builder->update();
    }

    function calculateTotalSarana($saranaLayak, $saranaRusak) {
        $saranaLayak = intval($saranaLayak);
        $saranaRusak = intval($saranaRusak);
        $totalSarana = $saranaLayak + $saranaRusak;
        return $totalSarana;
    }

    
    function getRecycle() {
        $builder = $this->db->table('tblSaranaLayananAset');
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblSaranaLayananAset.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        $builder->where('tblSaranaLayananAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblRincianAset', 'tblRincianAset.idRincianAset = tblSaranaLayananAset.idRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblSaranaLayananAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->join('tblStatusLayanan', 'tblStatusLayanan.idStatusLayanan = tblSaranaLayananAset.idStatusLayanan');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }
}
