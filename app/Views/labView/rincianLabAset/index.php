<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Rincian Aset &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Laboratorium</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rincian Aset</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Rincian Aset</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="dropdown">
            <button class="btn btn-primary btn-text mdi mdi-qrcode-scan dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" target="_blank" href="<?= site_url('generateLabQRDoc') ?>">Generate All</a>
                <a class="dropdown-item" target="_blank" href="<?= site_url('generateSelectedLabQR') ?>" id="generateSelectedLabQR">Generate Selected</a>
            </div>
        </div>
        <form action="<?= site_url('rincianLabAset/generateAndSetKodeRincianLabAset') ?>" method="post">
            <button type="submit" class="btn btn-primary btn-icon-text mdi mdi-key me-2 mb-2 mb-md-0"
                data-bs-toggle="tooltip" data-bs-placement="bottom" title="Click for generate kode aset"></button>
        </form>
        <a href="<?= site_url('rincianLabAset/trash') ?>" class="btn btn-danger btn-icon-text me-2 mb-2 mb-md-0">
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
                <a class="dropdown-item" href="<?= site_url('rincianLabAset/export') ?>">Download as Excel</a>
                <a class="dropdown-item" target="_blank" href="<?= site_url('rincianLabAset/generatePDF') ?>">Download as
                    PDF</a>
            </div>
        </div>
        <div class="dropdown">
            <button class="btn btn-secondary btn-icon-text dropdown-toggle me-2 mb-2 mb-md-0" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class=" btn-icon-prepend" data-feather="upload"></i>
                Import File
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="<?= site_url('rincianLabAset/createTemplate') ?>">Download Template</a>
                <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#modalImport">Upload Excel</a>
            </div>
        </div>
        <a href="<?= site_url('rincianLabAset/new') ?>" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="edit"></i>
            Tambah Data
        </a>
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
                    <table class="table table-hover" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th></th>
                                <th style="width: 5%;">No.</th>
                                <th style="width: 12%;">Kode Aset</th>
                                <th>Lokasi</th>
                                <th>Kategori Aset</th>
                                <th>Nama Aset</th>
                                <th>Status</th>
                                <th>Keterediaan</th>
                                <th>Sumber Dana</th>
                                <th>Tahun Pengadaan</th>
                                <th>Harga Beli</th>
                                <th>Merek</th>
                                <th>Warna</th>
                                <th>Spesifikasi</th>
                                <th style="width: 20%;">Aksi</th>
                                <th style="width: 20%;">Pemusnahan</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataRincianLabAset as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td><input type="checkbox" class="form-check-input row-select" name="selectedRows[]"
                                        value="<?= $value->idRincianLabAset ?>"></td>
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td class="text-center"><?=$value->kodeRincianLabAset?></td>
                                <td><?=$value->namaLab?></td>
                                <td><?=$value->namaKategoriManajemen?></td>
                                <td><?=$value->namaSarana?></td>
                                <td class="text-center">
                                    <?php if ($value->status == "Rusak") : ?>
                                    <span class="badge bg-warning">
                                        <?= $value->status; ?> 
                                    </span>
                                    <?php elseif ($value->status == "Hilang"): ?>
                                    <span class="badge bg-danger">
                                    <?= $value->status; ?> 
                                    </span>
                                    <?php elseif ($value->status == "Bagus"): ?>
                                    <?= $value->status; ?> 
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($value->sectionAset == "None") : ?>
                                        Tersedia
                                    <?php else : ?>
                                    <span class="badge bg-warning">
                                        <?= $value->sectionAset; ?> 
                                    </span>
                                    <?php endif; ?>
                                </td>
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
                                <td><?=$value->warna?></td>
                                <td
                                    style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?=$value->spesifikasi?>
                                </td>
                                <td class="text-center">
                                    <a href="<?=site_url('rincianLabAset/'.$value->idRincianLabAset) ?>"
                                        class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                    <a href="<?=site_url('rincianLabAset/'.$value->idRincianLabAset.'/edit') ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form action="<?=site_url('rincianLabAset/'.$value->idRincianLabAset)?>" method="post"
                                        class="d-inline" id="del-<?= $value->idRincianLabAset;?>">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-danger btn-icon"
                                            data-confirm="Apakah anda yakin menghapus data ini?"
                                            data-title="Hapus Aset">
                                            <i data-feather="trash"></i>
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form
                                        action="<?= site_url('pemusnahanLabAset/destruction/' . $value->idRincianLabAset) ?>"
                                        method="post" class="d-inline">
                                        <div class="form-group">
                                            <div class="d-flex align-items-center">
                                                <select name="sectionAset" class="form-control me-2 sectionAsetSelect"
                                                    style="width: 130px">
                                                    <option value="None">None</option>
                                                    <option value="Dimusnahkan">Dimusnahkan</option>
                                                </select>
                                                <input class="form-control" type="text" name="namaAkun"
                                                    value=" <?= session('nama'); ?>" hidden>
                                                <input class="form-control" type="text" name="kodeAkun"
                                                    value=" <?= session('role'); ?>" hidden>
                                                <button type="submit"
                                                    class="btn btn-success btn-icon ml-2 submitButton">
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

<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Import Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <form action="<?=site_url(" rincianLabAset/import")?>" method="POST" enctype="multipart/form-data"
                id="custom-validation">
                <div class="modal-body">

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

<script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
<script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sectionAsetSelects = document.querySelectorAll('.sectionAsetSelect');
        const submitButtons = document.querySelectorAll('.submitButton');

        sectionAsetSelects.forEach((select, index) => {
            select.addEventListener('change', function () {
                if (select.value === 'None') {
                    submitButtons[index].disabled = true;
                } else {
                    submitButtons[index].disabled = false;
                }
            });

            if (select.value === 'None') {
                submitButtons[index].disabled = true;
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const generateSelectedLabQR = document.getElementById('generateSelectedLabQR');
        generateSelectedLabQR.addEventListener('click', function (e) {
            e.preventDefault();

            const selectedRows = getSelectedRowIds();

            if (selectedRows.length > 0) {
                const selectedRowsQueryParam = selectedRows.join(',');
                window.open('<?= site_url('generateSelectedLabQR') ?>/' + selectedRowsQueryParam, '_blank');

            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak ada data',
                    text: 'Silahkan pilih minimal satu data!',
                    confirmButtonText: 'OK',
                });
            }
        });

        function getSelectedRowIds() {
            const checkboxes = document.querySelectorAll('input[name="selectedRows[]"]:checked');
            const selectedRowIds = [];
            checkboxes.forEach(function (checkbox) {
                selectedRowIds.push(checkbox.value);
            });
            return selectedRowIds;
        }
    });
</script>

<?= $this->endSection(); ?>