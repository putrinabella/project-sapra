<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Identitas Laboratorium &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Data Master</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('identitasLab')?>">Identitas Lab</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>



<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <form action="<?= site_url('identitasLab/'.$dataIdentitasLab->idIdentitasLab)?>"
                    method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="kodeLab" class="col-sm-3 col-form-label">Kode Lab</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kodeLab" name="kodeLab"
                                value="<?=$dataIdentitasLab->kodeLab?>"
                                placeholder="Masukkan kode lab">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaLab" class="col-sm-3 col-form-label">Nama Lab</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaLab" name="namaLab"
                                value="<?=$dataIdentitasLab->namaLab?>"
                                placeholder="Masukkan nama lab">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasGedung" class="col-sm-3 col-form-label">Lokasi Gedung</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1"  aria-hidden="true" id="idIdentitasGedung" name="idIdentitasGedung">  
                                <option value="" disabled hidden>Pilih lokasi gedung</option>
                                <?php foreach($dataIdentitasGedung as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasGedung?>" <?=$dataIdentitasLab->
                                    idIdentitasGedung == $value->idIdentitasGedung ? 'selected' : null ?>>
                                    <?=$value->namaGedung?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLantai" class="col-sm-3 col-form-label">Lokasi Lantai</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="2"  aria-hidden="true" id="idIdentitasLantai" name="idIdentitasLantai">
                                <option value="" disabled hidden>Pilih lokasi lantai</option>
                                <?php foreach($dataIdentitasLantai as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasLantai?>" <?=$dataIdentitasLab->
                                    idIdentitasLantai == $value->idIdentitasLantai ? 'selected' : null ?>>
                                    <?=$value->namaLantai?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="luas" class="col-sm-3 col-form-label">Luas</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="luas" name="luas"
                                value="<?=$dataIdentitasLab->luas?>" placeholder="Masukkan Luas">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="picturePath" class="col-sm-3 col-form-label">Foto</label>
                        <div class="col-sm-9">
                            <?php if ($dataIdentitasLab->picturePath): ?>
                            <div class="row">
                                <div class="col-sm-8">
                                    <b>Current Image:</b>
                                    <img src="<?= base_url($dataIdentitasLab->picturePath) ?>" alt="Current Image"
                                        style="max-width:100%;">
                                </div>
                                <div class="col-sm-4">
                                    <br>
                                    <span style="color: red; font-weight: bold;">Note:</span>
                                    <p style="text-align: justify;"> Jika ingin mengganti foto identitas lab,
                                        pilih file baru. Jika tidak, abaikan bagian ini. </p>
                                    <br>
                                    <br>
                                    <center> Upload foto baru disini:</center>
                                    <input type="file" class="form-control" id="picturePath" name="picturePath"
                                        accept="image/*">
                                </div>
                            </div>
                            <?php else: ?>
                            <div>
                                <input type="file" class="form-control" id="picturePath" name="picturePath"
                                    accept="image/*">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('identitasLab') ?>" class="btn btn-secondary me-2">Cancel</a>
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