<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Form Request Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('requestPeminjaman') ?>" class="btn btn-icon-text btn-outline-primary me-2">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Form Request Peminjaman</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('requestPeminjaman/processLoan') ?>" method="POST" autocomplete="off"
                    id="custom-validation" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" name="tanggal"
                                placeholder="Masukkan tanggal" readonly value="<?= $dataRequestPeminjaman->tanggal ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="asalPeminjam" class="col-sm-3 col-form-label">NIS/NIP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="nis" name="nis"
                                value="<?= $dataRequestPeminjaman->nis ?>" readonly>
                            <input type="text" class="form-control bg-transparent" id="asalPeminjam" name="asalPeminjam"
                                value="<?= $dataRequestPeminjaman->idDataSiswa ?>" hidden>
                            <input type="text" class="form-control bg-transparent" id="loanStatus" name="loanStatus"
                                value="Peminjaman" hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama Peminjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="namaPeminjam" name="namaPeminjam"
                                value="<?= $dataRequestPeminjaman->namaSiswa ?>" readonly>
                            <input type="text" class="form-control bg-transparent" id="idRequestPeminjaman"
                                name="idRequestPeminjaman" value="<?= $dataRequestPeminjaman->idRequestPeminjaman ?>"
                                hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kelasJabatan" class="col-sm-3 col-form-label">Karyawan/Siswa</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="kelasJabatan" name="kelasJabatan"
                                value="<?= $dataRequestPeminjaman->namaKelas ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLab" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="namaLab" name="namaLab"
                                value="<?= $dataRequestPeminjaman->namaLab ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jumlahPeminjaman" class="col-sm-3 col-form-label">Jumlah Aset Dipinjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="jumlahPeminjaman"
                                name="jumlahPeminjaman" value="<?= $dataRequestPeminjaman->jumlahPeminjaman ?> Aset"
                                readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="keperluanAlat" class="col-sm-3 col-form-label">Keperluan Alat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="keperluanAlat"
                                name="keperluanAlat" value="<?= $dataRequestPeminjaman->keperluanAlat ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="lamaPinjam" class="col-sm-3 col-form-label">Lama Pinjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="lamaPinjam" name="lamaPinjam"
                                value="<?= $dataRequestPeminjaman->lamaPinjam ?> Hari" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 mt-5">
                        <h5 class="text-center text-decoration-underline mb-3">Data Aset Dipinjam</h5>
                        <div class="table-responsive">
                            <table class="table table-hover" style="width: 100%;" id="dataTable">
                                <thead>
                                    <tr class="text-center">
                                        <th></th>
                                        <th class="d-none">Id Rincian Lab Aset</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Merk</th>
                                        <th>Warna</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="py-2">
                                    <?php foreach ($dataItemDipinjam as $key => $value) : ?>
                                    <tr style=" vertical-align: middle;">
                                        <td style="width: 5%">
                                            <?php if ($value->sectionAset !== "Dipinjam" && $value->sectionAset !== "Dimusnahkan") : ?>
                                                <input type="checkbox" class="form-check-input row-select"
                                                    name="selectedRows[]" value="<?= $value->idRincianLabAset ?>">
                                            <?php endif; ?>
                                        </td>
                                        <td class="d-none">
                                            <?= $value->idRincianLabAset; ?>
                                        </td>
                                        <td style="width: 22%">
                                            <?= $value->kodeRincianLabAset; ?>
                                        </td>
                                        <td>
                                            <?= $value->namaSarana; ?>
                                        </td>
                                        <td>
                                            <?= $value->merk; ?>
                                        </td>
                                        <td>
                                            <?= $value->warna; ?>
                                        </td>
                                        <td>
                                            <?php if ($value->sectionAset == "None") : ?>
                                            Tersedia
                                            <?php elseif ($value->sectionAset == "Dipinjam") : ?>
                                            <span class="badge bg-danger">Tidak Tersedia</span>
                                            <?php elseif ($value->sectionAset == "Dimusnahkan") : ?>
                                            <span class="badge bg-danger">Tidak Tersedia</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('requestPeminjaman/rejectLoan/' . $value->idRequestPeminjaman) ?>"
                                id="rejectButton" class="btn btn-danger me-2">Reject</a>
                            <button type="submit" class="btn btn-primary">Approve</button>
                            <br>
                            <input type="hidden" name="getData" id="getData" value="">
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
                    text: 'Pilih minimal satu barang!',
                });
            }
        });

        $("#rejectButton").on("click", function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this action!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = $(this).attr('href');
                }
            });
        });
    });
</script>



<?= $this->endSection(); ?>