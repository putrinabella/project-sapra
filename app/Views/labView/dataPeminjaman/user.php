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

<div class="col-12 col-xl-12 grid-margin stretch-card">
    <div class="card overflow-hidden">
        <div class="card-body">
            <div>
                <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible show fade" role="alert" id="alert">
                    <div class="alert-body">
                        <b>Success!</b>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                </div>
                <br>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible show fade" role="alert" id="alert">
                    <div class="alert-body">
                        <b>Error!</b>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                </div>
                <br>
                <?php endif; ?>
            </div>
            <h3 class="text-center py-3">Data Peminjaman</h3>
            <?php if (!empty($tableHeading)) : ?>
            <p class="text-center">
                <?= $tableHeading ?>
            </p>
            <?php endif; ?>
            <br>
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" style="width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 5%;">No.</th>
                            <th>Tanggal</th>
                            <th>Nama Peminjam</th>
                            <th>Karwayan/Siswa</th>
                            <th>Lokasi</th>
                            <th>Jumlah Aset Dipinjam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody class="py-2">
                        <?php foreach ($dataDataPeminjaman as $key => $value) : ?>

                        <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                            <td class="text-center">
                                <?= $key + 1 ?>
                            </td>
                            <td class="text-left">
                                <?= date('d F Y', strtotime($value->tanggal)) ?>
                            </td>
                            <td class="text-left">
                                <?= $value->namaPeminjam ?>
                            </td>
                            <td class="text-left">
                                <?= $value->asalPeminjam ?>
                            </td>
                            <td class="text-center">
                                <?= $value->namaLab ?>
                            </td>
                            <td class="text-center">
                                <?= $value->jumlahPeminjaman ?>
                            </td>
                            <td class="text-center">
                                <?php if ($value->loanStatus == "Peminjaman") : ?>
                                <span class="badge bg-warning">Sedang Dipinjam</span>
                                <?php else : ?>
                                <span class="badge bg-success">Sudah Dikembalikan</span>
                                <?php endif; ?>
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