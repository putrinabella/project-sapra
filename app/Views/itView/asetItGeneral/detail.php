<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>
    <?= $detailAset->namaSarana; ?> &verbar; SARPRA
</title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">IT</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('asetItGeneral')?>">Data General</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Aset</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('asetItGeneral')?>"
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
                            <?= $detailAset->namaSarana; ?>
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
                                    <th>Sumber Dana</th>
                                    <th>Tahun Pengadaan</th>
                                    <th>Status</th>
                                    <th>Harga Beli</th>
                                    <th>Merek</th>
                                    <th>Warna</th>
                                    <th style="width: 20%;">Aksi</th>
                                    <th style="width: 20%;">Pemusnahan</th>
                                </tr>
                            </thead>
                            <tbody class="py-2">
                                <?php foreach ($asetItGeneral as $key => $value) : ?>
                                <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                    <td class="text-center">
                                        <?=$key + 1?>
                                    </td>
                                    <td><?=$value->kodeRincianAset?></td>
                                    <td><?=$value->namaPrasarana?></td>
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
                                    <td><?=$value->status?></td>
                                    <td><?=number_format($value->hargaBeli, 0, ',', '.')?></td>
                                    <td><?=$value->merk?></td>
                                    <td><?=$value->warna?></td>
                                    <td class="text-center">
                                        <a href="<?=site_url('asetItGeneral/dataItAset/'.$value->idRincianAset) ?>"
                                            class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                        <a href="<?=site_url('rincianItAset/'.$value->idRincianAset.'/edit') ?>"
                                            class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                        <form action="<?=site_url('rincianItAset/'.$value->idRincianAset)?>" method="post"
                                            class="d-inline" id="del-<?= $value->idRincianAset;?>">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger btn-icon"
                                                data-confirm="Apakah anda yakin menghapus data ini?"
                                                data-title="Hapus Aset">
                                                <i data-feather="trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center">
                                        <form
                                            action="<?= site_url('pemusnahanAset/destruction/' . $value->idRincianAset) ?>"
                                            method="post" class="d-inline">
                                            <div class="form-group">
                                                <div class="d-flex align-items-center">
                                                    <select name="sectionAset"
                                                        class="form-control me-2 sectionAsetSelect"
                                                        style="width: 130px">
                                                        <option value="None">None</option>
                                                        <option value="Dimusnahkan">Dimusnahkan</option>
                                                    </select>
                                                    <input class="form-control" type="text" name="namaAkun"
                                                        value=" <?= session('nama'); ?>" hidden>
                                                    <input class="form-control" type="text" name="kodeAkun"
                                                        value=" <?= session('role'); ?>" hidden>
                                                    <button type="submit"
                                                        class="btn btn-success btn-icon ml-2 submitButton">
                                                        <i data-feather="check"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
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