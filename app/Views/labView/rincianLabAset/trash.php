<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Recycle Bin Rincian Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<h4 class="mb-3 mb-md-0">Recyle Bin Data Rincian Aset</h4>
<br>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('rincianLabAset')?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                        <a href="<?= site_url('rincianLabAset/restore')?>"
                            class="btn btn-primary btn-icon-text  me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="cloud-drizzle"></i>
                            Restore All
                        </a>
                        <form action="<?= site_url('rincianLabAset/deletePermanent/') ?>" method="POST"
                            class="d-inline me-2 mb-2 mb-md-0">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="btn btn-danger btn-icon-text" type="submit">
                            <i class="btn-icon-prepend" data-feather="alert-triangle"></i> Delete All Permanent </button>
                        </form>
                        </a>
                    </div>
                </div>

                <br>
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th style="width: 12%;">Kode Aset</th>
                                <th>Lokasi</th>
                                <th>Kategori Barang</th>
                                <th>Spesifikasi Barang</th>
                                <th>Status</th>
                                <th>Sumber Dana</th>
                                <th>Tahun Pengadaan</th>
                                <th>Harga Beli</th>
                                <th>Merek</th>
                                <th>Warna</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                        <?php foreach ($dataRincianLabAset as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td class="text-center"><?=$value->kodeRincianLabAset?></td>
                                <td class="text-center"><?=$value->namaLab?></td>
                                <td class="text-center"><?=$value->namaKategoriManajemen?></td>
                                <td class="text-center"><?=$value->namaSarana?></td>
                                <td class="text-center"><?=$value->status?></td>
                                <td class="text-center"><?=$value->namaSumberDana?></td>
                                <td class="text-center"><?=$value->tahunPengadaan?></td>
                                <td class="text-center"><?=number_format($value->hargaBeli, 0, ',', '.')?></td>
                                <td class="text-center"><?=$value->merk?></td>
                                <td class="text-center"><?=$value->warna?></td>
                                <td class="text-center">
                                    <a href="<?=site_url('rincianLabAset/restore/'.$value->idRincianLabAset) ?>"
                                        class="btn btn-primary"> Restore</a>
                                    <form action="<?= site_url('rincianLabAset/deletePermanent/'.$value->idRincianLabAset) ?>"
                                        method="POST" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-danger" type="submit"> Delete Permanent </button>
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


<?= $this->endSection(); ?>