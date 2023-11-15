<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Manajemen Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item active" aria-current="page">Manajemen Peminjaman </li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Manajemen Peminjaman </h4>
    </div>
</div>

<div class="row">
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
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width: 10%;">No.</th>
                                <th style="width: 30%;">Kode Laboratorium</th>
                                <th>Nama</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataLaboratorium as $key => $value) : ?>
                                <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                    <td>
                                        <?= $key + 1 ?>
                                    </td>
                                    <td class="text-center"><?= $value->kodeLab ?></td>
                                    <td class="text-left"><?= $value->namaLab ?></td>
                                    <td class="text-center">
                                        <a href="<?= site_url('manajemenPeminjaman/loan/' . $value->idIdentitasLab) ?>" class="btn btn-outline-success">Ajukan Peminjaman</a>
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