<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Data Pemusnahan Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Perangkat IT</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Pemusnahan Aset</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Data Pemusnahan Aset</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="dropdown">
            <button class="btn btn-success btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Export File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= site_url('pemusnahanItAset/exportDestroyFile') ?>">Download as Excel</a>
                <a class="dropdown-item" href="<?= site_url('pemusnahanItAset/dataDestroyaGeneratePDF') ?>">Download as PDF</a>
            </div>
        </div>
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
                    <table class="table table-hover" id="dataTable">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th>Tanggal Pemusnahan</th>
                                <th style="width: 12%;">Kode Aset</th>
                                <th>Lokasi</th>
                                <th>Kategori Aset</th>
                                <th>Nama Aset</th>
                                <th>Sumber Dana</th>
                                <th>Tahun Pengadaan</th>
                                <th>Harga Beli</th>
                                <th>Merek</th>
                                <th>Nama Akun</th>
                                <th>Kode Akun</th>
                                <th>Aksi</th>
                                <th>Pemusnahan</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataRincianAset as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td><?= date('d F Y', strtotime($value->tanggalPemusnahan)) ?></td>
                                <td><?=$value->kodeRincianAset?></td>
                                <td><?=$value->namaPrasarana?></td>
                                <td><?=$value->namaKategoriManajemen?></td>
                                <td><?=$value->namaSarana?></td>
                                <td><?=$value->namaSumberDana?></td>
                                <td>
                                    <?php 
                                        if($value->tahunPengadaan == 0 || 0000) {
                                            echo "Tidak diketahui"; 
                                        } else {
                                            echo $value->tahunPengadaan;
                                        };
                                    ?>
                                </td>
                                <td><?=number_format($value->hargaBeli, 0, ',', '.')?></td>
                                <td><?=$value->merk?></td>
                                <td><?= $value->namaAkun; ?> </td>
                                <td><?= $value->kodeAkun; ?> </td>
                                <td class="text-center">
                                    <a href="<?=site_url('dataItSarana/show/'.$value->idRincianAset) ?>"
                                        class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                    <a href="<?=site_url('dataItSarana/editPemusnahanIt/'.$value->idRincianAset) ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                </td>
                                <td class="text-center">
                                    <form action="<?= site_url('pemusnahanItAset/delete/' . $value->idRincianAset) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <div class="form-group">
                                            <div class="d-flex align-items-center">
                                                <select name="sectionAset" class="form-control me-2 sectionAsetSelect" style="width: 130px">
                                                    <option value="Dimusnahkan">Dimusnahkan</option>
                                                    <option value="None">None</option>
                                                </select>
                                                <input class="form-control" type="text" name="namaAkun" value=" <?= session('nama'); ?>" hidden>
                                                <input class="form-control" type="text" name="kodeAkun" value=" <?= session('role'); ?>" hidden>
                                                <button type="submit" class="btn btn-success btn-icon ml-2 submitButton">
                                                    <i data-feather="check"></i>
                                                </button>
                                            </div>
                                        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sectionAsetSelects = document.querySelectorAll('.sectionAsetSelect');
        const submitButtons = document.querySelectorAll('.submitButton');

        sectionAsetSelects.forEach((select, index) => {
            select.addEventListener('change', function () {
                if (select.value === 'Dimusnahkan') {
                    submitButtons[index].disabled = true;
                } else {
                    submitButtons[index].disabled = false;
                }
            });

            if (select.value === 'Dimusnahkan') {
                submitButtons[index].disabled = true;
            }
        });
    });
</script>
<?= $this->endSection(); ?>