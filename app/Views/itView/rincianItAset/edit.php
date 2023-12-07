<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Rincian Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">IT</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('rincianItAset')?>">Rincian Aset</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h4>Edit Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('rincianItAset/'.$dataRincianItAset->idRincianAset)?>" method="post"
                    autocomplete="off" id="custom-validation"  enctype="multipart/form-data">
                    
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="kodeRincianAset" class="col-sm-3 col-form-label">Kode Rincian Aset</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kodeRincianAset" name="kodeRincianAset"
                                placeholder="Kode akan dibuat secara otomatis" value=" <?= $dataRincianItAset->kodeRincianAset; ?> "; readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasPrasarana" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true"
                                id="idIdentitasPrasarana" name="idIdentitasPrasarana">
                                <option value="" selected disabled hidden>Pilih lokasi</option>
                                <?php foreach($dataIdentitasPrasarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasPrasarana?>" <?=$dataRincianItAset->idIdentitasPrasarana ==
                                    $value->idIdentitasPrasarana ? 'selected' : null ?>>
                                    <?=$value->namaPrasarana?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Barang</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="2" tabindex="-1" aria-hidden="true"
                                id="idKategoriManajemen" name="idKategoriManajemen">
                                <option value="" selected disabled hidden>Pilih kategori</option>
                                <?php foreach($dataKategoriManajemen as $key =>$value): ?>
                                <option value="<?=$value->idKategoriManajemen?>" <?=$dataRincianItAset->
                                    idKategoriManajemen == $value->idKategoriManajemen ? 'selected' : null ?>>
                                    <?=$value->namaKategoriManajemen?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="3" tabindex="-1" aria-hidden="true"
                                id="idIdentitasSarana" name="idIdentitasSarana">
                                <option value="" selected disabled hidden>Pilih aset</option>
                                <?php foreach($dataIdentitasSarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasSarana?>" <?=$dataRincianItAset->idIdentitasSarana ==
                                    $value->idIdentitasSarana ? 'selected' : null ?>>
                                    <?=$value->namaSarana?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nomorBarang" class="col-sm-3 col-form-label">Nomor Barang</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nomorBarang" name="nomorBarang"
                            value="<?=$dataRincianItAset->nomorBarang?>"placeholder="Masukkan nomor barang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="4" tabindex="-1" aria-hidden="true" id="status"
                                name="status">
                                <option value="" selected disabled hidden>Pilih status</option>
                            <option value="Bagus" <?= $dataRincianItAset->status == 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                            <option value="Rusak" <?= $dataRincianItAset->status == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                            <option value="Hilang" <?= $dataRincianItAset->status == 'Hilang' ? 'selected' : '' ?>>Hilang</option>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="5" tabindex="-1" aria-hidden="true" id="idSumberDana"
                                name="idSumberDana">
                                <option value="" selected disabled hidden>Pilih sumber dana</option>
                                <?php foreach($dataSumberDana as $key =>$value): ?>
                                <option value="<?=$value->idSumberDana?>" <?=$dataRincianItAset->idSumberDana ==
                                    $value->idSumberDana ? 'selected' : null ?>>
                                    <?=$value->namaSumberDana?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tahunPengadaan" class="col-sm-3 col-form-label">Tahun Pengadaan</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="tahunPengadaan" name="tahunPengadaan"
                                value="<?=$dataRincianItAset->tahunPengadaan == 0 ? '0000' : $dataRincianItAset->tahunPengadaan?>" placeholder="Masukkan tahun pengadaan">
                                <p class="text-primary" style="font-size: 12px;">Jika tahun pengadaan tidak diketahui, tulis dengan <b>0000</b></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="hargaBeli" class="col-sm-3 col-form-label">Harga Beli</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="hargaBeli" name="hargaBeli"
                                value="<?=$dataRincianItAset->hargaBeli?>"placeholder="Masukkan harga beli">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="noSeri" class="col-sm-3 col-form-label">No Seri</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="noSeri" name="noSeri"
                            value="<?=$dataRincianItAset->noSeri?>"placeholder="Masukkan nomor seri">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="merk" class="col-sm-3 col-form-label">Merek</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="merk" name="merk"
                            value="<?=$dataRincianItAset->merk?>"placeholder="Masukkan merek">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="warna" class="col-sm-3 col-form-label">Warna</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="warna" name="warna"
                            value="<?=$dataRincianItAset->warna?>"placeholder="Masukkan warna">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="spesifikasi" class="col-sm-3 col-form-label">Spesifikasi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="spesifikasi" name="spesifikasi" rows="10"
                                placeholder="Masukkan spesifikasi aset"><?=$dataRincianItAset->spesifikasi?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti" value="<?=$dataRincianItAset->bukti?>"  placeholder="Masukkan link bukti">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('rincianAset') ?>" class="btn btn-secondary me-2">Cancel</a>
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
        var kodeRincianAset = $('#kodeRincianAset').val();
        // alert('Before sending AJAX request: ' + kodeRincianAset);

        $.ajax({
            type: 'POST',
            url: 'updateCheckDuplicate',
            data: { kodeRincianAset: kodeRincianAset },
            success: function (response) {
                // alert('Inside success function: ' + response);
                var result = JSON.parse(response);
                if (result.isDuplicate) {
                    // alert('Kode Rincian Aset is a duplicate. Please change it.');
                        Swal.fire({
                        icon: 'error',
                        title: 'Duplicate Entry',
                        text: 'Kode Rincian Aset tidak boleh duplikat!',
                    });
                } else {
                    $('#custom-validation').submit();
                }
            }
        });
    }

    $(document).ready(function () {
        $('#idKategoriManajemen, #idIdentitasPrasarana, #idSumberDana, #tahunPengadaan, #idIdentitasSarana, #nomorBarang').change(function () {
            var idKategoriManajemen = $('#idKategoriManajemen').val();
            var idIdentitasPrasarana = $('#idIdentitasPrasarana').val();
            var idSumberDana = $('#idSumberDana').val();
            var tahunPengadaan = $('#tahunPengadaan').val();
            var idIdentitasSarana = $('#idIdentitasSarana').val();
            var nomorBarang = $('#nomorBarang').val();

            $.ajax({
                type: 'POST',
                url: 'updateKode',
                data: {
                    idKategoriManajemen: idKategoriManajemen,
                    idIdentitasPrasarana: idIdentitasPrasarana,
                    idSumberDana: idSumberDana,
                    tahunPengadaan: tahunPengadaan,
                    idIdentitasSarana: idIdentitasSarana,
                    nomorBarang: nomorBarang
                },
                success: function (data) {
                    data = data.replace(/"/g, '');
                    $('#kodeRincianAset').val(data);
                }
            });
        });
    });
</script>

<?= $this->endSection(); ?>