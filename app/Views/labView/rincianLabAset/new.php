<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Rincian Aset Laboratorium &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('rincianLabAset')?>">Rincian Aset</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <form action="<?= site_url('rincianLabAset')?>" method="post" enctype="multipart/form-data"
                    autocomplete="off" id="custom-validation">
                    <div class="row mb-3">
                        <label for="kodeRincianLabAset" class="col-sm-3 col-form-label">Kode Rincian Aset</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kodeRincianLabAset" name="kodeRincianLabAset"
                                placeholder="Kode akan dibuat secara otomatis" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLab" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1"  aria-hidden="true"
                                id="idIdentitasLab" name="idIdentitasLab">
                                <option value="" selected disabled hidden>Pilih lokasi</option>
                                <?php foreach($dataIdentitasLab as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasLab?>"><?=$value->namaLab?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Barang</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="2"  aria-hidden="true"
                                id="idKategoriManajemen" name="idKategoriManajemen">
                                <option value="" selected disabled hidden>Pilih kategori</option>
                                <?php foreach($dataKategoriManajemen as $key =>$value): ?>
                                <option value="<?=$value->idKategoriManajemen?>"><?=$value->namaKategoriManajemen?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="3"  aria-hidden="true"
                                id="idIdentitasSarana" name="idIdentitasSarana">
                                <option value="" selected disabled hidden>Pilih aset</option>
                                <?php foreach($dataIdentitasSarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasSarana?>"><?=$value->namaSarana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nomorBarang" class="col-sm-3 col-form-label">Nomor Barang</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="nomorBarang" name="nomorBarang"
                                placeholder="Masukkan nomor barang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-3 col-form-label">Status Aset</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="4"  aria-hidden="true" id="status"
                                name="status">
                                <option value="" selected disabled hidden>Pilih status</option>
                                <option value="Bagus">Bagus</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Hilang">Hilang</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="5"  aria-hidden="true" id="idSumberDana"
                                name="idSumberDana">
                                <option value="" selected disabled hidden>Pilih sumber dana</option>
                                <?php foreach($dataSumberDana as $key =>$value): ?>
                                <option value="<?=$value->idSumberDana?>"><?=$value->namaSumberDana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tahunPengadaan" class="col-sm-3 col-form-label">Tahun Pengadaan</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="tahunPengadaan" name="tahunPengadaan"
                                placeholder="Masukkan tahun pengadaan">
                            <p class="text-primary" style="font-size: 12px;">Jika tahun pengadaan tidak diketahui, tulis
                                dengan <b>0000</b></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="hargaBeli" class="col-sm-3 col-form-label">Harga Beli</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="hargaBeli" name="hargaBeli"
                                placeholder="Masukkan harga beli">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="noSeri" class="col-sm-3 col-form-label">No Seri</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="noSeri" name="noSeri"
                                placeholder="Masukkan nomor seri">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="merk" class="col-sm-3 col-form-label">Merek</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="merk" name="merk" placeholder="Masukkan merek">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="warna" class="col-sm-3 col-form-label">Warna</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="warna" name="warna"
                                placeholder="Masukkan warna">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="spesifikasi" class="col-sm-3 col-form-label">Spesifikasi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="spesifikasi" name="spesifikasi" rows="5"
                                placeholder="Masukkan spesifikasi aset" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti"
                                placeholder="Masukkan link dokumentasi (Link Google Drive)">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('rincianLabAset') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="button" class="btn btn-primary" id="submit-button"
                                onclick="checkForDuplicate()">Submit</button>
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
    function checkForDuplicate() {
        var kodeRincianLabAset = $('#kodeRincianLabAset').val();
        console.log('Checking for duplicate: ' + kodeRincianLabAset);

        $.ajax({
            type: 'POST',
            url: 'checkDuplicate',
            data: { kodeRincianLabAset: kodeRincianLabAset },
            success: function (response) {
                console.log('Response: ' + response);
                var result = JSON.parse(response);
                if (result.isDuplicate) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Duplicate Entry',
                        text: 'Kode Rincian Aset Laboratorium tidak boleh duplikat!',
                    });
                } else {
                    $('#custom-validation').submit();
                }
            }
        });
    }

    $(document).ready(function () {
        $('#idKategoriManajemen, #idIdentitasLab, #idSumberDana, #tahunPengadaan, #idIdentitasSarana, #nomorBarang').change(function () {
            var idKategoriManajemen = $('#idKategoriManajemen').val();
            var idIdentitasLab = $('#idIdentitasLab').val();
            var idSumberDana = $('#idSumberDana').val();
            var tahunPengadaan = $('#tahunPengadaan').val();
            var idIdentitasSarana = $('#idIdentitasSarana').val();
            var nomorBarang = $('#nomorBarang').val();

            $.ajax({
                type: 'POST',
                url: 'generateKode',
                data: {
                    idKategoriManajemen: idKategoriManajemen,
                    idIdentitasLab: idIdentitasLab,
                    idSumberDana: idSumberDana,
                    tahunPengadaan: tahunPengadaan,
                    idIdentitasSarana: idIdentitasSarana,
                    nomorBarang: nomorBarang
                },
                success: function (data) {
                    data = data.replace(/"/g, '');
                    $('#kodeRincianLabAset').val(data);
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>