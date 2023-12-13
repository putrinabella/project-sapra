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

                <div class="row mb-3 mt-5">
                    <h5 class="text-center text-decoration-underline mb-3">Data Aset Dipinjam</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" style="width: 100%;" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 5%;">No.</th>
                                    <th>Pertanyaan</th>
                                    <th>Pengaduan</th>
                                </tr>
                            </thead>
                            <tbody class="py-2">
                                <?php foreach ($dataPengaduan as $key => $value) : ?>
                                <tr style=" vertical-align: middle;">
                                    <td>
                                        <?= $key + 1 ?>
                                    </td>
                                    <td style="width: 22%">
                                        <?= $value->pertanyaanPengaduan; ?>
                                    </td>
                                    <td style="width: 22%">
                                        <?= $value->isiPengaduan; ?>
                                    </td>
                                    <td>
                                        <?php if ($value->statusPengaduan == "request") : ?>
                                        <span class="badge bg-primary">Diajukan</span>
                                        <?php elseif ($value->statusPengaduan == "process") : ?>
                                        <span class="badge bg-warning">Diproses</span>
                                        <?php elseif ($value->statusPengaduan == "done") : ?>
                                        <span class="badge bg-info">Selesai</span>
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
</div>
<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>


<?= $this->endSection(); ?>