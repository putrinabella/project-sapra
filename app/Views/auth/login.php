<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="NobleUI">
    <meta name="keywords"
        content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/core/core.css">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/demo1/style.css">
    <!-- End layout styles -->

    <link rel="shortcut icon" href="<?= base_url(); ?>/assets/images/favicon.png" />
</head>

<body>
    <div class="main-wrapper">
        <div class="page-wrapper full-page">
            <div class="page-content d-flex align-items-center justify-content-center">
                <div class="row w-100 mx-0 auth-page">
                    <div class="col-md-8 col-xl-6 mx-auto">
                        <div class="card">
                            <div class="col-md-12 ps-md-0">
                                <div class="auth-form-wrapper px-4 py-5">
                                    <a href="#" class="noble-ui-logo d-block mb-2">sar<span>pra</span></a>
                                    <h5 class="text-muted fw-normal mb-4">Welcome back! Log in to your account.</h5>
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
                                    <form action="<?= site_url('loginProcess')?>" method="post" id="custom-validation">
                                        <?= csrf_field() ?>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input id="username" class="form-control" name="username" type="text"
                                                placeholder="Masukkan username">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input id="password" class="form-control" name="password" type="password"
                                                placeholder="Masukkan password">
                                        </div>
                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="authCheck">
                                            <label class="form-check-label" for="authCheck">
                                                Remember me
                                            </label>
                                        </div>
                                        <!-- <button type="reset" class="btn btn-danger me-2">Reset</button> -->
                                        <button type="submit" class="btn btn-primary">Login</button>
                                        <div>
                                            <a href="" class="d-block mt-3 text-muted">Not a user? Sign up</a>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- core:js -->
    <script src="<?= base_url(); ?>/assets/vendors/core/core.js"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="<?= base_url(); ?>/assets/vendors/jquery-validation/jquery.validate.min.js"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="<?= base_url(); ?>/assets/vendors/feather-icons/feather.min.js"></script>
    <script src="<?= base_url(); ?>/assets/js/template.js"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="<?= base_url(); ?>/assets/js/form-validation.js"></script>
    <!-- End custom js for this page -->

</body>

</html>