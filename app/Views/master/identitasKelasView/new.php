<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Identitas Kelas &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Data Master</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('identitasKelas')?>">Identitas Kelas</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <form action="<?= site_url('identitasKelas')?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <div class="row mb-3">
                        <label for="namaKelas" class="col-sm-3 col-form-label">Identitas Kelas</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaKelas" name="namaKelas" placeholder="Masukkan Identitas Kelas">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('identitasKelas') ?>" class="btn btn-secondary me-2">Cancel</a>
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