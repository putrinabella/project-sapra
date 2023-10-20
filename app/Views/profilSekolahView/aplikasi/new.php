<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Aplikasi &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Aplikasi</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Tambah Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('aplikasi')?>" method="post" autocomplete="off"  id="custom-validation">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="namaAplikasi" class="col-sm-3 col-form-label">Aplikasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaAplikasi" name="namaAplikasi"
                                placeholder="Masukkan nama aplikasi">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="picAplikasi" class="col-sm-3 col-form-label">PIC</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="picAplikasi" name="picAplikasi"
                                placeholder="Masukkan nama PIC">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('aplikasi') ?>" class="btn btn-secondary me-2">Cancel</a>
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