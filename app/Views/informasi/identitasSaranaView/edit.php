<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Identitas Sarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Identitas Sarana</h4>
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
                <form action="<?= site_url('identitasSarana/update/'.$dataIdentitasSarana->idIdentitasSarana)?>" method="post" autocomplete="off"  id="custom-validation">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="namaSarana" class="col-sm-3 col-form-label">Nama Identitas Sarana</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaSarana" name="namaSarana" value="<?=$dataIdentitasSarana->namaSarana?>" placeholder="Masukkan Nama Sarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('identitasSarana') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Undo</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>