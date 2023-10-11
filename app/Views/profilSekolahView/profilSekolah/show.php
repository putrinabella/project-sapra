<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Rincian Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Profil Sekolah</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">  
        <a href="<?= site_url('profilSekolah/print/'.$dataProfilSekolah->idProfilSekolah)?>"
            class="btn btn-outline-success btn-icon-text me-2 mb-2 mb-md-0" target="_blank">
            <i class="btn-icon-prepend" data-feather="printer"></i>
            Print
        </a>
        <?php if ($rowCount == 1): ?>
                <a href="<?= site_url('profilSekolah/'.$dataProfilSekolah->idProfilSekolah.'/edit') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                    <i class="btn-icon-prepend" data-feather="edit-2"></i>
                    Edit
                </a>
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

                    <div class="table-responsive">
                    <h5>Identitas Sekolah</h5>
                    <br>
                    <table class="my-table">
                            <tr>
                                <td style="width: 25%;">NPSN</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->npsn; ?> </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->status; ?> </td>
                            </tr>
                            <tr>
                                <td>Bentuk Pendidikan</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->bentukPendidikan; ?> </td>
                            </tr>
                            <tr>
                                <td>Status Kepemilikan</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->statusKepemilikan; ?> </td>
                            </tr>
                            <tr>
                                <td>SK Pendirian</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->skPendirian; ?> </td>
                            </tr>
                            <tr>
                                <td>Tanggal SK Pendirian</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->tanggalSkPendirian; ?> </td>
                            </tr>
                            <tr>
                                <td>SK Izin Operasional</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->skIzinOperasional; ?> </td>
                            </tr>
                            <tr>
                                <td>Tanggal SK Izin Operasinal</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->tanggalSkIzinOperasional; ?> </td>
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
                                <td><?= $dataProfilSekolah->statusBos; ?> </td>
                            </tr>
                            <tr>
                                <td>Waktu Penyelenggaraan</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->waktuPenyelenggaraan; ?> </td>
                            </tr>
                            <tr>
                                <td>Spesifikasi ISO</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->sertifikasiIso; ?> </td>
                            </tr>
                            <tr>
                                <td>Sumber Listrik</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->sumberListrik; ?> </td>
                            </tr>
                            <tr>
                                <td>Kecepatan Internet</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->kecepatanInternet; ?> </td>
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
                                <td><?= $dataProfilSekolah->siswaKebutuhanKhusus; ?> </td>
                            </tr>
                            <tr>
                                <td>Nama Bank</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->namaBank; ?> </td>
                            </tr>
                            <tr>
                                <td>Cabang KCP</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->cabangKcp; ?> </td>
                            </tr>
                            <tr>
                                <td>Atas Nama Rekening</td>
                                <td style="width: 2%;">:</td>
                                <td><?= $dataProfilSekolah->atasNamaRekening; ?> </td>
                            </tr>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>