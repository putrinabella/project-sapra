<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Identitas Gedung &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Informasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Identitas Gedung</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Identitas Gedung</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">

        <a href="<?= site_url() ?>" class="btn btn-primary btn-icon me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="printer"></i>
        </a>
        <a href="<?= site_url() ?>" class="btn btn-primary btn-icon me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="download-cloud"></i>
        </a>
        <a href="<?= site_url('identitasGedung/trash') ?>" class="btn btn-danger btn-icon-text mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="trash"></i>
            Recycle Bin
        </a>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <!-- <h4 class="mb-3 mb-md-0">Identitas Gedung</h4> -->
                        <a href="<?= site_url('identitasGedung/new') ?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="edit"></i>
                            Show
                        </a>
                        <a href="<?= site_url('identitasGedung/new') ?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="edit"></i>
                            Search
                        </a>
                    </div>
                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                        <a href="<?= site_url() ?>"
                            class="btn btn-outline-success btn-icon-text me-2 mb-2 mb-md-0">
                            <i class=" btn-icon-prepend" data-feather="file-plus"></i>
                            <!-- Ganti jadi  -->
                            Import data
                        </a>
                        <a href="<?= site_url('identitasGedung/new') ?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="edit"></i>
                            Tambah Data
                        </a>
                    </div>
                </div>

                <br>
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
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 10%;">No.</th>
                                <th style="width: 15%;">ID</th>
                                <th>Nama Identitas Gedung</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataIdentitasGedung as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td class="text-center">
                                    <?= sprintf('%02d', $value->idIdentitasGedung) ?>
                                </td>
                                <td class="text-left"><?=$value->namaGedung?></td>
                                <td class="text-center">
                                    <a href="" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                    <a href="<?=site_url('identitasGedung/edit/'.$value->idIdentitasGedung) ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form action="<?=site_url('identitasGedung/delete/'.$value->idIdentitasGedung)?>"
                                        method="post" class="d-inline" onsubmit="return confirm('Yakin hapus data?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-danger btn-icon">
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


<?= $this->endSection(); ?>