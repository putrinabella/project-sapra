<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Data Siswa &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Data Master</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('dataSiswa')?>">Data Siswa</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>



<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('dataSiswa')?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <div class="row mb-3">
                        <label for="nis" class="col-sm-3 col-form-label">NIS</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="nis" name="nis"
                                placeholder="Masukkan NIS">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaSiswa" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaSiswa" name="namaSiswa"
                                placeholder="Masukkan nama siswa">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasKelas" class="col-sm-3 col-form-label">Kelas</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1" aria-hidden="true" id="idIdentitasKelas" name="idIdentitasKelas">
                                <option value="" selected disabled hidden>Pilih kelas</option>
                                <?php foreach($dataIdentitasKelas as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasKelas?>"><?=$value->namaKelas?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>                   
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('dataSiswa') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>