<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Layanan Aset Laboratorium &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Layanan Aset Laboratorium</h4>
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
                <form action="<?= site_url('layananLabAset')?>" method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tanggal">
                                <input type="text" class="form-control" name="tanggal">
                                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true" id="idIdentitasSarana" name="idIdentitasSarana">
                                <option value="" selected disabled hidden>Pilih Nama Aset</option>
                                <?php foreach ($dataIdentitsaSarana as $key => $value): ?>
                                    <option value="<?= $value->idIdentitasSarana ?>"><?= $value->namaSarana ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kodeRincianLabAset" class="col-sm-3 col-form-label">Kode Aset</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="2" tabindex="-1" aria-hidden="true" id="kodeRincianLabAset" name="kodeRincianLabAset">
                                <option value="" selected disabled hidden>Pilih Kode Aset</option>
                            </select>
                            <input type="text" class="form-control" id="idRincianLabAset" name="idRincianLabAset" placeholder="Menampilkan ID rincian aset" hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLab" class="col-sm-3 col-form-label">Lokasi Aset</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="idIdentitasLab" name="idIdentitasLab"
                                placeholder="Masukkan Lokasi" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Aset</label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" id="idKategoriManajemen" name="idKategoriManajemen"
                                placeholder="Masukkan Kategori Aset" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idStatusLayanan" class="col-sm-3 col-form-label">Status Layanan</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="3" tabindex="-1" aria-hidden="true" id="idStatusLayanan" name="idStatusLayanan">
                                <option value="" selected disabled hidden>Pilih status layanan</option>
                                <?php foreach($dataStatusLayanan as $key =>$value): ?>
                                <option value="<?=$value->idStatusLayanan?>"><?=$value->namaStatusLayanan?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="4" tabindex="-1" aria-hidden="true"  id="idSumberDana" name="idSumberDana">
                                <option value="" selected disabled hidden>Pilih sumber dana</option>
                                <?php foreach($dataSumberDana as $key =>$value): ?>
                                <option value="<?=$value->idSumberDana?>"><?=$value->namaSumberDana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="biaya" class="col-sm-3 col-form-label">Biaya</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="biaya" name="biaya"
                                placeholder="Masukkan biaya">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti" placeholder="Masukkan link dokumentasi">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="4"
                                placeholder="Masukkan keterangan layanan"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('layananLabAset') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $("#idIdentitasSarana").on("change", function() {
            var selectedIdIdentitasSarana = $(this).val();
            var $kodeRincianLabAsetSelect = $("#kodeRincianLabAset");

            $.ajax({
                url: "<?= site_url('getKodeRincianLabAsetBySarana') ?>",
                type: "POST",
                data: {
                    idIdentitasSarana: selectedIdIdentitasSarana,
                },
                dataType: "json",
                success: function(response) {
                    $kodeRincianLabAsetSelect.empty();
                    if (response.length === 0) {
                        $kodeRincianLabAsetSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $kodeRincianLabAsetSelect.append("<option value='' selected disabled hidden>Pilih Kode Aset</option>");
                        $.each(response, function(key, value) {
                            $kodeRincianLabAsetSelect.append("<option value='" + value.kodeRincianLabAset + "'>" + value.kodeRincianLabAset + "</option>");
                        });
                    }
                },
                error: function() {
                    alert("Failed to retrieve kode rincian aset options.");
                }
            });
        });

        $("#kodeRincianLabAset").on("change", function() {
            var selectedKodeRincianLabAset = $(this).val();
            var $idIdentitasLabSelect = $("#idIdentitasLab");

            $.ajax({
                url: "<?= site_url('getIdentitasLabByKodeRincianLabAset') ?>",
                type: "POST",
                data: {
                    kodeRincianLabAset: selectedKodeRincianLabAset,
                },
                dataType: "json",
                success: function(response) {
                    if (response.idIdentitasLab) {
                        $idIdentitasLabSelect.val(response.namaLab);
                    }
                },
                error: function() {
                    alert("Failed to retrieve lokasi.");
                }
            });
        });

        $("#kodeRincianLabAset").on("change", function() {
            var selectedKodeRincianLabAset = $(this).val();
            var $idKategoriManajemenSelect = $("#idKategoriManajemen"); 

            $.ajax({
                url: "<?= site_url('getKategoriManajemenByKodeRincianLabAset') ?>",
                type: "POST",
                data: {
                    kodeRincianLabAset: selectedKodeRincianLabAset,
                },
                dataType: "json",
                success: function(kategoriManajemenResponse) {
                    if (kategoriManajemenResponse.idKategoriManajemen) {
                        $idKategoriManajemenSelect.val(kategoriManajemenResponse.namaKategoriManajemen);
                    }
                },
                error: function() {
                    alert("Failed to retrieve kategori aset.");
                }
            });
        });

        $("#kodeRincianLabAset").on("change", function() {
        var selectedKodeRincianLabAset = $(this).val();
        var $idRincianLabAsetInput = $("#idRincianLabAset");

        $.ajax({
            url: "<?= site_url('getIdRincianLabAsetByKodeRincianLabAset') ?>",
            type: "POST",
            data: {
                kodeRincianLabAset: selectedKodeRincianLabAset,
            },
            dataType: "json",
            success: function(response) {
                if (response.idRincianLabAset) {
                    $idRincianLabAsetInput.val(response.idRincianLabAset);
                }
            },
            error: function() {
                alert("Failed to retrieve ID rincian aset.");
            }
        });
    });
    });
</script>

<?= $this->endSection(); ?>