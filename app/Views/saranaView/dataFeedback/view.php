<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Umpan Balik &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Umpan Balik</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Umpan Balik</li>
    </ol>
</nav>


<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <form action="<?= site_url('arsipFeedback') ?>" class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="startDatePicker">
                <input type="text" class="form-control border-primary" id="startDate" name="startDate"
                    placeholder="Start Date" readonly>
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i
                        data-feather="calendar"></i></span>
            </div>
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="endDatePicker">
                <input type="text" class="form-control border-primary" id="endDate" name="endDate"
                    placeholder="End Date" readonly>
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i
                        data-feather="calendar"></i></span>
            </div>
            <div class="col py-3 p-0 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-icon me-1">
                    <i data-feather="filter"></i>
                </button>
                <a href="<?= site_url('arsipFeedback') ?>" class="btn btn-success btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
    <div>
        <?php
                if (empty($_GET['startDate']) && empty($_GET['endDate'])) {
                    $exportLink = site_url('arsipFeedback/export');
                    $generatePDFLink = site_url('arsipFeedback/generatePDF');
                } else {
                    $startDate = $_GET['startDate'] ?? '';
                    $endDate = $_GET['endDate'] ?? '';
                    $exportLink = site_url("arsipFeedback/export?startDate=$startDate&endDate=$endDate");
                    $generatePDFLink = site_url("arsipFeedback/generatePDF?startDate=$startDate&endDate=$endDate");
                }
            ?>
        <a href="<?= $generatePDFLink ?>" target="_blank" class="btn btn-primary btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="download"></i>
            Download PDF
        </a>
    </div>

    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
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
                <h4 class="text-center py-3">Data Umpan Balik</h4>
                <?php if (!empty($tableHeading)) : ?>
                <p class="text-center">
                    <?= $tableHeading ?>
                </p>
                <?php endif; ?>
                <br>
                <div class="row mb-3">
                    <p style="font-weight: bold;">Rata-rata Kepuasan:
                        <?= number_format($averageFeedbackPercentages, 2) ?>%
                    </p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th>Tanggal</th>
                                <th>Kode Pengaduan</th>
                                <th>NIS/NIP</th>
                                <th>Nama</th>
                                <th>Karwayan/Kelas</th>
                                <th>Status</th>
                                <th style="width: 15%;">Kepuasan</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataFeedback as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td>
                                    <?= $key + 1 ?>
                                </td>
                                <?php
                                $originalDate = $value->tanggal;
                                if (!empty($originalDate)) {
                                    $formattedDate = date('d F Y', strtotime($originalDate));
                                } else {
                                    $formattedDate = '-';
                                }
                                ?>
                                <td data-sort="<?= !empty($originalDate) ? strtotime($originalDate) : 0 ?>">
                                    <?php echo $formattedDate; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('arsipPengaduan/' . $value->idFormPengaduan) ?>">
                                        <?= $value->kodeFormPengaduan ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $value->nis ?>
                                </td>
                                <td>
                                    <?= $value->namaSiswa ?>
                                </td>
                                <td>
                                    <?= $value->namaKelas ?>
                                </td>
                                <td style="width: 10%">
                                    <?php if ($value->statusFeedback == "empty") : ?>
                                    <span class="badge bg-warning">Belum diisi</span>
                                    <?php elseif ($value->statusFeedback == "done") : ?>
                                    <span class="badge bg-primary">Sudah diisi</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                $idFormFeedback = $value->idFormFeedback;
                                $kepuasanPercentage = isset($feedbackPercentages[$idFormFeedback]) ? $feedbackPercentages[$idFormFeedback] : 0;
                                ?>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: <?= round($kepuasanPercentage, 2) ?>%;"
                                            aria-valuenow="<?= round($kepuasanPercentage, 2) ?>" aria-valuemin="0"
                                            aria-valuemax="100">
                                            <?= round($kepuasanPercentage, 2) ?>%
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="<?=site_url('arsipFeedback/'.$value->idFormFeedback) ?>"
                                        class="btn btn-primary btn-icon me-2"> <i data-feather="info"></i></a>
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