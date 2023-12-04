<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Sosial Media &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Sosial Media</h4>
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
                <form action="<?= site_url('sosialMedia/'.$dataSosialMedia->idSosialMedia)?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="namaSosialMedia" class="col-sm-3 col-form-label">Aplikasi Sosial Media</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaSosialMedia" name="namaSosialMedia" value="<?=$dataSosialMedia->namaSosialMedia?>" placeholder="Masukkan Nama Sarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="usernameSosialMedia" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="usernameSosialMedia" name="usernameSosialMedia" value="<?=$dataSosialMedia->usernameSosialMedia?>" placeholder="Masukkan Nama Sarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="linkSosialMedia" class="col-sm-3 col-form-label">Link</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="linkSosialMedia" name="linkSosialMedia" value="<?=$dataSosialMedia->linkSosialMedia?>" placeholder="Masukkan Nama Sarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="picSosialMedia" class="col-sm-3 col-form-label">PIC</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="picSosialMedia" name="picSosialMedia" value="<?=$dataSosialMedia->picSosialMedia?>" placeholder="Masukkan Nama Sarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('sosialMedia') ?>" class="btn btn-secondary me-2">Cancel</a>
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