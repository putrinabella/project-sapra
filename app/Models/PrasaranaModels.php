<?php

namespace App\Models;

use CodeIgniter\Model;

class PrasaranaModels extends Model
{
    protected $table            = 'tblIdentitasPrasarana';
    protected $primaryKey       = 'idIdentitasPrasarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaPrasarana', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'idIdentitasPrasarana'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    public function countDataByIdentitasPrasarana($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->where('tblRincianAset.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->select('COUNT(*) AS jumlahTotal', false);
        $query = $builder->get();
        $result = $query->getRow();
    
        return $result->jumlahTotal ?? 0;
    }
    
    
    function getData() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $query = $builder->get();
        return $query->getResult();
    }

    function getRuangan() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $query = $builder->get();
        return $query->getResult();
    }

    function getIdentitasGedung($idIdentitasPrasarana) {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasGedung.namaGedung');
        $builder->join('tblIdentitasGedung', 'tblIdentitasGedung.idIdentitasGedung = tblIdentitasPrasarana.idIdentitasGedung');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $query = $builder->get();
    
        return $query->getRow();
    }
    
    function getIdentitasLantai($idIdentitasPrasarana) {
        $builder = $this->db->table($this->table);
        $builder->select('tblIdentitasLantai.namaLantai');
        $builder->join('tblIdentitasLantai', 'tblIdentitasLantai.idIdentitasLantai = tblIdentitasPrasarana.idIdentitasLantai');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $query = $builder->get();
    
        return $query->getRow();
    }

    function getSaranaByPrasaranaId($idIdentitasPrasarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianAset.idKategoriManajemen');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.deleted_at', null); 
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan'); 
        $query = $builder->get();
    
        return $query->getResult();
    }

    // public function getSaranaByPrasarana($idIdentitasPrasarana) {
    //     $builder = $this->db->table('tblRincianAset');
    //     $builder->select('tblRincianAset.idIdentitasPrasarana');
    //     $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
    //     $builder->where('tblRincianAset.idIdentitasPrasarana', $idIdentitasPrasarana);
    //     $builder->where('tblRincianAset.deleted_at', null);
    //     $query = $builder->get();

    //     return $query->getResult();
    // }


    public function getSaranaByPrasarana($idIdentitasPrasarana) {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianAset.sectionAset != "dimusnahkan" AND (tblRincianAset.status = "Bagus" OR tblRincianAset.status = "Rusak" OR tblRincianAset.status = "Hilang")
        THEN 1 ELSE 0 END) as totalSarana');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianAset.sectionAset != "dimusnahkan" AND tblRincianAset.status = "Bagus" 
        THEN 1 ELSE 0 END) as saranaLayak');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianAset.sectionAset != "dimusnahkan" AND tblRincianAset.status = "Rusak" 
        THEN 1 ELSE 0 END) as saranaRusak');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianAset.sectionAset != "dimusnahkan" AND tblRincianAset.status = "Hilang" 
        THEN 1 ELSE 0 END) as saranaHilang');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianAset.sectionAset != "dimusnahkan" AND tblRincianAset.sectionAset = "Dipinjam" 
        THEN 1 ELSE 0 END) as saranaDipinjam');
        $builder->select('tblRincianAset.idRincianAset');
        $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
        $builder->select('(SELECT SUM(jumlahBarangRusak + jumlahBarangHilang) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana AND tblManajemenPeminjaman.status = "pengembalian") as asetTidakTersedia', false);
        $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        
        $query = $builder->get();
    
        return $query->getResult();
    }
    
    public function getSaranaLayakCount($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('COUNT(idRincianAset) as saranaLayakCount');
        $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianAset.status', 'Bagus');

        $query = $builder->get();
        $result = $query->getRow();

        if ($result) {
            return $result->saranaLayakCount;
        } else {
            return 0; 
        }
    }

    public function getSaranaRusakCount($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('COUNT(idRincianAset) as saranaRusakCount');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianAset.status', 'Rusak');

        $query = $builder->get();
        $result = $query->getRow();

        if ($result) {
            return $result->saranaRusakCount;
        } else {
            return 0; 
        }
    }

    public function getSaranaHilangCount($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianAset');
        $builder->select('COUNT(idRincianAset) as saranaHilangCount');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianAset.status', 'Hilang');

        $query = $builder->get();
        $result = $query->getRow();

        if ($result) {
            return $result->saranaHilangCount;
        } else {
            return 0; 
        }
    }
    

    function getAll() {
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        return $query->getResult();
    }
}


// public function getSaranaByPrasarana($idIdentitasPrasarana) {
//     $builder = $this->db->table('tblRincianAset');
//     $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(tblRincianAset.saranaLayak) as totalSaranaLayak');
//     $builder->select('tblRincianAset.idRincianAset');
//     $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
//     $builder->select('(SUM(tblRincianAset.saranaLayak) - COALESCE(SUM(tblManajemenPeminjaman.jumlahBarangRusak), 0) - COALESCE(SUM(tblManajemenPeminjaman.jumlahBarangDikembalikan), 0)) as asetTersedia', false);

//     $builder->join('tblIdentitasPrasarana', 'tblRincianAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
//     $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
//     $builder->join('tblManajemenPeminjaman', 'tblManajemenPeminjaman.idIdentitasSarana = tblRincianAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana', 'left');

//     $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
//     $builder->where('tblRincianAset.deleted_at', null);
//     $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');

//     $query = $builder->get();
//     return $query->getResult();
// }


        // $builder->select('SUM(tblRincianAset.saranaLayak) - (SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianAset.idIdentitasPrasarana AND tblManajemenPeminjaman.status = "peminjaman") as asetTersedia', false);