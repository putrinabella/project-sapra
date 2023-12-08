<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Tambah Profil Sekolah &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sekolah</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('profilSekolah')?>">Profil Sekolah</a></li>
        <li class="breadcrumb-item active" aria-current="page">Input Data</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
            <form action="<?= site_url('profilSekolah')?>" method="post" autocomplete="off"  id="custom-validation">
                        <h5>SMK TELKOM BANJARBARU</h5>
                    <br>
                    <div class="row mb-3">
                        <label for="kepsek" class="col-sm-3 col-form-label">Nama Kepala Sekolah</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kepsek" name="kepsek" placeholder="Masukkan Nama Kepala Sekolah">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="operator" class="col-sm-3 col-form-label">Operator</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="operator" name="operator" placeholder="Masukkan Nama Operator">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="akreditasi" class="col-sm-3 col-form-label">akreditasi</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="akreditasi" name="akreditasi">
                                <option value="" hidden>Pilih status</option>
                                <option value="A" >A</option>
                                <option value="B" >B</option>
                                <option value="C" >C</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kurikulum" class="col-sm-3 col-form-label">Kurikulum</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="kurikulum" name="kurikulum">
                                <option value="" hidden>Pilih Kurikulum</option>
                                <option value="Kurikulum 2006" >Kurikulum 2006</option>
                                <option value="Kurikulum 2013" >Kurikulum 2013</option>
                                <option value="Kurikulum Merdeka" >Kurikulum Merdeka</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <h5>Identitas Sekolah</h5>
                    <br>
                    <div class="row mb-3">
                        <label for="npsn" class="col-sm-3 col-form-label">NPSN</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="npsn" name="npsn"
                                placeholder="Masukkan NPSN">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="npwp" class="col-sm-3 col-form-label">NPWP</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="npwp" name="npwp"
                                placeholder="Masukkan NPWP">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select class="form-select" id="status" name="status">
                                <option value="" hidden>Pilih status</option>
                                <option value="Swasta" >Swasta</option>
                                <option value="Negeri" >Negeri</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="bentukPendidikan" class="col-sm-3 col-form-label">Bentuk Pendidikan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="bentukPendidikan" name="bentukPendidikan"
                                placeholder="Masukkan Bentuk Pendidikan">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="statusKepemilikan" class="col-sm-3 col-form-label">Status Kepemilikan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="statusKepemilikan" name="statusKepemilikan"
                                placeholder="Masukkan Status Sekolah">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="skPendirian" class="col-sm-3 col-form-label">SK Pendirian</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="skPendirian" name="skPendirian"
                                placeholder="Masukkan Nomor SK Pendirian">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tanggalSkPendirian" class="col-sm-3 col-form-label">Tanggal SK Pendirian</label>
                        <div class="col-sm-9">
                            <div class="input-group date datepicker" id="tanggal">
                                    <input type="text" class="form-control" name="tanggalSkPendirian">
                                    <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="skIzinOperasional" class="col-sm-3 col-form-label">SK Izin Operasional</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="skIzinOperasional" name="skIzinOperasional"
                                placeholder="Masukkan Nomor SK Izin Operasional">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="tanggalSkIzinOperasional" class="col-sm-3 col-form-label">Tanggal SK Izin Operasional</label>
                        <div class="col-sm-9">
                        <divw class="input-group date datepicker" id="tanggalSkIzinOperasional">
                                <input type="text" class="form-control" name="tanggalSkIzinOperasional">
                                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
                            </divw>
                        </div>
                    </div>
                    <br>
                    <h5>Data Rinci</h5>
                    <br>
                    <div class="row mb-3">
                        <label for="statusBos" class="col-sm-3 col-form-label">Status BOS</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="statusBos" name="statusBos"
                                placeholder="Masukkan Status BOS">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="waktuPenyelenggaraan" class="col-sm-3 col-form-label">Waktu Penyelenggaraan BOS</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="waktuPenyelenggaraan" name="waktuPenyelenggaraan"
                                placeholder="Masukkan waktu penyelenggaraan BOS">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sertifikasiIso" class="col-sm-3 col-form-label">Sertifikasi ISO</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="sertifikasiIso" name="sertifikasiIso"
                                placeholder="Masukkan Nomor Sertifikasi ISO">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="sumberListrik" class="col-sm-3 col-form-label">Sumber Listrik</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="sumberListrik" name="sumberListrik"
                                placeholder="Masukkan sumber listrik">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="kecepatanInternet" class="col-sm-3 col-form-label">Kecepatan Internet</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="kecepatanInternet" name="kecepatanInternet"
                                placeholder="Masukkan kecepatan internet">
                        </div>
                    </div>
                    <br>
                    <h5>Data Pelengkap</h5>
                    <br>
                    <div class="row mb-3">
                        <label for="siswaKebutuhanKhusus" class="col-sm-3 col-form-label">Siswa Kebutuhan Khusus</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="siswaKebutuhanKhusus" name="siswaKebutuhanKhusus"
                                placeholder="Masukkan jumlah siswa kebutuhan khusus">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="namaBank" class="col-sm-3 col-form-label">Nama Bank</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="namaBank" name="namaBank"
                                placeholder="Masukkan nama Bank">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="cabangKcp" class="col-sm-3 col-form-label">Cabang KCP</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="cabangKcp" name="cabangKcp"
                                placeholder="Masukkan cabang KCP">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="atasNamaRekening" class="col-sm-3 col-form-label">Atas Nama Rekening</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="atasNamaRekening" name="atasNamaRekening"
                                placeholder="Masukkan atas nama rekening">
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