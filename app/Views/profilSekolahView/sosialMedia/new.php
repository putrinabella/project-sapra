<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Sosial Media &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Platform Digital</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('sosialMedia')?>">Sosial Media</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('sosialMedia')?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <div class="row mb-3">
                        <label for="namaSosialMedia" class="col-sm-3 col-form-label">Aplikasi Sosial Media</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaSosialMedia" name="namaSosialMedia"
                                placeholder="Masukkan nama aplikasi sosial media">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="usernameSosialMedia" class="col-sm-3 col-form-label">Username</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="usernameSosialMedia" name="usernameSosialMedia"
                                placeholder="Masukkan username sosial media">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="linkSosialMedia" class="col-sm-3 col-form-label">Link</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="linkSosialMedia" name="linkSosialMedia"
                                placeholder="Masukkan link sosial media">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="picSosialMedia" class="col-sm-3 col-form-label">PIC</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="picSosialMedia" name="picSosialMedia"
                                placeholder="Masukkan nama PIC">
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