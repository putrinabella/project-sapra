<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Tagihan Listrik &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Tagihan Listrik</h4>
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
                <form action="<?= site_url('tagihanListrik/'.$dataTagihanListrik->idTagihanListrik)?>" method="post" autocomplete="off"  id="custom-validation">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="pemakaianListrik" class="col-sm-3 col-form-label">Pemakaian Listrik</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="pemakaianListrik" name="pemakaianListrik"
                                placeholder="Masukkan pemakaian listrik (dalam kWh)" value="<?=$dataTagihanListrik->pemakaianListrik?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bulanPemakaianListrik" class="col-sm-3 col-form-label">Bulan</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true" id="bulanPemakaianListrik" name="bulanPemakaianListrik">
                                <option value="" selected disabled hidden>Pilih bulan</option>
                                <?php
                                $bulanPemakaianListrikOptions = [
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

                                $selectedValue = $dataTagihanListrik->bulanPemakaianListrik;
                                foreach ($bulanPemakaianListrikOptions as $value => $bulan): 
                                    $selected = ($value == $selectedValue) ? 'selected' : '';
                                    ?>
                                    <option value="<?= $value ?>" <?= $selected ?>><?= $bulan ?></option>
                                <?php endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tahunPemakaianListrik" class="col-sm-3 col-form-label">Tahun</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tahunPemakaianListrik">
                                <input type="number" class="form-control" name="tahunPemakaianListrik" value="<?=$dataTagihanListrik->tahunPemakaianListrik?>">
                                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="biaya" class="col-sm-3 col-form-label">Biaya Tagihan</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="biaya" name="biaya"
                                placeholder="Masukkan biaya tagihan" value="<?=$dataTagihanListrik->biaya?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('tagihanListrik') ?>" class="btn btn-secondary me-2">Cancel</a>
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