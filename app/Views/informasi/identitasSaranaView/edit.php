<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Identitas Sarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<!-- <nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Informasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Identitas Sarana</li>
    </ol>
</nav> -->

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
                    <div class="secion-header-back">
                        <a href="<?= site_url('identitasSarana')?>" class="btn btn-outline-primary btn-icon-text">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('identitasSarana/'.$dataIdentitasSarana->idIdentitasSarana)?>" method="post" autocomplete="off" was-validate>
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PUT">
                    <div class="row mb-3">
                        <label for="namaSarana" class="col-sm-3 col-form-label">Nama Sarana</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaSarana" name="namaSarana" value="<?=$dataIdentitasSarana->namaSarana?>" placeholder="Masukkan Nama Sarana" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('identitasSarana') ?>" class="btn btn-secondary me-2">Cancel</a>
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