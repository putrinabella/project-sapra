<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Recycle Bin Layanan Aset Sarana &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Sarana</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('saranaLayananAset')?>">Layanan Aset</a></li>
        <li class="breadcrumb-item active" aria-current="page">Recycle Bin</li>
    </ol>
</nav>


<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
                    <div>
                        <a href="<?= site_url('saranaLayananAset')?>"
                            class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="arrow-left"></i>
                            Back
                        </a>
                    </div>
                    <div class="d-flex align-items-center flex-wrap text-nowrap">
                        <a href="<?= site_url('saranaLayananAset/restore')?>"
                            class="btn btn-primary btn-icon-text  me-2 mb-2 mb-md-0">
                            <i class="btn-icon-prepend" data-feather="cloud-drizzle"></i>
                            Restore All
                        </a>
                        <form action="<?= site_url('saranaLayananAset/deletePermanent/') ?>" method="POST"
                            class="d-inline me-2 mb-2 mb-md-0">
                            
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="btn btn-danger btn-icon-text" type="submit">
                                <i class="btn-icon-prepend" data-feather="alert-triangle"></i> Delete All Permanent
                            </button>
                        </form>
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
                    <table class="table table-hover" id="dataTable" style="width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">No.</th>
                                <th>Tanggal</th>
                                <th>Nama Aset</th>
                                <th>Lokasi</th>
                                <th>Status Layanan</th>
                                <th>Kategori Manajemen</th>
                                <th>Sumber Dana</th>
                                <th>Biaya</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="py-2">
                            <?php foreach ($dataSaranaLayananAset as $key => $value) : ?>
                            <tr style="padding-top: 10px; padding-bottom: 10px; vertical-align: middle;">
                                <td class="text-center">
                                    <?=$key + 1?>
                                </td>
                                <td>
                                    <?= date('d F Y', strtotime($value->tanggal)) ?>
                                </td>
                                <td><?=$value->namaSarana?></td>
                                <td><?=$value->namaPrasarana?></td>
                                <td><?=$value->namaStatusLayanan?></td>
                                <td><?=$value->namaKategoriManajemen?></td>
                                <td><?=$value->namaSumberDana?></td>
                                <td><?=number_format($value->biaya, 0, ',', '.')?></td>
                                <td class="text-center">
                                    <a href="<?=site_url('saranaLayananAset/restore/'.$value->idSaranaLayananAset) ?>"
                                        class="btn btn-primary"> Restore</a>
                                    <form
                                        action="<?= site_url('saranaLayananAset/deletePermanent/'.$value->idSaranaLayananAset) ?>"
                                        method="POST" class="d-inline">
                                        
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button class="btn btn-danger" type="submit"> Delete Permanent </button>
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