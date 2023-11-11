<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Tambah Data</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="example">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item col-6">
                            <a class="nav-link active text-center" id="identitasPeminjam-tab" data-bs-toggle="tab"
                                href="#identitasPeminjam" role="tab" aria-controls="identitasPeminjam"
                                aria-selected="true">Identitas Peminjam</a>
                        </li>
                        <li class="nav-item col-6">
                            <a class="nav-link text-center" id="dataPinjaman-tab" data-bs-toggle="tab"
                                href="#dataPinjaman" role="tab" aria-controls="dataPinjaman"
                                aria-selected="false">Aset</a>
                        </li>
                    </ul>
                    <div class="tab-content border border-top-0 p-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="identitasPeminjam" role="tabpanel"
                            aria-labelledby="identitasPeminjam-tab">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row mb-3">
                                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                                        <div class="col-sm-9">
                                            <div class="input-group date datepicker m-0" id="tanggal">
                                                <input type="text" class="form-control" name="tanggal"
                                                    placeholder="Masukkan tanggal" readonly>
                                                <span class="input-group-text input-group-addon"><i
                                                        data-feather="calendar"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama
                                            Peminjam</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="namaPeminjam"
                                                name="namaPeminjam" placeholder="Masukkan nama">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="kategoriPeminjam"
                                            class="col-sm-3 col-form-label">Karyawan/Siswa</label>
                                        <div class="col-sm-9">
                                            <select
                                                class="js-example-basic-single form-select select2-hidden-accessible"
                                                data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true"
                                                id="kategoriPeminjam" name="kategoriPeminjam">
                                                <option value="" selected disabled hidden>Pilih Kategori</option>
                                                <option value="karyawan">Karyawan</option>
                                                <option value="siswa">Siswa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="asalPeminjam" class="col-sm-3 col-form-label">Role</label>
                                        <div class="col-sm-9">
                                            <select
                                                class="js-example-basic-single form-select select2-hidden-accessible"
                                                data-width="100%" data-select2-id="2" tabindex="-1" aria-hidden="true"
                                                id="asalPeminjam" name="asalPeminjam">
                                                <option value="" selected disabled hidden>Pilih Role</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- ROW 2 -->
                                <div class="col-6">
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="dataPinjaman" role="tabpanel" aria-labelledby="dataPinjaman-tab">
                            <div class="row mb-3">
                                <label for="idIdentitasLab" class="col-sm-1 col-form-label">Lokasi</label>
                                <div class="col-sm-3">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible"
                                        data-width="100%" tabindex="-1" aria-hidden="true" id="idIdentitasLab"
                                        name="idIdentitasLab">
                                        <option value="" selected disabled hidden>Pilih Lokasi</option>
                                        <?php foreach ($dataPrasaranaLab as $key => $value): ?>
                                        <option value="<?= $value->idIdentitasLab ?>">
                                            <?= $value->namaLab ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="idIdentitasSarana" class="col-sm-1 col-form-label">Aset</label>
                                <div class="col-sm-3">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible"
                                        data-width="100%" tabindex="-1" aria-hidden="true" id="idIdentitasSarana"
                                        name="idIdentitasSarana">
                                        <option value="" selected disabled hidden>Pilih Aset</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kodeRincianLabAset" class="col-sm-1 col-form-label">Kode
                                    Aset</label>
                                <div class="col-sm-3">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible"
                                        data-width="100%" data-select2-id="6" tabindex="-1" aria-hidden="true"
                                        id="kodeRincianLabAset" name="kodeRincianLabAset">
                                        <option value="" selected disabled hidden>Pilih Kode Aset</option>
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <button type="button" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                            <div class="row mb-3 mt-5">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="dataTable">
                                        <thead>
                                            <tr class="text-center">
                                                <th style="width: 5%;">No.</th>
                                                <th style="width: 12%;">Kode Aset</th>
                                                <th>Lokasi</th>
                                                <th>Kategori Aset</th>
                                                <th>Nama Aset</th>
                                                <th>Status</th>
                                                <th>Sumber Dana</th>
                                                <th>Tahun Pengadaan</th>
                                                <th>Merek</th>
                                                <th>Warna</th>
                                                <th style="width: 20%;">Aksi</th>
                                            </tr>
                                            </tr>
                                        </thead>
                                        <tbody class="py-2">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $("#kategoriPeminjam").on("change", function () {
            var selectedKategoriPeminjam = $(this).val();
            var $asalPeminjamSelect = $("#asalPeminjam");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getRole') ?>",
                type: "POST",
                data: {
                    kategoriPeminjam: selectedKategoriPeminjam,
                },
                dataType: "json",
                success: function (response) {
                    $asalPeminjamSelect.empty();
                    if (response.length === 0) {
                        $asalPeminjamSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $asalPeminjamSelect.append("<option value='' selected disabled hidden>Pilih Role</option>");
                        $.each(response, function (key, value) {
                            if (selectedKategoriPeminjam === 'siswa') {
                                $asalPeminjamSelect.append("<option value='" + value.namaKelas + "'>" + value.namaKelas + "</option>");
                            } else if (selectedKategoriPeminjam === 'karyawan') {
                                $asalPeminjamSelect.append("<option value='" + value.namaKategoriPegawai + "'>" + value.namaKategoriPegawai + "</option>");
                            }
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve data");
                }
            });
        });

        $("#idIdentitasLab").on("change", function () {
            var selectedIdIdentitasLab = $(this).val();
            var $idIdentitasSaranaSelect = $("#idIdentitasSarana");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getSaranaByLab') ?>",
                type: "POST",
                data: {
                    idIdentitasLab: selectedIdIdentitasLab,
                },
                dataType: "json",
                success: function (response) {
                    $idIdentitasSaranaSelect.empty();
                    if (response.length === 0) {
                        $idIdentitasSaranaSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $idIdentitasSaranaSelect.append("<option value='' selected disabled hidden>Pilih Aset</option>");
                        $.each(response, function (key, value) {
                            $idIdentitasSaranaSelect.append("<option value='" + value.idIdentitasSarana + "'>" + value.namaSarana + "</option>");
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve kode rincian aset options.");
                }
            });
        });

        $("#idIdentitasSarana").on("change", function () {
            var selectedIdIdentitasSarana = $(this).val();
            var selectedIdIdentitasLab = $("#idIdentitasLab").val();
            var $kodeRincianLabAsetSelect = $("#kodeRincianLabAset");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getKodeBySarana') ?>",
                type: "POST",
                data: {
                    idIdentitasSarana: selectedIdIdentitasSarana,
                    idIdentitasLab: selectedIdIdentitasLab,
                },
                dataType: "json",
                success: function (response) {
                    $kodeRincianLabAsetSelect.empty();
                    if (response.length === 0) {
                        $kodeRincianLabAsetSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $kodeRincianLabAsetSelect.append("<option value='' selected disabled hidden>Pilih Kode Aset</option>");
                        $.each(response, function (key, value) {
                            $kodeRincianLabAsetSelect.append("<option value='" + value.idRincianLabAset + "'>" + value.kodeRincianLabAset + "</option>");
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve kode rincian lab aset options.");
                }
            });
        });

    });


</script>
<?= $this->endSection(); ?>