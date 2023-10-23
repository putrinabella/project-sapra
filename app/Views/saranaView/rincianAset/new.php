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
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idIdentitasSarana" name="idIdentitasSarana">
                                <option value="" hidden>Pilih aset</option>
                                <?php foreach($dataIdentitasSarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasSarana?>"><?=$value->namaSarana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasPrasarana" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idIdentitasPrasarana" name="idIdentitasPrasarana">
                                <option value="" hidden>Pilih lokasi</option>
                                <?php foreach($dataIdentitasPrasarana as $key =>$value): ?>
                                <option value="<?=$value->idIdentitasPrasarana?>"><?=$value->namaPrasarana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idSumberDana" name="idSumberDana">
                                <option value="" hidden>Pilih sumber dana</option>
                                <?php foreach($dataSumberDana as $key =>$value): ?>
                                <option value="<?=$value->idSumberDana?>"><?=$value->namaSumberDana?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Manajemen</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="idKategoriManajemen" name="idKategoriManajemen">
                                <option value="" hidden>Pilih Kategori MEP</option>
                                <?php foreach($dataKategoriManajemen as $key =>$value): ?>
                                <option value="<?=$value->idKategoriManajemen?>"><?=$value->namaKategoriManajemen?>
                                </option>
                                <?php endforeach; ?>
                            </select>
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
                        <label for="tahunPengadaan" class="col-sm-3 col-form-label">Tahun Pengadaan</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="tahunPengadaan" name="tahunPengadaan"
                                placeholder="Masukkan tahun pengadaan">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="saranaLayak" class="col-sm-3 col-form-label">Jumlah Aset Layak</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="saranaLayak" name="saranaLayak"
                                placeholder="Masukkan jumlah aset layak">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="saranaRusak" class="col-sm-3 col-form-label">Jumlah Aset Rusak</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="saranaRusak" name="saranaRusak"
                                placeholder="Masukkan jumlah aset rusak">
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
                            <p id="bukti-error" class="text-danger"></p>
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

<script>
    const buktiInput = document.getElementById('bukti');
    const buktiError = document.getElementById('bukti-error');
    const submitButton = document.getElementById('submit-button');
    let buktiErrorShown = false;

    buktiInput.addEventListener('input', () => {
        const inputText = buktiInput.value;
        if (inputText.trim() === '') {
            buktiError.textContent = '';
            buktiErrorShown = false;
            return;
        }
        if (!containsGoogleDriveLink(inputText)) {
            buktiError.textContent = 'Your input does not contain a Google Drive link';
            buktiErrorShown = true;
        } else {
            buktiError.textContent = '';
            buktiErrorShown = false;
        }
    });

    submitButton.addEventListener('click', (event) => {
        if (buktiErrorShown) {
            event.preventDefault();
        }
    });

    function containsGoogleDriveLink(text) {
        // Regular expression to check if the text contains a Google Drive link
        const driveLinkPattern = /https:\/\/drive\.google\.com\/file\/d\//;
        return driveLinkPattern.test(text);
    }
</script>

                    <!-- <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti" placeholder="Masukkan link dokumentasi">
                            <p id="bukti-error" class="text-danger"></p>
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

<script>
    const buktiInput = document.getElementById('bukti');
    const buktiError = document.getElementById('bukti-error');
    const submitButton = document.getElementById('submit-button');

    submitButton.addEventListener('click', (event) => {
        const inputText = buktiInput.value;
        if (!isValidGoogleDriveURL(inputText)) {
            buktiError.textContent = 'Your input is not a valid Google Drive link';
            event.preventDefault(); // Prevent form submission
        }
    });

    function isValidGoogleDriveURL(text) {
        // Regular expression to match Google Drive links
        const driveLinkPattern = /^https:\/\/drive\.google\.com\/file\/d\/[a-zA-Z0-9_-]+\/view\?usp=sharing$/;
        return driveLinkPattern.test(text);
    }
</script> -->

<?= $this->endSection(); ?>