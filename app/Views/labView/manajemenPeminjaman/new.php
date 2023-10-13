<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Data Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  -->
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Data Peminjaman</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Tambah Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('rincianLabAset')?>" method="post" enctype="multipart/form-data"
                    autocomplete="off" id="custom-validation">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama Peminjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaPeminjam" name="namaPeminjam"
                                placeholder="Masukkan nama peminjam">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="asalPeminjam" class="col-sm-3 col-form-label">Asal Peminjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="asalPeminjam" name="asalPeminjam"
                                placeholder="Masukkan asal peminjam">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idIdentitasSarana" name="idIdentitasSarana">
                                <option value="" hidden>Pilih aset</option>
                                <?php foreach($dataSaranaLab as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasSarana?>"><?=$value->namaSarana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kodeLab" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="kodeLab" name="kodeLab">
                                <option value="" hidden>Pilih lokasi</option>
                                <?php foreach($dataPrasaranaLab as $key =>$value): ?>
                                <option value="<?=$value->kodeLab?>"><?=$value->namaLab?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jumlah" class="col-sm-3 col-form-label">Jumlah</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="jumlah" name="jumlah"
                                placeholder="Masukkan jumlah">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('rincianLabAset') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
    $('#kodeLab').select2({
        placeholder: 'Pilih lokasi',
    });

    $('#idIdentitasSarana').select2({
        placeholder: 'Pilih aset',
    });

    $('#idIdentitasSarana').change(function () {
        var idIdentitasSarana = $(this).val();
        $.ajax({
            url: 'getKodeLab/' + idIdentitasSarana,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                if (Array.isArray(response) && response.length > 0) {
                    $('#kodeLab').empty();
                    $.each(response, function (key, value) {
                        $('#kodeLab').append('<option value="' + value.kodeLab + '">' + value.namaLab + '</option>');
                    });
                } else {
                    console.error('Invalid or empty response from the server.');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX request failed:', textStatus, errorThrown);
            }
        });
    });
});

</script>



<?= $this->endSection(); ?>