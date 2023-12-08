<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Layanan Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">IT</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('layananAsetIt')?>">Layanan Aset</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <form action="<?= site_url('layananAsetIt/'.$dataLayananAsetIt->idSaranaLayananAset)?>"
                    method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
                    
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tanggal">
                                <input type="text" class="form-control bg-transparent" name="tanggal"
                                    value="<?=$dataLayananAsetIt->tanggal?>">
                                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true"
                                id="idIdentitasSarana" name="idIdentitasSarana">
                                <option value="" disabled hidden>Pilih Nama Aset</option>
                                <?php foreach ($dataIdentitsaSarana as $key => $value): ?>
                                <option value="<?= $value->idIdentitasSarana ?>" <?php echo ($value->idIdentitasSarana
                                    == $dataLayananAsetIt->idIdentitasSarana) ? 'selected' : '' ?>>
                                    <?= $value->namaSarana ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kodeRincianAset" class="col-sm-3 col-form-label">Kode Aset</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="2" tabindex="-1" aria-hidden="true"
                                id="kodeRincianAset" name="kodeRincianAset">
                                <option value="<?=$dataLayananAsetIt->kodeRincianAset?>">
                                    <?=$dataLayananAsetIt->kodeRincianAset?></option>
                            </select>
                            <input type="text" class="form-control" id="idRincianAset" name="idRincianAset"
                                placeholder="Menampilkan ID rincian aset"
                                value="<?=$dataLayananAsetIt->idRincianAset?>" hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasPrasarana" class="col-sm-3 col-form-label">Lokasi Aset</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="idIdentitasPrasarana"
                                name="idIdentitasPrasarana" placeholder="Masukkan Lokasi"
                                value="<?=$dataLayananAsetIt->namaPrasarana?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Aset</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="idKategoriManajemen" name="idKategoriManajemen"
                                placeholder="Masukkan Kategori Aset"
                                value="<?=$dataLayananAsetIt->namaKategoriManajemen?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idStatusLayanan" class="col-sm-3 col-form-label">Status Layanan</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="3" tabindex="-1" aria-hidden="true"
                                id="idStatusLayanan" name="idStatusLayanan">
                                <option value="" disabled hidden>Pilih status layanan</option>
                                <?php foreach ($dataStatusLayanan as $key => $value): ?>
                                <option value="<?= $value->idStatusLayanan ?>" <?php echo ($value->idStatusLayanan ==
                                    $dataLayananAsetIt->idStatusLayanan) ? 'selected' : '' ?>>
                                    <?= $value->namaStatusLayanan ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="4" tabindex="-1" aria-hidden="true" id="idSumberDana"
                                name="idSumberDana">
                                <option value="" selected disabled hidden>Pilih sumber dana</option>
                                <?php foreach ($dataSumberDana as $key => $value): ?>
                                <option value="<?= $value->idSumberDana ?>" <?php echo ($value->idSumberDana ==
                                    $dataLayananAsetIt->idSumberDana) ? 'selected' : '' ?>>
                                    <?= $value->namaSumberDana ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="biaya" class="col-sm-3 col-form-label">Biaya</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="biaya" name="biaya"
                                placeholder="Masukkan biaya" value="<?=$dataLayananAsetIt->biaya?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti"
                                placeholder="Masukkan link dokumentasi (Link Google Drive)" value="<?=$dataLayananAsetIt->bukti?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="keterangan" class="col-sm-3 col-form-label">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="4"
                                placeholder="Masukkan keterangan layanan"><?=$dataLayananAsetIt->keterangan?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('layananAsetIt') ?>" class="btn btn-secondary me-2">Cancel</a>
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
    $(document).ready(function () {
        $("#idIdentitasSarana").on("change", function () {
            var selectedIdIdentitasSarana = $(this).val();
            var $kodeRincianAsetSelect = $("#kodeRincianAset");

            $.ajax({
                url: "<?= site_url('getKodeRincianAsetBySarana') ?>",
                type: "POST",
                data: {
                    idIdentitasSarana: selectedIdIdentitasSarana,
                },
                dataType: "json",
                success: function (response) {
                    $kodeRincianAsetSelect.empty();
                    if (response.length === 0) {
                        $kodeRincianAsetSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $kodeRincianAsetSelect.append("<option value='' selected disabled hidden>Pilih Kode Aset</option>");
                        $.each(response, function (key, value) {
                            $kodeRincianAsetSelect.append("<option value='" + value.kodeRincianAset + "'>" + value.kodeRincianAset + "</option>");
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve kode rincian aset options.");
                }
            });
        });

        $("#kodeRincianAset").on("change", function () {
            var selectedKodeRincianAset = $(this).val();
            var $idIdentitasPrasaranaSelect = $("#idIdentitasPrasarana");

            $.ajax({
                url: "<?= site_url('getIdentitasPrasaranaByKodeRincianAset') ?>",
                type: "POST",
                data: {
                    kodeRincianAset: selectedKodeRincianAset,
                },
                dataType: "json",
                success: function (response) {
                    if (response.idIdentitasPrasarana) {
                        $idIdentitasPrasaranaSelect.val(response.namaPrasarana);
                    }
                },
                error: function () {
                    alert("Failed to retrieve lokasi.");
                }
            });
        });

        $("#kodeRincianAset").on("change", function () {
            var selectedKodeRincianAset = $(this).val();
            var $idKategoriManajemenSelect = $("#idKategoriManajemen");

            $.ajax({
                url: "<?= site_url('getKategoriManajemenByKodeRincianAset') ?>",
                type: "POST",
                data: {
                    kodeRincianAset: selectedKodeRincianAset,
                },
                dataType: "json",
                success: function (kategoriManajemenResponse) {
                    if (kategoriManajemenResponse.idKategoriManajemen) {
                        $idKategoriManajemenSelect.val(kategoriManajemenResponse.namaKategoriManajemen);
                    }
                },
                error: function () {
                    alert("Failed to retrieve kategori aset.");
                }
            });
        });

        $("#kodeRincianAset").on("change", function () {
            var selectedKodeRincianAset = $(this).val();
            var $idRincianAsetInput = $("#idRincianAset");

            $.ajax({
                url: "<?= site_url('getIdRincianAsetByKodeRincianAset') ?>",
                type: "POST",
                data: {
                    kodeRincianAset: selectedKodeRincianAset,
                },
                dataType: "json",
                success: function (response) {
                    if (response.idRincianAset) {
                        $idRincianAsetInput.val(response.idRincianAset);
                    }
                },
                error: function () {
                    alert("Failed to retrieve ID rincian aset.");
                }
            });
        });
    });
</script>


<?= $this->endSection(); ?>