<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Tagihan Internet &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('tagihanInternet')?>">Tagihan Internet</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <form action="<?= site_url('tagihanInternet')?>" method="post" autocomplete="off"  id="custom-validation">
                    <div class="row mb-3">
                        <label for="bulanPemakaianInternet" class="col-sm-3 col-form-label">Bulan</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1"  aria-hidden="true" id="bulanPemakaianInternet" name="bulanPemakaianInternet">
                            <option value="" selected disabled hidden>Pilih bulan</option>
                            <?php 
                            $bulanPemakaianInternetOptions = [
                                1 => "January",
                                2 => "February",
                                3 => "March",
                                4 => "April",
                                5 => "May",
                                6 => "June",
                                7 => "July",
                                8 => "August",
                                9 => "September",
                                10 => "October",
                                11 => "November",
                                12 => "December"
                            ];
                            foreach ($bulanPemakaianInternetOptions as $value => $bulan): ?>
                                <option value="<?= $value ?>"><?= $bulan ?></option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tahunPemakaianInternet" class="col-sm-3 col-form-label">Tahun</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tahunPemakaianInternet">
                                <input type="number" class="form-control" name="tahunPemakaianInternet">
                                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="pemakaianInternet" class="col-sm-3 col-form-label">Pemakaian Internet</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="pemakaianInternet" name="pemakaianInternet"
                                placeholder="Masukkan pemakaian internet">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="biaya" class="col-sm-3 col-form-label">Biaya Tagihan</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="biaya" name="biaya"
                                placeholder="Masukkan biaya tagihan">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('tagihanInternet') ?>" class="btn btn-secondary me-2">Cancel</a>
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