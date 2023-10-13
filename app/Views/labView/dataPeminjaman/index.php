<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Manajemen Aset Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manajemen Aset Laboratorium</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Manajemen Aset Peminjaman</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('manajemenStok/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
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
                <a class="dropdown-item" href="<?= site_url('manajemenStok/export') ?>">Download as Excel</a>
                <a class="dropdown-item" href="<?= site_url('manajemenStok/generatePDF') ?>">Download as PDF</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-secondary btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="upload"></i>
                Import File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= site_url('manajemenStok/createTemplate') ?>">Download Template</a>
                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#modalImport">Upload Excel</a>
            </div>
        </div>
        <a href="<?= site_url('manajemenStok/new') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="edit"></i>
            Tambah Data
        </a>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Data Peminjaman </h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="<?= site_url('manajemenPeminjaman/trash') ?>"
                class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
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
                    <a class="dropdown-item" href="<?= site_url('manajemenPeminjaman/export') ?>">Download as Excel</a>
                    <a class="dropdown-item" href="<?= site_url('manajemenPeminjaman/generatePDF') ?>">Download as
                        PDF</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-secondary btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class=" btn-icon-prepend" data-feather="upload"></i>
                    Import File
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="<?= site_url('manajemenPeminjaman/createTemplate') ?>">Download
                        Template</a>
                    <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#modalImport">Upload
                        Excel</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th>Nama Peminjam</th>
                                <th>Asal Peminjam</th>
                                <th>Aset</th>
                                <th>Lokasi</th>
                                <th>Jumlah</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataManajemenPeminjaman as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td class="text-center"><?=$value->namaPeminjam?></td>
                                <td class="text-center"><?=$value->asalPeminjam?></td>
                                <td class="text-center"><?=$value->namaSarana?></td>
                                <td class="text-center"><?=$value->namaLab?></td>
                                <td class="text-center"><?=$value->jumlah?></td>
                                <td class="text-center">
                                    <a href="<?=site_url('manajemenPeminjaman/'.$value->idManajemenPeminjaman) ?>"
                                        class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                    <a href="<?=site_url('manajemenPeminjaman/'.$value->idManajemenPeminjaman.'/edit') ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form action="<?=site_url('manajemenPeminjaman/'.$value->idManajemenPeminjaman)?>"
                                        method="post" class="d-inline" id="del-<?= $value->idManajemenPeminjaman;?>">
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
            <form action="<?=site_url("manajemenStok/import")?>" method="POST" enctype="multipart/form-data"  id="custom-validation">
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

<?= $this->endSection(); ?>