<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Form Pengaduan &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaduan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Form Pengaduan</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <form action="<?= site_url('formPengaduanUser/tambahPengaduan') ?>" method="POST"
                    enctype="multipart/form-data" id="custom-validation">
                    <?php foreach ($dataPertanyaanPengaduan as $value): ?>
                    <input type="text" class="form-control border-0" name="idPertanyaanPengaduan[]"
                        id="idPertanyaanPengaduan" value="<?= $value->idPertanyaanPengaduan; ?>" hidden>
                    <p>
                        <?= $value->pertanyaanPengaduan; ?>
                    </p>
                    <textarea class="form-control"
                        name="isiPengaduan[<?= $value->idPertanyaanPengaduan; ?>]"></textarea>
                    <br>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary">Submit </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>