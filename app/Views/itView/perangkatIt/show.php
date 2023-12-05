<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>  <?= $dataPerangkatIt->namaSarana; ?> &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('dataItSarana')?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <h5 class="p-2">Nama Sarana</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataPerangkatIt->namaSarana; ?>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-3">
                        <h5 class="p-2">Jumlah Keseluruhan</h5>
                        <div class="border rounded-2 p-2">
                        <?= !empty($totalSarana) ? $totalSarana : '-'; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5 class="p-2">Jumlah Layak</h5>
                        <div class="border rounded-2 p-2">
                        <?= !empty($saranaLayak) ? $saranaLayak : '-'; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5 class="p-2">Jumlah Rusak</h5>
                        <div class="border rounded-2 p-2">
                        <?= !empty($saranaRusak) ? $saranaRusak : '-'; ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h5 class="p-2">Jumlah Hilang</h5>
                        <div class="border rounded-2 p-2">
                        <?= !empty($saranaHilang) ? $saranaHilang : '-'; ?>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <div class="row">
                    <h5>Rincian Aset </h5>
                    <br>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable" style="width: 100%;">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 5%;">No.</th>
                                    <th style="width: 12%;">Kode Aset</th>
                                    <th>Lokasi</th>
                                    <th>Tahun Pengadaan</th>
                                    <th>Status Aset</th>
                                    <th>Sumber Dana</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="py-2">
                            <?php foreach ($dataAsetIT as $key => $value) : ?>
                                <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                    <td class="text-center">
                                        <?=$key + 1?>
                                    </td>
                                    <td class="text-center"><?=$value->kodeRincianAset?></td>
                                    <td class="text-center"><?=$value->namaPrasarana?></td>
                                    <td class="text-center"><?=$value->tahunPengadaan?></td>
                                    <td class="text-center"><?=$value->status?></td>
                                    <td class="text-center"><?=$value->namaSumberDana?></td>
                                    <td class="text-center">
                                        <a href="<?=site_url('rincianAset/'.$value->idRincianAset) ?>" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                        <a href="<?=site_url('rincianAset/'.$value->idRincianAset.'/edit') ?>" class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
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