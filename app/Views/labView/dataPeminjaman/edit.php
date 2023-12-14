<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Form Pengembalian &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('dataPeminjaman')?>">Data Peminjaman</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('dataPeminjaman') ?>" class="btn btn-icon-text btn-outline-primary me-2"> <i
                            class="btn-icon-prepend" data-feather="arrow-left"></i>Back</a>
                    <h4 class="text-center">Form Pengembalian</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('dataPeminjaman/' . $dataDataPeminjaman->idManajemenPeminjaman) ?>"
                    method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">

                    <input type="hidden" name="_method" value="PATCH">

                    <div class="row mb-3">
                        <label for="idPeminjam" class="col-sm-3 col-form-label">NIS/NIP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="idPeminjam" name="idPeminjam"
                                value="<?= $dataDataPeminjaman->nis ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama Peminjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="namaPeminjam" name="namaPeminjam"
                                value="<?= $dataDataPeminjaman->namaSiswa ?>" readonly>
                            <input type="text" class="form-control bg-transparent" id="idManajemenPeminjaman"
                                name="idManajemenPeminjaman" value="<?= $dataDataPeminjaman->idManajemenPeminjaman ?>"
                                hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="asalPeminjam" class="col-sm-3 col-form-label">Kelas/Karyawan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="asalPeminjam" name="asalPeminjam"
                                value="<?= $dataDataPeminjaman->namaKelas ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLab" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="namaLab" name="namaLab"
                                value="<?= $dataDataPeminjaman->namaLab ?>" readonly>
                            <input type="text" class="form-control bg-transparent" id="idIdentitasLab"
                                name="idIdentitasLab" value="<?= $dataDataPeminjaman->idIdentitasLab ?>" hidden>
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
                                name="jumlahPeminjaman" value="<?= $dataDataPeminjaman->jumlahPeminjaman ?> Aset"
                                readonly>
                            <input type="text" class="form-control bg-transparent" id="loanStatus" name="loanStatus"
                                value="Pengembalian" hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="keperluanAlat" class="col-sm-3 col-form-label">Keperluan Alat</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="keperluanAlat"
                                name="keperluanAlat" value="<?= $dataDataPeminjaman->keperluanAlat ?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="lamaPinjam" class="col-sm-3 col-form-label">Lama Pinjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control bg-transparent" id="lamaPinjam" name="lamaPinjam"
                                value="<?= $dataDataPeminjaman->lamaPinjam ?> Hari" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-3 col-form-label"
                            id="labelTanggalPengembalianContainer">Tanggal Pengembalian</label>
                        <div class="col-sm-9" id="tanggalPengembalianContainer">
                            <div class="input-group date datepicker" id="tanggalPengembalian">
                                <input type="readonly" class="form-control bg-transparent border-primary"
                                    id="tanggalPengembalian" name="tanggalPengembalian"
                                    value="<?= $dataDataPeminjaman->tanggalPengembalian ?>">
                                <span class="input-group-text input-group-addon bg-transparent border-primary"><i
                                        data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaPenerima" class="col-sm-3 col-form-label" id="labelNamaPenerimaContainer">Nama
                            Penerima</label>
                        <div class="col-sm-9" id="namaPenerimaContainer">
                            <input type="text" class="form-control bg-transparent border-primary" id="namaPenerima"
                                name="namaPenerima" value="<?= session('nama'); ?>">
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
                                        <th>Merk</th>
                                        <th>Warna</th>
                                        <th>Harga Beli</th>
                                        <th>Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody class="py-2">
                                    <?php foreach ($dataItemDipinjam as $key => $value) : ?>
                                    <tr style=" vertical-align: middle;">
                                        <td class="text-center">
                                            <?= $key + 1 ?>
                                        </td>
                                        <td class="d-none">
                                            <?= $value->idRincianLabAset; ?>
                                        </td>
                                        <td>
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
                                        <td>
                                            <select name="status[]" id="status" class="form-select me-2"
                                                style="width: 130px">
                                                <option value="Bagus">Bagus</option>
                                                <option value="Rusak">Rusak</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                            <input type="hidden" name="idRincianLabAset[]"
                                                value="<?= $value->idRincianLabAset; ?>">
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('dataPeminjaman') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<?= $this->endSection(); ?>