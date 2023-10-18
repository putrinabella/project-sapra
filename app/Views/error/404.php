<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
	<meta name="author" content="NobleUI">
	<meta name="keywords" content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<title>404 Not Found</title>

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
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/css/light/style.css">
  <!-- End layout styles -->

  <link rel="shortcut icon" href="<?= base_url(); ?>/assets/images/favicon.png" />
</head>
<body>
	<div class="main-wrapper">
		<div class="page-wrapper full-page">
			<div class="page-content d-flex align-items-center justify-content-center">

				<div class="row w-100 mx-0 auth-page">
					<div class="col-md-8 col-xl-6 mx-auto d-flex flex-column align-items-center">
						<img src="<?= base_url(); ?>/assets/images/others/404.svg" class="img-fluid mb-2" alt="404">
						<h1 class="fw-bolder mb-22 mt-2 tx-80 text-muted">404</h1>
						<h4 class="mb-2">Page Not Found</h4>
						<h6 class="text-muted mb-3 text-center">Oopps!! The page you were looking for doesn't exist.</h6>
						<a href="<?= site_url('/') ?>" class="btn btn-primary">Back to home</a>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- core:js -->
	<script src="<?= base_url(); ?>/assets/vendors/core/core.js"></script>
	<!-- endinject -->

	<!-- Plugin js for this page -->
	<!-- End plugin js for this page -->

	<!-- inject:js -->
	<script src="<?= base_url(); ?>/assets/vendors/feather-icons/feather.min.js"></script>
	<script src="<?= base_url(); ?>/assets/js/template.js"></script>
	<!-- endinject -->

	<!-- Custom js for this page -->
	<!-- End custom js for this page -->

</body>
</html>