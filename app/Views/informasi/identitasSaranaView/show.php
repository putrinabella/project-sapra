<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Identitas Sarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Informasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Identitas Sarana</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Identitas Sarana</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <button type="button" class="btn btn-primary btn-icon-text me-2 mb-2 mb-md-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-printer btn-icon-prepend">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print
        </button>
        <button type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-download-cloud btn-icon-prepend">
                <polyline points="8 17 12 21 16 17"></polyline>
                <line x1="12" y1="12" x2="12" y2="21"></line>
                <path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path>
            </svg>
            Download Report
        </button>
    </div>
</div>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h1> </h1>
                    <div class="secion-header-button">
                        <a href="<?= site_url('identitasSarana/add') ?>" class="btn btn-outline-primary btn-icon-text">
                            <i class="btn-icon-prepend" data-feather="edit"></i>
                            Tambah Data
                        </a>
                    </div>
                    <!-- USING MODAL -->
                    <!-- <a href="#" class="btn icon icon-left btn-primary" data-bs-toggle="modal" data-bs-target="#inlineForm"><i data-feather="edit"></i> Tambah Data</a> -->
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
                                <th style="width: 15%;">ID Sarana</th>
                                <th>Nama Sarana</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataIdentitasSarana as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td class="text-center">
                                    <?= sprintf('%03d', $value->idIdentitasSarana) ?>
                                </td>
                                <td class="text-left"><?=$value->namaSarana?></td>
                                <td class="text-center">
                                    <a href="" class="btn btn-secondary btn-icon"> <i data-feather="info"></i></a>
                                    <a href="<?=site_url('identitasSarana/edit/'.$value->idIdentitasSarana) ?>" class="btn btn-primary btn-icon"> <i data-feather="edit-2"></i></a>
                                    <form action="<?=site_url('identitasSarana/'.$value->idIdentitasSarana)?>" method="post" class="d-inline" onsubmit="return confirm('Yakin hapus data?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button  class="btn btn-danger btn-icon"> 
                                        <i data-feather="trash"></i>
                                    </button>
                                    <!-- <a href="" class="btn btn-danger btn-icon"> <i data-feather="trash"></i></a> -->
                                    <!-- <a href="" class="btn btn-danger btn-icon"> <i data-feather="trash"></i></a> -->
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

<!-- set auto close alert -->
<script>
    setTimeout(function() {
        document.getElementById('alert').style.display = 'none';
    }, 2000);
</script>

<!-- MODAL FOR INPUT DATA -->
<div class="modal fade" id="inlineForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>