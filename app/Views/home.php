<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Home &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Selamat Datang,
            <?= session('nama'); ?>!
        </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group date datepicker wd-200 me-2 mb-2 mb-md-0" id="dashboardDate">
            <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar"
                    class=" text-primary"></i></span>
            <input type="text" class="form-control border-primary bg-transparent">
        </div>
        <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="printer"></i>
            Print
        </button>
        <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="download-cloud"></i>
            Download Report
        </button>
    </div>

</div>


<?php if (session()->get('role') == 'Super Admin') { ?>
    <div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center mb-4">
                            <h4>Data Aset SMK Telkom Banjarbaru</h4>
                        </div>
                        <div class="row">
                            <div class="col-12 col-xl-12 stretch-card">
                                <div class="row flex-grow-1">
                                    <?php foreach ($dataRincianAset as $key => $value) : ?>
                                    <div class="col-md-4 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <div class="row">
                                                            <h6 class="card-title">
                                                                <?= $value->namaSarana; ?>
                                                            </h6>
                                                        </div>
                                                        <div class="row">
                                                            <div class="row text-center">
                                                                <h1>
                                                                    <?= $value->totalSarana; ?>
                                                                </h1>
                                                            </div>
                                                            <div class="row text-center">
                                                                <p>Buah</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="row">
                                                            <div class="box">
                                                                <div class="box-number">
                                                                    <?= $value->saranaLayak; ?>
                                                                </div>
                                                                <div class="box-label">Bagus</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="box">
                                                                <div class="box-number">
                                                                    <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                </div>
                                                                <div class="box-label">Rusak</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="box">
                                                                <div class="box-number">
                                                                    <?= $value->saranaDipinjam; ?>
                                                                </div>
                                                                <div class="box-label">Dipinjam</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center mb-4">
                            <h4>Data Aset Laboratorium</h4>
                        </div>
                        <div class="row">
                            <div class="col-12 col-xl-12 stretch-card">
                                <div class="row flex-grow-1">
                                    <?php foreach ($dataRincianAsetLab as $key => $value) : ?>
                                    <div class="col-md-4 grid-margin stretch-card">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <div class="row">
                                                            <h6 class="card-title">
                                                                <?= $value->namaSarana; ?>
                                                            </h6>
                                                        </div>
                                                        <div class="row">
                                                            <div class="row text-center">
                                                                <h1>
                                                                    <?= $value->totalSarana; ?>
                                                                </h1>
                                                            </div>
                                                            <div class="row text-center">
                                                                <p>Buah</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-5">
                                                        <div class="row">
                                                            <div class="box">
                                                                <div class="box-number">
                                                                    <?= $value->saranaLayak; ?>
                                                                </div>
                                                                <div class="box-label">Bagus</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="box">
                                                                <div class="box-number">
                                                                    <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                </div>
                                                                <div class="box-label">Rusak</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="box">
                                                                <div class="box-number">
                                                                    <?= $value->saranaDipinjam; ?>
                                                                </div>
                                                                <div class="box-label">Dipinjam</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?= $this->endSection(); ?>