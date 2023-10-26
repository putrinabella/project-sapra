<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Layanan Non Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Detail Layanan Non Aset</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('saranaLayananNonAset')?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="text-center">
                    <img src="<?= $buktiUrl ?>" alt="Foto Bukti" style="max-height: 300px;" class="mx-auto">
                </div>
                <br>
                <div class="table-responsive">
                    <table class="my-table" >
                        <tr>
                            <td style="width: 15%;">Tanggal</td>
                            <td style="width: 2%;">:</td>
                            <td>
                                <?= date('d F Y', strtotime($dataSaranaLayananNonAset->tanggal)) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <?= $dataSaranaLayananNonAset->namaPrasarana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Layanan</td>
                            <td>:</td>
                            <td>
                                <?= $dataSaranaLayananNonAset->namaStatusLayanan?>
                            </td>
                        </tr>
                        <tr>
                            <td>Kategori Manajemen</td>
                            <td>:</td>
                            <td>
                                <?= $dataSaranaLayananNonAset->namaKategoriManajemen?>
                            </td>
                        </tr>
                        <tr>
                            <td>Sumber Dana</td>
                            <td>:</td>
                            <td>
                                <?= $dataSaranaLayananNonAset->namaSumberDana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Biaya</td>
                            <td>:</td>
                            <td>Rp<?=number_format($dataSaranaLayananNonAset->biaya, 0, ',', '.')?></td>
                        </tr>
                        <tr>
                            <td>Link Bukti</td>
                            <td>:</td>
                            <td>
                            <a href="<?= $dataSaranaLayananNonAset->bukti ?>" target="_blank">
                                <?= $dataSaranaLayananNonAset->bukti ?>
                            </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Keterangan</td>
                            <td>:</td>
                            <td>
                                <?= $spesifikasiHtml ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>