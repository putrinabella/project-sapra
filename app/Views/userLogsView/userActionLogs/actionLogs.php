<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Action Logs &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Manajemen User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Action Logs</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <form action="<?= site_url('viewActions') ?>" onsubmit="return validateForm()" class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="startYearPicker">
                <input type="text" class="form-control border-primary bg-transparent" id="startYear" name="startYear" placeholder="Start Year" readonly>
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar"></i></span>
            </div>
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="endYearPicker">
                <input type="text" class="form-control border-primary bg-transparent" id="endYear" name="endYear" placeholder="End Year" readonly>
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar"></i></span>
            </div>
            <div class="col py-3 p-0 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-icon me-1">
                    <i data-feather="filter"></i>
                </button>
                <a href="<?= site_url('viewActions') ?>" class="btn btn-secondary btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>

    <div class="d-flex align-items-center flex-wrap text-nowrap">
    <?php
                if (empty($_GET['startYear']) && empty($_GET['endYear'])) {
                    $exportLink = site_url('viewActions/export');
                    $generatePDFLink = site_url('viewActions/generatePDF');
                } else {
                    $startYear = $_GET['startYear'] ?? '';
                    $endYear = $_GET['endYear'] ?? '';
                    $exportLink = site_url("viewActions/export?startYear=$startYear&endYear=$endYear");
                    $generatePDFLink = site_url("viewActions/generatePDF?startYear=$startYear&endYear=$endYear");
                }
            ?>
        <a href="<?= $generatePDFLink ?>" target="_blank" class="btn btn-primary btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="download"></i>
            Download PDF
        </a>
        <a href="<?= $exportLink ?>" class="btn btn-success btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="download"></i>
            Download Excel
        </a>
    </div>
</div>


<div class="row">
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
                <h4 class="text-center py-3">User Action Logs</h4>
                <?php if (!empty($tableHeading)) : ?>
                    <p class="text-center"><?= $tableHeading ?></p>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10%;">No.</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Type</th>
                                <th>Details</th>
                                <th>Time</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataActionLog as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center"><?=$key + 1?></td>
                                <td><?=$value->nama?></td>
                                <td><?=$value->role?></td>
                                <td>
                                    <?php if ($value->actionType === 'Restore' ||$value->actionType === 'Restore All') : ?>
                                        <span class="badge bg-primary"><?= $value->actionType ?></span>
                                    <?php elseif ($value->actionType === 'Delete' || $value->actionType === 'Delete All') : ?>
                                        <span class="badge bg-danger"><?= $value->actionType ?></span>
                                    <?php else : ?>
                                        <?= $value->actionType ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $value->actionDetails; ?> </td>
                                <td class="text-center"><?= date('H:i:s', strtotime($value->actionTime)) ?></td>
                                <td class="text-center"><?= date('d F Y', strtotime($value->actionTime)) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>
<script>
    function validateForm() {
        var startYear = document.getElementById("startYear").value;
        var endYear = document.getElementById("endYear").value;

        if (startYear.trim() === '' || endYear.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Validation Error',
                text: 'Please enter both start and end years.',
                confirmButtonText: 'OK'
            });

            return false; 
        }

        return true;
    }
</script>

<?= $this->endSection(); ?>