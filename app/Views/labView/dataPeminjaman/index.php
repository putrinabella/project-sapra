<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Peminjaman &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Peminjaman</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Data Peminjaman</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="<?= site_url('dataPeminjaman/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="trash"></i>
            Recycle Bin
        </a>
        <div class="dropdown">
            <button class="btn btn-success btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Export File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= site_url('dataPeminjaman/export') ?>">Download as Excel</a>
                <a class="dropdown-item" href="<?= site_url('dataPeminjaman/generatePDF') ?>">Download as PDF</a>
            </div>
        </div>
        <a href="<?= site_url('manajemenPeminjaman') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="edit"></i>
            Ajukan Peminjaman
        </a>
    </div>
</div>


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
                <table class="table table-hover" id="dataTable">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 5%;">No.</th>
                            <th>Tanggal</th>
                            <th>Nama Peminjam</th>
                            <th>Asal Peminjam</th>
                            <th>Aset</th>
                            <th>Lokasi</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Pengembalian</th>
                            <th style="width: 20%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="py-2">
                        <?php foreach ($dataDataPeminjaman as $key => $value) : ?>
                        <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                            <td class="text-center">
                                <?=$key + 1?>
                            </td>
                            <td class="text-center"><?=$value->tanggal?></td>
                            <td class="text-center"><?=$value->namaPeminjam?></td>
                            <td class="text-center"><?=$value->asalPeminjam?></td>
                            <td class="text-center"><?=$value->namaSarana?></td>
                            <td class="text-center"><?=$value->namaLab?></td>
                            <td class="text-center"><?=$value->jumlah?></td>
                            <td class="text-center">
                                <?php if ($value->status == "Peminjaman"): ?>
                                    <span class="label label-warning"> <?=$value->status?></span>
                                <?php else: ?>
                                    <span class="label label-success"> <?=$value->status?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center"><?= $value->tanggalPengembalian !== '' ? $value->tanggalPengembalian : 'Belum dikembalikan' ?></td>
                            <td class="text-center">
                                <a href="<?=site_url('dataPeminjaman/'.$value->idManajemenPeminjaman.'/edit') ?>"
                                    class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                <form action="<?=site_url('dataPeminjaman/'.$value->idManajemenPeminjaman)?>"
                                    method="post" class="d-inline" id="del-<?= $value->idManajemenPeminjaman;?>">
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




<?= $this->endSection(); ?>