<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tagihan Internet &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sekolah</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tagihan Internet</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <form action="<?=site_url('tagihanInternet')?>" class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="startYearPicker">
                <input type="text" class="form-control border-primary" id="startYear" name="startYear" placeholder="Start Year" readonly>
                <span class="input-group-text input-group-addon border-primary"><i data-feather="calendar"></i></span>
            </div>
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="endYearPicker">
                <input type="text" class="form-control border-primary" id="endYear" name="endYear" placeholder="End Year" readonly>
                <span class="input-group-text input-group-addon border-primary"><i data-feather="calendar"></i></span>
            </div>
            <div class="col py-3 p-0 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-icon me-1">
                    <i data-feather="filter"></i>
                </button>
                <a href="<?= site_url('tagihanInternet') ?>" class="btn btn-secondary btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('tagihanInternet/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="trash"></i>
            Recycle Bin
        </a>
        <div class="dropdown">
            <?php
                if (empty($_GET['startYear']) && empty($_GET['endYear'])) {
                    $exportLink = site_url('tagihanInternet/export');
                    $generatePDFLink = site_url('tagihanInternet/generatePDF');
                } else {
                    $startYear = $_GET['startYear'] ?? '';
                    $endYear = $_GET['endYear'] ?? '';
                    $exportLink = site_url("tagihanInternet/export?startYear=$startYear&endYear=$endYear");
                    $generatePDFLink = site_url("tagihanInternet/generatePDF?startYear=$startYear&endYear=$endYear");
                }
            ?>
            <button class="btn btn-success btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Export File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= $exportLink ?>">Download as Excel</a>
                <a class="dropdown-item" target="_blank" href="<?= $generatePDFLink ?>">Download as PDF</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="btn btn-secondary btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="upload"></i>
                Import File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= site_url('tagihanInternet/createTemplate') ?>">Download Template</a>
                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#modalImport">Upload Excel</a>
            </div>
        </div>
        <a href="<?= site_url('tagihanInternet/new') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="edit"></i>
            Tambah Data
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
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
                <h4 class="text-center py-3">Tagihan Pemakaian Internet</h4>
                <?php if (!empty($tableHeading)) : ?>
                    <p class="text-center"><?= $tableHeading ?></p>
                <?php endif; ?>
                <br>
                <div class="table-responsive">
                    <table class="table table-hover"  id="dataTable" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th>Bulan, Tahun</th>
                                <th>Pemakaian</th>
                                <th>Biaya</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataTagihanInternet as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td style="text-align:center;">
                                    <?=$key + 1?>
                                </td>
                                <td> <?= $value->bulanPemakaianInternet;?>, <?= $value->tahunPemakaianInternet; ?>  </td>
                                <td><?= $value->pemakaianInternet; ?> GB</td>
                                <td><?=number_format($value->biaya, 0, ',', '.')?></td>
                                <td style="text-align:center;">
                                    <a href="<?=site_url('tagihanInternet/'.$value->idTagihanInternet.'/edit') ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form action="<?=site_url('tagihanInternet/'.$value->idTagihanInternet)?>"
                                        method="post" class="d-inline" id="del-<?= $value->idTagihanInternet;?>">
                                        
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-danger btn-icon" data-confirm="Apakah anda yakin menghapus data ini?">
                                            <i data-feather="trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Grafik Tagihan Pemakaian Internet</h6>
                <div id="apexBar"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Grafik Banyak Pemakaian Internet</h6>
                <div id="apexPemakaianInternet"></div> <!-- New chart container -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Import Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <form action="<?=site_url("tagihanInternet/import")?>" method="POST" enctype="multipart/form-data"  id="custom-validation">
                <div class="modal-body">
                    
                    <input class="form-control" type="file" id="formExcel" name="formExcel">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<?php if (isset($categories) && isset($biaya)) : ?>
    <div id="apexBar"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if (isset($categories) && isset($biaya)) : ?>
                
                var options = {
                    chart: {
                        type: 'bar',
                        height: '320',
                    },
                    series: [{
                        name: 'Biaya',
                        data: <?= json_encode($biaya); ?>
                    }],
                    xaxis: {
                        type: 'category',
                        categories: <?= json_encode($categories); ?>
                    },
                    dataLabels: {
                        enabled: false 
                    },
                    tooltip: {
                        enabled: true,
                        y: {
                            formatter: function (val) {
                                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(val);
                            }
                        }
                    }
                };

                var apexBarChart = new ApexCharts(document.querySelector("#apexBar"), options);
                apexBarChart.render();
            <?php endif; ?>
        });
    </script>

<?php else : ?>
    <p>No data available for the chart.</p>
<?php endif; ?>

<?php if (isset($categories) && isset($pemakaianInternet)) : ?>
    <div id="apexPemakaianInternet"></div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php if (isset($categories) && isset($pemakaianInternet)) : ?>
                var pemakaianInternetOptions = {
                    chart: {
                        type: 'bar',
                        height: '320',
                    },
                    series: [{
                        name: 'Pemakaian Internet',
                        data: <?= json_encode($pemakaianInternet); ?>
                    }],
                    xaxis: {
                        type: 'category',
                        categories: <?= json_encode($categories); ?>
                    },
                    dataLabels: {
                        enabled: false
                    },
                    tooltip: {
                        enabled: true,
                        y: {
                            formatter: function (val) {
                                return val.toLocaleString() + ' GB';
                            }
                        }
                    }
                };

                var apexPemakaianInternetChart = new ApexCharts(document.querySelector("#apexPemakaianInternet"), pemakaianInternetOptions);
                apexPemakaianInternetChart.render();
            <?php endif; ?>
        });
    </script>
<?php else : ?>
    <p>No data available for the Pemakaian Internet chart.</p>
<?php endif; ?>


<?= $this->endSection(); ?>