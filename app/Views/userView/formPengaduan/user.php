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
                <form action="<?= site_url('#') ?>" method="POST" enctype="multipart/form-data" id="custom-validation">
                    <?php foreach ($dataPertanyaanPengaduan as $key => $value): ?>
                    <div class="row">
                        <input type="text" class="form-control border-0" name="idPertanyaanPengaduan"
                            id="idPertanyaanPengaduan" value="<?= $value->idPertanyaanPengaduan; ?>" hidden>
                        <input type="text" class="form-control border-0" name="pertanyaan" id="pertanyaan"
                            value="<?= $value->pertanyaanPengaduan; ?>">
                    </div>
                    <div class="row">
                        <div class="col">

                            <input type="radio" name="question4" value="5"> SP (Sangat Puas)
                            <input type="radio" name="question4" value="4"> P (Puas)
                            <input type="radio" name="question4" value="3"> N (Netral)
                            <input type="radio" name="question4" value="2"> TP (Tidak Puas)
                            <input type="radio" name="question4" value="1"> ST (Sangat Tidak Puas)
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <!-- <button type="submit">Submit Form</button> -->
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>