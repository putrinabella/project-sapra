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
                    <a href="<?= site_url('peminjamanUser') ?>" class="btn btn-icon-text btn-outline-primary me-2">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Form Peminjaman</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('peminjamanUser/addLoan') ?>" method="POST"
                    enctype="multipart/form-data" id="custom-validation">
                    <div class="row">
                        <div class="col-12">
                            <div>
                                <input type="text" class="form-control" id="loanStatus" name="loanStatus"
                                    value="Request" hidden>
                            </div>
                            <div class="row mb-3">
                                <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-transparent" name="tanggal" id="tanggalInput" readonly>
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
                                <label for="asalPeminjam" class="col-sm-3 col-form-label">NIS/NIP</label>
                                <div class="col-sm-9"> 
                                    <input type="text" class="form-control bg-transparent" id="nisnip" name="nisnip"
                                    value="<?= session('username'); ?>" readonly>
                                    <input type="text" class="form-control bg-transparent" id="asalPeminjam" name="asalPeminjam"
                                        value="<?= $idUser; ?>" hidden >
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama Peminjam</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-transparent" id="namaPeminjam"
                                        name="namaPeminjam" placeholder="Nama peminjam" value="<?= session('nama'); ?>" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="kelasJabatan" class="col-sm-3 col-form-label">Kelas/Jabatan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control bg-transparent" id="kelasJabatan"
                                        name="kelasJabatan" placeholder="Kelas atau jabatan" value="<?= $namaKelas ?>"  readonly>
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
                                <input type="number" class="form-control bg-transparent" id="lamaPinjam" name="lamaPinjam" placeholder="Masukkan lama pinjam (Hari)" pattern="\d*" title="Tulis lama peminjaman dengan angka">
                                </div>
                            </div>
                            <div class=" col-12 mb-3 text-end">
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

        function getCurrentFormattedDate() {
            const today = new Date();
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return today.toLocaleDateString('en-GB', options);
        }
        $("#tanggalInput").val(getCurrentFormattedDate());

    });
</script>
<?= $this->endSection(); ?>