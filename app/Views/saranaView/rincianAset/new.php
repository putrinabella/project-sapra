<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Rincian Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Rincian Aset</h4>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Tambah Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('rincianAset')?>" method="post" enctype="multipart/form-data" autocomplete="off" id="custom-validation">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <label for="kodeRincianAset" class="col-sm-3 col-form-label">Kode Rincian Aset</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kodeRincianAset" name="kodeRincianAset"
                                placeholder="Kode akan dibuat secara otomatis">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasPrasarana" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true" id="idIdentitasPrasarana" name="idIdentitasPrasarana">
                                <option value="" selected disabled hidden>Pilih lokasi</option>
                                <?php foreach($dataIdentitasPrasarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasPrasarana?>"><?=$value->namaPrasarana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Barang</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="2" tabindex="-1" aria-hidden="true" id="idKategoriManajemen" name="idKategoriManajemen">
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
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="3" tabindex="-1" aria-hidden="true" id="idIdentitasSarana" name="idIdentitasSarana">
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
                        <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="4" tabindex="-1" aria-hidden="true" id="status" name="status">
                            <option value="" selected disabled hidden>Pilih status</option>
                            <option value="Bagus" >Bagus</option>
                            <option value="Rusak" >Rusak</option>
                            <option value="Hilang" >Hilang</option>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="js-example-basic-single form-select select2-hidden-accessible" data-width="100%" data-select2-id="5" tabindex="-1" aria-hidden="true" id="idSumberDana" name="idSumberDana">
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
                                <p class="text-primary" style="font-size: 12px;">Jika tahun pengadaan tidak diketahui, tulis dengan <b>0000</b></p>
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
                            <input type="text" class="form-control" id="merk" name="merk"
                                placeholder="Masukkan merek">
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
                            <textarea class="form-control" id="spesifikasi" name="spesifikasi" rows="5" placeholder="Masukkan spesifikasi aset" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti" placeholder="Masukkan link dokumentasi">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('rincianAset') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="submit" class="btn btn-primary" id="submit-button">Submit</button>
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
    $(document).ready(function() {
        // Add change event listeners to the relevant input fields
        $('#idKategoriManajemen, #idIdentitasPrasarana, #idSumberDana, #tahunPengadaan, #idIdentitasSarana, #nomorBarang').change(function() {
            // Get the values from the input fields
            var kodeKategoriManajemen = $('#idKategoriManajemen').val();
            var kodePrasarana = $('#idIdentitasPrasarana').val();
            var kodeSumberDana = $('#idSumberDana').val();
            var tahunPengadaan = $('#tahunPengadaan').val();
            var kodeSarana = $('#idIdentitasSarana').val();
            var nomorBarang = $('#nomorBarang').val();

            // Make an Ajax request to the server to generate kodeRincianAset
            $.ajax({
                type: 'POST',
                url: 'generateKode', // Replace with your server URL
                data: {
                    kodeKategoriManajemen: kodeKategoriManajemen,
                    kodePrasarana: kodePrasarana,
                    kodeSumberDana: kodeSumberDana,
                    tahunPengadaan: tahunPengadaan,
                    kodeSarana: kodeSarana,
                    nomorBarang: nomorBarang
                },
                success: function(data) {
                    // Update the kodeRincianAset input field with the generated code
                    $('#kodeRincianAset').val(data);
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>