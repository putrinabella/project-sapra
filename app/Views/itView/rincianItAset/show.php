<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Rincian Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">IT</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('rincianItAset')?>">Rincian Aset</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('rincianItAset')?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                        <!-- <a href="<?= site_url('rincianItAset/print/'.$dataRincianItAset->idRincianAset)?>"
                            class="btn btn-outline-success btn-icon-text mb-2 mb-md-0" target="_blank">
                            <i class="btn-icon-prepend" data-feather="printer"></i>
                            Print
                        </a> -->
                    </div>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="my-table">
                        <tr>
                            <td style="width: 15%;">Kode Aset</td>
                            <td style="width: 2%;">:</td>
                            <td>
                                <?= $dataRincianItAset->kodeRincianAset?>
                            </td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->namaPrasarana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Kategori Barang</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->namaKategoriManajemen?>
                            </td>
                        </tr>
                        <tr>
                            <td>Nama Aset</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->namaSarana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Status Aset</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->status?>
                            </td>
                        </tr>
                        <tr>
                            <td>Sumber Dana</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->namaSumberDana?>
                            </td>
                        </tr>
                        <tr>
                            <td>Tahun Pengadaan</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->tahunPengadaan?>
                            </td>
                        </tr>
                        <tr>
                            <td>Harga Beli</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->hargaBeli?>
                            </td>
                        </tr>
                        <tr>
                            <td>Nomor Seri</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->noSeri?>
                            </td>
                        </tr>
                        <tr>
                            <td>Merek</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->merk?>
                            </td>
                        </tr>
                        <tr>
                            <td>Warna</td>
                            <td>:</td>
                            <td>
                                <?= $dataRincianItAset->warna?>
                            </td>
                        </tr>
                        <tr>
                            <td>Link Bukti</td>
                            <td>:</td>
                            <td>
                                <a href="<?= $dataRincianItAset->bukti ?>" target="_blank">
                                    <?= $dataRincianItAset->bukti ?>
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
                        <tr>
                            <td>Foto Dokumentasi</td>
                            <td>:</td>
                            <td>
                            <img src="<?= $buktiUrl ?>" alt="Foto Bukti" style="height: 250px;" class="text-center">
                            </td>
                        </tr>
                        <tr>
                            <td>QR Code</td>
                            <td>:</td>
                            <td>
                            <img src="<?= $qrCodeData ?>" alt="QR Code" style="height: 250px;" class="text-center">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>