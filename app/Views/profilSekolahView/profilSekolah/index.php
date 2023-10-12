<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Profil Sekolah &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Profil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Profil Sekolah</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Profil Sekolah</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('profilSekolah/print/'. $firstRecordId) ?>"
            class="btn btn-success btn-icon-text me-2 mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="printer"></i>
            Print
        </a>

        <?php if ($rowCount == 1): ?>
        <?php foreach ($dataProfilSekolah as $key => $value) : ?>
        <a href="<?= site_url('profilSekolah/'.$value->idProfilSekolah.'/edit') ?>"
            class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="edit-2"></i>
            Edit
        </a>
        <?php endforeach; ?>
        <?php elseif ($rowCount < 1): ?>
        <a href="<?= site_url('profilSekolah/new') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="edit"></i>
            Tambah
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div>
                    <?php if(session()->getFlashdata('success')) :?>
                    <div class="alert alert-success alert-dismissible show fade" role="alert" id="alert">
                        <div class="alert-body">
                            <b>Success!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="btn-close"></button>
                            <?=session()->getFlashdata('success')?>
                        </div>
                    </div>
                    <br>
                    <?php endif; ?>
                    <?php if(session()->getFlashdata('error')) :?>
                    <div class="alert alert-danger alert-dismissible show fade" role="alert" id="alert">
                        <div class="alert-body">
                            <b>Error!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="btn-close"></button>
                            <?=session()->getFlashdata('error')?>
                        </div>
                    </div>
                    <br>
                    <?php endif; ?>
                </div>

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
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <h4 class="mb-3 mb-md-0">Dokumen Pendukung</h4>
                    </div>
                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                        <a href="<?= site_url('profilSekolah/trashDokumen') ?>"
                            class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
                            <i class=" btn-icon-prepend" data-feather="trash"></i>
                            Recycle Bin
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-success btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0"
                                type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class=" btn-icon-prepend" data-feather="download"></i>
                                Export File
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?= site_url('profilSekolah/exportDokumen') ?>">Download as
                                    Excel</a>
                                <a class="dropdown-item" href="<?= site_url('profilSekolah/generatePDFDokumen') ?>">Download as
                                    PDF</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0"
                                type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class=" btn-icon-prepend" data-feather="upload"></i>
                                Import File
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="<?= site_url('profilSekolah/createTemplateDokumen') ?>">Download
                                    Template</a>
                                <a class="dropdown-item" href="" data-bs-toggle="modal"
                                    data-bs-target="#modalImport">Upload Excel</a>
                            </div>
                        </div>
                        <a href="<?= site_url('profilSekolah/newDokumen') ?>"
                            class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                            <i class=" btn-icon-prepend" data-feather="edit"></i>
                            Tambah Data
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th style="width: 35%;">Nama Dokumen</th>
                                <th>Link</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataDokumenSekolah as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td>
                                    <?= $value->namaDokumenSekolah; ?>
                                </td>
                                <td>
                                    <a href="<?= $value->linkDokumenSekolah; ?>" target="_blank"> Link Document
                                    </a>
                                <td class="text-center">
                                    <a href="<?=site_url('profilSekolah/'.$value->idDokumenSekolah.'/editDokumen') ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form
                                        action="<?=site_url('profilSekolah/deleteDokumen/'.$value->idDokumenSekolah)?>"
                                        method="post" class="d-inline" id="del-<?= $value->idDokumenSekolah;?>">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-danger btn-icon"
                                            data-confirm="Apakah anda yakin menghapus data ini?">
                                            <i data-feather="trash"></i>
                                        </button>
                                    </form>
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

<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Import Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <form action="<?=site_url("profilSekolah/importDokumen")?>" method="POST" enctype="multipart/form-data"  id="custom-validation">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input class="form-control" type="file" id="formExcel" name="formExcel">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>