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
                    <a href="<?= site_url('manajemenPeminjaman') ?>" class="btn btn-icon-text btn-outline-primary me-2">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Form Peminjaman</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('manajemenPeminjaman/addLoan') ?>" method="POST"
                    enctype="multipart/form-data" id="custom-validation">
                    <div class="row">
                        <div class="col-12">
                            <div>
                                <input type="text" class="form-control" id="loanStatus" name="loanStatus"
                                    value="Peminjaman" hidden>
                            </div>
                            <div class="row mb-3">
                                <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <div class="input-group date datepicker m-0" id="tanggal">
                                        <input type="text" class="form-control bg-transparent" name="tanggal"
                                            placeholder="Masukkan tanggal" readonly>
                                        <span class="input-group-text input-group-addon bg-transparent"><i
                                                data-feather="calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="namaLab" class="col-sm-3 col-form-label">Lokasi</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-transparent" id="namaLab" name="namaLab"
                                        value="<?= $namaLaboratorium->namaLab; ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kategoriPeminjam" class="col-sm-3 col-form-label">Karyawan/Siswa</label>
                                <div class="col-sm-9">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible"
                                        data-width="100%" data-select2-id="1" aria-hidden="true" id="kategoriPeminjam"
                                        name="kategoriPeminjam">
                                        <option value="" selected disabled hidden>Pilih Kategori</option>
                                        <option value="karyawan">Karyawan</option>
                                        <option value="siswa">Siswa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="asalPeminjam" class="col-sm-3 col-form-label">NIS/NIP</label>
                                <div class="col-sm-9">
                                    <select class="js-example-basic-single form-select select2-hidden-accessible"
                                        data-width="100%" data-select2-id="2" aria-hidden="true" id="asalPeminjam"
                                        name="asalPeminjam">
                                        <option value="" selected disabled hidden>Pilih NIS/NIP</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama Peminjam</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-transparent" id="namaPeminjam"
                                        name="namaPeminjam" placeholder="Nama peminjam" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kelasJabatan" class="col-sm-3 col-form-label">Kelas/Jabatan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-transparent" id="kelasJabatan"
                                        name="kelasJabatan" placeholder="Kelas atau jabatan" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="keperluanAlat" class="col-sm-3 col-form-label">Keperluan Alat</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-transparent" id="keperluanAlat"
                                        name="keperluanAlat" placeholder="Masukkan keperluan alat">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="lamaPinjam" class="col-sm-3 col-form-label">Lama Peminjaman</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control bg-transparent" id="lamaPinjam"
                                        name="lamaPinjam" placeholder="Masukkan lama pinjam (Hari)">
                                </div>
                            </div>
                            <div class=" col-12 mb-3 text-end">
                                <button type="submit" class="btn btn-primary">Ajukan Peminjaman </button>
                                <br>
                                <input type="hidden" name="getData" id="getData" value="">
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
                                                <input type="checkbox" class="form-check-input row-select"
                                                    name="selectedRows[]" value="<?= $value->idRincianLabAset ?>">
                                            </td>
                                            <td>
                                                <?= $value->kodeRincianLabAset ?>
                                            </td>
                                            <td>
                                                <?= $value->namaKategoriManajemen ?>
                                            </td>
                                            <td>
                                                <?= $value->namaSarana ?>
                                            </td>
                                            <td>
                                                <?= $value->merk ?>
                                            </td>
                                            <td>
                                                <?= $value->warna ?>
                                            </td>
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
    $(document).ready(function () {
        function getSelectedRowIds() {
            const checkboxes = document.querySelectorAll('input[name="selectedRows[]"]:checked');
            const selectedRowIds = [];
            checkboxes.forEach(function (checkbox) {
                selectedRowIds.push(checkbox.value);
            });
            return selectedRowIds;
        }

        $("#custom-validation").on("submit", function () {
            var selectedRows = getSelectedRowIds();
            $("#getData").val(selectedRows.join(','));
            if (selectedRows.length === 0) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning!',
                    text: 'Masukkan minimal satu barang!',
                });
            }
        });


        $("#kategoriPeminjam").on("change", function () {
            var selectedKategoriPeminjam = $(this).val();
            var $asalPeminjamSelect = $("#asalPeminjam");
            var $namaPeminjamSelect = $("#namaPeminjam");
            var $kelasJabatanSelect = $("#kelasJabatan");

            $namaPeminjamSelect.val("");
            $kelasJabatanSelect.val("");

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
                        $asalPeminjamSelect.append("<option value='' selected disabled hidden>Pilih Opsi</option>");
                        $.each(response, function (key, value) {
                            if (selectedKategoriPeminjam === 'siswa') {
                                $asalPeminjamSelect.append("<option value='" + value.idDataSiswa + "'>" + value.nis + "</option>");
                            } else if (selectedKategoriPeminjam === 'karyawan') {
                                $asalPeminjamSelect.append("<option value='" + value.idDataSiswa + "'>" + value.nis + "</option>");
                            }
                        });
                    }
                },
                error: function () {
                    alert("Failed to retrieve data");
                }
            });
        });

        $("#asalPeminjam").on("change", function () {
            var selectedAsalPeminjam = $(this).val();
            var selectedKategoriPeminjam = $("#kategoriPeminjam").val();
            var $namaPeminjamSelect = $("#namaPeminjam");
            var $kelasJabatanSelect = $("#kelasJabatan");
            $.ajax({
                url: "<?= site_url('getNama') ?>",
                type: "POST",
                data: {
                    asalPeminjam: selectedAsalPeminjam,
                    kategoriPeminjam: selectedKategoriPeminjam,
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response.error) {
                        alert('Error: ' + response.error);
                    } else {
                        if (selectedKategoriPeminjam === 'siswa' && response.namaPeminjam) {
                            $namaPeminjamSelect.val(response.namaPeminjam);
                            $kelasJabatanSelect.val(response.kategori);
                        } else if (selectedKategoriPeminjam === 'karyawan' && response.namaPeminjam) {
                            $namaPeminjamSelect.val(response.namaPeminjam);
                            $kelasJabatanSelect.val(response.kategori);
                        } else {
                            alert('Received data from server, but namaPeminjam is not set or kategoriPeminjam mismatch.');
                        }
                    }
                },
                error: function () {
                    alert("Failed to retrieve nama.");
                }
            });
        });




    });
</script>
<?= $this->endSection(); ?>