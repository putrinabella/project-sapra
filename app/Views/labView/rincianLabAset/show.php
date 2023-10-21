<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Rincian Aset Lab &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Detail Rincian Aset Lab</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('rincianLabAset')?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                        <a href="<?= site_url('rincianLabAset/print/'.$dataRincianLabAset->idRincianLabAset)?>"
                            class="btn btn-outline-success btn-icon-text mb-2 mb-md-0" target="_blank">
                            <i class="btn-icon-prepend" data-feather="printer"></i>
                            Print
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
                            <td style="width: 15%;">Kode Aset</td>
                            <td style="width: 2%;">:</td>
                            <td>
                                <?= $dataRincianLabAset->kodeRincianLabAset?>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Aset</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianLabAset->namaSarana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianLabAset->namaLab?>
                            </td>
                        </tr>
                        <tr>
                            <td>Sumber Dana</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianLabAset->namaSumberDana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Kategori Manajemen</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianLabAset->namaKategoriManajemen?>
                            </td>
                        </tr>
                        <tr>
                            <td>Tahun Pengadaan</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianLabAset->tahunPengadaan?>
                            </td>
                        </tr>
                        <tr>
                            <td>Sarana Layak</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianLabAset->saranaLayak?>
                            </td>
                        </tr>
                        <tr>
                            <td>Sarana Rusak</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianLabAset->saranaRusak?>
                            </td>
                        </tr>
                        <tr>
                            <td>Total Sarana</td>
                            <td>:</td>
                            <td>
                                <?= $totalSarana = $dataRincianLabAset->saranaLayak + $dataRincianLabAset->saranaRusak?>
                            </td>
                        </tr>
                        <tr>
                            <td>Link Bukti</td>
                            <td>:</td>
                            <td>
                                <a href="<?= $dataRincianLabAset->bukti ?>" target="_blank">
                                    <?= $dataRincianLabAset->bukti ?>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Spesifikasi</td>
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