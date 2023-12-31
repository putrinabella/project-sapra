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
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/mdi/css/materialdesignicons.min.css">
  <!-- End plugin css for this page -->

  <!-- inject:css -->
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/fonts/feather-font/css/iconfont.css">
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <!-- endinject -->

  <!-- Layout styles -->

  <?php
    $cssFile = session()->get('mode') === 'dark' ? 'dark' : 'light';
  ?>
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/<?= $cssFile ?>/style.css">

  <!-- End layout styles -->

  <!-- Custom css for this page -->
  <link rel="stylesheet" href="<?= base_url(); ?>/assets/css/custom.css">
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
          <?php
          $headerPicture = session()->get('mode') === 'dark' ? 'header-dark.png' : 'header-light.png';
          $userPicture = session()->get('mode') === 'dark' ? 'user-dark.png' : 'user-light.png';
        ?>
          <img class="" src="<?= base_url(); ?>/assets/images/<?= $headerPicture ?>" alt="Logo SMK TELKOM BJB"
            style="padding-top: 10px; padding-bottom: 10px;">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <div class="d-flex align-items-center">
                  <img class="wd-30 ht-30 rounded-circle mr-2 pr-2"
                    src="<?= base_url(); ?>/assets/images/<?= $userPicture ?>" alt="profile">
                </div>
              </a>
              <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                  <div class="text-center">
                    <div class="tx-16 fw-bolder">
                      <?= session('nama'); ?>
                    </div>
                    <div class="tx-12 text-muted">
                      <?= session('role'); ?>
                    </div>
                  </div>
                </div>
                <ul class="list-unstyled p-1">
                  <li class="dropdown-item py-2">
                    <div class="form-check form-switch ms-0">
                      <input class="form-check-input" type="checkbox" id="modeSwitch" <?=(session()->get('mode') ===
                      'dark') ? 'checked' : '' ?>>
                      <label class="form-check-label" for="modeSwitch" id="modeLabel">
                        <?= (session()->get('mode') === 'dark') ? 'Dark Mode' : 'Light Mode' ?>
                      </label>
                    </div>
                  </li>
                  <!-- <li class="dropdown-item py-2">
                    <a href="#" class="text-body ms-0" data-bs-toggle="modal" data-bs-target="#confirmPasswordModal">
                      <i class="me-2 icon-md" data-feather="edit"></i>
                      <span>Edit Profile</span>
                    </a>
                  </li> -->
                  <?php if (session()->get('role') == 'User') { ?>
                  <li class="dropdown-item py-2">
                    <a href="<?= site_url('profileUser')?>" class="text-body ms-0">
                      <i class="me-2 icon-md" data-feather="edit"></i>
                      <span>Edit Profile</span>
                    </a>
                  </li>
                  <?php } ?>
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
        <p class="text-muted mb-1 mb-md-0">Copyright © 2023 <a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"
            target="_blank">Meraki</a>.</p>
        <!-- <p class="text-muted">Handcrafted With <i class="mb-1 text-primary ms-1 icon-sm" data-feather="heart"></i></p> -->
      </footer>
      <!-- partial -->

    </div>

    <!-- Modal for Confirming Old Password -->
    <div class="modal fade" id="confirmPasswordModal" tabindex="-1" aria-labelledby="confirmPasswordModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmPasswordModalLabel">Confirm your password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="confirmPasswordForm">
              <div class="mb-3">
                <label for="oldPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="oldPassword" name="oldPassword" required>
              </div>
              <button type="button" class="btn btn-primary" onclick="confirmOldPassword()">Confirm</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal for Editing Profile -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editProfileForm">
              <div class="mb-3">
                <label for="newName" class="form-label">New Name</label>
                <input type="text" class="form-control" id="newName" name="newName" required>
              </div>
              <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
              </div>
              <button type="button" class="btn btn-primary" onclick="saveChanges()">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>


  <!-- core:js -->
  <script src="<?= base_url(); ?>/assets/vendors/core/core.js"></script>
  <!-- endinject -->

  <!-- Plugin js for this page -->
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
  <script src="<?= base_url(); ?>/assets/vendors/apexcharts/apexcharts.min.js"></script>
  <!-- End plugin js for this page -->

  <!-- inject:js -->
  <script src="<?= base_url(); ?>/assets/vendors/feather-icons/feather.min.js"></script>
  <script src="<?= base_url(); ?>/assets/js/template.js"></script>
  <!-- endinject -->

  <!-- Custom js for this page -->
  <?php
    $jsFile = session()->get('mode') === 'dark' ? 'dashboard-dark.js' : 'dashboard-light.js';
  ?>
  <script src="<?= base_url(); ?>/assets/js/<?= $jsFile ?>"></script>
  <script src="<?= base_url(); ?>/assets/js/form-validation.js"></script>
  <script src="<?= base_url(); ?>/assets/js/datepicker.js"></script>
  <script src="<?= base_url(); ?>/assets/js/hide-alert.js"></script>
  <script src="<?= base_url(); ?>/assets/js/data-table.js"></script>
  <script src="<?= base_url(); ?>/assets/js/sweet-alert.js"></script>
  <script src="<?= base_url(); ?>/assets/js/select2.js"></script>
  <script src="<?= base_url(); ?>/assets/js/custom.js"></script>
  <!-- End custom js for this page -->

  <script>
    $(document).ready(function () {
      updateSessionMode();

      $('#modeSwitch').change(function () {
        updateSessionMode();
      });

      function updateSessionMode() {
        var isChecked = $('#modeSwitch').is(':checked');
        var mode = isChecked ? 'dark' : 'light';

        $.ajax({
          url: "<?= site_url('updateSessionMode') ?>",
          type: "POST",
          data: { mode: mode },
          success: function (data) {
          }
        });
      }
    });

    document.addEventListener('DOMContentLoaded', function () {
      var modeSwitch = document.getElementById('modeSwitch');
      var modeLabel = document.getElementById('modeLabel');

      modeSwitch.addEventListener('change', function () {
        location.reload();
        modeLabel.textContent = modeSwitch.checked ? 'Light Mode' : 'Dark Mode';
      });
    });
  </script>
  <script>
    function confirmOldPassword() {
        var oldPassword = document.getElementById('oldPassword').value;

        $.ajax({
            type: 'POST',
            url: '<?= site_url('auth/checkOldPassword') ?>',
            data: { oldPassword: oldPassword },
            success: function(response) {
                if (response.success) {
                    $('#confirmPasswordModal').modal('hide');

                    $('#editProfileModal').modal('show');
                } else {
                    alert('Incorrect old password. Please try again.');
                }
            },
            error: function() {
                alert('Error checking old password.');
            }
        });
    }
  </script>


</body>

</html>