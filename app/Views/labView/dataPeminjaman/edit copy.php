<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Form Pengembalian &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Form Pengembalian</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('dataPeminjaman/'.$dataDataPeminjaman->idManajemenPeminjaman)?>"
                    method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">

                    <input type="hidden" name="_method" value="PATCH">

                    <div class="row mb-3">
                        <label for="namaPeminjam" class="col-sm-3 col-form-label">Nama Peminjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaPeminjam" name="namaPeminjam"
                                value="<?=$dataDataPeminjaman->namaPeminjam?>" readonly>
                            <input type="text" class="form-control" id="idManajemenPeminjaman"
                                name="idManajemenPeminjaman" value="<?=$dataDataPeminjaman->idManajemenPeminjaman?>"
                                hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="asalPeminjam" class="col-sm-3 col-form-label">Karyawan/Siswa</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="asalPeminjam" name="asalPeminjam"
                                value="<?=$dataDataPeminjaman->asalPeminjam?>" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLab" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaLab" name="namaLab"
                                value="<?=$dataDataPeminjaman->namaLab?>" readonly>
                            <input type="text" class="form-control" id="idIdentitasLab" name="idIdentitasLab"
                                value="<?=$dataDataPeminjaman->idIdentitasLab?>" hidden>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="jumlahPeminjaman" class="col-sm-3 col-form-label">Jumlah Aset Dipinjam</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="jumlahPeminjaman" name="jumlahPeminjaman"
                                value="<?=$dataDataPeminjaman->jumlahPeminjaman?>" readonly>
                            <input type="text" class="form-control" id="loanStatus" name="loanStatus"
                                value="Pengembalian" hidden>
                        </div>
                    </div>
                    <!-- <div class="row mb-3">
                        <label for="loanStatus" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1" aria-hidden="true" id="loanStatus"
                                name="loanStatus">
                                <option value="Pengembalian">Pengembalian</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="row mb-3">
                        <label for="tanggal" class="col-sm-3 col-form-label" id="labelTanggalPengembalianContainer"
                            style="display: none;">Tanggal Pengembalian</label>
                        <div class="col-sm-9" id="tanggalPengembalianContainer" style="display: none;">
                            <div class="input-group date datepicker" id="tanggalPengembalian">
                                <input type="readonly" class="form-control" id="tanggalPengembalian"
                                    name="tanggalPengembalian" value="<?=$dataDataPeminjaman->tanggalPengembalian?>">
                                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaPenerima" class="col-sm-3 col-form-label" id="labelNamaPenerimaContainer"
                            style="display: none;">Nama Penerima</label>
                        <div class="col-sm-9" id="namaPenerimaContainer" style="display: none;">
                            <input type="text" class="form-control" id="namaPenerima" name="namaPenerima"
                                value="<?= session('nama'); ?>">
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
                                        <th>Harga Beli</th>
                                        <th>Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody class="py-2">
                                    <?php foreach ($dataItemDipinjam as $key => $value) : ?>
                                    <tr class="text-center" style=" vertical-align: middle;">
                                        <td class="text-center">
                                            <?=$key + 1?>
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
                                            <?=number_format($value->hargaBeli, 0, ',', '.')?>
                                        </td>
                                        <td>
                                            <select name="sectionAset" id="sectionAset" class="form-select me-2"
                                                style="width: 130px">
                                                <option value="Bagus">Bagus</option>
                                                <option value="Rusak">Rusak</option>
                                                <option value="Hilang">Hilang</option>
                                            </select>
                                            <!-- <form
                                                action="<?= site_url('returnItems/changeStatus/' . $value->idRincianLabAset) ?>"
                                                method="post" class="d-inline">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center">
                                                        <select name="sectionAset"
                                                            class="form-control me-2 sectionAsetSelect"
                                                            style="width: 130px">
                                                            <option value="Bagus">Bagus</option>
                                                            <option value="Rusak">Rusak</option>
                                                            <option value="Hilang">Hilang</option>
                                                        </select>
                                                        <button type="submit"
                                                            class="btn btn-success btn-icon ml-2 submitButton">
                                                            <i data-feather="check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form> -->
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kelengkapanPengembalian" class="col-sm-3 col-form-label"
                            id="labelStatusPengembalianContainer" style="display: none;">Kelengkapan
                            Pengembalian</label>
                        <div class="col-sm-9" id="kelengkapanPengembalianContainer" style="display: none;">
                            <select class="form-select" name="kelengkapanPengembalian" id="kelengkapan">
                                <option value="" hidden>Pilih status pengembalian</option>
                                <option value="Lengkap">Lengkap</option>
                                <option value="Tidak Lengkap">Tidak Lengkap</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="asetDikembalikan" class="col-sm-3 col-form-label"
                            id="labelAsetDikembalikanContainer" style="display: none;">Aset Bagus</label>
                        <div class="col-sm-9" id="asetDikembalikanContainer" style="display: none;">
                            <input type="number" class="form-control" id="jumlahBarangDikembalikan"
                                name="jumlahBarangDikembalikan"
                                value="<?=$dataDataPeminjaman->jumlahBarangDikembalikan?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="asetRusak" class="col-sm-3 col-form-label" id="labelAsetRusakContainer"
                            style="display: none;">Aset Rusak</label>
                        <div class="col-sm-9" id="asetRusakContainer" style="display: none;">
                            <input type="number" class="form-control" id="jumlahBarangRusak" name="jumlahBarangRusak"
                                value="<?=$dataDataPeminjaman->jumlahBarangRusak?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="asetHilang" class="col-sm-3 col-form-label" id="labelAsetHilangContainer"
                            style="display: none;">Aset Hilang</label>
                        <div class="col-sm-9" id="asetHilangContainer" style="display: none;">
                            <input type="number" class="form-control" id="jumlahBarangHilang" name="jumlahBarangHilang"
                                value="<?=$dataDataPeminjaman->jumlahBarangHilang?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('dataPeminjaman') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Undo</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePengembalian() {
        var loanStatusSelect = document.getElementById('loanStatus');
        var loanStatus = loanStatusSelect.value;
        var labelTanggalPengembalianContainer = document.getElementById('labelTanggalPengembalianContainer');
        var tanggalPengembalianContainer = document.getElementById('tanggalPengembalianContainer');
        var tanggalPengembalianInput = document.getElementById('tanggalPengembalian');
        var kelengkapanPengembalianContainer = document.getElementById('kelengkapanPengembalianContainer');
        var labelStatusPengembalianContainer = document.getElementById('labelStatusPengembalianContainer');
        var labelNamaPenerimaContainer = document.getElementById('labelNamaPenerimaContainer');
        var namaPenerimaContainer = document.getElementById('namaPenerimaContainer');

        if (loanStatus === 'Pengembalian') {
            labelTanggalPengembalianContainer.style.display = 'block';
            tanggalPengembalianContainer.style.display = 'block';
            kelengkapanPengembalianContainer.style.display = 'block';
            labelStatusPengembalianContainer.style.display = 'block';
            labelNamaPenerimaContainer.style.display = 'block';
            namaPenerimaContainer.style.display = 'block';
        } else {
            labelTanggalPengembalianContainer.style.display = 'none';
            tanggalPengembalianContainer.style.display = 'none';
            kelengkapanPengembalianContainer.style.display = 'none';
            labelStatusPengembalianContainer.style.display = 'none';
            labelNamaPenerimaContainer.style.display = 'none';
            namaPenerimaContainer.style.display = 'none';

            tanggalPengembalianInput.value = '';
        }

        updateJumlahBarangDikembalikan();
    }

    function updateJumlahBarangDikembalikan() {
        var loanStatusSelect = document.getElementById('loanStatus');
        var kelengkapanSelect = document.getElementById('kelengkapan');
        var jumlahBarangDikembalikanInput = document.getElementById('jumlahBarangDikembalikan');

        if (loanStatusSelect.value === 'Pengembalian' && kelengkapanSelect.value === 'Lengkap') {
            jumlahBarangDikembalikanInput.value = '<?=$dataDataPeminjaman->jumlah?>';
        } else {
            jumlahBarangDikembalikanInput.value = '';
        }
    }

    function toggleKelengkapan() {
        var kelengkapanSelect = document.getElementById('kelengkapan');
        var kelengkapan = kelengkapanSelect.value;
        var labelAsetDikembalikanContainer = document.getElementById('labelAsetDikembalikanContainer');
        var asetDikembalikanContainer = document.getElementById('asetDikembalikanContainer');
        var labelAsetRusakContainer = document.getElementById('labelAsetRusakContainer');
        var asetRusakContainer = document.getElementById('asetRusakContainer');
        var labelAsetHilangContainer = document.getElementById('labelAsetHilangContainer');
        var asetHilangContainer = document.getElementById('asetHilangContainer');
        var jumlahBarangDikembalikanInput = document.getElementById('jumlahBarangDikembalikan');

        if (kelengkapan === 'Tidak Lengkap') {
            labelAsetDikembalikanContainer.style.display = 'block';
            asetDikembalikanContainer.style.display = 'block';
            labelAsetRusakContainer.style.display = 'block';
            asetRusakContainer.style.display = 'block';
            labelAsetHilangContainer.style.display = 'block';
            asetHilangContainer.style.display = 'block';
            jumlahBarangDikembalikanInput.value = '<?=$dataDataPeminjaman->jumlah?>';
        } else {
            labelAsetDikembalikanContainer.style.display = 'none';
            asetDikembalikanContainer.style.display = 'none';
            labelAsetRusakContainer.style.display = 'none';
            asetRusakContainer.style.display = 'none';
            labelAsetHilangContainer.style.display = 'none';
            asetHilangContainer.style.display = 'none';
            jumlahBarangDikembalikanInput.value = '';
        }

        updateJumlahBarangDikembalikan();
    }

    document.getElementById('kelengkapan').addEventListener('change', toggleKelengkapan);

    document.getElementById('loanStatus').addEventListener('change', togglePengembalian);

    window.addEventListener('load', function () {
        togglePengembalian();
        toggleKelengkapan();
        updateJumlahBarangDikembalikan();
    });
</script>



<?= $this->endSection(); ?>