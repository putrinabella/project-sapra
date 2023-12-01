<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Peminjaman</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <form action="<?= site_url('peminjamanDataUser') ?>" class="d-flex align-items-center flex-wrap text-nowrap">
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
                <a href="<?= site_url('peminjamanDataUser') ?>" class="btn btn-success btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('peminjamanUser') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="edit"></i>
            Ajukan Peminjaman
        </a>
    </div>
</div>

<div class="col-12 col-xl-12 grid-margin stretch-card">
    <div class="card overflow-hidden">
        <div class="card-body">
            <div>
                <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible show fade" role="alert" id="alert">
                    <div class="alert-body">
                        <b>Success!</b>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                </div>
                <br>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible show fade" role="alert" id="alert">
                    <div class="alert-body">
                        <b>Error!</b>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                </div>
                <br>
                <?php endif; ?>
            </div>
            <h4 class="text-center py-3">Data Peminjaman</h4>
            <?php if (!empty($tableHeading)) : ?>
            <p class="text-center">
                <?= $tableHeading ?>
            </p>
            <?php endif; ?>
            <br>
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 5%;">No.</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Jumlah Aset</th>
                            <th>Status</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="py-2">
                        <?php foreach ($dataUser as $key => $value) : ?>
                        <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                            <td>
                                <?= $key + 1 ?>
                            </td>
                            <?php
                                $originalDate = $value->tanggal;
                                $formattedDate = date('d F Y', strtotime($originalDate));
                                ?>
                            <td data-sort="<?= strtotime($originalDate) ?>">
                                <?php echo $formattedDate; ?>
                            </td>
                            <td>
                                <?= $value->namaLab ?>
                            </td>
                            <td class="text-center">
                                <?= $value->jumlahPeminjaman ?>
                            </td>
                            <td style="width: 10%">
                                <?php if ($value->loanStatus == "Approve" || $value->loanStatus == "Peminjaman") : ?>
                                <span class="badge bg-success">Approve</span>
                                <?php elseif ($value->loanStatus == "Request") : ?>
                                <span class="badge bg-primary">Request</span>
                                <?php elseif ($value->loanStatus == "Reject") : ?>
                                <span class="badge bg-danger">Reject</span>
                                <?php elseif ($value->loanStatus == "Completed" || $value->loanStatus == "Pengembalian") : ?>
                                <span class="badge bg-info">Completed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($value->loanStatus == "Peminjaman") : ?>
                                <a href="<?= site_url('peminjamanDataUser/userDetail/' . $value->idManajemenPeminjaman) ?>"
                                    class="btn btn-success btn-icon me-2"> <i data-feather="info"></i></a>
                                <a href="<?= site_url('peminjamanDataUser/print/' . $value->idManajemenPeminjaman) ?>"
                                    target="_blank" class="btn btn-secondary btn-icon"> <i
                                        data-feather="printer"></i></a>
                                <?php endif; ?>
                                <?php if ($value->loanStatus == "Pengembalian"): ?>
                                <a href="<?= site_url('peminjamanDataUser/userDetail/' . $value->idManajemenPeminjaman) ?>"
                                    class="btn btn-success btn-icon"> <i data-feather="info"></i></a>
                                <?php endif; ?>
                                <?php if ($value->loanStatus == "Request" || $value->loanStatus == "Reject"  || $value->loanStatus == "Approve"): ?>
                                <a href="<?= site_url('peminjamanDataUser/userRequestHistory/' . $value->idRequestPeminjaman) ?>"
                                    class="btn btn-primary btn-icon"> <i data-feather="info"></i></a>
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