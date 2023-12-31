<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Dokumen Sekolah &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('profilSekolah')?>">Profil Sekolah</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                
            <form action="<?= site_url('profilSekolah/updateDokumen/'.$dataDokumenSekolah->idDokumenSekolah) ?>" method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
                    
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="namaDokumenSekolah" class="col-sm-3 col-form-label">Nama</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaDokumenSekolah" name="namaDokumenSekolah" value="<?=$dataDokumenSekolah->namaDokumenSekolah?>" placeholder="Masukkan nama" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="linkDokumenSekolah" class="col-sm-3 col-form-label">Link</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="linkDokumenSekolah" name="linkDokumenSekolah" value="<?=$dataDokumenSekolah->linkDokumenSekolah?>" placeholder="Masukkan link" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('profilSekolah') ?>" class="btn btn-secondary me-2">Cancel</a>
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