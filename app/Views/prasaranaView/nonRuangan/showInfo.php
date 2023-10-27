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
                        <a href="<?= site_url('prasaranaNonRuangan/'.$dataRincianAset->idIdentitasPrasarana)?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                        <a href="<?= site_url('rincianAset/print/'.$dataRincianAset->idRincianAset)?>"
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
                                <?= $dataRincianAset->kodeRincianAset?>
                            </td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->namaPrasarana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Kategori Barang</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->namaKategoriManajemen?>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Aset</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->namaSarana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Aset</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->status?>
                            </td>
                        </tr>
                        <tr>
                            <td>Sumber Dana</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->namaSumberDana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Tahun Pengadaan</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->tahunPengadaan?>
                            </td>
                        </tr>
                        <tr>
                            <td>Harga Beli</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->hargaBeli?>
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor Seri</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->noSeri?>
                            </td>
                        </tr>
                        <tr>
                            <td>Merek</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->merk?>
                            </td>
                        </tr>
                        <tr>
                            <td>Warna</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianAset->warna?>
                            </td>
                        </tr>
                        <tr>
                            <td>Link Bukti</td>
                            <td>:</td>
                            <td>
                                <a href="<?= $dataRincianAset->bukti ?>" target="_blank">
                                    <?= $dataRincianAset->bukti ?>
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