<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Pertanyaaan Feedback &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Data Master</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('pertanyaanFeedback')?>">Pertanyaan Feedback</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <form action="<?= site_url('pertanyaanFeedback/'.$dataPertanyaanFeedback->idPertanyaanFeedback)?>" method="post" autocomplete="off"  id="custom-validation">
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="pertanyaanFeedback" class="col-sm-3 col-form-label">Pertanyaan Feedback</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="pertanyaanFeedback" name="pertanyaanFeedback"
                                placeholder="Masukkan pertanyaan" value="<?=$dataPertanyaanFeedback->pertanyaanFeedback?>" >
                        </div>
                    </div>                    
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('pertanyaanFeedback') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection(); ?>