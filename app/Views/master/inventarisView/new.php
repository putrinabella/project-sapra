<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Non Inventaris &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Non Inventaris</h4>
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
                <form action="<?= site_url('inventaris')?>" method="post" autocomplete="off"  id="custom-validation">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="namaInventaris" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaInventaris" name="namaInventaris"
                                placeholder="Masukkan inventaris">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="satuan" class="col-sm-3 col-form-label">Satuan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="satuan" name="satuan"
                                placeholder="Masukkan satuan">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('inventaris') ?>" class="btn btn-secondary me-2">Cancel</a>
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