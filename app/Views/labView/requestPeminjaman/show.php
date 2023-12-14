<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Request Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('requestPeminjaman')?>">Request Peminjaman</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Data</li>
    </ol>
</nav>
<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('requestPeminjaman') ?>" class="btn btn-icon-text btn-outline-primary me-2">
                        <i class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Request Peminjaman</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
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
                    <label for="kelasJabatan" class="col-sm-3 col-form-label">Kelas/Karyawan</label>
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
                        <input type="text" class="form-control bg-transparent" id="keperluanAlat" name="keperluanAlat"
                            value="<?= $dataRequestPeminjaman->keperluanAlat ?>" readonly>
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
                                    <th style="width: 5%;">No.</th>
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
                                    <td>
                                        <?= $key + 1 ?>
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
                                        <?php if ($value->requestItemStatus == "Approve") : ?>
                                        Approve
                                        <?php else :?>
                                        <span class="badge bg-danger">Reject</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>


<?= $this->endSection(); ?>