<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Manajemen Sarana, Prasarana, IT dan Laboratorium SMK TELKOM Banjarbaru">
    <meta name="author" content="Putri Nabella">
    <meta name="keywords" content="Sarana, Prasarana, IT, Lab">
    <?= $this->renderSection("title"); ?>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <!-- core:css -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/core/core.css">
    <!-- endinject -->

    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/prismjs/themes/prism.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/select2/select2.min.css">
    <!-- End plugin css for this page -->

    <!-- inject:css -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/fonts/feather-font/css/iconfont.css">
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <!-- endinject -->

    <!-- Layout styles -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/light/style.css">
    <!-- <link rel="stylesheet" href="<?= base_url(); ?>/assets/scss/light/style.scss"> -->
    <!-- End layout styles -->

    <!-- Custom css for this page -->
    <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/light/custom.css">
    <!-- End custom css for this page -->

    <link rel="shortcut icon" href="<?= base_url(); ?>/assets/images/favicon.png" />
</head>

<body>
    <div class="main-wrapper">

        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="<?= site_url() ?>" class="sidebar-brand">
                    sar<span>pra</span>
                </a>
                <div class="sidebar-toggler not-active">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="sidebar-body">
                <?= $this->include('template/sidebar') ?>
            </div>
        </nav>
        <nav class="settings-sidebar">
      <div class="sidebar-body">
        <a href="#" class="settings-sidebar-toggler">
          <i data-feather="settings"></i>
        </a>
        <h6 class="text-muted mb-2">Sidebar:</h6>
        <div class="mb-3 pb-3 border-bottom">
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight"
              value="sidebar-light" checked>
            <label class="form-check-label" for="sidebarLight">
              Light
            </label>
          </div>
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark"
              value="sidebar-dark">
            <label class="form-check-label" for="sidebarDark">
              Dark
            </label>
          </div>
        </div>
        <div class="theme-wrapper">
          <h6 class="text-muted mb-2">Light Theme:</h6>
          <a class="theme-item active" href="../demo1/dashboard.html">
            <img src="../assets/images/screenshots/light.jpg" alt="light theme">
          </a>
          <h6 class="text-muted mb-2">Dark Theme:</h6>
          <a class="theme-item" href="../demo2/dashboard.html">
            <img src="../assets/images/screenshots/dark.jpg" alt="light theme">
          </a>
        </div>
      </div>
    </nav>
        <div class="page-wrapper">

            <!-- partial:partials/_navbar.html -->
            <nav class="navbar">
                <a href="#" class="sidebar-toggler">
                    <i data-feather="menu"></i>
                </a>
                <div class="navbar-content">
                    <img class="" src="<?= base_url(); ?>/assets/images/header-pic.png" alt="Logo SMK TELKOM BJB"
                        style="padding-top: 10px; padding-bottom: 10px;">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <img class="wd-30 ht-30 rounded-circle mr-2 pr-2"
                                        src="<?= base_url(); ?>/assets/images/user.png" alt="profile">
                                </div>
                            </a>
                            <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                                <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                                    <div class="text-center">
                                        <div class="tx-16 fw-bolder"> <?=userLogin()->nama?> </div>
                                        <div class="tx-12 text-muted"> <?=userLogin()->role?> </div>
                                    </div>
                                </div>
                                <ul class="list-unstyled p-1">
                                    <li class="dropdown-item py-2">
                                        <a href="<?= site_url('logout') ?>" class="text-body ms-0">
                                            <i class="me-2 icon-md" data-feather="log-out"></i>
                                            <span>Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- partial -->

            <div class="page-content">
                <?= $this->renderSection("content"); ?>
            </div>

            <!-- partial:partials/_footer.html -->
            <footer
                class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
                <p class="text-muted mb-1 mb-md-0">Copyright © 2023 <a
                        href="https://github.com/putrinabella/project-sapra" target="_blank">StellarCoder</a>.</p>
                <!-- <p class="text-muted">Handcrafted With <i class="mb-1 text-primary ms-1 icon-sm"
                        data-feather="heart"></i></p> -->
            </footer>
            <!-- partial -->

        </div>
    </div>


    <!-- core:js -->
    <script src="<?= base_url(); ?>/assets/vendors/core/core.js"></script>
    <!-- endinject -->

    <!-- Plugin js for this page -->
    <script src="<?= base_url(); ?>/assets/vendors/jquery-validation/jquery.validate.min.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/chartjs/Chart.min.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/jquery.flot/jquery.flot.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/jquery.flot/jquery.flot.resize.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/prismjs/prism.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/clipboard/clipboard.min.js"></script>
    <script src="<?= base_url(); ?>/assets/vendors/select2/select2.min.js"></script>
    <!-- End plugin js for this page -->

    <!-- inject:js -->
    <script src="<?= base_url(); ?>/assets/vendors/feather-icons/feather.min.js"></script>
    <script src="<?= base_url(); ?>/assets/js/template.js"></script>
    <!-- endinject -->

    <!-- Custom js for this page -->
    <script src="<?= base_url(); ?>/assets/js/form-validation.js"></script>
    <script src="<?= base_url(); ?>/assets/js/dashboard-light.js"></script>
    <script src="<?= base_url(); ?>/assets/js/datepicker.js"></script>
    <script src="<?= base_url(); ?>/assets/js/hide-alert.js"></script>
    <script src="<?= base_url(); ?>/assets/js/data-table.js"></script>
    <script src="<?= base_url(); ?>/assets/js/sweet-alert.js"></script>
    <script src="<?= base_url(); ?>/assets/js/select2.js"></script>
    <!-- End custom js for this page -->

</body>

</html>