<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Identitas Prasarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Data Master</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('identitasPrasarana')?>">Identitas Prasarana</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">

            <div class="card-body">
                <div>
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
                <form action="<?= site_url('identitasPrasarana')?>" method="post" autocomplete="off"  id="custom-validation"  enctype="multipart/form-data">
                    
                    <div class="row mb-3">
                        <label for="kodePrasarana" class="col-sm-3 col-form-label">Kode Prasarana</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kodePrasarana" name="kodePrasarana"
                                placeholder="Masukkan kode prasarana">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaPrasarana" class="col-sm-3 col-form-label">Identitas Prasarana</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaPrasarana" name="namaPrasarana"
                                placeholder="Masukkan identitas prasarana">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tipe" class="col-sm-3 col-form-label">Tipe</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1"  aria-hidden="true" id="tipe" name="tipe">
                            <option value="" disabled hidden>Pilih tipe</option>
                            <option value="Ruangan" >Ruangan</option>
                            <option value="Non Ruangan" >Non Ruangan</option>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasGedung" class="col-sm-3 col-form-label">Lokasi Gedung</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="2"  aria-hidden="true" id="idIdentitasGedung" name="idIdentitasGedung">
                            <option value="" disabled hidden>Pilih lokasi gedung</option>
                            <?php foreach($dataIdentitasGedung as $key =>$value): ?>
                            <option value="<?=$value->idIdentitasGedung?>"><?=$value->namaGedung?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLantai" class="col-sm-3 col-form-label">Lokasi Lantai</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="3"  aria-hidden="true" id="idIdentitasLantai" name="idIdentitasLantai">
                            <option value="" disabled hidden>Pilih lokasi lantai</option>
                            <?php foreach($dataIdentitasLantai as $key =>$value): ?>
                            <option value="<?=$value->idIdentitasLantai?>"><?=$value->namaLantai?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="luas" class="col-sm-3 col-form-label">Luas</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="luas" name="luas"
                                placeholder="Masukkan luas (satuan meter)">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="picturePath" class="col-sm-3 col-form-label">Foto</label>
                        <div class="col-sm-9">
                            <input type="file" class="form-control" id="picturePath" name="picturePath"
                                placeholder="Masukkan foto prasarana">
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