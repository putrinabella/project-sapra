<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Data Siswa &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Data Siswa</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('dataSiswa/'.$dataDataSiswa->idDataSiswa)?>" method="post" autocomplete="off"
                    id="custom-validation">
                    
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="nis" class="col-sm-3 col-form-label">NIS</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nis" name="nis" value="<?=$dataDataSiswa->nis?>"
                                placeholder="Masukkan NIS">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaSiswa" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaSiswa" name="namaSiswa"
                                value="<?=$dataDataSiswa->namaSiswa?>" placeholder="Masukkan Nama Sarana">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="idIdentitasKelas" class="col-sm-3 col-form-label">Kelas</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1" aria-hidden="true" id="idIdentitasKelas"
                                name="idIdentitasKelas">
                                <option value="" selected disabled hidden>Pilih kelas</option>
                                <?php foreach($dataIdentitasKelas as $value): ?>
                                <option value="<?= $value->idIdentitasKelas ?>" <?=$dataDataSiswa->idIdentitasKelas
                                    == $value->idIdentitasKelas ? 'selected' : '' ?>>
                                    <?= $value->namaKelas ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('dataSiswa') ?>" class="btn btn-secondary me-2">Cancel</a>
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