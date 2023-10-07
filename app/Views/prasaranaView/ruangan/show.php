<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Rincian Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Detail Rincian Aset</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('rincianAset')?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                        <a href="<?= site_url('rincianAset/print/'.$dataPrasaranaRuangan->idIdentitasPrasarana)?>"
                            class="btn btn-outline-success btn-icon-text mb-2 mb-md-0" target="_blank">
                            <i class="btn-icon-prepend" data-feather="printer"></i>
                            Print
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="p-2">Nama Prasarana</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataPrasaranaRuangan->namaPrasarana; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5 class="p-2">Kode Prasarana</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataPrasaranaRuangan->kodePrasarana; ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <h5 class="p-2">Lokasi Gedung</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataInfoPrasarana->namaGedung;?>
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="p-2">Lokasi Lantai</h5>
                        <div class="border rounded-2 p-2">
                        <?= $dataInfoPrasarana->namaLantai;?>
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="p-2">Luas</h5>
                        <div class="border rounded-2 p-2">
                            <?= $luasFormatted = number_format($dataPrasaranaRuangan->luas, 0, ',', '.'); ?> m&sup2
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <h5>Rincian Aset </h5>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover"  id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 5%;">No.</th>
                                    <th style="width: 12%;">Kode Aset</th>
                                    <th>Nama Aset</th>
                                    <th>Tahun Pengadaan</th>
                                    <th>Kategori Manajemen</th>
                                    <th>Sumber Dana</th>
                                    <th>Aset Layak</th>
                                    <th>Aset Rusak</th>
                                    <th>Total Aset</th>
                                    <th style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="py-2">
                            <?php foreach ($dataSarana as $key => $value) : ?>
                                <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                    <td class="text-center">
                                        <?=$key + 1?>
                                    </td>
                                    <td class="text-center"><?=$value->kodeRincianAset?></td>
                                    <td class="text-center"><?=$value->namaSarana?></td>
                                    <td class="text-center"><?=$value->tahunPengadaan?></td>
                                    <td class="text-center"><?=$value->namaKategoriManajemen?></td>
                                    <td class="text-center"><?=$value->namaSumberDana?></td>
                                    <td class="text-center"><?=$value->saranaLayak?></td>
                                    <td class="text-center"><?=$value->saranaRusak?></td>
                                    <td class="text-center"><?=$value->totalSarana?></td>
                                    <td class="text-center">
                                        <a href="<?=site_url('rincianAset/'.$value->idRincianAset) ?>" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                        <a href="<?=site_url('rincianAset/'.$value->idRincianAset.'/edit') ?>"
                                            class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                        <form action="<?=site_url('rincianAset/'.$value->idRincianAset)?>"
                                            method="post" class="d-inline" id="del-<?= $value->idRincianAset;?>">
                                            <?= csrf_field() ?>
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
</div>
<?= $this->endSection(); ?>