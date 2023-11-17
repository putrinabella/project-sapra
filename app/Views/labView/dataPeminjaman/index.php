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
        <form action="<?= site_url('dataPeminjaman') ?>" class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="startDatePicker">
                <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Start Date"
                    readonly>
                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
            </div>
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="endDatePicker">
                <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" readonly>
                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
            </div>
            <div class="col py-3 p-0 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-icon me-1">
                    <i data-feather="filter"></i>
                </button>
                <a href="<?= site_url('dataPeminjaman') ?>" class="btn btn-success btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('dataPeminjaman/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="trash"></i>
            Recycle Bin
        </a>
        <div class="dropdown">
            <?php
            if (empty($_GET['startDate']) && empty($_GET['endDate'])) {
                $exportLink = site_url('dataPeminjaman/export');
                $generatePDFLink = site_url('dataPeminjaman/generatePDF');
            } else {
                $startDate = $_GET['startDate'] ?? '';
                $endDate = $_GET['endDate'] ?? '';
                $exportLink = site_url("dataPeminjaman/export?startDate=$startDate&endDate=$endDate");
                $generatePDFLink = site_url("dataPeminjaman/generatePDF?startDate=$startDate&endDate=$endDate");
            }
            ?>
            <button class="btn btn-success btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Export File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= $exportLink ?>">Download as Excel</a>
                <a class="dropdown-item" href="<?= $generatePDFLink ?>">Download as PDF</a>
            </div>
        </div>
        <a href="<?= site_url('manajemenPeminjaman') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
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
            <p>
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
                            <th>Nama Peminjam</th>
                            <th>Karwayan/Siswa</th>
                            <th>Lokasi</th>
                            <th>Jumlah Aset Dipinjam</th>
                            <th>Status</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="py-2">
                        <?php foreach ($dataDataPeminjaman as $key => $value) : ?>

                        <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                            <td>
                                <?= $key + 1 ?>
                            </td>
                            <td class="text-left">
                                <?= date('d F Y', strtotime($value->tanggal)) ?>
                            </td>
                            <td class="text-left">
                                <?= $value->namaPeminjam ?>
                            </td>
                            <td class="text-left">
                                <?= $value->asalPeminjam ?>
                            </td>
                            <td>
                                <?= $value->namaLab ?>
                            </td>
                            <td class="text-center">
                                <?= $value->jumlahPeminjaman ?>
                            </td>
                            <td class="text-center">
                                <?php if ($value->loanStatus == "Peminjaman") : ?>
                                <span class="badge bg-warning">Sedang Dipinjam</span>
                                <?php else : ?>
                                <span class="badge bg-success">Sudah Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($value->loanStatus == "Peminjaman") : ?>
                                <a href="<?= site_url('dataPeminjaman/print/' . $value->idManajemenPeminjaman) ?>" target="_blank"
                                    class="btn btn-secondary btn-icon"> <i data-feather="printer"></i></a>
                                <a href="<?= site_url('dataPeminjaman/' . $value->idManajemenPeminjaman . '/edit') ?>"
                                    class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                <?php endif; ?>
                                <?php if ($value->loanStatus == "Pengembalian"): ?>
                                <a href="<?= site_url('dataPeminjaman/history/' . $value->idManajemenPeminjaman) ?>"
                                    class="btn btn-success btn-icon"> <i data-feather="info"></i></a>
                                <?php endif; ?>
                                <form action="<?= site_url('dataPeminjaman/' .  $value->idManajemenPeminjaman) ?>"
                                    method="post" class="d-inline" id="del-<?= $value->idManajemenPeminjaman; ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-danger btn-icon"
                                        data-confirm="Apakah anda yakin menghapus data ini?">
                                        <i data-feather="trash"></i>
                                    </button>
                                </form>
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