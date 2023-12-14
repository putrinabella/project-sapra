<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Form Umpan Balik &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pengaduan</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('dataFeedbackUser')?>">Data Umpan Balik</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <form action="<?= site_url('dataFeedbackUser/addFeedback/'.$idFormFeedback) ?>" method="POST"
                    enctype="multipart/form-data" id="custom-validation">
                    <?php foreach ($dataFeedback as $key => $value): ?>
                    <input type="text" class="form-control border-0" name="idPertanyaanFeedback[]"
                        id="idPertanyaanFeedback" value="<?= $value->idPertanyaanFeedback; ?>" hidden>
                    <p>
                        <?= $key+1 . ". " . $value->pertanyaanFeedback; ?>
                    </p>
                    <div class="mb-3" style="margin-left: 15px;">
                        <div class="form-check mb-2 mt-2">
                            <input type="radio" class="form-check-input"
                                name="isiFeedback[<?= $value->idPertanyaanFeedback; ?>]" value="5" id="sangatPuas">
                            <label class="form-check-label" for="sangatPuas">
                                Sangat Puas
                            </label>
                        </div>
                        <div class="form-check mb-2 mt-2">
                            <input type="radio" class="form-check-input"
                                name="isiFeedback[<?= $value->idPertanyaanFeedback; ?>]" value="4" id="puas">
                            <label class="form-check-label" for="puas">
                                Puas
                            </label>
                        </div>
                        <div class="form-check mb-2 mt-2">
                            <input type="radio" class="form-check-input"
                                name="isiFeedback[<?= $value->idPertanyaanFeedback; ?>]" value="3" id="netral">
                            <label class="form-check-label" for="netral">
                                Netral
                            </label>
                        </div>
                        <div class="form-check mb-2 mt-2">
                            <input type="radio" class="form-check-input"
                                name="isiFeedback[<?= $value->idPertanyaanFeedback; ?>]" value="2" id="tidakPuas">
                            <label class="form-check-label" for="tidakPuas">
                                Tidak Puas
                            </label>
                        </div>
                        <div class="form-check mb-2 mt-2">
                            <input type="radio" class="form-check-input"
                                name="isiFeedback[<?= $value->idPertanyaanFeedback; ?>]" value="1" id="sangatTidakPuas">
                            <label class="form-check-label" for="sangatTidakPuas">
                                Sangat Tidak Puas
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary">Submit </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Any additional JavaScript code can be added here if needed
</script>

<?= $this->endSection(); ?>