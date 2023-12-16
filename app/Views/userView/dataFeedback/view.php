<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Umpan Balik &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaduan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Umpan Balik</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <form action="<?= site_url('dataFeedbackUser') ?>" class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="startDatePicker">
                <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Start Date"
                    readonly>
                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
            </div>
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="endDatePicker">
                <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" readonly>
                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
            </div>
            <div class="col py-3 p-0 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-icon me-1">
                    <i data-feather="filter"></i>
                </button>
                <a href="<?= site_url('dataFeedbackUser') ?>" class="btn btn-success btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="col-12 col-xl-12 grid-margin stretch-card">
    <div class="card overflow-hidden">
        <div class="card-body">
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
                            <th>Status</th>
                            <th>Kepuasan</th>
                            <th style="width: 20%;">Aksi</th>
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
                                <a href="<?= base_url('dataPengaduanUser/detail/' . $value->idFormPengaduan) ?>">
                                    <?= $value->kodeFormPengaduan ?>
                                </a>
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
                                <?php if ($value->statusFeedback == "empty") : ?>
                                    <a href="<?= site_url('dataFeedbackUser/edit/' . $value->idFormFeedback) ?>"
                                    class="btn btn-primary btn-icon me-2"> <i data-feather="edit-2"></i></a>
                                <?php elseif ($value->statusFeedback == "done") : ?>
                                    <a href="<?= site_url('dataFeedbackUser/detail/' . $value->idFormFeedback) ?>"
                                    class="btn btn-success btn-icon me-2"> <i data-feather="info"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>