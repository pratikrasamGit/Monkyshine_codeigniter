<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= (isset($pageTitle) ? $pageTitle : 'LiebeLive Dashboard') ?></title>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="author" content="WebFxm" />
    <!-- Favicon icon -->

    <link rel="icon" href="<?= base_url() ?>app_assets/uploads/common/1588926509_favicon.png" type="image/png" sizes="16x16">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="<?= base_url() ?>app_assets/fonts/fontawesome/css/fontawesome-all.min.css" />
    <!-- animation css -->
    <link rel="stylesheet" href="<?= base_url() ?>app_assets/plugins/animation/css/animate.min.css" />
    <!-- notification css -->
    <link rel="stylesheet" href="<?= base_url() ?>app_assets/plugins/notification/css/notification.min.css" />


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<link rel="stylesheet" href="<?= base_url() ?>app_assets/fonts/material/css/materialdesignicons.min.css" />


<!-- Bootstrap datetimepicker css -->
<link rel="stylesheet" href="<?= base_url() ?>app_assets/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css" />

<!-- modal-window-effects css -->
<link rel="stylesheet" href="<?= base_url() ?>app_assets/plugins/modal-window-effects/css/md-modal.css" />
<!-- vendor css -->
<link rel="stylesheet" href="<?= base_url() ?>app_assets/css/style.css" />

<!-- Common Javascript -->

<script src="<?= base_url() ?>app_assets/js/vendor-all.min.js"></script>
<script src="<?= base_url() ?>app_assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- editor -->
<link href="<?= base_url(); ?>app_assets/plugins/wysiwyg/dist/css/wysiwyg.min.css" rel="stylesheet" />
<script src="<?= base_url(); ?>app_assets/plugins/wysiwyg/dist/js/wysiwyg.min.js"></script>
<!-- editor -->

<link rel="stylesheet" href="<?= base_url() ?>app_assets/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css" />

<body>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->