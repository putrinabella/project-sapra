<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Restore Database &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Database</a></li>
        <li class="breadcrumb-item active" aria-current="page">Restore Database</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div>
                    <?php if(session()->getFlashdata('success')) :?>
                    <div class="alert alert-success alert-dismissible show fade" role="alert" id="alert">
                        <div class="alert-body">
                            <b>Success!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="btn-close"></button>
                            <?=session()->getFlashdata('success')?>
                        </div>
                    </div>
                    <br>
                    <?php endif; ?>
                    <?php if(session()->getFlashdata('error')) :?>
                    <div class="alert alert-danger alert-dismissible show fade" role="alert" id="alert">
                        <div class="alert-body">
                            <b>Error!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="btn-close"></button>
                            <?=session()->getFlashdata('error')?>
                        </div>
                    </div>
                    <br>
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-6">
                        <form action="<?= site_url('restoreDatabase') ?>" method="post" enctype="multipart/form-data">
                            <input class="form-control" type="file" name="database" id="database" accept=".sql"
                                required>
                            <br>
                            <button type="submit" class="btn btn-primary">Restore Database</button>
                        </form>
                    </div>
                    <div class="col-6" style="text-align: justify;">
                        <strong>Peringatan:</strong> Hanya file database yang sebelumnya dapat dibackup melalui website
                        ini yang dapat direstore.
                        <p>Silakan pilih file database yang sesuai dengan backup yang dihasilkan oleh layanan kami.
                            Dalam upaya memastikan integritas dan keamanan data, restore hanya dapat dilakukan
                            menggunakan file backup yang dihasilkan secara internal melalui website ini.</p>
                        <br>
                        <b>Catatan:</b> Anda tidak dapat melakukan restore menggunakan file database dari aplikasi lain
                        seperti
                        SQLyog atau phpMyAdmin. Pastikan menggunakan file backup yang dihasilkan melalui fungsi backup
                        di dalam webiste ini.
                    </div>
                </div>
                <!-- <div>
                    <form action="<?= site_url('restoreDatabase') ?>" method="post" enctype="multipart/form-data">
                        <input class="form-control" type="file" name="database" id="database" accept=".sql" required>
                        <br>
                        <button type="submit" class="btn btn-primary">Restore Database</button>
                    </form>
                </div> -->
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>