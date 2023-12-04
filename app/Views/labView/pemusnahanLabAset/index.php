<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Pemusnahan Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pemusnahan Aset</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <form action="<?= site_url('pemusnahanLabAset') ?>" class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="startDatePicker">
                <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Start Date"
                    readonly>
                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
            </div>
            <div class="input-group date datepicker col py-3 p-0 me-2 mb-2 mb-md-0" id="endDatePicker">
                <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" readonly>
                <span class="input-group-text input-group-addon bg-transparent"><i data-feather="calendar"></i></span>
            </div>
            <div class="col py-3 p-0 mb-2 mb-md-0">
                <button type="submit" class="btn btn-primary btn-icon me-1">
                    <i data-feather="filter"></i>
                </button>
                <a href="<?= site_url('pemusnahanLabAset') ?>" class="btn btn-success btn-icon ">
                    <i data-feather="refresh-ccw"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="dropdown">
            <?php
                if (empty($_GET['startDate']) && empty($_GET['endDate'])) {
                    $exportLink = site_url('pemusnahanLabAset/export');
                    $generatePDFLink = site_url('pemusnahanLabAset/generatePDF');
                } else {
                    $startDate = $_GET['startDate'] ?? '';
                    $endDate = $_GET['endDate'] ?? '';
                    $exportLink = site_url("pemusnahanLabAset/export?startDate=$startDate&endDate=$endDate");
                    $generatePDFLink = site_url("pemusnahanLabAset/generatePDF?startDate=$startDate&endDate=$endDate");
                }
            ?>
            <button class="btn btn-success btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="download"></i>
                Export File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= $exportLink ?>">Download as Excel</a>
                <a class="dropdown-item" target="_blank" href="<?= $generatePDFLink ?>">Download as PDF</a>
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
                <h4 class="text-center py-3">Data Pemusnahan Aset</h4>
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
                            <?php foreach ($dataRincianLabAset as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <?php
                                $originalDate = $value->tanggalPemusnahan;
                                $formattedDate = date('d F Y', strtotime($originalDate));
                                ?>
                                <td data-sort="<?= strtotime($originalDate) ?>"><?php echo $formattedDate; ?></td>
                                <td><?=$value->kodeRincianLabAset?></td>
                                <td><?=$value->namaLab?></td>
                                <td><?=$value->namaKategoriManajemen?></td>
                                <td><?=$value->namaSarana?></td>
                                <td><?=$value->namaSumberDana?></td>
                                <td class="text-center">
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
                                <a href="<?=site_url('pemusnahanLabAset/'.$value->idRincianLabAset) ?>" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                    <a href="<?=site_url('pemusnahanLabAset/'.$value->idRincianLabAset.'/edit') ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                </td>
                                <td class="text-center">
                                    <form action="<?= site_url('pemusnahanLabAset/destruction/' . $value->idRincianLabAset) ?>" method="post" class="d-inline">
                                        
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