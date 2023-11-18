<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Ruangan &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Prasarana</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ruangan</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Ruangan</h4>
    </div>

</div>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <?php foreach ($dataPrasaranaRuangan as $key => $value) : ?>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <img src="<?= base_url(); ?>/assets/images/Ruangan.jpeg" class="card-img-top" alt="Foto ruangan">
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?= $value->namaPrasarana ?> (
                            <?= $value->kodePrasarana; ?> )
                        </h5>
                        <p class="card-text mb-3">
                            <span class="badge rounded-pill border border-primary text-primary">
                                <?= $value->namaGedung; ?>
                            </span>
                            <span class="badge rounded-pill border border-primary text-primary">
                                <?= $value->namaLantai; ?>
                            </span>
                            <span class="badge rounded-pill border border-primary text-primary">
                                Luas:
                                <?= $value->luas; ?> m&sup2
                            </span>
                        </p>
                        <a href="<?=site_url('prasaranaRuangan/'.$value->idIdentitasPrasarana) ?>"
                            class="btn btn-primary">Tampilkan
                            Aset</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
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
                    <table class="table table-hover" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th>No.</th>
                                <th>Nama</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataPrasaranaRuangan as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td class="text-left"><?=$value->namaPrasarana?></td>
                                <td class="text-center">
                                    <a href="<?=site_url('prasaranaRuangan/'.$value->idIdentitasPrasarana) ?>"
                                        class="btn btn-outline-success"> Show Detail</a>
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
            <form action="<?=site_url(" prasaranaRuangan/import")?>" method="POST" enctype="multipart/form-data"
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

<?= $this->endSection(); ?>