<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit ManajemenUser &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">ManajemenUser</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Edit Password</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('manajemenUser/updatePassword/'.$dataManajemenUser->idUser)?>" method="post" autocomplete="off"  id="custom-validation">
                    <div class="row mb-3">
                        <label for="password" class="col-sm-3 col-form-label">Password</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="password" name="password" placeholder="Masukkan Password Baru" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="konfirmasiPassword" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="konfirmasiPassword" name="konfirmasiPassword" placeholder="Masukkan konfirmasi password" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('manajemenUser') ?>" class="btn btn-secondary me-2">Cancel</a>
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