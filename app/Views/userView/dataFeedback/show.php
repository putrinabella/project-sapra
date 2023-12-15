<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Umpan Balik &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaduan</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('dataFeedbackUser')?>">Data Umpan Balik</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('dataFeedbackUser') ?>" class="btn btn-icon-text btn-outline-primary me-2">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Data Umpan Balik</h4>
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
                        <td style="width: 25%;">Status Umpan Balik</td>
                        <td style="width: 2%;">:</td>
                        <td>
                            <?php if ($identitasUser->statusFeedback == "request") : ?>
                            <span class="badge bg-primary">Diajukan</span>
                            <?php elseif ($identitasUser->statusFeedback == "process") : ?>
                            <span class="badge bg-warning">Diproses</span>
                            <?php elseif ($identitasUser->statusFeedback == "done") : ?>
                            <span class="badge bg-info">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
                <br>
                <br>
                <h5 class="text-center text-decoration-underline mb-3">Isi Umpan Balik</h5>
                <div class="table-responsive">
                    <table class="table table-hover" id="my-table" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th>Pertanyaan</th>
                                <th>Umpan Balik</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">                        
                            <?php foreach ($dataFeedback as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td>
                                    <?= $key + 1 ?>
                                </td>
                                <td>
                                    <?= $value->pertanyaanFeedback ?>
                                </td>
                                <td>
                                    <?php if ($value->isiFeedback == "1") : ?>
                                    <span class="badge bg-danger">
                                        Sangat Tidak Puas
                                    </span>
                                    <?php elseif ($value->isiFeedback == "2") : ?>
                                    <span class="badge bg-warning">
                                        Tidak Puas
                                    </span>
                                    <?php elseif ($value->isiFeedback == "3") : ?>
                                    <span class="badge bg-primary">
                                        Netral
                                    </span>
                                    <?php elseif ($value->isiFeedback == "4") : ?>
                                    <span class="badge bg-primary">
                                        Puas
                                    </span>
                                    <?php elseif ($value->isiFeedback == "5") : ?>
                                    <span class="badge bg-primary">
                                        Sangat Puas
                                    </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>

                            <?php
                                $idFormFeedback = $value->idFormFeedback;
                                $kepuasanPercentage = isset($feedbackPercentages[$idFormFeedback]) ? $feedbackPercentages[$idFormFeedback] : 0;
                                ?>
                            <?php if ($kepuasanPercentage > 0) : ?>
                            <tr>
                                <td colspan="3" style="font-weight: bold;">
                                    Poin Kepuasan:
                                    <?= round($kepuasanPercentage, 2) ?>%
                                </td>
                            </tr>
                            <?php else : ?>
                            <tr>
                                <td colspan="3" style="font-weight: bold;">
                                    No feedback available.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>