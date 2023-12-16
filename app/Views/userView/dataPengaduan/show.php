<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Pengaduan &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaduan</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('dataPengaduanUser')?>">Data Pengaduan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('dataPengaduanUser') ?>" class="btn btn-icon-text btn-outline-primary me-2">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Data Pengaduan</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <table class="my-table">
                    <tr>
                        <td style="width: 25%;">Tanggal</td>
                        <td style="width: 2%;">:</td>
                        <td>
                            <?= date('l, j F Y', strtotime($identitasUser->tanggal)) ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%;">Kode Pengaduan</td>
                        <td style="width: 2%;">:</td>
                        <td><?= $identitasUser->kodeFormPengaduan; ?> 
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%;">NIS/NIP</td>
                        <td style="width: 2%;">:</td>
                        <td>
                            <?= $identitasUser->nis ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%;">Nama</td>
                        <td style="width: 2%;">:</td>
                        <td>
                            <?= $identitasUser->namaSiswa ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%;">Kelas/Karyawan</td>
                        <td style="width: 2%;">:</td>
                        <td>
                            <?= $identitasUser->namaKelas ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 25%;">Status Pengaduan</td>
                        <td style="width: 2%;">:</td>
                        <td>
                            <?php if ($identitasUser->statusPengaduan == "request") : ?>
                            <span class="badge bg-primary">Diajukan</span>
                            <?php elseif ($identitasUser->statusPengaduan == "process") : ?>
                            <span class="badge bg-warning">Diproses</span>
                            <?php elseif ($identitasUser->statusPengaduan == "done") : ?>
                            <span class="badge bg-info">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <br>
                <br>
                <h5>Isi Pengaduan</h5>
                <br>
                <?php foreach ($dataPengaduan as $key => $value): ?>
                <input type="text" class="form-control border-0" name="idPertanyaanPengaduan[]"
                    id="idPertanyaanPengaduan" value="<?= $value->idPertanyaanPengaduan; ?>" hidden>
                <p>
                    <?= $key+1 . '. ' . $value->pertanyaanPengaduan; ?>
                </p>
                <div class="my-border" style="margin-left: 12px ;">
                    <?= $value->isiPengaduan; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>