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
        $builder = $this->db->table('tblRincianPrasaranaAset');
        $builder->where('tblRincianPrasaranaAset.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianPrasaranaAset.deleted_at', null);
        $builder->where('tblRincianPrasaranaAset.sectionAset !=', 'Dimusnahkan');
        $builder->select('COUNT(*) AS jumlahTotal', false);
        $query = $builder->get();
        $result = $query->getRow();
    
        return $result->jumlahTotal ?? 0;
    }
    
    
    function getData() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianPrasaranaAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianPrasaranaAset.idKategoriManajemen');
        $builder->join('tblIdentitasPrasarana', 'tblIdentitasPrasarana.idIdentitasPrasarana = tblRincianPrasaranaAset.idIdentitasPrasarana');
        $builder->where('tblRincianPrasaranaAset.deleted_at', null);
        $builder->where('tblRincianPrasaranaAset.sectionAset !=', 'Dimusnahkan');
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
        $builder = $this->db->table('tblRincianPrasaranaAset');
        $builder->join('tblIdentitasPrasarana', 'tblRincianPrasaranaAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianPrasaranaAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianPrasaranaAset.idKategoriManajemen');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianPrasaranaAset.deleted_at', null); 
        $builder->where('tblRincianPrasaranaAset.sectionAset !=', 'Dimusnahkan'); 
        $query = $builder->get();
    
        return $query->getResult();
    }

    // public function getSaranaByPrasarana($idIdentitasPrasarana) {
    //     $builder = $this->db->table('tblRincianPrasaranaAset');
    //     $builder->select('tblRincianPrasaranaAset.idIdentitasPrasarana');
    //     $builder->join('tblIdentitasPrasarana', 'tblRincianPrasaranaAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
    //     $builder->where('tblRincianPrasaranaAset.idIdentitasPrasarana', $idIdentitasPrasarana);
    //     $builder->where('tblRincianPrasaranaAset.deleted_at', null);
    //     $query = $builder->get();

    //     return $query->getResult();
    // }


    public function getSaranaByPrasarana($idIdentitasPrasarana) {
        $builder = $this->db->table('tblRincianPrasaranaAset');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianPrasaranaAset.sectionAset != "dimusnahkan" AND (tblRincianPrasaranaAset.status = "Bagus" OR tblRincianPrasaranaAset.status = "Rusak" OR tblRincianPrasaranaAset.status = "Hilang")
        THEN 1 ELSE 0 END) as totalSarana');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianPrasaranaAset.sectionAset != "dimusnahkan" AND tblRincianPrasaranaAset.status = "Bagus" 
        THEN 1 ELSE 0 END) as saranaLayak');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianPrasaranaAset.sectionAset != "dimusnahkan" AND tblRincianPrasaranaAset.status = "Rusak" 
        THEN 1 ELSE 0 END) as saranaRusak');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianPrasaranaAset.sectionAset != "dimusnahkan" AND tblRincianPrasaranaAset.status = "Hilang" 
        THEN 1 ELSE 0 END) as saranaHilang');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianPrasaranaAset.sectionAset != "dimusnahkan" AND tblRincianPrasaranaAset.sectionAset = "Dipinjam" 
        THEN 1 ELSE 0 END) as saranaDipinjam');
        $builder->select('tblRincianPrasaranaAset.idRincianPrasaranaAset');
        $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianPrasaranaAset.idIdentitasPrasarana AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
        $builder->select('(SELECT SUM(jumlahBarangRusak + jumlahBarangHilang) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianPrasaranaAset.idIdentitasPrasarana AND tblManajemenPeminjaman.status = "pengembalian") as asetTidakTersedia', false);
        $builder->join('tblIdentitasPrasarana', 'tblRincianPrasaranaAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianPrasaranaAset.deleted_at', null);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        
        $query = $builder->get();
    
        return $query->getResult();
    }
    
    public function getSaranaLayakCount($idIdentitasPrasarana)
    {
        $builder = $this->db->table('tblRincianPrasaranaAset');
        $builder->select('COUNT(idRincianPrasaranaAset) as saranaLayakCount');
        $builder->join('tblIdentitasPrasarana', 'tblRincianPrasaranaAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianPrasaranaAset.deleted_at', null);
        $builder->where('tblRincianPrasaranaAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianPrasaranaAset.status', 'Bagus');

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
        $builder = $this->db->table('tblRincianPrasaranaAset');
        $builder->select('COUNT(idRincianPrasaranaAset) as saranaRusakCount');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianPrasaranaAset.deleted_at', null);
        $builder->where('tblRincianPrasaranaAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianPrasaranaAset.status', 'Rusak');

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
        $builder = $this->db->table('tblRincianPrasaranaAset');
        $builder->select('COUNT(idRincianPrasaranaAset) as saranaHilangCount');
        $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
        $builder->where('tblRincianPrasaranaAset.deleted_at', null);
        $builder->where('tblRincianPrasaranaAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianPrasaranaAset.status', 'Hilang');

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
//     $builder = $this->db->table('tblRincianPrasaranaAset');
//     $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(tblRincianPrasaranaAset.saranaLayak) as totalSaranaLayak');
//     $builder->select('tblRincianPrasaranaAset.idRincianPrasaranaAset');
//     $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianPrasaranaAset.idIdentitasPrasarana AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
//     $builder->select('(SUM(tblRincianPrasaranaAset.saranaLayak) - COALESCE(SUM(tblManajemenPeminjaman.jumlahBarangRusak), 0) - COALESCE(SUM(tblManajemenPeminjaman.jumlahBarangDikembalikan), 0)) as asetTersedia', false);

//     $builder->join('tblIdentitasPrasarana', 'tblRincianPrasaranaAset.idIdentitasPrasarana = tblIdentitasPrasarana.idIdentitasPrasarana');
//     $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana');
//     $builder->join('tblManajemenPeminjaman', 'tblManajemenPeminjaman.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianPrasaranaAset.idIdentitasPrasarana', 'left');

//     $builder->where('tblIdentitasPrasarana.idIdentitasPrasarana', $idIdentitasPrasarana);
//     $builder->where('tblRincianPrasaranaAset.deleted_at', null);
//     $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');

//     $query = $builder->get();
//     return $query->getResult();
// }


        // $builder->select('SUM(tblRincianPrasaranaAset.saranaLayak) - (SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianPrasaranaAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasPrasarana = tblRincianPrasaranaAset.idIdentitasPrasarana AND tblManajemenPeminjaman.status = "peminjaman") as asetTersedia', false);