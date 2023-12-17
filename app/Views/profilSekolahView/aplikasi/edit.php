<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Aplikasi &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Platform Digital</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('aplikasi')?>">Aplikasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('aplikasi/'.$dataAplikasi->idAplikasi)?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="namaAplikasi" class="col-sm-3 col-form-label">Nama Aplikasi</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaAplikasi" name="namaAplikasi" value="<?=$dataAplikasi->namaAplikasi?>" placeholder="Masukkan Nama Sarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="picAplikasi" class="col-sm-3 col-form-label">PIC</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="picAplikasi" name="picAplikasi" value="<?=$dataAplikasi->picAplikasi?>" placeholder="Masukkan Nama Sarana" >
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