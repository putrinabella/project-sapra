<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Data Pegawai &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Data Pegawai</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('dataPegawai')?>" method="post" autocomplete="off"  id="custom-validation">
                    
                    <div class="row mb-3">
                        <label for="nis" class="col-sm-3 col-form-label">NIP</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="nis" name="nis"
                                placeholder="Masukkan NIP">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaSiswa" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaSiswa" name="namaSiswa"
                                placeholder="Masukkan nama">
                            <input type="text" class="form-control" id="idIdentitasKelas" name="idIdentitasKelas"
                                value="1" hidden>
                        </div>
                    </div>        
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('dataPegawai') ?>" class="btn btn-secondary me-2">Cancel</a>
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