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
                        <a href="<?= site_url('rincianAset')?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="text-center">
                <img src="<?= base_url($dataRincianAset->link) ?>" alt="Foto Bukti" style="width: 100%; max-width: 200px;" class="mx-auto">
                </div>
                <br>
                <table class="table" style="max-width: 90%; margin: 0 auto;">
                    <tr>
                        <td style="width: 10%;">Kode Aset</td>
                        <td style="width: 5%;">:</td>
                        <td><?= $dataRincianAset->kodeRincianAset?></td>
                    </tr>
                    <tr>
                        <td>Nama Aset</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->namaSarana?></td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->namaPrasarana?></td>
                    </tr>
                    <tr>
                        <td>Sumber Dana</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->namaSumberDana?></td>
                    </tr>
                    <tr>
                        <td>Kategori Manajemen</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->namaKategoriManajemen?></td>
                    </tr>
                    <tr>
                        <td>Tahun Pengadaan</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->tahunPengadaan?></td>
                    </tr>
                    <tr>
                        <td>Sarana Layak</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->saranaLayak?></td>
                    </tr>
                    <tr>
                        <td>Sarana Rusak</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->saranaRusak?></td>
                    </tr>
                    <tr>
                        <td>Total Sarana</td>
                        <td>:</td>
                        <td><?= $dataRincianAset->totalSarana?></td>
                    </tr>
                    <tr>
                        <td>Spesifikasi</td>
                        <td>:</td>
                        <td>
                        <!-- <td style="white-space: pre-wrap; padding: 0; margin-top: 0;"> -->
                                <textarea class="form-control" id="editspek" name="spesifikasi" rows="10"
                                placeholder="Masukkan spesifikasi aset"><?=$dataRincianAset->spesifikasi?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(function() {
        'use strict';

        if ($("#editspek").length) {
            var simplemde = new SimpleMDE({
                element: $("#editspek")[0],
                toolbar: false,
                status: false,
                preview: true,
            });
            simplemde.togglePreview();
            simplemde.codemirror.options.readOnly = true;
        }
    });
</script> -->
<?= $this->endSection(); ?>