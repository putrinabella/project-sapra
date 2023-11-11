<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Histori Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('dataPeminjaman') ?>" class="btn btn-icon-text btn-outline-primary me-2">Back</a>
                    <h4 class="text-center">Histori Peminjaman</h4>
                    <div></div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="_method" value="PATCH">
                <div class="row mb-3">
                    <label for="tanggal" class="col-sm-3 col-form-label" id="tanggal">Tanggal Peminjaman</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="tanggal" name="tanggal" value="<?= date('d F Y', strtotime($dataDataPeminjaman->tanggal)) ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="tanggalPengembalian" class="col-sm-3 col-form-label" id="tanggalPengembalian">Tanggal Pengembalian</label>
                    <div class="col-sm-9">
                        <?php
                        $tanggalPengembalian = !empty($dataDataPeminjaman->tanggalPengembalian) ? date('d F Y', strtotime($dataDataPeminjaman->tanggalPengembalian)) : "Belum dikembalikan";
                        ?>
                        <input type="text" class="form-control" id="tanggalPengembalian" name="tanggalPengembalian" value="<?= $tanggalPengembalian ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama Peminjam</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaPeminjam" name="namaPeminjam" value="<?= $dataDataPeminjaman->namaPeminjam ?>" readonly>
                        <input type="text" class="form-control" id="idManajemenPeminjaman" name="idManajemenPeminjaman" value="<?= $dataDataPeminjaman->idManajemenPeminjaman ?>" hidden>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="asalPeminjam" class="col-sm-3 col-form-label">Karyawan/Siswa</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="asalPeminjam" name="asalPeminjam" value="<?= $dataDataPeminjaman->asalPeminjam ?>" readonly>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="idIdentitasLab" class="col-sm-3 col-form-label">Lokasi</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="namaLab" name="namaLab" value="<?= $dataDataPeminjaman->namaLab ?>" readonly>
                        <input type="text" class="form-control" id="idIdentitasLab" name="idIdentitasLab" value="<?= $dataDataPeminjaman->idIdentitasLab ?>" hidden>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="jumlahPeminjaman" class="col-sm-3 col-form-label">Jumlah Aset Dipinjam</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="jumlahPeminjaman" name="jumlahPeminjaman" value="<?= $dataDataPeminjaman->jumlahPeminjaman ?>" readonly>
                        <input type="text" class="form-control" id="loanStatus" name="loanStatus" value="Pengembalian" hidden>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="namaPenerima" class="col-sm-3 col-form-label" id="labelNamaPenerimaContainer">Nama Penerima</label>
                    <div class="col-sm-9" id="namaPenerimaContainer">
                        <?php
                        $namaPenerima = !empty($dataDataPeminjaman->namaPenerima) ? $dataDataPeminjaman->namaPenerima : "-";
                        ?>
                        <input type="text" class="form-control" id="namaPenerima" name="namaPenerima" value="<?= $namaPenerima ?>" readonly>
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
                                    <th>Kondisi Awal</th>
                                    <th>Kondisi Saat Dikembalikan</th>
                                </tr>
                            </thead>
                            <tbody class="py-2">
                                <?php foreach ($dataRincianLabAset  as $key => $value) : ?>
                                    <tr class="text-center" style=" vertical-align: middle;">
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
                                        <td> Bagus </td>
                                        <td>
                                            <?= $value->statusSetelahPengembalian; ?>
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