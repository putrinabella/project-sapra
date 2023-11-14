<?php

namespace App\Models;

use CodeIgniter\Model;

class HomeModels extends Model
{
    protected $table            = 'tblIdentitasPrasarana';
    protected $primaryKey       = 'idIdentitasSarana';
    protected $returnType       = 'object';
    protected $allowedFields    = ['namaSarana', 'luas', 'idIdentitasGedung', 'idIdentitasLantai', 'idIdentitasSarana'];
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = true;

    
    function getData() {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
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
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianAset.sectionAset != "dimusnahkan" AND tblRincianAset.sectionAset = "Dimusnahkan" 
        THEN 1 ELSE 0 END) as saranaDimusnahkan');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataIt() {
        $builder = $this->db->table('tblRincianAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianAset.idIdentitasSarana');
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
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianAset.sectionAset != "dimusnahkan" AND tblRincianAset.sectionAset = "Dimusnahkan" 
        THEN 1 ELSE 0 END) as saranaDimusnahkan');
        $builder->where('tblRincianAset.deleted_at', null);
        $builder->where('tblRincianAset.sectionAset !=', 'Dimusnahkan');
        $builder->where('tblIdentitasSarana.perangkatIT', 1); 
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataLab() {
        $builder = $this->db->table('tblRincianLabAset');
        $builder->join('tblIdentitasSarana', 'tblIdentitasSarana.idIdentitasSarana = tblRincianLabAset.idIdentitasSarana');
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
        $builder->select('tblIdentitasSarana.idIdentitasSarana, tblIdentitasSarana.namaSarana, SUM(CASE 
        WHEN tblRincianLabAset.sectionAset != "dimusnahkan" AND tblRincianLabAset.sectionAset = "Dimusnahkan" 
        THEN 1 ELSE 0 END) as saranaDimusnahkan');
        $builder->where('tblRincianLabAset.deleted_at', null);
        $builder->where('tblRincianLabAset.sectionAset !=', 'Dimusnahkan');
        $builder->groupBy('tblIdentitasSarana.idIdentitasSarana'); 
        $query = $builder->get();
        return $query->getResult();
    }

    function getDataInventaris(){
        $builder = $this->db->table('tblDataInventaris');
        $builder->join('tblInventaris', 'tblInventaris.idInventaris = tblDataInventaris.idInventaris');
        $builder->select('tblDataInventaris.*, tblInventaris.namaInventaris, tblInventaris.satuan'); 
        $builder->select('SUM(CASE WHEN tblDataInventaris.tipeDataInventaris = "Pemasukan" THEN tblDataInventaris.jumlahDataInventaris ELSE 0 END) as inventarisMasuk');
        $builder->select('SUM(CASE WHEN tblDataInventaris.tipeDataInventaris = "Pengeluaran" THEN tblDataInventaris.jumlahDataInventaris ELSE 0 END) as inventarisKeluar');
        $builder->where('tblDataInventaris.deleted_at', null);
        $builder->groupBy('tblInventaris.idInventaris'); 
        $query = $builder->get();
        return $query->getResult();
    }
    

}
