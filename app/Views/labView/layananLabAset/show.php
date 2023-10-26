<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Layanan Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Detail Layanan Aset</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('layananLabAset')?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="text-center">
                    <img src="<?= $buktiUrl ?>" alt="Foto Bukti" style=" max-height: 300px;" class="mx-auto">
                </div>
                <br>
                <div class="table-responsive">
                    <table class="my-table">
                        <tr>
                        <td style="width: 15%;">Tanggal</td>
                            <td style="width: 2%;">:</td>
                            <td>
                                <?= date('d F Y', strtotime($value->tanggal)) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Aset</td>
                            <td>:</td>
                            <td>
                                <?= $dataLayananLabAset->namaSarana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <?= $dataLayananLabAset->namaLab?>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Layanan</td>
                            <td>:</td>
                            <td>
                                <?= $dataLayananLabAset->namaStatusLayanan?>
                            </td>
                        </tr>
                        <tr>
                            <td>Kategori Manajemen</td>
                            <td>:</td>
                            <td>
                                <?= $dataLayananLabAset->namaKategoriManajemen?>
                            </td>
                        </tr>
                        <tr>
                            <td>Sumber Dana</td>
                            <td>:</td>
                            <td>
                                <?= $dataLayananLabAset->namaSumberDana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Biaya</td>
                            <td>:</td>
                            <td>Rp<?=number_format($dataLayananLabAset->biaya, 0, ',', '.')?></td>
                        </tr>
                        <tr>
                            <td>Link Bukti</td>
                            <td>:</td>
                            <td>
                                <a href="<?= $dataLayananLabAset->bukti ?>" target="_blank">
                                    <?= $dataLayananLabAset->bukti ?>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>