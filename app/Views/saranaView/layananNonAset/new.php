<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Layanan Non Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Layanan Non Aset</h4>
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
                <form action="<?= site_url('saranaLayananNonAset')?>" method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
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
                        <label for="idIdentitasPrasarana" class="col-sm-3 col-form-label">Lokasi Aset</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true" id="idIdentitasPrasarana" name="idIdentitasPrasarana">
                                <option value="" selected disabled hidden>Pilih lokasi</option>
                                <?php foreach($dataIdentitasPrasarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasPrasarana?>"><?=$value->namaPrasarana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idStatusLayanan" class="col-sm-3 col-form-label">Status Layanan</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="2" tabindex="-1" aria-hidden="true" id="idStatusLayanan" name="idStatusLayanan">
                                <option value="" selected disabled hidden>Pilih status layanan</option>
                                <?php foreach($dataStatusLayanan as $key =>$value): ?>
                                <option value="<?=$value->idStatusLayanan?>"><?=$value->namaStatusLayanan?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="3" tabindex="-1" aria-hidden="true" id="idSumberDana" name="idSumberDana">
                                <option value="" selected disabled hidden>Pilih sumber dana</option>
                                <?php foreach($dataSumberDana as $key =>$value): ?>
                                <option value="<?=$value->idSumberDana?>"><?=$value->namaSumberDana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriMep" class="col-sm-3 col-form-label">Kategori MEP</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="4" tabindex="-1" aria-hidden="true" id="idKategoriMep" name="idKategoriMep">
                                <option value="" selected disabled hidden>Pilih Kategori MEP</option>
                                <?php foreach($dataKategoriMep as $key =>$value): ?>
                                <option value="<?=$value->idKategoriMep?>"><?=$value->namaKategoriMep?>
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
                            <input type="text" class="form-control" id="bukti" name="bukti" placeholder="Masukkan link dokumentasi (Link Google Drive)">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="spesifikasi" class="col-sm-3 col-form-label">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="spesifikasi" name="spesifikasi" rows="5" placeholder="Masukkan keterangan" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('saranaLayananNonAset') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>


<?= $this->endSection(); ?>