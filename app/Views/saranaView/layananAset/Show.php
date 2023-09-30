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
                        <a href="<?= site_url('saranaLayananAset')?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                </div>
                <div class="text-center">
                    <img src="<?= base_url($dataSaranaLayananAset->bukti) ?>" alt="Foto Bukti" style="max-width: 100%;" class="mx-auto">
                </div>
                <br>
                <table class="table">
                    <tr>
                        <td>Tanggal</td>
                        <td style="width: 5%;">:</td>
                        <td><?= $dataSaranaLayananAset->tanggal?></td>
                    </tr>
                    <tr>
                        <td>Nama Aset</td>
                        <td>:</td>
                        <td><?= $dataSaranaLayananAset->namaSarana?></td>
                    </tr>
                    <tr>
                        <td>Lokasi</td>
                        <td>:</td>
                        <td><?= $dataSaranaLayananAset->namaPrasarana?></td>
                    </tr>
                    <tr>
                        <td>Status Layanan</td>
                        <td>:</td>
                        <td><?= $dataSaranaLayananAset->namaStatusLayanan?></td>
                    </tr>
                    <tr>
                        <td>Kategori Manajemen</td>
                        <td>:</td>
                        <td><?= $dataSaranaLayananAset->namaKategoriManajemen?></td>
                    </tr>
                    <tr>
                        <td>Sumber Dana</td>
                        <td>:</td>
                        <td><?= $dataSaranaLayananAset->namaSumberDana?></td>
                    </tr>
                    <tr>
                        <td>Biaya</td>
                        <td>:</td>
                        <td>Rp<?=number_format($dataSaranaLayananAset->biaya, 0, ',', '.')?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>