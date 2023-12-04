<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Rincian Aset &verbar; SARPRA </title>
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
                    <h4>Edit Data</h4>
                </div>
            </div>
            <div class="card-body">
                <form action="<?= site_url('rincianLabAset/'.$dataRincianLabAset->idRincianLabAset)?>" method="post"
                    autocomplete="off" id="custom-validation"  enctype="multipart/form-data">
                    
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="kodeRincianLabAset" class="col-sm-3 col-form-label">Kode Rincian Aset</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kodeRincianLabAset" name="kodeRincianLabAset"
                                placeholder="Kode akan dibuat secara otomatis" value=" <?= $dataRincianLabAset->kodeRincianLabAset; ?> "; readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasLab" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true"
                                id="idIdentitasLab" name="idIdentitasLab">
                                <option value="" selected disabled hidden>Pilih lokasi</option>
                                <?php foreach($dataIdentitasLab as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasLab?>" <?=$dataRincianLabAset->idIdentitasLab ==
                                    $value->idIdentitasLab ? 'selected' : null ?>>
                                    <?=$value->namaLab?>
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
                                <option value="<?=$value->idKategoriManajemen?>" <?=$dataRincianLabAset->
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
                                <option value="<?=$value->idIdentitasSarana?>" <?=$dataRincianLabAset->idIdentitasSarana ==
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
                            value="<?=$dataRincianLabAset->nomorBarang?>"placeholder="Masukkan nomor barang">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                        <select class="js-example-basic-single form-select select2-hidden-accessible"
                                data-width="100%" data-select2-id="4" tabindex="-1" aria-hidden="true" id="status"
                                name="status">
                                <option value="" selected disabled hidden>Pilih status</option>
                            <option value="Bagus" <?= $dataRincianLabAset->status == 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                            <option value="Rusak" <?= $dataRincianLabAset->status == 'Rusak' ? 'selected' : '' ?>>Rusak</option>
                            <option value="Hilang" <?= $dataRincianLabAset->status == 'Hilang' ? 'selected' : '' ?>>Hilang</option>
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
                                <option value="<?=$value->idSumberDana?>" <?=$dataRincianLabAset->idSumberDana ==
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
                                value="<?=$dataRincianLabAset->tahunPengadaan == 0 ? '0000' : $dataRincianLabAset->tahunPengadaan?>" placeholder="Masukkan tahun pengadaan">
                                <p class="text-primary" style="font-size: 12px;">Jika tahun pengadaan tidak diketahui, tulis dengan <b>0000</b></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="hargaBeli" class="col-sm-3 col-form-label">Harga Beli</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="hargaBeli" name="hargaBeli"
                                value="<?=$dataRincianLabAset->hargaBeli?>"placeholder="Masukkan harga beli">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="noSeri" class="col-sm-3 col-form-label">No Seri</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="noSeri" name="noSeri"
                            value="<?=$dataRincianLabAset->noSeri?>"placeholder="Masukkan nomor seri">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="merk" class="col-sm-3 col-form-label">Merek</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="merk" name="merk"
                            value="<?=$dataRincianLabAset->merk?>"placeholder="Masukkan merek">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="warna" class="col-sm-3 col-form-label">Warna</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="warna" name="warna"
                            value="<?=$dataRincianLabAset->warna?>"placeholder="Masukkan warna">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="spesifikasi" class="col-sm-3 col-form-label">Spesifikasi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="spesifikasi" name="spesifikasi" rows="10"
                                placeholder="Masukkan spesifikasi aset"><?=$dataRincianLabAset->spesifikasi?></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti" value="<?=$dataRincianLabAset->bukti?>"  placeholder="Masukkan link bukti">
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
        // alert('Before sending AJAX request: ' + kodeRincianLabAset);

        $.ajax({
            type: 'POST',
            url: 'updateCheckDuplicate',
            data: { kodeRincianLabAset: kodeRincianLabAset },
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
        $('#idKategoriManajemen, #idIdentitasLab, #idSumberDana, #tahunPengadaan, #idIdentitasSarana, #nomorBarang').change(function () {
            var idKategoriManajemen = $('#idKategoriManajemen').val();
            var idIdentitasLab = $('#idIdentitasLab').val();
            var idSumberDana = $('#idSumberDana').val();
            var tahunPengadaan = $('#tahunPengadaan').val();
            var idIdentitasSarana = $('#idIdentitasSarana').val();
            var nomorBarang = $('#nomorBarang').val();

            $.ajax({
                type: 'POST',
                url: 'updateKode',
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