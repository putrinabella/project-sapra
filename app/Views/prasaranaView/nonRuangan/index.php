<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Non Ruangan &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Prasarana</a></li>
        <li class="breadcrumb-item active" aria-current="page">Non Ruangan</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Non Ruangan</h4>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover"  id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10%;">No.</th>
                                <th>Nama</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                        <?php foreach ($dataPrasaranaNonRuangan as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td class="text-left"><?=$value->namaPrasarana?></td>
                                <td class="text-center">
                                    <a href="<?=site_url('prasaranaNonRuangan/'.$value->idIdentitasPrasarana) ?>" class="btn btn-outline-success">Show Detail</a>
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