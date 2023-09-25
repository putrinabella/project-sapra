<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Status Layanan &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Status Layanan</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Edit Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('statusLayanan/update/'.$dataStatusLayanan->idStatusLayanan)?>" method="post" autocomplete="off" id="custom-validation">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="namaStatusLayanan" class="col-sm-3 col-form-label">Nama Status Layanan</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaStatusLayanan" name="namaStatusLayanan" value="<?=$dataStatusLayanan->namaStatusLayanan?>" placeholder="Masukkan Nama Lantai" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('statusLayanan') ?>" class="btn btn-secondary me-2">Cancel</a>
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