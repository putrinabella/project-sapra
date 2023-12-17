<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sarana</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Peminjaman</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <form action="<?= site_url('dataAsetPeminjaman') ?>" class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="startDatePicker">
                <input type="text" class="form-control border-primary" id="startDate" name="startDate" placeholder="Start Date"
                    readonly>
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar"></i></span>
            </div>
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="endDatePicker">
                <input type="text" class="form-control border-primary" id="endDate" name="endDate" placeholder="End Date" readonly>
                <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar"></i></span>
            </div>
            <div class="col py-3 p-0 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-icon me-1">
                    <i data-feather="filter"></i>
                </button>
                <a href="<?= site_url('dataAsetPeminjaman') ?>" class="btn btn-success btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('dataAsetPeminjaman/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="trash"></i>
            Recycle Bin
        </a>
        <div class="dropdown">
            <?php
                if (empty($_GET['startDate']) && empty($_GET['endDate'])) {
                    $exportLink = site_url('dataAsetPeminjaman/export');
                    $printAllLink = site_url('dataAsetPeminjaman/printAll');
                } else {
                    $startDate = $_GET['startDate'] ?? '';
                    $endDate = $_GET['endDate'] ?? '';
                    $exportLink = site_url("dataAsetPeminjaman/export?startDate=$startDate&endDate=$endDate");
                    $printAllLink = site_url("dataAsetPeminjaman/printAll?startDate=$startDate&endDate=$endDate");
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

        <a href="<?= site_url('manajemenAsetPeminjaman') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
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
                            <th>NIS/NIP</th>
                            <th>Nama Peminjam</th>
                            <th>Karwayan/Siswa</th>
                            <th>Jumlah Aset Dipinjam</th>
                            <th>Status</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="py-2">
                        <?php foreach ($dataDataAsetPeminjaman as $key => $value) : ?>
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
                            <td class="text-left">
                                <?= $value->nis; ?>
                            </td>
                            <td class="text-left">
                                <?= $value->namaSiswa; ?>
                            </td>
                            <td class="text-left">
                                <?= $value->namaKelas; ?>
                            </td>
                            <td class="text-center">
                                <?= $value->jumlahPeminjaman ?>
                            </td>
                            <td>
                                <?php if ($value->loanStatus == "Peminjaman") : ?>
                                <span class="badge bg-warning">Sedang Dipinjam</span>
                                <?php elseif ($value->loanStatus == "Pengembalian"): ?>
                                <span class="badge bg-success">Sudah Dikembalikan</span>
                                <?php elseif ($value->loanStatus == "Dibatalkan"): ?>
                                <span class="badge bg-danger">Dibatalkan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($value->loanStatus == "Peminjaman") : ?>
                                <a href="<?= site_url('dataAsetPeminjaman/print/' . $value->idManajemenAsetPeminjaman) ?>"
                                    target="_blank" class="btn btn-secondary btn-icon"> <i
                                        data-feather="printer"></i></a>
                                <a href="<?= site_url('dataAsetPeminjaman/' . $value->idManajemenAsetPeminjaman . '/edit') ?>"
                                    class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                <form action="<?= site_url('dataAsetPeminjaman/revokeLoan/' .  $value->idManajemenAsetPeminjaman) ?>"
                                    method="post" class="d-inline" id="del-<?= $value->idManajemenAsetPeminjaman; ?>">
                                    <button class="btn btn-danger btn-icon"
                                        data-confirm="Apakah anda yakin membatalkan peminjaman ini?"  data-bs-toggle="tooltip" data-bs-placement="top" title="Batalkan Peminjaman">
                                        <i data-feather="alert-triangle"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if ($value->loanStatus == "Pengembalian" || $value->loanStatus == "Dibatalkan"): ?>
                                <a href="<?= site_url('dataAsetPeminjaman/history/' . $value->idManajemenAsetPeminjaman) ?>"
                                    class="btn btn-success btn-icon"> <i data-feather="info"></i></a>
                                <form action="<?= site_url('dataAsetPeminjaman/' .  $value->idManajemenAsetPeminjaman) ?>"
                                    method="post" class="d-inline" id="del-<?= $value->idManajemenAsetPeminjaman; ?>">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-danger btn-icon"
                                        data-confirm="Apakah anda yakin menghapus data ini?">
                                        <i data-feather="trash"></i>
                                    </button>
                                </form>
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