<!DOCTYPE html>
<!-- <html lang="en" class="light-theme"> -->
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
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/dropify/dist/dropify.min.css">
  <!-- End plugin css for this page -->

  <!-- inject:css -->
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/fonts/feather-font/css/iconfont.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <!-- endinject -->

  <!-- Layout styles -->
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/light/style.css" id="light-mode" class="theme">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/dark/style.css" id="dark-mode" class="theme" disabled>


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
              <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <div class="d-flex align-items-center">
                  <img class="wd-30 ht-30 rounded-circle mr-2 pr-2" src="<?= base_url(); ?>/assets/images/user.png"
                    alt="profile">
                </div>
              </a>
              <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                  <div class="text-center">
                  <div class="tx-16 fw-bolder"> <?= session('username'); ?> </div>
                    <div class="tx-12 text-muted"> <?= session('role'); ?> </div>
                  </div>
                </div>
                <ul class="list-unstyled p-1">
                  <h6 class="text-center text-muted py-2">
                    Theme:
                  </h6>
                  <li class="dropdown-item py-2">
                    <a href="javascript:void(0);" onclick="enableLightMode()">
                      <h6 class="text-muted mb-2 theme-item">Light</h6>
                    </a>
                  </li>
                  <li class="dropdown-item py-2">
                    <a href="javascript:void(0);" onclick="enableDarkMode()">
                      <h6 class="text-muted mb-2 theme-item">Dark</h6>
                    </a>
                  </li>
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
        <p class="text-muted mb-1 mb-md-0">Copyright © 2023 <a href="https://github.com/putrinabella/project-sapra"
            target="_blank">StellarCoder</a>.</p>
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
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
  <script src="<?= base_url(); ?>/assets/vendors/jquery/jquery-3.7.1.min.js"></script>
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
  <script src="<?= base_url(); ?>/assets/vendors/dropify/dist/dropify.min.js"></script>
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
  <script src="<?= base_url(); ?>/assets/js/dropify.js"></script>
  <script src="<?= base_url(); ?>/assets/js/custom.js"></script>
  <!-- End custom js for this page -->

  <!-- BASED ON DEVICE BROWSER MODE -->
  <!-- <script>
      // Check if the browser supports the 'prefers-color-scheme' media feature
      if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
          // Dark mode preference detected, activate dark mode
          document.getElementById('light-mode').disabled = true;
      } else {
          // Light mode preference detected, activate light mode
          document.getElementById('dark-mode').disabled = true;
      }
  </script> -->


  <!-- <script>
    function setThemePreference(theme) {
      localStorage.setItem('theme', theme);
    }
    // Function to enable light mode
    function enableLightMode() {
      document.getElementById('light-mode').disabled = false;
      document.getElementById('dark-mode').disabled = true;
      setThemePreference('light');
    }

    function enableDarkMode() {
      document.getElementById('light-mode').disabled = true;
      document.getElementById('dark-mode').disabled = false;
      setThemePreference('dark');
    }

    function applySavedTheme() {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        enableDarkMode();
      }
    }

    applySavedTheme();
    const html = document.querySelector('html');

    document.addEventListener('DOMContentLoaded', function () {
      if (html.classList.contains('dark-theme')) {
        enableDarkMode();
      }
    });
  </script> -->


  <script>
    function setThemePreference(theme) {
      localStorage.setItem('theme', theme);
    }

    // Function to enable light mode
    function enableLightMode() {
      document.getElementById('light-mode').disabled = false;
      document.getElementById('dark-mode').disabled = true;
      setThemePreference('light');
    }

    function enableDarkMode() {
      document.getElementById('light-mode').disabled = true;
      document.getElementById('dark-mode').disabled = false;
      setThemePreference('dark');
    }

    function applySavedTheme() {
      const savedTheme = localStorage.getItem('theme');
      if (savedTheme === 'dark') {
        enableDarkMode();
      } else {
        enableLightMode(); // Default to light mode if the preference is not found.
      }
    }

    applySavedTheme();
  </script>
</body>

</html>