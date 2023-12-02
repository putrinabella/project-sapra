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
        <form action="<?= site_url('requestPeminjaman') ?>" class="d-flex align-items-center flex-wrap text-nowrap">
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
                <a href="<?= site_url('requestPeminjaman') ?>" class="btn btn-success btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('requestPeminjaman/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="trash"></i>
            Recycle Bin
        </a>
        <div class="dropdown">
            <?php
                if (empty($_GET['startYear']) && empty($_GET['endYear'])) {
                    $exportLink = site_url('requestPeminjaman/export');
                    $printAllLink = site_url('requestPeminjaman/printAll');
                } else {
                    $startYear = $_GET['startYear'] ?? '';
                    $endYear = $_GET['endYear'] ?? '';
                    $exportLink = site_url("requestPeminjaman/export?startYear=$startYear&endYear=$endYear");
                    $printAllLink = site_url("requestPeminjaman/printAll?startYear=$startYear&endYear=$endYear");
                }
            ?>
            <button class="btn btn-success btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Export File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= $exportLink ?>">Download as Excel</a>
                <a class="dropdown-item" href="<?= $printAllLink ?>">Download as ZIP</a>
            </div>
        </div>
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
                            <th>NIS/NIP</th>
                            <th>Nama Peminjam</th>
                            <th>Karwayan/Siswa</th>
                            <th>Lokasi</th>
                            <th style="width: 15%;">Jumlah Aset</th>
                            <th>Status</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="py-2">
                        <?php foreach ($dataRequestPeminjaman as $key => $value) : ?>
                        <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                            <td>
                                <?= $key + 1 ?>
                            </td>
                            <td class="text-left">
                                <?= date('d F Y', strtotime($value->tanggal)) ?>
                            </td>
                            <td class="text-left">
                                <?= $value->nis; ?>
                            </td>
                            <td class="text-left">
                                <?= $value->namaSiswa; ?>
                            </td>
                            <td class="text-left">
                                <?= $value->namaKelas; ?>
                            </td>
                            <td>
                                <?= $value->namaLab ?>
                            </td>
                            <td>
                                <?php if ($value->loanStatus == "Approve") : ?>
                                    Request: <?= $value->jumlahPeminjaman ?>
                                    <br>
                                    Approve: <?= !empty($value->jumlahApprove) ? $value->jumlahApprove : '-'; ?>
                                <?php elseif ($value->loanStatus == "Request") : ?>
                                    Request: <?= $value->jumlahPeminjaman ?>
                                <?php elseif ($value->loanStatus == "Reject") : ?>
                                    Request: <?= $value->jumlahPeminjaman ?>
                                    <br>
                                    Approve: <?= !empty($value->jumlahApprove) ? $value->jumlahApprove : '0'; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($value->loanStatus == "Approve") : ?>
                                <span class="badge bg-success">Approve</span>
                                <?php elseif ($value->loanStatus == "Request") : ?>
                                <span class="badge bg-primary">Request</span>
                                <?php elseif ($value->loanStatus == "Reject") : ?>
                                <span class="badge bg-danger">Reject</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($value->loanStatus == "Request") : ?>
                                <a href="<?= site_url('requestPeminjaman/' . $value->idRequestPeminjaman . '/edit') ?>"
                                    class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                <?php endif; ?>                                
                                <?php if ($value->loanStatus == "Approve"  || $value->loanStatus == "Reject") : ?>
                                <a href="<?= site_url('requestPeminjaman/' . $value->idRequestPeminjaman) ?>"
                                    class="btn btn-success btn-icon"> <i data-feather="info"></i></a>
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