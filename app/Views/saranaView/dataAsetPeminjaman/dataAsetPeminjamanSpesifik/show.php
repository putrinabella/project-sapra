<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Histori Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sarana</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('dataAsetPeminjaman')?>">Data Peminjaman</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('dataAsetPeminjaman') ?>" class="btn btn-icon-text btn-outline-primary me-2"> <i
                            class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Histori Peminjaman</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="_method" value="PATCH">
                <div class="row mb-3">
                    <label for="tanggal" class="col-sm-3 col-form-label" id="tanggal">Tanggal Peminjaman</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-transparent" id="tanggal" name="tanggal"
                            value="<?= date('d F Y', strtotime($dataDataAsetPeminjaman->tanggal)) ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="tanggalPengembalian" class="col-sm-3 col-form-label" id="tanggalPengembalian">Tanggal
                        Pengembalian</label>
                    <div class="col-sm-9">
                        <?php
                        $tanggalPengembalian = !empty($dataDataAsetPeminjaman->tanggalPengembalian) ? date('d F Y', strtotime($dataDataAsetPeminjaman->tanggalPengembalian)) : "Belum dikembalikan";
                        ?>
                        <input type="text" class="form-control bg-transparent" id="tanggalPengembalian" name="tanggalPengembalian"
                            value="<?= $tanggalPengembalian ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="idPeminjam" class="col-sm-3 col-form-label">NIS/NIP</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-transparent" id="idPeminjam" name="idPeminjam"
                            value="<?= $dataDataAsetPeminjaman->nis ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama Peminjam</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-transparent" id="namaPeminjam" name="namaPeminjam" value="<?= $dataDataAsetPeminjaman->namaSiswa ?>" readonly>
                        <input type="text" class="form-control bg-transparent" id="idManajemenAsetPeminjaman" name="idManajemenAsetPeminjaman"
                            value="<?= $dataDataAsetPeminjaman->idManajemenAsetPeminjaman ?>" hidden>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="asalPeminjam" class="col-sm-3 col-form-label">Kelas/Karyawan</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-transparent" id="asalPeminjam" name="asalPeminjam"
                            value="<?= $dataDataAsetPeminjaman->namaKelas ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="idIdentitasPrasarana" class="col-sm-3 col-form-label">Lokasi</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-transparent" id="namaPrasarana" name="namaPrasarana"
                            value="<?= $dataDataAsetPeminjaman->namaPrasarana ?>" readonly>
                        <input type="text" class="form-control bg-transparent" id="idIdentitasPrasarana" name="idIdentitasPrasarana"
                            value="<?= $dataDataAsetPeminjaman->idIdentitasPrasarana ?>" hidden>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="jumlahPeminjaman" class="col-sm-3 col-form-label">Jumlah Aset Dipinjam</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-transparent" id="jumlahPeminjaman" name="jumlahPeminjaman"
                            value="<?= $dataDataAsetPeminjaman->jumlahPeminjaman ?> Aset" readonly>
                        <input type="text" class="form-control bg-transparent" id="loanStatus" name="loanStatus" value="Pengembalian"
                            hidden>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="keperluanAlat" class="col-sm-3 col-form-label">Keperluan Alat</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-transparent" id="keperluanAlat" name="keperluanAlat"
                            value="<?= $dataDataAsetPeminjaman->keperluanAlat ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="lamaPinjam" class="col-sm-3 col-form-label">Lama Pinjam</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control bg-transparent" id="lamaPinjam" name="lamaPinjam"
                            value="<?= $dataDataAsetPeminjaman->lamaPinjam ?> Hari" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="namaPenerima" class="col-sm-3 col-form-label" id="labelNamaPenerimaContainer">Nama
                        Penerima</label>
                    <div class="col-sm-9" id="namaPenerimaContainer">
                        <?php
                        $namaPenerima = !empty($dataDataAsetPeminjaman->namaPenerima) ? $dataDataAsetPeminjaman->namaPenerima : "-";
                        ?>
                        <input type="text" class="form-control bg-transparent" id="namaPenerima" name="namaPenerima"
                            value="<?= $namaPenerima ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3 mt-5">
                    <h5 class="text-center text-decoration-underline mb-3">Data Aset Dipinjam</h5>
                    <div class="table-responsive">
                        <table class="table table-hover" style="width: 100%;" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 5%;">No.</th>
                                    <th class="d-none">Id Rincian Lab Aset</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Kategori</th>
                                    <th>Merk</th>
                                    <th>Warna</th>
                                    <th>Kondisi Awal</th>
                                    <th>Kondisi Saat Dikembalikan</th>
                                </tr>
                            </thead>
                            <tbody class="py-2">
                                <?php foreach ($dataRincianAset  as $key => $value) : ?>
                                <tr style=" vertical-align: middle;">
                                    <td class="text-center">
                                        <?= $key + 1 ?>
                                    </td>
                                    <td class="d-none">
                                        <?= $value->idRincianAset; ?>
                                    </td>
                                    <td>
                                        <?= $value->kodeRincianAset; ?>
                                    </td>
                                    <td>
                                        <?= $value->namaSarana; ?>
                                    </td>
                                    <td>
                                        <?= $value->namaKategoriManajemen; ?>
                                    </td>
                                    <td>
                                        <?= $value->merk; ?>
                                    </td>
                                    <td>
                                        <?= $value->warna; ?>
                                    </td>
                                    <td  class="text-center"> Bagus </td>
                                    <td class="text-center">
                                    <?php if ($value->statusSetelahPengembalian == "Rusak") : ?>
                                    <span class="badge bg-warning">
                                        <?= $value->statusSetelahPengembalian; ?> 
                                    </span>
                                    <?php elseif ($value->statusSetelahPengembalian == "Hilang"): ?>
                                    <span class="badge bg-danger">
                                    <?= $value->statusSetelahPengembalian; ?> 
                                    </span>
                                    <?php elseif ($value->statusSetelahPengembalian == "Bagus"): ?>
                                    <?= $value->statusSetelahPengembalian; ?> 
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





<?= $this->endSection(); ?>