<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Non Inventaris &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Data Master</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('nonInventaris')?>">Non Inventaris</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('nonInventaris/'.$dataNonInventaris->idNonInventaris)?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="nama" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?=$dataNonInventaris->nama?>" placeholder="Masukkan Nama Sarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="satuan" class="col-sm-3 col-form-label">Satuan</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="satuan" name="satuan" value="<?=$dataNonInventaris->satuan?>" placeholder="Masukkan Nama Sarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('nonInventaris') ?>" class="btn btn-secondary me-2">Cancel</a>
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