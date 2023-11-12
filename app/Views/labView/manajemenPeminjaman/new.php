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
                    <a href="<?= site_url('manajemenPeminjaman') ?>" class="btn btn-icon-text btn-outline-primary me-2"> <i class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Form Peminjaman</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('manajemenPeminjaman/addLoan') ?>" method="POST" enctype="multipart/form-data" id="custom-validation">
                    <div class="row">
                        <div class="col-12">
                            <div>
                                <input type="text" class="form-control" id="loanStatus" name="loanStatus" value="Peminjaman" hidden>
                            </div>
                            <div class="row mb-3">
                                <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <div class="input-group date datepicker m-0" id="tanggal">
                                        <input type="text" class="form-control" name="tanggal" placeholder="Masukkan tanggal" readonly>
                                        <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama
                                    Peminjam</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="namaPeminjam" name="namaPeminjam" placeholder="Masukkan nama">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kategoriPeminjam" class="col-sm-3 col-form-label">Karyawan/Siswa</label>
                                <div class="col-sm-9">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1" aria-hidden="true" id="kategoriPeminjam" name="kategoriPeminjam">
                                        <option value="" selected disabled hidden>Pilih Kategori</option>
                                        <option value="karyawan">Karyawan</option>
                                        <option value="siswa">Siswa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="asalPeminjam" class="col-sm-3 col-form-label">Role</label>
                                <div class="col-sm-9">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="2" aria-hidden="true" id="asalPeminjam" name="asalPeminjam">
                                        <option value="" selected disabled hidden>Pilih Role</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 mb-3 text-end">
                                <button type="submit" class="btn btn-primary">Ajukan Peminjaman </button>
                                <br>
                                <input type="hidden" name="selectedRows" id="selectedRows" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="row mb-3 mt-3">
                            <div class="table-responsive">
                                <table class="table table-hover" id="dataTable" style="width: 100%;">
                                    <thead>
                                        <tr class="text-center">
                                            <th></th>
                                            <th style="width: 12%;">Kode Aset</th>
                                            <th>Lokasi</th>
                                            <th>Kategori Aset</th>
                                            <th>Nama Aset</th>
                                            <th>Merek</th>
                                            <th>Warna</th>
                                        </tr>
                                    </thead>
                                    <tbody class="py-2">
                                        <?php foreach ($dataRincianLabAset as $key => $value) : ?>
                                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                                <td>
                                                    <input type="checkbox" class="form-check-input row-select" name="selectedRows[]" value="<?= $value->idRincianLabAset ?>">
                                                </td>
                                                <td class="text-center"><?= $value->kodeRincianLabAset ?></td>
                                                <td class="text-center"><?= $value->namaLab ?></td>
                                                <td class="text-center"><?= $value->namaKategoriManajemen ?></td>
                                                <td class="text-center"><?= $value->namaSarana ?></td>
                                                <td class="text-center"><?= $value->merk ?></td>
                                                <td class="text-center"><?= $value->warna ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
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
        function getSelectedRowIds() {
            const checkboxes = document.querySelectorAll('input[name="selectedRows[]"]:checked');
            const selectedRowIds = [];
            checkboxes.forEach(function(checkbox) {
                selectedRowIds.push(checkbox.value);
            });
            return selectedRowIds;
        }

        $("#custom-validation").on("submit", function() {
            var selectedRows = getSelectedRowIds();
            $("#selectedRows").val(selectedRows.join(','));
            if (selectedRows.length === 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Masukkan minimal satu barang!',
                });
            }
        });


        $("#kategoriPeminjam").on("change", function() {
            var selectedKategoriPeminjam = $(this).val();
            var $asalPeminjamSelect = $("#asalPeminjam");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getRole') ?>",
                type: "POST",
                data: {
                    kategoriPeminjam: selectedKategoriPeminjam,
                },
                dataType: "json",
                success: function(response) {
                    $asalPeminjamSelect.empty();
                    if (response.length === 0) {
                        $asalPeminjamSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $asalPeminjamSelect.append("<option value='' selected disabled hidden>Pilih Role</option>");
                        $.each(response, function(key, value) {
                            if (selectedKategoriPeminjam === 'siswa') {
                                $asalPeminjamSelect.append("<option value='" + value.namaKelas + "'>" + value.namaKelas + "</option>");
                            } else if (selectedKategoriPeminjam === 'karyawan') {
                                $asalPeminjamSelect.append("<option value='" + value.namaKategoriPegawai + "'>" + value.namaKategoriPegawai + "</option>");
                            }
                        });
                    }
                },
                error: function() {
                    alert("Failed to retrieve data");
                }
            });
        });

        $("#idIdentitasLab").on("change", function() {
            var selectedIdIdentitasLab = $(this).val();
            var $idIdentitasSaranaSelect = $("#idIdentitasSarana");

            $.ajax({
                url: "<?= site_url('manajemenPeminjaman/getSaranaByLab') ?>",
                type: "POST",
                data: {
                    idIdentitasLab: selectedIdIdentitasLab,
                },
                dataType: "json",
                success: function(response) {
                    $idIdentitasSaranaSelect.empty();
                    if (response.length === 0) {
                        $idIdentitasSaranaSelect.append("<option value='' selected disabled hidden>No data</option>");
                    } else {
                        $idIdentitasSaranaSelect.append("<option value='' selected disabled hidden>Pilih Aset</option>");
                        $.each(response, function(key, value) {
                            $idIdentitasSaranaSelect.append("<option value='" + value.idIdentitasSarana + "'>" + value.namaSarana + "</option>");
                        });
                    }
                },
                error: function() {
                    alert("Failed to retrieve kode rincian aset options.");
                }
            });
        });

        $("#idIdentitasSarana").on("change", function() {
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
                    alert("Failed to retrieve kode rincian lab aset options.");
                }
            });
        });

    });
</script>
<?= $this->endSection(); ?>