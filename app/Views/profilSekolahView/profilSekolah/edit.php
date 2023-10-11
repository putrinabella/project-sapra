<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Edit Profil Sekolah &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Profil Sekolah</h4>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                    <h5>Identitas Sekolah</h5>
                    <br>
                <form action="<?= site_url('profilSekolah/'.$dataProfilSekolah->idProfilSekolah)?>"
                    method="post" autocomplete="off" id="custom-validation" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="row mb-3">
                        <label for="npsn" class="col-sm-3 col-form-label">NPSN</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="npsn" name="npsn" value="<?=$dataProfilSekolah->npsn?>" >
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                        <select class="form-select" id="status" name="status">
                            <option value="" hidden>Pilih status</option>
                            <option value="Swasta" <?= $dataProfilSekolah->status == "Swasta" ? 'selected' : ''; ?>>Swasta</option>
                            <option value="Negeri" <?= $dataProfilSekolah->status == "Negeri" ? 'selected' : ''; ?>>Negeri</option>
                        </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bentukPendidikan" class="col-sm-3 col-form-label">Bentuk Pendidikan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bentukPendidikan" name="bentukPendidikan" value="<?=$dataProfilSekolah->bentukPendidikan?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="statusKepemilikan" class="col-sm-3 col-form-label">Status Kepemilikan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="statusKepemilikan" name="statusKepemilikan" value="<?=$dataProfilSekolah->statusKepemilikan?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="skPendirian" class="col-sm-3 col-form-label">SK Pendirian</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="skPendirian" name="skPendirian" value="<?=$dataProfilSekolah->skPendirian?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tanggalSkPendirian" class="col-sm-3 col-form-label">Tanggal SK Pendirian</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tanggal">
                                    <input type="text" class="form-control" name="tanggalSkPendirian">
                                    <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="skIzinOperasional" class="col-sm-3 col-form-label">SK Izin Operasional</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="skIzinOperasional" name="skIzinOperasional" value="<?=$dataProfilSekolah->skIzinOperasional?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tanggalSkIzinOperasional" class="col-sm-3 col-form-label">Tanggal SK Izin Operasional</label>
                        <div class="col-sm-9">
                        <divw class="input-group date datepicker" id="tanggalSkIzinOperasional">
                                <input type="text" class="form-control" name="tanggalSkIzinOperasional">
                                <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
                            </divw>
                        </div>
                    </div>
                    <br>
                    <h5>Data Rinci</h5>
                    <br>
                    <div class="row mb-3">
                        <label for="statusBos" class="col-sm-3 col-form-label">Status BOS</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="statusBos" name="statusBos" value="<?=$dataProfilSekolah->statusBos?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="waktuPenyelenggaraan" class="col-sm-3 col-form-label">Waktu Penyelenggaraan BOS</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="waktuPenyelenggaraan" name="waktuPenyelenggaraan" value="<?=$dataProfilSekolah->waktuPenyelenggaraan?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sertifikasiIso" class="col-sm-3 col-form-label">Sertifikasi ISO</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="sertifikasiIso" name="sertifikasiIso" value="<?=$dataProfilSekolah->sertifikasiIso?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sumberListrik" class="col-sm-3 col-form-label">Sumber Listrik</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="sumberListrik" name="sumberListrik" value="<?=$dataProfilSekolah->sumberListrik?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kecepatanInternet" class="col-sm-3 col-form-label">Kecepatan Internet</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kecepatanInternet" name="kecepatanInternet" value="<?=$dataProfilSekolah->kecepatanInternet?>">
                        </div>
                    </div>
                    <br>
                    <h5>Data Pelengkap</h5>
                    <br>
                    <div class="row mb-3">
                        <label for="siswaKebutuhanKhusus" class="col-sm-3 col-form-label">Siswa Kebutuhan Khusus</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="siswaKebutuhanKhusus" name="siswaKebutuhanKhusus" value="<?=$dataProfilSekolah->siswaKebutuhanKhusus?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaBank" class="col-sm-3 col-form-label">Nama Bank</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaBank" name="namaBank" value="<?=$dataProfilSekolah->namaBank?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="cabangKcp" class="col-sm-3 col-form-label">Cabang KCP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="cabangKcp" name="cabangKcp" value="<?=$dataProfilSekolah->cabangKcp?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="atasNamaRekening" class="col-sm-3 col-form-label">Atas Nama Rekening</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="atasNamaRekening" name="atasNamaRekening" value="<?=$dataProfilSekolah->atasNamaRekening?>">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-12 text-end">
                            <a href="<?= site_url('profilSekolah') ?>" class="btn btn-secondary me-2">Cancel</a>
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