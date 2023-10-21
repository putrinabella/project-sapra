<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title> <?= $dataLaboratorium->namaLab; ?> &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('laboratorium')?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                        <a href="<?= site_url('laboratorium/print/'.$dataLaboratorium->idIdentitasLab)?>"
                            class="btn btn-outline-success btn-icon-text mb-2 mb-md-0" target="_blank">
                            <i class="btn-icon-prepend" data-feather="printer"></i>
                            Print
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="p-2">Nama Lab</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataLaboratorium->namaLab; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5 class="p-2">Kode Lab</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataLaboratorium->kodeLab; ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <h5 class="p-2">Lokasi Gedung</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataInfoLab->namaGedung;?>
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="p-2">Lokasi Lantai</h5>
                        <div class="border rounded-2 p-2">
                        <?= $dataInfoLab->namaLantai;?>
                        </div>
                    </div>
                    <div class="col">
                        <h5 class="p-2">Luas</h5>
                        <div class="border rounded-2 p-2">
                            <?= $luasFormatted = number_format($dataLaboratorium->luas, 0, ',', '.'); ?> m&sup2
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
                                    <td class="text-center"><?=$value->kodeRincianLabAset?></td>
                                    <td class="text-center"><?=$value->namaSarana?></td>
                                    <td class="text-center"><?=$value->tahunPengadaan?></td>
                                    <td class="text-center"><?=$value->namaKategoriManajemen?></td>
                                    <td class="text-center"><?=$value->namaSumberDana?></td>
                                    <td class="text-center"><?=$value->saranaLayak?></td>
                                    <td class="text-center"><?=$value->saranaRusak?></td>
                                    <td class="text-center"><?=$totalSarana = $value->saranaLayak + $value->saranaRusak?></td>
                                    <td class="text-center">
                                        <a href="<?=site_url('laboratorium/showInfo/'.$value->idRincianLabAset) ?>" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
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