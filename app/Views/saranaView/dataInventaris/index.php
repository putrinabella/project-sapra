<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Inventaris &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Profil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Inventaris</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <form action="<?= site_url('dataInventaris') ?>" class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="startDatePicker">
                <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Start Date"
                    readonly>
                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
            </div>
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="endDatePicker">
                <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" readonly>
                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
            </div>
            <div class="col py-3 p-0 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-icon me-1">
                    <i data-feather="filter"></i>
                </button>
                <a href="<?= site_url('dataInventaris') ?>" class="btn btn-success btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('dataInventaris/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="trash"></i>
            Recycle Bin
        </a>
        <div class="dropdown">
            <button class="btn btn-success btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Export File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= site_url('dataInventaris/export') ?>">Download as Excel</a>
                <a class="dropdown-item" href="<?= site_url('dataInventaris/generatePDF') ?>">Download as PDF</a>
            </div>
        </div>
        <a href="<?= site_url('dataInventaris/new') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
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
                <div>
                    <h4 class="text-center py-3">Data Inventaris</h4>
                    <?php if (!empty($tableHeading)) : ?>
                    <p class="text-center">
                        <?= $tableHeading ?>
                    </p>
                    <?php endif; ?>
                    <br>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover text-center" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>Satuan</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataDataInventaris as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td>
                                    <?= $value->tanggalDataInventaris; ?>
                                </td>
                                <td>
                                    <?= $value->namaInventaris; ?>
                                </td>
                                <td>
                                    <?= $value->satuan; ?>
                                </td>
                                <td>
                                    <?= $value->tipeDataInventaris; ?>
                                </td>
                                <td>
                                    <?= $value->jumlahDataInventaris; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?=site_url('dataInventaris/'.$value->idDataInventaris.'/edit') ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form action="<?=site_url('dataInventaris/'.$value->idDataInventaris)?>"
                                        method="post" class="d-inline" id="del-<?= $value->idDataInventaris;?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-danger btn-icon"
                                            data-confirm="Apakah anda yakin menghapus data ini?">
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

<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Import Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <form action="<?=site_url(" dataInventaris/import")?>" method="POST" enctype="multipart/form-data"
                id="custom-validation">
                <div class="modal-body">
                    <?= csrf_field() ?>
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
<!-- <div class="row">
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Grafik Inventaris Masuk</h6>
                <div id="apexBar"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Grafik Inventaris Keluar</h6>
                <div id="apexPemakaianListrik"></div> 
            </div>
        </div>
    </div>
</div> -->


<?= $this->endSection(); ?>