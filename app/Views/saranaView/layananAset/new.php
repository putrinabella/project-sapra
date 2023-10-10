<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Layanan Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Layanan Aset</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Tambah Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('saranaLayananAset')?>" method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tanggal">
                                <input type="text" class="form-control" name="tanggal">
                                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idIdentitasSarana" name="idIdentitasSarana">
                                <option value="" hidden>Pilih aset</option>
                                <?php foreach($dataIdentitasSarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasSarana?>"><?=$value->namaSarana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasPrasarana" class="col-sm-3 col-form-label">Lokasi Aset</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idIdentitasPrasarana" name="idIdentitasPrasarana">
                                <option value="" hidden>Pilih lokasi</option>
                                <?php foreach($dataIdentitasPrasarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasPrasarana?>"><?=$value->namaPrasarana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idStatusLayanan" class="col-sm-3 col-form-label">Status Layanan</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idStatusLayanan" name="idStatusLayanan">
                                <option value="" hidden>Pilih status layanan</option>
                                <?php foreach($dataStatusLayanan as $key =>$value): ?>
                                <option value="<?=$value->idStatusLayanan?>"><?=$value->namaStatusLayanan?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idSumberDana" name="idSumberDana">
                                <option value="" hidden>Pilih sumber dana</option>
                                <?php foreach($dataSumberDana as $key =>$value): ?>
                                <option value="<?=$value->idSumberDana?>"><?=$value->namaSumberDana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Manajemen</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idKategoriManajemen" name="idKategoriManajemen">
                                <option value="" hidden>Pilih Kategori MEP</option>
                                <?php foreach($dataKategoriManajemen as $key =>$value): ?>
                                <option value="<?=$value->idKategoriManajemen?>"><?=$value->namaKategoriManajemen?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="biaya" class="col-sm-3 col-form-label">Biaya</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="biaya" name="biaya"
                                placeholder="Masukkan biaya">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti" placeholder="Masukkan link dokumentasi">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('saranaLayananAset') ?>" class="btn btn-secondary me-2">Cancel</a>
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