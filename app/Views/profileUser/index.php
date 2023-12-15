<?= $this->extend('template/webshell'); ?>

<?= $this->section("title"); ?>
<title>Profile User &verbar; SARPRA </title>
<?= $this->endSection(); ?>

<?= $this->section("content"); ?>
<nav class="page-breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Profil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Profile User</li>
    </ol>
</nav>
<div class="row">
    <div class="col-12 col-xl-12 grid-margin stretch-card">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div>
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
                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-center align-items-center">
                        <?php 
                            $userPicture = session()->get('mode') === 'dark' ? 'user-dark.png' : 'user-light.png';
                        ?>
                        <img class="card-img-top rounded-circle mr-2 pr-2"
                            src="<?= base_url(); ?>/assets/images/<?= $userPicture ?>" alt="profile"
                            style="width: 100px; height: 100px;">

                    </div>
                    <h2 class="text-uppercase text-center mt-4 mb-2">
                        <?= $dataProfileUser->nama; ?>
                    </h2>
                </div>
                <div class="example">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">IDENTITAS PENGGUNA</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab"
                                aria-controls="profile" aria-selected="false">SETTINGS</a>
                        </li>
                    </ul>
                    <div class="tab-content border border-top-0 p-3" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="my-table">
                                        <tbody>
                                            <tr>
                                                <td style="width: 25%;">NIS/NIP</td>
                                                <td style="width: 2%;">:</td>
                                                <td>
                                                    <?= $dataProfileUser->nis; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%;">Kelas/Karyawan</td>
                                                <td style="width: 2%;">:</td>
                                                <td>
                                                    <?= $dataProfileUser->namaKelas; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <form action="<?= site_url('manajemenUser/updateUser/'.$dataProfileUser->idUser)?>"
                                method="post" autocomplete="off" id="custom-validation">
                                <div class="row mb-3">
                                    <label for="oldPassword" class="col-sm-3 col-form-label">Password Lama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="oldPassword" name="oldPassword"
                                            placeholder="Masukkan password lama anda">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-3 col-form-label">Password Baru</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="password" name="password"
                                            placeholder="Masukkan password baru">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="konfirmasiPassword" class="col-sm-3 col-form-label">Konfirmasi
                                        Password</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="konfirmasiPassword"
                                            name="konfirmasiPassword" placeholder="Masukkan konfirmasi password baru">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-sm-12 text-end">
                                        <a href="<?= site_url('profileUser') ?>"
                                            class="btn btn-secondary me-2">Cancel</a>
                                        <button type="reset" class="btn btn-danger me-2">Reset</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>