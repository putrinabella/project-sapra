<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data General &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data General</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Total Aset:
            <?= $jumlahTotal; ?>
        </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="<?= site_url('asetLabGeneral/generatePDF') ?>" target="_blank"
                class="btn btn-primary btn-icon-text me-2 mb-2 mb-md-0">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Download PDF
            </a>
            <a href="<?= site_url('asetLabGeneral/export') ?>" class="btn btn-success btn-icon-text me-2 mb-2 mb-md-0">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Download Excel
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th>Nama Aset</th>
                                <th>Total Aset</th>
                                <th>Aset Bagus</th>
                                <th>Aset Rusak</th>
                                <th>Aset Hilang</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataGeneral as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td><?=$value->namaSarana?></td>
                                <td class="text-center"><?=$value->jumlahAset?></td>
                                <td class="text-center"><?=$value->jumlahBagus?></td>
                                <td class="text-center"><?=$value->jumlahRusak?></td>
                                <td class="text-center"><?=$value->jumlahHilang?></td>
                                <td class="text-center">
                                    <a href="<?=site_url('asetLabGeneral/'.$value->idIdentitasSarana) ?>"
                                        class="btn btn-outline-success">Show Detail</a>
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
<?= $this->endSection(); ?>