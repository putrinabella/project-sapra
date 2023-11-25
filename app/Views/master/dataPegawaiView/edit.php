<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Data Pegawai &verbar; SARPRA </title>
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
                <div class="d-flex justify-content-between">
                    <h4>Edit Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('dataPegawai/'.$dataDataPegawai->idDataPegawai)?>" method="post" autocomplete="off"
                    id="custom-validation">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="nip" class="col-sm-3 col-form-label">NIP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nip" name="nip" value="<?=$dataDataPegawai->nip?>"
                                placeholder="Masukkan NIP">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaPegawai" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaPegawai" name="namaPegawai"
                                value="<?=$dataDataPegawai->namaPegawai?>" placeholder="Masukkan Nama Sarana">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="idKategoriPegawai" class="col-sm-3 col-form-label">Kelas</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1" aria-hidden="true" id="idKategoriPegawai"
                                name="idKategoriPegawai">
                                <option value="" selected disabled hidden>Pilih kategori pegawai</option>
                                <?php foreach($dataKategoriPegawai as $value): ?>
                                <option value="<?= $value->idKategoriPegawai ?>" <?=$dataDataPegawai->idKategoriPegawai
                                    == $value->idKategoriPegawai ? 'selected' : '' ?>>
                                    <?= $value->namaKategoriPegawai ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
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