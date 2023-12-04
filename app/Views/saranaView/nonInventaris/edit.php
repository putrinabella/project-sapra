<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Data NonInventaris &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sarana</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('dataNonInventaris')?>">Non Inventaris</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Edit Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('dataNonInventaris/'.$dataNonInventaris->idDataNonInventaris)?>" method="post"
                    autocomplete="off" id="custom-validation">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tanggal">
                                <input type="text" class="form-control" name="tanggal"
                                    value="<?= $dataNonInventaris->tanggal; ?> " readonly>
                                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
      

                    <div class="row mb-3">
                        <label for="idNonInventaris" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1" aria-hidden="true" id="idNonInventaris"
                                name="idNonInventaris">
                                <option value="" selected disabled hidden>Pilih nama</option>
                                <?php foreach($nonInventaris as $value): ?>
                                <option value="<?= $value->idNonInventaris ?>" <?=$dataNonInventaris->idNonInventaris ==
                                    $value->idNonInventaris ? 'selected' : '' ?>>
                                    <?= $value->nama ?> (<?= $value->satuan; ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="tipe" class="col-sm-3 col-form-label">Tipe</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="2" aria-hidden="true" id="tipe"
                                name="tipe">
                                <option value="" selected disabled hidden>Pilih Tipe</option>
                                <option value="Pemasukan" <?=$dataNonInventaris->tipe == 'Pemasukan' ?
                                    'selected' : '' ?>>
                                    Pemasukan
                                </option>
                                <option value="Pengeluaran" <?=$dataNonInventaris->tipe == 'Pengeluaran'
                                    ? 'selected' : '' ?>>
                                    Pengeluaran
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jumlah" class="col-sm-3 col-form-label">Jumlah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="jumlah"
                                name="jumlah" value="<?= $dataNonInventaris->jumlah; ?> ">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('dataNonInventaris') ?>" class="btn btn-secondary me-2">Cancel</a>
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