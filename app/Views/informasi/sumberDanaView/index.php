<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Sumber Dana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Informasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sumber Dana</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Sumber Dana</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('sumberDana/print') ?>" class="btn btn-primary btn-icon me-2 mb-2 mb-md-0" target="_blank">
            <i class="btn-icon-prepend" data-feather="printer"></i>
        </a>
        <a href="<?= site_url('sumberDana/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="trash"></i>
            Recycle Bin
        </a>
        <a href="<?= site_url('sumberDana/export') ?>" class="btn btn-success btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="download"></i>
            Export Excel
        </a>
        <div class="dropdown">
            <button class="btn btn-secondary btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="upload"></i>
                Import Excel
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?=base_url('excel/Sumber_Dana_Example_Import.xlsx') ?> ">Download Template</a>
                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#modalImport">Upload File</a>
            </div>
        </div>
        <a href="<?= site_url('sumberDana/new') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
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
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10%;">No.</th>
                                <th style="width: 15%;">ID Sumber Dana</th>
                                <th>Nama Sumber Dana</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataSumberDana as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td class="text-center">
                                    <?= sprintf('%03d', $value->idSumberDana) ?>
                                </td>
                                <td class="text-left"><?=$value->namaSumberDana?></td>
                                <td class="text-center">
                                    <a href="" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                    <a href="<?=site_url('sumberDana/edit/'.$value->idSumberDana) ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form action="<?=site_url('sumberDana/delete/'.$value->idSumberDana)?>"
                                        method="post" class="d-inline" id="del-<?= $value->idSumberDana;?>">
                                        <?= csrf_field() ?>
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
            <form action="<?=site_url("sumberDana/import")?>" method="POST" enctype="multipart/form-data"  id="custom-validation">
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