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
                    <a href="<?= site_url('requestPeminjaman') ?>" class="btn btn-icon-text btn-outline-primary me-2"> <i
                            class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Form Request Peminjaman</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('requestPeminjaman/processLoan') ?>"
                    method="POST" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <label for="idPeminjam" class="col-sm-3 col-form-label">NIS/NIP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="idPeminjam" name="idPeminjam"
                                value="<?= $dataRequestPeminjaman->nis ?>" readonly>
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
                        <label for="asalPeminjam" class="col-sm-3 col-form-label">Karyawan/Siswa</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="asalPeminjam" name="asalPeminjam"
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
                        <label for="jumlahPeminjaman" class="col-sm-3 col-form-label">Tujuan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="loanStatus" name="loanStatus"
                                value="Pengembalian" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jumlahPeminjaman" class="col-sm-3 col-form-label">Jumlah Aset Dipinjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="jumlahPeminjaman"
                                name="jumlahPeminjaman" value="<?= $dataRequestPeminjaman->jumlahPeminjaman ?> Aset"
                                readonly>
                            <input type="text" class="form-control bg-transparent" id="loanStatus" name="loanStatus"
                                value="Pengembalian" hidden>
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
                                        <th>Harga Beli</th>
                                    </tr>
                                </thead>
                                <tbody class="py-2">
                                    <?php foreach ($dataItemDipinjam as $key => $value) : ?>
                                    <tr style=" vertical-align: middle;">
                                        <td style="width: 5%">
                                            <input type="checkbox" class="form-check-input row-select"
                                                name="selectedRows[]" value="<?= $value->idRincianLabAset ?>">
                                                
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
                                            <?= number_format($value->hargaBeli, 0, ',', '.') ?>
                                        </td>
                                        <!-- <td>
                                            <select name="loanStatus[]" id="loanStatus" class="form-select me-2"
                                                style="width: 130px">
                                                <option value="Approve">Approve</option>
                                                <option value="Reject">Reject</option>
                                            </select>
                                            <input type="hidden" name="idRincianLabAset[]"
                                                value="<?= $value->idRincianLabAset; ?>">
                                        </td> -->
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('requestPeminjaman') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <br>
                            <input type="text" name="selectedRows" id="selectedRows" value="">
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
    });
</script>



<?= $this->endSection(); ?>