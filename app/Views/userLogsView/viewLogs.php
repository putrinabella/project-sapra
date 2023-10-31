<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>User Log &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Manajemen User</a></li>
        <li class="breadcrumb-item active" aria-current="page">User Log</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">User Log</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('viewLogs/generatePDF') ?>" class="btn btn-primary btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="download"></i>
            Download PDF
        </a>
        <a href="<?= site_url('viewLogs/export') ?>" class="btn btn-success btn-icon-text me-2 mb-2 mb-md-0">
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
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10%;">No.</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Time</th>
                                <th>Date</th>
                                <th>Action Type</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataUserLog as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center"><?=$key + 1?></td>
                                <td class="text-center"><?=$value->username?></td>
                                <td class="text-center"><?=$value->role?></td>
                                <td class="text-center"><?= date('H:i:s', strtotime($value->loginTime)) ?></td>
                                <td class="text-center"><?= date('d F Y', strtotime($value->loginTime)) ?></td>
                                <td class="text-center">
                                    <?php if ($value->actionType === 'Login') : ?>
                                        <span class="badge bg-success"><?= $value->actionType ?></span>
                                    <?php elseif ($value->actionType === 'Logout') : ?>
                                        <span class="badge bg-warning"><?= $value->actionType ?></span>
                                    <?php else : ?>
                                        <?= $value->actionType ?>
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
</div>

<?= $this->endSection(); ?>