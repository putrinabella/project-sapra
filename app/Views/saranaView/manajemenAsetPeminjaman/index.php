<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Pengajuan Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Prasarana</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pengajuan Peminjaman </li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Pengajuan Peminjaman </h4>
    </div>
</div>
<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <?php foreach ($dataPrasarana as $key => $value) : ?>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <?php if ($value->picturePath !== null) : ?>
                    <img src="<?= base_url($value->picturePath) ?>" class="card-img-top" alt="Foto ruangan"
                        style="max-height: 200px;">
                    <?php else : ?>
                    <img src="<?= base_url(); ?>/assets/images/Ruangan.jpeg" class="card-img-top"
                        alt="Default Foto ruangan" style="max-height: 200px;">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title text-center">
                            <?= $value->namaPrasarana ?> (
                            <?= $value->kodePrasarana; ?> )
                        </h5>
                        <p class="card-text mb-3">
                            <span class="badge rounded-pill border border-primary text-primary">
                                <?= $value->namaGedung; ?>
                            </span>
                            <span class="badge rounded-pill border border-primary text-primary">
                                <?= $value->namaLantai; ?>
                            </span>
                            <span class="badge rounded-pill border border-primary text-primary">
                                Luas:
                                <?= $value->luas; ?> m&sup2
                            </span>
                        </p>
                        <a href="<?= site_url('manajemenAsetPeminjaman/loan/' . $value->idIdentitasPrasarana) ?>" class="btn btn-primary">Ajukan Peminjaman</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>