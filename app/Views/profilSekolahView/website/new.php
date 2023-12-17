<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Website &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Platform Digital</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('website')?>">Website</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('website')?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <div class="row mb-3">
                        <label for="namaWebsite" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaWebsite" name="namaWebsite"
                                placeholder="Masukkan website">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="fungsiWebsite" class="col-sm-3 col-form-label">Fungsi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="fungsiWebsite" name="fungsiWebsite"
                                placeholder="Masukkan fungsi website">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="linkWebsite" class="col-sm-3 col-form-label">Link</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="linkWebsite" name="linkWebsite"
                                placeholder="Masukkan link website">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="picWebsite" class="col-sm-3 col-form-label">PIC</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="picWebsite" name="picWebsite"
                                placeholder="Masukkan nama PIC">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('website') ?>" class="btn btn-secondary me-2">Cancel</a>
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