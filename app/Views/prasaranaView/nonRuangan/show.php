<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>  <?= $dataPrasaranaNonRuangan->namaPrasarana; ?> &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('prasaranaNonRuangan')?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                        <a href="<?= site_url('prasaranaNonRuangan/print/'.$dataPrasaranaNonRuangan->idIdentitasPrasarana)?>"
                            class="btn btn-outline-success btn-icon-text mb-2 mb-md-0" target="_blank">
                            <i class="btn-icon-prepend" data-feather="printer"></i>
                            Print
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="p-2">Nama Lokasi</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataPrasaranaNonRuangan->namaPrasarana; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h5 class="p-2">Kode Prasarana</h5>
                        <div class="border rounded-2 p-2">
                            <?= $dataPrasaranaNonRuangan->kodePrasarana; ?>
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
                            <?= $luasFormatted = number_format($dataPrasaranaNonRuangan->luas, 0, ',', '.'); ?> m&sup2
                        </div>
                    </div>
                </div>
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
                                    <th>Kategori Aset</th>
                                    <th>Nama Aset</th>
                                    <th>Status</th>
                                    <th>Keterediaan</th>
                                    <th>Sumber Dana</th>
                                    <th>Tahun Pengadaan</th>
                                    <th>Harga Beli</th>
                                    <th>Merek</th>
                                    <th>Warna</th>
                                    <th style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="py-2">
                            <?php foreach ($dataSarana as $key => $value) : ?>
                                <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                    <td class="text-center">
                                        <?=$key + 1?>
                                    </td>
                                    <td><?=$value->kodeRincianAset?></td>
                                    <td><?=$value->namaPrasarana?></td>
                                    <td><?=$value->namaKategoriManajemen?></td>
                                    <td><?=$value->namaSarana?></td>
                                    <td class="text-center">
                                    <?php if ($value->status == "Rusak") : ?>
                                    <span class="badge bg-warning">
                                        <?= $value->status; ?> 
                                    </span>
                                    <?php elseif ($value->status == "Hilang"): ?>
                                    <span class="badge bg-danger">
                                    <?= $value->status; ?> 
                                    </span>
                                    <?php elseif ($value->status == "Bagus"): ?>
                                    <?= $value->status; ?> 
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($value->sectionAset == "None") : ?>
                                        Tersedia
                                    <?php else : ?>
                                    <span class="badge bg-warning">
                                        <?= $value->sectionAset; ?> 
                                    </span>
                                    <?php endif; ?>
                                </td>
                                    <td><?=$value->namaSumberDana?></td>
                                    <td>
                                        <?php 
                                            if($value->tahunPengadaan == 0 || 0000) {
                                                echo "Tidak diketahui"; 
                                            } else {
                                                echo $value->tahunPengadaan;
                                            };
                                        ?>
                                    </td>
                                    <td><?=number_format($value->hargaBeli, 0, ',', '.')?></td>
                                    <td><?=$value->merk?></td>
                                    <td><?=$value->warna?></td>
                                    <td class="text-center">
                                        <a href="<?=site_url('prasaranaNonRuangan/showInfo/'.$value->idRincianAset) ?>" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
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