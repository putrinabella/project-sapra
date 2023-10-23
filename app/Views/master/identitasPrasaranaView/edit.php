<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Identitas Prasarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Identitas Prasarana</h4>
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
                <form action="<?= site_url('identitasPrasarana/'.$dataIdentitasPrasarana->idIdentitasPrasarana)?>" method="post" autocomplete="off"  id="custom-validation">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="kodePrasarana" class="col-sm-3 col-form-label">Identitas Prasarana</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kodePrasarana" name="kodePrasarana"
                            value="<?=$dataIdentitasPrasarana->kodePrasarana?>"  placeholder="Masukkan kode prasarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaPrasarana" class="col-sm-3 col-form-label">Identitas Prasarana</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaPrasarana" name="namaPrasarana"
                            value="<?=$dataIdentitasPrasarana->namaPrasarana?>"  placeholder="Masukkan nama prasarana" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tipe" class="col-sm-3 col-form-label">Tipe</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="tipe" name="tipe">
                                <option value="" hidden>Pilih tipe</option>
                                <option value="Ruangan" <?= $dataIdentitasPrasarana->tipe == 'Ruangan' ? 'selected' : '' ?>>Ruangan</option>
                                <option value="Non Ruangan" <?= $dataIdentitasPrasarana->tipe == 'Non Ruangan' ? 'selected' : '' ?>>Non Ruangan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasGedung" class="col-sm-3 col-form-label">Lokasi Gedung</label>
                        <div class="col-sm-9">
                        <select class="form-select" id="idIdentitasGedung" name="idIdentitasGedung" >
                            <option value="" hidden>Pilih lokasi gedung</option>
                            <?php foreach($dataIdentitasGedung as $key =>$value): ?>
                            <option value="<?=$value->idIdentitasGedung?>"
                            <?=$dataIdentitasPrasarana->idIdentitasGedung == $value->idIdentitasGedung ? 'selected' : null ?>> 
                            <?=$value->namaGedung?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLantai" class="col-sm-3 col-form-label">Lokasi Lantai</label>
                        <div class="col-sm-9">
                        <select class="form-select" id="idIdentitasLantai" name="idIdentitasLantai" >
                            <option value="" hidden>Pilih lokasi lantai</option>
                            <?php foreach($dataIdentitasLantai as $key =>$value): ?>
                            <option value="<?=$value->idIdentitasLantai?>" 
                            <?=$dataIdentitasPrasarana->idIdentitasLantai == $value->idIdentitasLantai ? 'selected' : null ?>> 
                            <?=$value->namaLantai?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="luas" class="col-sm-3 col-form-label">Luas</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="luas" name="luas"
                            value="<?=$dataIdentitasPrasarana->luas?>"  placeholder="Masukkan Luas" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('identitasPrasarana') ?>" class="btn btn-secondary me-2">Cancel</a>
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