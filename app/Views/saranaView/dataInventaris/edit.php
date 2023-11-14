<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Data Inventaris &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Data Inventaris</h4>
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
                <form action="<?= site_url('dataInventaris/'.$dataDataInventaris->idDataInventaris)?>" method="post"
                    autocomplete="off" id="custom-validation">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="tanggalDataInventaris" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tanggal">
                                <input type="text" class="form-control" name="tanggalDataInventaris"
                                    value="<?= $dataDataInventaris->tanggalDataInventaris; ?> " readonly>
                                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idInventaris" class="col-sm-3 col-form-label">Nama Inventaris</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true" id="idInventaris"
                                name="idInventaris">
                                <option value="" selected disabled hidden>Pilih lokasi</option>
                                <?php foreach($dataInventaris as $value): ?>
                                <option value="<?= $value->idInventaris ?>" <?=$dataDataInventaris->idInventaris ==
                                    $value->idInventaris ? 'selected' : '' ?>>
                                    <?= $value->namaInventaris ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tipeDataInventaris" class="col-sm-3 col-form-label">Tipe</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="2" aria-hidden="true" id="tipeDataInventaris"
                                name="tipeDataInventaris">
                                <option value="" selected disabled hidden>Pilih Tipe</option>
                                <option value="Pemasukan" <?=$dataDataInventaris->tipeDataInventaris == 'Pemasukan' ?
                                    'selected' : '' ?>>
                                    Pemasukan
                                </option>
                                <option value="Pengeluaran" <?=$dataDataInventaris->tipeDataInventaris == 'Pengeluaran'
                                    ? 'selected' : '' ?>>
                                    Pengeluaran
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jumlahDataInventaris" class="col-sm-3 col-form-label">Jumlah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="jumlahDataInventaris"
                                name="jumlahDataInventaris" value="<?= $dataDataInventaris->jumlahDataInventaris; ?> ">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('dataInventaris') ?>" class="btn btn-secondary me-2">Cancel</a>
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