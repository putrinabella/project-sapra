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
                    <?php foreach ($dataPertanyaanPengaduan as $key => $value): ?>
                    <input type="text" class="form-control border-0" name="idPertanyaanPengaduan[]"
                        id="idPertanyaanPengaduan" value="<?= $value->idPertanyaanPengaduan; ?>" hidden>
                    <p>
                        <?= $key+1 . ". " . $value->pertanyaanPengaduan; ?>
                    </p>
                    <textarea class="form-control" style="margin-left: 15px;"
                        name="isiPengaduan[<?= $value->idPertanyaanPengaduan; ?>]" required></textarea>
                    <br>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('#submitBtn').click(function () {
            var isValid = true;

            $('textarea').each(function () {
                if ($.trim($(this).val()) == '') {
                    isValid = false;
                    return false; 
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Silahkan input pengaduan Anda!',
                });
            } else {
                $('#custom-validation').submit();
            }
        });
    });
</script>

<?= $this->endSection(); ?>
