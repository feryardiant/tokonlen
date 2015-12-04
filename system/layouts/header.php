<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title><?php echo $heading.' - '.conf('app.title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <!-- FAVICON -->
    <link href="<?php echo siteUrl('asset/favicon.png') ?>" rel="shortcut icon">
    <!-- END FAVICON -->
    <!-- CSS -->
    <link href="<?php echo siteUrl('asset/lib/jquery-ui.css') ?>" rel="stylesheet">
    <link href="<?php echo siteUrl('asset/style.css') ?>" rel="stylesheet">
    <!-- END CSS -->
    <!-- JS -->
    <script src="<?php echo siteUrl('asset/lib/jquery.min.js') ?>"></script>
    <script src="<?php echo siteUrl('asset/lib/jquery-ui.min.js') ?>"></script>
    <script src="<?php echo siteUrl('asset/lib/jquery-validate.min.js') ?>"></script>
    <script src="<?php echo siteUrl('asset/lib/nicedit.js') ?>"></script>
    <script src="<?php echo siteUrl('asset/script.js') ?>"></script>
    <!-- END JS -->
</head>
<body <?php bodyAttrs()?> data-siteurl="<?php echo siteUrl() ?>">
    <div class="wrapper sticky-wrap">
        <div class="sticky-head">
            <header id="site-header" class="only-screen">
                <div id="brand">
                    <h3><?php echo conf('app.title') ?></h3>
                    <span><?php echo conf('app.desc') ?></span>
                </div>
                <nav id="site-nav" class="clearfix">
                    <?php echo app('main-menu')->nav('menu menu-h') ?>
                    <?php echo app('user-menu')->nav('menu menu-h menu-right') ?>
                </nav>
            </header>
            <div id="site-contents" class="clearfix">
                <header id="content-header" class="clearfix">
                    <h3 id="page-title"><?php echo $heading ?></h3>
                    <?php if (isset($toolbar)) echo '<nav id="page-tool" class="clearfix">'.$toolbar->nav('menu menu-h menu-tool').'</nav>' ?>
                </header>
                <div id="content-main" class="clearfix">
                    <?php echo showAlert() ?>
