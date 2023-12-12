<?php

namespace App\Models;

use CodeIgniter\Model;

class RincianLabAsetModels extends Model
{
    protected $table            = 'tblRincianLabAset';
    protected $primaryKey       = 'idRincianLabAset';
    protected $returnType       = 'object';
    protected $allowedFields    = ['idRincianLabAset', 'idIdentitasSarana', 'idSumberDana', 'idKategoriManajemen', 'idIdentitasLab', 'tahunPengadaan', 'saranaLayak', 'saranaRusak', 'spesifikasi', 'bukti', 'kodeRincianLabAset', 'hargaBeli', 'merk', 'type', 'warna', 'noSeri', 'nomorBarang', 'status', 'sectionAset','tanggalPemusnahan' , 'namaAkun', 'kodeAkun'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    // GENERAL FUNCTION
    public function isDuplicate($kodeRincianLabAset) {
        $builder = $this->db->table($this->table);
        return $builder->where('kodeRincianLabAset', $kodeRincianLabAset)
            ->countAllResults() > 0;
    }

    public function getSelectedRows($selectedRows) {
        $builder = $this->db->table($this->table);
        $builder->select('tblRincianLabAset.*, tblIdentitasSarana.*, tblSumberDana.*, tblKategoriManajemen.*, tblIdentitasLab.*');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->whereIn('tblRincianLabAset.idRincianLabAset', $selectedRows);
    
        return $builder->get()->getResult();
    }

    function find($id = null, $columns = '*') {
        $builder = $this->db->table($this->table);
        $builder->select($columns);
        
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        
        $builder->where($this->primaryKey, $id);

        $query = $builder->get();
        return $query->getRow();
    }

    public function updateKodeRincianLabAset($idRincianLabAset, $newKodeRincianLabAset) {
        $data = [
            'kodeRincianLabAset' => $newKodeRincianLabAset,
        ];

        $builder = $this->db->table($this->table);
        $builder->where('idRincianLabAset', $idRincianLabAset);
        $builder->update($data);
    }

    public function updateSectionAset($idRincianLabAset, $newSectionAset, $namaAkun, $kodeAkun) {
        if (in_array($newSectionAset, ["Dipinjam", "Dimusnahkan", "None"])) {
            $data = ['sectionAset' => $newSectionAset];

            if ($newSectionAset === 'Dimusnahkan') {
                $data['tanggalPemusnahan'] = date('Y-m-d H:i:s');
                $data['namaAkun'] = $namaAkun;
                $data['kodeAkun'] = $kodeAkun; 
            } else if ($newSectionAset === 'None') {
                $data['tanggalPemusnahan'] = NULL;
                $data['namaAkun'] = NULL;
                $data['kodeAkun'] = NULL;
            }
            $this->update($idRincianLabAset, $data);
            return true;
        }
        return false;
    }
    
    // SARANA - DATA GENERAL
    function getDataBySarana() {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasSarana.*');
        $builder->select('COUNT(*) AS jumlahTotal', false);
        $builder->select('SUM(1) AS jumlahAset', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Bagus" THEN 1 ELSE 0 END) AS jumlahBagus', false);
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Rusak" THEN 1 ELSE 0 END) AS jumlahRusak', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Hilang" THEN 1 ELSE 0 END) AS jumlahHilang', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.sectionAset = "Dipinjam" THEN 1 ELSE 0 END) AS jumlahDipinjam', false); 
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataBySaranaDetail($id = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblIdentitasSarana.idIdentitasSarana', $id);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $query = $builder->get();
        return $query->getResult();
    }

    function getTotalSarana($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('COUNT(*) as totalSarana');
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.deleted_at', null); 
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
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $builder->where('status', 'Bagus');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }

    function getSaranaRusak($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $builder->where('status', 'Rusak');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }

    function getSaranaHilang($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('idIdentitasSarana', $idIdentitasSarana);
        $builder->where('status', 'Hilang');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }

    // SARANA - RINCIAN ASET
    // For another controller
    function getAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->orderBy('idRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    // For index so sorthing by koderincianaset
    function getData() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->orderBy('kodeRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataBagus() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status =', 'Bagus');
        $builder->orderBy('tblRincianLabAset.kodeRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataRusak() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status =', 'Rusak');
        $builder->orderBy('tblRincianLabAset.kodeRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getDataHilang() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status =', 'Hilang');
        $builder->orderBy('tblRincianLabAset.kodeRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at IS NOT NULL');
        $query = $builder->get();
        return $query->getResult();
    }

    // SARANA - PEMUSNAHAN ASET

    function getDestroy($startDate = null, $endDate = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset =', 'Dimusnahkan');
        $builder->orderBy('tanggalPemusnahan', 'desc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRincianLabAset.tanggalPemusnahan >=', $startDate);
            $builder->where('tblRincianLabAset.tanggalPemusnahan <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }

// ================================================================================================ //
    // IT - DATA GENERAL
    function getDataItBySarana() {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasSarana.*');
        $builder->select('COUNT(*) AS jumlahTotal', false);
        $builder->select('SUM(1) AS jumlahAset', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Bagus" THEN 1 ELSE 0 END) AS jumlahBagus', false);
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Rusak" THEN 1 ELSE 0 END) AS jumlahRusak', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.status = "Hilang" THEN 1 ELSE 0 END) AS jumlahHilang', false); 
        $builder->select('SUM(CASE WHEN tblRincianLabAset.sectionAset = "Dipinjam" THEN 1 ELSE 0 END) AS jumlahDipinjam', false); 
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana');
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataItBySaranaDetail($id = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblIdentitasSarana.idIdentitasSarana', $id);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblIdentitasSarana.perangkatIT', 1);
        $query = $builder->get();
        return $query->getResult();
    }

    
    function getTotalItSarana($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->select('COUNT(*) as totalSarana');
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.deleted_at', null); 
        $builder->where('tblIdentitasSarana.perangkatIT', 1);
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $query = $builder->get();
        $result = $query->getRow();
    
        if ($result) {
            return $result->totalSarana;
        } else {
            return 0;
        }
    }
    
    function getSaranaItLayak($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblIdentitasSarana.perangkatIT', 1);
        $builder->where('status', 'Bagus');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }

    function getSaranaItRusak($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblIdentitasSarana.perangkatIT', 1);
        $builder->where('status', 'Rusak');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }

    function getSaranaItHilang($idIdentitasSarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->select('COUNT(*) as count');
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.idIdentitasSarana', $idIdentitasSarana);
        $builder->where('tblIdentitasSarana.perangkatIT', 1);
        $builder->where('status', 'Hilang');
        $query = $builder->get();
        $result = $query->getRow();

        return $result ? $result->count : 0;
    }

    // IT - RINCIAN ASET
    
    function getItAll() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblIdentitasSarana.perangkatIT', 1);
        $builder->orderBy('idRincianLabAset', 'asc');  
        $query = $builder->get();
        return $query->getResult();
    }

    function getItData() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $builder->orderBy('kodeRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getItRecycle() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at IS NOT NULL');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataItBagus() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status =', 'Bagus');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $builder->orderBy('tblRincianLabAset.kodeRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataItRusak() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status =', 'Rusak');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $builder->orderBy('tblRincianLabAset.kodeRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }
    
    function getDataItHilang() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status =', 'Hilang');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $builder->orderBy('tblRincianLabAset.kodeRincianLabAset', 'asc'); 
        $query = $builder->get();
        return $query->getResult();
    }

    // IT - PEMUSNAHAN ASET

    function getDestroyIt($startDate = null, $endDate = null) {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset =', 'Dimusnahkan');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $builder->orderBy('tanggalPemusnahan', 'desc'); 
        if ($startDate !== null && $endDate !== null) {
            $builder->where('tblRincianLabAset.tanggalPemusnahan >=', $startDate);
            $builder->where('tblRincianLabAset.tanggalPemusnahan <=', $endDate);
        }
        $query = $builder->get();
        return $query->getResult();
    }
}
