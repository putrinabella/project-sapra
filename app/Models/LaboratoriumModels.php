<?php

namespace App\Models;

use CodeIgniter\Model;

class LaboratoriumModels extends Model
{
    protected $table            = 'tblIdentitasLab';
    protected $primaryKey       = 'idIdentitasLab';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaLab', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'idIdentitasLab'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    function getData() {
        $builder = $this->db->table($this->table);
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->join('tblIdentitasLab', 'tblIdentitasLab.idIdentitasLab = tblRincianLabAset.idIdentitasLab');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $query = $builder->get();
        return $query->getResult();
    }

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
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->join('tblSumberDana', 'tblSumberDana.idSumberDana = tblRincianLabAset.idSumberDana');
        $builder->join('tblKategoriManajemen', 'tblKategoriManajemen.idKategoriManajemen = tblRincianLabAset.idKategoriManajemen');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null); 
        $query = $builder->get();
    
        return $query->getResult();
    }

    public function getSaranaByPrasarana($idIdentitasPrasarana) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('tblRincianLabAset.idIdentitasLab');
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->where('tblRincianLabAset.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $query = $builder->get();

        return $query->getResult();
    }


    public function getSaranaByLab($idIdentitasLab) {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianLabAset.sectionAset != "dimusnahkan" AND (tblRincianLabAset.status = "Bagus" OR tblRincianLabAset.status = "Rusak" OR tblRincianLabAset.status = "Hilang")
        THEN 1 ELSE 0 END) as totalSarana');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianLabAset.sectionAset != "dimusnahkan" AND tblRincianLabAset.status = "Bagus" 
        THEN 1 ELSE 0 END) as saranaLayak');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianLabAset.sectionAset != "dimusnahkan" AND tblRincianLabAset.status = "Rusak" 
        THEN 1 ELSE 0 END) as saranaRusak');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianLabAset.sectionAset != "dimusnahkan" AND tblRincianLabAset.status = "Hilang" 
        THEN 1 ELSE 0 END) as saranaHilang');
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianLabAset.sectionAset != "dimusnahkan" AND tblRincianLabAset.sectionAset = "Dipinjam" 
        THEN 1 ELSE 0 END) as saranaDipinjam');
        $builder->select('tblRincianLabAset.idRincianLabAset');
        $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasLab = tblRincianLabAset.idIdentitasLab AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
        $builder->select('(SELECT SUM(jumlahBarangRusak + jumlahBarangHilang) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasLab = tblRincianLabAset.idIdentitasLab AND tblManajemenPeminjaman.status = "pengembalian") as asetTidakTersedia', false);
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');
        
        $query = $builder->get();
    
        return $query->getResult();
    }
    
    public function getSaranaLayakCount($idIdentitasLab)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('COUNT(idRincianLabAset) as saranaLayakCount');
        $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status', 'Bagus');

        $query = $builder->get();
        $result = $query->getRow();

        if ($result) {
            return $result->saranaLayakCount;
        } else {
            return 0; 
        }
    }

    public function getSaranaRusakCount($idIdentitasLab)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('COUNT(idRincianLabAset) as saranaRusakCount');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status', 'Rusak');

        $query = $builder->get();
        $result = $query->getRow();

        if ($result) {
            return $result->saranaRusakCount;
        } else {
            return 0; 
        }
    }

    public function getSaranaHilangCount($idIdentitasLab)
    {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->select('COUNT(idRincianLabAset) as saranaHilangCount');
        $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblRincianLabAset.status', 'Hilang');

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


// public function getSaranaByLab($idIdentitasLab) {
//     $builder = $this->db->table('tblRincianLabAset');
//     $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(tblRincianLabAset.saranaLayak) as totalSaranaLayak');
//     $builder->select('tblRincianLabAset.idRincianLabAset');
//     $builder->select('(SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasLab = tblRincianLabAset.idIdentitasLab AND tblManajemenPeminjaman.status = "peminjaman") as jumlahPeminjaman', false);
//     $builder->select('(SUM(tblRincianLabAset.saranaLayak) - COALESCE(SUM(tblManajemenPeminjaman.jumlahBarangRusak), 0) - COALESCE(SUM(tblManajemenPeminjaman.jumlahBarangDikembalikan), 0)) as asetTersedia', false);

//     $builder->join('tblIdentitasLab', 'tblRincianLabAset.idIdentitasLab = tblIdentitasLab.idIdentitasLab');
//     $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
//     $builder->join('tblManajemenPeminjaman', 'tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasLab = tblRincianLabAset.idIdentitasLab', 'left');

//     $builder->where('tblIdentitasLab.idIdentitasLab', $idIdentitasLab);
//     $builder->where('tblRincianLabAset.deleted_at', null);
//     $builder->groupBy('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana');

//     $query = $builder->get();
//     return $query->getResult();
// }


        // $builder->select('SUM(tblRincianLabAset.saranaLayak) - (SELECT SUM(jumlah) FROM tblManajemenPeminjaman WHERE tblManajemenPeminjaman.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana AND tblManajemenPeminjaman.idIdentitasLab = tblRincianLabAset.idIdentitasLab AND tblManajemenPeminjaman.status = "peminjaman") as asetTersedia', false);