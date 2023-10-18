<?php

namespace App\Models;

use CodeIgniter\Model;

class LaboratoriumModels extends Model
{
    protected $table            = 'tblIdentitasLab';
    protected $primaryKey       = 'idIdentitasLab';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaLab', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'kodeLab'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getRuangan() {
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdentitasGedung($idIdentitasLab) {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasGedung.namaGedung');
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasLab.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasLab.idIdentitasLantai');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $query = $builder->get();
    
        return $query->getRow();
    }
    
    function getIdentitasLantai($idIdentitasLab) {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasLantai.namaLantai');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasLab.idIdentitasLantai');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $query = $builder->get();
    
        return $query->getRow();
    }

    function getSaranaByLabId($idIdentitasLab) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.kodeLab = tblIdentitasLab.kodeLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null); 
        $query = $builder->get();
    
        return $query->getResult();
    }

    public function getSaranaByPrasarana($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('tblRincianLabAset.kodeLab');
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.kodeLab = tblIdentitasLab.kodeLab');
        $builder->where('tblRincianLabAset.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $query = $builder->get();

        return $query->getResult();
    }

    // WORK WORK WORK
    public function getSaranaByLab1($idIdentitasLab) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(tblRincianLabAset.saranaLayak) as totalSaranaLayak');
        $builder->select('tblRincianLabAset.idRincianLabAset');
        $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.kodeLab = tblRincianLabAset.kodeLab AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
        $builder->select('SUM(tblRincianLabAset.saranaLayak) - (SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.kodeLab = tblRincianLabAset.kodeLab AND tblManajemenPeminjaman.status = "peminjaman") as asetTersedia', false);
        $builder->select('(SELECT SUM(jumlahBarangRusak + jumlahBarangHilang) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.kodeLab = tblRincianLabAset.kodeLab AND tblManajemenPeminjaman.status = "pengembalian") as asetTidakTersedia', false);
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.kodeLab = tblIdentitasLab.kodeLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        
        $query = $builder->get();

        return $query->getResult();
    }

    // using total sarana (percobaan)
    public function getSaranaByLab($idIdentitasLab) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(tblRincianLabAset.totalSarana) as totalSarana');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(tblRincianLabAset.saranaRusak) as saranaRusak');
        $builder->select('tblRincianLabAset.idRincianLabAset');
        $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.kodeLab = tblRincianLabAset.kodeLab AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
        // $builder->select('SUM(tblRincianLabAset.saranaLayak) - (SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.kodeLab = tblRincianLabAset.kodeLab AND tblManajemenPeminjaman.status = "peminjaman") as asetTersedia', false);
        $builder->select('(SELECT SUM(jumlahBarangRusak + jumlahBarangHilang) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.kodeLab = tblRincianLabAset.kodeLab AND tblManajemenPeminjaman.status = "pengembalian") as asetTidakTersedia', false);
        $builder->select('SUM(tblRincianLabAset.saranaRusak) as saranaRusak', false);
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.kodeLab = tblIdentitasLab.kodeLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        
        $query = $builder->get();
    
        return $query->getResult();
    }
    

    

    function getAll() {
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        return $query->getResult();
    }
}


// public function getSaranaByLab($idIdentitasLab) {
//     $builder = $this->db->table('tblRincianLabAset');
//     $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(tblRincianLabAset.saranaLayak) as totalSaranaLayak');
//     $builder->select('tblRincianLabAset.idRincianLabAset');
//     $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.kodeLab = tblRincianLabAset.kodeLab AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
//     $builder->select('(SUM(tblRincianLabAset.saranaLayak) - COALESCE(SUM(tblManajemenPeminjaman.jumlahBarangRusak), 0) - COALESCE(SUM(tblManajemenPeminjaman.jumlahBarangDikembalikan), 0)) as asetTersedia', false);

//     $builder->join('tblIdentitasLab', 'tblRincianLabAset.kodeLab = tblIdentitasLab.kodeLab');
//     $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
//     $builder->join('tblManajemenPeminjaman', 'tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.kodeLab = tblRincianLabAset.kodeLab', 'left');

//     $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
//     $builder->where('tblRincianLabAset.deleted_at', null);
//     $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');

//     $query = $builder->get();
//     return $query->getResult();
// }
