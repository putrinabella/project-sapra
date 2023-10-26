<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Data Pemusnahan Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Data Pemusnahan Aset</h4>
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
                <form action="<?= site_url('pemusnahanAset/updatePemusnahan/'.$dataRincianAset->idRincianAset)?>" method="post"
                    autocomplete="off" id="custom-validation"  enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="idIdentitasPrasarana" class="col-sm-3 col-form-label">Lokasi</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="idIdentitasPrasarana" id="idIdentitasPrasarana" hidden
                            value="<?= $dataRincianAset->idIdentitasPrasarana ?>">
                            <input class="form-control" type="text" name="namaPrasarana" id="namaPrasarana" readonly
                            value="<?= $dataRincianAset->namaPrasarana ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idKategoriManajemen" class="col-sm-3 col-form-label">Kategori Barang</label>
                        <div class="col-sm-9">
                        <input class="form-control" type="text" name="idKategoriManajemen" id="idKategoriManajemen" hidden
                            value="<?= $dataRincianAset->idKategoriManajemen ?>">
                        <input class="form-control" type="text" name="namaKategoriManajemen" id="namaKategoriManajemen" readonly
                            value="<?= $dataRincianAset->namaKategoriManajemen ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idIdentitasSarana" class="col-sm-3 col-form-label">Nama Aset</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" name="idIdentitasSarana" id="idIdentitasSarana" hidden
                                value="<?= $dataRincianAset->idIdentitasSarana ?>">
                            <input class="form-control" type="text" name="namaSarana" id="namaSarana" readonly
                                value="<?= $dataRincianAset->namaSarana ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="nomorBarang" class="col-sm-3 col-form-label">Nomor Barang</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nomorBarang" name="nomorBarang"
                            value="<?=$dataRincianAset->nomorBarang?>"placeholder="Masukkan nomor barang" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="idSumberDana" class="col-sm-3 col-form-label">Sumber Dana</label>
                        <div class="col-sm-9">
                        <input class="form-control" type="text" name="idSumberDana" id="idSumberDana" hidden
                            value="<?= $dataRincianAset->idSumberDana ?>">
                        <input class="form-control" type="text" name="namaSumberDana" id="namaSumberDana" readonly
                            value="<?= $dataRincianAset->namaSumberDana ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tahunPengadaan" class="col-sm-3 col-form-label">Tahun Pengadaan</label>
                        <div class="col-sm-9">
                        <?php
                        $tahunPengadaan = str_pad($dataRincianAset->tahunPengadaan, 4, '0', STR_PAD_LEFT);
                        ?>
                            <input type="number" class="form-control" id="tahunPengadaan" name="tahunPengadaan"
                            value="<?=$tahunPengadaan?>" placeholder="Masukkan tahun pengadaan"readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="hargaBeli" class="col-sm-3 col-form-label">Harga Beli</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="hargaBeli" name="hargaBeli"
                                value="<?=$dataRincianAset->hargaBeli?>"placeholder="Masukkan harga beli" readonly>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bukti" class="col-sm-3 col-form-label">Bukti Dokumentasi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bukti" name="bukti" value="<?=$dataRincianAset->bukti?>"  placeholder="Masukkan link bukti">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('rincianAset') ?>" class="btn btn-secondary me-2">Cancel</a>
                            <button type="reset" class="btn btn-danger me-2">Reset</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>