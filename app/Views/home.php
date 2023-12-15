<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Home &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Selamat Datang,
            <?= session('nama'); ?>!
        </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group date datepicker wd-200  mb-2 mb-md-0" id="dashboardDate">
            <span class="input-group-text input-group-addon bg-transparent border-primary"><i data-feather="calendar"
                    class=" text-primary"></i></span>
            <input type="text" class="form-control border-primary bg-transparent" readonly>
        </div>
    </div>

</div>

<?php if (session()->get('role') == 'Super Admin') { ?>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="example">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item  text-center">
                                    <a class="nav-link active" id="aset-tab" data-bs-toggle="tab" href="#aset"
                                        role="tab" aria-controls="aset" aria-selected="true">Aset Sekolah</a>
                                </li>
                                <li class="nav-item  text-center">
                                    <a class="nav-link" id="labAset-tab" data-bs-toggle="tab" href="#labAset" role="tab"
                                        aria-controls="labAset" aria-selected="false">Aset Laboratorium</a>
                                </li>
                                <li class="nav-item  text-center">
                                    <a class="nav-link" id="itAset-tab" data-bs-toggle="tab" href="#itAset" role="tab"
                                        aria-controls="itAset" aria-selected="false">Aset Perangkat IT</a>
                                </li>
                                <li class="nav-item  text-center">
                                    <a class="nav-link" id="inventarisAset-tab" data-bs-toggle="tab"
                                        href="#inventarisAset" role="tab" aria-controls="inventarisAset"
                                        aria-selected="false">Non-Inventaris</a>
                                </li>
                                <li class="nav-item  text-center">
                                    <a class="nav-link" id="profil-tab" data-bs-toggle="tab" href="#profil" role="tab"
                                        aria-controls="profil" aria-selected="false">Profil Sekolah</a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="aset" role="tabpanel"
                                    aria-labelledby="aset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA ASET SMK TELKOM BANJARBARU</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataRincianAset as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->namaSarana; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->totalSarana; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>Buah</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaLayak; ?>
                                                                            </div>
                                                                            <div class="box-label">Bagus</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Rusak</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaDipinjam; ?>
                                                                            </div>
                                                                            <div class="box-label">Dipinjam</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="labAset" role="tabpanel" aria-labelledby="labAset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA ASET LABORATORIUM</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataRincianAsetLab as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->namaSarana; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->totalSarana; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>Buah</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaLayak; ?>
                                                                            </div>
                                                                            <div class="box-label">Bagus</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Rusak</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaDipinjam; ?>
                                                                            </div>
                                                                            <div class="box-label">Dipinjam</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="itAset" role="tabpanel" aria-labelledby="itAset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA ASET PERANGKAT IT</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataRincianItAset as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->namaSarana; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->totalSarana; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>Buah</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaLayak; ?>
                                                                            </div>
                                                                            <div class="box-label">Bagus</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Rusak</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Hilang</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="inventarisAset" role="tabpanel"
                                    aria-labelledby="inventarisAset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA INVENTARIS</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataNonInventaris as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->nama; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->inventarisMasuk - $value->inventarisKeluar; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>
                                                                            <?= $value->satuan; ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->inventarisMasuk; ?>
                                                                            </div>
                                                                            <div class="box-label">Masuk</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->inventarisKeluar ?>
                                                                            </div>
                                                                            <div class="box-label">Keluar</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                                    <div class="table-responsive">
                                        <center>
                                            <h5>SMK TELKOM BANJARBARU</h5>
                                        </center>
                                        <br>
                                    </div>
                                    <table class="my-table">
                                        <?php foreach ($dataProfilSekolah as $key => $value) : ?>
                                        <tr>
                                            <td style="width: 25%;">Kepala Sekolah</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kepsek; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Operator</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->operator; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Akreditasi</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->akreditasi; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kurikulum</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kurikulum; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Identitas Sekolah</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">NPSN</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npsn; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">NPWP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npwp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->status; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bentuk Pendidikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->bentukPendidikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Kepemilikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusKepemilikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Izin Operasional</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skIzinOperasional; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Izin Operasinal</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkIzinOperasional; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Rinci</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">Status BOS</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusBos; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Penyelenggaraan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->waktuPenyelenggaraan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Spesifikasi ISO</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sertifikasiIso; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sumber Listrik</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sumberListrik; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kecepatan Internet</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kecepatanInternet; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Pelengkap</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                        <tr>
                                            <td style="width: 25%;">Siswa Kebutuhan Khusus</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->siswaKebutuhanKhusus; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nama Bank</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->namaBank; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cabang KCP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->cabangKcp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Atas Nama Rekening</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->atasNamaRekening; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php endforeach; ?>
                                    <br>
                                    <br>
                                    <h5>Dokumen Pendukung</h5>
                                    <br>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered" style="width: 100%;">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th style="width: 5%;">No.</th>
                                                        <th style="width: 35%;">Nama Dokumen</th>
                                                        <th>Link</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="py-2">
                                                    <?php foreach ($dataDokumenSekolah as $key => $value) : ?>
                                                    <tr
                                                        style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                                        <td class="text-center">
                                                            <?=$key + 1?>
                                                        </td>
                                                        <td>
                                                            <?= $value->namaDokumenSekolah; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?= $value->linkDokumenSekolah; ?>"
                                                                target="_blank"> Link Dokumen
                                                                <?= $value->namaDokumenSekolah; ?>
                                                            </a>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<?php if (session()->get('role') == 'Laboran') { ?>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="example">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item text-center">
                                    <a class="nav-link active" id="labAset-tab" data-bs-toggle="tab" href="#labAset"
                                        role="tab" aria-controls="labAset" aria-selected="true">Aset Laboratorium</a>
                                </li>
                                <li class="nav-item text-center">
                                    <a class="nav-link" id="profil-tab" data-bs-toggle="tab" href="#profil" role="tab"
                                        aria-controls="profil" aria-selected="false">Profil Sekolah</a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="labAset" role="tabpanel"
                                    aria-labelledby="labAset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA ASET LABORATORIUM</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataRincianAsetLab as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->namaSarana; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->totalSarana; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>Buah</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaLayak; ?>
                                                                            </div>
                                                                            <div class="box-label">Bagus</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Rusak</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaDipinjam; ?>
                                                                            </div>
                                                                            <div class="box-label">Dipinjam</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                                    <div class="table-responsive">
                                        <center>
                                            <h5>SMK TELKOM BANJARBARU</h5>
                                        </center>
                                        <br>
                                    </div>
                                    <table class="my-table">
                                        <?php foreach ($dataProfilSekolah as $key => $value) : ?>
                                        <tr>
                                            <td style="width: 25%;">Kepala Sekolah</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kepsek; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Operator</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->operator; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Akreditasi</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->akreditasi; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kurikulum</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kurikulum; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Identitas Sekolah</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">NPSN</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npsn; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">NPWP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npwp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->status; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bentuk Pendidikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->bentukPendidikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Kepemilikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusKepemilikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Izin Operasional</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skIzinOperasional; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Izin Operasinal</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkIzinOperasional; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Rinci</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">Status BOS</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusBos; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Penyelenggaraan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->waktuPenyelenggaraan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Spesifikasi ISO</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sertifikasiIso; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sumber Listrik</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sumberListrik; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kecepatan Internet</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kecepatanInternet; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Pelengkap</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                        <tr>
                                            <td style="width: 25%;">Siswa Kebutuhan Khusus</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->siswaKebutuhanKhusus; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nama Bank</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->namaBank; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cabang KCP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->cabangKcp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Atas Nama Rekening</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->atasNamaRekening; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php endforeach; ?>
                                    <br>
                                    <br>
                                    <h5>Dokumen Pendukung</h5>
                                    <br>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered" style="width: 100%;">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th style="width: 5%;">No.</th>
                                                        <th style="width: 35%;">Nama Dokumen</th>
                                                        <th>Link</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="py-2">
                                                    <?php foreach ($dataDokumenSekolah as $key => $value) : ?>
                                                    <tr
                                                        style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                                        <td class="text-center">
                                                            <?=$key + 1?>
                                                        </td>
                                                        <td>
                                                            <?= $value->namaDokumenSekolah; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?= $value->linkDokumenSekolah; ?>"
                                                                target="_blank"> Link Dokumen
                                                                <?= $value->namaDokumenSekolah; ?>
                                                            </a>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<?php if (session()->get('role') == 'Admin Sarpra') { ?>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="example">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item text-center">
                                    <a class="nav-link active" id="aset-tab" data-bs-toggle="tab" href="#aset"
                                        role="tab" aria-controls="aset" aria-selected="true">Aset Sekolah</a>
                                </li>
                                <li class="nav-item text-center">
                                    <a class="nav-link" id="inventarisAset-tab" data-bs-toggle="tab"
                                        href="#inventarisAset" role="tab" aria-controls="inventarisAset"
                                        aria-selected="false">Non-Inventaris</a>
                                </li>
                                <li class="nav-item text-center">
                                    <a class="nav-link" id="profil-tab" data-bs-toggle="tab" href="#profil" role="tab"
                                        aria-controls="profil" aria-selected="false">Profil Sekolah</a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="aset" role="tabpanel"
                                    aria-labelledby="aset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA ASET SMK TELKOM BANJARBARU</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataRincianAset as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->namaSarana; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->totalSarana; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>Buah</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaLayak; ?>
                                                                            </div>
                                                                            <div class="box-label">Bagus</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Rusak</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaDipinjam; ?>
                                                                            </div>
                                                                            <div class="box-label">Dipinjam</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="inventarisAset" role="tabpanel"
                                    aria-labelledby="inventarisAset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA INVENTARIS</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataNonInventaris as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->nama; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->inventarisMasuk - $value->inventarisKeluar; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>
                                                                            <?= $value->satuan; ?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->inventarisMasuk; ?>
                                                                            </div>
                                                                            <div class="box-label">Masuk</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->inventarisKeluar ?>
                                                                            </div>
                                                                            <div class="box-label">Keluar</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                                    <div class="table-responsive">
                                        <center>
                                            <h5>SMK TELKOM BANJARBARU</h5>
                                        </center>
                                        <br>
                                    </div>
                                    <table class="my-table">
                                        <?php foreach ($dataProfilSekolah as $key => $value) : ?>
                                        <tr>
                                            <td style="width: 25%;">Kepala Sekolah</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kepsek; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Operator</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->operator; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Akreditasi</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->akreditasi; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kurikulum</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kurikulum; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Identitas Sekolah</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">NPSN</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npsn; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">NPWP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npwp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->status; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bentuk Pendidikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->bentukPendidikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Kepemilikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusKepemilikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Izin Operasional</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skIzinOperasional; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Izin Operasinal</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkIzinOperasional; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Rinci</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">Status BOS</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusBos; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Penyelenggaraan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->waktuPenyelenggaraan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Spesifikasi ISO</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sertifikasiIso; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sumber Listrik</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sumberListrik; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kecepatan Internet</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kecepatanInternet; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Pelengkap</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                        <tr>
                                            <td style="width: 25%;">Siswa Kebutuhan Khusus</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->siswaKebutuhanKhusus; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nama Bank</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->namaBank; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cabang KCP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->cabangKcp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Atas Nama Rekening</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->atasNamaRekening; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php endforeach; ?>
                                    <br>
                                    <br>
                                    <h5>Dokumen Pendukung</h5>
                                    <br>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered" style="width: 100%;">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th style="width: 5%;">No.</th>
                                                        <th style="width: 35%;">Nama Dokumen</th>
                                                        <th>Link</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="py-2">
                                                    <?php foreach ($dataDokumenSekolah as $key => $value) : ?>
                                                    <tr
                                                        style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                                        <td class="text-center">
                                                            <?=$key + 1?>
                                                        </td>
                                                        <td>
                                                            <?= $value->namaDokumenSekolah; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?= $value->linkDokumenSekolah; ?>"
                                                                target="_blank"> Link Dokumen
                                                                <?= $value->namaDokumenSekolah; ?>
                                                            </a>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>


<?php if (session()->get('role') == 'Admin IT') { ?>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="example">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item text-center">
                                    <a class="nav-link active" id="labAset-tab" data-bs-toggle="tab" href="#labAset"
                                        role="tab" aria-controls="labAset" aria-selected="true">Aset Perangkat IT</a>
                                </li>
                                <li class="nav-item text-center">
                                    <a class="nav-link" id="profil-tab" data-bs-toggle="tab" href="#profil" role="tab"
                                        aria-controls="profil" aria-selected="false">Profil Sekolah</a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3" id="myTabContent">
                                <div class="tab-pane fade show active" id="labAset" role="tabpanel"
                                    aria-labelledby="labAset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA ASET PERANGKAT IT</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataRincianItAset as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->namaSarana; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->totalSarana; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>Buah</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaLayak; ?>
                                                                            </div>
                                                                            <div class="box-label">Bagus</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaRusak ?>
                                                                            </div>
                                                                            <div class="box-label">Rusak</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Hilang</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                                    <div class="table-responsive">
                                        <center>
                                            <h5>SMK TELKOM BANJARBARU</h5>
                                        </center>
                                        <br>
                                    </div>
                                    <table class="my-table">
                                        <?php foreach ($dataProfilSekolah as $key => $value) : ?>
                                        <tr>
                                            <td style="width: 25%;">Kepala Sekolah</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kepsek; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Operator</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->operator; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Akreditasi</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->akreditasi; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kurikulum</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kurikulum; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Identitas Sekolah</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">NPSN</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npsn; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">NPWP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npwp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->status; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bentuk Pendidikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->bentukPendidikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Kepemilikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusKepemilikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Izin Operasional</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skIzinOperasional; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Izin Operasinal</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkIzinOperasional; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Rinci</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">Status BOS</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusBos; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Penyelenggaraan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->waktuPenyelenggaraan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Spesifikasi ISO</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sertifikasiIso; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sumber Listrik</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sumberListrik; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kecepatan Internet</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kecepatanInternet; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Pelengkap</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                        <tr>
                                            <td style="width: 25%;">Siswa Kebutuhan Khusus</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->siswaKebutuhanKhusus; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nama Bank</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->namaBank; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cabang KCP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->cabangKcp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Atas Nama Rekening</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->atasNamaRekening; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php endforeach; ?>
                                    <br>
                                    <br>
                                    <h5>Dokumen Pendukung</h5>
                                    <br>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered" style="width: 100%;">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th style="width: 5%;">No.</th>
                                                        <th style="width: 35%;">Nama Dokumen</th>
                                                        <th>Link</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="py-2">
                                                    <?php foreach ($dataDokumenSekolah as $key => $value) : ?>
                                                    <tr
                                                        style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                                        <td class="text-center">
                                                            <?=$key + 1?>
                                                        </td>
                                                        <td>
                                                            <?= $value->namaDokumenSekolah; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?= $value->linkDokumenSekolah; ?>"
                                                                target="_blank"> Link Dokumen
                                                                <?= $value->namaDokumenSekolah; ?>
                                                            </a>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php if (session()->get('role') == 'User') { ?>

<div class="row">
    <div class="col-12 col-xl-12 stretch-card">
        <div class="row flex-grow-1">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="example">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item  text-center">
                                    <a class="nav-link active" id="aset-tab" data-bs-toggle="tab" href="#aset"
                                        role="tab" aria-controls="aset" aria-selected="true">Aset Sekolah</a>
                                </li>
                                <li class="nav-item text-center">
                                    <a class="nav-link" id="labAset-tab" data-bs-toggle="tab" href="#labAset"
                                        role="tab" aria-controls="labAset" aria-selected="true">Aset Laboratorium</a>
                                </li>
                                <li class="nav-item text-center">
                                    <a class="nav-link" id="profil-tab" data-bs-toggle="tab" href="#profil" role="tab"
                                        aria-controls="profil" aria-selected="false">Profil Sekolah</a>
                                </li>
                            </ul>
                            <div class="tab-content border border-top-0 p-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="aset" role="tabpanel"
                                    aria-labelledby="aset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA ASET SMK TELKOM BANJARBARU</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataRincianAset as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->namaSarana; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->totalSarana; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>Buah</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaLayak; ?>
                                                                            </div>
                                                                            <div class="box-label">Bagus</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Rusak</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaDipinjam; ?>
                                                                            </div>
                                                                            <div class="box-label">Dipinjam</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="labAset" role="tabpanel"
                                    aria-labelledby="labAset-tab">
                                    <div class="row text-center mb-4">
                                        <center>
                                            <h5>DATA ASET LABORATORIUM</h5>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-xl-12 stretch-card">
                                            <div class="row flex-grow-1">
                                                <?php foreach ($dataRincianAsetLab as $key => $value) : ?>
                                                <div class="col-md-4 grid-margin stretch-card">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-7">
                                                                    <div class="row">
                                                                        <h6 class="card-title text-center">
                                                                            <?= $value->namaSarana; ?>
                                                                        </h6>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <h1>
                                                                            <?= $value->totalSarana; ?>
                                                                        </h1>
                                                                    </div>
                                                                    <div class="row text-center">
                                                                        <p>Buah</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-5">
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaLayak; ?>
                                                                            </div>
                                                                            <div class="box-label">Bagus</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaRusak + $value->saranaHilang; ?>
                                                                            </div>
                                                                            <div class="box-label">Rusak</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="box">
                                                                            <div class="box-number">
                                                                                <?= $value->saranaDipinjam; ?>
                                                                            </div>
                                                                            <div class="box-label">Dipinjam</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="profil" role="tabpanel" aria-labelledby="profil-tab">
                                    <div class="table-responsive">
                                        <center>
                                            <h5>SMK TELKOM BANJARBARU</h5>
                                        </center>
                                        <br>
                                    </div>
                                    <table class="my-table">
                                        <?php foreach ($dataProfilSekolah as $key => $value) : ?>
                                        <tr>
                                            <td style="width: 25%;">Kepala Sekolah</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kepsek; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Operator</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->operator; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Akreditasi</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->akreditasi; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kurikulum</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kurikulum; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Identitas Sekolah</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">NPSN</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npsn; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 25%;">NPWP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->npwp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->status; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Bentuk Pendidikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->bentukPendidikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Kepemilikan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusKepemilikan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Pendirian</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkPendirian; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SK Izin Operasional</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->skIzinOperasional; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal SK Izin Operasinal</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->tanggalSkIzinOperasional; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Rinci</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                            <td style="width: 25%;">Status BOS</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->statusBos; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Waktu Penyelenggaraan</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->waktuPenyelenggaraan; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Spesifikasi ISO</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sertifikasiIso; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sumber Listrik</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->sumberListrik; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Kecepatan Internet</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->kecepatanInternet; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <br>
                                    <h5>Data Pelengkap</h5>
                                    <br>
                                    <table class="my-table">
                                        <tr>
                                        <tr>
                                            <td style="width: 25%;">Siswa Kebutuhan Khusus</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->siswaKebutuhanKhusus; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nama Bank</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->namaBank; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Cabang KCP</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->cabangKcp; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Atas Nama Rekening</td>
                                            <td style="width: 2%;">:</td>
                                            <td>
                                                <?= $value->atasNamaRekening; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <?php endforeach; ?>
                                    <br>
                                    <br>
                                    <h5>Dokumen Pendukung</h5>
                                    <br>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-bordered" style="width: 100%;">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th style="width: 5%;">No.</th>
                                                        <th style="width: 35%;">Nama Dokumen</th>
                                                        <th>Link</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="py-2">
                                                    <?php foreach ($dataDokumenSekolah as $key => $value) : ?>
                                                    <tr
                                                        style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                                        <td class="text-center">
                                                            <?=$key + 1?>
                                                        </td>
                                                        <td>
                                                            <?= $value->namaDokumenSekolah; ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?= $value->linkDokumenSekolah; ?>"
                                                                target="_blank"> Link Dokumen
                                                                <?= $value->namaDokumenSekolah; ?>
                                                            </a>
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
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?= $this->endSection(); ?>