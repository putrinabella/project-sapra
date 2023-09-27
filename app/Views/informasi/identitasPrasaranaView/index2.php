<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Identitas Prasarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Informasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Identitas Prasarana</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Identitas Prasarana</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">

        <a href="<?= site_url() ?>" class="btn btn-primary btn-icon me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="printer"></i>
        </a>
        <a href="<?= site_url() ?>" class="btn btn-primary btn-icon me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="download-cloud"></i>
        </a>
        <a href="<?= site_url() ?>" class="btn btn-success btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="file-plus"></i>
            Import Data
        </a>
        <a href="<?= site_url('identitasPrasarana/new') ?>" class="btn btn-primary btn-icon-text me-2 mb-2 mb-md-0">
            <i class=" btn-icon-prepend" data-feather="edit"></i>
            Tambah Data
        </a>
        <a href="<?= site_url('identitasPrasarana/trash') ?>" class="btn btn-danger btn-icon-text mb-2 mb-md-0">
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
                    <div class="d-flex">
                        <div class="me-2 mb-2 mb-md-0">
                            <select class="form-select btn-outline-primary " id="showEntries" name="showEntries">
                                <option value="" hidden> Show entries</option>
                                <option value="10">Show 10 entries</option>
                                <option value="25">Show 25 entries</option>
                                <option value="50">Show 50 entries</option>
                                <option value="100">Show 100 entries</option>
                            </select>
                        </div>
                        <a href="<?= site_url('identitasPrasarana/new') ?>"
                            class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="edit"></i>
                            Search
                        </a>
                    </div>

                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                        <a href="<?= site_url('identitasPrasarana/trash') ?>"
                            class="btn btn-outline-success btn-icon-text me-2 mb-2 mb-md-0">
                            <i class=" btn-icon-prepend" data-feather="file-plus"></i>
                            Import data
                        </a>
                        <a href="<?= site_url('identitasPrasarana/new') ?>"
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
                    <table class="table table-hover" id="dataTable3">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th style="width: 12%;">ID</th>
                                <th>Identitas Prasarana</th>
                                <th>Lokasi Gedung</th>
                                <th>Lokasi Lantai</th>
                                <th>Luas</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php 
                         $page = isset($_GET['page']) ? $_GET['page'] : 1;
                         $no = 1 + (10 * ($page - 1));
                        foreach ($dataIdentitasPrasarana as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$no++?>
                                </td>
                                <td class="text-center">
                                    <?= sprintf('%03d', $value->idIdentitasPrasarana) ?>
                                </td>
                                <td class="text-left"><?=$value->namaPrasarana?></td>
                                <td class="text-center"><?=$value->namaGedung?></td>
                                <td class="text-center"><?=$value->namaLantai?></td>
                                <td class="text-center"><?=$value->luas?> m&sup2;</td>
                                <td class="text-center">
                                    <a href="" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                    <a href="<?=site_url('identitasPrasarana/'.$value->idIdentitasPrasarana.'/edit') ?>"
                                        class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form action="<?=site_url('identitasPrasarana/'.$value->idIdentitasPrasarana)?>"
                                        method="post" class="d-inline" id="del-<?= $value->idIdentitasPrasarana;?>">
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
                    <?= $pager->links('default', 'pagination'); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>