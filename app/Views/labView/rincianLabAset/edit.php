<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Rincian Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Rincian Aset</h4>
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
                <div>
                    <?php if(session()->getFlashdata('success')) :?>
                    <div class="alert alert-success alert-dismissible show fade" role="alert" id="alert">
                        <div class="alert-body">
                            <b>Success!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="btn-close"></button>
                            <?=session()->getFlashdata('success')?>
                        </div>
                    </div>
                    <br>
                    <?php endif; ?>
                    <?php if(session()->getFlashdata('error')) :?>
                    <div class="alert alert-danger alert-dismissible show fade" role="alert" id="alert">
                        <div class="alert-body">
                            <b>Error!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="btn-close"></button>
                            <?=session()->getFlashdata('error')?>
                        </div>
                    </div>
                    <br>
                    <?php endif; ?>
                </div>
                <form action="<?= site_url('rincianLabAset/'.$dataRincianLabAset->idRincianLabAset)?>" method="post"
                    autocomplete="off" id="custom-validation"  enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idIdentitasSarana" name="idIdentitasSarana">
                                <option value="" hidden>Pilih aset</option>
                                <?php foreach($dataIdentitasSarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasSarana?>" <?=$dataRincianLabAset->idIdentitasSarana ==
                                    $value->idIdentitasSarana ? 'selected' : null ?>>
                                    <?=$value->namaSarana?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLab" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idIdentitasLab" name="idIdentitasLab">
                                <option value="" hidden>Pilih aset</option>
                                <?php foreach($dataIdentitasLab as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasLab?>" <?=$dataRincianLabAset->idIdentitasLab ==
                                    $value->idIdentitasLab ? 'selected' : null ?>>
                                    <?=$value->namaLab?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idSumberDana" name="idSumberDana">
                                <option value="" hidden>Pilih aset</option>
                                <?php foreach($dataSumberDana as $key =>$value): ?>
                                <option value="<?=$value->idSumberDana?>" <?=$dataRincianLabAset->idSumberDana ==
                                    $value->idSumberDana ? 'selected' : null ?>>
                                    <?=$value->namaSumberDana?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Manajemen</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idKategoriManajemen" name="idKategoriManajemen">
                                <option value="" hidden>Pilih aset</option>
                                <?php foreach($dataKategoriManajemen as $key =>$value): ?>
                                <option value="<?=$value->idKategoriManajemen?>" <?=$dataRincianLabAset->
                                    idKategoriManajemen == $value->idKategoriManajemen ? 'selected' : null ?>>
                                    <?=$value->namaKategoriManajemen?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tahunPengadaan" class="col-sm-3 col-form-label">Tahun Pengadaan</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="tahunPengadaan" name="tahunPengadaan"
                                value="<?=$dataRincianLabAset->tahunPengadaan?>" placeholder="Masukkan tahun pengadaan">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="saranaLayak" class="col-sm-3 col-form-label">Jumlah Aset Layak</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="saranaLayak" name="saranaLayak"
                                value="<?=$dataRincianLabAset->saranaLayak?>" placeholder="Masukkan jumlah aset layak">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="saranaRusak" class="col-sm-3 col-form-label">Jumlah Aset Rusak</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="saranaRusak" name="saranaRusak"
                                value="<?=$dataRincianLabAset->saranaRusak?>" placeholder="Masukkan jumlah aset rusak">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="spesifikasi" class="col-sm-3 col-form-label">Spesifikasi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="spesifikasi" name="spesifikasi" rows="10"
                                placeholder="Masukkan spesifikasi aset"><?=$dataRincianLabAset->spesifikasi?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti" value="<?=$dataRincianLabAset->bukti?>"  placeholder="Masukkan link bukti">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('rincianLabAset') ?>" class="btn btn-secondary me-2">Cancel</a>
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