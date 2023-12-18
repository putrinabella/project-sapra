<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Kategori Manajemen &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Data Master</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('kategoriManajemen')?>">Kategori Manajemen</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>



<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('kategoriManajemen')?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <div class="row mb-3">
                        <label for="kodeKategoriManajemen" class="col-sm-3 col-form-label">Kode Kategori Barang</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="kodeKategoriManajemen" name="kodeKategoriManajemen" placeholder="Masukkan kode kategori barang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaKategoriManajemen" class="col-sm-3 col-form-label">Nama Kategori Barang</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaKategoriManajemen" name="namaKategoriManajemen" placeholder="Masukkan nama kategori barang">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('kategoriManajemen') ?>" class="btn btn-secondary me-2">Cancel</a>
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